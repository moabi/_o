<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://little-dream.fr
 * @since      1.0.0
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/public
 * @author     little-dream.fr <david@loading-data.com>
 */
define('BOOKING_URL', "reservation-service");
define('CONFIRMATION_URL', 'validation-devis');
define('SEJOUR_URL', 'nos-sejours');
define('DEVIS_EXPRESS', 'devis-express');

class Online_Booking_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;
    private $mdkey = "dql103s789fs7d";
    private $secret_iv = 'EPDIjepD8E9DP31JDM';

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }


    public function get_plugin_utilities($name)
    {
        $utility = '';
        if ($name == 'thumb'):
            $utility = plugin_dir_url(__FILE__) . "img/default.jpg";
        endif;

        echo $utility;
    }

    public function encode_str($data)
    {

        $key = $this->mdkey;
        $iv = $this->secret_iv;
        $iv = substr(hash('sha256', $iv), 0, 16);
        /*
        if(16 !== strlen($key)) $key = hash('MD5', $key, true);
  $padding = 16 - (strlen($data) % 16);
  $data .= str_repeat(chr($padding), $padding);
  return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16)));


        $encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->mdkey), $str, MCRYPT_MODE_CBC, md5(md5($this->mdkey))));
        return $encoded;
        */

        $ciphertext = openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);

        return $data;
    }

    public function decode_str($data)
    {
        $key = $this->mdkey;
        $iv = $this->secret_iv;
        $iv = substr(hash('sha256', $iv), 0, 16);
        /*
        $data = base64_decode($data);
        var_dump($data);
        $key = hash('MD5', $key, true);
        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
        $padding = ord($data[strlen($data) - 1]);

        return substr($data, 0, -$padding);
        */
        $plaintext = openssl_decrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return $data;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        wp_enqueue_style($this->plugin_name . 'plugins', plugin_dir_url(__FILE__) . 'css/onlyoo-plugins.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/online-booking-public.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . 'jquery-ui', plugin_dir_url(__FILE__) . 'js/jquery-ui/jquery-ui.min.css', array(), $this->version, 'all');

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name . 'moment', plugin_dir_url(__FILE__) . 'js/moment-with-locales.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . 'jqueryUi', plugin_dir_url(__FILE__) . 'js/jquery-ui/jquery-ui.min.js', array('jquery'), $this->version, true);

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/online-booking-plugins.js', array('jquery'), $this->version, true);
        wp_enqueue_script('booking-custom', plugin_dir_url(__FILE__) . 'js/online-booking-custom.js', array('jquery'), $this->version, true);

        $modify = (isset($_GET['mod'])) ? true : false;
        if( current_user_can( 'administrator' ) || current_user_can('onlyoo_team') ) {
            wp_enqueue_script('booking-admin', plugin_dir_url(__FILE__) . 'js/online-booking-admin.js', array('jquery','booking-custom'), $this->version, true);
        }

    }

    /**
     * get_custom_post_type_template
     * Load specific template
     *
     * @param $single_template
     * @return string
     */
    public function get_custom_post_type_template($single_template)
    {
        global $post;

        if ($post->post_type == 'product') {
            $single_template = plugin_dir_path(__FILE__) . 'tpl/single-product.php';
        } else if ($post->post_type == 'sejour') {
            $single_template = plugin_dir_path(__FILE__) . 'tpl/single-sejour.php';
        }
        return $single_template;
    }

    /**
     * remove_medialibrary_tab
     * hide library to non administrators
     *
     * @param $tabs
     * @return mixed
     */
    public function remove_medialibrary_tab($tabs)
    {
        if (!current_user_can('administrator')) {
            unset($tabs["mediaLibraryTitle"]);
        }
        return $tabs;
    }


    /**
     * my_body_class_names
     * add specific classes to body
     *
     * @param $classes
     * @return array
     */
    public function my_body_class_names($classes)
    {
        global $post;
        if (!is_home()) {
            $classes[] = 'tpl-booking';
        }
        $classes[] = 'tpl-booking';
        // return the $classes array
        return $classes;
    }


    /**
     * booking_page_template
     * add page templates
     *
     * @param $page_template
     * @return string
     */
    public function booking_page_template($page_template)
    {
        if (is_page(BOOKING_URL)) {
            $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-booking.php';
            $this::my_body_class_names(array('booking-app', 'tpl-booking'));

        } elseif (is_page(SEJOUR_URL)) {
            $page_template = plugin_dir_path(__FILE__) . 'tpl/archive-sejours.php';

        } elseif (is_page('compte')) {
            $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-compte.php';

        } elseif (is_page('public')) {
            $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-public.php';

        } elseif (is_page('proposer-votre-activite')) {
            $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-proposer.php';

        }

        return $page_template;
    }

    /**
     * A function used to programmatically create a post in WordPress. The slug, author ID, and title
     * are defined within the context of the function.
     *
     * @returns -1 if the post was never created, -2 if a post with the same title exists, or the ID
     *          of the post if successful.
     */
    public function create_booking_pages()
    {

        // Initialize the page ID to -1. This indicates no action has been taken.
        $post_id = -1;

        // Setup the author, slug, and title for the post
        $author_id = 1;

        // If the page doesn't already exist, then create it
        if (null == get_page_by_title('Nos séjours')) {

            // Set the post ID so that we know the post was created successfully
            $post_id = wp_insert_post(
                array(
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_author' => $author_id,
                    'post_name' => SEJOUR_URL,
                    'post_title' => 'Nos séjours',
                    'post_status' => 'publish',
                    'post_type' => 'page'
                )
            );

            // Otherwise, we'll stop
        } elseif (null == get_page_by_title('Validation demande de devis')) {

            // Set the post ID so that we know the post was created successfully
            $post_id = wp_insert_post(
                array(
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_author' => $author_id,
                    'post_name' => CONFIRMATION_URL,
                    'post_title' => __('Validation demande de devis', 'onlyoo'),
                    'post_status' => 'publish',
                    'post_type' => 'page',
                )
            );

        } elseif (null == get_page_by_title('Réservation')) {

            // Set the post ID so that we know the post was created successfully
            $post_id = wp_insert_post(
                array(
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_author' => $author_id,
                    'post_name' => BOOKING_URL,
                    'post_title' => 'Réservation',
                    'post_status' => 'publish',
                    'post_type' => 'page'
                )
            );

            // Otherwise, we'll stop
        } elseif (null == get_page_by_title('public')) {

            // Set the post ID so that we know the post was created successfully
            $post_id = wp_insert_post(
                array(
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_author' => $author_id,
                    'post_name' => 'public',
                    'post_title' => 'public',
                    'post_status' => 'publish',
                    'post_type' => 'page'
                )
            );

            // Otherwise, we'll stop
        } elseif (null == get_page_by_title('Mon compte')) {

            // Set the post ID so that we know the post was created successfully
            $post_id = wp_insert_post(
                array(
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_author' => $author_id,
                    'post_name' => 'compte',
                    'post_title' => 'Mon compte',
                    'post_status' => 'publish',
                    'post_type' => 'page'
                )
            );

            // Otherwise, we'll stop
        } elseif (null == get_page_by_title('Devis express')) {
            // Set the post ID so that we know the post was created successfully
            $post_id = wp_insert_post(
                array(
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_author' => $author_id,
                    'post_name' => DEVIS_EXPRESS,
                    'post_title' => 'Devis express',
                    'post_status' => 'publish',
                    'post_type' => 'page'
                )
            );
        } else {

            // Arbitrarily use -2 to indicate that the page with the title already exists
            $post_id = -2;

        } // end if

    }


    /**
     * date_range
     * provide a way to work with date range
     *
     * @param $first
     * @param $last
     * @param string $step
     * @param string $output_format
     * @return array
     */
    public function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y')
    {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    /**
     * lieu
     * Register Custom Taxonomy
     * for reservation & sejour
     */
    public function lieu()
    {

        $labels = array(
            'name' => _x('lieux', 'Taxonomy General Name', 'twentyfifteen'),
            'singular_name' => _x('lieu', 'Taxonomy Singular Name', 'twentyfifteen'),
            'menu_name' => __('lieux', 'twentyfifteen'),
            'all_items' => __('Tous les lieux', 'twentyfifteen'),
            'parent_item' => __('Parent', 'twentyfifteen'),
            'parent_item_colon' => __('Parent lieu', 'twentyfifteen'),
            'new_item_name' => __('Nouveau lieu', 'twentyfifteen'),
            'add_new_item' => __('Ajouter nouveau lieu', 'twentyfifteen'),
            'edit_item' => __('Editer lieu', 'twentyfifteen'),
            'update_item' => __('Mettre à jout ', 'twentyfifteen'),
            'view_item' => __('Voir lieu', 'twentyfifteen'),
            'separate_items_with_commas' => __('Separate items with commas', 'twentyfifteen'),
            'add_or_remove_items' => __('Add or remove items', 'twentyfifteen'),
            'choose_from_most_used' => __('Choose from the most used', 'twentyfifteen'),
            'popular_items' => __('Popular Items', 'twentyfifteen'),
            'search_items' => __('Search Items', 'twentyfifteen'),
            'not_found' => __('Not Found', 'twentyfifteen'),
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
        );
        register_taxonomy('lieu', array('reservation', 'sejour','product'), $args);

    }

    /**
     * reservation_type
     * Register Custom Taxonomy
     * for reservation custom post type
     */
    public function reservation_type()
    {

        $labels = array(
            'name' => _x('type', 'Taxonomy General Name', 'twentyfifteen'),
            'singular_name' => _x('type', 'Taxonomy Singular Name', 'twentyfifteen'),
            'menu_name' => __('types', 'twentyfifteen'),
            'all_items' => __('Tous les types', 'twentyfifteen'),
            'parent_item' => __('Parent', 'twentyfifteen'),
            'parent_item_colon' => __('Parent type', 'twentyfifteen'),
            'new_item_name' => __('Nouveau type', 'twentyfifteen'),
            'add_new_item' => __('Ajouter nouveau type', 'twentyfifteen'),
            'edit_item' => __('Editer type', 'twentyfifteen'),
            'update_item' => __('Mettre à jout ', 'twentyfifteen'),
            'view_item' => __('Voir type', 'twentyfifteen'),
            'separate_items_with_commas' => __('Separate items with commas', 'twentyfifteen'),
            'add_or_remove_items' => __('Add or remove items', 'twentyfifteen'),
            'choose_from_most_used' => __('Choose from the most used', 'twentyfifteen'),
            'popular_items' => __('Popular Items', 'twentyfifteen'),
            'search_items' => __('Search Items', 'twentyfifteen'),
            'not_found' => __('Not Found', 'twentyfifteen'),
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
        );
        register_taxonomy('reservation_type', array('reservation','product'), $args);

    }

    /**
     * Register Custom Taxonomy
     * secteur activité du séjour (particulier,séminaire,teamBuilding,...)
     * for reservation custom post type
     */
    public function theme()
    {

        $labels = array(
            'name' => _x('Secteur d\'activité', 'Taxonomy General Name', 'twentyfifteen'),
            'singular_name' => _x('Secteur d\'activité', 'Taxonomy Singular Name', 'twentyfifteen'),
            'menu_name' => __('Secteurs d\'activités', 'twentyfifteen'),
            'all_items' => __('Tous les Secteurs d\'activités', 'twentyfifteen'),
            'parent_item' => __('Parent', 'twentyfifteen'),
            'parent_item_colon' => __('Parent thème', 'twentyfifteen'),
            'new_item_name' => __('Nouveau thème', 'twentyfifteen'),
            'add_new_item' => __('Ajouter nouveau thème', 'twentyfifteen'),
            'edit_item' => __('Editer thème', 'twentyfifteen'),
            'update_item' => __('Mettre à jout ', 'twentyfifteen'),
            'view_item' => __('Voir thème', 'twentyfifteen'),
            'separate_items_with_commas' => __('Separate items with commas', 'twentyfifteen'),
            'add_or_remove_items' => __('Add or remove items', 'twentyfifteen'),
            'choose_from_most_used' => __('Choose from the most used', 'twentyfifteen'),
            'popular_items' => __('Popular Items', 'twentyfifteen'),
            'search_items' => __('Search Items', 'twentyfifteen'),
            'not_found' => __('Not Found', 'twentyfifteen'),
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
        );
        register_taxonomy('theme', array('reservation','product'), $args);

    }


    /**
     * Register Custom Taxonomy
     * theme_activity (activity,eat,party,...)
     * for reservation custom post type
     */
    public function theme_activity()
    {

        $labels = array(
            'name' => _x('Theme', 'Taxonomy General Name', 'twentyfifteen'),
            'singular_name' => _x('Theme', 'Taxonomy Singular Name', 'twentyfifteen'),
            'menu_name' => __('Theme', 'twentyfifteen'),
            'all_items' => __('Tous les Themes', 'twentyfifteen'),
            'parent_item' => __('Parent', 'twentyfifteen'),
            'parent_item_colon' => __('Parent thème', 'twentyfifteen'),
            'new_item_name' => __('Nouveau thème', 'twentyfifteen'),
            'add_new_item' => __('Ajouter nouveau thème', 'twentyfifteen'),
            'edit_item' => __('Editer thème', 'twentyfifteen'),
            'update_item' => __('Mettre à jout ', 'twentyfifteen'),
            'view_item' => __('Voir thème', 'twentyfifteen'),
            'separate_items_with_commas' => __('Separate items with commas', 'twentyfifteen'),
            'add_or_remove_items' => __('Add or remove items', 'twentyfifteen'),
            'choose_from_most_used' => __('Choose from the most used', 'twentyfifteen'),
            'popular_items' => __('Popular Items', 'twentyfifteen'),
            'search_items' => __('Search Items', 'twentyfifteen'),
            'not_found' => __('Not Found', 'twentyfifteen'),
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
        );
        register_taxonomy('theme_activity', array('reservation','product'), $args);

    }


    /**
     * Register Custom Post Type reservation
     *
     */
    public function reservation_post_type()
    {

        $labels = array(
            'name' => _x('Activités', 'Post Type General Name', 'twentyfifteen'),
            'singular_name' => _x('Activité', 'Post Type Singular Name', 'twentyfifteen'),
            'menu_name' => __('Activité', 'twentyfifteen'),
            'name_admin_bar' => __('Activités', 'twentyfifteen'),
            'parent_item_colon' => __('Parent Activity:', 'twentyfifteen'),
            'all_items' => __('Toutes les Activités', 'twentyfifteen'),
            'add_new_item' => __('Ajouter Activité', 'twentyfifteen'),
            'add_new' => __('Ajouter nouvelle', 'twentyfifteen'),
            'new_item' => __('Nouvelle Activité', 'twentyfifteen'),
            'edit_item' => __('Editer Activité', 'twentyfifteen'),
            'update_item' => __('Mettre à jour Activité', 'twentyfifteen'),
            'view_item' => __('Voir Activité', 'twentyfifteen'),
            'search_items' => __('Chercher une reservation', 'twentyfifteen'),
            'not_found' => __('Non trouvée', 'twentyfifteen'),
            'not_found_in_trash' => __('Non trouvée dans la poubelle', 'twentyfifteen'),
        );
        $args = array(
            'label' => __('reservation', 'twentyfifteen'),
            'description' => __('Booking for SB', 'twentyfifteen'),
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail', 'author'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'page',
        );
        register_post_type('reservation', $args);

    }

    /**
     * sejour_post_type
     * Register Custom Post Type: sejour
     */
    public function sejour_post_type()
    {

        $labels = array(
            'name' => _x('sejours', 'Post Type General Name', 'twentyfifteen'),
            'singular_name' => _x('sejour', 'Post Type Singular Name', 'twentyfifteen'),
            'menu_name' => __('sejour (Pack)', 'twentyfifteen'),
            'name_admin_bar' => __('sejour', 'twentyfifteen'),
            'parent_item_colon' => __('Parent sejour:', 'twentyfifteen'),
            'all_items' => __('Tous les sejours', 'twentyfifteen'),
            'add_new_item' => __('Ajouter sejour', 'twentyfifteen'),
            'add_new' => __('Nouveau sejour', 'twentyfifteen'),
            'new_item' => __('Nouveau sejour', 'twentyfifteen'),
            'edit_item' => __('Editer sejour', 'twentyfifteen'),
            'update_item' => __('Mettre à jour sejour', 'twentyfifteen'),
            'view_item' => __('Voir sejour', 'twentyfifteen'),
            'search_items' => __('Chercher un sejour', 'twentyfifteen'),
            'not_found' => __('Non trouvé', 'twentyfifteen'),
            'not_found_in_trash' => __('Non trouvé dans la poubelle', 'twentyfifteen'),
        );
        $args = array(
            'label' => __('sejour', 'twentyfifteen'),
            'description' => __('sejour for SB', 'twentyfifteen'),
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail', 'author'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'page',
        );
        register_post_type('sejour', $args);

    }


    /**
     * partner_post_type
     * Register Custom Post Type: partner
     */
    public function partner_post_type()
    {

        $labels = array(
            'name' => _x('Partenaires', 'Post Type General Name', 'twentyfifteen'),
            'singular_name' => _x('Partenaire', 'Post Type Singular Name', 'twentyfifteen'),
            'menu_name' => __('Partenaires', 'twentyfifteen'),
            'name_admin_bar' => __('Partenaire', 'twentyfifteen'),
            'parent_item_colon' => __('Partenaire supérieur:', 'twentyfifteen'),
            'all_items' => __('Tous les partenaires', 'twentyfifteen'),
            'add_new_item' => __('Ajouter partenaire', 'twentyfifteen'),
            'add_new' => __('Nouveau partenaire', 'twentyfifteen'),
            'new_item' => __('Nouveau partenaire', 'twentyfifteen'),
            'edit_item' => __('Editer partenaire', 'twentyfifteen'),
            'update_item' => __('Mettre à jour partenaire', 'twentyfifteen'),
            'view_item' => __('Voir partenaire', 'twentyfifteen'),
            'search_items' => __('Chercher un partenaire', 'twentyfifteen'),
            'not_found' => __('Non trouvé', 'twentyfifteen'),
            'not_found_in_trash' => __('Non trouvé dans la poubelle', 'twentyfifteen'),
        );
        $args = array(
            'label' => __('sejour', 'twentyfifteen'),
            'description' => __('sejour for SB', 'twentyfifteen'),
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail', 'author'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'page',
        );
        register_post_type('partner', $args);

    }


    /**
     * ajxfn
     * ajax FUNCTIONS
     * filter request and take actions
     *
     */
    public function ajxfn()
    {

        $user_action = new online_booking_user;

        if (!empty($_REQUEST['theme']) && !empty($_REQUEST['geo'])) {
            $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;
            $searchTerm = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
            $content = Online_Booking_Public::ajax_get_latest_posts($_REQUEST['theme'], $_REQUEST['geo'], $type, $searchTerm);
            $output = $content;

        } else if (!empty($_REQUEST['reservation'])) {
            $tripName = htmlspecialchars($_REQUEST['bookinkTrip']);
            $output = online_booking_user::save_trip($tripName);
        } else if (!empty($_REQUEST['deleteUserTrip'])) {
            $userTrip = intval($_REQUEST['deleteUserTrip']);
            $output = $user_action->delete_trip($userTrip);
        } else if (!empty($_REQUEST['estimateUserTrip'])) {
            $userTrip = intval($_REQUEST['estimateUserTrip']);
            $output = $user_action->estimateUserTrip($userTrip);
        } else if (!empty($_REQUEST['id'])) {
            $post_id = intval($_REQUEST['id']);
            $page_data = get_post($post_id);
            if ($page_data) {
                if ($page_data->post_status == "publish") {
                    //post_name
                    //var_dump($page_data);
                    $content = get_the_post_thumbnail($post_id);
                    $content .= '<h3><a href="' . get_permalink($post_id) . '">' . $page_data->post_title . '</a></h3>';
                    $content .= substr($page_data->post_content, 0, 200) . '...';
                    $output = $content;
                } else {

                }

            } else {
                $output = 'post not found...';
            }

        } else {
            $output = 'No function specified, check your jQuery.ajax() call';

        }

        $output = json_encode($output);
        if (is_array($output)) {
            print_r($output);
        } else {
            echo $output;
        }
        die;
    }


    /**
     * get_term_order
     *
     * @param $term_resa string - slug
     * @return int
     */
    public static function get_term_order($term_resa)
    {
        $terms_array_order = get_terms('reservation_type', array(
            'orderby' => 'count',
            'hide_empty' => 0,
            'parent' => 0,
        ));

        $i = 0;
        foreach ($terms_array_order as $term) {
            $i++;
            $slug_term = $term->slug;
            if ($term_resa == $slug_term):
                return $i;
            endif;
        }

    }


    /**
     * wp_query_thumbnail_posts
     * place : tpl-booking
     * SHOULD be merged with ajax_get_latest_posts
     * display selected post with GET var 'addId' in the thumbnail way
     *
     * @return string
     */
    public static function wp_query_thumbnail_posts()
    {
        $ux = new online_booking_ux;

        if (isset($_GET['addId'])) {
            wp_reset_query();
            wp_reset_postdata();
            $post_ID = intval($_GET['addId']);

            $filter_type = "filter-user";
            $reservation_type_obj = wp_get_post_terms($post_ID, 'reservation_type');
            //var_dump($reservation_type_obj);
            $reservation_type_name = $reservation_type_obj[0]->name;
            $reservation_type_ID = $reservation_type_obj[0]->term_id;
            $reservation_type_slug = $reservation_type_obj[0]->slug;
            $data_order = Online_Booking_Public::get_term_order($reservation_type_slug);
            $data_order_val = (!empty($data_order)) ? $data_order : 0;

            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'p' => $post_ID

            );

            $the_query = new WP_Query($args);
            if ($the_query->have_posts()) {

                $count_post = 0;
                $posts = '<div id="selectedOne" class="blocks selectedOne">';
                while ($the_query->have_posts()) {
                    if ($count_post == 0 && !isset($_GET['addId'])):
                        $posts .= '<h4 class="ajx-fetch">';
                        $posts .= $reservation_type_name;
                        $posts .= '</h4><div class="clearfix"></div>';
                    endif;
                    $the_query->the_post();
                    global $post;
                    $postID = $the_query->post->ID;
                    $term_list = wp_get_post_terms($post->ID, 'reservation_type');
                    $type = json_decode(json_encode($term_list), true);
                    $icon = $ux->get_reservation_type($postID, true);
                    //var_dump($type);
                    $termstheme = wp_get_post_terms($postID, 'theme');
                    $terms = wp_get_post_terms($postID, 'lieu');
                    $acf_price = get_field('prix');
                    $price = (!empty($acf_price)) ? $acf_price : '0';
                    $termsarray = json_decode(json_encode($terms), true);
                    $themearray = json_decode(json_encode($termstheme), true);
                    //var_dump($termsarray);
                    $lieu = 'data-lieux="';
                    foreach ($termsarray as $activity) {
                        $lieu .= $activity['slug'] . ', ';
                    }
                    $lieu .= '"';

                    $themes = 'data-themes="';
                    foreach ($themearray as $activity) {
                        $themes .= $activity['slug'] . ', ';
                    }
                    $themes .= '"';
                    $typearray = '';
                    foreach ($type as $singleType) {
                        $typearray .= ' ' . $singleType['slug'];
                    }

                    $posts .= '<div data-type="' . $reservation_type_slug . '" class="block" id="ac-' . get_the_id() . '" data-price="' . $price . '" ' . $lieu . ' ' . $themes . '>';

                    $posts .= '<div class="head"><h2>' . get_the_title() . '</h2><span class="price-u">' . $price . ' €</span></div>';

                    $posts .= '<div class="presta">';
                    $posts .= get_field("la_prestation_comprend") . '</div>';

                    $posts .= get_the_post_thumbnail($postID, 'square');

                    $posts .= '<a class="booking-details" href="' . get_permalink() . '">' . __('Détails', 'online-booking') . ' <i class="fa fa-search"></i></a>';
                    $posts .= '<a href="javascript:void(0)" onmouseover="selectYourDay(this)" onClick="addActivity(' . $postID . ',\'' . get_the_title() . '\',' . $price . ',\'' . $icon . '\',' . $data_order_val . ')" class="addThis">Ajouter <i class="fa fa-plus"></i></a>';


                    $posts .= '</div>';
                    $posts .= '<script type="text/javascript">
                                    jQuery(function() {
                                      var selectedOne = $("#selectedOne");
                                      $.magnificPopup.open({
                                        items: {
                                          src: selectedOne,
                                          type: "inline"
                                        },
                                        mainClass: "add-id-load",
                                        callbacks: {
                                          afterClose: function() {
                                            console.log("Popup is completely closed");
                                            var originalURL = window.location.href;
                                            removeParam("addId", originalURL);
                                          }
                                        }
                                      });
                                    });
                                </script>
                            ';

                    $count_post++;

                }


            } else {
                $posts = "";
            }
            $posts .= '</div>';
            wp_reset_query();
            wp_reset_postdata();
            return $posts;


        } else {
            return '';
        }
    }


    /**
     * home_activites
     * provide a shortcode : [ob-activities]
     * show activites
     *
     * @param $atts
     * @return string
     */
    public function home_activites($atts)
    {
        $obp = new online_booking_ux;
        /* Restore original Post Data */
        wp_reset_postdata();

        $args_act = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 8,
            'orderby' => 'rand'
        );
        $output = '';
        // The Query
        $the_query = new WP_Query($args_act);

        // The Loop
        if ($the_query->have_posts()) {

            while ($the_query->have_posts()) {
                $the_query->the_post();
                $postid = get_the_ID();
                $exc = strip_tags(get_the_content());
                $output .= '<div class="block-fe pure-u-1-2 pure-u-md-1-4">';
                $output .= '<div class="block-thumb">';
                $output .= '<a href="' . get_the_permalink() . '">';
                $output .= get_the_post_thumbnail($postid, 'square');
                $output .= '</a></div>';
                $output .= '<a href="' . get_the_permalink() . '">';
                $output .= '<div class="head-img">' . get_the_title() . '</div>';
                $output .= '</a>';
                $output .= '<div class="presta">';
                //$output .= '<div class="exc">'.substr($exc, 0, 70) . '...</div>';
                $output .= '<a href="' . get_the_permalink() . '">';
                $output .= '<i class="fa fa-users"></i>' . get_field('nombre_de_personnes');
                $output .= '<i class="fa fa-clock-o"></i>' . $obp->get_activity_time();
                $output .= '</a>';
                $output .= '</div>';
                $output .= '</div>';

            }

        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();


        return $output;

    }


    /*
        provide a shortcode
        show sejours
        [ob-sejours]
    */
    public function home_sejours($atts)
    {

        /* Restore original Post Data */
        wp_reset_postdata();

        $args_act = array(
            'post_type' => 'sejour',
            'post_status' => 'publish',
            'posts_per_page' => 5,
            'orderby' => 'rand'
        );
        $output = '';
        // The Query
        $the_query = new WP_Query($args_act);

        // The Loop
        if ($the_query->have_posts()) {
            $i = 0;
            while ($the_query->have_posts()) {
                $i++;
                $pure_class = ($i > 3) ? 'pure-u-md-1-2' : 'pure-u-md-1-3';
                $the_query->the_post();
                $postid = get_the_ID();
                $exc = get_the_excerpt();
                $output .= '<div class="block-fe sej pure-u-1-2 ' . $pure_class . '">';
                $output .= '<div class="block-thumb">';
                $output .= '<a href="' . get_the_permalink() . '">';
                $output .= get_the_post_thumbnail($postid, 'square');
                $output .= '</a></div>';
                $output .= '<a href="' . get_the_permalink() . '">';
                $output .= '<div class="head-img">' . get_the_title() . '</div>';
                $output .= '</a>';
                $output .= '</div>';

            }

        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();


        return $output;

    }


    /**
     * get_reservation_content
     * display the vignette content for an activity
     *
     * @param $args array arguments for the posts loops
     * @param $reservation_type_slug string slug of the reservation type term
     * @param $reservation_type_name string name of the reservation type term
     * @param $data_order integer activities order
     * @param bool|true $onbookingpage
     * @return string
     */
    public function get_reservation_content($args, $reservation_type_slug, $reservation_type_name, $data_order, $onbookingpage = true)
    {

        $term_reservation = get_term_by('name', $reservation_type_name, 'reservation_type');
        $fa_icon = get_field('fa_icon', $term_reservation->taxonomy . '_' . $term_reservation->term_id);
        $posts = '';
        $ux = new online_booking_ux;
        $the_query = new WP_Query($args);
        // The Loop
        if ($the_query->have_posts()) {

            $count_post = 0;

            while ($the_query->have_posts()) {
                if ($count_post == 0 && $onbookingpage == true):
                    $posts .= '<h4 class="ajx-fetch"><i class="fa ' . $fa_icon . '"></i>';
                    $posts .= $reservation_type_name;
                    $posts .= '</h4><div class="clearfix"></div>';
                endif;
                $the_query->the_post();
                global $post;
                $postID = $the_query->post->ID;
                $term_list = wp_get_post_terms($post->ID, 'reservation_type');
                $type = json_decode(json_encode($term_list), true);
                //var_dump($type);
                $termstheme = wp_get_post_terms($postID, 'theme');
                $terms = wp_get_post_terms($postID, 'lieu');
                $icon = $ux->get_reservation_type($postID, true);
                $price = get_field('prix');
                $termsarray = json_decode(json_encode($terms), true);
                $themearray = json_decode(json_encode($termstheme), true);
                //var_dump($termsarray);
                $lieu = 'data-lieux="';
                foreach ($termsarray as $activity) {
                    $lieu .= $activity['slug'] . ', ';
                }
                $lieu .= '"';

                $themes = 'data-themes="';
                foreach ($themearray as $activity) {
                    $themes .= $activity['slug'] . ', ';
                }
                $themes .= '"';
                $typearray = '';
                foreach ($type as $singleType) {
                    $typearray .= ' ' . $singleType['slug'];
                }

                $posts .= '<div data-type="' . $reservation_type_slug . '" class="block" id="ac-' . get_the_id() . '" data-price="' . $price . '" ' . $lieu . ' ' . $themes . '>';
                $posts .= '<div class="head"><h2>' . get_the_title() . '</h2><span class="price-u">' . $price . ' €</span></div>';
                $posts .= '<div class="presta">';
                $posts .= get_field("la_prestation_comprend") . '</div>';
                $posts .= '<div class="block-thumb">' . get_the_post_thumbnail($postID, 'square') . '</div>';

                $posts .= '<a class="booking-details" href="' . get_permalink() . '">' . __('Détails', 'online-booking') . '<i class="fa fa-search"></i></a>';
                if ($onbookingpage == true) {
                    $posts .= '<a href="javascript:void(0)" onmouseover="selectYourDay(this)" onClick="addActivity(' . $postID . ',\'' . get_the_title() . '\',' . $price . ',\'' . $icon . '\',' . $data_order . ')" class="addThis">' . __('Ajouter', 'online-booking') . '<i class="fa fa-plus"></i></a>';
                } else {
                    $posts .= '<a href="' . site_url() . '/' . BOOKING_URL . '?addId=' . $postID . '" class="addThis">' . __('Ajouter', 'online-booking') . '<i class="fa fa-plus"></i></a>';
                }


                $posts .= '</div>';

                $count_post++;

            }


        } else {

        }

        return $posts;
    }

    /*
        ajax_get_latest_posts function
        filter by term according to user choice
        $theme && $lieu should be mandatory
        order by term : reservation type
        @param $theme : integer - single term only
        @param $lieu  : integer - single term only
        @param $type  : array multiple choice, !$type == all $type elements

    */


    public function ajax_get_latest_posts($theme, $lieu, $type, $searchTerm)
    {

        //order posts by terms ? => yes and use $i to add data-order attr to element
        $terms_array_order = get_terms('reservation_type', array(
            'orderby' => 'count',
            'hide_empty' => 0,
            'parent' => 0,
        ));

        $global_theme = intval($theme);
        $global_lieu = intval($lieu);

        if (is_array($type)):
            $errors = array_filter($type);
        else:
            $errors = "no array";
        endif;
        //iterate through all terms or selected ones
        if ($type == null | empty($errors)):
            $array_custom_term = $terms_array_order;
        else:
            $array_custom_term = $type;
        endif;

        $posts = '<div id="filtered">';
        $i = 0;

        foreach ($array_custom_term as $term_item) {


            if (!is_int($term_item) && is_object($term_item)):
                //no filter, take all top terms
                $filter_type = "filter-top-term";
                $reservation_type_name = $term_item->name;
                $reservation_type_ID = $term_item->term_id;
                $reservation_type_slug = $term_item->slug;

            else:
                //we are filtering, we get term by id
                $filter_type = "filter-user";
                $reservation_type_obj = get_term_by('id', $term_item, 'reservation_type');
                $reservation_type_name = $reservation_type_obj->name;
                $reservation_type_ID = $reservation_type_obj->term_id;
                $reservation_type_slug = $reservation_type_obj->slug;
            endif;

            $data_order = Online_Booking_Public::get_term_order($reservation_type_slug);


            //var_dump($term_reservation);
            $i++;

            $posts .= '<div class="term_wrapper" data-place="' . $global_lieu . '" data-theme="' . $global_theme . '" data-id="' . $reservation_type_ID . '-' . $reservation_type_slug . '-- ' . $filter_type . '">';

            $_s = strip_tags($searchTerm);
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => 20,
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'theme',
                        'field' => 'term_id',
                        'terms' => $global_theme,
                    ),
                    array(
                        'taxonomy' => 'lieu',
                        'field' => 'term_id',
                        'terms' => $global_lieu,
                    ),
                    array(
                        'taxonomy' => 'reservation_type',
                        'field' => 'term_id',
                        'terms' => $reservation_type_ID,
                    ),

                ),
                's' => $_s
            );


            //GET CONTENT
            $content = $this::get_reservation_content($args, $reservation_type_slug, $reservation_type_name, $data_order);
            if (!empty($content)) {
                $posts .= $content;
            } else {
                //no post found for This category
                $posts .= "";
            }
            $posts .= '</div>';
            //wp_reset_postdata();
        }


        $posts .= '</div>';
        wp_reset_query();
        wp_reset_postdata();

        return $posts;
    }


    /**
     * the_sejours
     * INVITE YOU
     * displays packages filtered by place
     *
     * @param int $nb
     * @param bool $onBookingPage
     * @param bool $lieu
     */
    public function the_sejours($nb = 5, $onBookingPage = false, $lieu = false,$slider = false)
    {

        if ($lieu == false) {
            $terms = get_terms('lieu', array(
                'orderby' => 'count',
                'hide_empty' => 1,
                'parent' => 0,
            ));
        } else {
            $terms = get_terms('lieu', array(
                'orderby' => 'count',
                'hide_empty' => 1,
                'parent' => 0,
                'name' => $lieu
            ));
        }

        //var_dump($terms);
        foreach ($terms as $term) {
            $goToBookingPage = $onBookingPage ? 'true' : 'false';
            $slider = ($slider == false) ? 'grid-style' : 'slick-multi';
            // The Loop

            $args = array(
                'post_type' => 'sejour',
                'posts_per_page' => $nb,
                'post_status' => 'publish',
                'lieu' => $term->slug
            );


            $the_query = new WP_Query($args);

            if ($the_query->have_posts()) {
                $sejour = '<div class="blocks sejour-content pure-g"><div class="' . $slider . '">';
                echo '<h4><i class="fa fa-map-marker"></i>' . $term->name . '</h4>';
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    global $post;
                    $postID = $the_query->post->ID;
                    $term_lieu = wp_get_post_terms($postID, 'lieu');
                    foreach ($term_lieu as $key => $value) {
                        //echo '<span>'.$value->name.'</span> ';
                    }

                    $price = get_field('prix');
                    $personnes = get_field('personnes');
                    $budget_min = get_field('budget_min');
                    $budget_max = get_field('budget_max');
                    $budgMin = $budget_min * $personnes;
                    $budgMax = $budget_max * $personnes;
                    $theme = get_field('theme');
                    $lieu = get_field('lieu');
                    $rows = get_field('votre_sejour');
                    $row_count = count($rows);
                    $lastDay = 86400 * $row_count;
                    $departure_date = date("d/m/Y", time() + $lastDay);

                    $arrival_date = date("d/m/Y", time() + 86400);


                    $activityObj = 1;
                    $dayTrip = '{';
                    if (have_rows('votre_sejour')):
                        while (have_rows('votre_sejour')) : the_row();
                            $calcDay = 86400 * $activityObj;
                            $actual_date = date("d/m/Y", time() + $calcDay);
                            $dayTrip .= '"' . $actual_date . '" : {';
                            if (have_rows('activites')):
                                while (have_rows('activites')) : the_row();
                                    $activityArr = get_sub_field('activite');
                                    $i = 0;
                                    $len = count($activityArr);
                                    foreach ($activityArr as $data) {
                                        $field = get_field('prix', $data->ID);
                                        $url = wp_get_attachment_url(get_post_thumbnail_id($data->ID));
                                        $term_list = wp_get_post_terms($data->ID, 'reservation_type');
                                        $type = json_decode(json_encode($term_list), true);
                                        $comma = ($i == $len - 1) ? '' : ',';
                                        $dayTrip .= '"' . $data->ID . '":';
                                        $dayTrip .= '{ "name" : "' . $data->post_title . '","';
                                        if (!empty($field)):
                                            $dayTrip .= 'price": ' . $field . ',';
                                        else:
                                            $dayTrip .= 'price": 0,';
                                        endif;

                                        if (isset($type[0])):
                                            $type_slug = $type[0]['slug'];
                                            $dayTrip .= '"type": "' . $type[0]['slug'] . '"';
                                        else:
                                            $type_slug = (isset($type_slug)) ? $type_slug : "undefined var";
                                            $dayTrip .= '"type": "' . $type_slug . '"';
                                        endif;
                                        $dayTrip .= '}' . $comma;
                                        $i++;
                                    }
                                endwhile;
                            endif;
                            $dayTrip .= '},';
                            $activityObj++;
                        endwhile;
                    endif;
                    $dayTrip .= '}';
                    $colgrid = ($nb == 3) ? 'pure-u-md-1-3' : 'pure-u-md-1-4';
                    $sejour .= '<div id="post-' . $postID . '" class="block-trip pure-u-1 ' . $colgrid . '"><div class="block-trip">';
                    $sejour .= '<h2>' . get_the_title() . '</h2>';
                    $sejour .= get_the_post_thumbnail($postID, 'square');
                    $sejour .= '<div class="presta">' . substr(get_the_content(), 0, 120) . '</div>';
                    $sejour .= '<script>';
                    $sejour .= 'sejour' . $postID . ' = {
	                		"sejour" : "' . get_the_title() . '",
	                		"theme" : "' . $theme[0] . '",
	                		"lieu"  : "' . $lieu[0] . '",
	                		"arrival": "' . $arrival_date . '",
							"departure": "' . $departure_date . '",
							"days": ' . $row_count . ',
							"participants": "' . $personnes . '",
							"budgetPerMin": "' . $budget_min . '",
							"budgetPerMax": "' . $budget_max . '",
							"globalBudgetMin": ' . $budgMin . ',
							"globalBudgetMax": ' . $budgMax . ',
							"currentBudget" :' . $activityObj . ',
							"currentDay": "' . $arrival_date . '",
							"tripObject": ' . $dayTrip . '
							};';
                    $sejour .= '</script>';
                    $sejour .= '<a href="' . get_permalink() . '" class="seeit">Voir ce séjour</a>';
                    $sejour .= '<a href="javascript:void(0)" class="loadit" onclick="loadTrip(sejour' . $postID . ',' . $goToBookingPage . ');">' . __('Sélectionnez cet évènement', 'online-booking') . '</a></div></div>';

                }
                wp_reset_postdata();
                $sejour .= '</div></div>';
            } else {
                $sejour = "";
            }

            echo $sejour;
        }

    }


    /**
     * the_sejour
     * add a button and load var reservation object
     * @param $postid
     * @param bool|false $single_btn
     */
    public function the_sejour_btn($postid, $single_btn = false)
    {
        $postID = $postid;
        $sejours_url = 'nos-sejours';
        $price = get_field('prix');
        $personnes = get_field('personnes');
        $budget_min = get_field('budget_min');
        $budget_max = get_field('budget_max');
        $budgMin = $budget_min * $personnes;
        $budgMax = $budget_max * $personnes;
        $theme = get_field('theme');
        $lieu = get_field('lieu');
        $rows = get_field('votre_sejour');
        $row_count = count($rows);
        $lastDay = 86400 * $row_count;
        $departure_date = date("d/m/Y", time() + $lastDay);
        $arrival_date = date("d/m/Y", time() + 86400);

        $activityObj = 1;
        $dayTrip = '{';
        if (have_rows('votre_sejour')):
            while (have_rows('votre_sejour')) : the_row();
                $calcDay = 86400 * $activityObj;
                $actual_date = date("d/m/Y", time() + $calcDay);
                $dayTrip .= '"' . $actual_date . '" : {';
                if (have_rows('activites')):
                    while (have_rows('activites')) : the_row();
                        $activityArr = get_sub_field('activite');
                        $i = 0;
                        $len = count($activityArr);

                        foreach ($activityArr as $data) {
                            $field = get_field('prix', $data->ID);
                            $url = wp_get_attachment_url(get_post_thumbnail_id($data->ID));
                            $term_list = wp_get_post_terms($data->ID, 'reservation_type');
                            $type = json_decode(json_encode($term_list), true);

                            $comma = ($i == $len - 1) ? '' : ',';
                            $dayTrip .= '"' . $data->ID . '":';
                            $dayTrip .= '{ "name" : "' . $data->post_title . '","';
                            if (!empty($field)):
                                $dayTrip .= 'price": ' . $field . ',';
                            else:
                                $dayTrip .= 'price": 0,';
                            endif;

                            if (isset($type[0])):
                                $type_slug = $type[0]['slug'];
                                $dayTrip .= '"type": "' . $type[0]['slug'] . '"';
                            else:
                                $type_slug = (isset($type_slug)) ? $type_slug : "undefined var";
                                $dayTrip .= '"type": "' . $type_slug . '"';
                            endif;
                            $dayTrip .= '}' . $comma;

                            //var_dump($type[0]);
                            $i++;
                        }
                    endwhile;
                endif;
                $dayTrip .= '},';
                $activityObj++;
            endwhile;
        endif;
        $dayTrip .= '}';
        $sejour = '';
        if ($single_btn == false):
            $sejour .= '<script>';
            $sejour .= 'Uniquesejour' . $postID . ' = {
	                		"sejour" : "' . get_the_title() . '",
	                		"theme" : "' . $theme[0] . '",
	                		"lieu"  : "' . $lieu[0] . '",
	                		"arrival": "' . $arrival_date . '",
							"departure": "' . $departure_date . '",
							"days": ' . $row_count . ',
							"participants": "' . $personnes . '",
							"budgetPerMin": "' . $budget_min . '",
							"budgetPerMax": "' . $budget_max . '",
							"globalBudgetMin": ' . $budgMin . ',
							"globalBudgetMax": ' . $budgMax . ',
							"currentBudget" :' . $activityObj . ',
							"currentDay": "' . $arrival_date . '",
							"tripObject": ' . $dayTrip . '
							};';
            $sejour .= '</script>';
        endif;
        $sejour .= '<a id="CTA" href="javascript:void(0)" class="loadit" onclick="loadTrip(Uniquesejour' . $postID . ',true);">' . __('Sélectionnez cet évènement', 'online-booking') . '</a>';
        if ($single_btn == false):
            $sejour .= '<a class="btn btn-reg grey" href="' . get_site_url() . '/' . $sejours_url . '">' . __('Voir Toutes nos activités', 'online-booking') . '</a>';
        endif;
        echo $sejour;

    }


    /*
    * front_form_shortcode
    * add a form to set default values to trip on another page
    * @param string ($booking_url) the booking url to go to
    */
    public function front_form_shortcode($booking_url)
    {
        // Code
        $args = array(
            'show_option_all' => '',
            'show_option_none' => '',
            'option_none_value' => '-1',
            'orderby' => 'ID',
            'order' => 'ASC',
            'show_count' => 0,
            'hide_empty' => true,
            'child_of' => 0,
            'exclude' => '',
            'echo' => 0,
            'selected' => false,
            'hierarchical' => 0,
            'name' => 'cat',
            'id' => 'theme-form',
            'class' => 'postform form-control',
            'depth' => 0,
            'tab_index' => 0,
            'taxonomy' => 'theme',
            'hide_if_empty' => true,
            'value_field' => 'term_id',
        );
        $argsLieux = array(
            'show_option_all' => '',
            'show_option_none' => '',
            'option_none_value' => '-1',
            'orderby' => 'ID',
            'order' => 'ASC',
            'show_count' => 0,
            'hide_empty' => true,
            'child_of' => 0,
            'exclude' => '',
            'echo' => 0,
            'selected' => false,
            'hierarchical' => 1,
            'name' => 'categories',
            'id' => 'lieu-form',
            'class' => 'postform form-control',
            'depth' => 0,
            'tab_index' => 0,
            'taxonomy' => 'lieu',
            'hide_if_empty' => true,
            'value_field' => 'term_id',
        );

        if (!isset($_COOKIE['reservation'])):

            $front_form = '<form id="front-form" method="post" class="booking" action="' . get_bloginfo('url') . '/' . BOOKING_URL . '/">';
            $front_form .= '<div class="pure-g">';
            $front_form .= '<div class="pure-u-1 pure-u-sm-5-24">';
            $front_form .= wp_dropdown_categories($argsLieux);
            $front_form .= '</div><div class="pure-u-1 pure-u-sm-5-24">';
            $front_form .= wp_dropdown_categories($args);
            $front_form .= '</div><div class="pure-u-1 pure-u-sm-5-24">';
            $front_form .= '<div class="date-wrapper"><input data-value="" name="formdate" value="' . date("d/m/Y") . '" class="datepicker bk-form form-control" id="arrival-form">';
            $front_form .= '<div class="fs1" aria-hidden="true" data-icon=""></div></div>';
            $front_form .= '</div><div class="pure-u-1 pure-u-sm-3-24">';
            $front_form .= '<div class="people-wrapper"><input name="participants" type="number" id="participants-form" value="5" class="bk-form form-control" />';
            $front_form .= '<div class="fs1" aria-hidden="true" data-icon=""></div></div>';
            $front_form .= '</div><div class="pure-u-1 pure-u-sm-6-24">';
            $front_form .= '<input type="submit" value="GO" />';
            $front_form .= '</div></div></form>';
            $front_form .= '<div class="clearfix"></div>';
        else:

            $front_form = '<div id="front-form" class="booking exists"><a href="' . get_bloginfo('url') . '/' . BOOKING_URL . '/" title="' . __('Voir votre réservation', 'twentyfifteen') . '">' . __('Voir votre réservation', 'twentyfifteen') . '</a></div>';

        endif;

        return $front_form;
    }

    /**
     * header_form
     * add a login form to header.php
     * If user is logged : display account link and booked trips
     * if user is not logged : display a login form
     *
     */
    public function header_form()
    {
        global $current_user;
        wp_get_current_user();
        //var_dump($current_user);
        if (!is_user_logged_in()):
            $output = '<div id="logger">';
            $output .= '<a href="#login-popup" class="open-popup-link">';
            $output .= __('Se connecter', 'twentyfifteen');
            $output .= '</a>';
            $output .= '</div>';
            $output .= '<div id="login-popup" class="white-popup mfp-hide">';
            $output .= do_shortcode('[userpro template=register type=particuliers]');
            $output .= '</div>';
        else:
            $output = '<div id="logger">';
            //$output .= '<span class="user-name">';
            //$output .= __('Bonjour','online-booking');
            //$output .= $current_user->user_login;
            //$output .= '</span>';
            $output .= '<a class="my-account" href="' . get_bloginfo('url') . '/compte">' . __('Mon compte', 'online-booking') . '</a>';
            $output .= '<a class="log-out" href="' . wp_logout_url(home_url() . '?log=ftl') . '">' . __('Déconnexion', 'online-booking') . '</a>';
            $output .= '</div>';
        endif;
        Online_Booking_Public::delete_cookies();
        echo $output;

    }


    /**
     * delete_cookies
     * Clear cookies when log out by user
     */
    public function delete_cookies()
    {

        $logged_out = isset($_GET['log']) ? $_GET['log'] : '';
        if (isset($_SERVER['HTTP_COOKIE']) && $logged_out == 'ftl') {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
        }

    }

    /**
     * current_user_infos
     * add a login form to header.php
     */
    public function current_user_infos()
    {
        global $current_user;
        wp_get_current_user();
        //var_dump($current_user);
        if (is_user_logged_in()):
            $output = '<div id="logged_in_info" style="display:none;">';
            $output .= '<input id="user-logged-in-infos" data-id="' . $current_user->ID . '" />';
            $output .= '</div>';
        else:
            $output = '';
        endif;

        echo $output;

    }

    /**
     * Deprecated
     * remove for front use the tabs library...
     *
     * @param $tabs
     * @return mixed
     */
    public function remove_media_library_tab($tabs)
    {
        if (!is_admin()):
            unset($tabs['library']);
            return $tabs;
        endif;
    }

    /**
     * Redirect user after successful login.
     *
     * @param string $redirect_to URL to redirect to.
     * @param string $request URL the user is coming from.
     * @param object $user Logged user's data.
     * @return string
     */
    public function my_login_redirect($redirect_to, $request, $user)
    {
        //is there a user to check?
        global $user;
        if (isset($user->roles) && is_array($user->roles)) {
            //check for admins
            if (in_array('administrator', $user->roles)) {
                // redirect them to the default place
                return $redirect_to;
            } else {
                return home_url();
            }
        } else {
            return $redirect_to;
        }
    }


    /**
     * custom_add_shortcode_clock
     * add custom contact form value
     *
     */
    public function custom_add_shortcode_clock()
    {
        //$clockfn = $this::custom_clock_shortcode_handler();
        wpcf7_add_shortcode('clock', $this::custom_clock_shortcode_handler("clock")); // "clock" is the type of the form-tag
    }

    /**
     * custom_clock_shortcode_handler
     * @param $tag
     * @return string
     */
    public function custom_clock_shortcode_handler($tag)
    {
        $argsLieux = array(
            'show_option_all' => '',
            'show_option_none' => '',
            'option_none_value' => '-1',
            'orderby' => 'NAME',
            'order' => 'ASC',
            'show_count' => 0,
            'hide_empty' => true,
            'child_of' => 0,
            'exclude' => '',
            'echo' => false,
            'hierarchical' => 1,
            'name' => 'categories',
            'id' => 'lieu',
            'class' => 'postform terms-change form-control',
            'depth' => 0,
            'tab_index' => 0,
            'taxonomy' => 'lieu',
            'hide_if_empty' => true,
            'value_field' => 'term_id',
        );
        $places = wp_dropdown_categories($argsLieux);
        return wp_dropdown_categories($argsLieux);
    }


}
