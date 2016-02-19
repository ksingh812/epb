<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listable
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div class="entry-content">
			<main id="main" class="site-main" role="main">
			<?php
			$term =	$wp_query->queried_object;
			if ( isset( $term->slug) ) {
				$shortcode = '[jobs tags="' . $term->slug . '" show_tags="true"]';
				echo do_shortcode(  $shortcode );
			} ?>
			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php

get_sidebar();
get_footer(); ?>
