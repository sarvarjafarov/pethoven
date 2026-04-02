<?php
/**
 * Pethoven UI enhancements — animations, micro-interactions, and polish.
 *
 * Plugin Name: Pethoven UI
 * Description: Adds scroll-reveal animations, hover effects, sticky header, and micro-interactions.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Only load on the front-end. */
if ( is_admin() ) {
    return;
}

add_action( 'wp_head', 'pethoven_ui_css', 30 );
add_action( 'wp_footer', 'pethoven_ui_js', 30 );

/* ========================================================================
 * CSS — Animations, transitions, hover effects
 * ===================================================================== */

function pethoven_ui_css() {
    ?>
    <style id="pethoven-ui-css">

    /* ==========================================================
     * 1. SCROLL-REVEAL BASE
     * ========================================================== */

    .pt-reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1),
                    transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .pt-reveal.pt-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Staggered children — each child delays slightly */
    .pt-stagger > .e-con,
    .pt-stagger > .elementor-widget {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1),
                    transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .pt-stagger.pt-visible > .e-con:nth-child(1),
    .pt-stagger.pt-visible > .elementor-widget:nth-child(1) { transition-delay: 0s; opacity: 1; transform: translateY(0); }
    .pt-stagger.pt-visible > .e-con:nth-child(2),
    .pt-stagger.pt-visible > .elementor-widget:nth-child(2) { transition-delay: 0.12s; opacity: 1; transform: translateY(0); }
    .pt-stagger.pt-visible > .e-con:nth-child(3),
    .pt-stagger.pt-visible > .elementor-widget:nth-child(3) { transition-delay: 0.24s; opacity: 1; transform: translateY(0); }
    .pt-stagger.pt-visible > .e-con:nth-child(4),
    .pt-stagger.pt-visible > .elementor-widget:nth-child(4) { transition-delay: 0.36s; opacity: 1; transform: translateY(0); }
    .pt-stagger.pt-visible > .e-con:nth-child(5),
    .pt-stagger.pt-visible > .elementor-widget:nth-child(5) { transition-delay: 0.48s; opacity: 1; transform: translateY(0); }

    /* Fade-in-left / right for two-column hero */
    .pt-fade-left {
        opacity: 0;
        transform: translateX(-50px);
        transition: opacity 0.8s cubic-bezier(0.22, 1, 0.36, 1),
                    transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .pt-fade-right {
        opacity: 0;
        transform: translateX(50px);
        transition: opacity 0.8s cubic-bezier(0.22, 1, 0.36, 1),
                    transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .pt-fade-left.pt-visible,
    .pt-fade-right.pt-visible {
        opacity: 1;
        transform: translateX(0);
    }

    /* Scale-in for images */
    .pt-scale-in {
        opacity: 0;
        transform: scale(0.9);
        transition: opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1),
                    transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .pt-scale-in.pt-visible {
        opacity: 1;
        transform: scale(1);
    }

    /* ==========================================================
     * 2. HERO SECTION ENTRANCE
     * ========================================================== */

    @keyframes pt-hero-float {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-12px); }
    }

    .pt-hero-image img {
        animation: pt-hero-float 4s ease-in-out infinite;
        animation-delay: 1s;
    }

    /* ==========================================================
     * 3. STICKY HEADER WITH SCROLL EFFECT
     * ========================================================== */

    .ast-primary-header-bar {
        transition: box-shadow 0.3s ease,
                    background-color 0.3s ease,
                    padding 0.3s ease;
    }

    .pt-header-scrolled .ast-primary-header-bar {
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    /* ==========================================================
     * 4. PRODUCT CARD EFFECTS
     * ========================================================== */

    .astra-shop-thumbnail-wrap {
        overflow: hidden;
        border-radius: 12px;
    }

    .ast-article-single.product {
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        border-radius: 12px;
    }

    .ast-article-single.product:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.1);
    }

    .ast-article-single.product .astra-shop-thumbnail-wrap img {
        transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .ast-article-single.product:hover .astra-shop-thumbnail-wrap img {
        transform: scale(1.08);
    }

    /* Sale badge pulse */
    .ast-article-single.product .onsale {
        animation: pt-pulse-badge 2s ease-in-out infinite;
    }

    @keyframes pt-pulse-badge {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.08); }
    }

    /* Product title hover */
    .woocommerce-loop-product__title {
        transition: color 0.25s ease;
    }

    .ast-article-single.product:hover .woocommerce-loop-product__title {
        color: var(--ast-global-color-0, #8bc34a);
    }

    /* ==========================================================
     * 5. BUTTON MICRO-INTERACTIONS
     * ========================================================== */

    .elementor-button {
        position: relative;
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.3s cubic-bezier(0.22, 1, 0.36, 1) !important;
    }

    .elementor-button::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .elementor-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(106, 151, 57, 0.3) !important;
    }

    .elementor-button:hover::after {
        transform: translateX(100%);
    }

    .elementor-button:active {
        transform: translateY(0) scale(0.98);
    }

    /* Cart button (Add to Cart) */
    .button.add_to_cart_button,
    .single_add_to_cart_button {
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
    }

    .button.add_to_cart_button:hover,
    .single_add_to_cart_button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(106, 151, 57, 0.25);
    }

    /* ==========================================================
     * 6. FEATURES BAR — clean white card design
     * ========================================================== */

    /* Section: floating white card overlapping hero */
    .pt-features-bar,
    .pt-features-bar.e-con,
    .elementor-element.pt-features-bar {
        background: transparent !important;
        background-color: transparent !important;
        background-image: none !important;
        padding: 0 20px !important;
        margin-top: -40px !important;
        position: relative;
        z-index: 10;
    }

    .pt-features-bar > .e-con-inner {
        max-width: 1200px;
        margin: 0 auto;
        background: #ffffff !important;
        border-radius: 20px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.06),
                    0 1px 3px rgba(0, 0, 0, 0.04);
        padding: 0 !important;
        display: flex !important;
        flex-wrap: wrap;
        overflow: hidden;
    }

    /* Each feature card — kill Elementor dark backgrounds */
    .pt-features-bar .e-con.e-child,
    .pt-features-bar .e-con.e-child.e-con-full,
    .pt-features-bar > .e-con-inner > .e-con,
    .pt-features-bar > .e-con-inner > .elementor-element {
        flex: 1 1 0 !important;
        min-width: 0 !important;
        position: relative;
        padding: 32px 28px !important;
        background: transparent !important;
        background-color: transparent !important;
        background-image: none !important;
        border: none !important;
        border-radius: 0 !important;
        transition: background-color 0.3s ease;
    }

    /* Vertical divider between cards */
    .pt-features-bar .e-con.e-child::after {
        content: '';
        position: absolute;
        right: 0;
        top: 24%;
        height: 52%;
        width: 1px;
        background: #e8e8e8;
        transition: opacity 0.3s ease;
    }

    .pt-features-bar .e-con.e-child:last-child::after {
        display: none;
    }

    /* Hover: subtle green tint */
    .pt-features-bar .e-con.e-child:hover {
        background-color: rgba(139, 195, 74, 0.04) !important;
    }

    .pt-features-bar .e-con.e-child:first-child:hover {
        border-radius: 20px 0 0 20px !important;
    }

    .pt-features-bar .e-con.e-child:last-child:hover {
        border-radius: 0 20px 20px 0 !important;
    }

    /* Icon: circle with light green background */
    .pt-features-bar .elementor-icon-box-icon {
        margin-bottom: 0 !important;
    }

    .pt-features-bar .elementor-icon {
        width: 52px !important;
        height: 52px !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(139, 195, 74, 0.12) 0%, rgba(139, 195, 74, 0.06) 100%);
        border-radius: 50%;
        border: none !important;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1),
                    background 0.4s ease,
                    box-shadow 0.4s ease;
    }

    .pt-features-bar .elementor-icon i {
        font-size: 20px !important;
        color: var(--ast-global-color-1, #6a9739) !important;
        transition: color 0.3s ease, transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* Hover: icon pops */
    .pt-features-bar .e-con.e-child:hover .elementor-icon {
        transform: scale(1.1) rotate(-5deg);
        background: linear-gradient(135deg, rgba(139, 195, 74, 0.22) 0%, rgba(139, 195, 74, 0.1) 100%);
        box-shadow: 0 4px 16px rgba(139, 195, 74, 0.18);
    }

    .pt-features-bar .e-con.e-child:hover .elementor-icon i {
        color: var(--ast-global-color-0, #8bc34a) !important;
        transform: scale(1.15);
    }

    /* Layout: icon left, text right */
    .pt-features-bar .elementor-icon-box-wrapper {
        display: flex !important;
        align-items: center;
        gap: 14px;
        transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .pt-features-bar .e-con.e-child:hover .elementor-icon-box-wrapper {
        transform: translateY(-2px);
    }

    /* Text */
    .pt-features-bar .elementor-icon-box-content {
        text-align: left !important;
    }

    .pt-features-bar .elementor-icon-box-title {
        margin-bottom: 2px !important;
    }

    .pt-features-bar .elementor-icon-box-title,
    .pt-features-bar .elementor-icon-box-title a,
    .pt-features-bar h4.elementor-icon-box-title,
    .pt-features-bar h4.elementor-icon-box-title a {
        font-size: 15px !important;
        font-weight: 700 !important;
        color: #1a1a1a !important;
        text-decoration: none !important;
        letter-spacing: 0.2px;
        font-family: inherit !important;
    }

    .pt-features-bar .elementor-icon-box-description,
    .pt-features-bar p.elementor-icon-box-description {
        font-size: 13px !important;
        color: #999 !important;
        font-weight: 400 !important;
        line-height: 1.4 !important;
        margin: 0 !important;
    }

    /* ---- Tablet: 2x2 grid ---- */
    @media (max-width: 921px) {
        .pt-features-bar {
            margin-top: -24px !important;
        }

        .pt-features-bar > .e-con-inner {
            flex-wrap: wrap !important;
            border-radius: 16px;
        }

        .pt-features-bar .e-con.e-child {
            flex: 1 1 50% !important;
            padding: 24px 20px !important;
        }

        /* Remove vertical dividers, add bottom border instead */
        .pt-features-bar .e-con.e-child::after {
            display: none;
        }

        .pt-features-bar .e-con.e-child:nth-child(1),
        .pt-features-bar .e-con.e-child:nth-child(2) {
            border-bottom: 1px solid #f0f0f0;
        }

        .pt-features-bar .e-con.e-child:nth-child(odd) {
            border-right: 1px solid #f0f0f0;
        }

        .pt-features-bar .e-con.e-child:hover {
            border-radius: 0 !important;
        }

        .pt-features-bar .e-con.e-child:first-child:hover { border-radius: 16px 0 0 0 !important; }
        .pt-features-bar .e-con.e-child:nth-child(2):hover { border-radius: 0 16px 0 0 !important; }
        .pt-features-bar .e-con.e-child:nth-child(3):hover { border-radius: 0 0 0 16px !important; }
        .pt-features-bar .e-con.e-child:last-child:hover { border-radius: 0 0 16px 0 !important; }
    }

    /* ---- Mobile: single column ---- */
    @media (max-width: 544px) {
        .pt-features-bar {
            margin-top: -16px !important;
            padding: 0 16px !important;
        }

        .pt-features-bar > .e-con-inner {
            border-radius: 14px;
        }

        .pt-features-bar .e-con.e-child {
            flex: 1 1 100% !important;
            padding: 20px !important;
            border-right: none !important;
        }

        .pt-features-bar .e-con.e-child:not(:last-child) {
            border-bottom: 1px solid #f0f0f0 !important;
        }

        .pt-features-bar .e-con.e-child:nth-child(1),
        .pt-features-bar .e-con.e-child:nth-child(2) {
            border-right: none;
        }

        .pt-features-bar .elementor-icon {
            width: 44px !important;
            height: 44px !important;
        }

        .pt-features-bar .elementor-icon i {
            font-size: 18px !important;
        }

        .pt-features-bar .e-con.e-child:hover { border-radius: 0 !important; }
        .pt-features-bar .e-con.e-child:first-child:hover { border-radius: 14px 14px 0 0 !important; }
        .pt-features-bar .e-con.e-child:last-child:hover { border-radius: 0 0 14px 14px !important; }
    }

    /* Generic icon-box hover (for icon boxes outside the features bar) */
    .elementor-widget-icon-box .elementor-icon-box-wrapper {
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .elementor-widget-icon-box:hover .elementor-icon-box-wrapper {
        transform: translateY(-6px);
    }

    /* Don't double-apply to features bar */
    .pt-features-bar .elementor-widget-icon-box:hover .elementor-icon-box-wrapper {
        transform: translateY(-2px);
    }

    /* ==========================================================
     * 7. CATEGORY / IMAGE-BOX CARDS
     * ========================================================== */

    .elementor-widget-image-box .elementor-image-box-wrapper {
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .elementor-widget-image-box:hover .elementor-image-box-wrapper {
        transform: translateY(-6px);
    }

    .elementor-widget-image-box .elementor-image-box-img img {
        transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
        border-radius: 8px;
    }

    .elementor-widget-image-box:hover .elementor-image-box-img img {
        transform: scale(1.05);
    }

    /* ==========================================================
     * 8. TESTIMONIAL CARDS
     * ========================================================== */

    .elementor-widget-testimonial .elementor-testimonial-wrapper {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .elementor-widget-testimonial:hover .elementor-testimonial-wrapper {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    /* Star rating shimmer */
    .elementor-widget-star-rating .elementor-star-rating {
        transition: transform 0.3s ease;
    }

    .elementor-widget-star-rating:hover .elementor-star-rating {
        transform: scale(1.05);
    }

    /* ==========================================================
     * 9. BRAND LOGOS
     * ========================================================== */

    .elementor-widget-image-gallery .gallery-item img {
        filter: grayscale(100%) opacity(0.5);
        transition: filter 0.4s ease, transform 0.4s ease;
    }

    .elementor-widget-image-gallery .gallery-item:hover img {
        filter: grayscale(0%) opacity(1);
        transform: scale(1.1);
    }

    /* ==========================================================
     * 10. NAVIGATION LINKS
     * ========================================================== */

    .ast-builder-menu-1 .menu-item > a {
        position: relative;
    }

    .ast-builder-menu-1 .menu-item > a::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: var(--ast-global-color-0, #8bc34a);
        transition: width 0.3s ease, left 0.3s ease;
    }

    .ast-builder-menu-1 .menu-item > a:hover::after,
    .ast-builder-menu-1 .current-menu-item > a::after {
        width: 100%;
        left: 0;
    }

    /* ==========================================================
     * 11. FOOTER ENHANCEMENTS
     * ========================================================== */

    .site-footer a {
        transition: color 0.25s ease;
    }

    .site-footer .ast-builder-social-element {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
                    opacity 0.3s ease;
    }

    .site-footer .ast-builder-social-element:hover {
        transform: scale(1.2) translateY(-2px);
        opacity: 1;
    }

    /* ==========================================================
     * 12. SMOOTH SCROLL + SELECTION
     * ========================================================== */

    html {
        scroll-behavior: smooth;
    }

    ::selection {
        background: var(--ast-global-color-0, #8bc34a);
        color: #fff;
    }

    /* ==========================================================
     * 13. LEAF DECORATOR ANIMATION
     * ========================================================== */

    @keyframes pt-leaf-sway {
        0%, 100% { transform: rotate(0deg); }
        25%      { transform: rotate(3deg); }
        75%      { transform: rotate(-3deg); }
    }

    .elementor-widget-image img[src*="leaf"],
    .elementor-widget-image img[src*="basil"] {
        animation: pt-leaf-sway 5s ease-in-out infinite;
    }

    /* ==========================================================
     * 14. REDUCED MOTION — respect user preference
     * ========================================================== */

    @media (prefers-reduced-motion: reduce) {
        .pt-reveal,
        .pt-stagger > .e-con,
        .pt-stagger > .elementor-widget,
        .pt-fade-left,
        .pt-fade-right,
        .pt-scale-in {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }

        .pt-hero-image img,
        .elementor-widget-image img[src*="leaf"],
        .elementor-widget-image img[src*="basil"],
        .ast-article-single.product .onsale {
            animation: none !important;
        }

        html {
            scroll-behavior: auto;
        }
    }

    </style>
    <?php
}

/* ========================================================================
 * JS — IntersectionObserver for scroll-reveal + sticky header
 * ===================================================================== */

function pethoven_ui_js() {
    ?>
    <script id="pethoven-ui-js">
    (function () {
        'use strict';

        /* Bail if user prefers reduced motion */
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        /* ----------------------------------------------------------
         * 1. Scroll-reveal with IntersectionObserver
         * ---------------------------------------------------------- */
        var sections = document.querySelectorAll('.e-con.e-parent');

        sections.forEach(function (section, index) {
            /* Hero section (first): split children left/right */
            if (index === 0) {
                var children = section.querySelectorAll(':scope > .e-con');
                if (children.length >= 2) {
                    children[0].classList.add('pt-fade-left');
                    children[1].classList.add('pt-fade-right');
                    children[1].classList.add('pt-hero-image');
                }
            }
            /* Features bar (second): tag + stagger children */
            else if (index === 1) {
                section.classList.add('pt-features-bar');
                section.classList.add('pt-stagger');
            }
            /* Products section: stagger */
            else if (index === 2) {
                section.classList.add('pt-reveal');
            }
            /* Category cards: stagger */
            else if (index === 4) {
                section.classList.add('pt-stagger');
            }
            /* Everything else: simple reveal */
            else {
                section.classList.add('pt-reveal');
            }
        });

        /* Also reveal individual product cards */
        var products = document.querySelectorAll('.ast-article-single.product');
        products.forEach(function (card, i) {
            card.classList.add('pt-reveal');
            card.style.transitionDelay = (i * 0.1) + 's';
        });

        /* Create observer */
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('pt-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -60px 0px'
        });

        document.querySelectorAll('.pt-reveal, .pt-stagger, .pt-fade-left, .pt-fade-right, .pt-scale-in').forEach(function (el) {
            observer.observe(el);
        });

        /* ----------------------------------------------------------
         * 2. Sticky header shadow on scroll
         * ---------------------------------------------------------- */
        var header = document.getElementById('masthead');
        var lastScroll = 0;

        if (header) {
            window.addEventListener('scroll', function () {
                var scrollY = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollY > 50) {
                    header.classList.add('pt-header-scrolled');
                } else {
                    header.classList.remove('pt-header-scrolled');
                }

                lastScroll = scrollY;
            }, { passive: true });
        }

        /* ----------------------------------------------------------
         * 3. Magnetic effect on CTA buttons (subtle)
         * ---------------------------------------------------------- */
        document.querySelectorAll('.elementor-button').forEach(function (btn) {
            btn.addEventListener('mouseenter', function () {
                btn.style.willChange = 'transform, box-shadow';
            });

            btn.addEventListener('mouseleave', function () {
                btn.style.willChange = 'auto';
            });
        });

        /* ----------------------------------------------------------
         * 4. Counter animation for price elements
         * ---------------------------------------------------------- */
        var priceObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    priceObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        document.querySelectorAll('.price').forEach(function (price) {
            price.style.opacity = '0';
            price.style.transform = 'translateY(10px)';
            price.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            priceObserver.observe(price);
        });

    })();
    </script>
    <?php
}
