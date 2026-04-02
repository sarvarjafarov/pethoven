<?php
/**
 * Pethoven custom logo override.
 *
 * Plugin Name: Pethoven Logo
 * Description: Serves the branded Pethoven logo without requiring a media-library upload.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
 * Logo sizing: keep the logo crisp and properly spaced in the header.
 * Works with Astra's default header and the Header Builder.
 */
add_action( 'wp_head', 'pethoven_logo_styles', 20 );

function pethoven_logo_styles() {
    ?>
    <style id="pethoven-logo-css">
        /* Desktop: generous logo size for brand presence */
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

        /* Tablet */
        @media (max-width: 921px) {
            .site-logo-img .custom-logo-link .custom-logo,
            .ast-site-identity .custom-logo-link .custom-logo,
            img.custom-logo {
                max-height: 48px;
            }
        }

        /* Mobile */
        @media (max-width: 544px) {
            .site-logo-img .custom-logo-link .custom-logo,
            .ast-site-identity .custom-logo-link .custom-logo,
            img.custom-logo {
                max-height: 40px;
            }
        }
    </style>
    <?php
}
