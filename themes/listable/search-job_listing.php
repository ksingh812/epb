<?php
/**
 * Search only listings results archive
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listable
 */

get_header(); ?>

<div id="primary" class="content-area">
	<div class="entry-content">
		<main id="main" class="site-main" role="main">
			<?php echo do_shortcode('[jobs keywords="'. get_search_query() .'" show_filters="true" ]'); ?>
		</main><!-- #main -->
	</div>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer(); ?>