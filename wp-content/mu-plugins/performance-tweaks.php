<?php
/**
 * Lightweight performance and security optimizations for the front-end.
 *
 * Plugin Name: Pethoven Performance Tweaks
 * Description: Removes unnecessary front-end bloat, adds security hardening, and optimises asset delivery.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ========================================================================
 * 1. REMOVE EMOJI SCRIPTS & STYLES
 * ===================================================================== */

add_action( 'init', function () {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    add_filter( 'emoji_svg_url', '__return_false' );
} );

/* ========================================================================
 * 2. REMOVE OEMBED, RSD, WLW & REST API LINK TAGS
 * ===================================================================== */

add_action( 'init', function () {
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
} );

/* ========================================================================
 * 3. DISABLE WP-EMBED SCRIPT ON FRONT-END
 * ===================================================================== */

add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_admin() ) {
        wp_deregister_script( 'wp-embed' );
    }
}, 100 );

/* ========================================================================
 * 4. DROP DASHICONS FOR LOGGED-OUT VISITORS
 * ===================================================================== */

add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
}, 100 );

/* ========================================================================
 * 5. THROTTLE HEARTBEAT API
 * ===================================================================== */

add_filter( 'heartbeat_settings', function ( $settings ) {
    if ( isset( $settings['interval'] ) && $settings['interval'] < 60 ) {
        $settings['interval'] = 60;
    }
    return $settings;
} );

/* ========================================================================
 * 6. STRIP VERSION QUERY STRINGS FROM STATIC ASSETS
 * ===================================================================== */

add_filter( 'script_loader_src', 'pt_strip_asset_version_query', 10, 1 );
add_filter( 'style_loader_src', 'pt_strip_asset_version_query', 10, 1 );

function pt_strip_asset_version_query( $src ) {
    if ( strpos( $src, 'ver=' ) !== false ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

/* ========================================================================
 * 7. DISABLE XML-RPC (attack surface & performance)
 * ===================================================================== */

add_filter( 'xmlrpc_enabled', '__return_false' );

add_filter( 'wp_headers', function ( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
} );

/* ========================================================================
 * 8. DISABLE PINGBACKS & TRACKBACKS
 * ===================================================================== */

add_filter( 'pings_open', '__return_false', 10, 2 );

/* ========================================================================
 * 9. ADD DNS PREFETCH / PRECONNECT FOR COMMON ORIGINS
 * ===================================================================== */

add_action( 'wp_head', function () {
    $origins = array(
        'https://fonts.googleapis.com',
        'https://fonts.gstatic.com',
    );
    foreach ( $origins as $origin ) {
        printf(
            '<link rel="preconnect" href="%s" crossorigin>' . "\n",
            esc_url( $origin )
        );
    }
}, 1 );

/* ========================================================================
 * 10. DEFER NON-CRITICAL JAVASCRIPT
 * ===================================================================== */

add_filter( 'script_loader_tag', function ( $tag, $handle, $src ) {
    // Don't defer in admin or for critical scripts.
    if ( is_admin() ) {
        return $tag;
    }

    $no_defer = array( 'jquery-core', 'jquery-migrate', 'wp-polyfill' );
    if ( in_array( $handle, $no_defer, true ) ) {
        return $tag;
    }

    // Skip if already has defer or async.
    if ( strpos( $tag, ' defer' ) !== false || strpos( $tag, ' async' ) !== false ) {
        return $tag;
    }

    return str_replace( ' src=', ' defer src=', $tag );
}, 10, 3 );

/* ========================================================================
 * 11. ADD FETCHPRIORITY="HIGH" TO LCP IMAGE
 * ===================================================================== */

add_filter( 'wp_get_attachment_image_attributes', function ( $attr, $attachment, $size ) {
    if ( is_front_page() && ! is_paged() && 'full' === $size ) {
        $attr['fetchpriority'] = 'high';
        $attr['loading']       = 'eager';
    }
    return $attr;
}, 10, 3 );

/* ========================================================================
 * 12. LIMIT POST REVISIONS TO REDUCE DB BLOAT
 * ===================================================================== */

add_filter( 'wp_revisions_to_keep', function () {
    return 5;
} );

/* ========================================================================
 * 13. DISABLE SELF-PINGS
 * ===================================================================== */

add_action( 'pre_ping', function ( &$links ) {
    $home = home_url();
    foreach ( $links as $i => $link ) {
        if ( strpos( $link, $home ) === 0 ) {
            unset( $links[ $i ] );
        }
    }
} );

/* ========================================================================
 * 14. REMOVE JQUERY MIGRATE (not needed for modern plugins)
 * ===================================================================== */

add_action( 'wp_default_scripts', function ( $scripts ) {
    if ( is_admin() ) {
        return;
    }

    if ( isset( $scripts->registered['jquery'] ) ) {
        $jquery = $scripts->registered['jquery'];
        if ( $jquery->deps ) {
            $jquery->deps = array_diff( $jquery->deps, array( 'jquery-migrate' ) );
        }
    }
} );
