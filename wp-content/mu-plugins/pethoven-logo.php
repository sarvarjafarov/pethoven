<?php
/**
 * Pethoven custom logo override.
 *
 * Plugin Name: Pethoven Logo
 * Description: Serves the branded Pethoven logo, favicon, and web manifest.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ========================================================================
 * FAVICON & WEB MANIFEST
 * ===================================================================== */

/**
 * Override WordPress site icon with the Pethoven paw favicon.
 * Removes default wp_head site icon output and injects our own tags.
 */
add_action( 'wp_head', 'pethoven_favicon', 1 );
add_action( 'admin_head', 'pethoven_favicon', 1 );
add_action( 'login_head', 'pethoven_favicon', 1 );

function pethoven_favicon() {
    $base = content_url( 'mu-plugins/assets' );
    ?>
    <link rel="icon" href="<?php echo esc_url( $base . '/favicon.ico' ); ?>" sizes="48x48">
    <link rel="icon" href="<?php echo esc_url( $base . '/favicon-32x32.png' ); ?>" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo esc_url( $base . '/favicon-16x16.png' ); ?>" sizes="16x16" type="image/png">
    <link rel="icon" href="<?php echo esc_url( $base . '/favicon.png' ); ?>" sizes="270x270" type="image/png">
    <link rel="apple-touch-icon" href="<?php echo esc_url( $base . '/apple-touch-icon.png' ); ?>" sizes="180x180">
    <link rel="manifest" href="<?php echo esc_url( $base . '/site.webmanifest' ); ?>">
    <meta name="theme-color" content="#0e9a4e">
    <?php
}

/** Remove WordPress default site icon output to avoid duplicates. */
add_action( 'init', function () {
    remove_action( 'wp_head', 'wp_site_icon', 99 );
} );

/** Strip any other favicon/site-icon links injected by plugins or themes. */
add_action( 'wp_head', 'pethoven_remove_extra_favicons', 999 );

function pethoven_remove_extra_favicons() {
    // Remove via output buffering any stale site-icon references.
    ob_start( function ( $html ) {
        // Remove <link> tags with "site-icon" or "shortcut icon" that aren't ours.
        $html = preg_replace(
            '/<link[^>]*(rel=["\'](?:shortcut icon|icon)["\'])[^>]*wp-content\/uploads[^>]*>/i',
            '',
            $html
        );
        return $html;
    } );
}

/* ========================================================================
 * HEADER LOGO
 * ===================================================================== */

/**
 * Override get_custom_logo() output with the Pethoven branded logo.
 *
 * Uses a responsive srcset so retina screens get a crisp 2x version while
 * standard screens load the lighter 1x file.
 */
add_filter( 'get_custom_logo', 'pethoven_custom_logo', 20 );

function pethoven_custom_logo( $html ) {
    $base_url = content_url( 'mu-plugins/assets' );
    $logo_1x  = $base_url . '/pethoven-logo-1x.png';
    $logo_2x  = $base_url . '/pethoven-logo-2x.png';
    $home     = esc_url( home_url( '/' ) );
    $name     = esc_attr( get_bloginfo( 'name' ) );

    return sprintf(
        '<a href="%s" class="custom-logo-link" rel="home" aria-current="page">'
        . '<img src="%s" srcset="%s 1x, %s 2x" alt="%s" class="custom-logo" '
        . 'width="275" height="60" loading="eager" fetchpriority="high" decoding="async">'
        . '</a>',
        $home,
        esc_url( $logo_1x ),
        esc_url( $logo_1x ),
        esc_url( $logo_2x ),
        $name
    );
}

/**
 * Tell WordPress a custom logo is set so Astra renders the logo block.
 */
add_filter( 'theme_mod_custom_logo', 'pethoven_force_custom_logo_truthy' );

function pethoven_force_custom_logo_truthy( $value ) {
    if ( ! $value ) {
        return -1; // Truthy value triggers logo rendering in Astra.
    }
    return $value;
}

/**
 * Replace the Organic Store demo logo in footer widgets with the white
 * Pethoven logo. Catches image widgets, custom HTML widgets, and text widgets.
 */
add_filter( 'widget_display_callback', 'pethoven_replace_footer_widget_logo', 20, 3 );

function pethoven_replace_footer_widget_logo( $instance, $widget, $args ) {
    // Only act on footer sidebars.
    if ( empty( $args['id'] ) || strpos( $args['id'], 'footer' ) === false ) {
        return $instance;
    }

    // Buffer the widget output so we can search-replace images in it.
    ob_start();
    $widget->widget( $args, $instance );
    $html = ob_get_clean();

    if ( $html && preg_match( '/<img[^>]*organic[^>]*>/i', $html ) ) {
        $base_url = content_url( 'mu-plugins/assets' );
        $logo_1x  = esc_url( $base_url . '/pethoven-logo-white-1x.png' );
        $logo_2x  = esc_url( $base_url . '/pethoven-logo-white-2x.png' );
        $name     = esc_attr( get_bloginfo( 'name' ) );

        $replacement = sprintf(
            '<img src="%s" srcset="%s 1x, %s 2x" alt="%s" '
            . 'width="275" height="60" loading="lazy" decoding="async" '
            . 'style="max-height:50px;width:auto;height:auto;object-fit:contain;">',
            $logo_1x,
            $logo_1x,
            $logo_2x,
            $name
        );

        $html = preg_replace( '/<img[^>]*organic[^>]*>/i', $replacement, $html );
        echo $html;
        return false; // Prevent default widget rendering since we already echoed.
    }

    echo $html;
    return false;
}

/**
 * Also replace via output buffering as a safety net — catches logos rendered
 * outside standard widgets (Elementor footer, custom templates, etc.).
 */
add_action( 'astra_footer_before', 'pethoven_footer_ob_start' );
add_action( 'astra_footer_after', 'pethoven_footer_ob_end' );

function pethoven_footer_ob_start() {
    ob_start();
}

function pethoven_footer_ob_end() {
    $html = ob_get_clean();

    if ( $html && preg_match( '/<img[^>]*organic[^>]*>/i', $html ) ) {
        $base_url = content_url( 'mu-plugins/assets' );
        $logo_1x  = esc_url( $base_url . '/pethoven-logo-white-1x.png' );
        $logo_2x  = esc_url( $base_url . '/pethoven-logo-white-2x.png' );
        $name     = esc_attr( get_bloginfo( 'name' ) );

        $replacement = sprintf(
            '<img src="%s" srcset="%s 1x, %s 2x" alt="%s" '
            . 'width="275" height="60" loading="lazy" decoding="async" '
            . 'style="max-height:50px;width:auto;height:auto;object-fit:contain;">',
            $logo_1x,
            $logo_1x,
            $logo_2x,
            $name
        );

        $html = preg_replace( '/<img[^>]*organic[^>]*>/i', $replacement, $html );
    }

    echo $html;
}

/* ========================================================================
 * HERO IMAGE REPLACEMENT
 * ===================================================================== */

/**
 * Replace the Organic Store hero image with the Pethoven product shot.
 * Uses output buffering on the full page to swap the image src/srcset.
 */
add_action( 'template_redirect', 'pethoven_hero_image_buffer' );

function pethoven_hero_image_buffer() {
    if ( is_admin() ) {
        return;
    }

    ob_start( 'pethoven_replace_hero_image' );
}

function pethoven_replace_hero_image( $html ) {
    if ( ! $html ) {
        return $html;
    }

    $base_url = content_url( 'mu-plugins/assets' );
    $webp     = esc_url( $base_url . '/hero-product.webp' );
    $webp_2x  = esc_url( $base_url . '/hero-product-2x.webp' );
    $png      = esc_url( $base_url . '/hero-product.png' );

    // Replace the organic hero product image
    $html = preg_replace_callback(
        '/<img([^>]*(?:organic-products-hero|organic-products)[^>]*)>/i',
        function ( $matches ) use ( $webp, $webp_2x, $png ) {
            return sprintf(
                '<picture>'
                . '<source srcset="%s 1x, %s 2x" type="image/webp">'
                . '<img src="%s" alt="Pethoven Dog Shampoo" '
                . 'width="800" height="644" loading="eager" fetchpriority="high" '
                . 'decoding="async" class="attachment-full size-full">'
                . '</picture>',
                $webp,
                $webp_2x,
                $png
            );
        },
        $html
    );

    return $html;
}

/**
 * Logo sizing and footer logo styles.
 */
add_action( 'wp_head', 'pethoven_logo_styles', 20 );

function pethoven_logo_styles() {
    ?>
    <style id="pethoven-logo-css">
        /* ---- Header logo ---- */
        .site-logo-img .custom-logo-link .custom-logo,
        .ast-site-identity .custom-logo-link .custom-logo,
        img.custom-logo {
            max-height: 60px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .site-logo-img .custom-logo-link,
        .ast-site-identity .custom-logo-link {
            display: inline-flex;
            align-items: center;
        }

        @media (max-width: 921px) {
            .site-logo-img .custom-logo-link .custom-logo,
            .ast-site-identity .custom-logo-link .custom-logo,
            img.custom-logo {
                max-height: 48px;
            }
        }

        @media (max-width: 544px) {
            .site-logo-img .custom-logo-link .custom-logo,
            .ast-site-identity .custom-logo-link .custom-logo,
            img.custom-logo {
                max-height: 40px;
            }
        }

        /* ---- Footer logo (replaced widget) ---- */
        .site-footer img[srcset*="pethoven-logo-white"] {
            max-height: 50px;
            width: auto;
            height: auto;
        }

        @media (max-width: 544px) {
            .site-footer img[srcset*="pethoven-logo-white"] {
                max-height: 38px;
            }
        }
    </style>
    <?php
}
