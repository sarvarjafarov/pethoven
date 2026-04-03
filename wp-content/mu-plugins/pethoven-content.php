<?php
/**
 * Pethoven content overrides.
 *
 * Plugin Name: Pethoven Content
 * Description: Replaces Organic Store demo text with Pethoven dog shampoo content.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( is_admin() ) {
    return;
}

/**
 * Buffer the full front-end output and run all text replacements in one pass.
 */
add_action( 'template_redirect', 'pethoven_content_buffer', 1 );

function pethoven_content_buffer() {
    ob_start( 'pethoven_rewrite_content' );
}

function pethoven_rewrite_content( $html ) {
    if ( ! $html ) {
        return $html;
    }

    $replacements = array(

        /* ============================================
         * HERO SECTION
         * ============================================ */

        'Best Quality Products'
            => 'Premium Dog Care',

        'Join The Organic Movement!'
            => 'Clean Coat. Happy Dog.',

        // Hero description (lorem ipsum)
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'
            => 'Organic, vet-approved dog shampoos made with real ingredients. Gentle on skin. Tough on dirt. No sulfates, no parabens, no compromise.',

        /* ============================================
         * FEATURES BAR
         * ============================================ */

        'Above $5 Only'
            => 'On orders over $25',

        'Certified Organic'
            => '100% Organic',

        '100% Guarantee'
            => 'No harsh chemicals. Ever.',

        'Huge Savings'
            => 'Bundle & Save',

        'At Lowest Price'
            => 'Up to 30% off multi-packs',

        'No Questions Asked'
            => '30-day money-back guarantee',

        /* ============================================
         * PRODUCTS SECTION
         * ============================================ */

        'Best Selling Products'
            => 'Best Sellers',

        /* ============================================
         * CATEGORY CARDS
         * ============================================ */

        'Farm Fresh Fruits'
            => 'Sensitive Skin',

        'Ut sollicitudin quam vel purus tempus, vel eleifend felis varius.'
            => 'Gentle formulas for dogs with allergies or dry, itchy skin. Oatmeal and aloe based.',

        'Fresh Vegetables'
            => 'Deep Clean',

        'Aliquam porta justo nibh, id laoreet sapien sodales vitae justo.'
            => 'Built for active dogs. Cuts through mud, odor, and buildup without stripping natural oils.',

        'Organic Legume'
            => 'Puppy Collection',

        'Phasellus sed urna mattis, viverra libero sed, aliquam est.'
            => 'Tear-free, pH-balanced formulas safe for puppies 8 weeks and older.',

        /* ============================================
         * CTA BANNERS
         * ============================================ */

        'Get 25% Off On Your First Purchase!'
            => 'Get 20% Off Your First Order',

        'Try It For Free. No Registration Needed.'
            => 'Not Sure Which Formula? Take Our Quick Quiz.',

        /* ============================================
         * TESTIMONIALS
         * ============================================ */

        'Customers Reviews'
            => 'What Dog Owners Say',

        // Both testimonials have the same placeholder
        'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'
            => '',

        'Mila Kunis'
            => 'Sarah K.',

        'Mike Sendler'
            => 'James T.',

        /* ============================================
         * DEAL SECTION
         * ============================================ */

        'Deal Of The Day 15% Off On All Vegetables!'
            => 'Bundle Deal: Buy 2, Get 1 Free',

        'I am text block. Click edit button to change this tex em ips.'
            => 'Mix and match any formula. Limited time offer.',

        /* ============================================
         * BRAND LOGOS
         * ============================================ */

        'Featured Brands:'
            => 'Trusted By',

        /* ============================================
         * FOOTER
         * ============================================ */

        'Maecenas mi justo, interdum at consectetur vel, tristique et arcu.'
            => 'Organic dog shampoos made in the USA. Because your dog deserves better.',

        'Know More About Us'
            => 'About Us',

        'Visit Store'
            => 'Shop All',

        "Let's Connect"
            => 'Contact',

        'Locate Stores'
            => 'Store Locator',

        'Offers Coupons'
            => 'Deals',

    );

    // Run all replacements
    $html = str_replace(
        array_keys( $replacements ),
        array_values( $replacements ),
        $html
    );

    // Fix the two testimonials individually since both had identical placeholder text.
    // After the first pass they're now empty. Inject real reviews.
    $review_1 = 'Our golden retriever had flaky skin for months. Two washes with the Sensitive Skin formula and it cleared up completely. Coat is softer than it has been in years.';
    $review_2 = 'I tried five different dog shampoos before Pethoven. This is the only one that actually removes that wet dog smell and keeps his coat shiny for days.';

    // Find testimonial wrappers and inject content
    $html = preg_replace(
        '/(<div class="elementor-testimonial-content">)\s*(<p>)\s*(<\/p>)/i',
        '$1$2' . esc_html( $review_1 ) . '$3',
        $html,
        1 // Only first match
    );

    $html = preg_replace(
        '/(<div class="elementor-testimonial-content">)\s*(<p>)\s*(<\/p>)/i',
        '$1$2' . esc_html( $review_2 ) . '$3',
        $html,
        1 // Second match (now the first remaining empty one)
    );

    /* ============================================
     * HERO IMAGE SWAP
     * ============================================ */

    $base_url = content_url( 'mu-plugins/assets' );
    $webp     = esc_url( $base_url . '/hero-product.webp' );
    $webp_2x  = esc_url( $base_url . '/hero-product-2x.webp' );
    $png      = esc_url( $base_url . '/hero-product.png' );

    $html = preg_replace(
        '/<img([^>]*(?:organic-products-hero|organic-products)[^>]*)>/i',
        sprintf(
            '<picture>'
            . '<source srcset="%s 1x, %s 2x" type="image/webp">'
            . '<img src="%s" alt="Pethoven Dog Shampoo" '
            . 'width="800" height="644" loading="eager" fetchpriority="high" '
            . 'decoding="async" class="attachment-full size-full">'
            . '</picture>',
            $webp,
            $webp_2x,
            $png
        ),
        $html
    );

    /* ============================================
     * FOOTER LOGO SWAP (Organic Store → Pethoven white)
     * ============================================ */

    if ( preg_match( '/<img[^>]*organic[^>]*>/i', $html ) ) {
        $logo_1x = esc_url( $base_url . '/pethoven-logo-white-1x.png' );
        $logo_2x = esc_url( $base_url . '/pethoven-logo-white-2x.png' );
        $name    = esc_attr( get_bloginfo( 'name' ) );

        $logo_replacement = sprintf(
            '<img src="%s" srcset="%s 1x, %s 2x" alt="%s" '
            . 'width="275" height="60" loading="lazy" decoding="async" '
            . 'style="max-height:50px;width:auto;height:auto;object-fit:contain;">',
            $logo_1x, $logo_1x, $logo_2x, $name
        );

        $html = preg_replace( '/<img[^>]*organic[^>]*>/i', $logo_replacement, $html );
    }

    return $html;
}
