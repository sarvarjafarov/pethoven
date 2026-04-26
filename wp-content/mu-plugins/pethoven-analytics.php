<?php
/**
 * Pethoven Analytics — GA4 + Enhanced Ecommerce instrumentation.
 *
 * Plugin Name: Pethoven Analytics
 * Description: Loads gtag.js, configures GA4, and fires GA4 Enhanced
 *              Ecommerce events for WooCommerce + custom site events.
 *
 * Architecture
 * ------------
 *   - All events go through window.dataLayer first (gtag wraps that),
 *     so a future migration to Google Tag Manager is config-only.
 *   - Server-side rendering injects ecommerce events for view_item_list,
 *     view_item, view_cart, begin_checkout, purchase. Data is most
 *     accurate at render time and survives JS errors.
 *   - Client-side JS catches interaction events (select_item,
 *     add_to_cart, remove_from_cart, cta_click, newsletter_signup).
 *
 * Events fired
 * ------------
 *   Auto (GA4 enhanced measurement):
 *     - page_view, scroll, click (outbound), site_search, video_*
 *   Custom from this plugin:
 *     - view_item_list  (PHP, shop archive + category)
 *     - select_item     (JS, product card click)
 *     - view_item       (PHP, single product)
 *     - add_to_cart     (JS, after WC AJAX succeeds)
 *     - remove_from_cart(JS, on cart row remove)
 *     - view_cart       (PHP, cart page)
 *     - begin_checkout  (PHP, checkout page)
 *     - purchase        (PHP, order received page; idempotent)
 *     - newsletter_signup (JS, footer subscribe)
 *     - cta_click       (JS, brand CTA pills)
 *
 * GA4 setup checklist (UI-side, can't be done from code)
 * ------------------------------------------------------
 *   1. In GA4 Admin → Data Streams → choose this site → Enhanced
 *      Measurement: leave all defaults ON (Page views, Scrolls,
 *      Outbound clicks, Site search, Form interactions, Video
 *      engagement, File downloads).
 *   2. Admin → Events → mark these as conversions:
 *        purchase, begin_checkout, add_to_cart, newsletter_signup
 *   3. Admin → Ecommerce settings → Enable "Enhanced ecommerce".
 *   4. (Optional) Link Google Ads + Search Console for attribution.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PETHOVEN_GA4_ID', 'G-GSJNHEN7GX' );

/* ----------------------------------------------------------------------
 * 0. Sanity checks — only run on the public front-end.
 * ---------------------------------------------------------------------- */

function pethoven_ga_is_active() {
	if ( is_admin() ) {
		return false;
	}
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return false;
	}
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return false;
	}
	if ( ! PETHOVEN_GA4_ID ) {
		return false;
	}
	return true;
}

/* ----------------------------------------------------------------------
 * 1. Load gtag.js in <head> with a single config call.
 *
 *    Priority 1 so the snippet runs before other head output and any
 *    subsequent gtag('event', …) calls have a definition to call into.
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', 'pethoven_ga_head_snippet', 1 );

function pethoven_ga_head_snippet() {
	if ( ! pethoven_ga_is_active() ) {
		return;
	}
	$id = esc_js( PETHOVEN_GA4_ID );
	?>
	<!-- Pethoven Analytics: gtag.js (GA4) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( PETHOVEN_GA4_ID ); ?>"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', '<?php echo $id; ?>', {
		send_page_view: true,
		anonymize_ip: true,
		allow_google_signals: true
	});
	</script>
	<!-- /Pethoven Analytics -->
	<?php
}

/* ----------------------------------------------------------------------
 * 2. Helpers.
 *
 *    pethoven_ga_item()        : WC_Product → GA4 items[] entry
 *    pethoven_ga_inline_event(): emit a gtag('event', …) call inline
 *    pethoven_ga_currency()    : store currency or USD fallback
 * ---------------------------------------------------------------------- */

function pethoven_ga_currency() {
	if ( function_exists( 'get_woocommerce_currency' ) ) {
		$cur = get_woocommerce_currency();
		if ( $cur ) {
			return $cur;
		}
	}
	return 'USD';
}

function pethoven_ga_item( $product, $quantity = 1 ) {
	if ( ! $product instanceof WC_Product ) {
		return null;
	}

	$category = '';
	$cat_terms = get_the_terms( $product->get_id(), 'product_cat' );
	if ( $cat_terms && ! is_wp_error( $cat_terms ) ) {
		$category = $cat_terms[0]->name;
	}

	$price = (float) wc_get_price_to_display( $product );

	return array(
		'item_id'       => $product->get_sku() ? $product->get_sku() : 'product_' . $product->get_id(),
		'item_name'     => $product->get_name(),
		'item_category' => $category,
		'price'         => $price,
		'quantity'      => (int) $quantity,
	);
}

function pethoven_ga_inline_event( $name, $params ) {
	if ( ! pethoven_ga_is_active() ) {
		return;
	}
	?>
	<script>
	(function () {
		if (typeof gtag !== 'function') return;
		gtag('event', <?php echo wp_json_encode( $name ); ?>, <?php echo wp_json_encode( $params ); ?>);
	})();
	</script>
	<?php
}

/* ----------------------------------------------------------------------
 * 3. view_item_list — fires on shop archive, category, tag pages.
 *
 *    Hook: woocommerce_after_shop_loop runs once per archive after
 *    products are rendered. We pull the rendered post objects out of
 *    the main query so the items[] payload exactly matches what the
 *    user sees on screen (post-pagination).
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_after_shop_loop', 'pethoven_ga_view_item_list', 99 );

function pethoven_ga_view_item_list() {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	global $wp_query;
	if ( empty( $wp_query->posts ) ) {
		return;
	}

	$items = array();
	$index = 0;
	foreach ( $wp_query->posts as $post ) {
		$product = wc_get_product( $post->ID );
		if ( ! $product ) {
			continue;
		}
		$item = pethoven_ga_item( $product );
		if ( ! $item ) {
			continue;
		}
		$item['index'] = $index++;
		$items[]       = $item;
	}

	if ( empty( $items ) ) {
		return;
	}

	$list_name = 'Products';
	$list_id   = 'products';
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$list_name = 'Shop Archive';
		$list_id   = 'shop_archive';
	} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$term      = get_queried_object();
		$list_name = $term && isset( $term->name ) ? $term->name : 'Category';
		$list_id   = 'category_' . ( $term && isset( $term->slug ) ? $term->slug : 'unknown' );
	} elseif ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
		$term      = get_queried_object();
		$list_name = 'Tag: ' . ( $term && isset( $term->name ) ? $term->name : '' );
		$list_id   = 'tag_' . ( $term && isset( $term->slug ) ? $term->slug : 'unknown' );
	}

	pethoven_ga_inline_event(
		'view_item_list',
		array(
			'item_list_id'   => $list_id,
			'item_list_name' => $list_name,
			'currency'       => pethoven_ga_currency(),
			'items'          => $items,
		)
	);
}

/* ----------------------------------------------------------------------
 * 4. view_item — fires on single product detail pages.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_after_single_product_summary', 'pethoven_ga_view_item', 99 );

function pethoven_ga_view_item() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
	}
	if ( ! $product ) {
		return;
	}

	$item = pethoven_ga_item( $product );
	if ( ! $item ) {
		return;
	}

	pethoven_ga_inline_event(
		'view_item',
		array(
			'currency' => pethoven_ga_currency(),
			'value'    => (float) wc_get_price_to_display( $product ),
			'items'    => array( $item ),
		)
	);
}

/* ----------------------------------------------------------------------
 * 5. view_cart — fires when the cart page is rendered.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_before_cart', 'pethoven_ga_view_cart' );

function pethoven_ga_view_cart() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}
	$cart = WC()->cart;
	if ( $cart->is_empty() ) {
		return;
	}

	$items = array();
	foreach ( $cart->get_cart() as $cart_item ) {
		if ( empty( $cart_item['data'] ) ) {
			continue;
		}
		$item = pethoven_ga_item( $cart_item['data'], $cart_item['quantity'] );
		if ( $item ) {
			$items[] = $item;
		}
	}

	pethoven_ga_inline_event(
		'view_cart',
		array(
			'currency' => pethoven_ga_currency(),
			'value'    => (float) $cart->get_subtotal(),
			'items'    => $items,
		)
	);
}

/* ----------------------------------------------------------------------
 * 6. begin_checkout — fires when /checkout/ renders with a non-empty cart.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_before_checkout_form', 'pethoven_ga_begin_checkout' );

function pethoven_ga_begin_checkout() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}
	$cart = WC()->cart;
	if ( $cart->is_empty() ) {
		return;
	}

	$items = array();
	foreach ( $cart->get_cart() as $cart_item ) {
		if ( empty( $cart_item['data'] ) ) {
			continue;
		}
		$item = pethoven_ga_item( $cart_item['data'], $cart_item['quantity'] );
		if ( $item ) {
			$items[] = $item;
		}
	}

	pethoven_ga_inline_event(
		'begin_checkout',
		array(
			'currency' => pethoven_ga_currency(),
			'value'    => (float) $cart->get_subtotal(),
			'items'    => $items,
		)
	);
}

/* ----------------------------------------------------------------------
 * 7. purchase — fires once per order on the order-received page.
 *
 *    Idempotent via a `_pt_ga_purchase_fired` order meta flag: if the
 *    customer reloads the thank-you page, the event won't double-fire
 *    in dataLayer (GA4 also dedupes by transaction_id, but this keeps
 *    the local DOM clean).
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_thankyou', 'pethoven_ga_purchase', 10, 1 );

function pethoven_ga_purchase( $order_id ) {
	if ( ! $order_id ) {
		return;
	}
	$order = function_exists( 'wc_get_order' ) ? wc_get_order( $order_id ) : null;
	if ( ! $order ) {
		return;
	}

	if ( $order->get_meta( '_pt_ga_purchase_fired' ) ) {
		return;
	}

	$items = array();
	foreach ( $order->get_items() as $line_item ) {
		$product = $line_item->get_product();
		if ( ! $product instanceof WC_Product ) {
			continue;
		}
		$item             = pethoven_ga_item( $product, $line_item->get_quantity() );
		$item['price']    = (float) ( $line_item->get_subtotal() / max( 1, $line_item->get_quantity() ) );
		$items[]          = $item;
	}

	pethoven_ga_inline_event(
		'purchase',
		array(
			'transaction_id' => (string) $order->get_order_number(),
			'value'          => (float) $order->get_total(),
			'tax'            => (float) $order->get_total_tax(),
			'shipping'       => (float) $order->get_shipping_total(),
			'currency'       => $order->get_currency(),
			'items'          => $items,
		)
	);

	$order->update_meta_data( '_pt_ga_purchase_fired', 1 );
	$order->save();
}

/* ----------------------------------------------------------------------
 * 8. Client-side events: select_item, add_to_cart, remove_from_cart,
 *    cta_click, newsletter_signup.
 *
 *    add_to_cart hooks WooCommerce's `added_to_cart` jQuery event so
 *    we fire AFTER the AJAX call succeeds (accurate counts even on
 *    network retries). Falls back to a click handler for non-AJAX
 *    pages (e.g. archive without the AJAX add-to-cart setting).
 *
 *    remove_from_cart hooks the matching `removed_from_cart` event.
 *
 *    select_item, cta_click, newsletter_signup are pure delegated
 *    click/submit handlers on document, so they survive any DOM
 *    rerenders WooCommerce or Elementor do post-load.
 * ---------------------------------------------------------------------- */

add_action( 'wp_footer', 'pethoven_ga_client_events', 99 );

function pethoven_ga_client_events() {
	if ( ! pethoven_ga_is_active() ) {
		return;
	}
	$currency = wp_json_encode( pethoven_ga_currency() );
	?>
	<script>
	/* Pethoven Analytics — client events */
	(function () {
		if (typeof gtag !== 'function') return;
		var CURRENCY = <?php echo $currency; ?>;

		// Parse a WooCommerce product card into a GA4 items[] entry.
		// Works on archive cards, related-product cards, anywhere a
		// .product card has the standard Astra/Woo markup.
		function itemFromCard(card) {
			if (!card) return null;
			var titleEl = card.querySelector('.woocommerce-loop-product__title, .pt-product-name');
			var priceEl = card.querySelector('.price .amount, .price bdi, .pt-product-price');
			var btnEl   = card.querySelector('[data-product_sku], [data-product_id]');
			var sku     = btnEl ? btnEl.getAttribute('data-product_sku') : '';
			var pid     = btnEl ? btnEl.getAttribute('data-product_id')  : '';
			var price   = 0;
			if (priceEl) {
				var raw = (priceEl.textContent || '').replace(/[^0-9.,]/g, '').replace(',', '.');
				price = raw ? parseFloat(raw) : 0;
			}
			return {
				item_id:   sku || (pid ? 'product_' + pid : ''),
				item_name: titleEl ? (titleEl.textContent || '').trim() : '',
				price:    price || 0,
				quantity: 1
			};
		}

		// select_item — clicking a product card link.
		document.addEventListener('click', function (e) {
			var link = e.target.closest('.woocommerce-loop-product__link, .ast-loop-product__link');
			if (!link) return;
			var card = link.closest('li.product, .product');
			var item = itemFromCard(card);
			if (!item || !item.item_name) return;
			gtag('event', 'select_item', {
				item_list_id:   'shop_archive',
				item_list_name: 'Shop Archive',
				items: [ item ]
			});
		}, { passive: true });

		// add_to_cart — fired by Woo via jQuery after AJAX completes.
		// $button is the original button JQ object; we read product data
		// from its dataset, then enrich with card data if visible.
		if (window.jQuery) {
			jQuery(document.body).on('added_to_cart', function (e, fragments, cart_hash, $button) {
				var btn = $button && $button[0] ? $button[0] : null;
				if (!btn) return;
				var card = btn.closest('li.product, .product');
				var item = itemFromCard(card) || {};
				var qty  = parseInt(btn.getAttribute('data-quantity') || '1', 10) || 1;
				if (!item.item_id) {
					item.item_id = btn.getAttribute('data-product_sku') ||
						('product_' + (btn.getAttribute('data-product_id') || ''));
				}
				item.quantity = qty;
				gtag('event', 'add_to_cart', {
					currency: CURRENCY,
					value:    (item.price || 0) * qty,
					items:    [ item ]
				});
			});

			// remove_from_cart — Woo fires this on the cart page after
			// the user clicks a row's "x" remove link.
			jQuery(document.body).on('removed_from_cart', function (e, fragments, cart_hash, $button) {
				var btn = $button && $button[0] ? $button[0] : null;
				if (!btn) return;
				var item = {
					item_id:  btn.getAttribute('data-product_sku')   ||
					          'product_' + (btn.getAttribute('data-product_id') || ''),
					quantity: 1
				};
				gtag('event', 'remove_from_cart', {
					currency: CURRENCY,
					items:    [ item ]
				});
			});
		}

		// Fallback for non-AJAX add-to-cart (link triggers full reload).
		// Fires on click; if AJAX is enabled the jQuery handler above
		// also fires, so we tag this one with a `nonajax: true` flag
		// for de-dup in GA4 if needed. GA4 dedupes by event timestamp
		// + params, so a redundant event is rare in practice.
		document.addEventListener('click', function (e) {
			var btn = e.target.closest('a.add_to_cart_button:not(.ajax_add_to_cart)');
			if (!btn) return;
			var card = btn.closest('li.product, .product');
			var item = itemFromCard(card) || {};
			var qty  = parseInt(btn.getAttribute('data-quantity') || '1', 10) || 1;
			if (!item.item_id) {
				item.item_id = btn.getAttribute('data-product_sku') ||
					('product_' + (btn.getAttribute('data-product_id') || ''));
			}
			item.quantity = qty;
			gtag('event', 'add_to_cart', {
				currency: CURRENCY,
				value:    (item.price || 0) * qty,
				items:    [ item ],
				nonajax:  true
			});
		}, { passive: true });

		// cta_click — brand CTA pills (View all products, Shop Now,
		// hero buttons, promo card buttons, sidebar promo button).
		var CTA_SELECTOR = [
			'.pt-our-products-cta',
			'.pt-product-cta',
			'.pt-sidebar-promo-btn',
			'.elementor-element-3849851 a.elementor-button',
			'.elementor-element-28fc7dc a.elementor-button'
		].join(', ');
		document.addEventListener('click', function (e) {
			var cta = e.target.closest(CTA_SELECTOR);
			if (!cta) return;
			gtag('event', 'cta_click', {
				cta_text:  (cta.textContent || '').trim().slice(0, 60),
				cta_href:  cta.getAttribute('href') || '',
				cta_class: (cta.className || '').toString().slice(0, 120),
				page_path: location.pathname
			});
		}, { passive: true });

		// newsletter_signup — footer "Join the pack" form.
		// Fired on submit (optimistic). Form's own JS handles the
		// REST POST to /wp-json/pethoven/v1/subscribe; if the server
		// rejects with 400 the user retries — we treat the intent
		// as the engagement metric.
		document.addEventListener('submit', function (e) {
			var form = e.target.closest('.pt-footer-newsletter-form');
			if (!form) return;
			gtag('event', 'newsletter_signup', {
				method: 'footer_form'
			});
		}, { passive: true });
	})();
	</script>
	<?php
}
