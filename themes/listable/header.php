<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Listable
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-mapbox-token="<?php echo listable_get_option('mapbox_token', ''); ?>" data-mapbox-style="<?php echo listable_get_option('mapbox_style', ''); ?>">
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'listable' ); ?></a>

	<header id="masthead" class="site-header  <?php if( is_page_template( 'page-templates/front_page.php' ) && (listable_get_option( 'header_transparent', true ) == true) ) echo 'header--transparent'; ?>" role="banner">
		<?php if ( function_exists( 'jetpack_has_site_logo' ) && jetpack_has_site_logo() ) { // display the Site Logo if present ?>
			<div class="site-branding  site-branding--image">
				<?php jetpack_the_site_logo(); ?>
			</div>
		<?php } elseif ( is_front_page() && is_home() ) { ?>
			<div class="site-branding">
				<h1 class="site-title  site-title--text"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			</div><!-- .site-branding -->
		<?php } else { ?>
			<div class="site-branding">
				<h1 class="site-title  site-title--text"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			</div><!-- .site-branding -->
		<?php } ?>

		<form class="search-form  js-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
			<input type="hidden" name="post_type" value="job_listing" />
			<?php
				$has_search_menu = false;
				if ( has_nav_menu( 'search_suggestions' ) ) $has_search_menu = true;
			?>
			<div class="search-field-wrapper<?php if ( $has_search_menu ) echo '  has--menu'; ?>">
				<input class="search-field  js-search-mobile-field  js-search-suggestions-field" type="text" name="s" id="s" placeholder="<?php esc_html_e( 'What are you looking for?', 'listable' ); ?>" autocomplete="off" value="<?php the_search_query(); ?>"/>
				<?php wp_nav_menu( array(
					'container' => false,
					'theme_location' => 'search_suggestions',
					'menu_class' => 'search-suggestions-menu',
					'fallback_cb'     => false,
				) ); ?>
			</div>
			<span class="search-trigger--mobile  js-search-trigger-mobile">
				<?php get_template_part( 'assets/svg/search-icon-mobile-svg' ); ?>
				<?php get_template_part( 'assets/svg/close-icon-svg' ); ?>
			</span>
			<button class="search-submit  js-search-mobile-submit" name="submit" id="searchsubmit">
				<?php get_template_part( 'assets/svg/search-icon-svg' ); ?>
			</button>
		</form>

		<button class="menu-trigger  menu--open  js-menu-trigger">
			<?php get_template_part( 'assets/svg/menu-bars-svg' ); ?>
		</button>
		<nav id="site-navigation" class="menu-wrapper" role="navigation">
			<button class="menu-trigger  menu--close  js-menu-trigger">
				<?php get_template_part( 'assets/svg/close-icon-svg' ); ?>
			</button>
			<?php wp_nav_menu( array(
				'container' => false,
				'theme_location' => 'primary',
				'menu_class' => 'primary-menu',
			) ); ?>
		</nav>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
