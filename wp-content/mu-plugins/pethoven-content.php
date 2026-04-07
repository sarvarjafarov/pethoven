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
 * Inject announcement bar before the header.
 */
add_action( 'astra_header_before', 'pethoven_announcement_bar' );

function pethoven_announcement_bar() {
    ?>
    <div id="pt-announcement" class="pt-announcement-bar">
        <div class="pt-announcement-inner">
            <button class="pt-ann-prev" aria-label="Previous">&lsaquo;</button>
            <div class="pt-ann-slides">
                <div class="pt-ann-slide pt-ann-active">FREE shipping on orders over $25</div>
                <div class="pt-ann-slide">NEW: Avocado-Lavender formula for sensitive skin</div>
                <div class="pt-ann-slide">Bundle any 2 bottles, get 1 FREE</div>
            </div>
            <button class="pt-ann-next" aria-label="Next">&rsaquo;</button>
        </div>
    </div>
    <?php
}

/**
 * Announcement bar + section reorder styles.
 */
add_action( 'wp_head', 'pethoven_structure_css', 15 );

function pethoven_structure_css() {
    ?>
    <style id="pethoven-structure-css">
        /* ---- Announcement bar ---- */
        .pt-announcement-bar {
            background: #1a3a2a;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-align: center;
            position: relative;
            z-index: 100;
            overflow: hidden;
        }

        .pt-announcement-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px 40px;
            position: relative;
        }

        .pt-ann-slides {
            position: relative;
            width: 100%;
            height: 20px;
            overflow: hidden;
        }

        .pt-ann-slide {
            position: absolute;
            width: 100%;
            text-align: center;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
            line-height: 20px;
        }

        .pt-ann-slide.pt-ann-active {
            opacity: 1;
            transform: translateY(0);
        }

        .pt-ann-prev,
        .pt-ann-next {
            background: none;
            border: none;
            color: rgba(255,255,255,0.6);
            font-size: 20px;
            cursor: pointer;
            padding: 0 8px;
            line-height: 1;
            transition: color 0.2s ease;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
        }

        .pt-ann-prev { left: 8px; }
        .pt-ann-next { right: 8px; }

        .pt-ann-prev:hover,
        .pt-ann-next:hover {
            color: #ffffff;
        }

        @media (max-width: 544px) {
            .pt-announcement-bar { font-size: 11px; }
            .pt-ann-prev, .pt-ann-next { display: none; }
        }

        /* ---- Section reorder using CSS order ---- */
        /* Make the Elementor content area a flex column */
        .entry-content > .elementor,
        .entry-content > .elementor > .elementor-inner,
        .entry-content > .elementor > .elementor-inner > .elementor-section-wrap,
        .elementor-95 {
            display: flex !important;
            flex-direction: column !important;
        }

        /* Hero: 3849851 */
        .elementor-element-3849851 { order: 1 !important; }
        /* Features bar: 966d6bb */
        .elementor-element-966d6bb { order: 2 !important; }
        /* Products: f980f52 */
        .elementor-element-f980f52 { order: 3 !important; }
        /* Basil leaf divider: c6c5202 -- HIDE */
        .elementor-element-c6c5202 { display: none !important; }
        /* Category cards: d349891 */
        .elementor-element-d349891 { order: 4 !important; }
        /* Testimonials + deal: ea9e0d9 -- MOVE UP */
        .elementor-element-ea9e0d9 { order: 5 !important; }
        /* CTA 20% off: 28fc7dc */
        .elementor-element-28fc7dc { order: 6 !important; }
        /* Quiz CTA: 778c9e4 */
        .elementor-element-778c9e4 { order: 7 !important; }
        /* Brand logos: 357f4cd */
        .elementor-element-357f4cd { order: 8 !important; }
    </style>
    <?php
}

/**
 * Announcement bar auto-rotate script.
 */
add_action( 'wp_footer', 'pethoven_announcement_js', 30 );

function pethoven_announcement_js() {
    ?>
    <script id="pethoven-announcement-js">
    (function() {
        var slides = document.querySelectorAll('.pt-ann-slide');
        if (!slides.length) return;

        var current = 0;
        var total = slides.length;

        function show(index) {
            slides[current].classList.remove('pt-ann-active');
            current = (index + total) % total;
            slides[current].classList.add('pt-ann-active');
        }

        var prev = document.querySelector('.pt-ann-prev');
        var next = document.querySelector('.pt-ann-next');
        if (prev) prev.addEventListener('click', function() { show(current - 1); });
        if (next) next.addEventListener('click', function() { show(current + 1); });

        setInterval(function() { show(current + 1); }, 4000);
    })();
    </script>
    <?php
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
            => 'Vet-Approved. Organic. Effective.',

        'Join The Organic Movement!'
            => 'Bath Time They Actually Enjoy.',

        // Hero description (lorem ipsum)
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'
            => 'Dog shampoos made with real ingredients your dog\'s skin needs. No sulfates. No parabens. No tears. Just a clean, soft coat that smells incredible for days.',

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
            => 'Oatmeal and aloe formula. Stops itching. Repairs dry skin. Safe for dogs with allergies.',

        'Fresh Vegetables'
            => 'Deep Clean',

        'Aliquam porta justo nibh, id laoreet sapien sodales vitae justo.'
            => 'Mud, odor, buildup. Gone. Built for active dogs that get into everything.',

        'Organic Legume'
            => 'Puppy Collection',

        'Phasellus sed urna mattis, viverra libero sed, aliquam est.'
            => 'Tear-free. pH-balanced. Formulated for puppies 8 weeks and older.',

        /* ============================================
         * CTA BANNERS
         * ============================================ */

        'Get 25% Off On Your First Purchase!'
            => 'First Order? Save 20%. Code: CLEANCOAT',

        'Try It For Free. No Registration Needed.'
            => 'Not Sure Which Shampoo? Take the 30-Second Quiz.',

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
            => 'Buy 2, Get 1 Free. Any Formula.',

        'I am text block. Click edit button to change this tex em ips.'
            => 'Pick any three bottles. Mix Sensitive Skin, Deep Clean, Puppy. Your call.',

        /* ============================================
         * BRAND LOGOS
         * ============================================ */

        'Featured Brands:'
            => 'Trusted By',

        /* ============================================
         * FOOTER
         * ============================================ */

        'Maecenas mi justo, interdum at consectetur vel, tristique et arcu.'
            => 'Organic dog shampoos. Made in the USA. Backed by vets. Loved by dogs.',

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
