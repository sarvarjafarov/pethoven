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
     * 4. PRODUCT CARDS
     *
     * The unified product card styling now lives in sections 17,
     * 20b, and 20c and targets `.woocommerce ul.products li.product`.
     * That selector matches both the shop archive AND the homepage
     * Best Sellers shortcode (which renders its own
     * `<div class="woocommerce">` wrapper), so one rule set covers
     * everything. The old `.ast-article-single.product` rules that
     * lived here have been removed to avoid duplicate / conflicting
     * declarations.
     * ========================================================== */

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
     * 4c. BEST SELLERS SECTION — eyebrow, subtitle, "View all" CTA
     *
     * All three elements are injected via JS (see pt-homepage-section
     * block in cleanup-js). This CSS styles them and integrates with
     * the existing pt-products-section wrapper.
     * ========================================================== */

    .pt-products-eyebrow {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ast-global-color-1, #6a9739);
        text-align: center;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .pt-products-eyebrow::before,
    .pt-products-eyebrow::after {
        content: '';
        width: 28px;
        height: 1.5px;
        background: var(--ast-global-color-1, #6a9739);
        opacity: 0.5;
        border-radius: 2px;
    }

    .pt-products-subtitle {
        font-size: 16px;
        color: #666;
        text-align: center;
        margin: 10px auto 0;
        max-width: 560px;
        line-height: 1.55;
        padding: 0 20px;
        position: relative;
        z-index: 1;
    }

    /* View all products CTA at the bottom of the section */
    .pt-products-cta {
        text-align: center;
        margin: 48px auto 0;
        position: relative;
        z-index: 1;
    }

    .pt-products-cta-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 36px;
        background: #1a1a1a;
        color: #ffffff;
        border-radius: 100px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
                    background-color 0.3s ease,
                    box-shadow 0.3s ease,
                    color 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .pt-products-cta-link::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.18) 50%, transparent 70%);
        transform: translateX(-120%);
        transition: transform 0.6s ease;
    }

    .pt-products-cta-link:hover {
        background: var(--ast-global-color-1, #6a9739);
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(106, 151, 57, 0.28);
    }

    .pt-products-cta-link:hover::before {
        transform: translateX(120%);
    }

    .pt-products-cta-link-arrow {
        transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        display: inline-flex;
    }

    .pt-products-cta-link:hover .pt-products-cta-link-arrow {
        transform: translateX(5px);
    }

    @media (max-width: 921px) {
        .pt-products-subtitle {
            font-size: 15px;
        }
        .pt-products-cta {
            margin-top: 36px;
        }
        .pt-products-cta-link {
            padding: 13px 28px;
            font-size: 11px;
        }
    }

    @media (max-width: 544px) {
        .pt-products-eyebrow {
            font-size: 10px;
            letter-spacing: 2.5px;
        }
        .pt-products-eyebrow::before,
        .pt-products-eyebrow::after {
            width: 20px;
        }
        .pt-products-subtitle {
            font-size: 14px;
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

    /* (Removed the 48px h1 underline — it conflicted with the
     * description layout. The decorative brand mark above the title
     * now serves as the visual anchor, and the trust strip gives
     * the header a crisp bottom edge.) */

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

    .woocommerce ul.products {
        gap: 28px !important;
        padding-top: 8px !important;
        margin: 0 !important;
    }

    .woocommerce ul.products li.product,
    .woocommerce ul.products li.ast-article-post.product {
        background: #ffffff !important;
        border-radius: 20px !important;
        border: 1px solid #f0f0ec !important;
        overflow: hidden !important;
        box-shadow: 0 4px 16px rgba(16, 24, 40, 0.04) !important;
        transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                    border-color 0.35s ease !important;
        position: relative !important;
        padding: 0 !important;
        margin-bottom: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }

    /* Green accent bar on hover */
    .woocommerce ul.products li.product::before {
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

    .woocommerce ul.products li.product:hover::before {
        transform: scaleX(1);
    }

    .woocommerce ul.products li.product:hover {
        transform: translateY(-10px);
        border-color: rgba(139, 195, 74, 0.28) !important;
        box-shadow: 0 24px 60px rgba(106, 151, 57, 0.14),
                    0 8px 24px rgba(16, 24, 40, 0.06) !important;
    }

    /* Image area — vertical gradient that fades into the white content
     * area below, so there's no visible seam between image and summary. */
    .woocommerce ul.products li.product .astra-shop-thumbnail-wrap {
        background: linear-gradient(180deg, #eef2e8 0%, #f2f5ed 45%, #fafbf7 100%) !important;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        border-radius: 0 !important;
        margin: 0 !important;
        flex-shrink: 0;
    }

    /* Full-bleed image: fill the thumbnail area edge-to-edge. We use
     * object-fit: cover so uneven aspect ratios crop cleanly, and
     * drop mix-blend-mode since the photo now covers the card bg. */
    .woocommerce ul.products li.product .astra-shop-thumbnail-wrap img {
        display: block;
        width: 100% !important;
        height: 100% !important;
        max-width: 100% !important;
        max-height: 100% !important;
        min-width: 100% !important;
        min-height: 100% !important;
        object-fit: cover !important;
        object-position: center center;
        mix-blend-mode: normal !important;
        transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1) !important;
    }

    .woocommerce ul.products li.product .astra-shop-thumbnail-wrap > a {
        width: 100%;
        height: 100%;
    }

    .woocommerce ul.products li.product:hover .astra-shop-thumbnail-wrap img {
        transform: scale(1.05);
    }

    /* Sale badge — clean rounded pill, no pulse animation.
     * Sits in the top-right of the image area without competing
     * with the product photo. */
    .woocommerce ul.products li.product .onsale,
    body.woocommerce span.onsale,
    .wc-block-grid__product-onsale {
        background: #1a1a1a !important;
        color: #ffffff !important;
        font-size: 10px !important;
        font-weight: 800 !important;
        letter-spacing: 1.2px !important;
        text-transform: uppercase !important;
        padding: 5px 12px !important;
        border-radius: 100px !important;
        line-height: 1.3 !important;
        min-height: auto !important;
        min-width: auto !important;
        position: absolute !important;
        top: 14px !important;
        right: 14px !important;
        left: auto !important;
        bottom: auto !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18) !important;
        animation: none !important;
        z-index: 3 !important;
        margin: 0 !important;
        border: none !important;
    }

    /* Content area */
    .woocommerce ul.products li.product .astra-shop-summary-wrap {
        padding: 20px 20px 16px !important;
        text-align: center;
        background: #ffffff;
        margin: 0 !important;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }

    .woocommerce ul.products li.product .ast-woo-product-category {
        display: inline-block !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 1.6px !important;
        color: var(--ast-global-color-1, #6a9739) !important;
        background: rgba(139, 195, 74, 0.12);
        padding: 4px 12px;
        border-radius: 100px;
        margin-bottom: 10px !important;
        text-decoration: none;
        line-height: 1.4;
    }

    .woocommerce ul.products li.product .ast-woo-product-category a {
        color: inherit !important;
        text-decoration: none !important;
    }

    .woocommerce ul.products li.product .woocommerce-loop-product__title {
        font-family: inherit !important;
        font-size: 17px !important;
        font-weight: 700 !important;
        color: #1a1a1a !important;
        margin: 0 0 10px !important;
        line-height: 1.3 !important;
        letter-spacing: -0.2px;
        transition: color 0.25s ease;
    }

    .woocommerce ul.products li.product:hover .woocommerce-loop-product__title {
        color: var(--ast-global-color-1, #6a9739) !important;
    }

    /* Kill zero-star review wrappers in archive */
    .woocommerce ul.products li.product .review-rating:not(:has(.star-rating[style*="width:"])) {
        display: none;
    }
    .woocommerce ul.products li.product .review-rating .star-rating > span[style="width:0%"],
    .woocommerce ul.products li.product .review-rating .star-rating > span[style="width: 0%"] {
        display: none;
    }

    .woocommerce ul.products li.product .price {
        font-size: 19px !important;
        font-weight: 800 !important;
        color: #1a1a1a !important;
        letter-spacing: -0.3px !important;
        font-variant-numeric: tabular-nums;
        display: inline-flex;
        align-items: baseline;
        gap: 8px;
        margin: 0 0 16px !important;
    }

    .woocommerce ul.products li.product .price del {
        font-size: 14px !important;
        color: #bbb !important;
        font-weight: 500 !important;
        margin: 0 !important;
        order: 2;
    }

    .woocommerce ul.products li.product .price ins {
        color: var(--ast-global-color-1, #6a9739) !important;
        text-decoration: none !important;
        font-weight: 800 !important;
        order: 1;
    }

    /* Add to Cart button — primary action, rendered by Astra inside
     * the summary wrap via the astra_woo_shop_product_structure
     * filter. margin-top: auto pushes it to the bottom of the
     * summary regardless of title length. */
    .woocommerce ul.products li.product a.button.add_to_cart_button,
    .woocommerce ul.products li.product a.button.product_type_simple,
    .woocommerce ul.products li.product a.button.product_type_variable,
    .woocommerce ul.products li.product a.button.product_type_grouped,
    .woocommerce ul.products li.product a.button.product_type_external {
        position: static !important;
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: auto 0 0 !important;
        padding: 12px 16px !important;
        background: transparent !important;
        color: #1a1a1a !important;
        border: 1.5px solid #e5e5e5 !important;
        border-radius: 12px !important;
        font-family: inherit !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        text-align: center !important;
        text-decoration: none !important;
        line-height: 1.3 !important;
        transform: none !important;
        bottom: auto !important;
        left: auto !important;
        right: auto !important;
        z-index: auto !important;
        box-shadow: none !important;
        white-space: nowrap !important;
        overflow: hidden;
        transition: background-color 0.25s ease,
                    color 0.25s ease,
                    border-color 0.25s ease,
                    transform 0.25s ease,
                    box-shadow 0.25s ease !important;
    }

    .woocommerce ul.products li.product:hover a.button.add_to_cart_button,
    .woocommerce ul.products li.product:hover a.button.product_type_simple {
        background: var(--ast-global-color-1, #6a9739) !important;
        color: #ffffff !important;
        border-color: var(--ast-global-color-1, #6a9739) !important;
        box-shadow: 0 8px 20px rgba(106, 151, 57, 0.25) !important;
    }

    .woocommerce ul.products li.product a.button.add_to_cart_button:hover {
        background: #5d8b32 !important;
        border-color: #5d8b32 !important;
        color: #ffffff !important;
    }

    .woocommerce ul.products li.product a.button.add_to_cart_button.added,
    .woocommerce ul.products li.product a.button.added {
        background: #1a1a1a !important;
        color: #ffffff !important;
        border-color: #1a1a1a !important;
    }

    /* "View cart" link that appears after adding */
    .woocommerce ul.products li.product a.added_to_cart {
        display: block !important;
        width: 100% !important;
        margin: 6px 0 0 !important;
        padding: 6px 8px !important;
        text-align: center !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 1px !important;
        color: var(--ast-global-color-1, #6a9739) !important;
        text-decoration: none !important;
    }

    .woocommerce ul.products li.product a.added_to_cart:hover {
        color: #1a1a1a !important;
    }

    /* Loading state when adding */
    .woocommerce ul.products li.product a.button.loading {
        opacity: 0.7 !important;
        pointer-events: none;
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
     * 18b. SIDEBAR — injected section headings
     * ========================================================== */

    .pt-sidebar-heading {
        font-size: 11px !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 2px !important;
        color: var(--ast-global-color-1, #6a9739) !important;
        margin: 8px 0 14px !important;
        padding: 0 0 10px !important;
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pt-sidebar-heading::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, rgba(139,195,74,0.3), rgba(139,195,74,0));
    }

    /* Override the auto-generated widget titles we insert headings for */
    .ast-woo-sidebar-widget .widget-title:empty,
    .ast-woo-sidebar-widget h2:empty {
        display: none !important;
    }

    /* ==========================================================
     * 18c. CATEGORY LIST — pill tiles
     * ========================================================== */

    .ast-woo-sidebar-widget .wc-block-product-categories-list {
        display: grid !important;
        gap: 8px !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 0 8px !important;
    }

    .ast-woo-sidebar-widget .wc-block-product-categories-list-item {
        padding: 14px 44px 14px 18px !important;
        border: 1px solid #e8e8e8 !important;
        border-radius: 14px !important;
        background: #fff;
        transition: border-color 0.25s ease,
                    background 0.3s ease,
                    transform 0.25s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.25s ease !important;
        position: relative;
        overflow: hidden;
    }

    .ast-woo-sidebar-widget .wc-block-product-categories-list-item:last-child {
        border-bottom: 1px solid #e8e8e8 !important;
    }

    .ast-woo-sidebar-widget .wc-block-product-categories-list-item:hover {
        border-color: var(--ast-global-color-1, #6a9739) !important;
        background: linear-gradient(135deg, rgba(139,195,74,0.06), rgba(139,195,74,0.02));
        transform: translateX(4px);
        box-shadow: 0 4px 14px rgba(106, 151, 57, 0.08);
        padding-left: 18px !important;
    }

    /* Arrow indicator on hover */
    .ast-woo-sidebar-widget .wc-block-product-categories-list-item::after {
        content: '→';
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%) translateX(-6px);
        color: var(--ast-global-color-1, #6a9739);
        font-size: 16px;
        font-weight: 700;
        opacity: 0;
        transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .ast-woo-sidebar-widget .wc-block-product-categories-list-item:hover::after {
        opacity: 1;
        transform: translateY(-50%) translateX(0);
    }

    /* Count badge styling */
    .ast-woo-sidebar-widget .wc-block-product-categories-list-item-count {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        padding: 0 7px;
        margin-left: 10px !important;
        background: #f5f7f0;
        color: var(--ast-global-color-1, #6a9739) !important;
        font-size: 11px !important;
        font-weight: 700;
        border-radius: 20px;
        font-feature-settings: "tnum";
        transition: background 0.25s ease, color 0.25s ease;
    }

    .ast-woo-sidebar-widget .wc-block-product-categories-list-item:hover .wc-block-product-categories-list-item-count {
        background: var(--ast-global-color-1, #6a9739);
        color: #fff !important;
    }

    /* ==========================================================
     * 18d. FEATURED PRODUCTS — rank badges + polish
     * ========================================================== */

    .pt-rank-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 4;
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1a1a1a;
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.5px;
        border-radius: 6px;
        font-feature-settings: "tnum";
        pointer-events: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    }

    /* Accent the top-ranked one */
    .ast-woo-sidebar-widget .wc-block-grid__product:first-child .pt-rank-badge {
        background: linear-gradient(135deg, var(--ast-global-color-0, #8bc34a), var(--ast-global-color-1, #6a9739));
    }

    /* Tighten featured product layout now that rank sits at top-right */
    .ast-woo-sidebar-widget .wc-block-grid__product {
        position: relative;
    }

    /* ==========================================================
     * 18e. SIDEBAR PROMO CARD
     * ========================================================== */

    .pt-sidebar-promo {
        margin-top: 32px;
        padding: 24px 22px 22px;
        border-radius: 18px;
        background:
            radial-gradient(circle at 0% 0%, rgba(139,195,74,0.22) 0%, transparent 55%),
            radial-gradient(circle at 100% 100%, rgba(139,195,74,0.12) 0%, transparent 50%),
            linear-gradient(135deg, #1a3a2a 0%, #26543d 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(26, 58, 42, 0.18);
    }

    .pt-sidebar-promo-leaf {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.12);
        color: #c7e09a;
        margin-bottom: 12px;
    }

    .pt-sidebar-promo-eyebrow {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #c7e09a;
        margin-bottom: 6px;
    }

    .pt-sidebar-promo-title {
        font-size: 17px;
        font-weight: 700;
        line-height: 1.3;
        color: #ffffff;
        margin-bottom: 14px;
        letter-spacing: -0.2px;
    }

    .pt-sidebar-promo-code {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px dashed rgba(255, 255, 255, 0.35);
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 16px;
    }

    .pt-sidebar-promo-code::before {
        content: '🎟';
        display: none; /* hidden; we'll rely on the leaf icon above */
    }

    .pt-sidebar-promo-code span {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-weight: 700;
        letter-spacing: 2px;
        color: #ffffff;
        font-size: 13px;
    }

    .pt-sidebar-promo-btn {
        display: inline-flex !important;
        align-items: center;
        gap: 6px;
        padding: 11px 18px !important;
        background: #ffffff !important;
        color: #1a3a2a !important;
        border-radius: 30px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        text-decoration: none !important;
        transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.25s ease,
                    background 0.25s ease !important;
    }

    .pt-sidebar-promo-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2) !important;
        background: var(--ast-global-color-0, #8bc34a) !important;
        color: #1a3a2a !important;
    }

    .pt-sidebar-promo-btn span {
        transition: transform 0.25s ease;
    }

    .pt-sidebar-promo-btn:hover span {
        transform: translateX(3px);
    }

    /* Soft decorative pattern in background */
    .pt-sidebar-promo::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 140px;
        height: 140px;
        background: radial-gradient(circle, rgba(199, 224, 154, 0.18), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .pt-sidebar-promo > * {
        position: relative;
        z-index: 1;
    }

    /* Mobile: tighten the promo card */
    @media (max-width: 544px) {
        .pt-sidebar-promo {
            padding: 20px 18px;
        }

        .pt-sidebar-promo-title {
            font-size: 16px;
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

        .woocommerce ul.products {
            gap: 18px !important;
        }

        .woocommerce ul.products li.product .astra-shop-summary-wrap {
            padding: 16px 14px 20px !important;
        }

        .woocommerce ul.products li.product .woocommerce-loop-product__title {
            font-size: 15px !important;
        }

        .woocommerce ul.products li.product .price {
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

        .woocommerce ul.products li.product:hover {
            transform: translateY(-4px);
        }

        .woocommerce ul.products li.product .onsale {
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
     * 20b. SHOP ARCHIVE — catchy upgrades
     *
     * Fixes observed issues on /shop/ and category archives:
     *  - page title rendering as serif italic on category pages
     *  - Lorem ipsum in .term-description
     *  - sidebar featured products getting the full-size pulse
     *    sale badge on 64px thumbnails
     *  - archive feeling flat / static without hover CTA
     * ========================================================== */

    /* Subtle page-wide background wash for depth */
    body.archive.woocommerce,
    body.post-type-archive-product,
    body.tax-product_cat {
        background: linear-gradient(180deg, #ffffff 0%, #f9faf6 240px, #fbfbf8 100%);
    }

    /* Force the page title font to match the rest of the brand.
     * Astra's category archive template inherits a different font-
     * family than the shop page, producing the serif italic look.
     * This locks it to the theme's global sans-serif. */
    body.woocommerce h1.page-title,
    body.woocommerce h1.woocommerce-products-header__title,
    body.archive h1.page-title,
    body.archive .woocommerce-products-header__title,
    body.tax-product_cat h1.page-title {
        font-family: inherit !important;
        font-style: normal !important;
        font-weight: 800 !important;
        font-size: 52px !important;
        letter-spacing: -0.8px !important;
        color: #1a1a1a !important;
        line-height: 1.1 !important;
        margin: 0 auto 8px !important;
        display: inline-block;
    }

    /* Decorative green pill behind the title — gives the header visual anchor */
    body.woocommerce .woocommerce-products-header {
        position: relative;
        padding: 24px 0 56px !important;
        background: transparent;
    }

    body.woocommerce .woocommerce-products-header::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 50%;
        transform: translateX(-50%);
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(139, 195, 74, 0.16) 0%, rgba(139, 195, 74, 0) 70%);
        border-radius: 50%;
        filter: blur(30px);
        z-index: 0;
        pointer-events: none;
    }

    body.woocommerce .woocommerce-products-header > * {
        position: relative;
        z-index: 1;
    }

    /* Brand mark — decorative paw crown above the title */
    .pt-archive-crown {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 56px;
        height: 56px;
        margin: 0 auto 18px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(139,195,74,0.18), rgba(106,151,57,0.08));
        color: var(--ast-global-color-1, #6a9739);
        position: relative;
        z-index: 1;
        box-shadow: 0 4px 14px rgba(106, 151, 57, 0.1);
    }

    .pt-archive-crown svg {
        width: 28px;
        height: 28px;
    }

    /* Trust strip — flex row of check-icon pills */
    .pt-trust-strip {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin: 28px auto 0;
        position: relative;
        z-index: 1;
        max-width: 720px;
        padding: 0 16px;
    }

    .pt-trust-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #ffffff;
        border: 1px solid #eaeaea;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        color: #1a1a1a;
        letter-spacing: 0.2px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: border-color 0.25s ease, transform 0.25s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .pt-trust-item:hover {
        border-color: var(--ast-global-color-1, #6a9739);
        transform: translateY(-2px);
    }

    .pt-trust-item svg {
        width: 14px;
        height: 14px;
        color: var(--ast-global-color-1, #6a9739);
        flex-shrink: 0;
    }

    @media (max-width: 544px) {
        .pt-archive-crown {
            width: 44px;
            height: 44px;
            margin-bottom: 12px;
        }
        .pt-archive-crown svg {
            width: 22px;
            height: 22px;
        }
        .pt-trust-item {
            font-size: 11px;
            padding: 6px 12px;
        }
    }

    /* Category description — hide if empty, style if real */
    body.woocommerce .term-description,
    body.woocommerce .archive-description {
        max-width: 640px;
        margin: 8px auto 32px;
        padding: 0 20px;
        text-align: center;
        font-size: 15px;
        line-height: 1.6;
        color: #555;
        position: relative;
        z-index: 1;
    }

    body.woocommerce .term-description p,
    body.woocommerce .archive-description p {
        margin: 0;
    }

    body.woocommerce .term-description:empty,
    body.woocommerce .term-description p:empty,
    body.woocommerce .archive-description:empty {
        display: none !important;
    }

    /* Hide .term-description whose children are all empty paragraphs */
    body.woocommerce .term-description:has(> p:only-child:empty),
    body.woocommerce .archive-description:has(> p:only-child:empty) {
        display: none !important;
    }

    /* ==========================================================
     * 20c. PRODUCT CARD — hover overlay CTA
     *
     * On hover: subtle dark scrim + centered white arrow pill.
     * The whole thumbnail is already an <a> so the visual
     * functions purely as an affordance.
     * ========================================================== */

    .woocommerce ul.products li.product .astra-shop-thumbnail-wrap {
        position: relative;
        isolation: isolate;
    }

    /* Dark scrim */
    .woocommerce ul.products li.product .astra-shop-thumbnail-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(0deg, rgba(26,26,26,0.45) 0%, rgba(26,26,26,0.15) 45%, transparent 85%);
        opacity: 0;
        transition: opacity 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        pointer-events: none;
        z-index: 2;
    }

    .woocommerce ul.products li.product:hover .astra-shop-thumbnail-wrap::after {
        opacity: 1;
    }

    /* Make the anchor a positioning context for the arrow button */
    .woocommerce ul.products li.product .astra-shop-thumbnail-wrap > a {
        display: block;
        position: relative;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    /* Arrow pill — centered and revealed on hover */
    .woocommerce ul.products li.product .astra-shop-thumbnail-wrap > a::after {
        content: 'VIEW →';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) translateY(12px);
        padding: 11px 22px;
        background: #ffffff;
        color: #1a1a1a;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18), 0 2px 6px rgba(0,0,0,0.08);
        opacity: 0;
        transition: opacity 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                    transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        z-index: 4;
        pointer-events: none;
        white-space: nowrap;
    }

    .woocommerce ul.products li.product:hover .astra-shop-thumbnail-wrap > a::after {
        opacity: 1;
        transform: translate(-50%, -50%) translateY(0);
    }

    /* Kill the hover reveal on mobile — hover states don't make sense */
    @media (hover: none) {
        .woocommerce ul.products li.product .astra-shop-thumbnail-wrap::after,
        .woocommerce ul.products li.product .astra-shop-thumbnail-wrap > a::after {
            display: none !important;
        }
    }

    /* Zero-star ratings in archive — hide the visible bar */
    .woocommerce ul.products li.product .review-rating .star-rating > span[style*="width:0%"],
    .woocommerce ul.products li.product .review-rating .star-rating > span[style*="width: 0%"] {
        display: none;
    }
    .woocommerce ul.products li.product .review-rating:has(.star-rating > span[style*="width:0"]) {
        display: none;
    }

    /* ==========================================================
     * 20d. SIDEBAR FEATURED PRODUCTS — kill the oversized badge
     *
     * The sale badge CSS was designed for full-size cards. On the
     * tiny 64px sidebar thumbs it dominated the image. Override
     * with a compact, static badge.
     * ========================================================== */

    .ast-woo-sidebar-widget .wc-block-grid__product-onsale,
    .ast-woo-sidebar-widget .wc-block-grid__product .onsale {
        font-size: 9px !important;
        font-weight: 700 !important;
        padding: 2px 8px !important;
        border-radius: 4px !important;
        top: 4px !important;
        right: auto !important;
        left: 4px !important;
        line-height: 1.3 !important;
        box-shadow: 0 1px 4px rgba(238, 90, 36, 0.3) !important;
        animation: none !important;
        letter-spacing: 0.3px !important;
        position: absolute !important;
        z-index: 3 !important;
    }

    /* Ensure the sidebar product thumbnail is the positioning parent
     * for the mini sale badge */
    .ast-woo-sidebar-widget .wc-block-grid__product-link {
        position: relative;
    }

    .ast-woo-sidebar-widget .wc-block-grid__product-image {
        position: relative;
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
         *    by locale and plugin). Also hide the .review-rating
         *    wrapper Astra adds in the archive loop — otherwise the
         *    empty div leaves a vertical gap in the card content.
         * ---------------------------------------------------------- */
        document.querySelectorAll('.star-rating').forEach(function (r) {
            var inner = r.querySelector('span');
            if (!inner) return;
            var width = (inner.style.width || '').trim();
            if (width === '' || width === '0%' || width === '0') {
                r.style.display = 'none';
                var linkWrap = r.closest('.woocommerce-product-rating, .review-rating');
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

        /* ----------------------------------------------------------
         * G. Sidebar content injection — section headings + promo card
         *    The sidebar widgets come out of WordPress title-less, so
         *    we add proper <h3> labels (accessible) above each, plus
         *    a promotional card at the bottom.
         * ---------------------------------------------------------- */

        function makeHeading(text) {
            var h = document.createElement('h3');
            h.className = 'pt-sidebar-heading';
            h.textContent = text;
            return h;
        }

        // "SHOP BY CATEGORY" before the categories widget
        var categoriesWidget = document.querySelector(
            '.ast-woo-sidebar-widget:has(.wp-block-woocommerce-product-categories), ' +
            '.ast-woo-sidebar-widget .wc-block-product-categories-list'
        );
        var catContainer = categoriesWidget
            ? (categoriesWidget.closest('.ast-woo-sidebar-widget') || categoriesWidget)
            : null;
        if (catContainer && !catContainer.previousElementSibling?.classList.contains('pt-sidebar-heading')) {
            catContainer.parentNode.insertBefore(makeHeading('Shop by category'), catContainer);
        }

        // "TRENDING PICKS" before the best-sellers widget
        var bestSellersWidget = document.querySelector(
            '.ast-woo-sidebar-widget:has(.wp-block-woocommerce-product-best-sellers), ' +
            '.ast-woo-sidebar-widget .wc-block-product-best-sellers, ' +
            '.ast-woo-sidebar-widget .wp-block-product-best-sellers'
        );
        var bestContainer = bestSellersWidget
            ? (bestSellersWidget.closest('.ast-woo-sidebar-widget') || bestSellersWidget)
            : null;
        if (bestContainer && !bestContainer.previousElementSibling?.classList.contains('pt-sidebar-heading')) {
            bestContainer.parentNode.insertBefore(makeHeading('Trending picks'), bestContainer);
        }

        // Add rank numbers (01, 02, 03...) to featured products
        document.querySelectorAll('.ast-woo-sidebar-widget .wc-block-grid__product').forEach(function (prod, i) {
            if (prod.querySelector('.pt-rank-badge')) return;
            var rank = document.createElement('span');
            rank.className = 'pt-rank-badge';
            rank.textContent = String(i + 1).padStart(2, '0');
            var link = prod.querySelector('a, .wc-block-grid__product-link');
            (link || prod).prepend(rank);
        });

        /* ----------------------------------------------------------
         * H0. Homepage Best Sellers section — eyebrow, subtitle, CTA
         *     The "Best Sellers" heading is a bare h2 from Elementor.
         *     Wrap it with an eyebrow above and subtitle below, and
         *     inject a "View all products" CTA after the grid.
         * ---------------------------------------------------------- */
        var bestSellersHeading = Array.from(document.querySelectorAll('h2, h3')).find(function (h) {
            return (h.textContent || '').trim() === 'Best Sellers';
        });

        if (bestSellersHeading) {
            // Eyebrow above
            var headingWidget = bestSellersHeading.closest('.elementor-widget, .elementor-element') || bestSellersHeading.parentNode;
            if (headingWidget && !headingWidget.querySelector('.pt-products-eyebrow')) {
                var eyebrow = document.createElement('div');
                eyebrow.className = 'pt-products-eyebrow';
                eyebrow.textContent = 'Shop Our Bestsellers';
                headingWidget.insertBefore(eyebrow, headingWidget.firstChild);
            }

            // Subtitle directly after the heading
            var headingContainer = bestSellersHeading.parentNode;
            if (headingContainer && !headingContainer.querySelector('.pt-products-subtitle')) {
                var subtitle = document.createElement('div');
                subtitle.className = 'pt-products-subtitle';
                subtitle.textContent = 'The formulas dog owners keep coming back for. Sulfate-free, vet-approved, loved by thousands.';
                if (bestSellersHeading.nextSibling) {
                    headingContainer.insertBefore(subtitle, bestSellersHeading.nextSibling);
                } else {
                    headingContainer.appendChild(subtitle);
                }
            }
        }

        // "View all products" CTA injected at the bottom of the products section
        var homeProductsSection = document.querySelector('.pt-products-section');
        if (homeProductsSection && !homeProductsSection.querySelector('.pt-products-cta')) {
            var grid = homeProductsSection.querySelector('ul.products');
            if (grid) {
                var ctaWrap = document.createElement('div');
                ctaWrap.className = 'pt-products-cta';
                var ctaLink = document.createElement('a');
                ctaLink.className = 'pt-products-cta-link';
                ctaLink.href = '/shop/';
                ctaLink.innerHTML = 'View all products <span class="pt-products-cta-link-arrow" aria-hidden="true">→</span>';
                ctaWrap.appendChild(ctaLink);
                // Append after the closest shortcode/widget wrapper so it sits below the grid
                var gridWrapper = grid.closest('.elementor-shortcode, .elementor-widget-shortcode, .woocommerce') || grid.parentNode;
                gridWrapper.parentNode.insertBefore(ctaWrap, gridWrapper.nextSibling);
            }
        }

        /* ----------------------------------------------------------
         * H. Shop archive header — decorative brand mark + trust pills
         *    Injects a centered paw crown above the title and a row
         *    of check-icon pills below the description. Replaces the
         *    old text-only trust strip.
         * ---------------------------------------------------------- */
        var archiveHeader = document.querySelector('.woocommerce-products-header');
        if (archiveHeader && !archiveHeader.querySelector('.pt-archive-crown')) {
            var crown = document.createElement('div');
            crown.className = 'pt-archive-crown';
            crown.setAttribute('aria-hidden', 'true');
            crown.innerHTML =
                '<svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">' +
                    '<circle cx="5.5" cy="9" r="2"/>' +
                    '<circle cx="18.5" cy="9" r="2"/>' +
                    '<circle cx="8.5" cy="4.5" r="1.8"/>' +
                    '<circle cx="15.5" cy="4.5" r="1.8"/>' +
                    '<path d="M12 11c-3.5 0-6 3-6 6 0 1.66 1.34 3 3 3 1 0 1.5-.5 3-.5s2 .5 3 .5c1.66 0 3-1.34 3-3 0-3-2.5-6-6-6z"/>' +
                '</svg>';
            archiveHeader.insertBefore(crown, archiveHeader.firstChild);
        }

        // Trust pills — insert after the description (or at end of header if no description)
        var trustHost = archiveHeader;
        if (trustHost && !trustHost.querySelector('.pt-trust-strip')) {
            var checkIcon =
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">' +
                    '<polyline points="5 12 10 17 19 7"/>' +
                '</svg>';
            var items = [
                'Ships in 2 days',
                'Cruelty-free',
                '30-day guarantee',
                'Free shipping over $25'
            ];
            var strip = document.createElement('div');
            strip.className = 'pt-trust-strip';
            strip.setAttribute('role', 'list');
            strip.setAttribute('aria-label', 'Store promises');
            items.forEach(function (label) {
                var pill = document.createElement('span');
                pill.className = 'pt-trust-item';
                pill.setAttribute('role', 'listitem');
                pill.innerHTML = checkIcon + '<span>' + label + '</span>';
                strip.appendChild(pill);
            });
            trustHost.appendChild(strip);
        }

        // Promo card at the bottom of the sidebar (only on shop/archive
        // pages where the sidebar exists)
        var sidebar = document.querySelector('.widget-area.secondary .sidebar-main, .widget-area.secondary');
        if (sidebar && !sidebar.querySelector('.pt-sidebar-promo')) {
            var promo = document.createElement('aside');
            promo.className = 'pt-sidebar-promo';
            promo.setAttribute('aria-label', 'First-order discount');
            promo.innerHTML =
                '<span class="pt-sidebar-promo-leaf" aria-hidden="true">' +
                    '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' +
                        '<path d="M7 20C4.5 20 3 18 3 15.5 3 10 10 4 20 4c0 10-6 17-12 17-2.5 0-4-1.5-4-4"/>' +
                        '<path d="M3 20c4-4 8-6 14-7"/>' +
                    '</svg>' +
                '</span>' +
                '<div class="pt-sidebar-promo-eyebrow">New here?</div>' +
                '<div class="pt-sidebar-promo-title">Save 20% on your first order</div>' +
                '<div class="pt-sidebar-promo-code" aria-label="Coupon code"><span>CLEANCOAT</span></div>' +
                '<a href="/shop/" class="pt-sidebar-promo-btn">Shop now <span aria-hidden="true">→</span></a>';
            sidebar.appendChild(promo);
        }

    })();
    </script>
    <?php
}
