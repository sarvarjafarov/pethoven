<?php
/**
 * Pethoven Contact Page — full content replacement.
 *
 * Plugin Name: Pethoven Contact Page
 * Description: Replaces the legacy Astra demo content on /contact/
 *              with honest contact info + a real FAQ. Same pattern
 *              as pethoven-about-page.php — hooks the_content at
 *              priority 999 so we run after Elementor.
 *
 * Removed from the prior page:
 *   - The fake phone icon block (user request: no phone)
 *   - Generic demo address "1569 Ave, New York"
 *   - Six FAQ entries all answered with Lorem-ipsum placeholder
 *   - Astra "Get In Touch" widget chrome
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'the_content', 'pethoven_contact_replace_content', 999 );

function pethoven_contact_replace_content( $content ) {
	if ( ! is_page( 'contact' ) ) {
		return $content;
	}
	if ( isset( $_GET['elementor-preview'] ) ) {
		return $content;
	}

	ob_start();
	?>
	<section class="pt-contact" aria-labelledby="pt-contact-headline">

		<!-- Intro -->
		<div class="pt-contact-intro">
			<div class="pt-contact-eyebrow">Get in touch</div>
			<h1 id="pt-contact-headline" class="pt-contact-headline">A real human reads every email.</h1>
			<p class="pt-contact-lead">Order question, ingredient curiosity, press request &mdash; write us and we'll get back to you within one business day, usually sooner.</p>
		</div>

		<!-- Contact cards -->
		<div class="pt-contact-cards">
			<a class="pt-contact-card" href="mailto:support@pethoven.com">
				<div class="pt-contact-card-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
				</div>
				<div class="pt-contact-card-label">Customer support</div>
				<div class="pt-contact-card-email">support@pethoven.com</div>
				<div class="pt-contact-card-note">Order questions, returns, ingredient details, formula advice.</div>
			</a>

			<a class="pt-contact-card" href="mailto:press@pethoven.com">
				<div class="pt-contact-card-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h13v10H3z"/><path d="M16 11h4l2 3v3h-6z"/><circle cx="7" cy="17" r="2"/><circle cx="18" cy="17" r="2"/></svg>
				</div>
				<div class="pt-contact-card-label">Press &amp; partnerships</div>
				<div class="pt-contact-card-email">press@pethoven.com</div>
				<div class="pt-contact-card-note">Editorial coverage, retail partnerships, wholesale inquiries.</div>
			</a>

			<div class="pt-contact-card pt-contact-card--static">
				<div class="pt-contact-card-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
				</div>
				<div class="pt-contact-card-label">Mailing address</div>
				<div class="pt-contact-card-email">Global Tail Goods LLC</div>
				<div class="pt-contact-card-note">8 The Green, STE R<br>Dover, DE 19901, USA</div>
			</div>
		</div>

		<!-- Response-time strip -->
		<div class="pt-contact-strip">
			<div class="pt-contact-strip-item">
				<strong>Within one business day</strong>
				<span>Replies, Mon&ndash;Fri</span>
			</div>
			<div class="pt-contact-strip-divider" aria-hidden="true"></div>
			<div class="pt-contact-strip-item">
				<strong>Currently shipping to</strong>
				<span>NY &middot; NJ &middot; MA &middot; DC &middot; CT &middot; PA</span>
			</div>
		</div>

		<!-- FAQ -->
		<div class="pt-contact-faq">
			<div class="pt-contact-faq-head">
				<div class="pt-contact-eyebrow">Common questions</div>
				<h2 class="pt-contact-faq-headline">Frequently asked</h2>
			</div>

			<details class="pt-faq" open>
				<summary>What's actually in your shampoo?</summary>
				<div class="pt-faq-body">
					<p>Every formula starts with water, aloe vera leaf juice, and mild glucoside surfactants made from corn and coconut. The active ingredients change with the formula &mdash; avocado oil and lavender for sensitive skin; coconut oil, sea buckthorn, and lavender for long coats; cold-pressed hemp seed oil and rosemary for dry coats. The full ingredient list is printed on every bottle and on each product page.</p>
				</div>
			</details>

			<details class="pt-faq">
				<summary>Is it safe for puppies?</summary>
				<div class="pt-faq-body">
					<p>The Avocado-Lavender formula is gentle enough for puppies eight weeks and older. With any new shampoo we'd suggest the same routine: one bath, watch the skin for a day, continue if everything looks fine.</p>
				</div>
			</details>

			<details class="pt-faq">
				<summary>How often should I bathe my dog?</summary>
				<div class="pt-faq-body">
					<p>For most coats, every three to four weeks works well. Active dogs, dogs with allergies, or dogs with longer coats may need it more often. Our formulas are mild enough for weekly use without stripping the coat's natural oils.</p>
				</div>
			</details>

			<details class="pt-faq">
				<summary>Do you test on animals?</summary>
				<div class="pt-faq-body">
					<p>No. We test on ourselves and on our own dogs &mdash; never on third-party animals. Cruelty-free by certification and by practice.</p>
				</div>
			</details>

			<details class="pt-faq">
				<summary>How fast is shipping?</summary>
				<div class="pt-faq-body">
					<p>Orders placed before noon ET ship the same day. Standard delivery to the Northeast US takes two to three business days. Free over $25; flat $5.99 below that. Outside the current delivery region (NY, NJ, MA, DC, CT, PA) we can't ship yet, but we're scaling up.</p>
				</div>
			</details>

			<details class="pt-faq">
				<summary>What's your return policy?</summary>
				<div class="pt-faq-body">
					<p>Thirty days, no questions asked. If a formula doesn't agree with your dog's coat or skin, email us with the order number and we'll refund the bottle. You keep it &mdash; no need to ship it back.</p>
				</div>
			</details>

			<details class="pt-faq">
				<summary>Where are your products made?</summary>
				<div class="pt-faq-body">
					<p>Hand-mixed in small batches in Tartu, Estonia.</p>
				</div>
			</details>

			<details class="pt-faq">
				<summary>Are the bottles recyclable?</summary>
				<div class="pt-faq-body">
					<p>Yes &mdash; every bottle is recyclable PET. We chose PET over glass because it's lighter to ship (lower carbon footprint per bottle delivered), and over biodegradable alternatives because PET is one of the few plastics that actually gets recycled in most US municipal streams.</p>
				</div>
			</details>
		</div>

	</section>
	<?php
	return ob_get_clean();
}

/* ----------------------------------------------------------------------
 * Page-scoped CSS — wp_head only on the /contact/ page.
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', 'pethoven_contact_css', 40 );

function pethoven_contact_css() {
	if ( is_admin() || ! is_page( 'contact' ) ) {
		return;
	}
	?>
	<style id="pt-contact-css">
	/* Suppress legacy Elementor demo widgets on /contact/ */
	body.page-id-8386 .elementor:not(:has(.pt-contact)),
	body.page-id-95 .elementor:not(:has(.pt-contact)) {
		display: none !important;
	}
	body.page-template-default .entry-content { padding: 0 !important; }
	body.page-template-default .entry-header { display: none !important; }

	.pt-contact {
		max-width: 1080px;
		margin: 0 auto;
		padding: 72px 24px 96px;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
		color: #1a1a1a;
	}

	/* ---- INTRO ---- */
	.pt-contact-intro {
		max-width: 720px;
		margin: 0 auto 56px;
		text-align: center;
	}
	.pt-contact-eyebrow {
		display: inline-flex;
		align-items: center;
		gap: 12px;
		font-size: 11px;
		font-weight: 800;
		letter-spacing: 3px;
		text-transform: uppercase;
		color: #6a9739;
		margin-bottom: 22px;
	}
	.pt-contact-eyebrow::before,
	.pt-contact-eyebrow::after {
		content: '';
		width: 28px;
		height: 1.5px;
		background: #6a9739;
		opacity: 0.55;
		border-radius: 2px;
	}
	.pt-contact-headline {
		font-family: Georgia, 'Times New Roman', serif;
		font-size: 44px;
		font-weight: 800;
		line-height: 1.12;
		letter-spacing: -0.8px;
		color: #1a1a1a;
		margin: 0 0 24px;
	}
	.pt-contact-lead {
		font-size: 17px;
		line-height: 1.65;
		color: #3a3a3a;
		margin: 0;
		max-width: 580px;
		margin-inline: auto;
	}

	/* ---- CONTACT CARDS ----
	 * Three equal-width columns. Cards size to their natural content;
	 * CSS Grid's default align-items: stretch keeps them at the same
	 * height as the tallest. No artificial min-height — would just
	 * create dead vertical space inside cards. */
	.pt-contact-cards {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 18px;
		margin-bottom: 40px;
	}
	.pt-contact-card {
		display: flex;
		flex-direction: column;
		gap: 8px;
		padding: 28px 26px;
		background: #ffffff;
		border: 1px solid #f0f0ec;
		border-radius: 18px;
		text-decoration: none !important;
		color: #1a1a1a !important;
		transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
		            border-color 0.25s ease,
		            box-shadow 0.3s ease;
		box-sizing: border-box;
	}
	a.pt-contact-card:hover {
		transform: translateY(-3px);
		border-color: rgba(106, 151, 57, 0.32);
		box-shadow: 0 16px 36px rgba(106, 151, 57, 0.10),
		            0 4px 12px rgba(0, 0, 0, 0.04);
	}
	.pt-contact-card--static {
		cursor: default;
		background: #fafafa;
	}
	.pt-contact-card-icon {
		width: 40px;
		height: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #6a9739;
		background: linear-gradient(135deg, rgba(139,195,74,0.16), rgba(106,151,57,0.04));
		border-radius: 12px;
		margin-bottom: 6px;
		flex-shrink: 0;
	}
	.pt-contact-card-icon svg {
		width: 20px;
		height: 20px;
	}
	.pt-contact-card-label {
		font-size: 10.5px;
		font-weight: 800;
		letter-spacing: 1.8px;
		text-transform: uppercase;
		color: #6a6a6a;
	}
	.pt-contact-card-email {
		font-size: 16px;
		font-weight: 700;
		color: #1a1a1a;
		line-height: 1.35;
	}
	.pt-contact-card-note {
		font-size: 13.5px;
		line-height: 1.55;
		color: #5a5a5a;
		margin-top: 6px;
	}

	/* ---- INFO STRIP ---- */
	.pt-contact-strip {
		display: flex;
		align-items: stretch;
		justify-content: center;
		gap: 28px;
		background: linear-gradient(135deg, rgba(139,195,74,0.10) 0%, rgba(106,151,57,0.03) 100%);
		border: 1px solid rgba(106,151,57,0.22);
		border-radius: 16px;
		padding: 18px 28px;
		margin-bottom: 72px;
	}
	.pt-contact-strip-item {
		text-align: center;
		font-size: 14px;
		line-height: 1.55;
		color: #3a3a3a;
	}
	.pt-contact-strip-item strong {
		display: block;
		color: #1a3a2a;
		font-weight: 800;
		font-size: 13.5px;
	}
	.pt-contact-strip-item span {
		color: #5a5a5a;
		font-size: 12.5px;
	}
	.pt-contact-strip-divider {
		width: 1px;
		background: rgba(106,151,57,0.20);
	}

	/* ---- FAQ ---- */
	.pt-contact-faq-head {
		text-align: center;
		margin-bottom: 36px;
	}
	.pt-contact-faq-head .pt-contact-eyebrow {
		margin-bottom: 16px;
	}
	.pt-contact-faq-headline {
		font-family: Georgia, 'Times New Roman', serif;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.5px;
		color: #1a1a1a;
		margin: 0;
	}

	.pt-faq {
		border-top: 1px solid #f0f0ec;
		padding: 4px 0;
		max-width: 760px;
		margin: 0 auto;
	}
	.pt-faq:last-of-type { border-bottom: 1px solid #f0f0ec; }
	.pt-faq summary {
		position: relative;
		list-style: none;
		cursor: pointer;
		padding: 22px 44px 22px 0;
		font-size: 17px;
		font-weight: 700;
		color: #1a1a1a;
		line-height: 1.35;
		transition: color 0.2s ease;
	}
	.pt-faq summary::-webkit-details-marker { display: none; }
	.pt-faq summary::after {
		content: '';
		position: absolute;
		right: 4px;
		top: 50%;
		width: 14px;
		height: 14px;
		margin-top: -7px;
		background:
			linear-gradient(currentColor, currentColor) center / 100% 2px no-repeat,
			linear-gradient(currentColor, currentColor) center / 2px 100% no-repeat;
		color: #6a9739;
		transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1);
	}
	.pt-faq[open] summary::after { transform: rotate(45deg); }
	.pt-faq:hover summary { color: #6a9739; }
	.pt-faq-body {
		padding: 0 60px 22px 0;
	}
	.pt-faq-body p {
		margin: 0;
		font-size: 15.5px;
		line-height: 1.7;
		color: #3a3a3a;
	}

	/* ---- TABLET ---- */
	@media (max-width: 900px) {
		.pt-contact { padding: 52px 20px 72px; }
		.pt-contact-headline { font-size: 36px; }
		.pt-contact-cards { grid-template-columns: 1fr; gap: 14px; margin-bottom: 28px; }
		.pt-contact-strip { flex-direction: column; gap: 14px; padding: 20px 24px; margin-bottom: 56px; }
		.pt-contact-strip-divider { display: none; }
		.pt-contact-faq-headline { font-size: 26px; }
	}

	/* ---- MOBILE ---- */
	@media (max-width: 540px) {
		.pt-contact { padding: 40px 18px 56px; }
		.pt-contact-eyebrow { letter-spacing: 2.5px; font-size: 10.5px; }
		.pt-contact-eyebrow::before,
		.pt-contact-eyebrow::after { width: 20px; }
		.pt-contact-headline { font-size: 30px; letter-spacing: -0.5px; }
		.pt-contact-lead { font-size: 15.5px; }
		.pt-contact-card { padding: 22px 20px; }
		.pt-contact-faq summary { padding: 18px 36px 18px 0; font-size: 15.5px; }
		.pt-faq-body { padding: 0 24px 18px 0; }
		.pt-faq-body p { font-size: 15px; }
	}
	</style>
	<?php
}
