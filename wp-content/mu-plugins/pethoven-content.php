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
    <div id="pt-announcement" class="pt-announcement-bar" role="region" aria-label="Store announcements">
        <div class="pt-announcement-inner">
            <button class="pt-ann-prev" type="button" aria-label="Previous announcement">&lsaquo;</button>
            <div class="pt-ann-slides" aria-live="polite" aria-atomic="true">
                <div class="pt-ann-slide pt-ann-active">FREE shipping on orders over $25</div>
                <div class="pt-ann-slide">NEW: Avocado-Lavender formula for sensitive skin</div>
                <div class="pt-ann-slide">Bundle any 2 bottles, get 1 FREE</div>
            </div>
            <button class="pt-ann-next" type="button" aria-label="Next announcement">&rsaquo;</button>
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
        var paused = false;

        function show(index) {
            slides[current].classList.remove('pt-ann-active');
            current = (index + total) % total;
            slides[current].classList.add('pt-ann-active');
        }

        var bar  = document.getElementById('pt-announcement');
        var prev = document.querySelector('.pt-ann-prev');
        var next = document.querySelector('.pt-ann-next');

        if (prev) prev.addEventListener('click', function() { show(current - 1); });
        if (next) next.addEventListener('click', function() { show(current + 1); });

        // Pause rotation on hover and when tab is hidden
        if (bar) {
            bar.addEventListener('mouseenter', function() { paused = true;  });
            bar.addEventListener('mouseleave', function() { paused = false; });
            bar.addEventListener('focusin',   function() { paused = true;  });
            bar.addEventListener('focusout',  function() { paused = false; });
        }
        document.addEventListener('visibilitychange', function() {
            paused = document.hidden;
        });

        setInterval(function() {
            if (!paused) show(current + 1);
        }, 4500);
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

        /* ============================================
         * ABOUT PAGE
         * ============================================ */

        'We Are Your Favourite Store.'
            => 'Why Pethoven',

        'Numbers Speak For Themselves!'
            => 'Our Commitment In Numbers',

        'We Deal With Various Quality Organic Products!'
            => 'What Goes Into Every Bottle',

        'Tuas quisquam quo gravida proident harum, aptent ligula anim consequuntur, ultrices mauris, nunc voluptates lobortis, varius, potenti placeat! Fuga omnis. Cubilia congue. Recusandae. Vero penatibus quasi! Nostra tenetur dignissimos ultrices natus distinctio ultrices consequuntur numqu.'
            => 'Pethoven started with a frustration: every dog shampoo on the shelf was either harsh drugstore chemistry or expensive fluff. We spent 18 months working with vet dermatologists and formulators to build a shampoo line that actually earns the word "organic" — one that a groomer would pick off a lineup and a sensitive-skin pup could handle weekly without flare-ups.',

        'Officiis fuga harum porro et? Similique rhoncus atque! Netus blanditiis provident nunc posuere. Rem sequi, commodo, lorem tellus elit, hic sem tenetur anim amet quas, malesuada proident platea corrupti expedita.'
            => 'Every formula is sulfate-free, paraben-free, and phthalate-free. We source oatmeal, aloe, coconut oil, and botanicals from US suppliers, bottle in recyclable PET, and test on willing humans (and our own dogs). No shortcuts, no filler ingredients, no greenwashing.',

        /* Short Lorem snippets that appear in various About widgets */
        'Click edit button to change this text. Lorem ipsum dolor sit amet'
            => 'Built for dogs who deserve better than drugstore shampoo',

        /* About page category labels (grocery → shampoo ingredients) */
        '>Dry fruits<'      => '>Oatmeal<',
        '>Fresh vegetables<'=> '>Aloe Vera<',
        '>Dried vegetables<'=> '>Lavender<',
        '>Milk products<'   => '>Coconut Oil<',
        '>Organic honey<'   => '>Manuka Honey<',
        '>Organic tea<'     => '>Green Tea Extract<',

        'Certified Products'
            => 'Certified Clean',

        /* ============================================
         * CONTACT PAGE
         * ============================================ */

        'Frequently Asked Question!'
            => 'Frequently Asked Questions',

        /* Kill fake contact details */
        '+123 456 7890'
            => 'support@pethoven.com',
        'tel:+1234567890'
            => 'mailto:support@pethoven.com',
        'tel:1234567890'
            => 'mailto:support@pethoven.com',
        'info@example.com'
            => 'support@pethoven.com',
        'support@example.com'
            => 'press@pethoven.com',
        'mailto:info@example.com'
            => 'mailto:support@pethoven.com',
        'mailto:support@example.com'
            => 'mailto:press@pethoven.com',
        '1569 Ave, New York, NY 10028, USA'
            => 'Global Tail Goods LLC, New York, NY',
        '1569 Ave, New York, NY 10028'
            => 'Global Tail Goods LLC, New York, NY',

        /* ============================================
         * ALT TEXT FIXES (a11y + SEO)
         * ============================================ */

        'alt="banner 01"'  => 'alt="Pethoven lifestyle"',
        'alt="banner 02"'  => 'alt="Pethoven lifestyle"',
        'alt="banner 03"'  => 'alt="Pethoven lifestyle"',

        /* ============================================
         * SHOP CATEGORY DESCRIPTIONS
         *
         * The "Groceries" category description is live
         * Lorem ipsum. Replace with real category copy.
         * ============================================ */

        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim, velit et luctus interdum, est quam scelerisque tellus, eget luctus mi diam vitae erat. Praesent porttitor lacus vitae dictum posuere. Suspendisse elementum metus ac dolor tincidunt, eu imperdiet nisi dictum.'
            => 'Sulfate-free. Paraben-free. Cruelty-free. Every formula is built on plant-based actives that clean deep without stripping your dog\'s natural oils. Free shipping on orders over $25.',

    );

    // Run all replacements
    $html = str_replace(
        array_keys( $replacements ),
        array_values( $replacements ),
        $html
    );

    // Safety net: nuke any stray Lorem ipsum paragraph that snuck past
    // the explicit replacements above. Matches <p>Lorem ipsum ...</p>.
    $html = preg_replace(
        '#<p[^>]*>\s*Lorem ipsum[^<]*</p>#i',
        '',
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
     * FAQ REPLACEMENTS (Contact page accordion)
     *
     * The source has 6 items with only 3 unique questions:
     *   "Pulvinar nostrud class cum facilis?"  × 1
     *   "Pon excepturi numquam, facilis?"      × 3
     *   "Consequat nesciunt fusce facilisi?"   × 2
     * All 6 share the same Lorem ipsum answer.
     * We replace each duplicate one at a time so every FAQ item
     * ends up with a unique real question + answer.
     * ============================================ */

    $faq_questions_pon = array(
        'Is Pethoven safe for puppies?',
        'How often should I bathe my dog?',
        'Do you test on animals?',
    );
    foreach ( $faq_questions_pon as $q ) {
        $html = preg_replace(
            '/Pon excepturi numquam, facilis\?/',
            $q,
            $html,
            1
        );
    }

    $faq_questions_consequat = array(
        'How fast is shipping?',
        'What is your return policy?',
    );
    foreach ( $faq_questions_consequat as $q ) {
        $html = preg_replace(
            '/Consequat nesciunt fusce facilisi\?/',
            $q,
            $html,
            1
        );
    }

    // Single instance — safe to str_replace
    $html = str_replace(
        'Pulvinar nostrud class cum facilis?',
        'What ingredients are in Pethoven shampoo?',
        $html
    );

    // Answers: all 6 are the same Lorem ipsum — replace in order with 6 unique answers
    $faq_answers = array(
        'Our formulas are built around oatmeal, aloe vera, coconut oil, and botanical extracts. Zero sulfates, parabens, phthalates, or synthetic dyes. Every ingredient is on the label — no "fragrance" catch-alls.',
        'Yes. Our Puppy Collection is tear-free, pH-balanced, and formulated for puppies 8 weeks and older. The Sensitive Skin formula is puppy-safe too.',
        'Every 2–4 weeks works for most dogs. More often if they roll in something memorable, less often if they have dry skin. Our formulas are gentle enough for weekly use.',
        'Never. Pethoven is certified cruelty-free. We test our formulas on willing human volunteers and — carefully — our own dogs.',
        'Standard US shipping is 3–5 business days. Orders over $25 ship free. Priority shipping is available at checkout.',
        '30-day money-back guarantee, no questions asked. If your dog doesn\'t love it, email us and we\'ll refund you — even if the bottle is half empty.',
    );

    $faq_answer_placeholder = 'I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar leo.';

    foreach ( $faq_answers as $answer ) {
        $html = preg_replace(
            '/' . preg_quote( $faq_answer_placeholder, '/' ) . '/',
            esc_html( $answer ),
            $html,
            1
        );
    }

    // Catch any remaining answer variants (some Elementor builds strip the trailing period or use a slightly shorter Lorem)
    $html = preg_replace(
        '/I am item content\. Click edit button to change this text\. Lorem ipsum[^<]*/',
        esc_html( 'Email us at support@pethoven.com — we usually respond within a few hours.' ),
        $html
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
     * CONTACT PAGE: inject a styled mailto card
     *
     * The Contact page has no working form and the old contact
     * tiles were demo data. We drop in a minimal card with real
     * mailto links so visitors can actually reach the brand.
     * ============================================ */

    if ( is_page( 'contact' ) || is_page( 'contact-us' ) ) {
        $contact_card = '<div class="pt-contact-card" aria-label="How to reach Pethoven">'
            . '<div class="pt-contact-tile">'
                . '<span class="pt-contact-tile-label">Support</span>'
                . '<a href="mailto:support@pethoven.com">support@pethoven.com</a>'
            . '</div>'
            . '<div class="pt-contact-tile">'
                . '<span class="pt-contact-tile-label">Press &amp; Partnerships</span>'
                . '<a href="mailto:press@pethoven.com">press@pethoven.com</a>'
            . '</div>'
            . '<div class="pt-contact-tile">'
                . '<span class="pt-contact-tile-label">Response Time</span>'
                . '<span class="pt-contact-tile-value">Within 24h, Mon&ndash;Fri</span>'
            . '</div>'
        . '</div>';

        // Insert right after the "Get In Touch" heading if present,
        // otherwise fall back to inserting before the FAQ section.
        if ( preg_match( '/<h[1-3][^>]*>\s*Get In Touch\s*<\/h[1-3]>/i', $html ) ) {
            $html = preg_replace(
                '/(<h[1-3][^>]*>\s*Get In Touch\s*<\/h[1-3]>)/i',
                '$1' . $contact_card,
                $html,
                1
            );
        } elseif ( strpos( $html, 'Frequently Asked Questions' ) !== false ) {
            $html = preg_replace(
                '/(<[^>]+>\s*Frequently Asked Questions\s*<\/[^>]+>)/i',
                $contact_card . '$1',
                $html,
                1
            );
        }
    }

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
