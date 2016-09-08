<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 07/09/16
 * Time: 19:44
 */

class online_booking_wcvendors{

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

	function custom_menu_link( $pages ) {

/*
		//products
		$pages[ 'prestations' ] = array(
			'slug'			=> get_bloginfo('url').'/'.PARTNER_PRESTATIONS,
			'label'			=> __('Prestations', 'wcvendors-pro' ),
			'actions'		=> array()
		);

		//mon compte -> not necessary, use dasboard
		$pages[ 'my_account' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MY_ACCOUNT,
			'label'			=> __('Mon compte', 'wcvendors-pro' ),
			'actions'		=> array()
		);

		//edit-address
		$pages[ 'edit_adress' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MY_ACCOUNT.'/edit-address/',
			'label'			=> __('Mes adresses', 'wcvendors-pro' ),
			'actions'		=> array()
		);
*/
		//edit-account
		$pages[ 'edit_account' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MY_ACCOUNT.'/edit-account/',
			'label'			=> __('Détails du compte', 'wcvendors-pro' ),
			'actions'		=> array()
		);
		return $pages;
	}

	/**
	 * login_redirect
	 * redirect vendor to dashboard
	 *
	 * @param $redirect_to
	 * @param $user
	 *
	 * @return false|string
	 */
	public function login_redirect( $redirect_to, $user ) {
		$dasboard_partners = get_bloginfo('url').'/'.MY_ACCOUNT_PARTNER;

		if(in_array('vendor',$user->roles) || in_array('pending_vendor',$user->roles)){
			return $dasboard_partners;
		} else {
			return $redirect_to;
		}


	}


	/**
	 * Hook into the product meta save for the auction
	 *
	 * @since 1.0.0
	 */
	public function auction_meta_tab( $tabs ) {

		$tabs[ 'simple_auction' ]  = array(
			'label'  => __( 'CUSTOM FIELDS', 'wcvendors-pro-simple-auctions' ),
			'target' => 'auction',
			'class'  => array( 'auction_tab',  'hide_if_grouped', 'hide_if_external', 'hide_if_variable', 'show_if_simple' ),
		);

		return $tabs;

	} // simple_auction_meta_tab()

	/**
	 * custom_fields_edit_product_form
	 * add custom fields on product-edit tpl
	 *
	 * @since 1.0.0
	 */
	public function custom_fields_edit_product_form( $post_id ){

		echo '<div class="wcv-product-auction auction_product_data tabs-content" id="auction">';

		// Item Condition
		WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_simple_auctions_item_condition', array(
				'post_id'			=> $post_id,
				'id' 				=> '_auction_item_condition',
				'class'				=> 'select2',
				'label'	 			=> __( 'Item Condition', 'wc_simple_auctions' ),
				'desc_tip' 			=> 'true',
				'description' 			=> sprintf( __( 'The condition of the item you are selling', 'wcvendors-pro-simple-auctions' ) ),
				'wrapper_start' 		=> '<div class="all-100">',
				'wrapper_end' 			=> '</div>',
				'options' 			=> array( 'new' => __('New', 'wc_simple_auctions'), 'used'=> __('Used', 'wc_simple_auctions') )
			) )
		);

		// Type of Auction
		WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_simple_auctions_auction_type', array(
				'post_id'                       => $post_id,
				'id'                            => '_auction_type',
				'class'                         => 'select2',
				'label'                         => __( 'Auction Type', 'wc_simple_auctions' ),
				'desc_tip'                      => 'true',
				'description'                   => sprintf( __( 'Type of Auction - Normal prefers high bidders, reverse prefers low bids to win.', 'wcvendors-pro-simple-auctions' ) ),
				'wrapper_start'                 => '<div class="all-100">',
				'wrapper_end'                   => '</div>',
				'options'                       => array( 'normal' => __('Normal', 'wc_simple_auctions'), 'reverse'=> __('Reverse', 'wc_simple_auctions') )
			) )
		);

		// Proxy Options
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_simple_auctions_proxy_bidding', array(
				'post_id'			=> $post_id,
				'id' 				=> '_auction_proxy',
				'label' 			=> __( 'Enable proxy bidding', 'wc_simple_auctions' ),
				'type' 				=> 'checkbox'
			) )
		);

		// Auction Start Price
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_simple_auctions_start_price', array(
				'post_id'		=> $post_id,
				'id' 			=> '_auction_start_price',
				'label' 		=> __( 'Start Price', 'wc_simple_auctions' ) . ' (' . get_woocommerce_currency_symbol() . ')',
				'data_type' 		=> 'price',
				'wrapper_start' 	=> '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100 small-100">',
				'wrapper_end' 		=>  '</div></div>'
			) )
		);

		// Auction Bid Increment
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_simple_auctions_bid_increment', array(
				'post_id'               => $post_id,
				'id'                    => '_auction_bid_increment',
				'label'                 => __( 'Bid increment', 'wc_simple_auctions' ) . ' (' . get_woocommerce_currency_symbol() . ')',
				'data_type'             => 'price',
				'wrapper_start'         => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100 small-100">',
				'wrapper_end'           =>  '</div></div>'
			) )
		);

		// Reserve Price (note the keys are reserved not reserve, as is the auction developers code)
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_simple_auctions_reserved_price', array(
				'post_id'               => $post_id,
				'id'                    => '_auction_reserved_price',
				'label'                 => __( 'Reserve price', 'wc_simple_auctions' ) . ' (' . get_woocommerce_currency_symbol() . ')',
				'data_type'             => 'price',
				'wrapper_start'         => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100 small-100">',
				'wrapper_end'           =>  '</div></div>'
			) )
		);

		// Buy it Now Price
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_simple_auctions_buy_it_now_price', array(
				'post_id'               => $post_id,
				'id'                    => '_buy_it_now_price',
				'label'                 => __( 'Buy it now price', 'wc_simple_auctions' ) . ' (' . get_woocommerce_currency_symbol() . ')',
				'data_type'             => 'price',
				'wrapper_start'         => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100 small-100">',
				'wrapper_end'           =>  '</div></div>'
			) )
		);

		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_simple_auctions_start_date', array(
				'post_id'		=> $post_id,
				'id' 			=> '_auction_dates_from',
				'label' 		=> __( 'From', 'wcvendors-pro-simple-auctions' ),
				'class'			=> 'wcv-datepicker',
				'placeholder'	=> __( 'From&hellip;', 'placeholder', 'wcvendors-pro-simple-auctions' ). ' YYYY-MM-DD',
				'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100 ">',
				'wrapper_end' 	=> '</div></div>',
				'custom_attributes' => array(
					'maxlenth' 	=> '10',
					'pattern' 	=> '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])'
				),
			) )
		);

		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_simple_auctions_end_date', array(
				'post_id'			=> $post_id,
				'id' 				=> '_auction_dates_to',
				'label' 			=> __( 'To', 'wcvendors-pro-simple-auctions' ),
				'class'				=> 'wcv-datepicker',
				'placeholder'		=> __( 'To&hellip;', 'placeholder', 'wcvendors-pro-simple-auctions' ). ' YYYY-MM-DD',
				'wrapper_start' 	=> '<div class="all-50 small-100">',
				'wrapper_end' 		=> '</div>',
				'desc_tip'			=> true,
				'custom_attributes' => array(
					'maxlenth' 		=> '10',
					'pattern' 		=> '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])'
				),
			) )
		);

		echo '</div>';

	} // simple_auctions_form()


}