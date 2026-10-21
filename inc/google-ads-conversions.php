<?php
/**
 * Google Ads lead conversions for the Whitewater account managed by Bellaworks
 * (Google Ads 101-282-7723, tag AW-18328166999).
 *
 * The base tag (gtag config for AW-18328166999) is printed site-wide by the Insert Headers
 * and Footers plugin. This file only fires the conversion event, and only when one of the
 * forms below shows its confirmation, so a conversion means a real submission.
 *
 * Both forms use a "message" confirmation, so the event is appended to that message. A
 * redirect confirmation would skip it, so keep these forms on message confirmations or move
 * the event to the redirect target.
 *
 * @package bellaworks
 */

/**
 * Gravity Forms form ID => Google Ads conversion send_to.
 *
 * @return array
 */
function bellaworks_google_ads_form_conversions() {
	return array(
		23  => 'AW-18328166999/GikUCPP0-6QdENfExqNE', // Events Inquiry - Whitewater Center (/contact/events-inquiry/).
		763 => 'AW-18328166999/x0w9CPb0-6QdENfExqNE', // Groups & Private Events Open House Interest Form.
	);
}

/**
 * Append the conversion event to the form's confirmation message.
 *
 * The entry ID goes in as transaction_id, so Google Ads counts a resubmitted page once.
 *
 * @param string|array $confirmation Confirmation message, or redirect array.
 * @param array        $form         The form.
 * @param array        $entry        The entry.
 * @return string|array
 */
function bellaworks_google_ads_conversion_confirmation( $confirmation, $form, $entry ) {
	$conversions = bellaworks_google_ads_form_conversions();
	$form_id     = isset( $form['id'] ) ? (int) $form['id'] : 0;

	if ( ! isset( $conversions[ $form_id ] ) || ! is_string( $confirmation ) ) {
		return $confirmation;
	}

	$params = array(
		'send_to'        => $conversions[ $form_id ],
		'transaction_id' => 'gf' . $form_id . '-' . rgar( $entry, 'id' ),
	);

	$script = sprintf(
		'<script>if (typeof gtag === "function") { gtag("event", "conversion", %s); }</script>',
		wp_json_encode( $params, JSON_UNESCAPED_SLASHES )
	);

	return $confirmation . $script;
}
add_filter( 'gform_confirmation', 'bellaworks_google_ads_conversion_confirmation', 10, 3 );
