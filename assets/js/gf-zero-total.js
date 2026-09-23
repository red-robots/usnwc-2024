/**
 * Gravity Forms: hide payment fields when the order total is $0.
 *
 * Replaces the "hide Credit Card / Billing Address if Total < 1" conditional
 * logic that used to live on the race registration forms. Gravity Forms 3.x
 * loops forever when conditional logic is based on a Total field, so this
 * toggles the fields directly from the product-total filter instead.
 *
 * Stripe's frontend script checks gformIsHidden() on the card field before it
 * talks to Stripe, so hiding the field container is enough to skip payment.
 *
 * Fields are tagged server-side with the .bw-zero-total-hide class
 * (see inc/gf-zero-total.php).
 */
( function ( $ ) {
	'use strict';

	if ( typeof window.gform === 'undefined' || typeof window.gform.addFilter !== 'function' ) {
		return;
	}

	var FLAG = 'bwZeroTotalHidden';

	function toggle( formId, total ) {
		var hide    = parseFloat( total ) < 1,
			$fields = $( '#gform_' + formId + ' .gfield.bw-zero-total-hide' );

		if ( ! $fields.length ) {
			return;
		}

		$fields.each( function () {
			var $field = $( this );

			if ( hide ) {
				if ( $field.css( 'display' ) !== 'none' ) {
					$field.hide().data( FLAG, true );
				}
			} else if ( $field.data( FLAG ) ) {
				// Only re-show fields that this script hid, so other conditional logic still wins.
				$field.show().data( FLAG, false );
			}
		} );
	}

	// Runs every time the products module recalculates (product change, coupon apply/remove, page load).
	// Priority 100: after the Coupons add-on (50) and Stripe (51) have adjusted the total.
	window.gform.addFilter( 'gform_product_total', function ( total, formId ) {
		toggle( formId, total );
		return total;
	}, 100 );

	// Initial render: the 3.x products module only fires the filter above when a product changes,
	// so read the server-rendered total once (it is rendered as "$0.00" style text).
	$( document ).on( 'gform_post_render', function ( event, formId ) {
		var $total = $( '#gform_' + formId + ' .gfield--type-total input, #gform_' + formId + ' .ginput_container_total input' ).first(),
			value  = $total.length ? $total.val() : '',
			number;

		if ( value === '' ) {
			return;
		}

		number = typeof window.gformToNumber === 'function' ? window.gformToNumber( value ) : parseFloat( String( value ).replace( /[^0-9.\-]/g, '' ) );
		if ( number === false || isNaN( number ) ) {
			number = parseFloat( String( value ).replace( /[^0-9.\-]/g, '' ) ) || 0;
		}

		toggle( formId, number );
	} );
} )( jQuery );
