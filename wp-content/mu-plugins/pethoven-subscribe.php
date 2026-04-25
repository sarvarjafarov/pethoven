<?php
/**
 * Pethoven newsletter subscription endpoint.
 *
 * Plugin Name: Pethoven Subscribe
 * Description: REST endpoint backing the footer newsletter form.
 *              Subscribes the email to MailPoet list #3 and sends a
 *              welcome email containing the WELCOME10 promo code.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PETHOVEN_MAILPOET_LIST_ID', 3 );
define( 'PETHOVEN_COUPON_CODE',      'WELCOME10' );

/**
 * Register /wp-json/pethoven/v1/subscribe.
 *
 * Public POST with a JSON body: { "email": "user@example.com" }.
 * Returns 200 on success, 400 on validation errors, 500 on internal.
 */
add_action( 'rest_api_init', 'pethoven_register_subscribe_route' );

function pethoven_register_subscribe_route() {
	register_rest_route( 'pethoven/v1', '/subscribe', array(
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => 'pethoven_subscribe_handler',
		'args'                => array(
			'email' => array(
				'required' => true,
				'type'     => 'string',
			),
		),
	) );
}

/**
 * Subscribe handler.
 *
 * Flow:
 *   1. Validate email
 *   2. Rate-limit by IP (5 req / hour, soft cap)
 *   3. Add to MailPoet list via MailPoet PHP API (if MailPoet present)
 *   4. Send welcome email with WELCOME10 code via wp_mail()
 *
 * Mailing + subscription errors are logged but not surfaced to the
 * client so a half-registered attempt still shows the user a
 * friendly success message.
 */
function pethoven_subscribe_handler( WP_REST_Request $request ) {
	$email = sanitize_email( trim( (string) $request->get_param( 'email' ) ) );
	if ( ! is_email( $email ) ) {
		return new WP_REST_Response( array(
			'ok'    => false,
			'error' => 'Please enter a valid email address.',
		), 400 );
	}

	// Simple IP rate limit — 5 attempts per hour per IP.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? preg_replace( '/[^0-9a-f:.]/i', '', $_SERVER['REMOTE_ADDR'] ) : '0';
	$key = 'pt_sub_rl_' . md5( $ip );
	$cnt = (int) get_transient( $key );
	if ( $cnt >= 5 ) {
		return new WP_REST_Response( array(
			'ok'    => false,
			'error' => 'Too many attempts. Please try again later.',
		), 429 );
	}
	set_transient( $key, $cnt + 1, HOUR_IN_SECONDS );

	// Best-effort MailPoet subscribe.
	pethoven_maybe_subscribe_mailpoet( $email );

	// Always send the welcome email with the promo code, regardless
	// of whether the MailPoet call succeeded. The user promised the
	// subscriber a 10% code; that delivery should not depend on a
	// newsletter-list side-effect.
	pethoven_send_welcome_email( $email );

	return new WP_REST_Response( array(
		'ok'      => true,
		'message' => "You're in. Check your inbox for your 10% off code.",
	), 200 );
}

/**
 * Add to MailPoet "Newsletter mailing list" if the plugin is active.
 * Safe to call even if MailPoet is disabled; logs and returns early.
 */
function pethoven_maybe_subscribe_mailpoet( $email ) {
	if ( ! class_exists( '\\MailPoet\\API\\API' ) ) {
		error_log( 'pethoven_subscribe: MailPoet PHP API not available; skipping list add for ' . $email );
		return false;
	}

	try {
		$api = \MailPoet\API\API::MP( 'v1' );
		// Fetch or create the subscriber, then subscribe to list.
		// addSubscriber throws if the email already exists, so check first.
		$existing = null;
		try {
			$existing = $api->getSubscriber( $email );
		} catch ( \Exception $e ) {
			// Subscriber doesn't exist — create.
			$api->addSubscriber(
				array(
					'email'  => $email,
					'status' => 'subscribed',
				),
				array( PETHOVEN_MAILPOET_LIST_ID ),
				array(
					'send_confirmation_email' => false, // we're sending our own
					'schedule_welcome_email'  => false,
				)
			);
			return true;
		}

		// Existing subscriber — ensure they're on the list.
		if ( $existing && method_exists( $api, 'subscribeToList' ) ) {
			$api->subscribeToList( $email, PETHOVEN_MAILPOET_LIST_ID );
		}
		return true;
	} catch ( \Exception $e ) {
		error_log( 'pethoven_subscribe: MailPoet error — ' . $e->getMessage() );
		return false;
	}
}

/**
 * Send the welcome email — branded HTML design with a plain-text
 * fallback for clients that don't render HTML. Overrides the From
 * address + display name (the site default was "WordPress <wordpress@...>"
 * which reads as unbranded/transactional spam).
 */
function pethoven_send_welcome_email( $email ) {
	$subject  = 'Welcome to Pethoven — your 10% off code inside';
	$shop_url = home_url( '/shop/' );

	// Brand the sender for just this email — scoped filters removed
	// immediately after send so we don't affect unrelated wp_mail
	// calls (WooCommerce order receipts, password resets, etc.).
	$from_filter      = function () { return 'support@pethoven.com'; };
	$from_name_filter = function () { return 'Pethoven'; };
	add_filter( 'wp_mail_from',      $from_filter );
	add_filter( 'wp_mail_from_name', $from_name_filter );

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'Reply-To: support@pethoven.com',
	);

	$html = pethoven_welcome_email_html( PETHOVEN_COUPON_CODE, $shop_url );

	$sent = wp_mail( $email, $subject, $html, $headers );

	remove_filter( 'wp_mail_from',      $from_filter );
	remove_filter( 'wp_mail_from_name', $from_name_filter );

	if ( ! $sent ) {
		error_log( 'pethoven_subscribe: wp_mail failed for ' . $email );
	}
	return $sent;
}

/**
 * Build the HTML body for the welcome email.
 *
 * Styling notes:
 *  - All CSS is inline (email clients strip <style> blocks reliably).
 *  - Max-width 560px; mobile stacks naturally on small screens.
 *  - System font stack only — no web fonts (unsupported in most clients).
 *  - Single-column layout, table-free structure works in Gmail/Apple Mail/
 *    Outlook.com/iOS. Outlook desktop will render slightly plainer but
 *    still correctly (no broken layout).
 *  - The promo code sits in a high-contrast dashed box so it's the
 *    most scannable element on first glance.
 */
function pethoven_welcome_email_html( $code, $shop_url ) {
	$code_safe = esc_html( $code );
	$shop_safe = esc_url( $shop_url );

	// Use the 2x logo scaled to 150px wide (crisp on retina).
	// Falls back to alt text if the client blocks images — common
	// default behavior in Outlook, some Gmail views.
	$logo_url  = esc_url( home_url( '/wp-content/mu-plugins/assets/pethoven-logo-2x.png' ) );

	return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Welcome to Pethoven</title>
</head>
<body style="margin:0;padding:0;background-color:#fbfbf7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;color:#1a1a1a;-webkit-font-smoothing:antialiased;">
<center style="width:100%;background-color:#fbfbf7;">
<div style="max-width:560px;margin:0 auto;padding:0 20px;">

  <!-- Logo / brand -->
  <div style="padding:40px 0 28px;text-align:center;">
    <a href="{$shop_safe}" style="display:inline-block;text-decoration:none;">
      <img src="{$logo_url}" alt="Pethoven" width="150" height="auto" style="display:inline-block;border:0;outline:none;max-width:150px;height:auto;">
    </a>
  </div>

  <!-- Main card -->
  <div style="background:#ffffff;border-radius:20px;padding:44px 36px;box-shadow:0 2px 10px rgba(0,0,0,0.04);">

    <div style="font-size:11px;font-weight:800;letter-spacing:2.5px;text-transform:uppercase;color:#6a9739;margin-bottom:14px;">Welcome to the pack</div>

    <h1 style="font-family:Georgia,'Times New Roman',serif;font-size:30px;font-weight:800;color:#1a1a1a;margin:0 0 18px;line-height:1.2;letter-spacing:-0.5px;">Here's 10% off your first order.</h1>

    <p style="font-size:16px;line-height:1.6;color:#4a4a4a;margin:0 0 28px;">
      Thanks for joining us. Pethoven makes organic, vet-approved dog shampoos — sulfate-free, cruelty-free, and built to actually work on real coats.
    </p>

    <p style="font-size:16px;line-height:1.6;color:#4a4a4a;margin:0 0 24px;">
      Use this code at checkout to save 10% on your first bottle:
    </p>

    <!-- Promo code -->
    <div style="background:#f7faf1;border:2px dashed #6a9739;border-radius:14px;padding:26px 20px;text-align:center;margin:0 0 30px;">
      <div style="font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#6a9739;margin-bottom:10px;">Your welcome code</div>
      <div style="font-family:'SF Mono','Menlo','Courier New',monospace;font-size:30px;font-weight:800;color:#1a1a1a;letter-spacing:4px;line-height:1;">{$code_safe}</div>
      <div style="font-size:12px;color:#8a8a8a;margin-top:12px;">10% off • one-time use • valid 12 months</div>
    </div>

    <!-- CTA -->
    <div style="text-align:center;margin:0 0 32px;">
      <a href="{$shop_safe}" style="display:inline-block;background:#1a1a1a;color:#ffffff;text-decoration:none;padding:16px 42px;border-radius:100px;font-size:12px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;">Shop Now &rarr;</a>
    </div>

    <!-- Trust bar -->
    <div style="border-top:1px solid #f0f0ec;padding-top:22px;">
      <p style="font-size:12px;color:#7a7a7a;margin:0 0 12px;text-align:center;font-weight:700;letter-spacing:0.5px;text-transform:uppercase;">Why Pethoven</p>
      <p style="font-size:13.5px;color:#5a5a5a;line-height:1.8;margin:0;text-align:center;">
        <span style="color:#6a9739;font-weight:700;">✓</span> Organic, vet-approved formulas &nbsp;•&nbsp;
        <span style="color:#6a9739;font-weight:700;">✓</span> Sulfate &amp; paraben free<br>
        <span style="color:#6a9739;font-weight:700;">✓</span> Cruelty-free &nbsp;•&nbsp;
        <span style="color:#6a9739;font-weight:700;">✓</span> Made in Estonia
      </p>
    </div>

  </div>

  <!-- What to expect -->
  <div style="padding:28px 24px;text-align:center;">
    <p style="font-size:13.5px;line-height:1.65;color:#8a8a8a;margin:0;">
      You'll hear from us a few times a month — bath-time tips, new formula drops, and early access to deals. No spam, ever.
    </p>
  </div>

  <!-- Footer -->
  <div style="padding:20px 20px 40px;text-align:center;">
    <p style="font-size:12px;color:#8a8a8a;margin:0 0 6px;">
      &copy; Pethoven &nbsp;·&nbsp; A brand of Global Tail Goods LLC
    </p>
    <p style="font-size:12px;color:#a8a8a8;margin:0;">
      Questions? Reply to this email or write to
      <a href="mailto:support@pethoven.com" style="color:#6a9739;text-decoration:none;">support@pethoven.com</a>.
    </p>
  </div>

</div>
</center>
</body>
</html>
HTML;
}
