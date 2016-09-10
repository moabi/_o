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
	public function lieu_meta_tab( $tabs ) {

		$tabs[ 'lieu' ]  = array(
			'label'  => __( 'Lieu', 'wcvendors-pro' ),
			'target' => 'acf-cat',
			'class'  => array( 'lieu_tab',  'hide_if_grouped', 'hide_if_external', 'hide_if_variable', 'show_if_simple' ),
		);

		$tabs[ 'reglages' ]  = array(
			'label'  => __( 'Réglages', 'wcvendors-pro' ),
			'target' => 'wcv-acf-reglages',
			'class'  => array( 'reglages_tab',  'hide_if_grouped', 'hide_if_external', 'hide_if_variable', 'show_if_simple' ),
		);

		return $tabs;

	} // simple_auction_meta_tab()

	/**
	 * custom_fields_edit_product_form
	 * add custom fields on product-edit tpl
	 *
	 *
	 * @since 1.0.0
	 */
	public function reglages_edit_product_form( $post_id ){

		echo '<div class="wcv-acf-reglages reglages_product_data tabs-content" id="wcv-acf-reglages">';
		//themes
		WCVendors_Pro_Form_Helper::select( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_theme',
				'class'				=> 'select2',
				'label'				=> __('Thème', 'wcvendors-pro'),
				'show_option_none'	=> '',
				'taxonomy'			=>	'theme',
				'taxonomy_args'		=> array(
					'hide_empty'	=> 0,
				),
			)
		);

		//nombre de personnes
		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_people',
				'class'				=> '',
				'label'				=> __('nombre de personnes', 'wcvendors-pro'),
				'placeholder'       => '2',
				'type'              => 'number',
				'name'              => 'nombre_de_personnes'

			)
		);

		//nombre de personnes
		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_duree',
				'class'				=> '',
				'label'				=> __('Durée de la prestation', 'wcvendors-pro'),
				'placeholder'       => '2',
				'type'              => 'number',
				'name'              => 'duree'

			)
		);

		echo '</div>';

	} // lieu_edit_product_form()


	/**
	 * custom_fields_edit_product_form
	 * add custom fields on product-edit tpl
	 *
	 * TODO: get custom taxonomies,add map geolocalisation
	 *
	 * @since 1.0.0
	 */
	public function lieu_edit_product_form( $post_id ){

		echo '<div class="wcv-product-lieu lieu_product_data tabs-content" id="acf-cat">';

		WCVendors_Pro_Form_Helper::select( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_lieu',
				'class'				=> 'select2',
				'label'				=> __('Lieu', 'wcvendors-pro'),
				'show_option_none'	=> '',
				'taxonomy'			=>	'lieu',
				'taxonomy_args'		=> array(
					'hide_empty'	=> 0,
					'orderby'            => 'NAME',
					'order'              => 'ASC',
					'value_field'	     => 'term_id'
				),
			)
		);
		//descriptif du lieu
		WCVendors_Pro_Form_Helper::textarea( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_lieu_desc',
				'class'				=> '',
				'label'				=> __('Donnez un descriptif du lieu', 'wcvendors-pro'),

			)
		);

		/*
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
*/
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

	} // lieu_edit_product_form()

	/**
	 * add_action( 'wcv_save_product', 'save_custom_taxonomy' );
	 * @param $post_id
	 */
	public function save_lieu( $post_id ){

		//save taxonomies
		$term = (isset($_POST[ 'wcv_custom_product_lieu' ])) ? $_POST[ 'wcv_custom_product_lieu' ]: '';
		$meta_value_lieu_desc = (isset($_POST[ 'wcv_custom_product_lieu_desc' ])) ? $_POST[ 'duree' ]: 0;
		wp_set_post_terms( $post_id, $term, 'lieu' );
		update_post_meta($post_id, 'lieu', $meta_value_lieu_desc);

		$term_theme = (isset($_POST[ 'wcv_custom_product_theme' ])) ?$_POST[ 'wcv_custom_product_theme' ]: '';
		wp_set_post_terms( $post_id, $term_theme, 'theme' );

		//save custom field
		$meta_value_people = (isset($_POST[ 'nombre_de_personnes' ])) ? $_POST[ 'nombre_de_personnes' ]: 1;
		$meta_value_duree = (isset($_POST[ 'duree' ])) ? $_POST[ 'duree' ]: 0;
		update_post_meta($post_id, 'nombre_de_personnes', $meta_value_people);
		update_post_meta($post_id, 'duree', $meta_value_duree);

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