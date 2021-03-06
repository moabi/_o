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

define('BOOKING_URL', "reservation-service");
define('BOOKING_COOKIE', "reservation");
define('FEUILLE_DE_ROUTE', "feuille-de-route");
define('MAX_BOOKINGS_CLIENT', 11);
define('CONFIRMATION_URL', 'validation-devis');
define('SEJOUR_URL', 'nos-sejours');
define('DEVIS_EXPRESS', 'devis-express');
define('PARTNER_PRESTATIONS', 'mes-prestations');
define('MY_ACCOUNT','mon-compte');
define('MY_ACCOUNT_PARTNER','dashboard');
define('MY_QUOTES','mes-devis');
define('MESSENGER','mon-compte/messagerie');
define('BOOKINGS','dashboard/reservations');
define('VENDOR_CUSTOM_DASHBOARD','dashboard/prestataire');
define('VENDOR_LEGAL_DOCS','dashboard/documents-legaux');
define('VENDOR_ADD_PACKAGE','dashboard/ajouter-un-programme');
define('VENDOR_LIST_PACKAGE','dashboard/mes-programmes');
define('VENDOR_REGISTER','dashboard/compte-prestataire');
define('VENDOR_CUSTOM_NEWS','dashboard/news');
define('VENDOR_ORDER','dashboard/order');
define('PM_DASHBOARD','mon-compte');




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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-types.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-user.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-partners.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-budget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-ux.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-woocommerce.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-utils.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-wcvendors.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-fep.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pure-menu.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-templates.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-vendor.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-project-manager.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-roadbook.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-online-booking-sejour.php';


		/**
		 * ACF FIELDS
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/acf/vendor-role.php';

		//extends
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/wcvendors/class-wcvendors-pro-dashboard.php';
		add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );

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
		$this->loader->add_filter('acf/fields/google_map/api',$plugin_admin, 'my_acf_google_map_api');
		$this->loader->add_action('acf/init',$plugin_admin, 'my_acf_init');

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
		$plugin_types = new Online_Booking_Types( $this->get_plugin_name(), $this->get_version() );
		$plugin_partner = new online_booking_partners($this->get_plugin_name(), $this->get_version());
		$plugin_wc = new onlineBookingWoocommerce( $this->get_plugin_name(), $this->get_version() );
		$plugin_ux = new online_booking_ux($this->get_plugin_name(), $this->get_version() );
		$plugin_utils = new online_booking_utils();
		$plugin_wcvendors = new online_booking_wcvendors($this->get_plugin_name(), $this->get_version());
		$plugin_vendors = new online_booking_vendor();
		$plugin_fep = new online_booking_fep();
		$plugin_widget = new User_Widget();
		$plugin_pm = new OnlineBookingProjectManager();
		$plugin_sejour = new Online_Booking_Sejour($this->get_plugin_name(), $this->get_version());


		//$this->loader->add_action( 'wpcf7_init',$plugin_public, 'custom_add_shortcode_clock' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_filter( 'single_template', $plugin_public, 'get_custom_post_type_template' );
		$this->loader->add_filter( 'body_class', $plugin_public, 'my_body_class_names' );
		
		$this->loader->add_filter( 'page_template', $plugin_public, 'booking_page_template' );
		//$this->loader->add_filter( 'template_include', $plugin_public,'portfolio_page_template', 99 );
		$this->loader->add_filter( 'after_setup_theme', $plugin_public, 'create_booking_pages' );
		$this->loader->add_action( 'template_redirect',$plugin_public, 'my_page_template_redirect' );

		//set up taxonomies
		$this->loader->add_action( 'init', $plugin_types, 'lieu',0 );
		$this->loader->add_action( 'init', $plugin_types, 'theme',0 );
		//$this->loader->add_action( 'init', $plugin_public, 'theme_activity',0 );

		//setup custom post types
		$this->loader->add_action( 'init', $plugin_types, 'reservation_type',0 );
		$this->loader->add_action( 'init', $plugin_types, 'sejour_post_type',0 );
		$this->loader->add_action( 'init', $plugin_types, 'private_news_post_type',0 );
		$this->loader->add_action( 'init', $plugin_types, 'news_category',0 );
		$this->loader->add_action( 'init', $plugin_types, 'private_road_book_post_type',0 );

		$this->loader->add_shortcode( 'frontform', $plugin_public,'front_form_shortcode' );
		$this->loader->add_shortcode( 'ob-activities',$plugin_public, 'home_activites' );
		$this->loader->add_shortcode( 'ob-sejours',$plugin_public, 'home_sejours' );

		//AJAX
		$this->loader->add_action('wp_ajax_nopriv_do_ajax', $plugin_public,  'ajxfn');
		$this->loader-> add_action('wp_ajax_do_ajax', $plugin_public, 'ajxfn');
		
		//USER FILTERS/HOOK
		//$this->loader->add_action('wp_logout',$plugin_public, 'clear_reservation_cookie');


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
		$this->loader->add_filter( 'woocommerce_min_password_strength', $plugin_wc,'password_strength',30 );
		//METAdata to items
		$this->loader->add_filter('woocommerce_checkout_cart_item_quantity',$plugin_wc,'ob_add_user_custom_option_from_session_into_cart',1,3);
		$this->loader->add_filter('woocommerce_cart_item_price',$plugin_wc,'ob_add_user_custom_option_from_session_into_cart',1,3);
		$this->loader->add_action('woocommerce_add_order_item_meta',$plugin_wc,'ob_add_values_to_order_item_meta',1,2);
		$this->loader->add_action('woocommerce_before_cart_item_quantity_zero',$plugin_wc,'ob_remove_user_custom_data_options_from_cart',1,1);
		$this->loader->add_action( 'woocommerce_after_order_notes',$plugin_wc, 'my_custom_checkout_field' );
		$this->loader->add_action('woocommerce_checkout_update_order_meta', $plugin_wc,'my_custom_checkout_field_update_order_meta');
		//checkout
		$this->loader->add_action('woocommerce_checkout_before_order_review', $plugin_wc,'add_cart_metadata');


		//redirect after login
		$this->loader->add_filter( 'woocommerce_login_redirect',$plugin_wcvendors, 'login_redirect', 10, 2 );

		//Partners dashboard

		//UX

		$this->loader->add_action( 'widgets_init',$plugin_ux ,'online_booking_widgets_init' );
		$this->loader->add_filter('get_avatar',$plugin_ux, 'filter_profile_avatar', 10, 3);
		$this->loader->add_filter( 'fep_menu_buttons',$plugin_ux, 'fep_cus_fep_menu_buttons' );


		//handle menu for customers or vendors
		$this->loader->add_filter( 'woocommerce_account_menu_items',$plugin_ux, 'wcvendors_my_account_menu_items' );
		$this->loader->add_filter( 'wcv_pro_dashboard_urls',$plugin_wcvendors, 'custom_menu_link' );

		$this->loader->add_filter( 'wcv_pro_dashboard_urls',$plugin_wcvendors, 'change_dashboard_labels' );
		$this->loader->add_filter( 'wcv_product_meta_tabs',$plugin_wcvendors, 'lieu_meta_tab' );
		$this->loader->add_action( 'wcv_before_general_tab', $plugin_wcvendors, 'lieu_edit_product_form' );
		//$this->loader->add_action( 'wcv_before_general_tab', $plugin_wcvendors, 'reglages_edit_product_form' );
		$this->loader->add_action( 'wcv_before_general_tab', $plugin_wcvendors, 'infos_edit_product_form' );
		$this->loader->add_action( 'wcv_before_general_tab', $plugin_wcvendors, 'type_edit_product_form' );
		$this->loader->add_action('wcvendors_settings_before_paypal',$plugin_wcvendors,'before_paypal');



		$this->loader->add_action( 'wcv_save_product', $plugin_wcvendors,'save_lieu' );
		$this->loader->add_filter('wcv_product_title',$plugin_wcvendors, 'custom_wcv_product_title');
		$this->loader->add_filter('wcv_product_description',$plugin_wcvendors, 'custom_wcv_product_description');
		$this->loader->add_filter('wcv_product_short_description',$plugin_wcvendors, 'custom_wcv_product_short_description');
		$this->loader->add_filter('wcv_product_save_button',$plugin_wcvendors, 'custom_wcv_product_save_button');
		$this->loader->add_filter('wcv_shipping_tab',$plugin_wcvendors, 'custom_wcv_shipping_tab');
		$this->loader->add_filter( 'woocommerce_product_tabs',$plugin_wc, 'sb_woo_move_description_tab', 98);
		$this->loader->add_filter( 'the_content',$plugin_wcvendors, 'dashboard_vendor_page',10 );

		$this->loader->add_filter('posts_where', $plugin_vendors, 'my_posts_where_vendor_activities');
		$this->loader->add_filter('acf/pre_save_post' ,$plugin_vendors, 'save_program_form', 10, 1 );

		//allow preview posts
		//$this->loader->add_filter( 'pre_get_posts',$plugin_utils, array( __CLASS__, 'show_public_preview' ) );

		//product listing
		$this->loader->add_filter( 'wcv_product_table_actions_path',$plugin_wcvendors, 'product_header_table' );


		//FEP overrides
		$this->loader->add_action( 'fep_before_form_fields',$plugin_fep,'get_vendor_manager');
		//$this->loader->add_filter( 'fep_main_shortcode_output',$plugin_fep, 'output_fep' );
		$this->loader->add_action( 'fep_display_before_messagebox',$plugin_fep, 'add_new_msg' );
		$this->loader->add_action( 'wp_enqueue_scripts',$plugin_fep, 'remove_fep_stylesheet', 20 );
		$this->loader->add_action('wp_head',$plugin_fep,'remove_fep_menu');
		$this->loader->add_filter( 'fep_messagebox',$plugin_fep, 'output_mesg_box' );

		//WIDGETS
		//$this->loader->add_action( 'widgets_init',$plugin_widget, function(){register_widget( 'User_Widget' );});

		//PROJECT MANAGER (PM) $plugin_pm
		$this->loader->add_filter( 'the_content',$plugin_pm, 'get_pm_templates',10 );
		$this->loader->add_filter( 'init',$plugin_pm, 'create_pm_menu',0 );


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
