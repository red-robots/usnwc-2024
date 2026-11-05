<?php
/**
 * Race registration experience.
 *
 * Turns a multi-page Gravity Form on a race-forms post into a full-screen,
 * one-question-at-a-time registration flow. Gravity Forms still does all the
 * real work (validation, conditional logic, pricing, coupons, Stripe, entries,
 * notifications, feeds); assets/race-registration/race-reg.js only changes how
 * the fields are presented and walks the visitor through them.
 *
 * Each race gets a config file in inc/race-registration/ named
 * form-{ID}.php, returning an array (see form-629.php for the reference).
 * A form without a config file renders exactly as before.
 *
 * Switching it on:
 *  - 'enabled' => true in the config turns it on for everyone.
 *  - ?regx=1 on the registration URL previews it while 'enabled' is false.
 *  - ?regx=0 forces the classic form (handy for staff troubleshooting).
 *
 * Next year's copy: staff duplicate last year's form, which gives it a new ID. A form
 * with no config file of its own borrows the config of a configured form with the same
 * title once years and "(Copy)" are ignored ("2027 A Shot in the Dark Charlotte ..." uses
 * form-757.php), as long as most of the field IDs the config uses are still there.
 * Logged-in staff see a note on such a page, since dates, prices and art in the borrowed
 * config are last year's. Add form-{new ID}.php to give the new form its own.
 *
 * @package bellaworks
 */

/**
 * Load the experience config for a form ID, or null if there isn't one.
 *
 * @param int $form_id
 * @return array|null
 */
function bellaworks_regx_config( $form_id ) {
	$form_id = absint( $form_id );
	if ( ! $form_id ) {
		return null;
	}
	$config = bellaworks_regx_load_config_file( $form_id );
	if ( $config ) {
		$config['formId'] = $form_id;
	} else {
		$source = bellaworks_regx_inherited_source( $form_id );
		if ( ! $source ) {
			return null;
		}
		$config                  = bellaworks_regx_load_config_file( $source );
		$config['formId']        = $form_id;
		$config['inheritedFrom'] = $source;
	}

	// The child form comes from the Nested Form field itself, so a copied parent finds its own child.
	if ( ! empty( $config['nested']['field'] ) && class_exists( 'GFAPI' ) ) {
		$field = GFAPI::get_field( $form_id, $config['nested']['field'] );
		if ( $field && ! empty( $field->gpnfForm ) ) {
			$config['nested']['formId'] = (int) $field->gpnfForm;
		}
	}
	return $config;
}

/**
 * The config array in inc/race-registration/form-{ID}.php, or null.
 *
 * @param int $form_id
 * @return array|null
 */
function bellaworks_regx_load_config_file( $form_id ) {
	$file = get_template_directory() . '/inc/race-registration/form-' . absint( $form_id ) . '.php';
	if ( ! file_exists( $file ) ) {
		return null;
	}
	$config = include $file;
	return is_array( $config ) ? $config : null;
}

/**
 * A form title without years, season ranges or "(Copy 1)", for matching a duplicate to its source.
 * "2026 A Shot in the Dark Charlotte Race Registration (Registration)" and
 * "A Shot in the Dark Charlotte Race Registration (Registration) 2027 (Copy 1)" both become
 * "a shot in the dark charlotte race registration registration".
 *
 * @param string $title
 * @return string
 */
function bellaworks_regx_title_key( $title ) {
	$t = strtolower( wp_strip_all_tags( (string) $title ) );
	$t = preg_replace( '/\(\s*copy(\s*\d+)?\s*\)|\bcopy\b/', ' ', $t );
	$t = preg_replace( '/\b(?:19|20)?\d{2}\s*[-\x{2013}\/]\s*(?:19|20)?\d{2}\b/u', ' ', $t ); // 26-27, 2026-2027
	$t = preg_replace( '/\b(?:19|20)\d{2}\b/', ' ', $t );
	$t = preg_replace( '/[^a-z0-9]+/', ' ', $t );
	return trim( $t );
}

/**
 * The configured form a config-less form should borrow from, or 0.
 *
 * @param int $form_id
 * @return int
 */
function bellaworks_regx_inherited_source( $form_id ) {
	static $memo = array();
	if ( isset( $memo[ $form_id ] ) ) {
		return $memo[ $form_id ];
	}
	$memo[ $form_id ] = 0;
	if ( ! class_exists( 'GFAPI' ) ) {
		return 0;
	}
	$form = GFAPI::get_form( $form_id );
	$key  = $form ? bellaworks_regx_title_key( $form['title'] ) : '';
	if ( '' === $key ) {
		return 0;
	}
	$ids = array();
	foreach ( (array) $form['fields'] as $field ) {
		$ids[ (int) $field->id ] = true;
	}

	$best = 0;
	$best_score = 0;
	foreach ( (array) glob( get_template_directory() . '/inc/race-registration/form-*.php' ) as $file ) {
		if ( ! preg_match( '/form-(\d+)\.php$/', $file, $m ) ) {
			continue;
		}
		$source_id = (int) $m[1];
		// A duplicate always gets a higher ID; older forms (last year's pages still published) never borrow.
		if ( $source_id >= $form_id ) {
			continue;
		}
		$source = GFAPI::get_form( $source_id );
		if ( ! $source || bellaworks_regx_title_key( $source['title'] ) !== $key ) {
			continue;
		}
		// Same race by name; make sure it's still the same form underneath.
		$config = bellaworks_regx_load_config_file( $source_id );
		$wanted = array();
		foreach ( (array) ( $config['frames'] ?? array() ) as $frame ) {
			foreach ( (array) ( $frame['fields'] ?? array() ) as $id ) {
				$wanted[] = (int) $id;
			}
		}
		if ( ! $wanted ) {
			continue;
		}
		$found = 0;
		foreach ( $wanted as $id ) {
			$found += isset( $ids[ $id ] ) ? 1 : 0;
		}
		$score = $found / count( $wanted );
		if ( ! empty( $config['nested']['field'] ) ) {
			$nested = GFAPI::get_field( $form_id, $config['nested']['field'] );
			if ( ! $nested || 'form' !== $nested->type ) {
				$score = 0;
			}
		}
		// Ties go to the newest source form (last year's over the year before).
		if ( $score >= 0.75 && ( $score > $best_score || ( $score === $best_score && $source_id > $best ) ) ) {
			$best       = $source_id;
			$best_score = $score;
		}
	}
	$memo[ $form_id ] = $best;
	return $best;
}

/**
 * The experience config for the race-forms post being viewed, if it should run.
 *
 * @return array|null
 */
function bellaworks_regx_current() {
	static $resolved = false, $current = null;
	if ( $resolved ) {
		return $current;
	}
	$resolved = true;

	if ( ! is_singular( 'race-forms' ) || ! class_exists( 'GFAPI' ) ) {
		return null;
	}

	$flag = isset( $_GET['regx'] ) ? sanitize_key( wp_unslash( $_GET['regx'] ) ) : '';
	if ( '0' === $flag ) {
		return null;
	}

	$post = get_queried_object();
	if ( ! $post || empty( $post->post_content ) ) {
		return null;
	}
	if ( ! preg_match( '/\[gravityforms?\s[^\]]*\bid=["\']?(\d+)/', $post->post_content, $m ) ) {
		return null;
	}

	$config = bellaworks_regx_config( (int) $m[1] );
	if ( ! $config ) {
		return null;
	}
	if ( empty( $config['enabled'] ) && '1' !== $flag ) {
		return null;
	}

	$current = $config;
	return $current;
}

/**
 * Body classes so the stylesheet can take over the page.
 */
function bellaworks_regx_body_class( $classes ) {
	$config = bellaworks_regx_current();
	if ( $config ) {
		$classes[] = 'regx';
		$classes[] = 'regx--' . sanitize_html_class( $config['theme'] ?? 'default' );
		if ( ! empty( $config['light'] ) ) {
			$classes[] = 'regx--light'; // light background: see "light themes" in race-reg.css
		}
	}
	return $classes;
}
add_filter( 'body_class', 'bellaworks_regx_body_class' );

/**
 * Stylesheet, fonts and the flow script.
 */
function bellaworks_regx_scripts() {
	$config = bellaworks_regx_current();
	if ( ! $config ) {
		return;
	}

	$dir = get_template_directory() . '/assets/race-registration';
	$uri = get_template_directory_uri() . '/assets/race-registration';
	$ver = function ( $file ) use ( $dir ) {
		return file_exists( $dir . '/' . $file ) ? (string) filemtime( $dir . '/' . $file ) : '1.0';
	};

	if ( ! empty( $config['fonts'] ) ) {
		wp_enqueue_style( 'bellaworks-regx-fonts', $config['fonts'], array(), null );
	}
	wp_enqueue_style( 'bellaworks-regx', $uri . '/race-reg.css', array(), $ver( 'race-reg.css' ) );

	wp_enqueue_script( 'bellaworks-regx', $uri . '/race-reg.js', array( 'jquery' ), $ver( 'race-reg.js' ), true );

	$client = $config;
	unset( $client['enabled'], $client['fonts'], $client['inheritedFrom'] );
	$client['assets'] = $uri . '/' . sanitize_file_name( $config['theme'] ?? 'default' ) . '/';
	// A POST means the visitor is mid-form (GF page change or validation), so skip the welcome screen.
	$client['isPost'] = isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'];

	// ?regx_done=1 shows the success screen with the form's real confirmation text (staff only; no entry is made).
	if ( ! empty( $_GET['regx_done'] ) && current_user_can( 'edit_posts' ) ) {
		$client['previewDone'] = bellaworks_regx_confirmation_html( $config['formId'] );
	}
	wp_add_inline_script( 'bellaworks-regx', 'window.RWRegx = ' . wp_json_encode( $client ) . ';', 'before' );
}
add_action( 'wp_enqueue_scripts', 'bellaworks_regx_scripts', 20 );

/**
 * Paint the page background before the stylesheet arrives, so the page
 * reload between Gravity Forms pages doesn't flash white.
 */
function bellaworks_regx_head_paint() {
	$config = bellaworks_regx_current();
	if ( ! $config || empty( $config['colors']['bg'] ) ) {
		return;
	}
	printf(
		'<style id="regx-paint">html,body.regx{background:%s;}</style>' . "\n",
		esc_html( $config['colors']['bg'] )
	);
}
add_action( 'wp_head', 'bellaworks_regx_head_paint', 1 );

/**
 * The form's default confirmation message, for previewing the success screen.
 *
 * @param int $form_id
 * @return string
 */
function bellaworks_regx_confirmation_html( $form_id ) {
	$form = GFAPI::get_form( $form_id );
	$text = '';
	if ( $form && ! empty( $form['confirmations'] ) ) {
		foreach ( $form['confirmations'] as $confirmation ) {
			if ( ! empty( $confirmation['isDefault'] ) && 'message' === ( $confirmation['type'] ?? '' ) ) {
				$text = $confirmation['message'];
				break;
			}
		}
	}
	if ( '' === $text ) {
		$text = 'Thanks! Your registration has been received.';
	}
	return '<div class="gform_confirmation_message">' . wp_kses_post( wpautop( do_shortcode( $text ) ) ) . '</div>';
}
