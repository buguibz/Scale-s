<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Edin
 */

function edin_jetpack_setup() {
	/**
	 * Add theme support for Infinite Scroll.
	 * See: http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'main',
		'footer_widgets' => array(
			'sidebar-2',
			'sidebar-3',
			'sidebar-4',
		),
		'footer'         => 'page',
	) );

	/**
	 * Add theme support for Responsive Videos.
	 */
	add_theme_support( 'jetpack-responsive-videos' );

	/**
	 * Add theme support for Logo upload.
	 */
	add_image_size( 'edin-logo', 583, 192 );
	add_theme_support( 'site-logo', array( 'size' => 'edin-logo' ) );
}
add_action( 'after_setup_theme', 'edin_jetpack_setup' );

/**
 * Return early if Site Logo is not available.
 */
function edin_the_site_logo() {
	if ( ! function_exists( 'jetpack_the_site_logo' ) ) {
		return;
	} else {
		jetpack_the_site_logo();
	}
}

/**
 * Remove sharedaddy from excerpt.
 */
function edin_remove_sharedaddy() {
    remove_filter( 'the_excerpt', 'sharing_display', 19 );
}
add_action( 'loop_start', 'edin_remove_sharedaddy' );