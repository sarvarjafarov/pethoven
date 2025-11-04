<?php
/**
 * Astra Organic child theme bootstrap.
 */

add_action( 'wp_enqueue_scripts', function () {
	$child = wp_get_theme();

	wp_enqueue_style(
		'astra-organic-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'astra-theme-css' ),
		$child->get( 'Version' )
	);
} );

add_action( 'after_setup_theme', function () {
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'Organic Green', 'astra-organic' ),
			'slug'  => 'organic-green',
			'color' => '#4caf50',
		),
		array(
			'name'  => __( 'Deep Forest', 'astra-organic' ),
			'slug'  => 'deep-forest',
			'color' => '#2e7d32',
		),
		array(
			'name'  => __( 'Harvest Gold', 'astra-organic' ),
			'slug'  => 'harvest-gold',
			'color' => '#ef9a1f',
		),
		array(
			'name'  => __( 'Earth Clay', 'astra-organic' ),
			'slug'  => 'earth-clay',
			'color' => '#a65a2a',
		),
		array(
			'name'  => __( 'Soft Canvas', 'astra-organic' ),
			'slug'  => 'soft-canvas',
			'color' => '#f5f1e6',
		),
	) );

	add_theme_support( 'custom-logo' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'woocommerce' );
} );

add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	$plugins = array(
		'astra-sites/astra-sites.php'                    => __( 'Starter Templates', 'astra-organic' ),
		'ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php' => __( 'Spectra', 'astra-organic' ),
		'woocommerce/woocommerce.php'                    => __( 'WooCommerce', 'astra-organic' ),
		'wpforms-lite/wpforms.php'                       => __( 'WPForms Lite', 'astra-organic' ),
	);

	$missing = array();
	foreach ( $plugins as $path => $label ) {
		if ( ! file_exists( WP_PLUGIN_DIR . '/' . $path ) ) {
			$missing[] = $label;
			continue;
		}

		if ( ! is_plugin_active( $path ) ) {
			$missing[] = $label;
		}
	}

	if ( empty( $missing ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%s</p><p><strong>%s</strong>: %s</p></div>',
		esc_html__( 'The Astra Organic child theme recommends a few free plugins to unlock the Organic Store starter template.', 'astra-organic' ),
		esc_html__( 'Activate', 'astra-organic' ),
		esc_html( implode( ', ', $missing ) )
	);
} );
