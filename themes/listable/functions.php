<?php
/**
 * Listable functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Listable
 */

if ( ! function_exists( 'listable_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function listable_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Listable, use a find and replace
		 * to change 'listable' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'listable', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Used for Listing Cards
		// Max Width of 450px
		add_image_size( 'listable-card-image', 450, 9999, false );

		// Used for Single Listing carousel images
		// Max Height of 800px
		add_image_size( 'listable-carousel-image', 9999, 800, false );

		// Used for Full Width (fill) images on Pages and Listings
		// Max Width of 2700px
		add_image_size( 'listable-featured-image', 2700, 9999, false );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary'            => esc_html__( 'Primary Menu', 'listable' ),
			'footer_menu'        => esc_html__( 'Footer Menu', 'listable' ),
			'search_suggestions' => esc_html__( 'Search Menu', 'listable' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * No support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', array() );

		add_theme_support( 'job-manager-templates' );

		add_theme_support( 'woocommerce' );

		add_post_type_support( 'page', 'excerpt' );

		remove_post_type_support( 'page', 'thumbnail' );

		// custom javascript handlers - make sure it is the last one added
		add_action( 'wp_head', 'listable_load_custom_js_header', 999 );
		add_action( 'wp_footer', 'listable_load_custom_js_footer', 999 );

		/*
		 * Add editor custom style to make it look more like the frontend
		 * Also enqueue the custom Google Fonts and self-hosted ones
		 */
		add_editor_style( array( 'editor-style.css' ) );
	}
endif; // listable_setup
add_action( 'after_setup_theme', 'listable_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function listable_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'listable_content_width', 1050, 0 );
}

add_action( 'after_setup_theme', 'listable_content_width', 0 );

/**
 * Set the gallery widget width in pixels, based on the theme's design and stylesheet.
 */
function listable_gallery_widget_width( $args, $instance ) {
	return '1050';
}

add_filter( 'gallery_widget_content_width', 'listable_gallery_widget_width', 10, 3 );

/**
 * Enqueue scripts and styles.
 */
function listable_scripts() {

	wp_deregister_style( 'wc-paid-listings-packages' );
	wp_deregister_style( 'wc-bookings-styles' );

	if ( ! is_rtl() ) {
		wp_enqueue_style( 'listable-style', get_template_directory_uri() . '/style.css' );
	}

	wp_enqueue_script( 'google-maps-api', '//maps.google.com/maps/api/js?v=3.2&sensor=false', array(), '3.2', true );
	wp_enqueue_script( 'listable-scripts', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'google-maps-api'), '1.0.0', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_localize_script( 'listable-scripts', 'listable_params', array(
		'login_url' => esc_url( wp_login_url() ),
		'strings' => array(
			'wp-job-manager-file-upload' => esc_html__( 'Add Photo', 'listable' ),
			'no_job_listings_found' => esc_html__( 'No results', 'listable' ),
			'results-no' => esc_html__( 'Results', 'listable'), //@todo this is not quite right as it is tied to the number of results - they can 1 or 0

		)
	) );

}

add_action( 'wp_enqueue_scripts', 'listable_scripts' );

function listable_admin_scripts() {

	if ( listable_is_edit_page() ) {
		wp_enqueue_script( 'listable-admin-edit-scripts', get_template_directory_uri() . '/assets/js/admin/edit-page.js', array( 'jquery' ), '1.0.0', true );

		if ( get_post_type() === 'job_listing' ) {
			wp_enqueue_style( 'listable-admin-edit-styles', get_template_directory_uri() . '/assets/css/admin/edit-listing.css' );
		} elseif ( get_post_type() === 'page' ) {
			wp_enqueue_style( 'listable-admin-edit-styles', get_template_directory_uri() . '/assets/css/admin/edit-page.css' );
		}
	} else if ( is_post_type_archive( 'job_listing' ) ) {
		wp_enqueue_style( 'listable-admin-edit-styles', get_template_directory_uri() . '/assets/css/admin/edit-listing.css' );
	}

	wp_enqueue_script( 'listable-admin-general-scripts', get_template_directory_uri() . '/assets/js/admin/admin-general.js', array( 'jquery' ), '1.0.0', true );

	$translation_array = array (
			'import_failed' => esc_html__( 'The import didn\'t work completely!', 'listable') . '<br/>' . esc_html__( 'Check out the errors given. You might want to try reloading the page and try again.', 'listable'),
			'import_confirm' => esc_html__( 'Importing the demo data will overwrite your current site content and options. Proceed anyway?', 'listable'),
			'import_phew' => esc_html__( 'Phew...that was a hard one!', 'listable'),
			'import_success_note' => __( 'The demo data was imported without a glitch! Awesome! ', 'listable') . '<br/><br/>',
			'import_success_reload' => __( '<i>We have reloaded the page on the right, so you can see the brand new data!</i>', 'listable'),
			'import_success_warning' => '<p>' . esc_html__( 'Remember to update the passwords and roles of imported users.', 'listable') . '</p><br/>',
			'import_all_done' => esc_html__( "All done!", 'listable'),
			'import_working' => esc_html__( "Working...", 'listable'),
			'import_widgets_failed' => esc_html__( "The setting up of the demo widgets failed...", 'listable'),
			'import_widgets_error' => esc_html__( 'The setting up of the demo widgets failed', 'listable') . '</i><br />' . esc_html__( '(The script returned the following message', 'listable'),
			'import_widgets_done' => esc_html__( 'Finished setting up the demo widgets...', 'listable'),
			'import_theme_options_failed' => esc_html__( "The importing of the theme options has failed...", 'listable'),
			'import_theme_options_error' => esc_html__( 'The importing of the theme options has failed', 'listable') . '</i><br />' . esc_html__( '(The script returned the following message', 'listable'),
			'import_theme_options_done' => esc_html__( 'Finished importing the demo theme options...', 'listable'),
			'import_posts_failed' => esc_html__( "The importing of the theme options has failed...", 'listable'),
			'import_posts_step' => esc_html__( 'Importing posts | Step', 'listable'),
			'import_error' =>  esc_html__( "Error:", 'listable'),
			'import_try_reload' =>  esc_html__( "You can reload the page and try again.", 'listable'),
	);
	wp_localize_script( 'listable-admin-general-scripts', 'listable_admin_js_texts', $translation_array );
}

add_action( 'admin_enqueue_scripts', 'listable_admin_scripts' );

function listable_login_scripts() {
	wp_enqueue_style( 'listable-custom-login', get_template_directory_uri() . '/assets/css/admin/login-page.css' );
}

add_action( 'login_enqueue_scripts', 'listable_login_scripts' );

/**
 * Load custom javascript set by theme options
 * This method is invoked by wpgrade_callback_themesetup
 * The function is executed on wp_enqueue_scripts
 */
function listable_load_custom_js_header() {
	$custom_js = listable_get_option( 'custom_js' );
	if ( ! empty( $custom_js ) ) {
		//first lets test is the js code is clean or has <script> tags and such
		//if we have <script> tags than we will not enclose it in anything - raw output
		if ( strpos( $custom_js, '</script>' ) !== false ) {
			echo $custom_js . "\n";
		} else {
			echo "<script type=\"text/javascript\">\n;(function($){\n" . $custom_js . "\n})(jQuery);\n</script>\n";
		}
	}
}

function listable_load_custom_js_footer() {
	$custom_js = listable_get_option( 'custom_js_footer' );
	if ( ! empty( $custom_js ) ) {
		//first lets test is the js code is clean or has <script> tags and such
		//if we have <script> tags than we will not enclose it in anything - raw output
		if ( strpos( $custom_js, '</script>' ) !== false ) {
			echo $custom_js . "\n";
		} else {
			echo "<script type=\"text/javascript\">\n;(function($){\n" . $custom_js . "\n})(jQuery);\n</script>\n";
		}
	}
}

/**
 * Implement the Custom Header feature.
 */
// require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */

require get_template_directory() . '/inc/extras.php';

require get_template_directory() . '/inc/widgets.php';

require get_template_directory() . '/inc/tutorials.php';

require get_template_directory() . '/inc/activation.php';

/* WP Job Manager related */

// Just to be safe, don't do anything if no WPJM activated - why would you do that is beyond the scope of this comment :)
if ( class_exists( 'WP_Job_Manager' ) ) {

	require get_template_directory() . '/inc/wp-job-manager.php';

	require get_template_directory() . '/inc/wp-job-manager-integrations.php';

}


/**
 * Load theme's configuration file (via Customify plugin)
 */
require get_template_directory() . '/inc/config.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Recommended/Required plugins notification
 */
require get_template_directory() . '/inc/required-plugins/required-plugins.php';

/**
 * Load WooCommerce compatibility file.
 */
require get_template_directory() . '/inc/woocommerce.php';

// Callback function to insert 'styleselect' into the $buttons array
function listable_mce_buttons( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
// Register our callback to the appropriate filter
add_filter('mce_buttons_2', 'listable_mce_buttons');

// Callback function to filter the MCE settings
function listable_formats( $init_array ) {
	// Define the style_formats array
	$style_formats = array(
		// Each array child is a format with it's own settings
		array(
			'title' => 'Intro',
			'inline' => 'span',
			'classes' => 'intro',
			'wrapper' => true
		),
		array(
			'title' => 'Two Columns',
			'block' => 'div',
			'classes' => 'twocolumn',
			'wrapper' => true
		),
		array(
			'title' => 'Separator',
			'block' => 'hr',
			'classes' => 'clear'
		),
	);
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = json_encode( $style_formats );

	return $init_array;

}
// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', 'listable_formats' );

function wupdates_check_Kv7Br( $transient ) {
	// Nothing to do here if the checked transient entry is empty
	if ( empty( $transient->checked ) ) {
		return $transient;
	}

	// Let's start gathering data about the theme
	// First get the theme directory name (the theme slug - unique)
	$slug = basename( get_template_directory() );
	$http_args = array (
		'body' => array(
			'slug' => $slug,
			'url' => home_url(), //the site's home URL
			'version' => 0,
			'data' => null, //no optional data is sent by default
		)
	);

	// If the theme has been checked for updates before, get the checked version
	if ( $transient->checked[ $slug ] ) {
		$http_args['body']['version'] = $transient->checked[ $slug ];
	}

	// Use this filter to add optional data to send
	// Make sure you return an associative array - do not encode it in any way
	$optional_data = apply_filters( 'wupdates_call_data_request', $http_args['body']['data'], $slug, $http_args['body']['version'] );

	// Encrypting optional data with private key, just to keep your data a little safer
	// You should not edit the code bellow
	$optional_data = json_encode( $optional_data );
	$w=array();$re="";$s=array();$sa=md5(str_rot13('0ab162a893ad8d7cfce46a6569d63b4a1203aeb9'));
	$l=strlen($sa);$d=str_rot13($optional_data);$ii=-1;
	while(++$ii<256){$w[$ii]=ord(substr($sa,(($ii%$l)+1),1));$s[$ii]=$ii;} $ii=-1;$j=0;
	while(++$ii<256){$j=($j+$w[$ii]+$s[$ii])%255;$t=$s[$j];$s[$ii]=$s[$j];$s[$j]=$t;}
	$l=strlen($d);$ii=-1;$j=0;$k=0;
	while(++$ii<$l){$j=($j+1)%256;$k=($k+$s[$j])%255;$t=$w[$j];$s[$j]=$s[$k];$s[$k]=$t;
		$x=$s[(($s[$j]+$s[$k])%255)];$re.=chr(ord($d[$ii])^$x);}
	$optional_data=base64_encode($re);

	// Save the encrypted optional data so it can be sent to the updates server
	$http_args['body']['data'] = $optional_data;

	// Check for an available update
	$raw_response = wp_remote_post( 'https://wupdates.com/wp-json/wup/v1/themes/check_version/Kv7Br', $http_args );

	// We stop in case we haven't received a proper response
	if ( is_wp_error( $raw_response ) || $raw_response['response']['code'] !== 200 ) {
		return $transient;
	}

	$response = (array) json_decode($raw_response['body']);
	if ( ! empty( $response ) ) {
		// You can use this action to show notifications or take other action
		do_action( 'wupdates_before_response', $response, $transient );
		if ( isset( $response['allow_update'] ) && $response['allow_update'] && isset( $response['transient'] ) ) {
			$transient->response[ $slug ] = (array) $response['transient'];
		}
		do_action( 'wupdates_after_response', $response, $transient );
	}

	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'wupdates_check_Kv7Br' );


