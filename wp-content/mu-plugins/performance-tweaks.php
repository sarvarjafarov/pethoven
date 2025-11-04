<?php
/**
 * Must-use plugin with lightweight performance optimizations for local development.
 */

add_action( 'init', function () {
	if ( is_admin() ) {
		return;
	}

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	add_filter( 'emoji_svg_url', '__return_false' );

	add_action( 'wp_enqueue_scripts', function () {
		wp_dequeue_style( 'dashicons' );
		wp_dequeue_style( 'astra-google-fonts' );
		wp_dequeue_script( 'comment-reply' );
		wp_deregister_script( 'wp-embed' );
	}, 20 );
} );

add_filter( 'heartbeat_settings', function ( $settings ) {
	if ( ! is_admin() ) {
		$settings['interval'] = 60;
	} else {
		$settings['interval'] = 30;
	}

	return $settings;
} );

add_filter( 'should_load_remote_block_patterns', '__return_false' );
