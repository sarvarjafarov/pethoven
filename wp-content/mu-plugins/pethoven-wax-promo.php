<?php
/**
 * Pethoven Wax Promo — auto-add Coat Wax to every cart for the first
 * 60 orders, at the regular $10 catalog price, with the customer able
 * to remove it.
 *
 * Plugin Name: Pethoven Wax Promo
 * Description: While the promo is active (first 60 orders that include
 *              the wax), the Coat Wax (SKU PT-COAT_WAX) is automatically
 *              added to any cart containing another product. The cart
 *              line carries a "pt_wax_promo" flag and a small "Auto-added"
 *              sub-label so the customer knows where it came from; price
 *              is the regular $10 catalog price, quantity is locked to 1.
 *              If the customer removes it, we honour that for the rest of
 *              the session. Once 60 promo orders complete, the wax stops
 *              auto-adding (still buyable manually).
 *
 * Configuration constants
 * -----------------------
 *   PETHOVEN_WAX_SKU            — SKU of the wax product (must exist).
 *   PETHOVEN_WAX_PROMO_LIMIT    — promo cuts off after this many orders.
 *   PETHOVEN_WAX_OPT_COUNT      — wp_option holding the live promo
 *                                 counter (incremented per qualifying
 *                                 order, idempotent via order meta).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PETHOVEN_WAX_SKU',           'PT-COAT_WAX' );
define( 'PETHOVEN_WAX_PROMO_LIMIT',   60 );
define( 'PETHOVEN_WAX_OPT_COUNT',     'pt_wax_promo_count' );
define( 'PETHOVEN_WAX_SESSION_KEY',   'pt_wax_user_removed' );

/* ----------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

function pt_wax_product_id() {
	static $id = null;
	if ( $id !== null ) {
		return $id;
	}
	if ( ! function_exists( 'wc_get_product_id_by_sku' ) ) {
		$id = 0;
		return $id;
	}
	$id = (int) wc_get_product_id_by_sku( PETHOVEN_WAX_SKU );
	return $id;
}

function pt_wax_promo_count() {
	return (int) get_option( PETHOVEN_WAX_OPT_COUNT, 0 );
}

function pt_wax_promo_active() {
	return pt_wax_promo_count() < PETHOVEN_WAX_PROMO_LIMIT;
}

function pt_wax_session_user_removed() {
	if ( ! function_exists( 'WC' ) || ! WC()->session ) {
		return false;
	}
	return (bool) WC()->session->get( PETHOVEN_WAX_SESSION_KEY, false );
}

function pt_wax_session_set_removed( $value = true ) {
	if ( ! function_exists( 'WC' ) || ! WC()->session ) {
		return;
	}
	WC()->session->set( PETHOVEN_WAX_SESSION_KEY, (bool) $value );
}

/**
 * Find the wax in the current cart, if present.
 *
 * @return array{key:string,item:array}|null
 */
function pt_wax_find_in_cart() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return null;
	}
	$wax_id = pt_wax_product_id();
	if ( ! $wax_id ) {
		return null;
	}
	foreach ( WC()->cart->get_cart() as $key => $item ) {
		if ( (int) $item['product_id'] === $wax_id ) {
			return array(
				'key'  => $key,
				'item' => $item,
			);
		}
	}
	return null;
}

/* ----------------------------------------------------------------------
 * 1. Auto-add the wax when ANY non-wax product is added to cart.
 *
 *    The action runs with the cart item key, product id, quantity,
 *    variation id/data, and any custom cart item data. We bail out
 *    when the trigger product IS the wax itself (so our recursive
 *    add_to_cart call below doesn't loop), when the wax is already
 *    in cart, when the customer has explicitly removed it this
 *    session, or when the 60-order promo limit has been reached.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_add_to_cart', 'pt_wax_maybe_auto_add', 99, 6 );

function pt_wax_maybe_auto_add( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}
	if ( ! pt_wax_promo_active() ) {
		return;
	}
	if ( pt_wax_session_user_removed() ) {
		return;
	}

	$wax_id = pt_wax_product_id();
	if ( ! $wax_id ) {
		return;
	}
	if ( (int) $product_id === $wax_id ) {
		// User manually added the wax (or our own recursive call); leave alone.
		return;
	}
	if ( pt_wax_find_in_cart() ) {
		// Wax already in cart from a prior add — nothing to do.
		return;
	}

	WC()->cart->add_to_cart(
		$wax_id,
		1,
		0,
		array(),
		array( 'pt_wax_promo' => true )
	);

	if ( function_exists( 'wc_add_notice' ) ) {
		wc_add_notice(
			'Coat Wax added to your order. You can remove it from the cart if you don\'t need it.',
			'notice'
		);
	}
}

/* ----------------------------------------------------------------------
 * 1b. Safety-net: ensure the wax is in cart whenever the customer
 *     visits the cart or checkout page with at least one other
 *     product in cart.
 *
 *     The primary `woocommerce_add_to_cart` hook (above) covers the
 *     normal AJAX-add-from-shop flow. This hook covers edge cases:
 *
 *       - Cart was loaded from a persisted session (logged-in user,
 *         "save cart" plugins) without re-running the add hook.
 *       - LiteSpeed object cache or page cache intercepted the
 *         original add and skipped the hook side-effects.
 *       - A different code path (REST API, CartFlows, etc.) added
 *         the product without firing woocommerce_add_to_cart.
 *
 *     Fires on cart + checkout templates. Cheap when nothing's
 *     needed (early bails on empty cart / already-present wax /
 *     session-removed flag / promo expired).
 * ---------------------------------------------------------------------- */

add_action( 'template_redirect', 'pt_wax_ensure_in_cart', 20 );

function pt_wax_ensure_in_cart() {
	if ( ! function_exists( 'is_cart' ) ) {
		return;
	}
	if ( ! is_cart() && ! is_checkout() ) {
		return;
	}
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}
	if ( WC()->cart->is_empty() ) {
		return;
	}
	if ( ! pt_wax_promo_active() ) {
		return;
	}
	if ( pt_wax_session_user_removed() ) {
		return;
	}

	$wax_id = pt_wax_product_id();
	if ( ! $wax_id ) {
		return;
	}
	if ( pt_wax_find_in_cart() ) {
		return;
	}

	// Confirm the cart contains at least one non-wax product before
	// auto-adding (otherwise we'd be adding wax to a cart that was
	// emptied except for some edge case).
	$has_other = false;
	foreach ( WC()->cart->get_cart() as $item ) {
		if ( (int) $item['product_id'] !== $wax_id ) {
			$has_other = true;
			break;
		}
	}
	if ( ! $has_other ) {
		return;
	}

	WC()->cart->add_to_cart(
		$wax_id,
		1,
		0,
		array(),
		array( 'pt_wax_promo' => true )
	);
}

/* ----------------------------------------------------------------------
 * 2. Display "Auto-added — remove if not needed" label on the cart
 *    line item. The wax keeps its normal $10 catalog price; the promo
 *    is just the auto-add behaviour, not a discount.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_get_item_data', 'pt_wax_cart_item_label', 10, 2 );

function pt_wax_cart_item_label( $item_data, $cart_item ) {
	if ( empty( $cart_item['pt_wax_promo'] ) ) {
		return $item_data;
	}
	$item_data[] = array(
		'key'     => 'Add-on',
		'value'   => 'Auto-added — remove if not needed',
		'display' => '',
	);
	return $item_data;
}

/* ----------------------------------------------------------------------
 * 4. Lock cart quantity to 1 for the promo wax.
 *
 *    Without this, the customer could bump the quantity in the cart
 *    UI and get N free waxes. Replacing the input with a static "1"
 *    span keeps the row visually consistent and disables the +/-.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_cart_item_quantity', 'pt_wax_lock_qty_input', 10, 3 );

function pt_wax_lock_qty_input( $product_quantity, $cart_item_key, $cart_item ) {
	if ( ! empty( $cart_item['pt_wax_promo'] ) ) {
		return '<span class="pt-wax-fixed-qty">1</span>';
	}
	return $product_quantity;
}

/* ----------------------------------------------------------------------
 * 5. When the wax is removed by the customer, set a session flag so
 *    we don't re-add it on subsequent add_to_cart calls.
 *
 *    Also: if removing the last non-wax item leaves only the (free)
 *    wax in the cart, auto-remove the wax too — we don't want to
 *    leave a customer with an orphan zero-value cart that can't
 *    actually check out.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_cart_item_removed', 'pt_wax_on_removed', 10, 2 );

function pt_wax_on_removed( $cart_item_key, $cart ) {
	$wax_id = pt_wax_product_id();
	if ( ! $wax_id ) {
		return;
	}

	// Was the just-removed item the wax?
	if ( ! empty( $cart->removed_cart_contents[ $cart_item_key ]['product_id'] ) ) {
		$removed_pid = (int) $cart->removed_cart_contents[ $cart_item_key ]['product_id'];
		if ( $removed_pid === $wax_id ) {
			pt_wax_session_set_removed( true );
			return;
		}
	}

	// Otherwise, see if removing this item leaves the cart with ONLY
	// the wax. If so, the wax is now an orphan free item — remove it.
	$wax_entry    = pt_wax_find_in_cart();
	$has_non_wax  = false;
	foreach ( $cart->get_cart() as $item ) {
		if ( (int) $item['product_id'] !== $wax_id ) {
			$has_non_wax = true;
			break;
		}
	}
	if ( $wax_entry && ! $has_non_wax ) {
		$cart->remove_cart_item( $wax_entry['key'] );
	}
}

/* ----------------------------------------------------------------------
 * 6. Restore: if the customer clicks "Undo" after removing the wax,
 *    clear the session-removed flag so it can re-add naturally.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_restore_cart_item', 'pt_wax_on_restore', 10, 1 );

function pt_wax_on_restore( $cart_item_key ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}
	$cart_items = WC()->cart->get_cart();
	if ( empty( $cart_items[ $cart_item_key ]['product_id'] ) ) {
		return;
	}
	if ( (int) $cart_items[ $cart_item_key ]['product_id'] === pt_wax_product_id() ) {
		pt_wax_session_set_removed( false );
	}
}

/* ----------------------------------------------------------------------
 * 7. Persist the promo flag onto the order line item, so we can
 *    distinguish promo waxes from manually-added waxes when counting.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_checkout_create_order_line_item', 'pt_wax_save_line_meta', 10, 4 );

function pt_wax_save_line_meta( $item, $cart_item_key, $values, $order ) {
	if ( ! empty( $values['pt_wax_promo'] ) ) {
		$item->add_meta_data( '_pt_wax_promo', 1, true );
	}
}

/* ----------------------------------------------------------------------
 * 8. Increment the promo counter when a qualifying order moves to
 *    processing or completed. Idempotent via _pt_wax_promo_counted
 *    on the order — repeated status transitions don't double-count.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_order_status_processing', 'pt_wax_count_promo_order', 10, 2 );
add_action( 'woocommerce_order_status_completed',  'pt_wax_count_promo_order', 10, 2 );

function pt_wax_count_promo_order( $order_id, $order = null ) {
	if ( ! $order_id ) {
		return;
	}
	if ( ! $order && function_exists( 'wc_get_order' ) ) {
		$order = wc_get_order( $order_id );
	}
	if ( ! $order ) {
		return;
	}
	if ( $order->get_meta( '_pt_wax_promo_counted' ) ) {
		return;
	}

	$contains_promo = false;
	foreach ( $order->get_items() as $item ) {
		if ( $item->get_meta( '_pt_wax_promo' ) ) {
			$contains_promo = true;
			break;
		}
	}
	if ( ! $contains_promo ) {
		return;
	}

	$count = pt_wax_promo_count();
	update_option( PETHOVEN_WAX_OPT_COUNT, $count + 1 );
	$order->update_meta_data( '_pt_wax_promo_counted', 1 );
	$order->save();
}

/* ----------------------------------------------------------------------
 * 9. (Removed) Shop-card badge.
 *
 *    A previous version painted a green "Free add-on" pill on the
 *    wax product card. The wax is now auto-added at its normal $10
 *    catalog price — nothing about the shop archive presentation
 *    is special, so the badge would have been misleading. The
 *    auto-add behaviour signals itself via the cart "Add-on:
 *    auto-added" sub-label when the customer reaches the cart.
 * ---------------------------------------------------------------------- */
