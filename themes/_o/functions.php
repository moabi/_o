<?php


/**
 * Twenty Fifteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Twenty Fifteen 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

/**
 * Twenty Fifteen only works in WordPress 4.1 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.1-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentyfifteen_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since Twenty Fifteen 1.0
	 */
	function twentyfifteen_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on twentyfifteen, use a find and replace
		 * to change 'twentyfifteen' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'twentyfifteen', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		//add_theme_support( 'automatic-feed-links' );

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
		 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 825, 510, true );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu',      'twentyfifteen' ),
			'social'  => __( 'Social Links Menu', 'twentyfifteen' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		) );




	}
endif; // twentyfifteen_setup
add_action( 'after_setup_theme', 'twentyfifteen_setup' );



/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_scripts() {
	// Load our main stylesheet.
	wp_enqueue_style( 'twentyfifteen-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'twentyfifteen_scripts' );

//load our functions
include_once 'inc/functions-ld.php';


/**
 * CHILD THEME STARTS
 */


/**
 * register_my_menus2
 * Add 2 wp menus
 */
function register_my_menus2() {
	register_nav_menus(
		array(
			'savoir' => __( 'En savoir plus' ),
			'trouver' => __( 'Notre société' )
		)
	);
}
add_action( 'init', 'register_my_menus2' );


if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery", 0);
/**
 * register jquery
 * should be v2
 */
function my_jquery() {
	wp_deregister_script('jquery');
	wp_register_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', array(), '1.11.3',false);
	wp_enqueue_script('jquery');
}


/**
 * contact form 7 hooks
 * add a custom field
 */
function custom_add_shortcode_clock() {
	wpcf7_add_shortcode( 'clock', 'custom_clock_shortcode_handler' ); // "clock" is the type of the form-tag
}
add_action( 'wpcf7_init', 'custom_add_shortcode_clock' );

function custom_clock_shortcode_handler( $tag ) {
	$argsLieux = array(
		'show_option_all'    => '',
		'show_option_none'   => '',
		'option_none_value'  => '-1',
		'orderby'            => 'NAME',
		'order'              => 'ASC',
		'show_count'         => 0,
		'hide_empty'         => true,
		'child_of'           => 0,
		'exclude'            => '',
		'echo'               => false,
		'hierarchical'       => 1,
		'name'               => 'categories',
		'id'                 => 'lieu',
		'class'              => 'postform terms-change form-control',
		'depth'              => 0,
		'tab_index'          => 0,
		'taxonomy'           => 'lieu',
		'hide_if_empty'      => true,
		'value_field'	     => 'term_id',
	);


	return  wp_dropdown_categories( $argsLieux );
}

function wpse27856_set_content_type(){
	return "text/html";
}
add_filter('wp_mail_content_type','wpse27856_set_content_type');

/**
 * Add a google map API KEY
 * TODO: add as an option in wp-admin
 */
function my_acf_init() {

	acf_update_setting('google_api_key', 'AIzaSyBt7tOkkPVyzm0tQpQwAZ8qA1J6aakWE6o');
}

add_action('acf/init', 'my_acf_init');


//woocommerce integration
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
	echo '<section id="main">';
}

function my_theme_wrapper_end() {
	echo '</section>';
}

//hide it

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

/*
 *  DO NOT DISPLAY ADMIN BAR IN THE FRONT-END
 */

add_filter( 'show_admin_bar', '__return_false' );

//remove wp embed in front end
function my_deregister_scripts(){
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );

