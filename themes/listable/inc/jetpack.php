<?php
/**
 * Jetpack Compatibility File - load the theme's duplicate files if Jetpack is not present
 * This way we provide a smooth transition to those that decide to use Jetpack
 * See: http://jetpack.me/
 *
 * @package Timber
 * @since Timber 1.0
 */
function listable_load_jetpack_compatibility() {

	//first test if Jetpack is present and activated
	// only if it is not present load the duplicated code from the theme
	if ( ! class_exists( 'Jetpack' ) ) {
		//these are safe as they do their own house cleaning
		require_once get_template_directory() . '/inc/jetpack/site-logo.php';
		//this is not safe -- needed to prefix the functions
		require_once get_template_directory() . '/inc/jetpack/responsive-videos.php';
	}
}
add_action( 'after_setup_theme', 'listable_load_jetpack_compatibility' );

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function listable_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'listable_infinite_scroll_render',
		'footer'    => 'page',
	) );


	/**
	 * Add theme support for site logo
	 *
	 * First, it's the image size we want to use for the logo thumbnails
	 * Second, the 2 classes we want to use for the "Display Header Text" Customizer logic
	 */
	add_theme_support( 'site-logo', array(
		'size'        => 'listable-site-logo',
		'header-text' => array(
			'site-title',
			'site-description-text',
		)
	) );

	add_image_size( 'listable-site-logo', 1360, 600, false );
	add_theme_support( 'jetpack-responsive-videos' );

} // end function listable_jetpack_setup
add_action( 'after_setup_theme', 'listable_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function listable_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function listable_infinite_scroll_render

function listable_jetpack_remove_share_from_listings() {
	if ( is_post_type_archive('job_listing') || get_query_var( 'post_type' )  == 'job_listing' ) {
		remove_filter( 'the_content', 'sharing_display', 19 );
		remove_filter( 'the_excerpt', 'sharing_display', 19 );
		if ( class_exists( 'Jetpack_Likes' ) ) {
			remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
		}
	}
}

add_action( 'loop_start', 'listable_jetpack_remove_share_from_listings' );