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
        transform: translateY(20px);
        transition: opacity 0.8s ease-out,
                    transform 0.8s ease-out;
    }

    .pt-reveal.pt-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Staggered children */
    .pt-stagger > .e-con,
    .pt-stagger > .elementor-widget {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.7s ease-out,
                    transform 0.7s ease-out;
    }

    .pt-stagger.pt-visible > .e-con:nth-child(1),
    .pt-stagger.pt-visible > .elementor-widget:nth-child(1) { transition-delay: 0s; opacity: 1; transform: translateY(0); }
    .pt-stagger.pt-visible > .e-con:nth-child(2),
    .pt-stagger.pt-visible > .elementor-widget:nth-child(2) { transition-delay: 0.08s; opacity: 1; transform: translateY(0); }
    .pt-stagger.pt-visible > .e-con:nth-child(3),
    .pt-stagger.pt-visible > .elementor-widget:nth-child(3) { transition-delay: 0.16s; opacity: 1; transform: translateY(0); }
    .pt-stagger.pt-visible > .e-con:nth-child(4),
    .pt-stagger.pt-visible > .elementor-widget:nth-child(4) { transition-delay: 0.24s; opacity: 1; transform: translateY(0); }
    .pt-stagger.pt-visible > .e-con:nth-child(5),
    .pt-stagger.pt-visible > .elementor-widget:nth-child(5) { transition-delay: 0.32s; opacity: 1; transform: translateY(0); }

    /* Fade-in-left / right for two-column hero */
    .pt-fade-left {
        opacity: 0;
        transform: translateX(-24px);
        transition: opacity 0.9s ease-out,
                    transform 0.9s ease-out;
    }

    .pt-fade-right {
        opacity: 0;
        transform: translateX(24px);
        transition: opacity 0.9s ease-out,
                    transform 0.9s ease-out;
    }

    .pt-fade-left.pt-visible,
    .pt-fade-right.pt-visible {
        opacity: 1;
        transform: translateX(0);
    }

    /* Scale-in for images */
    .pt-scale-in {
        opacity: 0;
        transform: scale(0.96);
        transition: opacity 0.8s ease-out,
                    transform 0.8s ease-out;
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
        50%      { transform: translateY(-6px); }
    }

    .pt-hero-image img {
        animation: pt-hero-float 5s ease-in-out infinite;
        animation-delay: 1.5s;
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
     * 4. PRODUCT CARDS — catchy & attractive
     * ========================================================== */

    /* Grid */
    .woocommerce ul.products {
        gap: 24px !important;
    }

    /* ---- Card shell ---- */
    .ast-article-single.product {
        background: #ffffff !important;
        border-radius: 20px;
        border: none;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.45s cubic-bezier(0.22, 1, 0.36, 1);
        position: relative;
    }

    /* Green accent line at top */
    .ast-article-single.product::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--ast-global-color-0, #8bc34a), var(--ast-global-color-1, #6a9739));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        z-index: 3;
        border-radius: 20px 20px 0 0;
    }

    .ast-article-single.product:hover::before {
        transform: scaleX(1);
    }

    .ast-article-single.product:hover {
        transform: translateY(-12px);
        box-shadow: 0 24px 60px rgba(106, 151, 57, 0.12),
                    0 8px 24px rgba(0, 0, 0, 0.06);
    }

    /* ---- Image area ---- */
    .astra-shop-thumbnail-wrap {
        overflow: hidden;
        border-radius: 0;
        background: linear-gradient(145deg, #f5f7f0 0%, #eef2e8 100%) !important;
        position: relative;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ast-article-single.product .astra-shop-thumbnail-wrap img {
        transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1),
                    filter 0.4s ease;
        mix-blend-mode: multiply;
        object-fit: contain;
    }

    .ast-article-single.product:hover .astra-shop-thumbnail-wrap img {
        transform: scale(1.1) rotate(1deg);
    }

    /* Dark overlay on hover — slides up from bottom */
    .astra-shop-thumbnail-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            0deg,
            rgba(0, 0, 0, 0.35) 0%,
            rgba(0, 0, 0, 0.08) 40%,
            transparent 100%
        );
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
        z-index: 1;
    }

    .ast-article-single.product:hover .astra-shop-thumbnail-wrap::after {
        opacity: 1;
    }

    /* ---- Add to Cart — slides up inside image area on hover ---- */
    .ast-article-single.product .button.add_to_cart_button,
    .ast-article-single.product .button.product_type_simple {
        position: absolute !important;
        bottom: -50px;
        left: 50% !important;
        transform: translateX(-50%);
        z-index: 2;
        padding: 12px 32px !important;
        background: #ffffff !important;
        color: var(--ast-global-color-1, #6a9739) !important;
        border: none !important;
        border-radius: 30px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        white-space: nowrap !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
        transition: bottom 0.4s cubic-bezier(0.22, 1, 0.36, 1),
                    background-color 0.25s ease,
                    color 0.25s ease,
                    box-shadow 0.25s ease !important;
        margin: 0 !important;
    }

    .ast-article-single.product:hover .button.add_to_cart_button,
    .ast-article-single.product:hover .button.product_type_simple {
        bottom: 20px;
    }

    .ast-article-single.product .button.add_to_cart_button:hover,
    .ast-article-single.product .button.product_type_simple:hover {
        background: var(--ast-global-color-1, #6a9739) !important;
        color: #ffffff !important;
        box-shadow: 0 8px 28px rgba(106, 151, 57, 0.35) !important;
        transform: translateX(-50%) translateY(-2px) !important;
    }

    /* ---- Sale badge ---- */
    .ast-article-single.product .onsale {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24) !important;
        color: #fff !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 6px 16px !important;
        border-radius: 0 0 12px 12px !important;
        line-height: 1.3 !important;
        min-height: auto !important;
        min-width: auto !important;
        top: 0 !important;
        right: 20px !important;
        left: auto !important;
        box-shadow: 0 4px 12px rgba(238, 90, 36, 0.3);
        animation: pt-pulse-badge 2.5s ease-in-out infinite;
        z-index: 2;
    }

    @keyframes pt-pulse-badge {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.05); }
    }

    /* ---- Content area ---- */
    .astra-shop-summary-wrap {
        padding: 22px 20px 28px !important;
        text-align: center;
        position: relative;
    }

    /* Category pill */
    .ast-woo-product-category {
        display: inline-block !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 1.5px !important;
        color: var(--ast-global-color-1, #6a9739) !important;
        background: rgba(139, 195, 74, 0.1);
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 10px !important;
    }

    /* Product title */
    .woocommerce-loop-product__title {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #1a1a1a !important;
        margin-bottom: 8px !important;
        line-height: 1.35 !important;
        transition: color 0.25s ease;
    }

    .ast-article-single.product:hover .woocommerce-loop-product__title {
        color: var(--ast-global-color-1, #6a9739) !important;
    }

    /* Star ratings */
    .ast-article-single.product .star-rating {
        margin: 0 auto 10px !important;
        font-size: 12px !important;
        color: #f5a623 !important;
    }

    /* Price */
    .ast-article-single.product .price {
        font-size: 20px !important;
        font-weight: 800 !important;
        color: #1a1a1a !important;
        letter-spacing: -0.3px;
    }

    .ast-article-single.product .price del {
        font-size: 14px !important;
        color: #ccc !important;
        font-weight: 400 !important;
    }

    .ast-article-single.product .price ins {
        color: var(--ast-global-color-1, #6a9739) !important;
        text-decoration: none !important;
        font-weight: 800 !important;
    }

    /* ---- Responsive ---- */
    @media (max-width: 921px) {
        .woocommerce ul.products {
            gap: 16px !important;
        }

        .astra-shop-summary-wrap {
            padding: 16px 14px 22px !important;
        }

        .woocommerce-loop-product__title {
            font-size: 15px !important;
        }

        .ast-article-single.product .price {
            font-size: 17px !important;
        }
    }

    @media (max-width: 544px) {
        .ast-article-single.product:hover {
            transform: translateY(-6px);
        }

        .ast-article-single.product .onsale {
            font-size: 10px !important;
            padding: 4px 12px !important;
        }

        /* Show cart button always on mobile (no hover) */
        .ast-article-single.product .button.add_to_cart_button,
        .ast-article-single.product .button.product_type_simple {
            position: relative !important;
            bottom: auto !important;
            left: auto !important;
            transform: none !important;
            margin-top: 12px !important;
            width: 100% !important;
            background: var(--ast-global-color-1, #6a9739) !important;
            color: #fff !important;
        }
    }

    /* ==========================================================
     * 4b. PRODUCTS SECTION WRAPPER
     * ========================================================== */

    /* Section background — subtle organic pattern feel */
    .pt-products-section,
    .elementor-element-f980f52 {
        background: linear-gradient(175deg, #f9faf6 0%, #f1f4ec 40%, #f7f5f0 100%) !important;
        padding: 80px 0 90px !important;
        position: relative;
        overflow: hidden;
    }

    /* Decorative blurred blobs behind the grid */
    .pt-products-section::before,
    .pt-products-section::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.4;
        z-index: 0;
        pointer-events: none;
    }

    .pt-products-section::before {
        width: 350px;
        height: 350px;
        background: rgba(139, 195, 74, 0.15);
        top: -80px;
        right: -60px;
    }

    .pt-products-section::after {
        width: 280px;
        height: 280px;
        background: rgba(139, 195, 74, 0.1);
        bottom: -60px;
        left: -40px;
    }

    .pt-products-section > .e-con-inner {
        position: relative;
        z-index: 1;
    }

    /* ---- Section heading ---- */
    .pt-products-section .elementor-widget-heading {
        margin-bottom: 8px !important;
    }

    .pt-products-section .elementor-heading-title {
        font-size: 40px !important;
        font-weight: 800 !important;
        letter-spacing: -0.5px;
        color: #1a1a1a !important;
        position: relative;
        display: inline-block;
    }

    /* Replace the leaf image with a styled subtitle */
    .pt-products-section .elementor-widget-image {
        margin-bottom: 36px !important;
    }

    /* Hide the default leaf image */
    .pt-products-section .elementor-widget-image img[src*="leaf"] {
        display: none !important;
    }

    /* Custom green divider bar under heading */
    .pt-products-section .elementor-widget-image .elementor-widget-container {
        display: flex;
        justify-content: center;
    }

    .pt-products-section .elementor-widget-image .elementor-widget-container::before {
        content: '';
        display: block;
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--ast-global-color-0, #8bc34a), var(--ast-global-color-1, #6a9739));
        border-radius: 4px;
    }

    /* Product grid needs to be above blobs */
    .pt-products-section .woocommerce {
        position: relative;
        z-index: 1;
    }

    /* ---- Mobile ---- */
    @media (max-width: 921px) {
        .pt-products-section,
        .elementor-element-f980f52 {
            padding: 60px 0 70px !important;
        }

        .pt-products-section .elementor-heading-title {
            font-size: 32px !important;
        }
    }

    @media (max-width: 544px) {
        .pt-products-section,
        .elementor-element-f980f52 {
            padding: 48px 0 56px !important;
        }

        .pt-products-section .elementor-heading-title {
            font-size: 26px !important;
        }

        .pt-products-section::before,
        .pt-products-section::after {
            display: none;
        }
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
    .elementor-element-966d6bb,
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

    /* Each feature card — kill element AND ::before overlay backgrounds */
    .elementor-element-ab3b0ab,
    .elementor-element-d6c3c68,
    .elementor-element-4236095,
    .elementor-element-c3254ca,
    .pt-features-bar .e-con.e-child {
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

    /* Kill the ::before overlay — this is where Elementor puts #333 */
    .elementor-element-ab3b0ab::before,
    .elementor-element-d6c3c68::before,
    .elementor-element-4236095::before,
    .elementor-element-c3254ca::before,
    .elementor-element-966d6bb::before,
    .pt-features-bar .e-con.e-child::before {
        background: transparent !important;
        background-color: transparent !important;
        opacity: 0 !important;
    }

    /* Kill parent background too */
    .elementor-element-966d6bb,
    .elementor-element-966d6bb:not(.elementor-motion-effects-element-type-background) {
        background-color: transparent !important;
        background: transparent !important;
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
    .elementor-element-ab3b0ab:hover,
    .elementor-element-d6c3c68:hover,
    .elementor-element-4236095:hover,
    .elementor-element-c3254ca:hover,
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
        50%      { transform: rotate(2deg); }
    }

    .elementor-widget-image img[src*="leaf"],
    .elementor-widget-image img[src*="basil"] {
        animation: pt-leaf-sway 6s ease-in-out infinite;
    }

    /* ==========================================================
     * 14. CLEANUP — hide placeholder/broken sections until real
     *     content exists. Targets elementor IDs from the theme.
     * ========================================================== */

    /* Quiz CTA ("Take the 30-Second Quiz") — no quiz built yet */
    .elementor-element-778c9e4 {
        display: none !important;
    }

    /* "Trusted By" brand logos — placeholder images */
    .elementor-element-357f4cd {
        display: none !important;
    }

    /* Zero-star ratings are worse than no rating. Hide them in the
     * product loop and on the single product page until reviews exist. */
    .woocommerce ul.products li.product .star-rating[title*="Rated 0"],
    .woocommerce ul.products li.product .star-rating[aria-label*="Rated 0"],
    .woocommerce-product-rating .star-rating[title*="Rated 0"],
    .ast-woocommerce-container .star-rating[title*="Rated 0"] {
        display: none !important;
    }
    /* Fallback: hide empty star widgets where span[style] width is 0% */
    .star-rating > span[style="width:0%"],
    .star-rating > span[style="width: 0%"] {
        display: none !important;
    }
    .star-rating:has(> span[style="width:0%"]),
    .star-rating:has(> span[style="width: 0%"]) {
        display: none !important;
    }

    /* Hide the "(0 customer reviews)" count too */
    .woocommerce-review-link .count:empty,
    .woocommerce-review-link:has(.count:empty) {
        display: none !important;
    }

    /* ==========================================================
     * 15. CONTACT PAGE POLISH
     * ========================================================== */

    /* Style the injected mailto block (see pethoven_contact_card) */
    .pt-contact-card {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 16px;
        max-width: 820px;
        margin: 32px auto 48px;
        padding: 0 20px;
    }

    .pt-contact-tile {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 16px;
        padding: 24px 22px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .pt-contact-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(106, 151, 57, 0.10);
    }

    .pt-contact-tile-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--ast-global-color-1, #6a9739);
        margin-bottom: 8px;
    }

    .pt-contact-tile a,
    .pt-contact-tile-value {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
        text-decoration: none;
        line-height: 1.4;
        word-break: break-word;
    }

    .pt-contact-tile a:hover {
        color: var(--ast-global-color-1, #6a9739);
    }

    /* ==========================================================
     * 16. SHOP ARCHIVE — page header + toolbar
     * ========================================================== */

    body.woocommerce .ast-woocommerce-container {
        padding-top: 8px;
    }

    /* Breadcrumb centered, subtle */
    body.woocommerce nav.woocommerce-breadcrumb {
        text-align: center;
        font-size: 12px;
        letter-spacing: 0.8px;
        color: #a0a0a0;
        padding: 24px 0 4px;
        border: none;
        text-transform: uppercase;
        font-weight: 600;
    }

    body.woocommerce nav.woocommerce-breadcrumb a {
        color: var(--ast-global-color-1, #6a9739);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    body.woocommerce nav.woocommerce-breadcrumb a:hover {
        color: #1a1a1a;
    }

    /* Page title: modern sans-serif, dark, no italic */
    body.woocommerce .woocommerce-products-header {
        text-align: center;
        padding: 8px 0 16px;
        margin-bottom: 0;
        border: none;
    }

    body.woocommerce .woocommerce-products-header__title.page-title,
    body.woocommerce h1.woocommerce-products-header__title {
        font-size: 48px !important;
        font-weight: 800 !important;
        color: #1a1a1a !important;
        font-style: normal !important;
        letter-spacing: -0.8px !important;
        margin: 0 auto 8px !important;
        line-height: 1.1 !important;
        display: inline-block;
        position: relative;
    }

    /* Subtle green underline accent on title */
    body.woocommerce .woocommerce-products-header__title.page-title::after {
        content: '';
        display: block;
        width: 48px;
        height: 4px;
        background: linear-gradient(90deg, var(--ast-global-color-0, #8bc34a), var(--ast-global-color-1, #6a9739));
        border-radius: 4px;
        margin: 16px auto 0;
    }

    /* Toolbar row: result count + ordering */
    body.woocommerce .woocommerce-result-count {
        font-size: 13px;
        color: #888;
        margin: 0 0 24px;
        font-weight: 500;
    }

    body.woocommerce .woocommerce-ordering {
        margin: 0 0 24px;
    }

    body.woocommerce .woocommerce-ordering select {
        border: 1px solid #e5e5e5;
        border-radius: 30px;
        padding: 10px 40px 10px 18px;
        font-size: 13px;
        font-weight: 500;
        background: #fff;
        color: #1a1a1a;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'><path fill='none' stroke='%236a9739' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round' d='M1 1l4 4 4-4'/></svg>");
        background-repeat: no-repeat;
        background-position: right 16px center;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    body.woocommerce .woocommerce-ordering select:hover,
    body.woocommerce .woocommerce-ordering select:focus {
        border-color: var(--ast-global-color-1, #6a9739);
        outline: none;
        box-shadow: 0 0 0 3px rgba(106, 151, 57, 0.1);
    }

    /* ==========================================================
     * 17. SHOP ARCHIVE — product cards (ul.products variant)
     *     Mirrors the homepage card treatment but uses archive
     *     selectors (.ast-article-post.product).
     * ========================================================== */

    body.woocommerce ul.products {
        gap: 28px !important;
        padding-top: 8px !important;
        margin: 0 !important;
    }

    body.woocommerce ul.products li.product,
    body.woocommerce ul.products li.ast-article-post.product {
        background: #ffffff !important;
        border-radius: 20px !important;
        border: none !important;
        overflow: hidden !important;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.45s cubic-bezier(0.22, 1, 0.36, 1) !important;
        position: relative !important;
        padding: 0 !important;
        margin-bottom: 0 !important;
    }

    /* Green accent bar on hover */
    body.woocommerce ul.products li.product::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--ast-global-color-0, #8bc34a), var(--ast-global-color-1, #6a9739));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        z-index: 3;
        border-radius: 20px 20px 0 0;
    }

    body.woocommerce ul.products li.product:hover::before {
        transform: scaleX(1);
    }

    body.woocommerce ul.products li.product:hover {
        transform: translateY(-10px);
        box-shadow: 0 24px 60px rgba(106, 151, 57, 0.12),
                    0 8px 24px rgba(0, 0, 0, 0.06) !important;
    }

    /* Image area — consistent soft-green background, mix-blend clean */
    body.woocommerce ul.products li.product .astra-shop-thumbnail-wrap {
        background: linear-gradient(145deg, #f5f7f0 0%, #eef2e8 100%) !important;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        border-radius: 0 !important;
        margin: 0 !important;
    }

    body.woocommerce ul.products li.product .astra-shop-thumbnail-wrap img {
        transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1) !important;
        mix-blend-mode: multiply;
        object-fit: contain;
        max-width: 80% !important;
        max-height: 80% !important;
        width: auto !important;
        height: auto !important;
    }

    body.woocommerce ul.products li.product:hover .astra-shop-thumbnail-wrap img {
        transform: scale(1.08);
    }

    /* Sale badge — force the red/orange pulse over Astra green */
    body.woocommerce ul.products li.product .onsale,
    body.woocommerce span.onsale,
    .wc-block-grid__product-onsale {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24) !important;
        color: #ffffff !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        padding: 6px 16px !important;
        border-radius: 0 0 12px 12px !important;
        line-height: 1.3 !important;
        min-height: auto !important;
        min-width: auto !important;
        position: absolute !important;
        top: 0 !important;
        right: 20px !important;
        left: auto !important;
        bottom: auto !important;
        box-shadow: 0 4px 12px rgba(238, 90, 36, 0.3) !important;
        animation: pt-pulse-badge 2.5s ease-in-out infinite;
        z-index: 3 !important;
        margin: 0 !important;
        border: none !important;
    }

    /* Content area */
    body.woocommerce ul.products li.product .astra-shop-summary-wrap {
        padding: 22px 20px 26px !important;
        text-align: center;
        background: #ffffff;
        margin: 0 !important;
    }

    body.woocommerce ul.products li.product .ast-woo-product-category {
        display: inline-block !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 1.5px !important;
        color: var(--ast-global-color-1, #6a9739) !important;
        background: rgba(139, 195, 74, 0.1);
        padding: 4px 12px;
        border-radius: 20px;
        margin-bottom: 12px !important;
        text-decoration: none;
    }

    body.woocommerce ul.products li.product .ast-woo-product-category a {
        color: inherit !important;
        text-decoration: none !important;
    }

    body.woocommerce ul.products li.product .woocommerce-loop-product__title {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #1a1a1a !important;
        margin: 0 0 12px !important;
        line-height: 1.35 !important;
        transition: color 0.25s ease;
    }

    body.woocommerce ul.products li.product:hover .woocommerce-loop-product__title {
        color: var(--ast-global-color-1, #6a9739) !important;
    }

    /* Kill zero-star review wrappers in archive */
    body.woocommerce ul.products li.product .review-rating:not(:has(.star-rating[style*="width:"])) {
        display: none;
    }
    body.woocommerce ul.products li.product .review-rating .star-rating > span[style="width:0%"],
    body.woocommerce ul.products li.product .review-rating .star-rating > span[style="width: 0%"] {
        display: none;
    }

    body.woocommerce ul.products li.product .price {
        font-size: 20px !important;
        font-weight: 800 !important;
        color: #1a1a1a !important;
        letter-spacing: -0.3px !important;
        display: block;
    }

    body.woocommerce ul.products li.product .price del {
        font-size: 14px !important;
        color: #ccc !important;
        font-weight: 400 !important;
        margin-right: 6px;
    }

    body.woocommerce ul.products li.product .price ins {
        color: var(--ast-global-color-1, #6a9739) !important;
        text-decoration: none !important;
        font-weight: 800 !important;
    }

    /* ==========================================================
     * 18. SIDEBAR CLEANUP + STYLING
     * ========================================================== */

    /* Kill the stuck-loading widgets (those grey skeleton pills).
     * These never hydrate because the WooCommerce Store API hasn't
     * initialized on this page, so they'd sit as placeholders forever.
     * We target both the inner block and the whole widget wrapper so
     * we don't leave an empty bordered container behind. */
    .ast-woo-sidebar-widget .wp-block-woocommerce-active-filters.is-loading,
    .ast-woo-sidebar-widget .wp-block-woocommerce-price-filter.is-loading,
    .ast-woo-sidebar-widget:has(.wp-block-woocommerce-active-filters.is-loading),
    .ast-woo-sidebar-widget:has(.wp-block-woocommerce-price-filter.is-loading),
    .ast-woo-sidebar-widget:has(> .wp-block-woocommerce-active-filters),
    .ast-woo-sidebar-widget:has(> .wp-block-woocommerce-price-filter) {
        display: none !important;
    }

    /* Also hide any raw placeholder skeletons */
    .wc-block-active-product-filters__placeholder,
    .wc-block-product-categories__placeholder,
    .wc-block-product-search__placeholder {
        display: none !important;
    }

    /* Widget spacing */
    .ast-woo-sidebar-widget.widget {
        margin-bottom: 32px !important;
        padding: 0 !important;
        background: transparent !important;
        border: none !important;
    }

    /* Widget titles: uppercase micro-label */
    .ast-woo-sidebar-widget .widget-title,
    .ast-woo-sidebar-widget h2,
    .ast-woo-sidebar-widget h3 {
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 1.5px !important;
        color: #999 !important;
        margin: 0 0 14px !important;
        padding-bottom: 10px !important;
        border-bottom: 1px solid #eee !important;
    }

    /* Product search */
    .wc-block-product-search__fields {
        display: flex;
        gap: 0;
    }

    .wc-block-product-search__field {
        flex: 1;
        border: 1px solid #e5e5e5 !important;
        border-right: none !important;
        border-radius: 30px 0 0 30px !important;
        padding: 10px 18px !important;
        font-size: 13px !important;
        background: #fff !important;
        color: #1a1a1a !important;
        transition: border-color 0.2s ease !important;
    }

    .wc-block-product-search__field:focus {
        border-color: var(--ast-global-color-1, #6a9739) !important;
        outline: none !important;
    }

    .wc-block-product-search__button {
        border-radius: 0 30px 30px 0 !important;
        background: var(--ast-global-color-1, #6a9739) !important;
        border: none !important;
        padding: 0 18px !important;
        transition: background-color 0.2s ease !important;
    }

    .wc-block-product-search__button:hover {
        background: var(--ast-global-color-0, #8bc34a) !important;
    }

    .wc-block-product-search__button svg {
        fill: #fff !important;
    }

    /* Category list */
    .wc-block-product-categories-list {
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .wc-block-product-categories-list-item {
        padding: 10px 0 !important;
        border-bottom: 1px solid #f2f2f2;
        transition: padding-left 0.2s ease, color 0.2s ease;
    }

    .wc-block-product-categories-list-item:last-child {
        border-bottom: none;
    }

    .wc-block-product-categories-list-item:hover {
        padding-left: 6px !important;
    }

    .wc-block-product-categories-list-item a {
        color: #1a1a1a !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }

    .wc-block-product-categories-list-item:hover a,
    .wc-block-product-categories-list-item:hover .wc-block-product-categories-list-item__name {
        color: var(--ast-global-color-1, #6a9739) !important;
    }

    .wc-block-product-categories-list-item-count {
        color: #aaa !important;
        font-size: 12px !important;
        margin-left: 6px;
    }

    /* Featured products list — compact row layout */
    .ast-woo-sidebar-widget .wc-block-grid__products {
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
    }

    .ast-woo-sidebar-widget .wc-block-grid__product {
        margin: 0 0 14px !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .ast-woo-sidebar-widget .wc-block-grid__product-link {
        display: grid !important;
        grid-template-columns: 64px 1fr !important;
        gap: 12px !important;
        align-items: center !important;
        text-decoration: none !important;
        padding: 8px;
        border-radius: 10px;
        transition: background 0.2s ease;
    }

    .ast-woo-sidebar-widget .wc-block-grid__product-link:hover {
        background: rgba(139, 195, 74, 0.05);
    }

    .ast-woo-sidebar-widget .wc-block-grid__product-image {
        margin: 0 !important;
        aspect-ratio: 1 / 1;
        background: linear-gradient(145deg, #f5f7f0 0%, #eef2e8 100%);
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
    }

    .ast-woo-sidebar-widget .wc-block-grid__product-image img {
        max-width: 100%;
        max-height: 100%;
        width: auto !important;
        height: auto !important;
        object-fit: contain;
        mix-blend-mode: multiply;
    }

    .ast-woo-sidebar-widget .wc-block-grid__product-title {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #1a1a1a !important;
        line-height: 1.3 !important;
        margin: 0 !important;
        text-align: left !important;
    }

    .ast-woo-sidebar-widget .wc-block-grid__product-price {
        font-size: 13px !important;
        color: #666 !important;
        margin-top: 4px !important;
        display: block;
    }

    .ast-woo-sidebar-widget .wc-block-grid__product-price del {
        color: #bbb !important;
        font-size: 12px;
        margin-right: 4px;
    }

    .ast-woo-sidebar-widget .wc-block-grid__product-price ins {
        color: var(--ast-global-color-1, #6a9739) !important;
        text-decoration: none !important;
        font-weight: 700;
    }

    /* Sticky sidebar on desktop */
    @media (min-width: 922px) {
        body.woocommerce .widget-area.secondary {
            position: sticky;
            top: 100px;
            align-self: flex-start;
        }
    }

    /* ==========================================================
     * 19. PAGINATION
     * ========================================================== */

    .woocommerce-pagination {
        margin: 48px 0 24px !important;
        text-align: center;
        border: none !important;
    }

    .woocommerce-pagination ul.page-numbers {
        display: inline-flex !important;
        gap: 6px !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
    }

    .woocommerce-pagination .page-numbers li {
        display: inline-block !important;
        border: none !important;
        background: transparent !important;
        margin: 0 !important;
    }

    .woocommerce-pagination .page-numbers li a.page-numbers,
    .woocommerce-pagination .page-numbers li span.page-numbers,
    .woocommerce-pagination a.page-numbers,
    .woocommerce-pagination span.page-numbers {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 40px !important;
        height: 40px !important;
        padding: 0 14px !important;
        border-radius: 10px !important;
        background: #fff !important;
        border: 1px solid #e5e5e5 !important;
        color: #1a1a1a !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        line-height: 1 !important;
        transition: all 0.2s ease !important;
    }

    .woocommerce-pagination .page-numbers li a.page-numbers:hover {
        border-color: var(--ast-global-color-1, #6a9739) !important;
        color: var(--ast-global-color-1, #6a9739) !important;
        transform: translateY(-1px);
    }

    .woocommerce-pagination .page-numbers .current {
        background: var(--ast-global-color-1, #6a9739) !important;
        border-color: var(--ast-global-color-1, #6a9739) !important;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(106, 151, 57, 0.3);
    }

    .woocommerce-pagination .next.page-numbers,
    .woocommerce-pagination .prev.page-numbers {
        font-weight: 700 !important;
    }

    /* ==========================================================
     * 20. SHOP ARCHIVE — responsive
     * ========================================================== */

    @media (max-width: 921px) {
        body.woocommerce .woocommerce-products-header__title.page-title {
            font-size: 36px !important;
        }

        body.woocommerce ul.products {
            gap: 18px !important;
        }

        body.woocommerce ul.products li.product .astra-shop-summary-wrap {
            padding: 16px 14px 20px !important;
        }

        body.woocommerce ul.products li.product .woocommerce-loop-product__title {
            font-size: 15px !important;
        }

        body.woocommerce ul.products li.product .price {
            font-size: 17px !important;
        }
    }

    @media (max-width: 544px) {
        body.woocommerce nav.woocommerce-breadcrumb {
            padding: 16px 0 0;
            font-size: 11px;
        }

        body.woocommerce .woocommerce-products-header__title.page-title {
            font-size: 28px !important;
        }

        body.woocommerce .woocommerce-products-header__title.page-title::after {
            width: 36px;
            height: 3px;
            margin-top: 10px;
        }

        body.woocommerce ul.products li.product:hover {
            transform: translateY(-4px);
        }

        body.woocommerce ul.products li.product .onsale {
            font-size: 10px !important;
            padding: 4px 12px !important;
            right: 12px !important;
        }

        .woocommerce-pagination .page-numbers li a.page-numbers,
        .woocommerce-pagination .page-numbers li span.page-numbers {
            min-width: 36px !important;
            height: 36px !important;
            font-size: 13px !important;
        }
    }

    /* ==========================================================
     * 21. REDUCED MOTION — respect user preference
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
            /* Products section */
            else if (index === 2) {
                section.classList.add('pt-products-section');
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
            card.style.transitionDelay = (i * 0.06) + 's';
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
            threshold: 0.05,
            rootMargin: '0px 0px -30px 0px'
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

    <script id="pethoven-cleanup-js">
    (function () {
        'use strict';

        /* ----------------------------------------------------------
         * A. Hide nav items that aren't real destinations.
         *    We match anchor text rather than IDs so this survives
         *    menu reordering in WordPress admin.
         * ---------------------------------------------------------- */
        var killMenuLabels = ['KOON', 'Store Locator', 'Locate Stores'];

        document.querySelectorAll(
            '.ast-builder-menu-1 a, ' +
            '#ast-mobile-header a, ' +
            '.ast-mobile-header-content a, ' +
            '.site-footer a, ' +
            '.ast-footer-widget-1-area a, ' +
            '.ast-footer-widget-2-area a, ' +
            '.ast-footer-widget-3-area a, ' +
            '.ast-footer-widget-4-area a'
        ).forEach(function (a) {
            var text = (a.textContent || '').trim();
            if (killMenuLabels.indexOf(text) !== -1) {
                var li = a.closest('li');
                (li || a).style.display = 'none';
            }
        });

        /* ----------------------------------------------------------
         * B. Dedupe footer columns.
         *    The footer currently has three columns with heavy
         *    overlap (Website / Quick Links / Site Links). We hide
         *    the "Quick Links" column since its contents are a
         *    subset of the others.
         * ---------------------------------------------------------- */
        document.querySelectorAll('.site-footer .widget-title, .site-footer h2, .site-footer h3, .site-footer h4').forEach(function (h) {
            var t = (h.textContent || '').trim().toLowerCase();
            if (t === 'quick links') {
                var col = h.closest('.widget, .elementor-widget, .wp-block-group, .ast-footer-widget-1-area, .ast-footer-widget-2-area, .ast-footer-widget-3-area, .ast-footer-widget-4-area');
                (col || h.parentElement || h).style.display = 'none';
            }
        });

        /* ----------------------------------------------------------
         * C. Route dead "#" links to the shop.
         *    We leave real anchor links alone (any href="#something")
         *    and only rewrite href="#" or empty hrefs on buttons/CTAs
         *    that were templated but never pointed anywhere.
         * ---------------------------------------------------------- */
        var shopUrl = '/shop/';

        document.querySelectorAll('a').forEach(function (a) {
            var href = a.getAttribute('href');
            if (href === '#' || href === '' || href === null) {
                // Skip controls that are supposed to be buttons
                if (a.closest('.pt-announcement-bar')) return;
                if (a.getAttribute('role') === 'button' && a.dataset.action) return;
                // Skip menu toggles
                if (a.classList.contains('menu-toggle') || a.classList.contains('mobile-menu-toggle')) return;

                a.setAttribute('href', shopUrl);
            }
        });

        /* ----------------------------------------------------------
         * D. Hide zero-rating stars that slip past CSS (title varies
         *    by locale and plugin). Belt-and-suspenders.
         * ---------------------------------------------------------- */
        document.querySelectorAll('.star-rating').forEach(function (r) {
            var inner = r.querySelector('span');
            if (!inner) return;
            var width = (inner.style.width || '').trim();
            if (width === '' || width === '0%' || width === '0') {
                r.style.display = 'none';
                var linkWrap = r.closest('.woocommerce-product-rating');
                if (linkWrap) linkWrap.style.display = 'none';
            }
        });

        /* ----------------------------------------------------------
         * E. Add missing aria-labels on icon-only header buttons
         * ---------------------------------------------------------- */
        document.querySelectorAll('.ast-header-woo-cart a, .ast-cart-menu-wrap').forEach(function (el) {
            if (!el.getAttribute('aria-label')) el.setAttribute('aria-label', 'View cart');
        });
        document.querySelectorAll('.ast-header-account a').forEach(function (el) {
            if (!el.getAttribute('aria-label')) el.setAttribute('aria-label', 'Account');
        });

        /* ----------------------------------------------------------
         * F. Hide stuck-loading sidebar widgets (shop archive).
         *    Belt-and-suspenders for browsers without :has() support.
         *    Also hide any widget whose only content is a loading
         *    placeholder — those never hydrate without Store API JS.
         * ---------------------------------------------------------- */
        var deadBlockSelectors = [
            '.wp-block-woocommerce-active-filters.is-loading',
            '.wp-block-woocommerce-price-filter.is-loading'
        ];

        deadBlockSelectors.forEach(function (sel) {
            document.querySelectorAll(sel).forEach(function (block) {
                var widget = block.closest('.ast-woo-sidebar-widget, .widget');
                (widget || block).style.display = 'none';
            });
        });

        /* Remove sidebar widgets whose visible content is just a placeholder */
        document.querySelectorAll('.ast-woo-sidebar-widget').forEach(function (w) {
            var visibleText = (w.textContent || '').replace(/\s+/g, '').length;
            var hasInteractive = w.querySelector('input, select, button, a, ul, ol, img');
            if (visibleText < 3 && !hasInteractive) {
                w.style.display = 'none';
            }
        });

    })();
    </script>
    <?php
}
