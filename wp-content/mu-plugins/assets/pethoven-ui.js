/* ======== pethoven-ui-js ======== */
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

/* ======== pethoven-broken-img-js ======== */
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

/* ======== pethoven-cleanup-js ======== */
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

        // Three icons used in rotation by index. We don't render real
        // product images in this section — the cards are tagline-led
        // and the icons act as quiet visual variation.
        var icons = [
            // 0 — leaf (soothing)
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19.8 2c1 5 .5 10-2 13.5-1.5 2-5 4.5-6.8 4.5z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>',
            // 1 — water droplet (fresh)
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/><path d="M8.5 14c.5 1.5 1.8 2.5 3.5 2.5" stroke-linecap="round"/></svg>',
            // 2 — paw (shine)
            '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><ellipse cx="6" cy="11" rx="2.2" ry="2.6"/><ellipse cx="18" cy="11" rx="2.2" ry="2.6"/><ellipse cx="9.5" cy="6" rx="2" ry="2.4"/><ellipse cx="14.5" cy="6" rx="2" ry="2.4"/><path d="M12 13.2c-3.2 0-5.6 2.7-5.6 5.3 0 1.4 1.1 2.5 2.5 2.5 1 0 1.5-.5 3.1-.5s2.1.5 3.1.5c1.4 0 2.5-1.1 2.5-2.5 0-2.6-2.4-5.3-5.6-5.3z"/></svg>'
        ];

        // PHP pre-renders the catalog into window.PETHOVEN_HOMEPAGE_PRODUCTS
        // (see pethoven-content.php → pethoven_homepage_products_data).
        // Each item already has name/desc/price/priceNote/href filled in
        // from the live WC catalog, so URLs and titles stay in sync as
        // products are renamed or replaced.
        var dynamic = Array.isArray(window.PETHOVEN_HOMEPAGE_PRODUCTS)
            ? window.PETHOVEN_HOMEPAGE_PRODUCTS
            : [];
        var products = dynamic.length
            ? dynamic.slice(0, 3).map(function (p, idx) {
                return {
                    name:      p.name      || '',
                    desc:      p.desc      || '',
                    price:     p.price     || '',
                    priceNote: p.priceNote || '',
                    href:      p.href      || '/shop/',
                    image:     p.image     || '',
                    icon:      icons[idx % icons.length]
                };
            })
            // Fallback (shouldn't trigger on prod/staging — PHP always sets
            // the global — but keeps the script self-sufficient if loaded
            // standalone, e.g. during a partial deploy or local preview).
            : [
                { name: 'Avocado-Lavender Dog Shampoo',  desc: 'Soothing avocado + lavender for sensitive skin.',     price: '$22.50', priceNote: '300ml', href: '/shop/', image: '', icon: icons[0] },
                { name: 'Coconut-Peppermint Dog Shampoo',desc: 'Detangling, conditioning wash for long-haired coats.', price: '$22.50', priceNote: '300ml', href: '/shop/', image: '', icon: icons[1] },
                { name: 'Hemp Oil-Rosemary Dog Shampoo', desc: 'Strong-and-shiny coat formula for dry or dull coats.', price: '$22.50', priceNote: '300ml', href: '/shop/', image: '', icon: icons[2] }
            ];

        function esc(s) {
            return String(s).replace(/[&<>"']/g, function (c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
            });
        }

        var cardsHtml = products.map(function (p) {
            var imageHtml = p.image
                ? '<img src="' + esc(p.image) + '" alt="' + esc(p.name) + '" loading="lazy" decoding="async">'
                : p.icon;
            return '<article class="pt-product-card' + (p.image ? ' pt-product-card-has-image' : '') + '">' +
                '<div class="pt-product-image">' + imageHtml + '</div>' +
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
            '<div class="pt-our-products-grid">' + cardsHtml + '</div>' +
            '<div class="pt-our-products-cta-row">' +
                '<a class="pt-our-products-cta" href="/shop/">View all products <span aria-hidden="true">→</span></a>' +
            '</div>';

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
        subtitle.textContent = 'Three targeted shampoos and a finishing wax — organic, vet-approved, and formulated to actually work.';
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
