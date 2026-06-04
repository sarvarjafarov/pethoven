<?php
/**
 * Pethoven Emails — branded WooCommerce transactional emails.
 *
 * Plugin Name: Pethoven Emails
 * Description: Replaces WooCommerce's default email wrapper with a
 *              Pethoven-branded design — logo header, serif welcome,
 *              cream/green palette matching the site, brand-promise
 *              footer, organic order-table styling. Applies to every
 *              WC transactional email (processing/completed/on-hold/
 *              refunded/customer-note/reset-password/etc.) — they all
 *              share the same `woocommerce_email_header` /
 *              `woocommerce_email_footer` hooks.
 *
 * Design references
 *   - Outer bg cream #fbfbf7 (matches site body wash)
 *   - Card white with 24px radius + subtle shadow
 *   - Hero panel dark-green #1a3a2a → #26543d gradient (matches the
 *     site's promo block + footer copybar)
 *   - Accent green #6a9739 (--ast-global-color-1)
 *   - Serif headlines (Georgia) to mirror the brand serif used on
 *     /shop/ "Built for dogs who deserve better" surface
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ----------------------------------------------------------------------
 * 1. Brand constants
 * ---------------------------------------------------------------------- */

function pethoven_email_logo_url() {
	// 2x asset rendered at 160px wide in the email — sharp on retina,
	// served from our own domain so DKIM-aligned with the From header.
	return 'https://pethoven.com/wp-content/mu-plugins/assets/pethoven-logo-2x.png';
}

function pethoven_email_colors() {
	return array(
		'bg_outer'    => '#fbfbf7',
		'card_bg'     => '#ffffff',
		'hero_bg_a'   => '#1a3a2a',
		'hero_bg_b'   => '#26543d',
		'hero_accent' => '#c7e09a',
		'text'        => '#1a1a1a',
		'text_soft'   => '#5a5a5a',
		'green'       => '#6a9739',
		'green_pale'  => '#f4f6ee',
		'border'      => '#f0f0ec',
	);
}

/* ----------------------------------------------------------------------
 * 2. Detach WC default header + footer renderers, attach ours.
 *
 *    WC core hooks WC_Emails::email_header() and ::email_footer() onto
 *    the two action hooks at priority 10. We swap those for our own
 *    callbacks at the same priority. Wrapped in a late init so the
 *    mailer singleton is guaranteed initialized.
 * ---------------------------------------------------------------------- */

add_action( 'init', 'pethoven_emails_swap_wrapper', 99 );

function pethoven_emails_swap_wrapper() {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}
	$mailer = WC()->mailer();
	remove_action( 'woocommerce_email_header', array( $mailer, 'email_header' ), 10 );
	remove_action( 'woocommerce_email_footer', array( $mailer, 'email_footer' ), 10 );

	add_action( 'woocommerce_email_header', 'pethoven_email_header', 10, 2 );
	add_action( 'woocommerce_email_footer', 'pethoven_email_footer', 10, 1 );
}

/* ----------------------------------------------------------------------
 * 3. Branded header — DOCTYPE + outer table + logo + hero with the
 *    email-specific heading WC passes in. Leaves the body cell open;
 *    the email template then dumps its content into it. The footer
 *    callback below closes everything.
 * ---------------------------------------------------------------------- */

function pethoven_email_header( $email_heading, $email = null ) {
	$c    = pethoven_email_colors();
	$logo = pethoven_email_logo_url();
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="x-apple-disable-message-reformatting" />
<title><?php echo esc_html( wp_strip_all_tags( $email_heading ) ); ?></title>
<!--[if mso]>
<style>* { font-family: Arial, sans-serif !important; }</style>
<![endif]-->
</head>
<body style="margin:0;padding:0;background-color:<?php echo esc_attr( $c['bg_outer'] ); ?>;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:<?php echo esc_attr( $c['text'] ); ?>;-webkit-font-smoothing:antialiased;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">

<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:<?php echo esc_attr( $c['bg_outer'] ); ?>;">
<tr><td align="center" style="padding:36px 16px;">

<!-- Card -->
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;background-color:<?php echo esc_attr( $c['card_bg'] ); ?>;border-radius:24px;overflow:hidden;box-shadow:0 4px 24px rgba(26,58,42,0.06);">

<!-- Logo -->
<tr><td align="center" style="padding:32px 36px 18px;">
  <img src="<?php echo esc_url( $logo ); ?>" alt="Pethoven" width="160" style="display:block;width:160px;max-width:160px;height:auto;border:0;outline:none;text-decoration:none;" />
  <div style="margin-top:14px;font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:<?php echo esc_attr( $c['green'] ); ?>;">— Built for dogs who deserve better —</div>
</td></tr>

<!-- Hero heading -->
<tr><td style="padding:8px 36px 0;">
  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background:linear-gradient(135deg,<?php echo esc_attr( $c['hero_bg_a'] ); ?> 0%,<?php echo esc_attr( $c['hero_bg_b'] ); ?> 100%);background-color:<?php echo esc_attr( $c['hero_bg_a'] ); ?>;border-radius:18px;">
  <tr><td align="center" style="padding:32px 28px 30px;">
    <div style="font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:800;line-height:1.2;color:#ffffff;letter-spacing:-0.4px;margin:0;">
      <?php echo wp_kses_post( $email_heading ); ?>
    </div>
  </td></tr>
  </table>
</td></tr>

<!-- Body open — WC dumps the email content here -->
<tr><td style="padding:30px 36px 4px;font-size:14.5px;line-height:1.65;color:<?php echo esc_attr( $c['text'] ); ?>;">
	<?php
}

/* ----------------------------------------------------------------------
 * 4. Branded footer — closes the body cell + adds brand promises,
 *    sign-off, and the legal-footer outside the white card.
 * ---------------------------------------------------------------------- */

function pethoven_email_footer( $email = null ) {
	$c = pethoven_email_colors();
	?>
</td></tr>

<!-- Brand promises -->
<tr><td style="padding:18px 36px 8px;">
  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-top:1px solid <?php echo esc_attr( $c['border'] ); ?>;">
  <tr><td style="padding-top:22px;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td width="50%" valign="top" style="padding:0 8px 12px 0;font-size:12.5px;line-height:1.5;color:<?php echo esc_attr( $c['text_soft'] ); ?>;">
        <span style="color:<?php echo esc_attr( $c['green'] ); ?>;font-weight:800;">✓</span>&nbsp; <strong style="color:<?php echo esc_attr( $c['text'] ); ?>;">Organic & vet-approved</strong>
      </td>
      <td width="50%" valign="top" style="padding:0 0 12px 8px;font-size:12.5px;line-height:1.5;color:<?php echo esc_attr( $c['text_soft'] ); ?>;">
        <span style="color:<?php echo esc_attr( $c['green'] ); ?>;font-weight:800;">✓</span>&nbsp; <strong style="color:<?php echo esc_attr( $c['text'] ); ?>;">Free shipping over $25</strong>
      </td>
    </tr>
    <tr>
      <td width="50%" valign="top" style="padding:0 8px 0 0;font-size:12.5px;line-height:1.5;color:<?php echo esc_attr( $c['text_soft'] ); ?>;">
        <span style="color:<?php echo esc_attr( $c['green'] ); ?>;font-weight:800;">✓</span>&nbsp; <strong style="color:<?php echo esc_attr( $c['text'] ); ?>;">30-day guarantee</strong>
      </td>
      <td width="50%" valign="top" style="padding:0 0 0 8px;font-size:12.5px;line-height:1.5;color:<?php echo esc_attr( $c['text_soft'] ); ?>;">
        <span style="color:<?php echo esc_attr( $c['green'] ); ?>;font-weight:800;">✓</span>&nbsp; <strong style="color:<?php echo esc_attr( $c['text'] ); ?>;">Ships in 2 business days</strong>
      </td>
    </tr>
    </table>
  </td></tr>
  </table>
</td></tr>

<!-- Sign off -->
<tr><td style="padding:4px 36px 32px;">
  <div style="font-size:13.5px;line-height:1.65;color:<?php echo esc_attr( $c['text_soft'] ); ?>;border-top:1px solid <?php echo esc_attr( $c['border'] ); ?>;padding-top:22px;">
    Questions about your order? Hit reply or write to <a href="mailto:support@pethoven.com" style="color:<?php echo esc_attr( $c['green'] ); ?>;text-decoration:none;font-weight:600;">support@pethoven.com</a>. A real human gets back within one business day.
    <br><br>
    Thanks for being part of the pack,<br>
    <strong style="color:<?php echo esc_attr( $c['text'] ); ?>;">— The Pethoven team</strong>
  </div>
</td></tr>

</table>
<!-- /card -->

<!-- Legal footer -->
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;margin-top:22px;">
<tr><td align="center" style="padding:0 20px;font-size:11.5px;color:#8a8a8a;line-height:1.6;">
  © <?php echo esc_html( gmdate( 'Y' ) ); ?> Pethoven &nbsp;·&nbsp; A brand of <strong style="color:#6a6a6a;">Global Tail Goods LLC</strong><br>
  8 The Green, STE R, Dover, DE 19901, USA &nbsp;·&nbsp; <a href="https://pethoven.com" style="color:#8a8a8a;text-decoration:underline;">pethoven.com</a><br>
  <span style="color:#a8a8a8;">Currently shipping to NY &middot; NJ &middot; MA &middot; DC &middot; CT &middot; PA</span>
</td></tr>
</table>

</td></tr></table>
</body>
</html>
	<?php
}

/* ----------------------------------------------------------------------
 * 5. CSS for the body content (between header + footer). WC injects
 *    its templates into <td> above; the body section uses these styles
 *    plus WC's own classes (h1, h2, h3, p, table.td, address, etc.).
 *    Filter receives the existing CSS string and the email object —
 *    we append our overrides so they win the cascade.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_email_styles', 'pethoven_email_styles_inject', 99, 2 );

function pethoven_email_styles_inject( $css, $email = null ) {
	$c = pethoven_email_colors();
	$brand_css = "
/* === Pethoven email body overrides === */
body { background-color: {$c['bg_outer']} !important; }

#wrapper, #template_container, #template_header, #template_body, #template_footer, #template_footer td {
	background-color: transparent !important;
	border: 0 !important;
	box-shadow: none !important;
}

/* Typography */
h1, h2, h3, .templateColumnContainer h1, .templateColumnContainer h2 {
	font-family: Georgia, 'Times New Roman', serif !important;
	color: {$c['text']} !important;
	font-weight: 800 !important;
	letter-spacing: -0.3px !important;
	background: transparent !important;
	border: 0 !important;
}
h1 { font-size: 21px !important; line-height: 1.25 !important; margin: 0 0 12px !important; padding: 0 !important; }
h2 { font-size: 17px !important; line-height: 1.3 !important; margin: 22px 0 10px !important; }
h3 { font-size: 15px !important; line-height: 1.4 !important; margin: 18px 0 8px !important; }

p, td, li, body, #body_content_inner {
	font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
	font-size: 14.5px !important;
	line-height: 1.65 !important;
	color: {$c['text']} !important;
}
p { margin: 0 0 14px !important; }

/* Order items table — WC ships this with class .td (yes, classname is just 'td') */
table.td {
	border-collapse: collapse !important;
	width: 100% !important;
	border: 1px solid {$c['border']} !important;
	border-radius: 12px !important;
	background: #ffffff !important;
	margin-top: 6px !important;
}
table.td th, table.td td {
	padding: 12px 14px !important;
	text-align: left !important;
	font-size: 13.5px !important;
	color: {$c['text']} !important;
	line-height: 1.5 !important;
	border-color: {$c['border']} !important;
	background: transparent !important;
}
table.td thead th {
	background: {$c['green_pale']} !important;
	font-size: 11px !important;
	font-weight: 800 !important;
	letter-spacing: 1.5px !important;
	text-transform: uppercase !important;
	color: {$c['text_soft']} !important;
	border-bottom: 1px solid {$c['border']} !important;
}
table.td tfoot th, table.td tfoot td {
	background: transparent !important;
	text-transform: none !important;
	letter-spacing: 0 !important;
	font-size: 14px !important;
	color: {$c['text_soft']} !important;
	font-weight: 600 !important;
}
table.td tfoot tr:last-child th, table.td tfoot tr:last-child td {
	font-size: 17px !important;
	font-weight: 800 !important;
	color: {$c['text']} !important;
	border-top: 2px solid {$c['text']} !important;
	padding-top: 14px !important;
	padding-bottom: 14px !important;
}

/* Product image inside the order table */
table.td td img { border-radius: 8px !important; }

/* Address card */
address {
	font-style: normal !important;
	font-size: 13.5px !important;
	line-height: 1.55 !important;
	color: {$c['text_soft']} !important;
	background: {$c['green_pale']} !important;
	padding: 14px 16px !important;
	border-radius: 12px !important;
	border: 1px solid {$c['border']} !important;
}

/* Anything WC outputs that's a link */
a, a:link, a:visited {
	color: {$c['green']} !important;
	text-decoration: none !important;
	font-weight: 600;
}
a:hover { text-decoration: underline !important; }
";
	return $css . "\n" . $brand_css;
}

/* ----------------------------------------------------------------------
 * 6. Friendlier subject lines for the customer-facing emails.
 *    Default WC subjects read like internal-tool copy ("[Pethoven]:
 *    You've got a new order"). Override to brand voice.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_email_subject_customer_processing_order', 'pethoven_email_subject_processing', 10, 2 );
add_filter( 'woocommerce_email_subject_customer_completed_order',  'pethoven_email_subject_completed',  10, 2 );
add_filter( 'woocommerce_email_subject_customer_on_hold_order',    'pethoven_email_subject_on_hold',    10, 2 );

function pethoven_email_subject_processing( $subject, $order ) {
	return sprintf( 'Order #%s received — thanks for joining the pack 🐾', $order->get_order_number() );
}
function pethoven_email_subject_completed( $subject, $order ) {
	return sprintf( 'Order #%s is on its way 📦', $order->get_order_number() );
}
function pethoven_email_subject_on_hold( $subject, $order ) {
	return sprintf( 'Order #%s — quick check before we ship', $order->get_order_number() );
}
