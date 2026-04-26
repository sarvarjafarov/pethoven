<?php
/**
 * Pethoven UI enhancements — animations, micro-interactions, and polish.
 *
 * Plugin Name: Pethoven UI
 * Description: Adds scroll-reveal animations, hover effects, sticky header, and micro-interactions.
 *
 * The actual CSS and JS live as static assets in ./assets/ (extracted
 * from formerly-inline <style>/<script> blocks for browser caching +
 * gzipped delivery — saves ~225KB on every HTML response).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( is_admin() ) {
    return;
}

add_action( 'wp_enqueue_scripts', 'pethoven_ui_enqueue', 30 );
add_filter( 'wp_resource_hints', 'pethoven_ui_resource_hints', 10, 2 );

/**
 * Preconnect / dns-prefetch hints for the third-party origins we
 * load on every page. Saves the DNS lookup + TLS handshake on first
 * use of fonts and the GA4 gtag bundle. Output by core via the
 * standard <link rel="preconnect"> tags in <head>.
 */
function pethoven_ui_resource_hints( $hints, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $hints[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
        $hints[] = 'https://www.googletagmanager.com';
    }
    if ( 'dns-prefetch' === $relation_type ) {
        $hints[] = '//fonts.googleapis.com';
    }
    return $hints;
}

function pethoven_ui_enqueue() {
    $base_dir = __DIR__ . '/assets';
    $base_url = WPMU_PLUGIN_URL . '/assets';

    $css_path = $base_dir . '/pethoven-ui.css';
    $js_path  = $base_dir . '/pethoven-ui.js';

    if ( file_exists( $css_path ) ) {
        wp_enqueue_style(
            'pethoven-ui',
            $base_url . '/pethoven-ui.css',
            array(),
            (string) filemtime( $css_path )
        );
    }

    if ( file_exists( $js_path ) ) {
        wp_enqueue_script(
            'pethoven-ui',
            $base_url . '/pethoven-ui.js',
            array(),
            (string) filemtime( $js_path ),
            true /* in_footer */
        );
    }
}

/**
 * Defer the pethoven-ui script tag so it doesn't block parse/render.
 * The script is self-contained (no document.write, no inline-after
 * dependencies) so defer is safe.
 */
add_filter( 'script_loader_tag', 'pethoven_ui_defer_tag', 10, 2 );

function pethoven_ui_defer_tag( $tag, $handle ) {
    if ( 'pethoven-ui' === $handle && false === strpos( $tag, ' defer' ) ) {
        $tag = preg_replace( '/<script /', '<script defer ', $tag, 1 );
    }
    return $tag;
}
