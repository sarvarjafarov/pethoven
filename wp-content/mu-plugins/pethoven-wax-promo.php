<?php
/**
 * Pethoven Wax Promo — auto-add Coat Wax to every cart for the first
 * 60 orders, free of charge, with the customer able to remove it.
 *
 * Plugin Name: Pethoven Wax Promo
 * Description: While the promo is active (first 60 orders that include
 *              the wax), the Coat Wax (SKU PT-COAT_WAX) is automatically
 *              added to any cart containing another product. The promo
 *              copy of the wax is locked to $0 and quantity 1; if the
 *              customer removes it, we honour that for the rest of the
 *              session and don't re-add. Once 60 promo orders complete,
 *              the wax stops auto-adding and renders at its normal price.
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
			sprintf(
				/* translators: %d = promo limit */
				'Complimentary finishing wax added to your order — limited to the first %d customers.',
				PETHOVEN_WAX_PROMO_LIMIT
			),
			'success'
		);
	}
}

/* ----------------------------------------------------------------------
 * 2. Lock the promo wax line price to $0.
 *
 *    Runs every time WC recalculates totals (cart, checkout, mini-cart).
 *    The data carried on the cart item is a clone of the product
 *    object, so set_price() here is per-cart-item only and never
 *    persists to the catalog.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_before_calculate_totals', 'pt_wax_zero_price', 10, 1 );

function pt_wax_zero_price( $cart ) {
	if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		return;
	}
	foreach ( $cart->get_cart() as $cart_item ) {
		if ( ! empty( $cart_item['pt_wax_promo'] ) && isset( $cart_item['data'] ) ) {
			$cart_item['data']->set_price( 0 );
		}
	}
}

/* ----------------------------------------------------------------------
 * 3. Display "Free with first 60 orders" label on the cart line item.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_get_item_data', 'pt_wax_cart_item_label', 10, 2 );

function pt_wax_cart_item_label( $item_data, $cart_item ) {
	if ( empty( $cart_item['pt_wax_promo'] ) ) {
		return $item_data;
	}
	$item_data[] = array(
		'key'     => 'Promotion',
		'value'   => sprintf(
			/* translators: %d = promo limit */
			'Free — first %d orders',
			PETHOVEN_WAX_PROMO_LIMIT
		),
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
 * 9. Front-of-shop signal: small badge on the wax product card so
 *    customers know it ships free with their order. Hidden once the
 *    promo limit is hit.
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', 'pt_wax_promo_badge_css', 50 );

function pt_wax_promo_badge_css() {
	if ( is_admin() ) {
		return;
	}
	if ( ! pt_wax_promo_active() ) {
		return;
	}
	?>
	<style id="pt-wax-promo-badge">
	/* Free-add-on badge on the wax product card. Targeted by the
	 * post-id class WooCommerce stamps on the loop <li>; falls back
	 * to a SKU data attribute on the inner add-to-cart button. */
	.woocommerce ul.products li.product .pt-wax-promo-badge,
	.pt-wax-promo-badge {
		position: absolute;
		top: 14px;
		left: 14px;
		background: var(--ast-global-color-1, #6a9739);
		color: #ffffff;
		font-size: 10px;
		font-weight: 800;
		letter-spacing: 1.2px;
		text-transform: uppercase;
		padding: 5px 12px;
		border-radius: 100px;
		line-height: 1.3;
		box-shadow: 0 4px 14px rgba(106, 151, 57, 0.32);
		z-index: 3;
		pointer-events: none;
	}
	</style>
	<script>
	(function () {
		document.addEventListener('DOMContentLoaded', function () {
			document.querySelectorAll('.add_to_cart_button[data-product_sku="<?php echo esc_js( PETHOVEN_WAX_SKU ); ?>"]').forEach(function (btn) {
				var card = btn.closest('li.product, .product');
				if (!card) return;
				var thumb = card.querySelector('.astra-shop-thumbnail-wrap');
				if (!thumb) return;
				if (thumb.querySelector('.pt-wax-promo-badge')) return;
				var b = document.createElement('span');
				b.className = 'pt-wax-promo-badge';
				b.textContent = 'Free add-on';
				thumb.appendChild(b);
			});
		});
	})();
	</script>
	<?php
}
