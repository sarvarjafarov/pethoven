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
     * 3b. FOOTER — cream to match body
     *
     * Astra's footer defaults to #111111 on .site-footer and
     * #000000 on both the primary-footer-builder and
     * below-footer-builder wraps. A black footer against the
     * cream body reads as a harsh band at the bottom of every
     * scroll.
     *
     * We previously tried dark forest green to match the brand,
     * but that clashed with the 20% off promo card (also dark
     * green) — the two regions visually merged into one dark
     * block with no clear separation.
     *
     * Solution: make the footer the same cream as the body. The
     * lower half of the page now reads as one calm light surface,
     * the dark-green promo card stands out cleanly against it,
     * and the footer content reads as part of the page — not a
     * separate dark slab at the end.
     * ========================================================== */

    .site-footer,
    .site-footer .site-primary-footer-wrap,
    .site-footer .site-below-footer-wrap,
    [data-section="section-primary-footer-builder"],
    [data-section="section-below-footer-builder"],
    .ast-builder-footer-grid-row {
        background-color: #fbfbf7 !important;
        background-image: none !important;
    }

    /* Text and links — dark on cream */
    .site-footer,
    .site-footer p,
    .site-footer span,
    .site-footer li {
        color: #2a2a2a !important;
    }

    .site-footer h1,
    .site-footer h2,
    .site-footer h3,
    .site-footer h4,
    .site-footer h5,
    .site-footer h6,
    .site-footer .widget-title,
    .site-footer .ast-widget-title {
        color: #1a1a1a !important;
    }

    .site-footer a {
        color: #4a4a4a !important;
        transition: color 0.2s ease;
    }

    .site-footer a:hover {
        color: var(--ast-global-color-1, #6a9739) !important;
    }

    /* Social icons — dark on cream, green on hover */
    .site-footer .ast-builder-social-element,
    .site-footer .ast-builder-social-element svg,
    .site-footer .ast-builder-social-element path {
        color: #4a4a4a !important;
        fill: currentColor !important;
    }

    .site-footer .ast-builder-social-element:hover,
    .site-footer .ast-builder-social-element:hover svg,
    .site-footer .ast-builder-social-element:hover path {
        color: var(--ast-global-color-1, #6a9739) !important;
        fill: currentColor !important;
    }

    /* Subtle divider line between primary-footer and below-footer */
    [data-section="section-below-footer-builder"] {
        border-top-color: rgba(0, 0, 0, 0.06) !important;
    }

    /* ==========================================================
     * 3c. FOOTER — structural polish
     *
     * Addresses the "sparse / unfinished" look of the default
     * Astra footer: kills the italic tagline, tightens column
     * gutters so the middle void disappears, adds a green accent
     * under each column heading, lifts link hover into a small
     * horizontal shift, and re-styles the below-footer so the
     * legal line + social row reads as a proper copyright bar.
     * ========================================================== */

    /* Contain the primary footer row and pull columns inward so
     * they don't drift to the page edges. Grid-row is already a
     * flex row in Astra; we just cap it and re-distribute. */
    .site-footer .site-primary-footer-wrap,
    .site-footer .site-below-footer-wrap {
        max-width: 1120px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        padding-left: 32px !important;
        padding-right: 32px !important;
    }

    .site-footer .ast-builder-grid-row {
        gap: 40px !important;
        align-items: flex-start !important;
    }

    /* Force the primary footer to 3 equal columns.
     *
     * Astra's `.ast-builder-grid-row` defaults to
     * `grid-template-columns: auto auto` (2 tracks). The 3-equal
     * layout class is supposed to override that, but in our setup
     * it isn't always applied — the result was the logo column
     * collapsing to its own row and leaving Website/Site Links
     * floating in a wide 2-column row.
     *
     * `min-width: 0` on children is required so grid items can
     * shrink below their intrinsic content width — without it a
     * single tall child can force the other columns to wrap. */
    .site-footer .site-primary-footer-wrap .ast-builder-grid-row {
        grid-template-columns: 1fr 1fr 1fr !important;
    }

    .site-footer .site-primary-footer-wrap .ast-builder-grid-row > * {
        min-width: 0 !important;
    }

    /* Soft top border so the footer reads as its own section
     * against the cream body wash, without introducing a dark slab */
    .site-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.06) !important;
    }

    /* Tagline under the logo — kill italic, tighten width, warm up color.
     * The tagline lives inside an HTML builder widget which Astra renders
     * as <em> inside <p>. Override at every level to defeat inline styles
     * and the theme's italic default. */
    .site-footer p,
    .site-footer p em,
    .site-footer p i,
    .site-footer em,
    .site-footer i,
    .site-footer .ast-builder-html-element,
    .site-footer .ast-builder-html-element *,
    .site-footer [data-section^="section-fb-html-"],
    .site-footer [data-section^="section-fb-html-"] * {
        font-style: normal !important;
    }

    .site-footer [data-section^="section-fb-html-"] p,
    .site-footer .ast-builder-html-element p {
        font-size: 14.5px !important;
        line-height: 1.65 !important;
        color: #4a4a4a !important;
        max-width: 340px !important;
        margin: 0 0 14px 0 !important;
    }

    /* Column headings — add a short green accent underline + set
     * a consistent size / weight / tracking across all four widget
     * areas, regardless of which heading level the widget renders */
    .site-footer .widget-title,
    .site-footer .ast-widget-title,
    .site-footer h2.widget-title,
    .site-footer h3.widget-title,
    .site-footer h4.widget-title {
        position: relative !important;
        font-size: 13px !important;
        font-weight: 800 !important;
        letter-spacing: 2px !important;
        text-transform: uppercase !important;
        color: #1a1a1a !important;
        margin: 0 0 22px 0 !important;
        padding-bottom: 10px !important;
    }

    .site-footer .widget-title::after,
    .site-footer .ast-widget-title::after {
        content: "" !important;
        position: absolute !important;
        left: 0 !important;
        bottom: 0 !important;
        width: 28px !important;
        height: 3px !important;
        background: var(--ast-global-color-1, #6a9739) !important;
        border-radius: 2px !important;
    }

    /* Nav link columns — tighten list rhythm and add a hover shift */
    .site-footer .widget ul,
    .site-footer .menu {
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .site-footer .widget ul li,
    .site-footer .menu li {
        margin: 0 0 10px 0 !important;
        padding: 0 !important;
        border: 0 !important;
    }

    .site-footer .widget ul li a,
    .site-footer .menu li a {
        display: inline-block !important;
        font-size: 14.5px !important;
        font-weight: 500 !important;
        color: #4a4a4a !important;
        text-decoration: none !important;
        transition: color 0.2s ease, transform 0.2s ease !important;
    }

    .site-footer .widget ul li a:hover,
    .site-footer .menu li a:hover {
        color: var(--ast-global-color-1, #6a9739) !important;
        transform: translateX(3px) !important;
    }

    /* Below-footer — cleaner legal/copyright bar */
    .site-footer .site-below-footer-wrap {
        padding-top: 20px !important;
        padding-bottom: 20px !important;
        border-top: 1px solid rgba(0, 0, 0, 0.08) !important;
    }

    .site-footer .site-below-footer-wrap p,
    .site-footer .ast-footer-copyright,
    .site-footer .ast-footer-copyright p {
        font-size: 13px !important;
        color: #6a6a6a !important;
        margin: 0 !important;
        line-height: 1.55 !important;
    }

    /* Social icons — small constant-size pills instead of bare SVGs
     * that felt orphaned in the corner */
    .site-footer .ast-builder-social-element {
        width: 34px !important;
        height: 34px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        border-radius: 50% !important;
        background: rgba(255, 255, 255, 0.6) !important;
        margin-left: 6px !important;
    }

    .site-footer .ast-builder-social-element:hover {
        border-color: var(--ast-global-color-1, #6a9739) !important;
        background: rgba(139, 195, 74, 0.1) !important;
        transform: translateY(-2px) !important;
    }

    .site-footer .ast-builder-social-element svg {
        width: 15px !important;
        height: 15px !important;
    }

    /* ----- Injected contact block (see JS section N2) -----
     * Adds support email + location + response time below the
     * tagline so the logo column doesn't feel half-empty. */
    .pt-footer-contact {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .pt-footer-contact-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13.5px;
        color: #4a4a4a;
        line-height: 1.4;
    }

    .pt-footer-contact-row svg {
        width: 15px;
        height: 15px;
        margin-top: 2px;
        flex-shrink: 0;
        color: var(--ast-global-color-1, #6a9739);
    }

    .pt-footer-contact-row a {
        color: #4a4a4a !important;
        text-decoration: none !important;
        transition: color 0.2s ease !important;
    }

    .pt-footer-contact-row a:hover {
        color: var(--ast-global-color-1, #6a9739) !important;
    }

    .pt-footer-contact-row strong {
        color: #1a1a1a;
        font-weight: 600;
    }

    /* ----- Injected copyright bar (see JS section N2) -----
     * A proper bottom strip with © year + legal entity on the left
     * and the social icons on the right — replaces the default
     * below-footer row which left the icons floating in an empty
     * strip with no balance. */
    .pt-footer-copybar {
        max-width: 1120px;
        margin: 0 auto;
        padding: 22px 32px 28px;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px 32px;
        font-size: 13px;
        color: #6a6a6a;
    }

    .pt-footer-copybar-left,
    .pt-footer-copybar-right {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px 18px;
    }

    .pt-footer-copybar-left {
        min-width: 0;
    }

    .pt-footer-copybar-sep {
        color: rgba(0, 0, 0, 0.2);
    }

    /* "Follow us:" label sits before the social icons in the bar */
    .pt-footer-copybar-sociallabel {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #6a6a6a;
        margin-right: 4px;
    }

    /* Social icons, once relocated into the copybar, sit in a tight
     * inline row with our pill styling. We rely on the pill sizing
     * set earlier in section 3c so icons look identical wherever
     * they live. */
    .pt-footer-copybar .ast-builder-social-element,
    .pt-footer-copybar [class*="ast-header-social"] .ast-builder-social-element {
        margin-left: 0 !important;
    }

    .pt-footer-copybar-social {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* Hide the stock below-footer row once we've moved its contents
     * into our copybar. Without this the old empty strip stays in
     * the DOM and leaves a ~60px void above our bar. */
    .site-footer.pt-copybar-active .site-below-footer-wrap {
        display: none !important;
    }

    @media (max-width: 768px) {
        .site-footer .ast-builder-grid-row {
            gap: 32px !important;
        }
        .site-footer .site-primary-footer-wrap,
        .site-footer .site-below-footer-wrap {
            padding-left: 20px !important;
            padding-right: 20px !important;
        }
        .pt-footer-copybar {
            padding: 22px 20px 26px;
            justify-content: center;
            text-align: center;
        }
        .pt-footer-copybar-left,
        .pt-footer-copybar-right {
            justify-content: center;
        }
    }


    /* ==========================================================
     * 4a. HOMEPAGE BODY WASH
     *
     * Give the homepage a very subtle warm-white base color so
     * every section sits on the same underlying tone. Sections
     * that need emphasis (Best Sellers tint, 20% off dark card)
     * render over this base rather than against pure white,
     * which removes the hard edges we had between sections.
     * ========================================================== */

    body.home,
    body.page.home,
    html body.home {
        background-color: #fbfbf7 !important;
        background-image: none !important;
    }

    /* Make sure the content wrappers (site-content / #content,
     * Astra's .ast-page-builder-template wrapper, the main tag)
     * don't paint white/dark over the body cream. */
    body.home #content,
    body.home .site-content,
    body.home .site-content > .ast-container,
    body.home main,
    body.home .ast-page-builder-template .site-content,
    body.home.ast-page-builder-template #content {
        background-color: transparent !important;
        background: transparent !important;
    }

    /* ==========================================================
     * 4b. PRODUCTS SECTION WRAPPER
     * ========================================================== */

    /* Section background — fades in from the body base, tints
     * subtly in the middle, then fades back to the base. No hard
     * edges with the sections above and below. */
    .pt-products-section,
    .elementor-element-f980f52 {
        background: linear-gradient(180deg,
            rgba(241, 244, 236, 0) 0%,
            rgba(241, 244, 236, 0.75) 18%,
            rgba(241, 244, 236, 0.75) 82%,
            rgba(241, 244, 236, 0) 100%) !important;
        padding: 80px 0 !important;
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

    /* ---- Mobile: 2×2 grid, icon-on-top centered ----
     * Previous mobile layout stacked the 4 cards vertically. Icon
     * sat on the LEFT with text on the right — which with icon
     * sizes and mobile padding read as "left-aligned" and loose.
     * The 2×2 grid with icon-on-top, content centered, is denser
     * and feels balanced on a phone. Dividers stay as subtle
     * 1px lines between rows and between columns.
     */
    @media (max-width: 544px) {
        .pt-features-bar {
            margin-top: -16px !important;
            padding: 0 16px !important;
        }

        .pt-features-bar > .e-con-inner {
            border-radius: 14px;
            flex-wrap: wrap !important;
        }

        .pt-features-bar .e-con.e-child {
            flex: 0 0 50% !important;
            max-width: 50% !important;
            padding: 18px 10px !important;
            border: none !important;
            text-align: center !important;
        }

        /* Column divider between col 1 and col 2 */
        .pt-features-bar .e-con.e-child:nth-child(odd) {
            border-right: 1px solid #f0f0f0 !important;
        }
        /* Row divider between row 1 and row 2 */
        .pt-features-bar .e-con.e-child:nth-child(1),
        .pt-features-bar .e-con.e-child:nth-child(2) {
            border-bottom: 1px solid #f0f0f0 !important;
        }

        /* Stack icon on top, center everything */
        .pt-features-bar .elementor-icon-box-wrapper {
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            gap: 8px !important;
        }
        .pt-features-bar .elementor-icon-box-icon,
        .pt-features-bar .elementor-icon-box-content {
            text-align: center !important;
            margin: 0 auto !important;
        }

        .pt-features-bar .elementor-icon {
            width: 40px !important;
            height: 40px !important;
            margin: 0 auto !important;
        }
        .pt-features-bar .elementor-icon i,
        .pt-features-bar .elementor-icon svg {
            font-size: 16px !important;
            width: 16px !important;
            height: 16px !important;
        }
        .pt-features-bar .elementor-icon-box-title,
        .pt-features-bar .elementor-icon-box-title a {
            font-size: 13px !important;
            line-height: 1.25 !important;
        }
        .pt-features-bar .elementor-icon-box-description {
            font-size: 11px !important;
            line-height: 1.35 !important;
            margin-top: 2px !important;
        }

        .pt-features-bar .e-con.e-child:hover { border-radius: 0 !important; transform: none !important; }
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
     * 22. HOMEPAGE — CATEGORY CARDS (Sensitive / Deep Clean / Puppy)
     *     Section: .elementor-element-d349891
     *
     * Three image-box widgets + three Shop Now buttons inherit
     * the grocery-template look (basil-leaf icon, plain text, flat
     * buttons). Transform each column into a proper premium card
     * with a gradient icon disc, hover lift, and unified CTA.
     * ========================================================== */

    /* Kill the basil leaf icon inside every image-box on this section */
    .elementor-element-d349891 .elementor-image-box-img,
    .elementor-element-d349891 .elementor-image-box-img img {
        display: none !important;
    }

    /* Section spacing */
    .elementor-element-d349891 {
        padding: 72px 0 88px !important;
    }

    /* Injected heading wrapper — sits above the 3-card row and
     * takes a full-width slot even when the parent is a flex row.
     * Margin below is small because the subtitle inside already
     * carries its own 40px bottom margin (was double-stacked). */
    .pt-cats-header {
        flex: 0 0 100% !important;
        width: 100% !important;
        max-width: 100% !important;
        text-align: center;
        padding: 0 20px;
        margin: 0 auto 8px;
        box-sizing: border-box;
    }

    /* Section eyebrow + heading injected via JS (pt-cats-heading) */
    .pt-cats-eyebrow {
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
    }

    .pt-cats-eyebrow::before,
    .pt-cats-eyebrow::after {
        content: '';
        width: 28px;
        height: 1.5px;
        background: var(--ast-global-color-1, #6a9739);
        opacity: 0.5;
        border-radius: 2px;
    }

    .pt-cats-heading {
        font-family: inherit !important;
        font-size: 40px;
        font-weight: 800;
        color: #1a1a1a;
        text-align: center;
        margin: 0 auto 14px;
        letter-spacing: -0.5px;
        line-height: 1.1;
    }

    .pt-cats-subtitle {
        font-size: 16px;
        color: #666;
        text-align: center;
        margin: 0 auto 40px;
        max-width: 540px;
        line-height: 1.55;
        padding: 0 20px;
    }

    /* Turn each Elementor column / flex child into a card */
    .elementor-element-d349891 > .elementor-container > .elementor-column,
    .elementor-element-d349891 > .e-con-inner > .e-con.e-child,
    .elementor-element-d349891 > .elementor-container .elementor-widget-wrap,
    .elementor-element-d349891 .e-con > .e-con-inner > .e-con.e-child {
        background: #ffffff !important;
        background-color: #ffffff !important;
        border: 1px solid #f0f0ec !important;
        border-radius: 24px !important;
        padding: 48px 32px 40px !important;
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1),
                    border-color 0.35s ease,
                    box-shadow 0.4s cubic-bezier(0.22, 1, 0.36, 1) !important;
        position: relative;
        overflow: hidden;
        align-items: center !important;
    }

    /* Green accent on hover */
    .elementor-element-d349891 > .elementor-container > .elementor-column::before,
    .elementor-element-d349891 > .e-con-inner > .e-con.e-child::before {
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
        z-index: 2;
    }

    .elementor-element-d349891 > .elementor-container > .elementor-column:hover::before,
    .elementor-element-d349891 > .e-con-inner > .e-con.e-child:hover::before {
        transform: scaleX(1);
    }

    .elementor-element-d349891 > .elementor-container > .elementor-column:hover,
    .elementor-element-d349891 > .e-con-inner > .e-con.e-child:hover {
        transform: translateY(-8px);
        border-color: rgba(139, 195, 74, 0.32) !important;
        box-shadow: 0 24px 48px rgba(106, 151, 57, 0.12),
                    0 8px 20px rgba(0, 0, 0, 0.04) !important;
    }

    /* Inject a gradient icon disc above each image-box where the
     * basil leaf used to sit */
    .elementor-element-d349891 .elementor-widget-image-box {
        text-align: center;
        margin-bottom: 0 !important;
    }

    .elementor-element-d349891 .elementor-widget-image-box .elementor-image-box-wrapper {
        position: relative;
        padding-top: 96px;
    }

    .elementor-element-d349891 .elementor-widget-image-box .elementor-image-box-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%) scale(1);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(139,195,74,0.28) 0%, rgba(106,151,57,0.08) 100%);
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 8px 20px rgba(106, 151, 57, 0.12);
    }

    .elementor-element-d349891 > .elementor-container > .elementor-column:hover .elementor-widget-image-box .elementor-image-box-wrapper::before,
    .elementor-element-d349891 > .e-con-inner > .e-con.e-child:hover .elementor-widget-image-box .elementor-image-box-wrapper::before {
        transform: translateX(-50%) scale(1.08);
    }

    /* Per-card color variation: 1 bright green, 2 deep green, 3 warm peach */
    .elementor-element-d349891 > .elementor-container > .elementor-column:nth-child(2) .elementor-widget-image-box .elementor-image-box-wrapper::before,
    .elementor-element-d349891 > .e-con-inner > .e-con.e-child:nth-child(2) .elementor-widget-image-box .elementor-image-box-wrapper::before {
        background: linear-gradient(135deg, rgba(38, 84, 61, 0.28) 0%, rgba(26, 58, 42, 0.08) 100%);
        box-shadow: 0 8px 20px rgba(38, 84, 61, 0.14);
    }

    .elementor-element-d349891 > .elementor-container > .elementor-column:nth-child(3) .elementor-widget-image-box .elementor-image-box-wrapper::before,
    .elementor-element-d349891 > .e-con-inner > .e-con.e-child:nth-child(3) .elementor-widget-image-box .elementor-image-box-wrapper::before {
        background: linear-gradient(135deg, rgba(245, 183, 120, 0.38) 0%, rgba(255, 216, 168, 0.1) 100%);
        box-shadow: 0 8px 20px rgba(230, 160, 90, 0.16);
    }

    /* SVG icon injected via JS, positioned on top of the gradient disc.
     * One icon per card; color tinted per card to match the disc. */
    .pt-cat-icon {
        position: absolute;
        top: 0;
        left: 50%;
        width: 80px;
        height: 80px;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        z-index: 1;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .pt-cat-icon svg {
        width: 36px;
        height: 36px;
        color: var(--ast-global-color-1, #6a9739);
        stroke: currentColor;
        fill: none;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* Filled paw icon for the puppy card */
    .pt-cat-icon.pt-cat-icon--filled svg {
        fill: currentColor;
        stroke: none;
    }

    .elementor-element-d349891 > .elementor-container > .elementor-column:nth-child(2) .pt-cat-icon svg,
    .elementor-element-d349891 > .e-con-inner > .e-con.e-child:nth-child(2) .pt-cat-icon svg {
        color: #26543d;
    }

    .elementor-element-d349891 > .elementor-container > .elementor-column:nth-child(3) .pt-cat-icon svg,
    .elementor-element-d349891 > .e-con-inner > .e-con.e-child:nth-child(3) .pt-cat-icon svg {
        color: #c87d3a;
    }

    .elementor-element-d349891 > .elementor-container > .elementor-column:hover .pt-cat-icon,
    .elementor-element-d349891 > .e-con-inner > .e-con.e-child:hover .pt-cat-icon {
        transform: translateX(-50%) scale(1.08);
    }

    /* Title + description */
    .elementor-element-d349891 .elementor-image-box-title {
        font-family: inherit !important;
        font-size: 24px !important;
        font-weight: 800 !important;
        color: #1a1a1a !important;
        margin: 0 0 14px !important;
        letter-spacing: -0.3px !important;
        line-height: 1.2 !important;
    }

    .elementor-element-d349891 .elementor-image-box-description {
        font-size: 15px !important;
        color: #666 !important;
        line-height: 1.6 !important;
        margin: 0 !important;
        min-height: 96px;
    }

    /* Shop Now button — match the black-pill CTA language */
    .elementor-element-d349891 .elementor-widget-button {
        text-align: center;
        margin-top: 28px !important;
    }

    .elementor-element-d349891 .elementor-button {
        display: inline-flex !important;
        align-items: center;
        gap: 8px;
        padding: 12px 28px !important;
        background: #1a1a1a !important;
        background-color: #1a1a1a !important;
        background-image: none !important;
        color: #ffffff !important;
        fill: #ffffff !important;
        border: none !important;
        border-radius: 100px !important;
        font-family: inherit !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        text-decoration: none !important;
        transition: background-color 0.3s ease, color 0.3s ease,
                    transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.3s ease !important;
    }

    .elementor-element-d349891 .elementor-button:hover {
        background: var(--ast-global-color-1, #6a9739) !important;
        background-color: var(--ast-global-color-1, #6a9739) !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(106, 151, 57, 0.28) !important;
    }

    /* ==========================================================
     * 23. HOMEPAGE — 20% OFF PROMO (28fc7dc)
     *
     * A single heading + button section. Wrap it in a dark green
     * branded card with an icon, coupon pill, and white CTA.
     * ========================================================== */

    /* Section hidden per user request — the Elementor dark
     * background kept bleeding around our injected green card no
     * matter how we overrode it (likely cached CSS from a CDN or
     * an Elementor inline style we couldn't reach cleanly). The
     * "first-order 20% off" message still lives in the rotating
     * announcement bar at the top of the site, so we're not
     * losing the offer — just removing the problematic section. */
    .elementor-element-28fc7dc,
    .elementor-95 .elementor-element.elementor-element-28fc7dc,
    .elementor-95 .elementor-element.elementor-element-28fc7dc:not(.elementor-motion-effects-element-type-background) {
        display: none !important;
    }

    /* Kill any Elementor background overlay layered on top */
    .elementor-element-28fc7dc > .elementor-background-overlay,
    .elementor-element-28fc7dc > .elementor-background-video-container {
        display: none !important;
    }

    /* Elementor section nested wrappers sometimes also paint a bg
     * (inner container, shape divider). Clear those too. */
    .elementor-element-28fc7dc > .elementor-container,
    .elementor-element-28fc7dc > .e-con-inner,
    .elementor-element-28fc7dc .elementor-shape {
        background: transparent !important;
        background-color: transparent !important;
    }

    /* Decorative dark card background */
    .elementor-element-28fc7dc::before {
        content: '';
        position: absolute;
        left: max(20px, 50% - 620px);
        right: max(20px, 50% - 620px);
        top: 36px;
        bottom: 48px;
        background:
            radial-gradient(circle at 12% 18%, rgba(139,195,74,0.26) 0%, transparent 55%),
            radial-gradient(circle at 88% 82%, rgba(139,195,74,0.18) 0%, transparent 50%),
            linear-gradient(135deg, #1a3a2a 0%, #26543d 100%);
        border-radius: 28px;
        z-index: 0;
        pointer-events: none;
        box-shadow: 0 20px 50px rgba(26, 58, 42, 0.18);
    }

    .elementor-element-28fc7dc > * {
        position: relative;
        z-index: 1;
    }

    .elementor-element-28fc7dc .elementor-widget-container {
        padding: 56px 24px !important;
        text-align: center;
    }

    /* Heading — white + split into eyebrow + title via CSS */
    .elementor-element-28fc7dc .elementor-heading-title {
        font-family: inherit !important;
        font-size: 36px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        line-height: 1.15 !important;
        letter-spacing: -0.5px !important;
        text-align: center !important;
        max-width: 640px;
        margin: 0 auto !important;
    }

    /* Promo eyebrow injected via JS */
    .pt-promo-eyebrow {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #c7e09a;
        text-align: center;
        margin: 0 auto 12px;
    }

    /* Coupon code pill injected via JS */
    .pt-promo-code {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin: 22px auto 0;
        padding: 10px 18px;
        background: rgba(255, 255, 255, 0.1);
        border: 1.5px dashed rgba(255, 255, 255, 0.4);
        border-radius: 12px;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-weight: 700;
        letter-spacing: 2px;
        font-size: 14px;
        color: #ffffff;
    }

    .pt-promo-code-label {
        font-family: inherit;
        font-size: 10px;
        letter-spacing: 2px;
        font-weight: 700;
        color: #c7e09a;
        text-transform: uppercase;
    }

    .elementor-element-28fc7dc .elementor-widget-button {
        margin-top: 28px;
    }

    .elementor-element-28fc7dc .elementor-button {
        display: inline-flex !important;
        align-items: center;
        gap: 10px;
        padding: 15px 36px !important;
        background: #ffffff !important;
        background-color: #ffffff !important;
        background-image: none !important;
        color: #1a3a2a !important;
        fill: #1a3a2a !important;
        border: none !important;
        border-radius: 100px !important;
        font-family: inherit !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: 1.8px !important;
        text-transform: uppercase !important;
        text-decoration: none !important;
    }

    .elementor-element-28fc7dc .elementor-button:hover {
        background: var(--ast-global-color-0, #8bc34a) !important;
        background-color: var(--ast-global-color-0, #8bc34a) !important;
        color: #1a3a2a !important;
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(0, 0, 0, 0.25) !important;
    }

    /* ==========================================================
     * 24. HOMEPAGE — TESTIMONIALS + BUNDLE (ea9e0d9)
     *
     * Improve testimonial cards and the Buy-2-Get-1 image-box
     * at the bottom of the section.
     * ========================================================== */

    .elementor-element-ea9e0d9,
    .elementor-95 .elementor-element.elementor-element-ea9e0d9,
    .elementor-95 .elementor-element.elementor-element-ea9e0d9:not(.elementor-motion-effects-element-type-background) {
        padding: 80px 0 !important;
        /* Elementor renders a white→cream linear-gradient plus a
         * decorative leaf image ::before on this section. Nuke
         * both so the cream body shows through uniformly. Matching
         * Elementor's selector depth so our rule wins on
         * specificity as well as !important. */
        background: transparent !important;
        background-color: transparent !important;
        background-image: none !important;
    }

    .elementor-element-ea9e0d9 > .elementor-background-overlay,
    .elementor-element-ea9e0d9 > .elementor-background-video-container,
    .elementor-element-ea9e0d9 .elementor-shape {
        display: none !important;
    }

    /* Kill the leaf background image Elementor stacks on the
     * section's ::before pseudo-element */
    .elementor-95 .elementor-element.elementor-element-ea9e0d9::before {
        background-image: none !important;
        content: none !important;
        display: none !important;
    }

    .elementor-element-ea9e0d9 > .elementor-container,
    .elementor-element-ea9e0d9 > .e-con-inner {
        background: transparent !important;
        background-color: transparent !important;
    }

    /* Testimonial cards */
    .elementor-element-ea9e0d9 .elementor-testimonial-wrapper {
        background: #ffffff;
        border: 1px solid #f0f0ec;
        border-radius: 24px;
        padding: 36px 32px;
        position: relative;
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                    border-color 0.3s ease,
                    box-shadow 0.35s ease;
    }

    .elementor-element-ea9e0d9 .elementor-widget-testimonial:hover .elementor-testimonial-wrapper {
        transform: translateY(-6px);
        border-color: rgba(139, 195, 74, 0.32);
        box-shadow: 0 20px 40px rgba(106, 151, 57, 0.08),
                    0 4px 12px rgba(0, 0, 0, 0.04);
    }

    /* Decorative opening quote */
    .elementor-element-ea9e0d9 .elementor-testimonial-wrapper::before {
        content: '\201C';  /* left double quote */
        position: absolute;
        top: 18px;
        left: 30px;
        font-family: Georgia, serif;
        font-size: 60px;
        line-height: 1;
        color: var(--ast-global-color-0, #8bc34a);
        opacity: 0.3;
        pointer-events: none;
    }

    .elementor-element-ea9e0d9 .elementor-testimonial-content {
        position: relative;
        z-index: 1;
        font-size: 15px !important;
        line-height: 1.65 !important;
        color: #2a2a2a !important;
        margin-bottom: 20px !important;
    }

    .elementor-element-ea9e0d9 .elementor-testimonial-details {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .elementor-element-ea9e0d9 .elementor-testimonial-image img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
    }

    .elementor-element-ea9e0d9 .elementor-testimonial-identity {
        display: flex;
        flex-direction: column;
    }

    .elementor-element-ea9e0d9 .elementor-testimonial-name {
        font-weight: 700 !important;
        color: #1a1a1a !important;
        font-size: 14px !important;
    }

    .elementor-element-ea9e0d9 .elementor-testimonial-job {
        font-size: 12px !important;
        color: #888 !important;
    }

    .elementor-element-ea9e0d9 .elementor-star-rating {
        color: #f5a623 !important;
    }

    /* Bundle deal — image-box containing "Buy 2, Get 1 Free" */
    .elementor-element-ea9e0d9 .elementor-widget-image-box .elementor-image-box-img,
    .elementor-element-ea9e0d9 .elementor-widget-image-box .elementor-image-box-img img {
        display: none !important;
    }

    /* Kill everything the grocery template put on the middle promo
     * container (element-79f6103): the pepper Unsplash photo, the
     * Elementor overlay ::before that paints a dark grey over it,
     * the drop shadow, and the forced width. We want this container
     * to be a transparent pass-through so only the Buy 2 Get 1 card
     * inside it renders. */
    .elementor-95 .elementor-element.elementor-element-79f6103,
    .elementor-element-79f6103,
    .elementor-element-79f6103:not(.elementor-motion-effects-element-type-background),
    .elementor-element-79f6103 > .elementor-motion-effects-container > .elementor-motion-effects-layer {
        background-image: none !important;
        background-color: transparent !important;
        box-shadow: none !important;
        --overlay-opacity: 0 !important;
    }

    /* Elementor's overlay ::before on the container still paints a
     * dark grey via `background-color: var(--e-global-color-astglobalcolor3)`.
     * Remove it entirely so the container is truly transparent. */
    .elementor-95 .elementor-element.elementor-element-79f6103::before,
    .elementor-element-79f6103::before,
    .elementor-element-79f6103 > .elementor-background-video-container::before,
    .elementor-element-79f6103 > .e-con-inner > .elementor-background-video-container::before,
    .elementor-element-79f6103 > .elementor-background-slideshow::before,
    .elementor-element-79f6103 > .e-con-inner > .elementor-background-slideshow::before {
        content: none !important;
        display: none !important;
        background-color: transparent !important;
        background-image: none !important;
    }

    /* Hide the decorative leaf-branch image (logo-leaf-new.png)
     * that the grocery template placed between the heading and
     * the testimonial cards. It's off-brand and adds vertical
     * noise without earning its space. */
    .elementor-element-ea9e0d9 .elementor-element-0270db5,
    .elementor-element-ea9e0d9 .elementor-widget-image img[src*="logo-leaf"],
    .elementor-element-ea9e0d9 .elementor-widget-image img[src*="logo-leaf-new"] {
        display: none !important;
    }

    .elementor-element-ea9e0d9 .elementor-widget-image:has(img[src*="logo-leaf"]) {
        display: none !important;
    }

    .elementor-element-ea9e0d9 .elementor-widget-image-box {
        background: linear-gradient(135deg, #f5f7f0 0%, #eef2e8 100%);
        border: 1px solid rgba(139, 195, 74, 0.2);
        border-radius: 24px;
        padding: 44px 32px;
        text-align: center;
        margin-top: 56px;
        position: relative;
        overflow: hidden;
    }

    .elementor-element-ea9e0d9 .elementor-widget-image-box::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(139, 195, 74, 0.2), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .elementor-element-ea9e0d9 .elementor-widget-image-box > * {
        position: relative;
        z-index: 1;
    }

    .elementor-element-ea9e0d9 .elementor-image-box-title {
        font-family: inherit !important;
        font-size: 28px !important;
        font-weight: 800 !important;
        color: #1a1a1a !important;
        margin: 0 0 12px !important;
        letter-spacing: -0.3px !important;
    }

    .elementor-element-ea9e0d9 .elementor-image-box-description {
        font-size: 15px !important;
        color: #555 !important;
        line-height: 1.6 !important;
        margin: 0 auto !important;
        max-width: 520px;
    }

    .elementor-element-ea9e0d9 .elementor-widget-button {
        text-align: center;
        margin-top: 20px;
    }

    .elementor-element-ea9e0d9 .elementor-button {
        display: inline-flex !important;
        align-items: center;
        gap: 10px;
        padding: 14px 32px !important;
        background: #1a1a1a !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 100px !important;
        font-family: inherit !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        text-decoration: none !important;
        transition: background-color 0.3s ease, transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.3s ease !important;
    }

    .elementor-element-ea9e0d9 .elementor-button:hover {
        background: var(--ast-global-color-1, #6a9739) !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(106, 151, 57, 0.28) !important;
    }

    /* ==========================================================
     * 25. HOMEPAGE — HERO (3849851)
     *
     * Add a trust micro-strip below the main CTA matching the
     * archive's language.
     * ========================================================== */

    .pt-hero-trust {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 18px;
        margin-top: 22px;
        font-size: 12px;
        color: #888;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .pt-hero-trust-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .pt-hero-trust-item svg {
        width: 14px;
        height: 14px;
        color: var(--ast-global-color-1, #6a9739);
        flex-shrink: 0;
    }

    /* Make the hero "Shop Now" match the new black-pill CTA language */
    .elementor-element-3849851 .elementor-button {
        padding: 16px 38px !important;
        background: #1a1a1a !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 100px !important;
        font-family: inherit !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        transition: background-color 0.3s ease, transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.3s ease !important;
    }

    .elementor-element-3849851 .elementor-button:hover {
        background: var(--ast-global-color-1, #6a9739) !important;
        color: #ffffff !important;
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(106, 151, 57, 0.28) !important;
    }

    /* Style the "Vet-Approved. Organic. Effective." eyebrow as a
     * tiny green pill so it reads as a branded label rather than
     * a stray heading above the H1. */
    .elementor-element-3849851 h5,
    .elementor-element-3849851 .elementor-widget-heading h5,
    .elementor-element-3849851 .hfe-infocard-sub-title {
        display: inline-block;
        font-family: inherit !important;
        font-size: 11px !important;
        font-weight: 800 !important;
        letter-spacing: 2.5px !important;
        text-transform: uppercase !important;
        color: var(--ast-global-color-1, #6a9739) !important;
        background: rgba(139, 195, 74, 0.12);
        padding: 6px 14px !important;
        border-radius: 100px !important;
        margin-bottom: 18px !important;
        line-height: 1.3 !important;
    }

    /* ==========================================================
     * 26. HOMEPAGE — SECTION RHYTHM
     *
     * Normalize vertical spacing across hero-adjacent promo
     * sections so the page has a consistent rhythm.
     * ========================================================== */

    @media (max-width: 921px) {
        .elementor-element-d349891 {
            padding: 60px 0 !important;
        }
        .elementor-element-d349891 > .elementor-container > .elementor-column,
        .elementor-element-d349891 > .e-con-inner > .e-con.e-child {
            padding: 36px 24px !important;
        }
        .elementor-element-d349891 .elementor-image-box-title,
        .pt-cats-heading {
            font-size: 28px !important;
        }
        .elementor-element-d349891 .elementor-image-box-description {
            min-height: 0;
        }
        .elementor-element-28fc7dc::before {
            left: 16px;
            right: 16px;
            border-radius: 22px;
        }
        .elementor-element-28fc7dc .elementor-heading-title {
            font-size: 28px !important;
        }
        .elementor-element-28fc7dc .elementor-widget-container {
            padding: 44px 20px !important;
        }
        .elementor-element-ea9e0d9 .elementor-image-box-title {
            font-size: 22px !important;
        }
    }

    @media (max-width: 544px) {
        .elementor-element-d349891 {
            padding: 44px 0 !important;
        }
        .pt-cats-heading {
            font-size: 24px !important;
        }
        .pt-cats-subtitle {
            font-size: 14px !important;
        }
        .elementor-element-28fc7dc .elementor-heading-title {
            font-size: 23px !important;
        }
        .elementor-element-ea9e0d9 .elementor-testimonial-wrapper {
            padding: 28px 24px;
        }
    }

    /* ==========================================================
     * 27. TESTIMONIALS HEADING BLOCK (injected via JS)
     *
     * Matches the eyebrow + heading + subtitle treatment used by
     * Best Sellers and Category Cards so the section hierarchy
     * on the page feels consistent.
     * ========================================================== */

    .pt-testimonials-header {
        flex: 0 0 100% !important;
        width: 100% !important;
        text-align: center;
        padding: 0 20px;
        margin: 0 auto 16px;
        box-sizing: border-box;
    }

    .pt-testimonials-eyebrow {
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
    }

    .pt-testimonials-eyebrow::before,
    .pt-testimonials-eyebrow::after {
        content: '';
        width: 28px;
        height: 1.5px;
        background: var(--ast-global-color-1, #6a9739);
        opacity: 0.5;
        border-radius: 2px;
    }

    .pt-testimonials-heading {
        font-family: inherit !important;
        font-size: 40px;
        font-weight: 800;
        color: #1a1a1a;
        text-align: center;
        margin: 0 auto 14px;
        letter-spacing: -0.5px;
        line-height: 1.1;
    }

    .pt-testimonials-subtitle {
        font-size: 16px;
        color: #666;
        text-align: center;
        margin: 0 auto;
        max-width: 540px;
        line-height: 1.55;
    }

    /* Hide the default small "What Dog Owners Say" heading the
     * template ships with — our injected block replaces it */
    .elementor-element-ea9e0d9 .elementor-widget-heading:has(.elementor-heading-title) {
        display: none !important;
    }

    /* Breathing room between the testimonials grid and the Buy 2
     * Get 1 bundle card below it — they were feeling jammed */
    .elementor-element-ea9e0d9 .elementor-widget-image-box {
        margin-top: 72px !important;
    }

    @media (max-width: 921px) {
        .pt-testimonials-heading { font-size: 32px !important; }
        .elementor-element-ea9e0d9 .elementor-widget-image-box { margin-top: 56px !important; }
    }

    @media (max-width: 544px) {
        .pt-testimonials-heading { font-size: 26px !important; }
        .pt-testimonials-subtitle { font-size: 14px !important; }
    }

    /* ==========================================================
     * 28. FOOTER NEWSLETTER (injected via JS)
     *
     * Sits at the top of the footer as a proper card (cream-green
     * tinted panel with a decorative leaf). Left: text hook.
     * Right: email input + Subscribe button + wrapping status line.
     * ========================================================== */

    .pt-footer-newsletter {
        max-width: 1200px;
        margin: 0 auto 40px;
        padding: 40px 48px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 32px;
        background: linear-gradient(135deg, rgba(139,195,74,0.08) 0%, rgba(139,195,74,0.02) 100%);
        border: 1px solid rgba(139, 195, 74, 0.18);
        border-radius: 24px;
        position: relative;
        overflow: hidden;
    }

    /* Decorative bloom in the corner */
    .pt-footer-newsletter::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -80px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(139, 195, 74, 0.22), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }

    .pt-footer-newsletter > * {
        position: relative;
        z-index: 1;
    }

    .pt-footer-newsletter-text {
        flex: 1 1 320px;
        min-width: 0;
    }

    .pt-footer-newsletter-eyebrow {
        display: inline-block;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: var(--ast-global-color-1, #6a9739);
        background: rgba(139, 195, 74, 0.14);
        padding: 4px 12px;
        border-radius: 100px;
        margin-bottom: 12px;
    }

    .pt-footer-newsletter-title {
        font-family: inherit !important;
        font-size: 26px;
        font-weight: 800;
        color: #1a1a1a;
        line-height: 1.2;
        letter-spacing: -0.4px;
        margin: 0;
    }

    .pt-footer-newsletter-title small {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #5a5a5a;
        margin-top: 6px;
        letter-spacing: 0;
        line-height: 1.5;
    }

    /* Form container is its own flex row so we can wrap the status
     * line to a second row cleanly */
    .pt-footer-newsletter-form {
        flex: 1 1 360px;
        max-width: 520px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .pt-footer-newsletter-input {
        flex: 1 1 220px;
        min-width: 0;
        padding: 14px 20px;
        border: 1px solid #e2e2e2;
        border-radius: 100px;
        background: #ffffff;
        font-size: 14px;
        font-family: inherit;
        color: #1a1a1a;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .pt-footer-newsletter-input::placeholder {
        color: #a8a8a8;
    }

    .pt-footer-newsletter-input:focus {
        outline: none;
        border-color: var(--ast-global-color-1, #6a9739);
        box-shadow: 0 0 0 3px rgba(106, 151, 57, 0.14);
    }

    .pt-footer-newsletter-btn {
        flex: 0 0 auto;
        padding: 14px 30px;
        background: #1a1a1a;
        color: #ffffff;
        border: none;
        border-radius: 100px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        cursor: pointer;
        white-space: nowrap;
        transition: background-color 0.25s ease, transform 0.25s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.25s ease;
    }

    .pt-footer-newsletter-btn:hover {
        background: var(--ast-global-color-1, #6a9739);
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(106, 151, 57, 0.22);
    }

    /* Status line is its own 100% row — wraps below input + button.
     * Zero height until populated so it doesn't leave an awkward
     * empty gap in the layout by default. */
    .pt-footer-newsletter-status {
        flex: 0 0 100%;
        font-size: 13px;
        font-weight: 500;
        color: var(--ast-global-color-1, #6a9739);
        margin: 0;
        min-height: 0;
        padding: 0 6px;
    }

    .pt-footer-newsletter-status:empty {
        display: none;
    }

    /* Certification / trust row below the newsletter */
    .pt-footer-trust {
        max-width: 1200px;
        margin: 0 auto 32px;
        padding: 0 24px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 16px 40px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.4px;
        color: #555;
    }

    .pt-footer-trust-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .pt-footer-trust-item svg {
        width: 16px;
        height: 16px;
        color: var(--ast-global-color-1, #6a9739);
        flex-shrink: 0;
    }

    @media (max-width: 921px) {
        .pt-footer-newsletter {
            padding: 32px 32px;
            gap: 24px;
        }
        .pt-footer-newsletter-title { font-size: 22px; }
    }

    @media (max-width: 544px) {
        .pt-footer-newsletter {
            padding: 28px 20px;
            gap: 20px;
            border-radius: 20px;
        }
        .pt-footer-newsletter::before {
            top: -40px;
            right: -40px;
            width: 140px;
            height: 140px;
        }
        .pt-footer-newsletter-title { font-size: 20px; }
        .pt-footer-newsletter-title small { font-size: 13px; }
        .pt-footer-newsletter-input,
        .pt-footer-newsletter-btn {
            flex: 1 1 100%;
        }
        .pt-footer-newsletter-btn { padding: 14px 24px; }
        .pt-footer-trust {
            gap: 12px 24px;
            font-size: 11px;
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

    /* ==========================================================
     * 28a. OUR PRODUCTS section + Best Sellers hide
     *
     * Injected via JS at runtime (see section P in the JS block).
     * 3-card block linking to real WooCommerce products:
     *   /product/sensitive-skin/  ($45)
     *   /product/deep-clean/      ($36)
     *   /product/puppy-collection/ ($57)
     * The original "Best Sellers" [products] shortcode block is
     * tagged by JS and hidden here.
     * ========================================================== */
    .pt-hide-best-sellers {
        display: none !important;
    }

    .pt-our-products {
        max-width: 1200px;
        margin: 0 auto;
        /* Top padding is intentionally smaller than bottom because the
         * features bar (floating card, margin-top:-40px) already eats
         * ~40px of implicit spacing above. 56/80 produces a balanced
         * "one rhythm unit" gap on both sides of the Our Products block.
         * See section 31 for the site-wide vertical rhythm scale. */
        padding: 56px 24px 80px;
        /* Homepage content container is flex-column with CSS `order`
         * on every section (see pethoven-content.php). Sit where Best
         * Sellers used to be. */
        order: 3 !important;
        width: 100%;
    }
    .pt-our-products-head {
        text-align: center;
        margin-bottom: 40px;
    }
    .pt-our-products-eyebrow {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ast-global-color-1, #6a9739);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 14px;
    }
    .pt-our-products-eyebrow::before,
    .pt-our-products-eyebrow::after {
        content: '';
        width: 28px;
        height: 1.5px;
        background: var(--ast-global-color-1, #6a9739);
        opacity: 0.5;
        border-radius: 2px;
    }
    .pt-our-products-heading {
        font-family: inherit;
        font-size: 38px;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0 0 14px;
        letter-spacing: -0.5px;
        line-height: 1.15;
    }
    .pt-our-products-subtitle {
        font-size: 16px;
        color: #666;
        max-width: 540px;
        line-height: 1.55;
        margin: 0 auto;
    }
    .pt-our-products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }
    .pt-product-card {
        background: #ffffff;
        border: 1px solid #f0f0ec;
        border-radius: 24px;
        padding: 28px;
        display: flex;
        flex-direction: column;
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1),
                    border-color 0.35s ease,
                    box-shadow 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        position: relative;
        overflow: hidden;
    }
    .pt-product-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--ast-global-color-0, #8bc34a), var(--ast-global-color-1, #6a9739));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .pt-product-card:hover {
        transform: translateY(-6px);
        border-color: rgba(139, 195, 74, 0.32);
        box-shadow: 0 20px 42px rgba(106, 151, 57, 0.1),
                    0 6px 16px rgba(0, 0, 0, 0.04);
    }
    .pt-product-card:hover::before { transform: scaleX(1); }

    .pt-product-image {
        aspect-ratio: 1;
        background: linear-gradient(135deg, rgba(139,195,74,0.14) 0%, rgba(106,151,57,0.04) 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 22px;
        overflow: hidden;
    }
    .pt-product-image svg {
        width: 56px;
        height: 56px;
        color: var(--ast-global-color-1, #6a9739);
    }
    .pt-product-card:nth-child(2) .pt-product-image {
        background: linear-gradient(135deg, rgba(38,84,61,0.14) 0%, rgba(26,58,42,0.04) 100%);
    }
    .pt-product-card:nth-child(2) .pt-product-image svg { color: #26543d; }
    .pt-product-card:nth-child(3) .pt-product-image {
        background: linear-gradient(135deg, rgba(245,183,120,0.22) 0%, rgba(255,216,168,0.06) 100%);
    }
    .pt-product-card:nth-child(3) .pt-product-image svg { color: #c87d3a; }

    .pt-product-name {
        font-size: 22px;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0 0 10px;
        letter-spacing: -0.3px;
    }
    .pt-product-desc {
        font-size: 14.5px;
        color: #666;
        line-height: 1.55;
        margin: 0 0 18px;
        flex-grow: 1;
    }
    .pt-product-price {
        font-size: 20px;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0 0 18px;
    }
    .pt-product-price small {
        font-size: 13px;
        font-weight: 500;
        color: #8a8a8a;
        margin-left: 6px;
    }
    .pt-product-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 22px;
        background: #1a1a1a;
        color: #ffffff !important;
        border: none;
        border-radius: 100px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        text-decoration: none !important;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        align-self: flex-start;
    }
    .pt-product-cta:hover {
        background: var(--ast-global-color-1, #6a9739);
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(106, 151, 57, 0.28);
    }

    @media (max-width: 921px) {
        .pt-our-products-grid { grid-template-columns: 1fr; gap: 20px; }
        .pt-our-products-heading { font-size: 28px; }
        .pt-our-products { padding: 32px 20px 48px; }
    }

    /* ==========================================================
     * 28b. HERO — tighter vertical padding
     *
     * Elementor's per-post CSS defined padding-top/bottom: 120px
     * via the --padding-* CSS vars. Reduce to 90px desktop, 56px
     * mobile so the next section shows sooner but the hero still
     * has breathing room.
     * ========================================================== */
    .elementor-95 .elementor-element.elementor-element-3849851,
    .elementor-element-3849851 {
        --padding-top: 90px !important;
        --padding-bottom: 90px !important;
        padding-top: 90px !important;
        padding-bottom: 90px !important;
    }
    @media (max-width: 921px) {
        .elementor-95 .elementor-element.elementor-element-3849851,
        .elementor-element-3849851 {
            --padding-top: 56px !important;
            --padding-bottom: 56px !important;
            padding-top: 56px !important;
            padding-bottom: 56px !important;
        }
    }

    /* ==========================================================
     * 28c. HIDE "Find your formula" section
     *
     * Per 2026-04-24 product direction: not shipping the 3-card
     * category section for now. Hide the Elementor container.
     * ========================================================== */
    .elementor-95 .elementor-element.elementor-element-d349891,
    .elementor-element-d349891 {
        display: none !important;
    }

    /* ==========================================================
     * 29. SHOP ARCHIVE — hide left sidebar, full-width grid
     *
     * Scoped to body.post-type-archive-product only so individual
     * product detail pages (/product/xyz/) keep their sidebar if
     * they have one. Uses Astra's existing ast-left-sidebar body
     * class to reverse layout geometry cleanly.
     * ========================================================== */
    body.post-type-archive-product #secondary,
    body.woocommerce-shop #secondary {
        display: none !important;
    }
    body.post-type-archive-product #primary,
    body.woocommerce-shop #primary {
        width: 100% !important;
        max-width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        float: none !important;
        /* Astra's ast-left-sidebar layout paints a 1px border-left on
         * #primary (and right for ast-right-sidebar). With the sidebar
         * hidden, that border renders as an orphan vertical line down
         * the whole page — visible as the thin grey stripe on the left
         * of /shop/. Kill the full border so neither layout shows it. */
        border: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    /* Astra's left-sidebar layout reserves a margin on #primary we
     * need to unset as well, otherwise the grid stays offset. */
    body.post-type-archive-product.ast-left-sidebar #primary,
    body.post-type-archive-product.ast-right-sidebar #primary,
    body.woocommerce-shop.ast-left-sidebar #primary,
    body.woocommerce-shop.ast-right-sidebar #primary {
        margin: 0 auto !important;
    }

    /* ==========================================================
     * 29b. SHOP ARCHIVE — header, noise hide, centered grid
     *
     * With only 3 real products, WooCommerce's default affordances
     * (result count, sort dropdown, pagination) are noise. Hide
     * them. Make the page title a proper brand header with eyebrow
     * and subtitle. Center the 3-card grid so it doesn't sit flush
     * left with a giant empty right side.
     * ========================================================== */

    /* Branded page title */
    body.post-type-archive-product .woocommerce-products-header__title,
    body.woocommerce-shop .woocommerce-products-header__title {
        font-family: inherit !important;
        font-size: 44px !important;
        font-weight: 800 !important;
        color: #1a1a1a !important;
        text-align: center !important;
        margin: 0 auto 16px !important;
        letter-spacing: -0.6px !important;
        line-height: 1.1 !important;
    }
    /* Eyebrow + subtitle injected via JS (see section "S" in JS block) */
    .pt-shop-eyebrow {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ast-global-color-1, #6a9739);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 14px;
    }
    .pt-shop-eyebrow::before,
    .pt-shop-eyebrow::after {
        content: '';
        width: 28px;
        height: 1.5px;
        background: var(--ast-global-color-1, #6a9739);
        opacity: 0.5;
        border-radius: 2px;
    }
    .pt-shop-subtitle {
        text-align: center;
        font-size: 16px;
        line-height: 1.55;
        color: #666;
        max-width: 540px;
        margin: 0 auto 48px;
    }

    /* Products header container — add room above */
    body.post-type-archive-product .woocommerce-products-header,
    body.woocommerce-shop .woocommerce-products-header {
        text-align: center !important;
        padding: 48px 20px 0 !important;
        margin: 0 auto !important;
        max-width: 1200px !important;
    }

    /* Hide the noise: result count + sort + pagination when <= 3 products */
    body.post-type-archive-product .woocommerce-result-count,
    body.post-type-archive-product .woocommerce-ordering,
    body.post-type-archive-product .woocommerce-pagination,
    body.woocommerce-shop .woocommerce-result-count,
    body.woocommerce-shop .woocommerce-ordering,
    body.woocommerce-shop .woocommerce-pagination {
        display: none !important;
    }
    body.post-type-archive-product .woocommerce-notices-wrapper:empty,
    body.woocommerce-shop .woocommerce-notices-wrapper:empty {
        display: none !important;
    }

    /* Center the products grid — with 3 items, give each ~340px */
    body.post-type-archive-product ul.products,
    body.woocommerce-shop ul.products {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 340px)) !important;
        justify-content: center !important;
        gap: 28px !important;
        max-width: 1120px !important;
        margin: 0 auto 56px !important;
        padding: 0 20px !important;
        float: none !important;
        list-style: none !important;
    }
    body.post-type-archive-product ul.products li.product,
    body.woocommerce-shop ul.products li.product {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        float: none !important;
    }

    /* Mobile: single column */
    @media (max-width: 768px) {
        body.post-type-archive-product .woocommerce-products-header__title,
        body.woocommerce-shop .woocommerce-products-header__title {
            font-size: 30px !important;
        }
        body.post-type-archive-product .woocommerce-products-header,
        body.woocommerce-shop .woocommerce-products-header {
            padding: 32px 16px 0 !important;
        }
        .pt-shop-subtitle {
            font-size: 14.5px;
            margin-bottom: 32px;
        }
        body.post-type-archive-product ul.products,
        body.woocommerce-shop ul.products {
            grid-template-columns: 1fr !important;
            max-width: 440px !important;
            gap: 20px !important;
        }
    }

    /* ==========================================================
     * 30b. TESTIMONIALS — custom rebuild
     *
     * The default Astra/Elementor testimonial layout had four real
     * problems:
     *   1. Bundle promo widget sat in the middle column of a
     *      testimonials grid (breaks the "trust" narrative; users
     *      expect reviews there)
     *   2. Star rating was a separate widget sibling to the
     *      testimonial — visually floated, disconnected from the
     *      card below it
     *   3. Quote text lived inside a second nested card (the
     *      wrapper I styled + the inner testimonial-wrapper), so
     *      users saw "card-in-card" which read as broken styling
     *   4. Avatar URLs pointed to uploads that no longer exist —
     *      rendering alt text like "client02 free img"
     *
     * Solution: hide everything the section originally rendered
     * (except my injected heading) and build a proper 2-card grid
     * from scratch via JS. Cards use initials-in-circle avatars
     * (which can't 404), a single flat card, clean typography.
     * ========================================================== */

    /* Hide legacy Elementor children inside the testimonials section.
     * .pt-testimonials-header (injected) and .pt-testimonials-grid
     * (injected) stay visible. */
    .elementor-element-ea9e0d9 > .e-con-inner > .e-con.e-child,
    .elementor-element-ea9e0d9 > .e-con-inner > .elementor-widget,
    .elementor-element-ea9e0d9 > .e-con-inner > .elementor-element:not(.pt-testimonials-header):not(.pt-testimonials-grid) {
        display: none !important;
    }

    /* Section container — make it a clean centered flex column */
    .elementor-element-ea9e0d9 > .e-con-inner {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        max-width: 1040px !important;
        margin: 0 auto !important;
        width: 100% !important;
        padding: 0 24px !important;
    }

    .pt-testimonials-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 440px));
        gap: 28px;
        width: 100%;
        justify-content: center;
        margin: 0 auto;
    }

    .pt-testimonial-card {
        background: #ffffff;
        border: 1px solid #f0f0ec;
        border-radius: 22px;
        padding: 34px 30px 28px;
        position: relative;
        display: flex;
        flex-direction: column;
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                    border-color 0.3s ease,
                    box-shadow 0.35s ease;
    }
    .pt-testimonial-card::before {
        content: "\201C";
        position: absolute;
        top: 16px;
        right: 22px;
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 68px;
        color: rgba(139, 195, 74, 0.22);
        line-height: 1;
        pointer-events: none;
    }
    .pt-testimonial-card:hover {
        transform: translateY(-4px);
        border-color: rgba(139, 195, 74, 0.32);
        box-shadow: 0 20px 40px rgba(106, 151, 57, 0.08),
                    0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .pt-testimonial-stars {
        color: #f5a623;
        font-size: 15px;
        letter-spacing: 3px;
        line-height: 1;
        margin-bottom: 16px;
        font-family: Georgia, 'Times New Roman', serif;
    }

    .pt-testimonial-quote {
        font-size: 15.5px;
        line-height: 1.65;
        color: #2a2a2a;
        margin: 0 0 24px;
        font-family: inherit;
        font-style: normal;
        font-weight: 400;
        flex-grow: 1;
        border: none;
        padding: 0;
        quotes: none;
    }
    .pt-testimonial-quote::before,
    .pt-testimonial-quote::after { content: none; }

    .pt-testimonial-author {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-top: 18px;
        border-top: 1px solid #f0f0ec;
    }

    .pt-testimonial-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 17px;
        flex-shrink: 0;
    }
    .pt-testimonial-avatar--green {
        background: linear-gradient(135deg, rgba(139,195,74,0.26), rgba(106,151,57,0.08));
        color: #6a9739;
    }
    .pt-testimonial-avatar--peach {
        background: linear-gradient(135deg, rgba(245,183,120,0.32), rgba(255,216,168,0.1));
        color: #c87d3a;
    }

    .pt-testimonial-meta {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }
    .pt-testimonial-name {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a1a;
        line-height: 1.3;
    }
    .pt-testimonial-role {
        font-size: 12px;
        color: #8a8a8a;
        line-height: 1.3;
    }

    @media (max-width: 768px) {
        .pt-testimonials-grid {
            grid-template-columns: 1fr;
            max-width: 440px;
            gap: 18px;
        }
        .pt-testimonial-card {
            padding: 28px 24px 22px;
        }
        .pt-testimonial-card::before {
            font-size: 56px;
            top: 12px;
            right: 18px;
        }
    }

    /* ==========================================================
     * 30c. MOBILE POLISH — homepage
     *
     * Targeted fixes for phone-sized screens (≤768px). Focus areas:
     *  - Hero H1 was scaling to ~40px+ on mobile (Astra + Elementor
     *    defaults); cap at a readable 30px
     *  - Hero description + button sized for thumb-readability
     *  - Product cards: comfortable internal padding, real touch
     *    targets (min 44px) on the "Shop Now" CTA
     *  - Section headings pared to ~26-28px so they don't dominate
     *  - 20% off promo heading scales down from 38px → 24px
     *  - Brand-logo row spacing tightened to prevent wrap chaos
     *  - Footer: explicit stack + centered alignment, less cramped
     * ========================================================== */

    @media (max-width: 768px) {
        /* -------- HERO -------- */
        .elementor-element-3849851 .hfe-infocard-title,
        .elementor-element-3849851 h1,
        .elementor-element-3849851 .elementor-heading-title {
            font-size: 30px !important;
            line-height: 1.18 !important;
            letter-spacing: -0.4px !important;
        }
        .elementor-element-3849851 .hfe-infocard-text,
        .elementor-element-3849851 p {
            font-size: 15px !important;
            line-height: 1.6 !important;
        }
        .elementor-element-3849851 .elementor-button {
            font-size: 12px !important;
            padding: 14px 28px !important;
            min-height: 46px;
        }
        /* Hero eyebrow pill already OK from section 26 */

        /* -------- OUR PRODUCTS -------- */
        .pt-our-products-heading {
            font-size: 26px !important;
            line-height: 1.2 !important;
        }
        .pt-our-products-subtitle { font-size: 14.5px !important; }
        .pt-product-card { padding: 24px 22px !important; }
        .pt-product-image { margin-bottom: 18px !important; }
        .pt-product-name { font-size: 20px !important; }
        .pt-product-desc { font-size: 14px !important; }
        .pt-product-price { font-size: 18px !important; }
        .pt-product-cta {
            padding: 14px 24px !important;
            min-height: 46px;
            align-self: stretch !important;  /* full-width CTA on phones */
            text-align: center;
        }

        /* -------- 20% OFF PROMO -------- */
        .elementor-element-28fc7dc .elementor-heading-title,
        .elementor-element-28fc7dc h1,
        .elementor-element-28fc7dc h2 {
            font-size: 24px !important;
            line-height: 1.2 !important;
        }
        .elementor-element-28fc7dc .elementor-button {
            font-size: 12px !important;
            padding: 14px 24px !important;
            min-height: 46px !important;
        }

        /* -------- TESTIMONIALS -------- */
        .pt-testimonials-heading { font-size: 26px !important; line-height: 1.2 !important; }
        .pt-testimonials-subtitle { font-size: 14px !important; }
        .pt-testimonial-quote { font-size: 15px !important; }

        /* -------- QUIZ -------- */
        .elementor-element-778c9e4 .elementor-heading-title,
        .elementor-element-778c9e4 h3 {
            font-size: 22px !important;
            line-height: 1.25 !important;
        }
        .elementor-element-778c9e4 .elementor-button {
            padding: 14px 24px !important;
            min-height: 46px !important;
        }

        /* -------- BRAND LOGOS -------- */
        .elementor-element-357f4cd .elementor-widget-image {
            flex: 1 1 33% !important;
            max-width: 33% !important;
            padding: 4px 10px !important;
        }
        .elementor-element-357f4cd .elementor-widget-image img {
            max-width: 100% !important;
            height: auto !important;
        }

        /* -------- FOOTER — hide primary footer block on mobile --------
         *
         * Designer call (2026-04-24): the entire primary footer wrap
         * (logo + tagline + contact rows + Website/Site Links
         * columns) is removed on phones. It was eating ~5 screens of
         * scroll for content the user can reach from the header nav
         * or the Contact page.
         *
         * Kept on mobile: the newsletter card (.pt-footer-newsletter,
         * inserted before the wrap) and the copyright bar
         * (.pt-footer-copybar, appended after the wrap) — both are
         * siblings of .site-primary-footer-wrap, not children, so
         * hiding the wrap doesn't affect them.
         *
         * Earlier attempts at a "compact mobile layout" lived here;
         * removed in favour of this simpler hide rule.
         */
        .site-footer .site-primary-footer-wrap {
            display: none !important;
        }

        /* Newsletter card (above the footer grid): lighter padding */
        .pt-footer-newsletter { padding: 24px 18px !important; text-align: center !important; margin-bottom: 24px !important; }
        .pt-footer-newsletter-title { text-align: center !important; font-size: 19px !important; }
        .pt-footer-newsletter-title small { font-size: 12px !important; }
        .pt-footer-newsletter-form { flex-direction: column !important; gap: 10px !important; }
        .pt-footer-newsletter-input,
        .pt-footer-newsletter-btn { width: 100% !important; min-height: 46px !important; }

        /* Copyright bar: tighter + stacked */
        .pt-footer-copybar {
            padding: 16px 16px 22px !important;
            gap: 10px 14px !important;
            font-size: 12px !important;
        }
        .pt-footer-copybar-left,
        .pt-footer-copybar-right {
            justify-content: center !important;
            gap: 4px 12px !important;
        }

        /* Announcement bar — smaller font + trimmed padding */
        .pt-announcement-bar { font-size: 11.5px !important; }
        .pt-announcement-inner { padding: 8px 20px !important; }

        /* -------- Generic: kill any horizontal overflow -------- */
        body { overflow-x: hidden !important; }
    }

    @media (max-width: 480px) {
        /* Very small phones — slightly tighter again */
        .elementor-element-3849851 .hfe-infocard-title,
        .elementor-element-3849851 h1 { font-size: 26px !important; }
        .pt-our-products-heading,
        .pt-testimonials-heading { font-size: 24px !important; }
        .pt-testimonial-card { padding: 22px 20px 18px !important; }
    }

    /* ==========================================================
     * 31. HOMEPAGE VERTICAL RHYTHM — consolidated scale
     *
     * Every visible section below the hero uses ONE rhythm unit —
     * 80px vertical padding on desktop, 48px on mobile. Goal: the
     * page reads as evenly-spaced rather than a jumble of per-
     * section Elementor defaults (which ranged from 40px to 120px).
     *
     * Keep specific exceptions documented:
     *   - Hero keeps its own 90/90 (handled in section 28b above)
     *   - Features bar keeps 35/30 + negative margin-top (floating
     *     card lifting over the hero boundary)
     *   - Our Products uses 56 top / 80 bottom because the features
     *     bar above already absorbs ~40px of implicit gap
     * ========================================================== */

    /* Testimonials section — match rhythm (was 80/80 → overridden
     * to 56/56 earlier; restoring to rhythm 80/80 desktop. Internal
     * heading→cards gap stays tight via .pt-testimonials-header rule) */
    .elementor-95 .elementor-element.elementor-element-ea9e0d9,
    .elementor-element-ea9e0d9 {
        padding-top: 80px !important;
        padding-bottom: 80px !important;
    }

    /* 20% off promo — bump 60/60 → 80/80 */
    .elementor-95 .elementor-element.elementor-element-28fc7dc,
    .elementor-element-28fc7dc {
        padding-top: 80px !important;
        padding-bottom: 80px !important;
    }

    /* Quiz CTA — was 0/0, give it breathing room 40/40 */
    .elementor-95 .elementor-element.elementor-element-778c9e4,
    .elementor-element-778c9e4 {
        padding-top: 40px !important;
        padding-bottom: 40px !important;
    }

    /* Brand logos — was 0 top / 40 bottom, balance to 60/60 */
    .elementor-95 .elementor-element.elementor-element-357f4cd,
    .elementor-element-357f4cd {
        padding-top: 60px !important;
        padding-bottom: 60px !important;
    }

    /* Mobile rhythm — half the desktop scale so sections don't
     * feel oversized on phones */
    @media (max-width: 768px) {
        .elementor-95 .elementor-element.elementor-element-ea9e0d9,
        .elementor-element-ea9e0d9,
        .elementor-95 .elementor-element.elementor-element-28fc7dc,
        .elementor-element-28fc7dc {
            padding-top: 48px !important;
            padding-bottom: 48px !important;
        }
        .elementor-95 .elementor-element.elementor-element-778c9e4,
        .elementor-element-778c9e4 {
            padding-top: 24px !important;
            padding-bottom: 24px !important;
        }
        .elementor-95 .elementor-element.elementor-element-357f4cd,
        .elementor-element-357f4cd {
            padding-top: 40px !important;
            padding-bottom: 40px !important;
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

    <script id="pethoven-broken-img-js">
    /* ----------------------------------------------------------
     * Hide broken images everywhere.
     *
     * The wp-content/uploads/ directory is currently empty (not yet
     * restored from Hostinger backup). Every reference to a demo
     * image file (logo-leaf-new.png, product photos, etc.) 404s
     * and the browser renders a broken-image icon next to its alt
     * text — e.g. "logo leaf new", "client01 free img".
     *
     * Strategy: hide any <img> whose naturalWidth is 0 after load.
     * Handles three cases:
     *   1. Already-loaded broken images (immediate scan)
     *   2. Images still loading on DOM ready (onerror listener)
     *   3. Images injected later by other scripts (MutationObserver)
     *
     * When the uploads dir is eventually restored from the backup,
     * this code is a harmless no-op — it only acts when an image
     * actually fails to load.
     * ---------------------------------------------------------- */
    (function () {
        'use strict';

        var handle = function (img) {
            if (img.dataset.ptBrokenHandled === '1') return;
            if (!img.getAttribute('src')) { img.style.display = 'none'; return; }

            if (img.complete) {
                if (img.naturalWidth === 0) {
                    img.dataset.ptBrokenHandled = '1';
                    img.style.display = 'none';
                    // Also hide the wrapping <a> if it only contains this
                    // image (common for linked logos — otherwise you see
                    // an empty clickable gap where the image used to be).
                    var anchor = img.parentElement;
                    if (anchor && anchor.tagName === 'A' &&
                        anchor.childElementCount === 1 &&
                        !anchor.textContent.trim()) {
                        anchor.style.display = 'none';
                    }
                }
            } else {
                img.addEventListener('error', function () {
                    img.dataset.ptBrokenHandled = '1';
                    img.style.display = 'none';
                    var a = img.parentElement;
                    if (a && a.tagName === 'A' && a.childElementCount === 1 && !a.textContent.trim()) {
                        a.style.display = 'none';
                    }
                }, { once: true });
            }
        };

        var scan = function (root) {
            (root || document).querySelectorAll('img').forEach(handle);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () { scan(); });
        } else {
            scan();
        }

        // Watch for late-injected images (our own JS injections, lazy loaders, etc.)
        if ('MutationObserver' in window) {
            var mo = new MutationObserver(function (mutations) {
                mutations.forEach(function (m) {
                    m.addedNodes.forEach(function (node) {
                        if (node.nodeType !== 1) return;
                        if (node.tagName === 'IMG') handle(node);
                        else if (node.querySelectorAll) scan(node);
                    });
                });
            });
            if (document.body) mo.observe(document.body, { childList: true, subtree: true });
            else document.addEventListener('DOMContentLoaded', function () {
                mo.observe(document.body, { childList: true, subtree: true });
            });
        }
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
         *    Astra's footer is configured with 4 zones but only 3
         *    of them hold unique content — the 3rd zone is a
         *    "Quick Links" menu whose entries duplicate Website +
         *    Site Links. We hide the entire grid cell (not just the
         *    inner widget) so the footer grid collapses cleanly to
         *    3 visible columns; otherwise the empty zone would
         *    leave a ghost track in the grid and force Site Links
         *    to wrap to a new row.
         * ---------------------------------------------------------- */
        document.querySelectorAll('.site-footer .widget-title, .site-footer h2, .site-footer h3, .site-footer h4').forEach(function (h) {
            var t = (h.textContent || '').trim().toLowerCase();
            if (t === 'quick links') {
                var gridCell = h.closest('[class*="site-footer-primary-section-"], [class*="site-footer-section-"], .ast-footer-widget-1-area, .ast-footer-widget-2-area, .ast-footer-widget-3-area, .ast-footer-widget-4-area');
                (gridCell || h.parentElement || h).style.display = 'none';
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
         * J. Homepage — Category cards section heading
         *    The section has no heading by default. Inject an
         *    eyebrow + headline + subtitle ABOVE the 3-card flex
         *    row (not inside it — otherwise it becomes a 4th column
         *    and squeezes into ~25% width).
         * ---------------------------------------------------------- */
        var catsSection = document.querySelector('.elementor-element-d349891');
        if (catsSection && !catsSection.querySelector('.pt-cats-heading')) {
            // Find the flex row that holds the 3 cards (columns or flex-children)
            var columnsRow = catsSection.querySelector(':scope > .elementor-container, :scope > .e-con-inner');
            // Fallback: any container that holds the columns
            if (!columnsRow) {
                columnsRow = catsSection.querySelector('.elementor-container, .e-con-inner');
            }

            var catsHead = document.createElement('div');
            catsHead.className = 'pt-cats-header';
            catsHead.innerHTML =
                '<div class="pt-cats-eyebrow">Built for the three dogs you know</div>' +
                '<h2 class="pt-cats-heading">Find your formula</h2>' +
                '<p class="pt-cats-subtitle">Three targeted shampoos for the three things dogs need: relief, a deep clean, or a gentler start.</p>';

            if (columnsRow && columnsRow.parentNode) {
                // Insert BEFORE the flex row, as a sibling above it
                columnsRow.parentNode.insertBefore(catsHead, columnsRow);
            } else {
                // Last-resort fallback
                catsSection.insertBefore(catsHead, catsSection.firstChild);
            }
        }

        /* ----------------------------------------------------------
         * J2. Homepage — Category card icons
         *     Each card has a gradient disc (CSS ::before) where the
         *     basil-leaf image used to live. We killed the image,
         *     which left the disc empty. Inject a meaningful SVG
         *     icon into each card so the disc has a subject:
         *       1. Sensitive Skin  → leaf (soothing, natural)
         *       2. Deep Clean      → water droplet
         *       3. Puppy Collection→ paw print
         * ---------------------------------------------------------- */
        if (catsSection) {
            var leafSvg =
                '<svg viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">' +
                    '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19.8 2c1 5 .5 10-2 13.5-1.5 2-5 4.5-6.8 4.5z"/>' +
                    '<path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>' +
                '</svg>';
            var dropSvg =
                '<svg viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">' +
                    '<path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>' +
                    '<path d="M8.5 14c.5 1.5 1.8 2.5 3.5 2.5" stroke-linecap="round"/>' +
                '</svg>';
            var pawSvg =
                '<svg viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">' +
                    '<ellipse cx="6" cy="11" rx="2.2" ry="2.6"/>' +
                    '<ellipse cx="18" cy="11" rx="2.2" ry="2.6"/>' +
                    '<ellipse cx="9.5" cy="6" rx="2" ry="2.4"/>' +
                    '<ellipse cx="14.5" cy="6" rx="2" ry="2.4"/>' +
                    '<path d="M12 13.2c-3.2 0-5.6 2.7-5.6 5.3 0 1.4 1.1 2.5 2.5 2.5 1 0 1.5-.5 3.1-.5s2.1.5 3.1.5c1.4 0 2.5-1.1 2.5-2.5 0-2.6-2.4-5.3-5.6-5.3z"/>' +
                '</svg>';

            var icons = [leafSvg, dropSvg, pawSvg];
            var iconModifier = ['', '', 'pt-cat-icon--filled']; // paw is filled, others are line

            // Find each card column (works for both Elementor legacy columns and flex containers)
            var cards = catsSection.querySelectorAll(
                ':scope > .elementor-container > .elementor-column, ' +
                ':scope > .e-con-inner > .e-con.e-child'
            );

            cards.forEach(function (card, i) {
                if (i >= icons.length) return;
                var wrapper = card.querySelector('.elementor-image-box-wrapper');
                if (!wrapper || wrapper.querySelector('.pt-cat-icon')) return;
                var iconEl = document.createElement('span');
                iconEl.className = 'pt-cat-icon' + (iconModifier[i] ? ' ' + iconModifier[i] : '');
                iconEl.innerHTML = icons[i];
                wrapper.insertBefore(iconEl, wrapper.firstChild);
            });
        }

        /* ----------------------------------------------------------
         * K. Homepage — 20% off promo eyebrow + coupon pill
         *    The heading reads "First Order? Save 20%. Code: CLEANCOAT"
         *    all in one line. Split it visually: inject a separate
         *    eyebrow and coupon code pill, rewrite the heading.
         * ---------------------------------------------------------- */
        var promoSection = document.querySelector('.elementor-element-28fc7dc');
        if (promoSection) {
            var promoHeading = promoSection.querySelector('h1, h2, h3, .elementor-heading-title');
            if (promoHeading && !promoSection.querySelector('.pt-promo-eyebrow')) {
                // Rewrite heading to drop the "Code: CLEANCOAT" suffix
                promoHeading.textContent = 'Your first bottle, 20% off.';

                // Eyebrow goes before the heading's widget wrapper
                var headingWidget = promoHeading.closest('.elementor-widget, .elementor-element') || promoHeading.parentNode;
                var eyebrow = document.createElement('div');
                eyebrow.className = 'pt-promo-eyebrow';
                eyebrow.textContent = 'Welcome offer';
                headingWidget.parentNode.insertBefore(eyebrow, headingWidget);

                // Coupon pill goes after the heading widget
                var coupon = document.createElement('div');
                coupon.innerHTML = '<div class="pt-promo-code"><span class="pt-promo-code-label">Use code</span><span>CLEANCOAT</span></div>';
                headingWidget.parentNode.insertBefore(coupon, headingWidget.nextSibling);
            }
        }

        /* ----------------------------------------------------------
         * L. Homepage — Hero trust micro-strip
         *    Add a short ticked "promises" row under the hero CTA.
         * ---------------------------------------------------------- */
        var heroSection = document.querySelector('.elementor-element-3849851');
        if (heroSection && !heroSection.querySelector('.pt-hero-trust')) {
            var heroButton = heroSection.querySelector('.elementor-widget-button');
            if (heroButton) {
                var trustIcon =
                    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">' +
                        '<polyline points="5 12 10 17 19 7"/>' +
                    '</svg>';
                var items = ['Free shipping $25+', '30-day guarantee', 'Cruelty-free'];
                var trust = document.createElement('div');
                trust.className = 'pt-hero-trust';
                items.forEach(function (t) {
                    var span = document.createElement('span');
                    span.className = 'pt-hero-trust-item';
                    span.innerHTML = trustIcon + '<span>' + t + '</span>';
                    trust.appendChild(span);
                });
                heroButton.parentNode.insertBefore(trust, heroButton.nextSibling);
            }
        }

        /* ----------------------------------------------------------
         * M. Homepage — Testimonials section heading block
         *    Give testimonials a proper eyebrow + H2 + subtitle so
         *    it matches Best Sellers and Category Cards.
         * ---------------------------------------------------------- */
        var testSection = document.querySelector('.elementor-element-ea9e0d9');
        if (testSection && !testSection.querySelector('.pt-testimonials-heading')) {
            var testColumnsRow = testSection.querySelector(':scope > .elementor-container, :scope > .e-con-inner');
            if (!testColumnsRow) {
                testColumnsRow = testSection.querySelector('.elementor-container, .e-con-inner');
            }

            var testHead = document.createElement('div');
            testHead.className = 'pt-testimonials-header';
            testHead.innerHTML =
                '<div class="pt-testimonials-eyebrow">Real reviews from real dogs\' people</div>' +
                '<h2 class="pt-testimonials-heading">What dog owners say</h2>' +
                '<p class="pt-testimonials-subtitle">Thousands of coats cleaned. Here\'s what a few of them thought afterward.</p>';

            if (testColumnsRow && testColumnsRow.parentNode) {
                testColumnsRow.parentNode.insertBefore(testHead, testColumnsRow);
            } else {
                testSection.insertBefore(testHead, testSection.firstChild);
            }
        }

        /* ----------------------------------------------------------
         * N1. Testimonials — custom 2-card grid
         *
         * The default Elementor testimonials block has a bundle-promo
         * column sitting between two testimonial cards (breaks the
         * trust narrative), a second nested card inside each cell, and
         * broken avatar image URLs. Rather than patch the layered
         * Elementor widgets, we inject a clean 2-card grid into the
         * same .e-con-inner container and hide the legacy widgets via
         * CSS (see section 30b).
         * ---------------------------------------------------------- */
        if (testSection && !testSection.querySelector('.pt-testimonials-grid')) {
            var inner = testSection.querySelector(':scope > .e-con-inner') ||
                        testSection.querySelector('.e-con-inner');
            if (inner) {
                var reviews = [
                    {
                        initial: 'S',
                        color: 'green',
                        name: 'Sarah K.',
                        role: 'Golden retriever mom',
                        quote: 'Our golden retriever had flaky skin for months. Two washes with the Sensitive Skin formula and it cleared up completely. Coat is softer than it\'s been in years.'
                    },
                    {
                        initial: 'J',
                        color: 'peach',
                        name: 'James T.',
                        role: 'Husky dad, Colorado',
                        quote: 'I tried five different dog shampoos before Pethoven. This is the only one that actually removes that wet dog smell and keeps his coat shiny for days.'
                    }
                ];

                var grid = document.createElement('div');
                grid.className = 'pt-testimonials-grid';
                grid.innerHTML = reviews.map(function (r) {
                    return '<article class="pt-testimonial-card">' +
                        '<div class="pt-testimonial-stars" aria-label="5 out of 5 stars">' +
                          '&#9733;&#9733;&#9733;&#9733;&#9733;' +
                        '</div>' +
                        '<p class="pt-testimonial-quote">' + r.quote + '</p>' +
                        '<div class="pt-testimonial-author">' +
                            '<div class="pt-testimonial-avatar pt-testimonial-avatar--' + r.color + '" aria-hidden="true">' + r.initial + '</div>' +
                            '<div class="pt-testimonial-meta">' +
                                '<div class="pt-testimonial-name">' + r.name + '</div>' +
                                '<div class="pt-testimonial-role">' + r.role + '</div>' +
                            '</div>' +
                        '</div>' +
                    '</article>';
                }).join('');

                inner.appendChild(grid);
            }
        }

        /* ----------------------------------------------------------
         * N. Footer — newsletter signup + trust row
         *    Prepends a simple email capture and a row of
         *    certification claims to the top of the footer. Submit
         *    is a no-op that shows a thank-you message; wire up to
         *    a real ESP later.
         * ---------------------------------------------------------- */
        var footer = document.querySelector('.site-footer');
        if (footer && !footer.querySelector('.pt-footer-newsletter')) {
            var newsletter = document.createElement('div');
            newsletter.className = 'pt-footer-newsletter';
            newsletter.innerHTML =
                '<div class="pt-footer-newsletter-text">' +
                    '<div class="pt-footer-newsletter-eyebrow">Join the pack</div>' +
                    '<h3 class="pt-footer-newsletter-title">Get 10% off your first order' +
                        '<small>Plus dog-care tips and early access to new formulas.</small>' +
                    '</h3>' +
                '</div>' +
                '<form class="pt-footer-newsletter-form" novalidate>' +
                    '<input type="email" class="pt-footer-newsletter-input" placeholder="your@email.com" aria-label="Email address" required>' +
                    '<button type="submit" class="pt-footer-newsletter-btn">Subscribe</button>' +
                    '<p class="pt-footer-newsletter-status" role="status" aria-live="polite"></p>' +
                '</form>';

            footer.insertBefore(newsletter, footer.firstChild);

            var form = newsletter.querySelector('form');
            var submitBtn = form.querySelector('.pt-footer-newsletter-btn');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var input = form.querySelector('.pt-footer-newsletter-input');
                var status = form.querySelector('.pt-footer-newsletter-status');
                var email = (input.value || '').trim();
                if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    status.textContent = 'Please enter a valid email address.';
                    status.style.color = '#c23b3b';
                    return;
                }

                // Disable + show "Sending…" state
                if (submitBtn) { submitBtn.disabled = true; submitBtn.dataset.origText = submitBtn.textContent; submitBtn.textContent = 'Sending…'; }
                status.textContent = '';

                // POST to our subscribe endpoint — see wp-content/mu-plugins/pethoven-subscribe.php
                fetch('/wp-json/pethoven/v1/subscribe', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email })
                }).then(function (r) {
                    return r.json().then(function (data) { return { ok: r.ok, data: data }; });
                }).then(function (res) {
                    if (res.ok && res.data && res.data.ok) {
                        status.textContent = res.data.message || "You're in. Check your inbox for your 10% off code.";
                        status.style.color = 'var(--ast-global-color-1, #6a9739)';
                        input.value = '';
                    } else {
                        status.textContent = (res.data && res.data.error) ? res.data.error : 'Something went wrong. Please try again.';
                        status.style.color = '#c23b3b';
                    }
                }).catch(function () {
                    status.textContent = 'Network error. Please try again.';
                    status.style.color = '#c23b3b';
                }).finally(function () {
                    if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = submitBtn.dataset.origText || 'Subscribe'; }
                });
            });

            // Trust row under the newsletter
            var trust = document.createElement('div');
            trust.className = 'pt-footer-trust';
            var checkIcon =
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">' +
                    '<polyline points="5 12 10 17 19 7"/>' +
                '</svg>';
            ['Made in Estonia', 'Sulfate & paraben free', 'Cruelty-free', 'Backed by vets']
                .forEach(function (label) {
                    var item = document.createElement('span');
                    item.className = 'pt-footer-trust-item';
                    item.innerHTML = checkIcon + '<span>' + label + '</span>';
                    trust.appendChild(item);
                });
            newsletter.parentNode.insertBefore(trust, newsletter.nextSibling);
        }

        /* ----------------------------------------------------------
         * N2. Footer — contact block under the logo + copyright bar
         *     The stock footer leaves the logo column sparse (just a
         *     tagline) and drops the legal entity line into an
         *     orphan paragraph at the bottom. We inject a proper
         *     contact block under the tagline (support email +
         *     location) and replace the orphan line with a clean
         *     copyright bar that renders © + year + entity on the
         *     left and a "Made with care in NYC" credit on the right.
         * ---------------------------------------------------------- */
        (function () {
            var footerRoot = document.querySelector('.site-footer');
            if (!footerRoot) return;

            // --- Contact block: find the logo column by walking up
            //     from the logo image (or the tagline paragraph) to
            //     the column container Astra renders — the first
            //     footer widget area OR a builder column.
            var logoCol = null;
            var logoImg = footerRoot.querySelector(
                'img[src*="pethoven-logo"], img[alt*="Pethoven" i], .custom-logo'
            );
            if (logoImg) {
                logoCol = logoImg.closest(
                    '.site-footer-primary-section-1, ' +
                    '.ast-builder-layout-element, ' +
                    '[data-section^="section-fb-html-"], ' +
                    '.ast-footer-widget-1-area'
                );
                // Walk up further to the grid cell if we only landed on
                // the tight image wrapper — we want to be siblings with
                // the tagline paragraph, not nested inside it.
                if (logoCol) {
                    var parent = logoCol.parentElement;
                    while (parent && parent !== footerRoot &&
                           !parent.classList.contains('ast-builder-grid-row') &&
                           !parent.matches('[class*="site-footer-primary-section"]')) {
                        logoCol = parent;
                        parent = parent.parentElement;
                    }
                }
            }
            if (!logoCol) {
                logoCol = footerRoot.querySelector(
                    '.ast-footer-widget-1-area, [data-section="section-fb-widget-1"]'
                );
            }

            if (logoCol && !logoCol.querySelector('.pt-footer-contact')) {
                var contact = document.createElement('div');
                contact.className = 'pt-footer-contact';
                contact.innerHTML =
                    '<div class="pt-footer-contact-row">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
                            '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>' +
                            '<polyline points="22,6 12,13 2,6"/>' +
                        '</svg>' +
                        '<span><a href="mailto:support@pethoven.com">support@pethoven.com</a><br>' +
                        '<span style="font-size:12px;color:#8a8a8a;">We reply within one business day</span></span>' +
                    '</div>' +
                    '<div class="pt-footer-contact-row">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
                            '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>' +
                            '<circle cx="12" cy="10" r="3"/>' +
                        '</svg>' +
                        '<span>New York, NY<br>' +
                        '<span style="font-size:12px;color:#8a8a8a;">Formulated &amp; shipped from Estonia</span></span>' +
                    '</div>';
                logoCol.appendChild(contact);
            }

            // --- Copyright bar: build a single clean row with ©/legal
            //     on the left and the social icons on the right. We
            //     relocate the stock social icons into our bar, then
            //     flag the footer so the now-empty default
            //     below-footer row is hidden via CSS (avoiding the
            //     orphan strip of icons floating in empty space).
            if (!footerRoot.querySelector('.pt-footer-copybar')) {
                var bar = document.createElement('div');
                bar.className = 'pt-footer-copybar';
                var year = new Date().getFullYear();
                bar.innerHTML =
                    '<div class="pt-footer-copybar-left">' +
                        '<span>© ' + year + ' Pethoven · A brand of Global Tail Goods LLC · All rights reserved</span>' +
                    '</div>' +
                    '<div class="pt-footer-copybar-right">' +
                        '<span class="pt-footer-copybar-sociallabel">Follow</span>' +
                        '<span class="pt-footer-copybar-social"></span>' +
                    '</div>';

                // Find the stock social icons container in the
                // below-footer and move its individual links into
                // our bar. Works for Astra builder's typical markup:
                // .ast-header-social-1-wrap or [data-section=section-fb-social-icons-*]
                var socialTarget = bar.querySelector('.pt-footer-copybar-social');
                var socialSource = footerRoot.querySelector(
                    '[class*="ast-header-social"], ' +
                    '[data-section^="section-fb-social"], ' +
                    '.ast-builder-social-element-wrap'
                );
                if (socialSource && socialTarget) {
                    var icons = socialSource.querySelectorAll('.ast-builder-social-element');
                    if (icons.length) {
                        icons.forEach(function (icon) {
                            socialTarget.appendChild(icon);
                        });
                    } else {
                        // fallback: move whatever links are in there
                        socialTarget.appendChild(socialSource);
                    }
                }

                // Hide any stock paragraph that still carries the
                // legal-entity line (the copy source in pethoven-content.php).
                var killPatterns = [
                    'pethoven is the brand operating under',
                    'global tail goods llc'
                ];
                footerRoot.querySelectorAll('p, span, div').forEach(function (el) {
                    var t = (el.textContent || '').trim().toLowerCase();
                    if (!t || t.length > 200) return;
                    if (el.querySelector('a, svg, input, button')) return;
                    if (el.closest('.pt-footer-copybar')) return;
                    for (var i = 0; i < killPatterns.length; i++) {
                        if (t.indexOf(killPatterns[i]) !== -1 &&
                            t.indexOf('©') === -1) {
                            el.style.display = 'none';
                            break;
                        }
                    }
                });

                footerRoot.appendChild(bar);
                footerRoot.classList.add('pt-copybar-active');
            }
        })();

        /* ----------------------------------------------------------
         * H0 / P. "Our Products" section
         *
         * 2026-04-24 direction: replace the "Best Sellers" block
         * (Astra grocery demo products) with an "Our Products"
         * 3-card section linking to the real WooCommerce products
         * for Sensitive Skin / Deep Clean / Puppy Collection.
         *
         * Behavior:
         *  1. Find the "Best Sellers" heading, walk up to its
         *     Elementor section, and flag it .pt-hide-best-sellers
         *     (CSS hides it).
         *  2. Inject the Our Products section at the same position
         *     so the page flow is uninterrupted.
         *
         * Only runs on the homepage (body.home).
         * ---------------------------------------------------------- */
        (function injectOurProducts() {
            if (!document.body.classList.contains('home') && !document.body.classList.contains('page-id-95')) return;
            if (document.querySelector('.pt-our-products')) return;

            // Locate Best Sellers section (the [products] shortcode block)
            var bestSellersHeading = Array.from(document.querySelectorAll('h2, h3')).find(function (h) {
                return (h.textContent || '').trim() === 'Best Sellers';
            });

            var bsSection = null;
            if (bestSellersHeading) {
                // Walk up to the top-level Elementor section (e-con.e-parent
                // for flex containers, or .elementor-top-section for legacy)
                var cur = bestSellersHeading;
                for (var _i = 0; _i < 10 && cur; _i++) {
                    cur = cur.parentElement;
                    if (cur && (cur.classList.contains('e-parent') ||
                                cur.classList.contains('elementor-top-section') ||
                                cur.matches('section.elementor-section'))) {
                        bsSection = cur;
                        break;
                    }
                }
            }

            var products = [
                {
                    name: 'Sensitive Skin',
                    desc: 'Oatmeal and aloe formula. Stops itching, repairs dry skin, safe for allergies.',
                    price: '$45',
                    priceNote: '12 oz bottle',
                    href: '/product/sensitive-skin/',
                    icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19.8 2c1 5 .5 10-2 13.5-1.5 2-5 4.5-6.8 4.5z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>'
                },
                {
                    name: 'Deep Clean',
                    desc: 'Mud, odor, buildup — gone. Built for active dogs that get into everything.',
                    price: '$36',
                    priceNote: '12 oz bottle',
                    href: '/product/deep-clean/',
                    icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/><path d="M8.5 14c.5 1.5 1.8 2.5 3.5 2.5" stroke-linecap="round"/></svg>'
                },
                {
                    name: 'Puppy Collection',
                    desc: 'Tear-free, pH-balanced. Formulated for puppies 8 weeks and older.',
                    price: '$57',
                    priceNote: '8 oz bottle',
                    href: '/product/puppy-collection/',
                    icon: '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><ellipse cx="6" cy="11" rx="2.2" ry="2.6"/><ellipse cx="18" cy="11" rx="2.2" ry="2.6"/><ellipse cx="9.5" cy="6" rx="2" ry="2.4"/><ellipse cx="14.5" cy="6" rx="2" ry="2.4"/><path d="M12 13.2c-3.2 0-5.6 2.7-5.6 5.3 0 1.4 1.1 2.5 2.5 2.5 1 0 1.5-.5 3.1-.5s2.1.5 3.1.5c1.4 0 2.5-1.1 2.5-2.5 0-2.6-2.4-5.3-5.6-5.3z"/></svg>'
                }
            ];

            var cardsHtml = products.map(function (p) {
                return '<article class="pt-product-card">' +
                    '<div class="pt-product-image">' + p.icon + '</div>' +
                    '<h3 class="pt-product-name">' + p.name + '</h3>' +
                    '<p class="pt-product-desc">' + p.desc + '</p>' +
                    '<div class="pt-product-price">' + p.price +
                        '<small>' + p.priceNote + '</small>' +
                    '</div>' +
                    '<a class="pt-product-cta" href="' + p.href + '">Shop Now <span aria-hidden="true">→</span></a>' +
                  '</article>';
            }).join('');

            var section = document.createElement('section');
            section.className = 'pt-our-products';
            section.innerHTML =
                '<div class="pt-our-products-head">' +
                    '<div class="pt-our-products-eyebrow">Three formulas, one standard</div>' +
                    '<h2 class="pt-our-products-heading">Our Products</h2>' +
                    '<p class="pt-our-products-subtitle">Targeted, vet-approved shampoos for every coat and every life stage.</p>' +
                '</div>' +
                '<div class="pt-our-products-grid">' + cardsHtml + '</div>';

            if (bsSection && bsSection.parentNode) {
                // Insert new section right before Best Sellers, then hide BS
                bsSection.parentNode.insertBefore(section, bsSection);
                bsSection.classList.add('pt-hide-best-sellers');
            } else {
                // Fallback: insert after the features bar
                var featuresBar = document.querySelector('.pt-features-bar, .elementor-element-966d6bb');
                if (featuresBar && featuresBar.parentNode) {
                    featuresBar.parentNode.insertBefore(section, featuresBar.nextSibling);
                }
            }
        })();

        /* ----------------------------------------------------------
         * H. Shop archive header — decorative brand mark + trust pills
         *    Injects a centered paw crown above the title and a row
         *    of check-icon pills below the description. Replaces the
         *    old text-only trust strip.
         * ---------------------------------------------------------- */
        var archiveHeader = document.querySelector('.woocommerce-products-header');

        /* Branded eyebrow above the page title */
        if (archiveHeader && !archiveHeader.querySelector('.pt-shop-eyebrow')) {
            var eyebrow = document.createElement('div');
            eyebrow.className = 'pt-shop-eyebrow';
            eyebrow.textContent = 'Built for dogs who deserve better';
            archiveHeader.insertBefore(eyebrow, archiveHeader.firstChild);
        }

        /* Subtitle below the title */
        var pageTitle = archiveHeader && archiveHeader.querySelector('.woocommerce-products-header__title, .page-title');
        if (pageTitle && !archiveHeader.querySelector('.pt-shop-subtitle')) {
            var subtitle = document.createElement('p');
            subtitle.className = 'pt-shop-subtitle';
            subtitle.textContent = 'Three targeted shampoos — organic, vet-approved, and formulated to actually work.';
            pageTitle.parentNode.insertBefore(subtitle, pageTitle.nextSibling);
        }

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
