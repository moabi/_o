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

	/**
	 * @param $args
	 *
	 * @return mixed
	 */
	public function custom_wcv_product_title( $args ){
		$args['label'] = 'Nouvelle activité';
		return $args;
	}
	public function custom_wcv_product_description( $args ){
		$args['label'] = 'Description';
		return $args;
	}
	public function custom_wcv_product_short_description( $args ){
		$args['label'] = 'Description rapide';
		return $args;
	}
	public function custom_wcv_product_save_button( $args ){
		$args['label'] = 'Enregistrer activité';
		return $args;
	}

	/**
	 * custom_menu_link
	 * add items to the dashboard pro menu
	 * @param $pages
	 *
	 * @return mixed
	 */
	public function custom_menu_link( $pages ) {
		//messenger
		$pages[ 'Messagerie' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MESSENGER,
			'label'			=> __('Messagerie', 'wcvendors-pro' ),
			'actions'		=> array()
		);
		//edit-account
		$pages[ 'edit_account' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MY_ACCOUNT.'/edit-account/',
			'label'			=> __('Mon compte', 'wcvendors-pro' ),
			'actions'		=> array()
		);
		//add product
		$pages[ 'edit' ] = array(
			'slug'			=> get_bloginfo('url').'/dashboard/product/edit/',
			'label'			=> __('Ajouter activité', 'wcvendors-pro' ),
			'actions'		=> array()
		);
		$pages[ 'settings' ] = array(
			'slug'			=> '',
			'label'			=> '',
			'actions'		=> array()
		);



		return $pages;
	}

	/**
	 * @param $dashboard_urls
	 *
	 * @return mixed
	 */
	public function change_dashboard_labels( $dashboard_urls ){
		// Products
		if ( array_key_exists('product', $dashboard_urls ) ){
			$dashboard_urls[ 'product' ][ 'label' ] = __('Mes prestations', 'wcvendors-pro' );
		}
		if ( array_key_exists('order', $dashboard_urls ) ){
			$dashboard_urls[ 'order' ][ 'label' ] = __('Réservations', 'wcvendors-pro' );
		}

		return $dashboard_urls;
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
			'label'  => __( 'Lieu*', 'wcvendors-pro' ),
			'target' => 'acf-cat',
			'class'  => array( 'lieu_tab',  'hide_if_grouped', 'hide_if_external', 'hide_if_variable', 'show_if_simple' ,'js-show-gmap'),
		);

		$tabs[ 'reglages' ]  = array(
			'label'  => __( 'Réglages*', 'wcvendors-pro' ),
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
		$utils = new online_booking_utils();
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
		$people_value = (get_field('nombre_de_personnes')) ? get_field('nombre_de_personnes') : '';
		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_people',
				'class'				=> '',
				'label'				=> __('nombre de personnes', 'wcvendors-pro'),
				'placeholder'       => '2',
				'type'              => 'number',
				'name'              => 'nombre_de_personnes',
				'value'             => get_post_meta( $post_id, 'nombre_de_personnes', true ),
				'custom_attributes' => array(
					'data-rules' => 'required', // Change 'required' to '' to make it not required (just remove the word required but keep the single quotes)
					'data-error' => __( 'This field is required.', 'wcvendors-pro' )
				)

			)
		);

		//infos_pratiques
		WCVendors_Pro_Form_Helper::textarea( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_infos_pratiques',
				'class'				=> '',
				'label'				=> __('Renseignez les informations pratiques :', 'wcvendors-pro'),
				'value'             => get_post_meta( $post_id, 'lieu', true ),
				'custom_attributes' => array(
					'data-rules' => 'required', // Change 'required' to '' to make it not required (just remove the word required but keep the single quotes)
					'data-error' => __( 'This field is required.', 'wcvendors-pro' )
				)

			)
		);

		//Durée
		echo '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">';
		echo "<h3>".__('Durée de la prestation')."</h3>";
		echo '</div></div>';

		echo '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-20 small-100">';
		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_duree_j',
				'class'				=> 'half',
				'label'				=> __('Jours', 'wcvendors-pro'),
				'placeholder'       => '0',
				'type'              => 'number',
				'name'              => 'duree-j',
				'value'             => get_post_meta( $post_id, 'duree-j', true )

			)
		);
		echo '</div>';
		echo '<div class="all-5 small-100">&nbsp;</div>';
		echo '<div class="all-20 small-100">';

		//get_post_meta( $post_id, 'duree-s', true )
		/**
		 * Help:
		 * to get options from a select :
		 * $field = get_field_object('field_56a33e7db847e');
		 * $durees_opt = (isset($field['choices'])) ? $field['choices'] : array('error : mising key field !!');
		 */

		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_duree',
				'class'				=> 'half',
				'label'				=> __('Heures (max 24)', 'wcvendors-pro'),
				'placeholder'       => '2',
				'type'              => 'number',
				'name'              => 'duree',
				'value'             => get_post_meta( $post_id, 'duree', true )

			)
		);
		echo '</div>';
		echo '<div class="all-5 small-100">&nbsp;</div>';
		echo '<div class="all-20 small-100">';
		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_duree_m',
				'class'				=> 'half',
				'label'				=> __('Minutes (max 60)', 'wcvendors-pro'),
				'placeholder'       => '00',
				'type'              => 'number',
				'name'              => 'duree-m',
				'value'             => get_post_meta( $post_id, 'duree-m', true )

			)
		);

		//var_dump(get_field_object('duree-s'));
		echo '</div>';
		echo '</div>';



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
				'value'             => get_post_meta( $post_id, 'lieu', true ),
				'custom_attributes' => array(
					'data-rules' => 'required', // Change 'required' to '' to make it not required (just remove the word required but keep the single quotes)
					'data-error' => __( 'This field is required.', 'wcvendors-pro' )
				)

			)
		);

		//GOOGLE MAP GEOCODING
		//get_post_meta( $post_id, 'duree-m', true )
		$gmap = get_post_meta( $post_id, 'gps', true );
		$gmap_adress = (isset($gmap['location'])) ? $gmap['location'] : '';
		$gmap_lat = (isset($gmap['latitude'])) ? $gmap['latitude'] : '';
		$gmap_long = (isset($gmap['longitude'])) ? $gmap['longitude'] : '';

		echo '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-60 small-100">';
		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'address',
				'class'				=> 'half',
				'label'				=> __('Adresse de la prestation', 'wcvendors-pro'),
				'placeholder'       => '8 rue de verdun, Monptellier, 34000',
				'type'              => 'text',
				'name'              => 'gmap-adress-geocoding',
				'value'             => $gmap_adress

			)
		);

		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'address-lat',
				'type'              => 'hidden',
				'name'              => 'address-lat',
				'value'             => $gmap_lat

			)
		);
		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'address-long',
				'type'              => 'hidden',
				'name'              => 'address-long',
				'value'             => $gmap_long

			)
		);
		echo '</div>';
		echo '<div class="all-5 small-100">&nbsp;</div>';
		echo '<div class="all-30 small-100"><div class="control-group"><label class="clearfix">&nbsp;</label>';
		echo '<button id="gmap-geocoding-btn" class="btn btn-reg btn-primary">'.("Trouver mon adresse").'</button>';
		echo '</div></div></div>';

		$map = '<div id="map" class="gmap-vendor" style="width: 100%;min-height:300px;display: block;margin:1em 0;"></div>';

		$map .= "<script>
			function initMap() {
				var map = new google.maps.Map(document.getElementById('map'), {
			    zoom: 12,
			    center: {lat: 43.550809, lng: 3.906089}
			  });
			}
			
			function geocodeAddress(geocoder, resultsMap) {
				var address = document.getElementById('address').value;
				geocoder.geocode({'address': address}, function(results, status) {
					if (status === google.maps.GeocoderStatus.OK) {
						var latitude = results[0].geometry.location.lat();
						var longitude = results[0].geometry.location.lng();
			             var mapOptions = {
			                 zoom: 14,
			                 center: latlng,
			                 mapTypeId: google.maps.MapTypeId.ROADMAP
			             };
			
			             map = new google.maps.Map(document.getElementById('map'), mapOptions);
			
			             var latlng = new google.maps.LatLng(latitude, longitude);
			             map.setCenter(latlng);
			
			             var marker = new google.maps.Marker({
			                 map: map,
			                 position: latlng,
			                 zoom:15
			             });
			             jQuery('#address-lat').val(latitude);
			             jQuery('#address-long').val(longitude);
			    } else {
						alert('Geocode was not successful for the following reason: ' + status);
					}
				});
			}
			jQuery('.js-show-gmap').click(function(){
			    setTimeout(function(){
			        google.maps.event.trigger(map, 'resize');
			    },300);
			    
			});
			jQuery('#gmap-geocoding-btn').click(function(e) {
			  e.preventDefault();
			  var gmapAdress = $('#gmap-geocoding').val();
			  var geocoder = new google.maps.Geocoder();
			  geocodeAddress(geocoder, map);
			  
			});
    	</script>";
		$map .= '<script src="https://maps.googleapis.com/maps/api/js?key='.GMAP_APIKEY
		        .'&signed_in=true&callback=initMap"
        async defer></script>';



		echo $map;

		echo '</div>';

	} // lieu_edit_product_form()

	/**
	 * add_action( 'wcv_save_product', 'save_custom_taxonomy' );
	 * @param $post_id
	 */
	public function save_lieu( $post_id ){

		//save taxonomies
		$term = (isset($_POST[ 'wcv_custom_product_lieu' ])) ? $_POST[ 'wcv_custom_product_lieu' ]: '';
		$meta_value_lieu_desc = (isset($_POST[ 'wcv_custom_product_lieu_desc' ])) ? $_POST[ 'wcv_custom_product_lieu_desc' ]: '';
		wp_set_post_terms( $post_id, $term, 'lieu' );
		update_post_meta($post_id, 'lieu', $meta_value_lieu_desc);

		$term_theme = (isset($_POST[ 'wcv_custom_product_theme' ])) ?$_POST[ 'wcv_custom_product_theme' ]: '';
		wp_set_post_terms( $post_id, $term_theme, 'theme' );

		//save custom field on settings tab
		$meta_value_people = (isset($_POST[ 'nombre_de_personnes' ])) ? $_POST[ 'nombre_de_personnes' ]: 1;
		$meta_value_infos_pratiques = (isset($_POST[ 'wcv_custom_product_infos_pratiques' ])) ? $_POST[ 'wcv_custom_product_infos_pratiques' ]: '';
		$meta_value_duree_j = (isset($_POST[ 'duree-j' ])) ? $_POST[ 'duree-j' ]: 0;
		$meta_value_duree = (isset($_POST[ 'duree' ])) ? $_POST[ 'duree' ]: 0;
		$meta_value_duree_m = (isset($_POST[ 'duree-m' ])) ? $_POST[ 'duree-m' ]: 0;
		$meta_value_address = (isset($_POST[ 'gmap-adress-geocoding' ])) ? $_POST[ 'gmap-adress-geocoding' ]: '';
		$meta_value_address_long = (isset($_POST[ 'address-long' ])) ? $_POST[ 'address-long' ]: '';
		$meta_value_address_lat = (isset($_POST[ 'address-lat' ])) ? $_POST[ 'address-lat' ]: '';
		$gmap = array(
			'address'  =>   $meta_value_address,
			'lng'       =>  $meta_value_address_long,
			'lat'       =>  $meta_value_address_lat,
			'zoom'      => 14
		);

		update_post_meta($post_id, 'nombre_de_personnes', $meta_value_people);
		update_post_meta($post_id, 'infos_pratiques', $meta_value_infos_pratiques);
		update_post_meta($post_id, 'duree-j', $meta_value_duree_j);
		update_post_meta($post_id, 'duree', $meta_value_duree);
		update_post_meta($post_id, 'duree-m', $meta_value_duree_m);
		//update_post_meta($post_id, 'gps', $gmap);
		update_field('gps', $gmap, $post_id);


	}

	/**
	 *
	 */
	public function load_payment_page()
	{

		global $woocommerce;
		$base_dir			= plugin_dir_path( dirname( __FILE__ ) );

		$vendor_id = get_current_user_id();

		$store_name = get_user_meta( $vendor_id, 'pv_shop_name', true );
		$store_description = get_user_meta( $vendor_id, 'pv_shop_description', true );
		$shipping_disabled			= WCVendors_Pro::get_option( 'shipping_management_cap' );
		$shipping_methods 			= $woocommerce->shipping->load_shipping_methods();
		$shipping_method_enabled	= ( array_key_exists( 'wcv_pro_vendor_shipping', $shipping_methods ) &&         $shipping_methods['wcv_pro_vendor_shipping']->enabled == 'yes' ) ? true : 0;

		wc_get_template( 'store-payment.php', array(
			'store_name' 				=> $store_name,
			'store_description' 		=> $store_description,
			'shipping_disabled'			=> $shipping_disabled,
			'shipping_method_enabled'	=> $shipping_method_enabled,
		),
			'wc-vendors/dashboard/', $base_dir . 'templates/dashboard/' );
	} // load_order_page()

	/**
	 * rename product tab in vendor dashboard
	 * @return mixed
	 */
	public function custom_wcv_shipping_tab() {
		$args['title'] = 'Envois';
		return $args;
	}


}