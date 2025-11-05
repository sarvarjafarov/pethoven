<?php
/**
 * Lightweight performance optimizations for the front-end.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Disable emoji scripts and styles everywhere.
 */
add_action( 'init', function () {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    add_filter( 'emoji_svg_url', '__return_false' );
} );

/**
 * Remove oEmbed discovery links and REST API links from the front-end head output.
 */
add_action( 'init', function () {
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
} );

/**
 * Prevent the embed script from loading on the front-end.
 */
add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_admin() ) {
        wp_deregister_script( 'wp-embed' );
    }
}, 100 );

/**
 * Drop Dashicons for visitors who are not logged in.
 */
add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
}, 100 );

/**
 * Slow the Heartbeat API down to reduce server load.
 */
add_filter( 'heartbeat_settings', function ( $settings ) {
    if ( isset( $settings['interval'] ) && $settings['interval'] < 60 ) {
        $settings['interval'] = 60;
    }

    return $settings;
} );

/**
 * Strip version query strings from static assets to improve caching.
 */
add_filter( 'script_loader_src', 'pt_strip_asset_version_query', 10, 1 );
add_filter( 'style_loader_src', 'pt_strip_asset_version_query', 10, 1 );

function pt_strip_asset_version_query( $src ) {
    if ( strpos( $src, 'ver=' ) !== false ) {
        $src = remove_query_arg( 'ver', $src );
    }

    return $src;
}
