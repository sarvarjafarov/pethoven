<?php
/**
 * Ensure essential Organic Store pages exist and stay published.
 */

define( 'ASTRA_ORGANIC_PAGE_SEED_VERSION', '1.0.0' );

if ( ! function_exists( 'wp_get_nav_menu_object' ) ) {
	require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
}

add_action( 'init', 'astra_organic_seed_pages', 30 );

function astra_organic_seed_pages() {
	if ( is_admin() && isset( $_GET['page'] ) && 'astra-sites' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		// Allow starter template UI to manage content without interference.
		return;
	}

	if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
		return;
	}

	if ( ! function_exists( 'wp_insert_post' ) ) {
		return;
	}

	$pages       = astra_organic_required_pages();
	$created_map = array();

	foreach ( $pages as $slug => $config ) {
		$page_id = astra_organic_ensure_page( $slug, $config );

		if ( $page_id ) {
			$created_map[ $slug ] = $page_id;
		}

		if ( ! $page_id ) {
			continue;
		}

		if ( isset( $config['set_front'] ) && $config['set_front'] ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $page_id );
		}

		if ( isset( $config['set_posts'] ) && $config['set_posts'] ) {
			update_option( 'page_for_posts', $page_id );
		}

		if ( isset( $config['option'] ) ) {
			update_option( $config['option'], $page_id );
		}
	}

	if ( ! empty( $created_map ) ) {
		astra_organic_sync_primary_menu( $pages, $created_map );
	}
}

function astra_organic_required_pages() {
	return array(
		'home'       => array(
			'title'     => __( 'Home', 'astra-organic' ),
			'content'   => astra_organic_template_content( 'home' ),
			'menu'      => __( 'Home', 'astra-organic' ),
			'set_front' => true,
		),
		'shop'       => array(
			'title'   => __( 'Shop', 'astra-organic' ),
			'content' => '<!-- wp:paragraph {"fontSize":"small"} --><p class="has-small-font-size">' . esc_html__( 'This page displays your latest products.', 'astra-organic' ) . '</p><!-- /wp:paragraph -->',
			'menu'    => __( 'Shop', 'astra-organic' ),
			'option'  => 'woocommerce_shop_page_id',
		),
		'cart'       => array(
			'title'   => __( 'Cart', 'astra-organic' ),
			'content' => '<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->',
			'option'  => 'woocommerce_cart_page_id',
		),
		'checkout'   => array(
			'title'   => __( 'Checkout', 'astra-organic' ),
			'content' => '<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->',
			'option'  => 'woocommerce_checkout_page_id',
		),
		'my-account' => array(
			'title'   => __( 'My Account', 'astra-organic' ),
			'content' => '<!-- wp:shortcode -->[woocommerce_my_account]<!-- /wp:shortcode -->',
			'option'  => 'woocommerce_myaccount_page_id',
		),
		'about'      => array(
			'title'   => __( 'About Us', 'astra-organic' ),
			'content' => astra_organic_template_content( 'about' ),
			'menu'    => __( 'About', 'astra-organic' ),
		),
		'blog'       => array(
			'title'     => __( 'Journal', 'astra-organic' ),
			'content'   => astra_organic_template_content( 'blog' ),
			'menu'      => __( 'Journal', 'astra-organic' ),
			'set_posts' => true,
		),
		'contact'    => array(
			'title'   => __( 'Contact', 'astra-organic' ),
			'content' => astra_organic_template_content( 'contact' ),
			'menu'    => __( 'Contact', 'astra-organic' ),
		),
	);
}

function astra_organic_template_content( $slug ) {
	$path = get_stylesheet_directory() . '/content/' . $slug . '.html';

	if ( file_exists( $path ) ) {
		return file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	return '';
}

function astra_organic_ensure_page( $slug, $config ) {
	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post ) {
		if ( 'trash' === $page->post_status ) {
			wp_untrash_post( $page->ID );
			$page = get_post( $page->ID );
		}

		if ( 'publish' !== $page->post_status ) {
			wp_update_post(
				array(
					'ID'          => $page->ID,
					'post_status' => 'publish',
				)
			);
		}

		return $page->ID;
	}

	$page_id = wp_insert_post(
		array(
			'post_title'   => $config['title'],
			'post_name'    => $slug,
			'post_content' => $config['content'],
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_author'  => get_current_user_id() ? get_current_user_id() : 1,
			'meta_input'   => array(
				'_astra_organic_seed_version' => ASTRA_ORGANIC_PAGE_SEED_VERSION,
			),
		),
		true
	);

	if ( is_wp_error( $page_id ) ) {
		return 0;
	}

	return (int) $page_id;
}

function astra_organic_sync_primary_menu( $pages, $created_map ) {
	$menu_name = __( 'Organic Primary', 'astra-organic' );
	$menu      = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = $menu->term_id;
	}

	$locations              = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary']   = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	$existing_items = wp_get_nav_menu_items( $menu_id );
	$existing_map   = array();

	if ( $existing_items ) {
		foreach ( $existing_items as $item ) {
			if ( 'page' === $item->object ) {
				$existing_map[ $item->object_id ] = true;
			}
		}
	}

	foreach ( $pages as $slug => $config ) {
		if ( empty( $config['menu'] ) || empty( $created_map[ $slug ] ) ) {
			continue;
		}

		$page_id = (int) $created_map[ $slug ];

		if ( isset( $existing_map[ $page_id ] ) ) {
			continue;
		}

		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $config['menu'],
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page_id,
				'menu-item-status'    => 'publish',
				'menu-item-type'      => 'post_type',
			)
		);
	}
}
