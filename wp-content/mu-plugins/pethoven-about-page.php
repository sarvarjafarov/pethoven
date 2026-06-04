<?php
/**
 * Pethoven About Page — full content replacement.
 *
 * Plugin Name: Pethoven About Page
 * Description: Replaces the legacy Astra/Elementor demo content on
 *              the About page (page-id 96) with a clean, honest,
 *              image-led layout. Renders entirely via the_content
 *              filter so we don't have to touch Elementor's data.
 *
 * Why a filter, not an Elementor edit:
 *   The page was inherited from the Astra grocery starter template
 *   and accumulated bad widgets (fake "4800+ Curated Products"
 *   counter, "Mila Kunit" placeholder testimonial, ingredient
 *   list with Manuka Honey + Green Tea Extract — none of which are
 *   in our actual products). Cleaner to fully replace the rendered
 *   markup than to tame the widgets one at a time.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// the_content filter on the About page only.
// Priority 999 so we run AFTER Elementor's apply_builder_in_content
// (which is hooked at priority 9) and AFTER any other filter that
// might rewrite the content. If we ran earlier, Elementor would
// silently overwrite our markup with its own builder output for
// pages flagged with _elementor_edit_mode.
add_filter( 'the_content', 'pethoven_about_replace_content', 999 );

function pethoven_about_replace_content( $content ) {
	// Match by page slug, not ID, so it survives a DB rebuild
	if ( ! is_page( 'about' ) ) {
		return $content;
	}

	// Don't replace inside Elementor's edit-mode (would break the
	// editor preview). Elementor sets a query var in edit context.
	if ( isset( $_GET['elementor-preview'] ) ) {
		return $content;
	}

	$img = WPMU_PLUGIN_URL . '/assets/about-hero.png';

	ob_start();
	?>
	<section class="pt-about" aria-labelledby="pt-about-headline">

		<!-- Hero -->
		<div class="pt-about-hero">
			<div class="pt-about-hero-image">
				<img src="<?php echo esc_url( $img ); ?>" alt="A woman embracing a golden retriever next to a bottle of Pethoven Hemp Oil-Rosemary Dog Shampoo" width="900" height="900" loading="eager" decoding="async">
			</div>
			<div class="pt-about-hero-copy">
				<div class="pt-about-eyebrow">Why Pethoven</div>
				<h1 id="pt-about-headline" class="pt-about-headline">A small line of dog shampoos. Honestly made.</h1>
				<p class="pt-about-lead">We started Pethoven because we couldn't find a dog shampoo we'd actually want to use ourselves. The drugstore stuff was harsh. The premium stuff was overpriced for the same generic formula behind a nicer label.</p>
			</div>
		</div>

		<!-- Story -->
		<div class="pt-about-block pt-about-block--story">
			<p>So we started small. Three shampoos, each formulated for a specific coat condition &mdash; sensitive skin, long coats, dry-and-dull coats &mdash; plus a paw wax for hot pavement and winter salt.</p>
			<p>Hand-mixed in small batches in <strong>Tartu, Estonia</strong>. Shipped from our warehouse in <strong>Dover, Delaware</strong>.</p>
			<p>We test every batch on our own dogs first. If they won't use it, it doesn't ship.</p>
		</div>

		<!-- What's in / what's out -->
		<div class="pt-about-grid">
			<div class="pt-about-card pt-about-card--in">
				<div class="pt-about-card-label">What's in</div>
				<ul class="pt-about-list">
					<li>Cold-pressed plant oils &mdash; avocado, coconut, hemp seed, sweet almond</li>
					<li>Aloe vera leaf juice for soothing</li>
					<li>Essential oils for scent &mdash; lavender, rosemary, peppermint</li>
					<li>Mild glucoside surfactants from corn and coconut</li>
					<li>Beeswax and shea butter (paw wax only)</li>
				</ul>
			</div>
			<div class="pt-about-card pt-about-card--out">
				<div class="pt-about-card-label">What's not</div>
				<ul class="pt-about-list pt-about-list--out">
					<li>Sulfates (SLS, SLES)</li>
					<li>Parabens</li>
					<li>Phthalates</li>
					<li>Synthetic fragrances</li>
					<li>Artificial dyes</li>
					<li>Animal testing &mdash; ever</li>
				</ul>
			</div>
		</div>

		<!-- Where + ship -->
		<div class="pt-about-block pt-about-block--where">
			<h2 class="pt-about-h2">Where it's made</h2>
			<p>Each bottle is hand-finished in our workshop in <strong>Tartu, Estonia</strong>, then shipped to our US warehouse in <strong>Dover, Delaware</strong>, and on to your door.</p>
			<p class="pt-about-region">Currently delivering to <strong>NY &middot; NJ &middot; MA &middot; DC &middot; CT &middot; PA</strong>. More regions coming as we scale up batch sizes.</p>
		</div>

		<!-- CTA -->
		<div class="pt-about-cta-row">
			<a class="pt-about-cta" href="/shop/">Shop all formulas <span aria-hidden="true">&rarr;</span></a>
		</div>

	</section>
	<?php
	return ob_get_clean();
}

/* ----------------------------------------------------------------------
 * Inline CSS — kept with the renderer for cohesion. Output via wp_head
 * only on the About page to avoid loading on every request.
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', 'pethoven_about_css', 40 );

function pethoven_about_css() {
	if ( is_admin() || ! is_page( 'about' ) ) {
		return;
	}
	?>
	<style id="pt-about-css">
	/* Page-level surface — cream wash, no Elementor demo bg leaking through */
	body.page-id-96 #content,
	body.page-id-96 .ast-container,
	body.page-id-96 main,
	body.page-id-96 .entry-content {
		background: transparent !important;
	}
	body.page-id-96 .entry-content { padding: 0 !important; }

	/* Hide anything Elementor still renders on the about page —
	 * leftover demo widgets (fake stats, fake testimonial,
	 * miscategorized ingredient list, etc.). Our the_content filter
	 * already replaces the body, but the page header area + any
	 * top-level Elementor wrappers WP may still output around it
	 * get suppressed here for belt-and-braces. */
	body.page-id-96 .elementor:not(:has(.pt-about)) {
		display: none !important;
	}
	body.page-id-96 .entry-header {
		display: none !important; /* the H1 lives inside pt-about-hero */
	}

	.pt-about {
		max-width: 1100px;
		margin: 0 auto;
		padding: 56px 24px 80px;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
		color: #1a1a1a;
	}

	/* ---- HERO ---- */
	.pt-about-hero {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 56px;
		align-items: center;
		margin-bottom: 72px;
	}
	.pt-about-hero-image {
		border-radius: 24px;
		overflow: hidden;
		background: #f4f6ee;
		aspect-ratio: 1;
		box-shadow: 0 18px 48px rgba(26, 58, 42, 0.08);
	}
	.pt-about-hero-image img {
		display: block;
		width: 100%;
		height: 100%;
		object-fit: cover;
		object-position: center;
	}
	.pt-about-eyebrow {
		font-size: 11px;
		font-weight: 800;
		letter-spacing: 3px;
		text-transform: uppercase;
		color: #6a9739;
		margin-bottom: 18px;
	}
	.pt-about-headline {
		font-family: Georgia, 'Times New Roman', serif;
		font-size: 42px;
		font-weight: 800;
		line-height: 1.12;
		letter-spacing: -0.8px;
		color: #1a1a1a;
		margin: 0 0 22px;
	}
	.pt-about-lead {
		font-size: 17px;
		line-height: 1.65;
		color: #3a3a3a;
		margin: 0;
	}

	/* ---- STORY ---- */
	.pt-about-block {
		max-width: 720px;
		margin: 0 auto 64px;
	}
	.pt-about-block p {
		font-size: 16px;
		line-height: 1.75;
		color: #2a2a2a;
		margin: 0 0 18px;
	}
	.pt-about-block p strong {
		color: #1a1a1a;
		font-weight: 700;
	}

	/* ---- WHAT'S IN / WHAT'S NOT ---- */
	.pt-about-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 24px;
		margin: 0 0 72px;
	}
	.pt-about-card {
		background: #ffffff;
		border: 1px solid #f0f0ec;
		border-radius: 20px;
		padding: 32px 30px;
	}
	.pt-about-card--in {
		background: linear-gradient(135deg, rgba(139, 195, 74, 0.08) 0%, rgba(106, 151, 57, 0.02) 100%);
		border-color: rgba(106, 151, 57, 0.22);
	}
	.pt-about-card--out {
		background: #fafafa;
	}
	.pt-about-card-label {
		font-size: 11px;
		font-weight: 800;
		letter-spacing: 2.5px;
		text-transform: uppercase;
		color: #1a1a1a;
		margin-bottom: 18px;
	}
	.pt-about-card--in .pt-about-card-label { color: #6a9739; }
	.pt-about-card--out .pt-about-card-label { color: #888; }

	.pt-about-list {
		list-style: none;
		padding: 0;
		margin: 0;
	}
	.pt-about-list li {
		position: relative;
		padding: 8px 0 8px 28px;
		font-size: 14.5px;
		line-height: 1.55;
		color: #2a2a2a;
		border-top: 1px solid rgba(0, 0, 0, 0.04);
	}
	.pt-about-list li:first-child { border-top: 0; }
	.pt-about-list li::before {
		content: '✓';
		position: absolute;
		left: 0;
		top: 8px;
		width: 18px;
		height: 18px;
		font-weight: 800;
		color: #6a9739;
		font-size: 13px;
		line-height: 1.35;
	}
	.pt-about-list--out li::before {
		content: '×';
		color: #b4b4b4;
		font-size: 18px;
		line-height: 1;
		top: 8px;
	}

	/* ---- WHERE ---- */
	.pt-about-block--where {
		text-align: center;
	}
	.pt-about-h2 {
		font-family: Georgia, 'Times New Roman', serif;
		font-size: 26px;
		font-weight: 800;
		letter-spacing: -0.3px;
		color: #1a1a1a;
		margin: 0 0 18px;
	}
	.pt-about-region {
		font-size: 14px;
		color: #5a5a5a;
		margin-top: 18px;
	}

	/* ---- CTA ---- */
	.pt-about-cta-row {
		display: flex;
		justify-content: center;
		margin-top: 48px;
	}
	.pt-about-cta {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 16px 36px;
		background: #1a1a1a;
		color: #ffffff !important;
		border-radius: 100px;
		font-size: 13px;
		font-weight: 800;
		letter-spacing: 1.5px;
		text-transform: uppercase;
		text-decoration: none !important;
		line-height: 1.3;
		transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
	}
	.pt-about-cta:hover {
		background: #6a9739;
		transform: translateY(-2px);
		box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
	}
	.pt-about-cta span { transition: transform 0.25s ease; }
	.pt-about-cta:hover span { transform: translateX(4px); }

	/* ---- TABLET ---- */
	@media (max-width: 900px) {
		.pt-about { padding: 40px 20px 60px; }
		.pt-about-hero {
			grid-template-columns: 1fr;
			gap: 32px;
			margin-bottom: 48px;
		}
		.pt-about-hero-image { aspect-ratio: 4 / 3; }
		.pt-about-headline { font-size: 32px; }
		.pt-about-lead { font-size: 15.5px; }
		.pt-about-grid {
			grid-template-columns: 1fr;
			gap: 16px;
			margin-bottom: 56px;
		}
		.pt-about-block { margin-bottom: 48px; }
	}

	/* ---- MOBILE ---- */
	@media (max-width: 540px) {
		.pt-about { padding: 32px 18px 56px; }
		.pt-about-headline { font-size: 28px; letter-spacing: -0.5px; }
		.pt-about-card { padding: 24px 22px; }
		.pt-about-cta { padding: 14px 28px; font-size: 12px; }
	}
	</style>
	<?php
}
