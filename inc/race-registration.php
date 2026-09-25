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
	$file = get_template_directory() . '/inc/race-registration/form-' . $form_id . '.php';
	if ( ! file_exists( $file ) ) {
		return null;
	}
	$config = include $file;
	if ( ! is_array( $config ) ) {
		return null;
	}
	$config['formId'] = $form_id;
	return $config;
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
	unset( $client['enabled'], $client['fonts'] );
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
