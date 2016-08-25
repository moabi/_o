<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://little-dream.fr
 * @since      1.0.0
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Online_Booking
 * @subpackage Online_Booking/includes
 * @author     little-dream.fr <david@loading-data.com>
 */
class Online_Booking {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Online_Booking_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {



		$this->plugin_name = 'online-booking';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_mailer_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Online_Booking_Loader. Orchestrates the hooks of the plugin.
	 * - Online_Booking_i18n. Defines internationalization functionality.
	 * - Online_Booking_Admin. Defines all hooks for the admin area.
	 * - Online_Booking_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-online-booking-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-online-booking-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-online-booking-mailer.php';
		 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-quotation-table.php';
		 require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-online-booking-admin.php';
		
		

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-user.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-partners.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-budget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-ux.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-woocommerce.php';

		$this->loader = new Online_Booking_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Online_Booking_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Online_Booking_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Online_Booking_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'online_booking_menu' );
		$this->loader->add_filter( 'plugin_action_links_' . plugin_basename(__FILE__),$plugin_admin, 'my_plugin_action_links' );
		//settings
		//$this->loader->add_action( 'admin_init',$plugin_admin, 'register_ob_settings' );

	}
	
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_mailer_hooks() {

		$plugin_admin = new Online_Booking_Mailer( $this->get_plugin_name(), $this->get_version() );


	}
	

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		
		
		$plugin_public = new Online_Booking_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_wc = new onlineBookingWoocommerce( $this->get_plugin_name(), $this->get_version() );
		
		//$this->loader->add_action( 'wpcf7_init',$plugin_public, 'custom_add_shortcode_clock' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_filter( 'single_template', $plugin_public, 'get_custom_post_type_template' );
		$this->loader->add_filter( 'body_class', $plugin_public, 'my_body_class_names' );
		
		$this->loader->add_filter( 'page_template', $plugin_public, 'booking_page_template' );
		//$this->loader->add_filter( 'template_include', $plugin_public,'portfolio_page_template', 99 );
		$this->loader->add_filter( 'after_setup_theme', $plugin_public, 'create_booking_pages' );
		
		$this->loader->add_action( 'init', $plugin_public, 'lieu',0 );
		$this->loader->add_action( 'init', $plugin_public, 'theme',0 );
		//$this->loader->add_action( 'init', $plugin_public, 'theme_activity',0 );
		
		$this->loader->add_action( 'init', $plugin_public, 'reservation_type',0 );
		$this->loader->add_action( 'init', $plugin_public, 'reservation_post_type',0 );
		$this->loader->add_action( 'init', $plugin_public, 'sejour_post_type',0 );
		//$this->loader->add_action( 'init', $plugin_public, 'partner_post_type',0 );
		
		$this->loader->add_shortcode( 'frontform', $plugin_public,'front_form_shortcode' );
		$this->loader->add_shortcode( 'ob-activities',$plugin_public, 'home_activites' );
		$this->loader->add_shortcode( 'ob-sejours',$plugin_public, 'home_sejours' );
		
		//add_filter('media_upload_tabs', 'remove_media_library_tab');
		$this->loader->add_filter( 'media_upload_tabs', $plugin_public, 'remove_media_library_tab',0 );
		$this->loader->add_filter('media_view_strings',$plugin_public, 'remove_medialibrary_tab');
		//AJAX
		$this->loader->add_action('wp_ajax_nopriv_do_ajax', $plugin_public,  'ajxfn');
		$this->loader-> add_action('wp_ajax_do_ajax', $plugin_public, 'ajxfn');
		
		//USER FILTERS/HOOK
		$this->loader->add_action('wp_logout',$plugin_public, 'clear_reservation_cookie');
		$this->loader->add_filter( 'login_redirect',$plugin_public,'my_login_redirect', 10, 3 );
		
		//filter head
		$this->loader->add_action('wp_head',$plugin_public,'header_form');
		$this->loader->add_action('wp_head',$plugin_public,'current_user_infos');

		//woocommerce
		$this->loader->add_filter( 'template_include', $plugin_wc,'wc_tpl', 99 );
		$this->loader->add_action('init',$plugin_wc, 'wc_empty_cart');
		$this->loader->add_action('init',$plugin_wc, 'wc_items_to_cart',10,5);
		//
		$this->loader->add_action( 'woocommerce_before_template_part',$plugin_wc, 'wc_before', 20, 0 );
		$this->loader->add_action( 'woocommerce_after_template_part',$plugin_wc, 'wc_after', 20, 0 );



	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Online_Booking_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
