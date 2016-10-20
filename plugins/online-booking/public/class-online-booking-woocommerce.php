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

	/**
	 *
	 */
    public function wc_empty_cart() {
        global $woocommerce;
        if( isset($_REQUEST['ut']) && is_user_logged_in() ) {
            //$wc_session  = new WC_Session_Handler();
            //$wc_session->destroy_session();
            //$woocommerce->cart->empty_cart();
        }
    }

	/**
	 * wc_add_to_cart
	 * Build a new cart with items
	 *
	 * @param $tripID
	 * @param $item
	 * @param $state
	 * @param bool $from_db
	 *
	 * @return bool
	 */
    public function wc_add_to_cart($tripID , $item, $state,$from_db = false){
        global $woocommerce,$wpdb, $current_user;

        WC()->cart->empty_cart();
        WC()->cart->set_session();

        if($from_db == true){
            //LEFT JOIN $wpdb->users b ON a.user_ID = b.ID
            $sql = $wpdb->prepare("
						SELECT *
						FROM ".$wpdb->prefix."online_booking a
						WHERE a.ID = %d
						",$tripID);

            $results = $wpdb->get_results($sql);

            $it = (isset($results[0])) ? $results[0] : false ;
            $item = ($it) ? $it->booking_object : $item;
            $budget = json_decode($item, true);

        } else {
	        $it = 'class-online-booking-woocommerce.php - l126';
            $budget = json_decode($item, true);
        }

        $trips = $budget['tripObject'];
        $number_participants = (isset($budget['participants'])) ? $budget['participants'] : 1;
        $days_count = 0;

        foreach ($trips as $key => $trip) {
            if (is_array($trip)){

                //  Scan through inner loop
                $trip_id =  array_keys($trip);
                $i = 0;
                foreach ($trip as $value) {

	                $uuid = (isset($value['uuid'])) ? $value['uuid'] : 'undefined';
	                $trip_uuid = (isset($it->trip_id)) ? $it->trip_id : 'undefined';
                    $product_id = (isset($trip_id[$i])) ? $trip_id[$i] : 0;
	                $term_reservation_type = wp_get_post_terms( $product_id, 'reservation_type' );
	                $trip_name = (isset($it->booking_ID)) ? $it->booking_ID : 'undefined';

	                $type = (isset($term_reservation_type[0])) ? $term_reservation_type[0]->name : '';
	                $meta_data = array(
	                    'type'  => $type,
	                    'trip_uuid'  => $trip_uuid,
	                    'trip_name'  => $trip_name
	                );
	                $attributes = array(
		                'reference'  => $uuid,
		                'date'       => $key
	                );
                    //woocommerce calculate price
                    WC()->cart->add_to_cart($product_id, $number_participants,0,$attributes,$meta_data);
                    $i++;
                }
                $days_count++;
            }
        }

        if( !$current_user )
            return false;
        //SAVE CART IN SESSION
        $saved_cart = get_user_meta( $current_user->ID, '_woocommerce_persistent_cart', true );
        if ( $saved_cart ){
            if ( empty( WC()->session->cart ) || ! is_array( WC()->session->cart ) || sizeof( WC()->session->cart ) == 0 ){
                WC()->session->set('cart', $saved_cart['cart'] );
            }
            //WC()->cart->persistent_cart_update();
            //var_dump(WC()->session);
        } else {
            //var_dump('FAIL TO SAVE CART');
        }
        return false;
    }

	/**
	 * @param $product_name
	 * @param $values
	 * @param $cart_item_key
	 *
	 * @return string
	 */
	function ob_add_user_custom_option_from_session_into_cart($product_name, $values, $cart_item_key )
	{

		$variation = (isset($values['variation'])) ? $values['variation'] : false;
		$ref = (isset($variation['reference'])) ? $variation['reference'] : false;
		$date = (isset($variation['date'])) ? $variation['date'] : false;
		/*code to add custom data on Cart & checkout Page*/

			$return_string = $product_name . "</a><dl class='variation'>";
			$return_string .= "<table class='wdm_options_table' id='" . $values['product_id'] . "'>";
			$return_string .= "<tr><td>Type: " . $values['type'] . "</td></tr>";
			if(isset($ref)){
				$return_string .= "<tr><td>Ref: " . $ref . "</td></tr>";
			}
			if(isset($date)){
				$return_string .= "<tr><td>Date: " . $date . "</td></tr>";
			}
			$return_string .= "</table></dl>";
			return $return_string;
	}

	/**
	 * @param $item_id
	 * @param $values
	 */
	function ob_add_values_to_order_item_meta($item_id, $values) {
		global $woocommerce,$wpdb;
		$user_custom_values = $values['type'];
		$item_uuid = $values['uuid'];
		$trip_uuid = $values['trip_uuid'];
		$trip_name = $values['trip_name'];
		if(!empty($user_custom_values))
		{
			wc_add_order_item_meta($item_id,'Type',$user_custom_values);
		}
		if(!empty($item_uuid))
		{
			wc_add_order_item_meta($item_id,'Ref',$user_custom_values);
		}
		if(!empty($trip_uuid))
		{
			wc_add_order_item_meta($item_id,'Ref unique reservation',$user_custom_values);
		}
		if(!empty($trip_name))
		{
			wc_add_order_item_meta($item_id,'Nom reservation',$user_custom_values);
		}

	}

	/**
	 * @param $cart_item_key
	 */
	function ob_remove_user_custom_data_options_from_cart($cart_item_key) {
		global $woocommerce;
		// Get cart
		$cart = $woocommerce->cart->get_cart();
		// For each item in cart, if item is upsell of deleted product, delete it
		foreach( $cart as $key => $values)
		{
			if ( $values['type'] == $cart_item_key )
				unset( $woocommerce->cart->cart_contents[ $key ] );

			if ( $values['uuid'] == $cart_item_key )
				unset( $woocommerce->cart->cart_contents[ $key ] );

			if ( $values['trip_uuid'] == $cart_item_key )
				unset( $woocommerce->cart->cart_contents[ $key ] );

			if ( $values['trip_name'] == $cart_item_key )
				unset( $woocommerce->cart->cart_contents[ $key ] );
		}
	}







		/**
	 * TODO: update booking status
	 * add_filter( 'woocommerce_payment_complete_order_status', 'ob_update_order_status', 10, 2 );
	 * @param $order_status
	 * @param $order_id
	 *
	 * @return string
	 */
	function ob_update_order_status( $order_status, $order_id ) {

	}



	/**
	 * If is needed to add an extra field after order notes
	 * add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );
	 * @param $checkout
	 */
	function my_custom_checkout_field( $checkout ) {
		global $post,$woocommerce;
		$items = $woocommerce->cart->get_cart();
		//var_dump($items);
		foreach($items as $item => $values) {
			$trip_uuid = (isset($values['trip_uuid'])) ? $values['trip_uuid'] : 'undefined';
		}
		foreach($items as $item => $values) {
			$trip_name = (isset($values['trip_name'])) ? $values['trip_name'] : '';
		}

			echo '<div id="trip-id">';

		woocommerce_form_field( 'tripid', array(
			'type'          => 'text',
			'class'         => array('form-row-wide hidden'),
			'default'         => $trip_uuid,
			'id'            => 'tripuuid',
			'custom_attributes' => array(
				'readonly'  => ''
			),
		), $checkout->get_value( 'tripid' ));

		woocommerce_form_field( 'tripname', array(
			'type'          => 'text',
			'class'         => array('trip-name-class form-row-wide'),
			'label'         => __('Nom de votre réservation'),
			'default'         => $trip_name,
			'id'            => 'trip_name',
			'custom_attributes' => array(
				'readonly'  => ''
			),
		), $checkout->get_value( 'tripname' ));

		echo '</div>';

	}

	/**
	 * add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');
	 * @param $order_id
	 */
	function my_custom_checkout_field_update_order_meta( $order_id ) {
		if ($_POST['tripid']) update_post_meta( $order_id, 'Reference', esc_attr($_POST['tripid']));
		if ($_POST['tripname']) update_post_meta( $order_id, 'Nom de la réservation', esc_attr($_POST['tripname']));
	}

	/**
	 * Add the field to order emails
	 * add_filter('woocommerce_email_order_meta_keys', 'my_custom_checkout_field_order_meta_keys');
	 **/

	function my_custom_checkout_field_order_meta_keys( $keys ) {
		$keys[] = 'TRIP ID';
		return $keys;
	}


	/**
	 * Process the checkout
	 * add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');
	 */
	function my_custom_checkout_field_process() {
		// Check if set, if its not set add an error.
		if ( ! $_POST['custom_checkout_field'] )
			wc_add_notice( __( 'Please enter something into this new shiny field.' ), 'error' );
	}



	/**
	 * change tabs order for client dashboard
	 * @param $tabs
	 *
	 * @return mixed
	 */
	function sb_woo_move_description_tab($tabs) {

		$tabs['product']['priority'] = 45;

		return $tabs;
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
        $output = '';
        return $output;
    }

	/**
	 * @param $data
	 *
	 * Strength Settings
	 * 3 = Strong (default)
	 * 2 = Medium
	 * 1 = Weak
	 * 0 = Very Weak / Anything
	 * @return integer
	 */
	public function password_strength() {
		$strength = 0;
		return intval($strength);
	}


	public function add_cart_metadata($order_id){
		echo 'Réservation Onlyoo';
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