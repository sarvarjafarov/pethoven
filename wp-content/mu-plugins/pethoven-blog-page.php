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
 * Single-post: server-side cleanup.
 *
 *   1. Close comments + pings on all posts (brand blog, not a forum)
 *   2. Replace author display name "admin" with "Pethoven" in meta
 *   3. Inject a "← Back to blog" link at the end of post content so
 *      the user can return to the index without a back-button
 * ---------------------------------------------------------------------- */

// (1) Close comments on every post — open or closed — so even existing
// posts with comments enabled in the DB don't render the form.
add_filter( 'comments_open',     'pethoven_blog_close_comments', 99, 2 );
add_filter( 'pings_open',        'pethoven_blog_close_comments', 99, 2 );
add_filter( 'comments_array',    '__return_empty_array', 99 );

function pethoven_blog_close_comments( $open, $post_id ) {
	if ( 'post' === get_post_type( $post_id ) ) {
		return false;
	}
	return $open;
}

// (2) Replace "admin" author display name in the front-end meta only
add_filter( 'the_author', 'pethoven_blog_replace_admin_author', 10, 1 );

function pethoven_blog_replace_admin_author( $name ) {
	if ( is_singular( 'post' ) && in_array( strtolower( (string) $name ), array( 'admin', 'administrator' ), true ) ) {
		return 'Pethoven';
	}
	return $name;
}

// (3) Prepend a meta strip + append back-to-blog CTA to single-post content
add_filter( 'the_content', 'pethoven_blog_wrap_content', 9999 );

function pethoven_blog_wrap_content( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$post_id    = get_the_ID();
	$cats       = get_the_category( $post_id );
	$cat        = ! empty( $cats ) ? $cats[0]->name : '';
	$date       = get_the_date( 'M j, Y', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) );
	$read_min   = max( 1, (int) round( $word_count / 220 ) );

	$meta = '<div class="pt-post-meta">';
	if ( $cat ) {
		$meta .= '<span class="pt-post-meta-cat">' . esc_html( $cat ) . '</span>';
	}
	$meta .= '<span class="pt-post-meta-dot" aria-hidden="true">·</span>';
	$meta .= '<span class="pt-post-meta-date">' . esc_html( $date ) . '</span>';
	$meta .= '<span class="pt-post-meta-dot" aria-hidden="true">·</span>';
	$meta .= '<span class="pt-post-meta-read">' . (int) $read_min . ' min read</span>';
	$meta .= '</div>';

	$back = '<div class="pt-post-back-row"><a class="pt-post-back" href="/sample-page/"><span aria-hidden="true">&larr;</span> All Care Guide posts</a></div>';

	return $meta . $content . $back;
}

/* ----------------------------------------------------------------------
 * Single-post: front-end CSS polish.
 *
 * Kills the leftover Astra/WP chrome that doesn't belong on a brand
 * blog (entry-meta byline, comments area, post-navigation, sidebar
 * stub) and restyles the surviving elements to match the rest of the
 * site (Georgia serif headline, 720px reading column, green inline
 * links, generous vertical rhythm).
 * ---------------------------------------------------------------------- */

add_action( 'wp_head', 'pethoven_single_post_css', 41 );

function pethoven_single_post_css() {
	if ( is_admin() || ! is_singular( 'post' ) ) {
		return;
	}
	?>
	<style id="pt-single-post-css">
	/* ----- HIDE all leftover Astra/WP chrome ----- */
	body.single-post .entry-meta,
	body.single-post .post-navigation,
	body.single-post .comments-area,
	body.single-post #comments,
	body.single-post #respond,
	body.single-post .ast-author-details,
	body.single-post .related-posts,
	body.single-post .ast-related-post,
	body.single-post #secondary {
		display: none !important;
	}

	/* ----- LAYOUT: ditch Astra's two-container chrome on posts.
	 *
	 * Scope to the content area only — earlier rule targeted
	 * `body.single-post .ast-container` which also matched the site
	 * header's container, breaking the logo + menu layout. The
	 * #content prefix limits us to .ast-container instances inside
	 * the main content region, leaving the header (which sits in
	 * .site-header above #content) untouched. ----- */
	body.single-post #content > .ast-container,
	body.single-post .site-content > .ast-container {
		max-width: 100% !important;
		padding: 0 !important;
	}
	body.single-post #primary,
	body.single-post #main {
		width: 100% !important;
		max-width: 100% !important;
		padding: 0 !important;
		margin: 0 !important;
		float: none !important;
		background: transparent !important;
		border: 0 !important;
	}
	body.single-post .ast-article-single,
	body.single-post .ast-article-post {
		background: transparent !important;
		padding: 0 !important;
		border: 0 !important;
		box-shadow: none !important;
		margin: 0 !important;
	}
	body.single-post #content {
		background: linear-gradient(180deg, #fbfbf7 0%, #ffffff 320px, #ffffff 100%) !important;
	}

	/* ----- POST HEADER:
	 * Reorder via flex so the TITLE renders before the featured
	 * image — editorial-blog convention (Medium, Substack, NYT) where
	 * the reader sees the headline first, then the supporting image.
	 * Cap image height so it never dominates the viewport.
	 * ----- */
	body.single-post .entry-header {
		max-width: 720px;
		margin: 56px auto 0;
		padding: 0 24px;
		text-align: left;
		display: flex;
		flex-direction: column;
	}
	body.single-post .entry-header .entry-title { order: 1; }
	body.single-post .entry-header .post-thumb-img-content,
	body.single-post .entry-header .post-thumbnail,
	body.single-post .entry-header .post-thumb {
		order: 2;
	}

	/* ----- TITLE (renders first) ----- */
	body.single-post .entry-title {
		font-family: Georgia, 'Times New Roman', serif !important;
		font-weight: 800 !important;
		font-style: normal !important;
		font-size: 38px !important;
		line-height: 1.18 !important;
		letter-spacing: -0.6px !important;
		color: #1a1a1a !important;
		margin: 0 0 20px !important;
		padding: 0;
	}

	/* ----- FEATURED IMAGE (renders below the title)
	 * Capped at 360px tall so it stays a supporting visual, not a
	 * page-hijacker. Wider aspect ratio (16:9) gives it a banner
	 * feel rather than a poster. ----- */
	body.single-post .post-thumb-img-content,
	body.single-post .post-thumbnail,
	body.single-post .post-thumb {
		display: block;
		max-width: 720px;
		margin: 8px auto 0 !important;
		border-radius: 18px;
		overflow: hidden;
		background: #f4f6ee;
		box-shadow: 0 18px 44px rgba(26, 58, 42, 0.10),
		            0 4px 12px rgba(26, 58, 42, 0.04);
	}
	body.single-post .post-thumb-img-content img,
	body.single-post .post-thumbnail img,
	body.single-post .post-thumb img {
		display: block;
		width: 100%;
		height: auto;
		max-height: 360px;
		aspect-ratio: 16 / 9;
		object-fit: cover;
		object-position: center;
		border-radius: 18px !important;
		max-width: 100% !important;
		margin: 0 !important;
	}

	/* ----- META STRIP (injected via the_content filter at the top
	 * of the body — category pill + date + reading time) ----- */
	.pt-post-meta {
		display: flex;
		align-items: center;
		gap: 10px;
		margin: 0 0 32px !important;
		padding: 16px 0 18px !important;
		border-bottom: 1px solid #f0f0ec;
		font-size: 12px;
		color: #8a8a8a;
		font-weight: 600;
		letter-spacing: 0.4px;
	}
	.pt-post-meta-cat {
		display: inline-flex;
		padding: 5px 12px;
		background: rgba(139, 195, 74, 0.14);
		color: #6a9739 !important;
		border-radius: 100px;
		font-size: 11px;
		font-weight: 800;
		letter-spacing: 1.6px;
		text-transform: uppercase;
	}
	.pt-post-meta-dot { color: #c8c8c8; }
	.pt-post-meta-date,
	.pt-post-meta-read { color: #8a8a8a; }

	/* ----- BODY CONTENT ----- */
	body.single-post .entry-content {
		max-width: 720px;
		margin: 0 auto 24px !important;
		padding: 0 24px 16px !important;
		font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
		font-size: 17px !important;
		line-height: 1.75 !important;
		color: #2a2a2a !important;
	}
	body.single-post .entry-content p {
		margin: 0 0 20px !important;
		font-size: 17px !important;
		line-height: 1.75 !important;
		color: #2a2a2a !important;
	}
	body.single-post .entry-content p:first-of-type {
		font-size: 18.5px !important;
		line-height: 1.7 !important;
		color: #1a1a1a !important;
	}
	body.single-post .entry-content h2 {
		font-family: Georgia, 'Times New Roman', serif !important;
		font-size: 27px !important;
		font-weight: 800 !important;
		line-height: 1.25 !important;
		letter-spacing: -0.3px !important;
		color: #1a1a1a !important;
		margin: 48px 0 18px !important;
	}
	body.single-post .entry-content h3 {
		font-size: 20px !important;
		font-weight: 700 !important;
		margin: 32px 0 12px !important;
		color: #1a1a1a !important;
	}
	body.single-post .entry-content a {
		color: #6a9739 !important;
		text-decoration: underline !important;
		text-decoration-thickness: 1.5px !important;
		text-underline-offset: 3px !important;
		font-weight: 600;
	}
	body.single-post .entry-content a:hover { color: #1a3a2a !important; }
	body.single-post .entry-content strong { color: #1a1a1a; font-weight: 700; }
	body.single-post .entry-content ul,
	body.single-post .entry-content ol {
		margin: 0 0 22px !important;
		padding-left: 24px !important;
	}
	body.single-post .entry-content li {
		margin-bottom: 8px;
		font-size: 17px;
		line-height: 1.7;
	}
	body.single-post .entry-content hr {
		border: 0 !important;
		border-top: 1px solid #e8e8e0 !important;
		margin: 40px auto !important;
		max-width: 120px;
	}
	body.single-post .entry-content blockquote {
		border-left: 3px solid #6a9739;
		padding: 4px 0 4px 20px;
		margin: 28px 0;
		font-style: italic;
		color: #3a3a3a;
		background: transparent;
	}

	/* ----- "BACK TO BLOG" link injected at content end ----- */
	.pt-post-back-row {
		max-width: 720px;
		margin: 40px auto 80px;
		padding: 24px;
		text-align: center;
		border-top: 1px solid #f0f0ec;
	}
	.pt-post-back {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 12px 24px;
		background: transparent;
		color: #1a1a1a !important;
		border: 1.5px solid #1a1a1a;
		border-radius: 100px;
		font-size: 12px;
		font-weight: 700;
		letter-spacing: 1.5px;
		text-transform: uppercase;
		text-decoration: none !important;
		transition: background 0.25s ease, color 0.25s ease, transform 0.25s ease;
	}
	.pt-post-back:hover {
		background: #1a1a1a;
		color: #ffffff !important;
		transform: translateY(-2px);
	}
	.pt-post-back span {
		transition: transform 0.25s ease;
	}
	.pt-post-back:hover span { transform: translateX(-3px); }

	/* ----- MOBILE ----- */
	@media (max-width: 740px) {
		body.single-post .entry-header { margin-top: 32px; padding: 0 18px; }
		body.single-post .entry-title { font-size: 28px !important; letter-spacing: -0.4px !important; }
		body.single-post .post-thumb-img-content,
		body.single-post .post-thumbnail,
		body.single-post .post-thumb { border-radius: 14px; margin-top: 4px !important; }
		body.single-post .post-thumb-img-content img,
		body.single-post .post-thumbnail img,
		body.single-post .post-thumb img {
			max-height: 240px;
			aspect-ratio: 4 / 3;
			border-radius: 14px !important;
		}
		.pt-post-meta {
			margin-bottom: 24px !important;
			padding: 14px 0 14px !important;
			flex-wrap: wrap;
			gap: 8px;
			font-size: 11.5px;
		}
		.pt-post-meta-cat { font-size: 10.5px; padding: 4px 10px; letter-spacing: 1.4px; }
		body.single-post .entry-content { font-size: 16px !important; padding: 0 18px 12px !important; }
		body.single-post .entry-content p,
		body.single-post .entry-content li { font-size: 16px !important; }
		body.single-post .entry-content p:first-of-type { font-size: 17px !important; }
		body.single-post .entry-content h2 { font-size: 22px !important; margin: 36px 0 14px !important; }
		body.single-post .entry-content h3 { font-size: 18px !important; }
	}
	</style>
	<?php
}
