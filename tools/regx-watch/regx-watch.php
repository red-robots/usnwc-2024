<?php
/**
 * Race registration watcher.
 *
 * Watches every race form that has the full-screen registration skin turned on
 * (inc/race-registration/) and emails when entries stop coming through.
 *
 * Installed on the live droplet at ~/regx-watch/regx-watch.php (outside the web root)
 * and run hourly by the whitewaters crontab:
 *
 *   17 * * * * cd /sites/whitewater.org/files/ && /usr/local/bin/wp eval-file ~/regx-watch/regx-watch.php --quiet >> ~/regx-watch/cron.log 2>&1
 *
 * Modes (extra args after the file name):
 *   (none)   run the checks, email new alerts, send the daily digest when due
 *   report   print the checks and the digest to the terminal; no email, no state saved
 *   digest   send the digest now
 *   test     send a test email
 *
 * Checks, per form:
 *   page      the race page loads (200) with the skin's body class and the GF form on it
 *   silent    no finished entry for longer than this form's own last 4 weeks make likely,
 *             while visitors keep arriving at the page
 *   stuck     people are stepping through the form (GF page POSTs) but none finish
 *   errors    5xx responses on the form page or admin-ajax (Nested Forms modal)
 *   payments  a run of failed Stripe payments, or a payment stuck in Processing
 *   php       new PHP errors from the race registration code or GF / Nested Forms / Stripe
 *
 * Source of truth is the theme repo: tools/regx-watch/regx-watch.php. Copy it to the
 * droplet after changing it.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit;
}

class Regx_Watch {

	const RECIPIENTS   = array( 'austin@bellaworksweb.com' );
	const SITE_PATH    = '/sites/whitewater.org';
	const STATE_FILE   = '/sites/whitewater.org/regx-watch/state.json';
	const LOG_FILE     = '/sites/whitewater.org/regx-watch/watch.log';
	const GO_LIVE_UTC  = '2026-11-06 20:13:00';
	const DIGEST_HOUR  = 8;  // Site time (America/New_York).
	const RESEND_HOURS = 12; // An open alert is repeated at most this often.

	// Thresholds. Measured Nov 6, 2026: about 60% of visitors who post a GF page step finish.
	const SILENT_MIN_EXPECTED = 5;  // Entries the last 4 weeks would have expected in the silent stretch.
	const SILENT_MIN_VISITORS = 8;  // Distinct visitors to the page during that stretch.
	const STUCK_WINDOW_HOURS  = 8;
	const STUCK_MIN_POSTERS   = 6;  // Distinct people posting form steps (started at least an hour ago).
	const ERRORS_MIN_5XX      = 3;  // In the last hour.
	const FAILED_MIN_24H      = 3;
	const PROCESSING_MAX_HOURS = 3;

	private $mode;
	private $now;
	private $state;
	private $forms   = array();
	private $access  = null;
	private $alerts  = array(); // key => message, conditions true on this run.
	private $notes   = array(); // Things worth showing in the digest but not alerting on.

	public function __construct( $mode ) {
		$this->mode = $mode;
		$this->now  = time();
	}

	public function main() {
		$this->state = $this->load_state();

		if ( 'test' === $this->mode ) {
			$ok = $this->mail( 'Test email from the race form watcher', '<p>If you can read this, the watcher on whitewater.org can email you.</p>' );
			WP_CLI::log( $ok ? 'Test email sent.' : 'wp_mail() returned false.' );
			return;
		}

		if ( ! function_exists( 'bellaworks_regx_config' ) || ! class_exists( 'GFAPI' ) ) {
			$this->alerts['engine'] = 'The race registration engine or Gravity Forms is not loaded. bellaworks_regx_config() or GFAPI is missing, so no race form has its skin.';
		} else {
			$this->forms = $this->discover_forms();
			if ( ! $this->forms ) {
				$this->alerts['engine'] = 'No race form has the registration skin turned on. Either every config is off or the configs did not deploy.';
			}
			foreach ( $this->forms as &$form ) {
				$this->check_form( $form );
			}
			unset( $form );
			$this->check_ajax_errors();
		}
		$this->check_php_errors();

		if ( 'report' === $this->mode ) {
			$this->print_report();
			return;
		}

		$this->send_alerts();
		if ( 'digest' === $this->mode || $this->digest_due() ) {
			$this->send_digest();
		}
		$this->save_state();
	}

	/* ------------------------------------------------------------------ forms */

	/**
	 * Every published race-forms page whose form has an enabled skin config (own or inherited).
	 */
	private function discover_forms() {
		$found = array();
		$posts = get_posts(
			array(
				'post_type'   => 'race-forms',
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);
		foreach ( $posts as $post ) {
			if ( ! preg_match( '/\[gravityforms?\s[^\]]*\bid=["\']?(\d+)/', $post->post_content, $m ) ) {
				continue;
			}
			$form_id = (int) $m[1];
			$config  = bellaworks_regx_config( $form_id );
			if ( ! $config || empty( $config['enabled'] ) ) {
				continue;
			}
			$gf = GFAPI::get_form( $form_id );
			if ( ! $gf ) {
				continue;
			}
			$closed = '';
			if ( empty( $gf['is_active'] ) ) {
				$closed = 'form is inactive';
			} elseif ( method_exists( 'GFFormDisplay', 'validate_form_schedule' ) && GFFormDisplay::validate_form_schedule( $gf ) ) {
				$closed = 'outside its schedule';
			} elseif ( method_exists( 'GFFormDisplay', 'validate_entry_limit' ) && GFFormDisplay::validate_entry_limit( $gf ) ) {
				$closed = 'entry limit reached';
			}
			$key           = $form_id . '|' . $post->ID;
			$found[ $key ] = array(
				'id'        => $form_id,
				'title'     => $gf['title'],
				'url'       => get_permalink( $post ),
				'path'      => wp_parse_url( get_permalink( $post ), PHP_URL_PATH ),
				'theme'     => isset( $config['theme'] ) ? $config['theme'] : '',
				'inherited' => isset( $config['inheritedFrom'] ) ? (int) $config['inheritedFrom'] : 0,
				'closed'    => $closed,
				'created'   => strtotime( $gf['date_created'] . ' UTC' ),
			);
		}
		return $found;
	}

	private function check_form( &$form ) {
		$id    = $form['id'];
		$label = $this->label( $form );

		$entries          = $this->entry_times( $id, 29 );
		$form['last']     = $this->last_entry_time( $id );
		$form['entries']  = $entries;
		$form['traffic']  = $this->traffic( $form['path'] );
		$form['payments'] = $this->payments( $id );

		// Page loads with the skin.
		$page = $this->fetch_page( $form['url'] );
		$form['page'] = $page;
		if ( $page['problem'] ) {
			$this->alerts[ "page:$id" ] = "$label: the race page has a problem: {$page['problem']}. {$form['url']}";
		}

		// Server errors on the page itself.
		$hour_5xx = $form['traffic']['5xx_1h'];
		if ( $hour_5xx >= self::ERRORS_MIN_5XX ) {
			$this->alerts[ "5xx:$id" ] = "$label: $hour_5xx server errors (5xx) on the race page in the last hour.";
		}

		// Payments.
		$p = $form['payments'];
		if ( $p['failed_24h'] >= self::FAILED_MIN_24H && $p['failed_24h'] >= $p['paid_24h'] ) {
			$this->alerts[ "failed:$id" ] = "$label: {$p['failed_24h']} failed payments and {$p['paid_24h']} paid in the last 24 hours.";
		}
		if ( $p['stuck_processing'] ) {
			$this->alerts[ "processing:$id" ] = "$label: {$p['stuck_processing']} payment(s) have been Processing for more than " . self::PROCESSING_MAX_HOURS . ' hours.';
		}

		if ( $form['closed'] ) {
			$this->notes[] = "$label is not taking entries ($form[closed]); silence checks skipped.";
			return;
		}

		// Silent: no finished entry for longer than this form's history makes likely, while people visit.
		$since    = max( $form['last'], $form['created'] );
		$silent_h = ( $this->now - $since ) / 3600;
		$expected = $this->expected_entries( $entries, $since, $this->now );
		$visitors = $this->distinct_since( $form['traffic']['visits'], $since );
		$form['silent'] = compact( 'silent_h', 'expected', 'visitors' );
		if ( $expected >= self::SILENT_MIN_EXPECTED && $visitors >= self::SILENT_MIN_VISITORS ) {
			$this->alerts[ "silent:$id" ] = sprintf(
				'%s: no new entry for %s. The last 4 weeks would have expected about %d in that time, and %d people visited the page.',
				$label,
				$this->hours( $silent_h ),
				round( $expected ),
				$visitors
			);
		}

		// Stuck: people post form steps but nobody finishes.
		$window_start = $this->now - self::STUCK_WINDOW_HOURS * 3600;
		$posters      = $this->distinct_started( $form['traffic']['posts'], $window_start, $this->now - 3600 );
		$finished     = $this->count_since( $entries, $window_start );
		$form['stuck'] = compact( 'posters', 'finished' );
		if ( $posters >= self::STUCK_MIN_POSTERS && 0 === $finished ) {
			$this->alerts[ "stuck:$id" ] = sprintf(
				'%s: %d people stepped through the form in the last %d hours and none of them finished. Normally about 60%% do.',
				$label,
				$posters,
				self::STUCK_WINDOW_HOURS
			);
		}
	}

	/* --------------------------------------------------------------- entries */

	private function entry_times( $form_id, $days ) {
		global $wpdb;
		$table = GFFormsModel::get_entry_table_name();
		$rows  = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT date_created FROM $table WHERE form_id = %d AND status = 'active' AND date_created > UTC_TIMESTAMP() - INTERVAL %d DAY",
				$form_id,
				$days
			)
		);
		return array_map( array( $this, 'utc' ), $rows );
	}

	private function last_entry_time( $form_id ) {
		global $wpdb;
		$table = GFFormsModel::get_entry_table_name();
		$last  = $wpdb->get_var( $wpdb->prepare( "SELECT MAX(date_created) FROM $table WHERE form_id = %d AND status = 'active'", $form_id ) );
		return $last ? $this->utc( $last ) : 0;
	}

	/**
	 * Average entries in the same stretch of time (same hours, same weekday mix) on each of the last 28 days.
	 */
	private function expected_entries( $times, $from, $to ) {
		$from  = max( $from, $to - 28 * DAY_IN_SECONDS );
		$total = 0;
		for ( $d = 1; $d <= 28; $d++ ) {
			$a = $from - $d * DAY_IN_SECONDS;
			$b = $to - $d * DAY_IN_SECONDS;
			foreach ( $times as $t ) {
				if ( $t >= $a && $t < $b && $t < $from ) {
					$total++;
				}
			}
		}
		return $total / 28;
	}

	private function payments( $form_id ) {
		global $wpdb;
		$table = GFFormsModel::get_entry_table_name();
		$rows  = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT payment_status, date_created FROM $table WHERE form_id = %d AND status IN ('active','trash') AND date_created > UTC_TIMESTAMP() - INTERVAL 7 DAY",
				$form_id
			)
		);
		$out = array(
			'paid_24h'         => 0,
			'failed_24h'       => 0,
			'paid_7d'          => 0,
			'failed_7d'        => 0,
			'stuck_processing' => 0,
		);
		foreach ( $rows as $r ) {
			$t   = $this->utc( $r->date_created );
			$day = $t > $this->now - DAY_IN_SECONDS;
			if ( 'Paid' === $r->payment_status ) {
				$out['paid_7d']++;
				$out['paid_24h'] += $day ? 1 : 0;
			} elseif ( 'Failed' === $r->payment_status ) {
				$out['failed_7d']++;
				$out['failed_24h'] += $day ? 1 : 0;
			} elseif ( 'Processing' === $r->payment_status && $t < $this->now - self::PROCESSING_MAX_HOURS * 3600 && $t > $this->now - DAY_IN_SECONDS ) {
				$out['stuck_processing']++;
			}
		}
		return $out;
	}

	/* ------------------------------------------------------------ access log */

	/**
	 * Visits (GET 200 from people) and form-step POSTs per path, from today's and yesterday's access log.
	 */
	private function traffic( $path ) {
		$log = $this->access_log();
		return isset( $log[ $path ] ) ? $log[ $path ] : array(
			'visits' => array(),
			'posts'  => array(),
			'5xx_1h' => 0,
			'5xx_24h' => 0,
		);
	}

	private function access_log() {
		if ( null !== $this->access ) {
			return $this->access;
		}
		$this->access = array( '__ajax' => array( '5xx_1h' => 0, '5xx_24h' => 0 ) );
		$paths        = array();
		foreach ( $this->forms as $f ) {
			$paths[ $f['path'] ] = true;
		}
		$files = glob( self::SITE_PATH . '/logs/access.log-*.gz' );
		sort( $files );
		$read = array();
		if ( $files ) {
			$read[] = 'compress.zlib://' . end( $files );
		}
		$read[] = self::SITE_PATH . '/logs/access.log';
		$cut    = $this->now - DAY_IN_SECONDS;
		$bots   = '/bot|crawl|spider|slurp|facebookexternalhit|preview|monitor|curl|wget|python|go-http|wordpress|regx-watch|headless|lighthouse/i';

		foreach ( $read as $file ) {
			$fh = @fopen( $file, 'r' );
			if ( ! $fh ) {
				continue;
			}
			while ( ( $line = fgets( $fh ) ) !== false ) {
				if ( false === strpos( $line, '/race-forms/' ) && false === strpos( $line, 'admin-ajax' ) ) {
					continue;
				}
				if ( ! preg_match( '/^(\S+) \S+ \S+ \[([^\]]+)\] "(\w+) (\S+) [^"]*" (\d{3}) \S+ "[^"]*" "([^"]*)"/', $line, $m ) ) {
					continue;
				}
				$t = strtotime( str_replace( '/', ' ', preg_replace( '/:/', ' ', $m[2], 1 ) ) );
				if ( ! $t || $t < $cut ) {
					continue;
				}
				list( , $ip, , $method, $uri, $status, $ua ) = $m;
				$status = (int) $status;
				$p      = strtok( $uri, '?' );

				if ( false !== strpos( $p, 'admin-ajax' ) ) {
					if ( $status >= 500 ) {
						$this->access['__ajax']['5xx_24h']++;
						$this->access['__ajax']['5xx_1h'] += $t > $this->now - 3600 ? 1 : 0;
					}
					continue;
				}
				if ( ! isset( $paths[ $p ] ) ) {
					continue;
				}
				if ( ! isset( $this->access[ $p ] ) ) {
					$this->access[ $p ] = array(
						'visits'  => array(),
						'posts'   => array(),
						'5xx_1h'  => 0,
						'5xx_24h' => 0,
					);
				}
				$row = &$this->access[ $p ];
				if ( $status >= 500 ) {
					$row['5xx_24h']++;
					$row['5xx_1h'] += $t > $this->now - 3600 ? 1 : 0;
				}
				if ( preg_match( $bots, $ua ) ) {
					unset( $row );
					continue;
				}
				$who = $ip . '|' . $ua;
				if ( 'GET' === $method && 200 === $status ) {
					$row['visits'][ $who ][] = $t;
				} elseif ( 'POST' === $method ) {
					$row['posts'][ $who ][] = $t;
				}
				unset( $row );
			}
			fclose( $fh );
		}
		return $this->access;
	}

	private function distinct_since( $hits, $since ) {
		$n = 0;
		foreach ( $hits as $times ) {
			if ( max( $times ) >= $since ) {
				$n++;
			}
		}
		return $n;
	}

	/** People whose first POST falls in [from, until): they've had at least an hour to finish. */
	private function distinct_started( $hits, $from, $until ) {
		$n = 0;
		foreach ( $hits as $times ) {
			$first = min( $times );
			if ( $first >= $from && $first < $until ) {
				$n++;
			}
		}
		return $n;
	}

	private function count_since( $times, $since ) {
		$n = 0;
		foreach ( $times as $t ) {
			$n += $t >= $since ? 1 : 0;
		}
		return $n;
	}

	private function check_ajax_errors() {
		$log = $this->access_log();
		$n   = $log['__ajax']['5xx_1h'];
		if ( $n >= self::ERRORS_MIN_5XX ) {
			$this->alerts['5xx:ajax'] = "admin-ajax.php returned $n server errors (5xx) in the last hour. The Nested Forms runner modal uses it.";
		}
	}

	/* ------------------------------------------------------------------ page */

	private function fetch_page( $url ) {
		$res = wp_remote_get(
			add_query_arg( 'rxwatch', $this->now, $url ),
			array(
				'timeout'    => 20,
				'user-agent' => 'regx-watch (whitewater.org race form watcher)',
				'sslverify'  => true,
			)
		);
		$out = array(
			'code'    => 0,
			'ms'      => 0,
			'problem' => '',
		);
		if ( is_wp_error( $res ) ) {
			$out['problem'] = 'request failed (' . $res->get_error_message() . ')';
			return $out;
		}
		$code        = (int) wp_remote_retrieve_response_code( $res );
		$body        = wp_remote_retrieve_body( $res );
		$out['code'] = $code;
		if ( 200 !== $code ) {
			$out['problem'] = "it returned HTTP $code";
		} elseif ( false === strpos( $body, 'regx--' ) ) {
			$out['problem'] = 'the registration skin is not on the page (no regx body class), so visitors get the classic form';
		} elseif ( false === strpos( $body, 'gform_wrapper' ) && false === strpos( $body, 'gform_' ) ) {
			$out['problem'] = 'the Gravity Form is missing from the page';
		} elseif ( false !== stripos( $body, 'There has been a critical error' ) ) {
			$out['problem'] = 'WordPress shows a critical error';
		}
		return $out;
	}

	/* ------------------------------------------------------------- php errors */

	private function check_php_errors() {
		$file = self::SITE_PATH . '/logs/debug.log';
		if ( ! is_readable( $file ) ) {
			return;
		}
		clearstatcache( true, $file );
		$size   = filesize( $file );
		$inode  = fileinode( $file );
		$offset = isset( $this->state['debug']['offset'] ) ? (int) $this->state['debug']['offset'] : $size;
		if ( empty( $this->state['debug']['inode'] ) || $this->state['debug']['inode'] !== $inode || $offset > $size ) {
			$offset = isset( $this->state['debug']['inode'] ) ? 0 : $size; // First run: start at the end.
		}

		$hits = array();
		$fh   = fopen( $file, 'r' );
		if ( $fh ) {
			fseek( $fh, $offset );
			while ( ( $line = fgets( $fh ) ) !== false ) {
				$ours = preg_match( '#race-registration|/gravityforms|/gp-nested-forms|/gravityformsstripe|/gp-inventory|/gravityperks#', $line );
				if ( ! $ours ) {
					continue;
				}
				if ( preg_match( '/PHP (Fatal|Parse|Warning|Notice|Deprecated)/', $line, $lvl ) ) {
					if ( in_array( $lvl[1], array( 'Deprecated', 'Notice' ), true ) ) {
						continue;
					}
					$msg          = trim( preg_replace( '/^\[[^\]]+\]\s*/', '', $line ) );
					$msg          = substr( $msg, 0, 300 );
					$hits[ $msg ] = isset( $hits[ $msg ] ) ? $hits[ $msg ] + 1 : 1;
				}
			}
			fclose( $fh );
		}
		$this->state['debug'] = array(
			'offset' => $size,
			'inode'  => $inode,
		);

		foreach ( $hits as $msg => $n ) {
			$severe = false !== strpos( $msg, 'Fatal' ) || false !== strpos( $msg, 'Parse' ) || false !== strpos( $msg, 'race-registration' );
			$line   = ( $n > 1 ? "$n× " : '' ) . $msg;
			if ( $severe ) {
				$this->alerts[ 'php:' . md5( $msg ) ] = 'New PHP error: ' . $line;
			} else {
				$this->notes[] = 'PHP warning: ' . $line;
			}
		}
	}

	/* --------------------------------------------------------------- sending */

	private function send_alerts() {
		$open = isset( $this->state['alerts'] ) ? $this->state['alerts'] : array();
		$send = array();

		foreach ( $this->alerts as $key => $msg ) {
			$php = 0 === strpos( $key, 'php:' ); // Log lines are new each time, so never repeat them.
			if ( ! isset( $open[ $key ] ) ) {
				$open[ $key ] = array(
					'first' => $this->now,
					'sent'  => 0,
				);
			}
			$open[ $key ]['msg']  = $msg;
			$open[ $key ]['seen'] = $this->now;
			if ( ! $open[ $key ]['sent'] || ( ! $php && $this->now - $open[ $key ]['sent'] >= self::RESEND_HOURS * 3600 ) ) {
				$send[]               = $open[ $key ]['sent'] ? $msg . ' (still happening since ' . $this->local( $open[ $key ]['first'] ) . ')' : $msg;
				$open[ $key ]['sent'] = $this->now;
			}
		}

		$cleared = array();
		foreach ( $open as $key => $a ) {
			if ( isset( $this->alerts[ $key ] ) ) {
				continue;
			}
			if ( 0 !== strpos( $key, 'php:' ) ) {
				$cleared[] = 'Cleared: ' . $a['msg'];
			}
			unset( $open[ $key ] );
		}
		$this->state['alerts'] = $open;

		foreach ( array_merge( $send, $cleared ) as $m ) {
			$this->log( $m );
		}
		if ( ! $send && ! $cleared ) {
			$this->log( 'OK: ' . count( $this->forms ) . ' forms checked, no new alerts.' );
			return;
		}

		$subject = $send ? 'Race forms need a look: ' . $this->short( $send[0] ) : 'Race forms back to normal';
		$html    = '';
		if ( $send ) {
			$html .= '<p><strong>Problem' . ( count( $send ) > 1 ? 's' : '' ) . ':</strong></p><ul>';
			foreach ( $send as $m ) {
				$html .= '<li>' . esc_html( $m ) . '</li>';
			}
			$html .= '</ul>';
		}
		if ( $cleared ) {
			$html .= '<p><strong>Back to normal:</strong></p><ul>';
			foreach ( $cleared as $m ) {
				$html .= '<li>' . esc_html( substr( $m, 9 ) ) . '</li>';
			}
			$html .= '</ul>';
		}
		$html .= '<p style="color:#666">Classic-form fallback for any race: add <code>?regx=0</code> to its URL, or set <code>\'enabled\' =&gt; false</code> in its <code>inc/race-registration/form-{ID}.php</code>.</p>';
		$html .= $this->digest_table();
		$this->mail( $subject, $html );
	}

	private function digest_due() {
		$tz    = wp_timezone();
		$local = new DateTime( '@' . $this->now );
		$local->setTimezone( $tz );
		$today = $local->format( 'Y-m-d' );
		$last  = isset( $this->state['digest'] ) ? $this->state['digest'] : '';
		return (int) $local->format( 'G' ) >= self::DIGEST_HOUR && $last !== $today;
	}

	private function send_digest() {
		$local = new DateTime( '@' . $this->now );
		$local->setTimezone( wp_timezone() );
		$this->state['digest'] = $local->format( 'Y-m-d' );

		$open = count( $this->alerts );
		$html = '<p>' . ( $open ? "<strong>$open open problem" . ( $open > 1 ? 's' : '' ) . '.</strong>' : 'All race forms look normal.' ) . '</p>';
		if ( $this->alerts ) {
			$html .= '<ul>';
			foreach ( $this->alerts as $m ) {
				$html .= '<li>' . esc_html( $m ) . '</li>';
			}
			$html .= '</ul>';
		}
		$html .= $this->digest_table();
		if ( $this->notes ) {
			$html .= '<p style="color:#666">Notes:</p><ul style="color:#666">';
			foreach ( array_unique( $this->notes ) as $n ) {
				$html .= '<li>' . esc_html( $n ) . '</li>';
			}
			$html .= '</ul>';
		}
		$subject = 'Race forms daily: ' . ( $open ? "$open problem" . ( $open > 1 ? 's' : '' ) : 'all normal' ) . ', ' . $this->entries_today_total() . ' entries in the last 24h';
		$this->mail( $subject, $html );
		$this->log( 'Digest sent.' );
	}

	private function digest_table() {
		$go = $this->utc( self::GO_LIVE_UTC );
		$h  = '<table cellpadding="6" cellspacing="0" style="border-collapse:collapse;font:13px/1.4 -apple-system,Segoe UI,sans-serif">';
		$h .= '<tr style="background:#f2f2f2;text-align:left"><th>Form</th><th>24h</th><th>7 days</th><th>Prior 7</th><th>Last entry</th><th>Finish rate 24h</th><th>Paid / failed 7d</th><th>Page</th></tr>';
		foreach ( $this->forms as $f ) {
			$e       = $f['entries'];
			$d1      = $this->count_since( $e, $this->now - DAY_IN_SECONDS );
			$d7      = $this->count_since( $e, $this->now - 7 * DAY_IN_SECONDS );
			$prior7  = $this->count_since( $e, $this->now - 14 * DAY_IN_SECONDS ) - $d7;
			$posters = $this->distinct_started( $f['traffic']['posts'], $this->now - DAY_IN_SECONDS, $this->now - 3600 );
			$fin     = $this->count_since( $e, $this->now - DAY_IN_SECONDS );
			$rate    = $posters ? round( 100 * min( $fin, $posters ) / $posters ) . "% ($fin of $posters)" : '—';
			$page    = $f['page']['problem'] ? '<span style="color:#b00">problem</span>' : 'OK';
			$last    = $f['last'] ? $this->hours( ( $this->now - $f['last'] ) / 3600 ) . ' ago' : 'never';
			$name    = esc_html( $this->label( $f ) ) . ( $f['closed'] ? ' <span style="color:#888">(closed)</span>' : '' );
			$h      .= '<tr style="border-top:1px solid #ddd"><td><a href="' . esc_url( $f['url'] ) . '">' . $name . '</a></td>'
				. "<td>$d1</td><td>$d7</td><td>$prior7</td><td>$last</td><td>$rate</td><td>{$f['payments']['paid_7d']} / {$f['payments']['failed_7d']}</td><td>$page</td></tr>";
		}
		$h .= '</table>';
		$h .= '<p style="color:#666;font-size:12px">Skin went live ' . esc_html( $this->local( $go ) ) . '. "Finish rate" = entries ÷ people who started stepping through the form; about 60% was normal the week before go-live.</p>';
		return $h;
	}

	private function entries_today_total() {
		$n = 0;
		foreach ( $this->forms as $f ) {
			$n += $this->count_since( $f['entries'], $this->now - DAY_IN_SECONDS );
		}
		return $n;
	}

	private function mail( $subject, $html ) {
		$body = '<div style="font:14px/1.5 -apple-system,Segoe UI,sans-serif;color:#222">' . $html
			. '<p style="color:#999;font-size:12px">Sent by ~/regx-watch on the whitewater.org droplet (source: theme repo tools/regx-watch/).</p></div>';
		return wp_mail( self::RECIPIENTS, '[whitewater.org] ' . $subject, $body, array( 'Content-Type: text/html; charset=UTF-8' ) );
	}

	/* ---------------------------------------------------------------- report */

	private function print_report() {
		WP_CLI::log( 'Forms watched: ' . count( $this->forms ) );
		foreach ( $this->forms as $f ) {
			$s = isset( $f['silent'] ) ? $f['silent'] : array();
			$k = isset( $f['stuck'] ) ? $f['stuck'] : array();
			WP_CLI::log(
				sprintf(
					'  %-46s page=%s%s  last=%s ago  expected-in-gap=%s  visitors-in-gap=%s  posters-8h=%s finished-8h=%s  paid/failed-24h=%d/%d  5xx-24h=%d%s',
					$this->label( $f ),
					$f['page']['code'],
					$f['page']['problem'] ? ' (' . $f['page']['problem'] . ')' : '',
					$f['last'] ? $this->hours( ( $this->now - $f['last'] ) / 3600 ) : 'never',
					isset( $s['expected'] ) ? round( $s['expected'], 1 ) : '-',
					isset( $s['visitors'] ) ? $s['visitors'] : '-',
					isset( $k['posters'] ) ? $k['posters'] : '-',
					isset( $k['finished'] ) ? $k['finished'] : '-',
					$f['payments']['paid_24h'],
					$f['payments']['failed_24h'],
					$f['traffic']['5xx_24h'],
					$f['closed'] ? "  CLOSED ($f[closed])" : ''
				)
			);
		}
		$log = $this->access_log();
		WP_CLI::log( 'admin-ajax 5xx in 24h: ' . $log['__ajax']['5xx_24h'] );
		WP_CLI::log( $this->alerts ? "Alerts:\n  - " . implode( "\n  - ", $this->alerts ) : 'Alerts: none' );
		if ( $this->notes ) {
			WP_CLI::log( "Notes:\n  - " . implode( "\n  - ", array_unique( $this->notes ) ) );
		}
	}

	/* --------------------------------------------------------------- helpers */

	private function label( $f ) {
		$title = preg_replace( '/\s*\((Registration|Copy)[^)]*\)?/i', '', $f['title'] );
		return $f['id'] . ' ' . trim( $title );
	}

	private function utc( $mysql ) {
		return strtotime( $mysql . ' UTC' );
	}

	private function local( $ts ) {
		return wp_date( 'D M j, g:ia', $ts );
	}

	private function hours( $h ) {
		if ( $h < 1 ) {
			return round( $h * 60 ) . ' min';
		}
		if ( $h < 48 ) {
			return round( $h, 1 ) . ' h';
		}
		return round( $h / 24, 1 ) . ' days';
	}

	private function short( $s ) {
		return strlen( $s ) > 90 ? substr( $s, 0, 87 ) . '…' : $s;
	}

	private function load_state() {
		if ( is_readable( self::STATE_FILE ) ) {
			$s = json_decode( (string) file_get_contents( self::STATE_FILE ), true );
			if ( is_array( $s ) ) {
				return $s;
			}
		}
		return array();
	}

	private function save_state() {
		$dir = dirname( self::STATE_FILE );
		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}
		file_put_contents( self::STATE_FILE, wp_json_encode( $this->state, JSON_PRETTY_PRINT ) );
	}

	private function log( $msg ) {
		$line = '[' . wp_date( 'Y-m-d H:i:s' ) . '] ' . $msg . "\n";
		@file_put_contents( self::LOG_FILE, $line, FILE_APPEND );
		if ( 'report' !== $this->mode ) {
			WP_CLI::log( trim( $line ) );
		}
	}
}

$regx_watch = new Regx_Watch( isset( $args[0] ) ? (string) $args[0] : 'run' );
$regx_watch->main();
