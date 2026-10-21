<?php
/**
 * Google Ads conversions for the Whitewater account managed by Bellaworks
 * (Google Ads 101-282-7723, tag AW-18328166999).
 *
 * The base tag (gtag config for AW-18328166999) is printed site-wide by the Insert Headers
 * and Footers plugin. This file only fires the conversion event, and only when one of the
 * forms below shows its confirmation, so a conversion means a real submission.
 *
 * All of these forms use "message" confirmations, so the event is appended to that message.
 * A redirect confirmation would skip it, so keep these forms on message confirmations or move
 * the event to the redirect target.
 *
 * Race registration forms are per year: when next year's form replaces one of them, add its
 * form ID here.
 *
 * @package bellaworks
 */

/**
 * Race Registration conversion (Purchase, value = amount paid).
 */
const BELLAWORKS_GOOGLE_ADS_RACE_REGISTRATION = 'AW-18328166999/sbplCI3OgaUdENfExqNE';

/**
 * Gravity Forms form ID => conversion: send_to, and whether it is a paid registration.
 *
 * @return array
 */
function bellaworks_google_ads_form_conversions() {
	return array(
		// Leads.
		23  => array( 'send_to' => 'AW-18328166999/GikUCPP0-6QdENfExqNE' ), // Events Inquiry - Whitewater Center (/contact/events-inquiry/).
		763 => array( 'send_to' => 'AW-18328166999/x0w9CPb0-6QdENfExqNE' ), // Groups & Private Events Open House Interest Form.

		// Race registrations.
		754 => array( 'send_to' => BELLAWORKS_GOOGLE_ADS_RACE_REGISTRATION, 'registration' => true ), // 2026 Gravelanche.
		757 => array( 'send_to' => BELLAWORKS_GOOGLE_ADS_RACE_REGISTRATION, 'registration' => true ), // 2026 A Shot in the Dark Charlotte.
		652 => array( 'send_to' => BELLAWORKS_GOOGLE_ADS_RACE_REGISTRATION, 'registration' => true ), // 2026 Channel the Holidays.
		694 => array( 'send_to' => BELLAWORKS_GOOGLE_ADS_RACE_REGISTRATION, 'registration' => true ), // 2027 Rocky Road.
	);
}

/**
 * Append the conversion event to the form's confirmation message.
 *
 * The entry ID goes in as transaction_id, so Google Ads counts a resubmitted page once.
 * Registrations only count once the payment is Paid (a free registration counts at $0), so the
 * "payment is taking longer than expected" confirmation never records a conversion.
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

	$conversion = $conversions[ $form_id ];
	$params     = array(
		'send_to'        => $conversion['send_to'],
		'transaction_id' => 'gf' . $form_id . '-' . rgar( $entry, 'id' ),
	);

	if ( ! empty( $conversion['registration'] ) ) {
		$value = (float) rgar( $entry, 'payment_amount' );

		if ( 'Paid' !== rgar( $entry, 'payment_status' ) ) {
			$total = class_exists( 'GFCommon' ) ? (float) GFCommon::get_order_total( $form, $entry ) : 0;
			if ( $total > 0 ) {
				return $confirmation; // Payment still processing or not taken.
			}
			$value = 0;
		}

		$params['value']    = round( $value, 2 );
		$params['currency'] = 'USD';
	}

	$script = sprintf(
		'<script>if (typeof gtag === "function") { gtag("event", "conversion", %s); }</script>',
		wp_json_encode( $params, JSON_UNESCAPED_SLASHES )
	);

	return $confirmation . $script;
}
add_filter( 'gform_confirmation', 'bellaworks_google_ads_conversion_confirmation', 10, 3 );
