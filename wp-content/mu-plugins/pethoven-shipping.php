<?php
/**
 * Pethoven Shipping — geographic delivery restriction.
 *
 * Plugin Name: Pethoven Shipping
 * Description: Restricts WooCommerce checkout to the 6 US states we
 *              currently deliver to. Displays a delivery-region notice
 *              on product pages, cart, and checkout. Single source of
 *              truth for the allowed states list — edit the array
 *              below to expand coverage.
 *
 * Enforcement layers
 * ------------------
 *   - woocommerce_countries_allowed_countries  → US only (selling)
 *   - woocommerce_countries_shipping_countries → US only (shipping)
 *   - woocommerce_states                       → trims US state list
 *     to the allowed 6 (state dropdown on checkout shows nothing else)
 *   - woocommerce_checkout_process             → server-side validation
 *     belt-and-braces in case a client bypasses the dropdown
 *
 * Display layers
 * --------------
 *   - Product page: notice above the Add-to-Cart button
 *   - Cart page:    notice above the cart table
 *   - Checkout:     notice above the form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Single source of truth — the 6 states we currently deliver to.
 * 2-letter USPS codes matching WooCommerce's internal `US` state keys.
 */
function pethoven_delivery_states() {
	return array( 'NY', 'NJ', 'MA', 'DC', 'CT', 'PA' );
}

/**
 * Human-readable list for display copy.
 *   ['NY','NJ','MA','DC','CT','PA'] → "NY · NJ · MA · DC · CT · PA"
 */
function pethoven_delivery_states_inline() {
	return implode( ' · ', pethoven_delivery_states() );
}

/* ----------------------------------------------------------------------
 * 1. Country restriction — US only for both selling and shipping.
 *
 *    These filters override whatever WC → Settings → General has
 *    configured, so we don't depend on admin clicking through.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_countries_allowed_countries', 'pethoven_us_only' );
add_filter( 'woocommerce_countries_shipping_countries', 'pethoven_us_only' );

function pethoven_us_only( $countries ) {
	return array( 'US' => 'United States (US)' );
}

/* ----------------------------------------------------------------------
 * 2. State restriction — only the 6 allowed states appear in the
 *    checkout state dropdown. Customers in other states physically
 *    can't pick a billing/shipping state.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_states', 'pethoven_filter_us_states' );

function pethoven_filter_us_states( $states ) {
	if ( ! isset( $states['US'] ) ) {
		return $states;
	}
	$allowed       = array_flip( pethoven_delivery_states() );
	$states['US']  = array_intersect_key( $states['US'], $allowed );
	return $states;
}

/* ----------------------------------------------------------------------
 * 3. Server-side enforcement on checkout submit.
 *
 *    The state-list filter above blocks the dropdown, but a determined
 *    user could POST a different state value. This second check
 *    rejects the order with a clear error if the submitted shipping
 *    state isn't in our list.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_checkout_process', 'pethoven_validate_checkout_state' );

function pethoven_validate_checkout_state() {
	if ( ! function_exists( 'wc_add_notice' ) ) {
		return;
	}

	$state   = '';
	$country = '';
	if ( ! empty( $_POST['ship_to_different_address'] ) ) {
		$state   = isset( $_POST['shipping_state'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_state'] ) ) : '';
		$country = isset( $_POST['shipping_country'] ) ? sanitize_text_field( wp_unslash( $_POST['shipping_country'] ) ) : '';
	} else {
		$state   = isset( $_POST['billing_state'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_state'] ) ) : '';
		$country = isset( $_POST['billing_country'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_country'] ) ) : '';
	}

	if ( 'US' !== $country ) {
		wc_add_notice(
			sprintf(
				/* translators: %s = inline list of state codes */
				'We currently ship to the United States only (%s).',
				pethoven_delivery_states_inline()
			),
			'error'
		);
		return;
	}

	if ( $state && ! in_array( $state, pethoven_delivery_states(), true ) ) {
		wc_add_notice(
			sprintf(
				/* translators: %s = inline list of state codes */
				'Sorry, we don\'t ship to that state yet. Currently delivering to: %s.',
				pethoven_delivery_states_inline()
			),
			'error'
		);
	}
}

/* ----------------------------------------------------------------------
 * 4. Display the delivery-region notice on product pages, cart, and
 *    checkout. Uses a single render function with consistent markup
 *    so all three locations style identically.
 * ---------------------------------------------------------------------- */

function pethoven_render_delivery_notice() {
	$states = esc_html( pethoven_delivery_states_inline() );
	?>
	<div class="pt-delivery-notice" role="note" aria-label="Shipping availability">
		<svg class="pt-delivery-notice-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<path d="M3 7h13v10H3z"></path>
			<path d="M16 11h4l2 3v3h-6z"></path>
			<circle cx="7" cy="17" r="2"></circle>
			<circle cx="18" cy="17" r="2"></circle>
		</svg>
		<div class="pt-delivery-notice-body">
			<strong>Currently shipping to:</strong> <?php echo $states; ?>
			<small>USA only. More regions coming — sign up below to be notified.</small>
		</div>
	</div>
	<?php
}

// Product page — above the Add to Cart button area
add_action( 'woocommerce_single_product_summary', 'pethoven_render_delivery_notice', 25 );

// Cart page — above the cart table
add_action( 'woocommerce_before_cart_table', 'pethoven_render_delivery_notice' );

// Checkout page — above the form
add_action( 'woocommerce_before_checkout_form', 'pethoven_render_delivery_notice', 5 );

/* ----------------------------------------------------------------------
 * 5. Inline styles for the notice block. Kept inline rather than in
 *    pethoven-ui.css because this file is a self-contained module —
 *    the chrome it injects belongs with the rest of its logic.
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', 'pethoven_shipping_styles', 32 );

function pethoven_shipping_styles() {
	if ( is_admin() ) {
		return;
	}
	?>
	<style id="pethoven-shipping-css">
	.pt-delivery-notice {
		display: flex;
		gap: 14px;
		align-items: flex-start;
		background: linear-gradient(135deg, rgba(139, 195, 74, 0.10) 0%, rgba(106, 151, 57, 0.04) 100%);
		border: 1px solid rgba(106, 151, 57, 0.22);
		border-radius: 14px;
		padding: 14px 18px;
		margin: 18px 0 22px;
		font-size: 14px;
		line-height: 1.55;
		color: #1a3a2a;
	}
	.pt-delivery-notice-icon {
		width: 22px;
		height: 22px;
		flex-shrink: 0;
		color: var(--ast-global-color-1, #6a9739);
		margin-top: 1px;
	}
	.pt-delivery-notice-body strong {
		color: #1a1a1a;
		font-weight: 700;
		margin-right: 4px;
	}
	.pt-delivery-notice-body small {
		display: block;
		margin-top: 4px;
		font-size: 12.5px;
		color: #4a4a4a;
	}
	@media (max-width: 600px) {
		.pt-delivery-notice {
			padding: 12px 14px;
			font-size: 13px;
			gap: 10px;
		}
		.pt-delivery-notice-icon { width: 20px; height: 20px; }
	}
	</style>
	<?php
}
