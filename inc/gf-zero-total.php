<?php
/**
 * Gravity Forms: hide payment fields when the order total is $0.
 *
 * The race registration forms used to hide the Stripe Credit Card and Billing
 * Address fields with conditional logic based on the Total field, so that
 * volunteer / 100% coupon registrations needed no card. Gravity Forms 3.x
 * loops forever on Total-based conditional logic (the page freezes on load),
 * and Gravity support confirmed the pattern is unsupported.
 *
 * This file recreates that behaviour outside the conditional-logic engine:
 *  - tags the payment fields with a CSS class,
 *  - loads assets/js/gf-zero-total.js, which hides/shows them from the
 *    product-total filter,
 *  - waives "required" validation on those fields when the order total is $0
 *    (fields hidden by conditional logic used to skip validation automatically).
 *
 * Applies to: Stripe Card fields, and Address fields whose label contains "Billing".
 *
 * @package bellaworks
 */

/**
 * Is this a field that should disappear when the total is $0?
 *
 * @param GF_Field|mixed $field
 * @return bool
 */
function bellaworks_gf_is_zero_total_payment_field( $field ) {
	if ( ! is_object( $field ) || empty( $field->type ) ) {
		return false;
	}
	if ( 'stripe_creditcard' === $field->type ) {
		return true;
	}
	if ( 'address' === $field->type && false !== stripos( (string) $field->label, 'billing' ) ) {
		return true;
	}
	return false;
}

/**
 * Tag the payment fields so the JS can find them.
 */
function bellaworks_gf_zero_total_field_class( $classes, $field, $form ) {
	if ( bellaworks_gf_is_zero_total_payment_field( $field ) ) {
		$classes .= ' bw-zero-total-hide';
	}
	return $classes;
}
add_filter( 'gform_field_css_class', 'bellaworks_gf_zero_total_field_class', 10, 3 );

/**
 * Load the toggle script only on forms that have a Stripe Card field.
 */
function bellaworks_gf_zero_total_scripts( $form, $is_ajax ) {
	if ( empty( $form['fields'] ) ) {
		return;
	}
	foreach ( $form['fields'] as $field ) {
		if ( is_object( $field ) && 'stripe_creditcard' === $field->type ) {
			$path = get_template_directory() . '/assets/js/gf-zero-total.js';
			wp_enqueue_script(
				'bellaworks-gf-zero-total',
				get_template_directory_uri() . '/assets/js/gf-zero-total.js',
				array( 'jquery', 'gform_gravityforms' ),
				file_exists( $path ) ? (string) filemtime( $path ) : '1.0',
				true
			);
			return;
		}
	}
}
add_action( 'gform_enqueue_scripts', 'bellaworks_gf_zero_total_scripts', 10, 2 );

/**
 * A hidden payment field must not block submission of a $0 order.
 */
function bellaworks_gf_zero_total_validation( $result, $value, $form, $field ) {
	if ( ! empty( $result['is_valid'] ) ) {
		return $result;
	}
	if ( ! bellaworks_gf_is_zero_total_payment_field( $field ) ) {
		return $result;
	}
	if ( ! class_exists( 'GFCommon' ) || ! class_exists( 'GFFormsModel' ) ) {
		return $result;
	}

	$entry = GFFormsModel::get_current_lead();
	if ( empty( $entry ) ) {
		return $result;
	}

	$total = GFCommon::get_order_total( $form, $entry );
	if ( (float) $total < 1 ) {
		$result['is_valid'] = true;
		$result['message']  = '';
	}
	return $result;
}
add_filter( 'gform_field_validation', 'bellaworks_gf_zero_total_validation', 20, 4 );
