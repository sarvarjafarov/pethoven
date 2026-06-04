<?php
/**
 * Pethoven Blog Page — replaces /sample-page/ with a real blog
 * landing-grid populated from the live Care Guide post category.
 *
 * Plugin Name: Pethoven Blog Page
 * Description: The nav menu points "Blog" at /sample-page/ (legacy
 *              from the Astra demo). Rather than re-pointing the
 *              menu and risking dead links, we keep that URL and
 *              render a clean post-grid index on it. Posts are
 *              pulled from the live wp_posts table at render time so
 *              future entries auto-appear.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'the_content', 'pethoven_blog_replace_content', 999 );

function pethoven_blog_replace_content( $content ) {
	if ( ! is_page( 'sample-page' ) ) {
		return $content;
	}
	if ( isset( $_GET['elementor-preview'] ) ) {
		return $content;
	}

	$posts = get_posts( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 12,
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );

	ob_start();
	?>
	<section class="pt-blog" aria-labelledby="pt-blog-headline">

		<!-- Intro -->
		<div class="pt-blog-intro">
			<div class="pt-blog-eyebrow">Care Guide</div>
			<h1 id="pt-blog-headline" class="pt-blog-headline">Notes on dog care.</h1>
			<p class="pt-blog-lead">Specific, honest writeups about coat care, ingredient choices, and how to use our products. No filler, no listicles, no AI-generated wellness fluff.</p>
		</div>

		<?php if ( empty( $posts ) ) : ?>
			<div class="pt-blog-empty">
				<p>No posts yet. Check back soon.</p>
			</div>
		<?php else : ?>
			<div class="pt-blog-grid">
				<?php foreach ( $posts as $p ) :
					$image  = get_the_post_thumbnail_url( $p->ID, 'large' );
					$cats   = get_the_category( $p->ID );
					$cat    = ! empty( $cats ) ? $cats[0]->name : '';
					$url    = get_permalink( $p->ID );
					$date   = mysql2date( 'M j, Y', $p->post_date );

					// Reading time estimate — words / 220 wpm
					$word_count = str_word_count( wp_strip_all_tags( $p->post_content ) );
					$read_min   = max( 1, (int) round( $word_count / 220 ) );

					// Excerpt: use saved excerpt or first 24 words of content
					$excerpt = $p->post_excerpt;
					if ( '' === $excerpt ) {
						$excerpt = wp_trim_words( wp_strip_all_tags( $p->post_content ), 24, '…' );
					}
					?>
					<article class="pt-blog-card">
						<a class="pt-blog-card-link" href="<?php echo esc_url( $url ); ?>">
							<?php if ( $image ) : ?>
								<div class="pt-blog-card-image">
									<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $p->post_title ); ?>" loading="lazy" decoding="async">
								</div>
							<?php endif; ?>
							<div class="pt-blog-card-body">
								<div class="pt-blog-card-meta">
									<?php if ( $cat ) : ?><span class="pt-blog-card-cat"><?php echo esc_html( $cat ); ?></span><?php endif; ?>
									<span class="pt-blog-card-readtime"><?php echo (int) $read_min; ?> min read</span>
								</div>
								<h2 class="pt-blog-card-title"><?php echo esc_html( $p->post_title ); ?></h2>
								<p class="pt-blog-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>
								<div class="pt-blog-card-footer">
									<span class="pt-blog-card-date"><?php echo esc_html( $date ); ?></span>
									<span class="pt-blog-card-cta">Read &rarr;</span>
								</div>
							</div>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</section>
	<?php
	return ob_get_clean();
}

/* ----------------------------------------------------------------------
 * Inline CSS — scoped to /sample-page/ only.
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', 'pethoven_blog_css', 40 );

function pethoven_blog_css() {
	if ( is_admin() || ! is_page( 'sample-page' ) ) {
		return;
	}
	?>
	<style id="pt-blog-css">
	body.page-template-default .entry-header { display: none !important; }
	body.page-template-default .entry-content { padding: 0 !important; background: transparent !important; }

	.pt-blog {
		max-width: 1180px;
		margin: 0 auto;
		padding: 72px 24px 96px;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
		color: #1a1a1a;
	}

	/* ---- INTRO ---- */
	.pt-blog-intro {
		max-width: 720px;
		margin: 0 auto 56px;
		text-align: center;
	}
	.pt-blog-eyebrow {
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
	.pt-blog-eyebrow::before,
	.pt-blog-eyebrow::after {
		content: '';
		width: 28px;
		height: 1.5px;
		background: #6a9739;
		opacity: 0.55;
		border-radius: 2px;
	}
	.pt-blog-headline {
		font-family: Georgia, 'Times New Roman', serif;
		font-size: 44px;
		font-weight: 800;
		line-height: 1.12;
		letter-spacing: -0.8px;
		color: #1a1a1a;
		margin: 0 0 22px;
	}
	.pt-blog-lead {
		font-size: 16.5px;
		line-height: 1.65;
		color: #3a3a3a;
		margin: 0 auto;
		max-width: 600px;
	}

	/* ---- GRID ---- */
	.pt-blog-grid {
		display: grid;
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 32px;
	}
	.pt-blog-card {
		background: #ffffff;
		border: 1px solid #f0f0ec;
		border-radius: 20px;
		overflow: hidden;
		transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1),
		            border-color 0.25s ease,
		            box-shadow 0.35s ease;
	}
	.pt-blog-card:hover {
		transform: translateY(-4px);
		border-color: rgba(106, 151, 57, 0.32);
		box-shadow: 0 22px 48px rgba(106, 151, 57, 0.10),
		            0 6px 16px rgba(0, 0, 0, 0.04);
	}
	.pt-blog-card-link {
		display: block;
		color: #1a1a1a !important;
		text-decoration: none !important;
	}
	.pt-blog-card-image {
		aspect-ratio: 16 / 10;
		background: #f4f6ee;
		overflow: hidden;
	}
	.pt-blog-card-image img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		object-position: center;
		display: block;
		transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
	}
	.pt-blog-card:hover .pt-blog-card-image img {
		transform: scale(1.04);
	}
	.pt-blog-card-body {
		padding: 24px 26px 26px;
	}
	.pt-blog-card-meta {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 11px;
		font-weight: 700;
		letter-spacing: 1.5px;
		text-transform: uppercase;
		color: #8a8a8a;
		margin-bottom: 12px;
	}
	.pt-blog-card-cat {
		color: #6a9739;
		background: rgba(139, 195, 74, 0.12);
		padding: 4px 10px;
		border-radius: 100px;
	}
	.pt-blog-card-readtime { color: #999; letter-spacing: 1px; font-weight: 600; }
	.pt-blog-card-title {
		font-family: Georgia, 'Times New Roman', serif;
		font-size: 22px;
		font-weight: 800;
		line-height: 1.25;
		letter-spacing: -0.3px;
		color: #1a1a1a;
		margin: 0 0 12px;
	}
	.pt-blog-card:hover .pt-blog-card-title { color: #1a3a2a; }
	.pt-blog-card-excerpt {
		font-size: 14.5px;
		line-height: 1.6;
		color: #5a5a5a;
		margin: 0 0 18px;
	}
	.pt-blog-card-footer {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding-top: 14px;
		border-top: 1px solid #f0f0ec;
		font-size: 12px;
	}
	.pt-blog-card-date { color: #8a8a8a; }
	.pt-blog-card-cta {
		color: #6a9739;
		font-weight: 700;
		letter-spacing: 1px;
		text-transform: uppercase;
		font-size: 11px;
		transition: transform 0.25s ease;
	}
	.pt-blog-card:hover .pt-blog-card-cta { transform: translateX(3px); }

	.pt-blog-empty {
		text-align: center;
		padding: 48px 0;
		color: #6a6a6a;
		font-size: 15px;
	}

	/* ---- TABLET / MOBILE ---- */
	@media (max-width: 900px) {
		.pt-blog { padding: 56px 20px 72px; }
		.pt-blog-headline { font-size: 36px; }
		.pt-blog-grid { grid-template-columns: 1fr; gap: 22px; }
		.pt-blog-card-body { padding: 22px 22px 22px; }
		.pt-blog-card-title { font-size: 19px; }
	}

	@media (max-width: 540px) {
		.pt-blog { padding: 40px 18px 56px; }
		.pt-blog-eyebrow { font-size: 10.5px; letter-spacing: 2.5px; }
		.pt-blog-eyebrow::before, .pt-blog-eyebrow::after { width: 20px; }
		.pt-blog-headline { font-size: 30px; letter-spacing: -0.5px; }
		.pt-blog-lead { font-size: 15px; }
		.pt-blog-card-image { aspect-ratio: 16 / 11; }
	}
	</style>
	<?php
}

/* ----------------------------------------------------------------------
 * Single-post template polish — apply matching brand styling to
 * individual post pages so reading from the grid into a post feels
 * like one consistent surface. Light overrides only.
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', 'pethoven_single_post_css', 41 );

function pethoven_single_post_css() {
	if ( is_admin() || ! is_singular( 'post' ) ) {
		return;
	}
	?>
	<style id="pt-single-post-css">
	body.single-post .entry-content {
		max-width: 720px;
		margin: 0 auto;
		font-size: 16.5px;
		line-height: 1.75;
		color: #2a2a2a;
		padding: 24px 24px 64px;
	}
	body.single-post .entry-content h2 {
		font-family: Georgia, 'Times New Roman', serif;
		font-size: 26px;
		font-weight: 800;
		letter-spacing: -0.3px;
		margin: 44px 0 16px;
		color: #1a1a1a;
	}
	body.single-post .entry-content p {
		margin: 0 0 18px;
	}
	body.single-post .entry-content a {
		color: #6a9739;
		text-decoration: underline;
		text-decoration-thickness: 1.5px;
		text-underline-offset: 2px;
	}
	body.single-post .entry-content a:hover { color: #1a3a2a; }
	body.single-post .entry-content ul,
	body.single-post .entry-content ol {
		margin: 0 0 22px;
		padding-left: 22px;
	}
	body.single-post .entry-content li {
		margin-bottom: 8px;
	}
	body.single-post .entry-content hr {
		border: 0;
		border-top: 1px solid #e8e8e0;
		margin: 36px 0;
	}
	body.single-post .entry-title {
		font-family: Georgia, 'Times New Roman', serif;
		font-weight: 800;
		letter-spacing: -0.5px;
		font-size: 36px !important;
		line-height: 1.15;
		max-width: 720px;
		margin: 0 auto 16px;
		text-align: left;
		padding: 0 24px;
	}
	body.single-post .ast-article-single { padding-top: 32px; }
	body.single-post .post-thumb-img-content img {
		max-width: 720px;
		margin: 0 auto;
		border-radius: 16px;
	}
	@media (max-width: 600px) {
		body.single-post .entry-title { font-size: 28px !important; }
		body.single-post .entry-content { font-size: 15.5px; padding: 20px 18px 48px; }
		body.single-post .entry-content h2 { font-size: 22px; }
	}
	</style>
	<?php
}
