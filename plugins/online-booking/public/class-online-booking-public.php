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


	/**
	 * get some default assets
	 * @param $name
	 */
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

        if( current_user_can( 'administrator' ) || current_user_can('vendor') ) {
            wp_enqueue_script('vendors', plugin_dir_url(__FILE__) . 'js/vendor.js', array('jquery','booking-custom'), $this->version, true);
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
    	global $post;
        if (is_page(BOOKING_URL)) {
            $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-booking.php';
            $this::my_body_class_names(array('booking-app', 'tpl-booking'));

        } elseif (is_page(SEJOUR_URL)) {
            $page_template = plugin_dir_path(__FILE__) . 'tpl/archive-sejours.php';

        } elseif (is_page('compte')) {
            $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-compte.php';

        } elseif (is_page('public')) {
            $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-public.php';

        } elseif (is_page('proposer-votre-activite') || is_page('ajouter-activite')) {
            $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-proposer.php';

        } elseif (is_page(PARTNER_PRESTATIONS)) {
	        $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-mes-prestations.php';

        } elseif (is_page(MY_QUOTES)) {
	        $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-mes-devis.php';

        } elseif (is_page('dashboard') || is_page(MY_ACCOUNT)) {
	        $page_template = plugin_dir_path(__FILE__) . 'tpl/tpl-dasboard-vendor.php';

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
        }  else {

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
     * ajxfn
     * ajax FUNCTIONS
     * filter request and take actions
     *
     */
    public function ajxfn()
    {

        $user_action = new online_booking_user;
	    $vendor = new online_booking_vendor();

        if (!empty($_REQUEST['theme']) && !empty($_REQUEST['geo'])) {
            $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;
            $searchTerm = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	        $output = $this->ajax_get_latest_posts($_REQUEST['theme'], $_REQUEST['geo'], $type, $searchTerm);
        } elseif(!empty($_REQUEST['generateid'])){
	        $output = $user_action->generateTransId();
        } elseif (!empty($_REQUEST['reservation'])) {
            $trip_id = intval($_REQUEST['existingTripId']);
            $output = $user_action->save_trip($trip_id);
        } else if (!empty($_REQUEST['deleteUserTrip'])) {
            $userTrip = intval($_REQUEST['deleteUserTrip']);
            $output = $user_action->delete_trip_action($userTrip);
        } else if (!empty($_REQUEST['estimateUserTrip'])) {
            $userTrip = intval($_REQUEST['estimateUserTrip']);
            $output = $user_action->estimateUserTrip($userTrip);
        } else if (!empty($_REQUEST['id'])) {
        	$post_id_int = intval($_REQUEST['id']);
	        $output = $this->get_post_card($post_id_int);
        } else if (!empty($_REQUEST['type'])) {
        	if($_REQUEST['type'] == 'setActivityStatus'){
        		$status = (isset($_REQUEST['activity_status'])) ? intval($_REQUEST['activity_status']) : false;
		        $uuid = (isset($_REQUEST['uuid'])) ? intval($_REQUEST['uuid']) : false;
		        $output = $vendor->set_activity_status($status,$uuid);
	        } else {
	        	$output = 'error - aucun changement effectué';
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
	 * get_post_card
	 * get a small preview of product
	 * @param $id
	 * return $output
	 */
    public function get_post_card($id){
	    $post_id = intval($id);
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
			    $output = '';
		    }

	    } else {
		    $output = 'post not found...';
	    }

	    return $output;
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
    public function wp_query_thumbnail_posts()
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
                        $posts .= '<h3 class="ajx-fetch">';
                        $posts .= $reservation_type_name;
                        $posts .= '</h3><div class="clearfix"></div>';
                    endif;
                    $the_query->the_post();
                    global $post,$woocommerce,$product;

	                var_dump($product);
                    $postID = $the_query->post->ID;
                    $term_list = wp_get_post_terms($post->ID, 'reservation_type');
                    $type = json_decode(json_encode($term_list), true);
                    $icon = $ux->get_reservation_type($postID, true);
                    //var_dump($type);
                    $termstheme = wp_get_post_terms($postID, 'theme');
                    $terms = wp_get_post_terms($postID, 'lieu');
	                $_product = wc_get_product( $postID );
	                $product_excerpt = get_the_excerpt($postID);
	                $price = $_product->get_price();
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

                    $posts .= '<div class="head"><h4>' . get_the_title() . '</h4><span class="price-u">' . $price . ' €</span></div>';

                    $posts .= '<div class="presta">';
                    $posts .= $product_excerpt;
	                $posts .= '</div>';

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
	            $_product = wc_get_product( $postid );
	            $product_excerpt = get_the_excerpt($postid);
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
                $output .= $product_excerpt;
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


	/**
	 * provide a shortcode : [ob-sejours]
	 * show sejours
	 * @param $atts
	 *
	 * @return string
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
                    $posts .= '<h3 class="ajx-fetch"><i class="fa ' . $fa_icon . '"></i>';
                    $posts .= $reservation_type_name;
                    $posts .= '</h3><div class="clearfix"></div>';
                endif;
                $the_query->the_post();
                global $post,$product;

                $postID = $the_query->post->ID;
	            $_product = wc_get_product( $postID );
	            $product_excerpt = get_the_excerpt($post->ID);
	            $price = $_product->get_price();
                $term_list = wp_get_post_terms($post->ID, 'reservation_type');
                $type = json_decode(json_encode($term_list), true);
                //var_dump($type);
                $termstheme = wp_get_post_terms($postID, 'theme');
                $terms = wp_get_post_terms($postID, 'lieu');
                $icon = $ux->get_reservation_type($postID, true);
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
                $posts .= '<div class="head"><h4>' . get_the_title() . '</h4><span class="price-u">' . $price . ' €</span></div>';
                $posts .= '<div class="presta">';
                $posts .= $product_excerpt;
	            $posts .= '<span class="app-time-short"><i class="fa fa-clock-o" aria-hidden="true"></i> Durée'.$ux->get_activity_time().'</span>';
	            $posts .= '<span class="app-users-short"><i class="fa fa-users" aria-hidden="true"></i> Jusqu\'à '.get_field('nombre_de_personnes', $post->ID).'personne(s)</span>';
	            $posts .= '</div>';


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


	/**
	 * ajax_get_latest_posts function
	 * filter by term according to user choice
	 * $theme && $lieu should be mandatory
	 * order by term : reservation type
	 * @param $theme integer - single term only
	 * @param $lieu integer - single term only
	 * @param $type array multiple choice, !$type == all $type elements
	 * @param $searchTerm
	 *
	 * @return string
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
     * @param bool $goto_booking_page
     * @param bool $lieu
     */
    public function the_sejours($nb = 5, $goto_booking_page = false, $lieu = false,$slider = false)
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
            $goToBookingPage = $goto_booking_page ? 'true' : 'false';
            $slider_class = ($slider) ? 'slick-multi' : 'grid-style' ;
            // The Loop

            $args = array(
                'post_type' => 'sejour',
                'posts_per_page' => $nb,
                'post_status' => 'publish',
                'lieu' => $term->slug
            );


            $the_query = new WP_Query($args);

            if ($the_query->have_posts()) {
	            $sejour = '<h3 class="related-place"><i class="fa fa-map-marker"></i>' . $term->name . '</h3>';
                $sejour .= '<div class="blocks sejour-content pure-g"><div class="' . $slider_class . '">';

                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    global $post,$product;
                    $postID = $the_query->post->ID;
                    $term_lieu = wp_get_post_terms($postID, 'lieu');
                    foreach ($term_lieu as $key => $value) {
                        //echo '<span>'.$value->name.'</span> ';
                    }

	                $colgrid = ($nb == 3) ? 'pure-u-1 pure-u-md-1-3' : 'pure-u-1 pure-u-md-1-4';
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
	                //author
	                $manager = get_field('manager',$post->ID);
	                $first_name = get_the_author_meta('first_name',$post->ID);
	                $last_name = get_the_author_meta('last_name',$post->ID);
	                $author_email = get_the_author_meta('user_email',$post->ID);
	                $display_name = (!empty($first_name.$last_name)) ? $first_name : get_the_author();
	                $avatar = get_avatar( get_the_author_meta( 'ID' ), 32 );
	                //filters arrays to list...
	                //$filter_place = (isset($term_lieu[0]))? 'data-lieu="'.$term_lieu[0].'"' : 0;
	                //$filter_theme = (!empty($theme))? 'data-theme="'.$theme.'"' : 0;


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
                                        //$field = get_field('prix', $data->ID);
	                                    $_product = wc_get_product( $data->ID );
	                                    $price = $_product->get_price();
                                        $url = wp_get_attachment_url(get_post_thumbnail_id($data->ID));
                                        $term_list = wp_get_post_terms($data->ID, 'reservation_type');
                                        $type = json_decode(json_encode($term_list), true);
                                        $comma = ($i == $len - 1) ? '' : ',';
                                        $dayTrip .= '"' . $data->ID . '":';
                                        $dayTrip .= '{ "name" : "' . $data->post_title . '","';
                                        if (!empty($price)):
                                            $dayTrip .= 'price": ' . $price . ',';
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

                    $sejour .= '<div id="post-' . $postID . '" class="block block-trip-container ' . $colgrid . '">';
	                $sejour .= '<div class="block-trip">';
                    $sejour .= '<h4>' . get_the_title() . '</h4>';
	                if(!empty($display_name)){
		                $sejour .= '<div class="sejour-author">';
		                $sejour .= $avatar;
		                $sejour .= __('<span class="proposed-by">proposé par').' '.$display_name;
		                $sejour .= '</span></div>';
	                }

                    $sejour .= get_the_post_thumbnail($postID, 'square');
                    $sejour .= '<div class="presta">' . get_the_excerpt($postID) . '</div>';
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
	                $sejour .= '<a href="javascript:void(0)" class="loadit" onclick="loadTrip(sejour' . $postID . ',' . $goToBookingPage . ');">' . __('Charger ce séjour', 'online-booking') . '<i class="fa fa-plus" 
                    aria-hidden="true"></i></a>';
                    $sejour .= '<a href="' . get_permalink() . '" class="seeit">Plus de détails<span class="fa 
                    fa-search" aria-hidden="true"></span></a>';
	                $sejour .= '</div></div>';


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
                            //$field = get_field('prix', $data->ID);
	                        $_product = wc_get_product( $data->ID );
	                        $price = $_product->get_price();
                            $url = wp_get_attachment_url(get_post_thumbnail_id($data->ID));
                            $term_list = wp_get_post_terms($data->ID, 'reservation_type');
                            $type = json_decode(json_encode($term_list), true);

                            $comma = ($i == $len - 1) ? '' : ',';
                            $dayTrip .= '"' . $data->ID . '":';
                            $dayTrip .= '{ "name" : "' . $data->post_title . '","';
                            if (!empty($price)):
                                $dayTrip .= 'price": ' . $price . ',';
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
            $sejour .= '<a class="btn btn-reg grey" href="' . get_site_url() . '/' . SEJOUR_URL . '">' . __('Voir Toutes nos activités', 'online-booking') . '</a>';
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
            $front_form .= '<div class="pure-u-1 pure-u-sm-6-24">';
            $front_form .= wp_dropdown_categories($argsLieux);
            $front_form .= '</div><div class="pure-u-1 pure-u-sm-6-24">';
            $front_form .= wp_dropdown_categories($args);
            $front_form .= '</div><div class="pure-u-1 pure-u-sm-5-24">';
            $front_form .= '<div class="date-wrapper"><input data-value="" name="formdate" value="' . date("d/m/Y") . '" class="datepicker bk-form form-control" id="arrival-form">';
            $front_form .= '<i class="fs1 fa fa-calendar" aria-hidden="true"></i></div>';
            $front_form .= '</div><div class="pure-u-1 pure-u-sm-3-24">';
            $front_form .= '<div class="people-wrapper"><input name="participants" type="number" id="participants-form" value="1" class="bk-form form-control" />';
            $front_form .= '<i class="fs1 fa fa-users" aria-hidden="true"></i></div>';
            $front_form .= '</div><div class="pure-u-1 pure-u-sm-4-24">';
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
     * TODO:clear specific cookies on logout
     */
    public function header_form()
    {
        global $current_user;
        wp_get_current_user();

	    $logoutUrl = get_bloginfo('url').'/coming-soon';
	    $login_url = get_bloginfo('url').'/'.MY_ACCOUNT;
		$is_vendor = ( current_user_can('pending_vendor') || current_user_can('vendor') ) ;
	    $access_account_url = ($is_vendor) ? get_bloginfo('url') . '/'.MY_ACCOUNT_PARTNER : get_bloginfo('url') . '/'
	                                                                                .MY_ACCOUNT;
	    $mailer_url = get_bloginfo('url').'/'.MESSENGER;
	    $unread_count = (fep_get_user_message_count('unread') )?fep_get_user_message_count( 'unread' ):0;

	    $login_args = array(
		    'echo'           => false,
		    'remember'       => true,
		    'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
		    'form_id'        => 'loginform',
		    'id_username'    => 'user_login',
		    'id_password'    => 'user_pass',
		    'id_remember'    => 'rememberme',
		    'id_submit'      => 'wp-submit',
		    'label_username' => __( 'Username' ),
		    'label_password' => __( 'Password' ),
		    'label_remember' => __( 'Remember Me' ),
		    'label_log_in'   => __( 'Log In' ),
		    'value_username' => '',
		    'value_remember' => true
	    );

        //var_dump($current_user);
        if (!is_user_logged_in()):
            $output = '<div id="logger">';
            //$output .= '<a href="#login-popup" class="open-popup-link">';
            //$output .= __('Se connecter', 'twentyfifteen');
            //$output .= '</a>';
	        ///mon-compte/
	        $output .= '<a href="'.$login_url.'" class="login-link">';
	        $output .= __('Se connecter', 'twentyfifteen');
	        $output .= '</a>';
            $output .= '</div>';
            //$output .= '<div id="login-popup" class="white-popup mfp-hide">';
            //$output .= wp_login_form($login_args);
	        //$output .= do_shortcode('[wpuf_profile type="registration" id="1320"]');
            //$output .= '</div>';
        else:
            $output = '<div id="logger">';
	        //__('Mon compte', 'online-booking')
	        //' . __('Déconnexion', 'online-booking') . '
	        $userName = (isset($current_user->user_firstname) && !empty($current_user->user_firstname)) ? $current_user->user_firstname : $current_user->user_login;
	        if(current_user_can('vendor')){
		        $output .= '<a id="mailer-info" href="'.$mailer_url.'">';
		        $output .= '<i class="fa fa-envelope" aria-hidden="true"></i><i class="mail-number">'.$unread_count.'</i>';
		        $output .= '</a>';
	        }

            $output .= '<a class="my-account" href="' . $access_account_url .'" title="accéder à mon compte">';
	        if(get_avatar( $current_user->ID, 52 )){
		        $output .= '<span class="wp-user-avatar">'.get_avatar( $current_user->ID, 52 ).'</span>';
	        }
	        $output .=  $userName. '</a>';
            $output .= '<a class="log-out" href="' . wp_logout_url($logoutUrl) . '" title="déconnexion"><i class="fa fa-power-off" aria-hidden="true"></i></a>';
            $output .= '</div>';
        endif;
        //delete cookies tied to the application
	    //Online_Booking_Public::delete_cookies();
        echo $output;

    }


    /**
     * delete_cookies
     * if receive get parameter log=ftl
     * Clear cookies when log out by user
     * TODO : @logout send error : : Cannot modify header information - headers already sent by...
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
     * Deprecated ?
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
     * Deprecated ?
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
