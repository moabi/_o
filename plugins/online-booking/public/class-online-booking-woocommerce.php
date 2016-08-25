<?php

/**
 * Created by PhpStorm.
 * User: david1
 * Date: 11/05/16
 * Time: 22:21
 */


class onlineBookingWoocommerce
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
     * wc_tpl
     * redirect to plugin tpl
     * @param $page_template
     * @return string
     */
    public function wc_tpl($page_template){

        if (is_singular(array('product','reservation')) ) {
            $page_template = plugin_dir_path(__FILE__) . 'woocommerce/single-product.php';

        }

        return $page_template;
    }

    /**
     * wc_items_to_cart
     * add multiple products to cart
     * @param $product_id
     * @param $quantity
     * @param $variation_id
     * @param $variation
     * @param $cart_item_data
     * @return bool
     */
    public function wc_items_to_cart($product_id = 0, $quantity = 1, $variation_id = 0, $variation = array(), $cart_item_data = array()){

        if( isset($_REQUEST['ut']) && is_user_logged_in()) {
            WC()->cart->add_to_cart($product_id, $quantity);
        }
        return false;
    }

    public function wc_empty_cart() {
        global $woocommerce;
        if( isset($_REQUEST['ut']) && is_user_logged_in() ) {
            //$wc_session  = new WC_Session_Handler();
            //$wc_session->destroy_session();
            //$woocommerce->cart->empty_cart();
        }
    }

    public function wc_add_to_cart($tripID , $item, $state,$from_db = false){
        global $woocommerce;
        WC()->cart->empty_cart();
        WC()->cart->set_session();
        if($from_db == true){
            global $wpdb;
            //LEFT JOIN $wpdb->users b ON a.user_ID = b.ID
            $sql = $wpdb->prepare("
						SELECT *
						FROM ".$wpdb->prefix."online_booking a
						WHERE a.ID = %d
						",$tripID);

            $results = $wpdb->get_results($sql);

            $it = $results[0];
            $item = (isset($results[0])) ? $it->booking_object : $item;
            $budget = json_decode($item, true);

        } else {
            $budget = json_decode($item, true);
        }

        $trips = $budget['tripObject'];

        $days = ($budget['days'] > 1) ? $budget['days'].' jours' : $budget['days'].' jour';
        $place_id = $budget['lieu'];
        $place_trip = get_term_by('id', $place_id, 'lieu');
        $dates = ($budget['arrival'] == $budget['departure']) ? $budget['arrival'] : ' du '.$budget['arrival'].' au '.$budget['departure'];
        $number_participants = (isset($budget['participants'])) ? $budget['participants'] : 1;

        $trip_dates =  array_keys($trips);
        $days_count = 0;
        foreach ($trips as $trip) {
            if (is_array($trip)){
                //  Scan through inner loop
                $trip_id =  array_keys($trip);
                $i = 0;
                foreach ($trip as $value) {

                    $product_id = (isset($trip_id[$i])) ? $trip_id[$i] : 0;
                    $productPrice = (isset($value['price'])) ? $value['price'] : 0;
                    $productName = (isset($value['name'])) ? $value['name'] : 'Undefined Name';

                    //woocommerce calculate price
                    WC()->cart->add_to_cart($product_id, $number_participants);

                    $i++;
                }

                $days_count++;
            }else{

            }
        }

        global $current_user;

        if( ! $current_user )
            return false;
        //SAVE CART IN SESSIOn
        $saved_cart = get_user_meta( $current_user->ID, '_woocommerce_persistent_cart', true );
        if ( $saved_cart ){
            if ( empty( WC()->session->cart ) || ! is_array( WC()->session->cart ) || sizeof( WC()->session->cart ) == 0 ){
                WC()->session->set('cart', $saved_cart['cart'] );
            }

            //WC()->cart->persistent_cart_update();
            //
            //var_dump(WC()->session);
        } else {
            //var_dump('FAIL TO SAVE CART');
        }

        return false;

    }


    /**
     * woo_get_featured_product_ids
     * Get Featured products ID
     * @return array|mixed
     */
    public function woo_get_featured_product_ids() {
        // Load from cache
        $featured_product_ids = get_transient( 'wc_featured_products' );

        // Valid cache found
        if ( false !== $featured_product_ids )
            return $featured_product_ids;

        $featured = get_posts( array(
            'post_type'      => array( 'product', 'product_variation' ),
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'meta_query'     => array(
                array(
                    'key' 		=> '_visibility',
                    'value' 	=> array('catalog', 'visible'),
                    'compare' 	=> 'IN'
                ),
                array(
                    'key' 	=> '_featured',
                    'value' => 'yes'
                )
            ),
            'fields' => 'id=>parent'
        ) );

        $product_ids = array_keys( $featured );
        $parent_ids  = array_values( $featured );
        $featured_product_ids = array_unique( array_merge( $product_ids, $parent_ids ) );

        set_transient( 'wc_featured_products', $featured_product_ids );

        return $featured_product_ids;
    }

    /**
     * remove_product_from_cart
     * add_action( 'template_redirect', 'remove_product_from_cart' );
     * @param int $product_id
     */
    public function remove_product_from_cart($product_id = 0) {
        // Run only in the Cart or Checkout Page
        if( is_cart() || is_checkout() ) {
            // Set the product ID to remove
            $prod_to_remove = $product_id;

            // Cycle through each product in the cart
            foreach( WC()->cart->cart_contents as $prod_in_cart ) {
                // Get the Variation or Product ID
                $prod_id = ( isset( $prod_in_cart['variation_id'] ) && $prod_in_cart['variation_id'] != 0 ) ? $prod_in_cart['variation_id'] : $prod_in_cart['product_id'];

                // Check to see if IDs match
                if( $prod_to_remove == $prod_id ) {
                    // Get it's unique ID within the Cart
                    $prod_unique_id = WC()->cart->generate_cart_id( $prod_id );
                    // Remove it from the cart by un-setting it
                    unset( WC()->cart->cart_contents[$prod_unique_id] );
                }
            }

        }
    }

    public function wc_before(){
        $output = 'TOTOTOTOTOTOTOTOTO';
        return $output;
    }

    public function wc_after(){
        $output = 'TOTOTOTOTOTOTOTOTO';
        return $output;
    }

    /**
     * OVERRIDE WOOCOMMERCE MESSAGES
     */
    /*
     *
     * add_filter( 'woocommerce_checkout_coupon_message',              'override_checkout_coupon_message', 10, 1 );
function override_checkout_coupon_message( $message ) {
    return __( 'Have a coupon for our store?', 'spyr' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter it', 'spyr' ) . '</a>';
}

add_filter( 'woocommerce_checkout_login_message',               'override_checkout_login_message',  10, 1 );
function override_checkout_login_message( $message ) {
    return __('Already have an account with us?', 'spyr' );
}

add_filter( 'woocommerce_lost_password_message',                'override_lost_password_message',   10, 1 );
function override_lost_password_message( $message ) {
    return  __( 'Lost your password? Please enter your username or email address.', 'spyr' );
}

add_filter( 'woocommerce_my_account_my_address_title',          'override_my_address_title',        10, 1 );
function override_my_address_title( $title ) {
    return __( 'Your Address', 'spyr' );
}
add_filter( 'woocommerce_my_account_my_address_description',    'override_my_address_description',  10, 1 );
function override_my_address_description( $description ) {
    return __( 'The following addresses will be used on the checkout.', 'spyr' );
}
add_filter( 'woocommerce_my_account_my_downloads_title',        'override_my_downloads_title',      10, 1 );
function override_my_downloads_title( $title ) {
    return __( 'Your Downloads', 'spyr' );
}
add_filter( 'woocommerce_my_account_my_orders_title',           'override_my_orders_title',         10, 1 );
function override_my_orders_title( $title ) {
    return __( 'Your Most Recent Orders', 'spyr' );
}
    */
}