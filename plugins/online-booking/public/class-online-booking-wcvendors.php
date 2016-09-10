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

		//edit-account
		$pages[ 'product' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MY_ACCOUNT_PARTNER.'/product/',
			'label'			=> __('Mes prestations', 'wcvendors-pro' ),
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
			'label'  => __( 'Catégories', 'wcvendors-pro-acf-cat' ),
			'target' => 'acf-cat',
			'class'  => array( 'auction_tab',  'hide_if_grouped', 'hide_if_external', 'hide_if_variable', 'show_if_simple' ),
		);

		return $tabs;

	} // simple_auction_meta_tab()


	/**
	 * custom_fields_edit_product_form
	 * add custom fields on product-edit tpl
	 *
	 * TODO: get custom taxonomies,add map geolocalisation
	 *
	 * @since 1.0.0
	 */
	public function custom_fields_edit_product_form( $post_id ){


		$sel_theme = (isset($_POST["cat"])) ? intval($_POST["cat"]) : false;
		$args = array(
			'show_option_all'    => '',
			'show_option_none'   => '',
			'option_none_value'  => '-1',
			'orderby'            => 'NAME',
			'order'              => 'ASC',
			'show_count'         => 0,
			'hide_empty'         => true,
			'child_of'           => 0,
			'exclude'            => '',
			'echo'               => 0,
			'selected'           => $sel_theme,
			'hierarchical'       => 0,
			'name'               => 'cat',
			'id'                 => 'theme',
			'class'              => 'postform terms-change form-control',
			'depth'              => 0,
			'tab_index'          => 0,
			'taxonomy'           => 'theme',
			'hide_if_empty'      => true,
			'value_field'	     => 'term_id',
		);


		echo '<div class="wcv-product-auction auction_product_data tabs-content" id="acf-cat">';

		WCVendors_Pro_Form_Helper::select( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_custom_taxonomy',
				'class'				=> 'select2',
				'label'				=> __('Lieu', 'wcvendors-pro'),
				'show_option_none'	=> __('Lieu de la prestation', 'wcvendors-pro'),
				'taxonomy'			=>	'lieu',
				'taxonomy_args'		=> array(
					'hide_empty'	=> 0,
				),
			)
		);

		WCVendors_Pro_Form_Helper::select( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_custom_taxonomy',
				'class'				=> 'select2',
				'label'				=> __('Thème', 'wcvendors-pro'),
				'show_option_none'	=> __('Thème de la prestation', 'wcvendors-pro'),
				'taxonomy'			=>	'theme',
				'taxonomy_args'		=> array(
					'hide_empty'	=> 0,
				),
			)
		);

		/*
		WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_simple_auctions_auction_type', array(
				'post_id'                       => $post_id,
				'id'                            => '_auction_type',
				'class'                         => 'select2',
				'label'                         => __( 'Type de prestation', 'wc_simple_auctions' ),
				'desc_tip'                      => 'true',
				'description'                   => sprintf( __( 'Le type de prestation ou de public',
					'wcvendors-pro-simple-auctions' ) ),
				'wrapper_start'                 => '<div class="all-100">',
				'wrapper_end'                   => '</div>',
				'options'                       => array( 'normal' => __('Normal', 'wc_simple_auctions'), 'reverse'=> __('Reverse', 'wc_simple_auctions') )
			) )
		);*/

		echo '</div>';

	} // simple_auctions_form()

	/**
	 * add_action( 'wcv_save_product', 'save_custom_taxonomy' );
	 * @param $post_id
	 */
	public function save_custom_taxonomy( $post_id ){
		$term = $_POST[ 'wcv_custom_product_custom_taxonomy' ];
		wp_set_post_terms( $post_id, $term, 'custom_taxonomy' );
	}


	/**
	 * rename product tab in vendor dashboard
	 * @return mixed
	 */
	public function custom_wcv_shipping_tab() {
		$args['title'] = 'Envois';
		return $args;
	}


}