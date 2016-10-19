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
		$args['label'] = 'Titre de la prestation';
		$args['placeholder'] = 'Ex:saut en parachute,préparez-vous à frissoner!';
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
	 * infos pratiques
	 * @param $post_id
	 */
	public function infos_edit_product_form( $post_id ){
		echo '<div class="wcv-acf-reglages reglages_product_data tabs-content" id="wcv-acf-infos">';
		//infos_pratiques
		WCVendors_Pro_Form_Helper::textarea( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_infos_pratiques',
				'class'				=> '',
				'label'				=> __('<strong>Ajoutez vos informations pratiques</strong> (<em>ce qui est important de 
				savoir 
				autour de l\'activité.</em>)', 'wcvendors-pro'),
				'value'             => get_post_meta( $post_id, 'infos_pratiques', true ),
				'custom_attributes' => array(
					'data-rules' => 'required', // Change 'required' to '' to make it not required (just remove the word required but keep the single quotes)
					'data-error' => __( 'Champs obligatoire', 'wcvendors-pro' )
				)

			)
		);
		echo '</div>';
	}

	public function sold_indiv_edit_product_form( $post_id ){
		echo '<div id="wcv-acf-price-indiv">';
		//infos_pratiques
		echo '<div class="control-group">';
		echo '<input type="checkbox" id="sold_individually" name="sold_individually" /><label for="">Ce prix est forfaitaire.</label>';
		echo '</div></div>';
	}

	/**
	 * type & categories
	 * @param $post_id
	 */
	public function type_edit_product_form( $post_id ){
		echo '<div class="wcv-acf-reglages reglages_product_data tabs-content" id="wcv-acf-reglages">';
		//themes
		/*
		WCVendors_Pro_Form_Helper::select( array(
				'post_id'			=> $post_id,
				'id'				=> 'tax_theme',
				'class'				=> 'select2',
				'label'				=> __('Type de public visé', 'wcvendors-pro'),
				'show_option_none'	=> '',
				'taxonomy'			=>	'theme',
				'taxonomy_args'		=> array(
					'hide_empty'	=> 0,
				),
			)
		);
		*/

		$terms = get_terms( array(
			'taxonomy' => 'theme',
			'hide_empty' => false,
			'orderby'            => 'NAME',
			'order'              => 'ASC'
		) );
		$selected_terms = wp_get_post_terms( $post_id, 'theme');
		$sel_place = array();
		foreach ($selected_terms as $selected_term){
			$sel_place[] = $selected_term->term_id;
		}

		echo '<div class="control-group"><div class="control">';
		echo '<label for="product_lieu">Choisir un ou plusieurs type de public visé </label><br />';
		echo '<select id="tax_theme" name="tax_theme[]" class="select2" multiple="multiple" tabindex="-1">';
		foreach ($terms as $term){
			$sel = (in_array($term->term_id,$sel_place ))? 'selected="selected"' : '';
			echo '<option value="'.$term->term_id.'" '.$sel.'>'.$term->name.'</option>';
		}
		echo '</select>';
		echo '</div></div>';

		//type
		WCVendors_Pro_Form_Helper::select( array(
				'post_id'			=> $post_id,
				'id'				=> 'tax_type',
				'class'				=> 'select2',
				'label'				=> __('Catégorie de la prestation', 'wcvendors-pro'),
				'show_option_none'	=> '',
				'taxonomy'			=>	'reservation_type',
				'taxonomy_args'		=> array(
					'hide_empty'	=> 0,
					//'parent'        => 0
				),
			)
		);
/*
		WCVendors_Pro_Form_Helper::select( array(
				'post_id'			=> $post_id,
				'id'				=> 'tax_type_child',
				'class'				=> 'select2',
				'label'				=> __('Sous-catégorie de la prestation', 'wcvendors-pro'),
				'show_option_none'	=> '',
				'taxonomy'			=>	'reservation_type',
				'taxonomy_args'		=> array(
					'hide_empty'	=> 0,
					'parent'        => 1,
					'child_of'        => 0
				),
			)
		);*/
		//WCVendors_Pro_Product_Form::tags( $object_id, true );
		echo '</div>';
	}

	/**
	 * custom_fields_edit_product_form
	 * add custom fields on product-edit tpl
	 *
	 *
	 * @since 1.0.0
	 */
	public function reglages_edit_product_form( $post_id ){

		echo '<div class="wcv-acf-reglages" id="wcv-time-peoples">';
		//Durée
		echo '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">';
		echo "<strong>".__('Durée de la prestation')."</strong>";
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
				'value'             => get_post_meta( $post_id, 'duree-j', true ),
				'custom_attributes' => array(
					'max' => '4'
				)

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
				'value'             => get_post_meta( $post_id, 'duree', true ),
				'custom_attributes' => array(
					'max' => '24'
				)

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
				'value'             => get_post_meta( $post_id, 'duree-m', true ),
				'custom_attributes' => array(
					'max' => '60'
				)

			)
		);

		//var_dump(get_field_object('duree-s'));
		echo '</div>';

		echo '<div class="all-5 small-100">&nbsp;</div>';
		echo '<div class="all-25 small-100">';
		//nombre de personnes
		$people_value = (get_post_meta( $post_id, 'nombre_de_personnes', true )) ? get_field('nombre_de_personnes',$post_id) : 0;
		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_people',
				'class'				=> '',
				'label'				=> __('nombre de personnes', 'wcvendors-pro'),
				'placeholder'       => '2',
				'type'              => 'number',
				'name'              => 'nombre_de_personnes',
				'value'             => $people_value,
				'custom_attributes' => array(
					'data-rules' => 'required', // Change 'required' to '' to make it not required (just remove the word required but keep the single quotes)
					'data-error' => __( 'Champs obligatoire', 'wcvendors-pro' )
				)

			)
		);

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


		$terms = get_terms( array(
			'taxonomy' => 'lieu',
			'hide_empty' => false,
			'orderby'            => 'NAME',
			'order'              => 'ASC'
		) );

		$selected_terms = wp_get_post_terms( $post_id, 'lieu');
		$sel_place = array();
		foreach ($selected_terms as $selected_term){
			$sel_place[] = $selected_term->term_id;
		}

		echo '<div class="control-group"><div class="control">';
		echo '<label for="product_lieu">Choisir un ou plusieurs lieu </label><br />';
		echo '<select id="product_lieu" name="product_lieu[]" class="select2" multiple="multiple" tabindex="-1">';
		foreach ($terms as $term){
			$sel = (in_array($term->term_id,$sel_place ))? 'selected="selected"' : '';
			echo '<option value="'.$term->term_id.'" '.$sel.'>'.$term->name.'</option>';
		}
		echo '</select>';
		echo '</div></div>';

		//descriptif du lieu
		WCVendors_Pro_Form_Helper::textarea( array(
				'post_id'			=> $post_id,
				'id'				=> 'wcv_custom_product_lieu_desc',
				'class'				=> '',
				'label'				=> __('Donnez un descriptif du lieu', 'wcvendors-pro'),
				'value'             => get_post_meta( $post_id, 'lieu', true ),
				'custom_attributes' => array(
					'data-rules' => 'required', // Change 'required' to '' to make it not required (just remove the word required but keep the single quotes)
					'data-error' => __( 'Champs obligatoire', 'wcvendors-pro' )
				)

			)
		);

		//GOOGLE MAP GEOCODING
		$gmap_key = esc_attr( get_option('ob_gmap_key') );
		$gmap_acf = (get_post_meta( $post_id, 'gps', true )) ? get_field('gps',$post_id) : false;
		//var_dump($gmap_acf);
		$gmap_address = (isset($gmap_acf['address'])) ? $gmap_acf['address'] : '';
		$is_address_defined = (isset($gmap_acf['lat'])) ? 'true' : 'false';
		$gmap_lat = (isset($gmap_acf['lat']) && !empty($gmap_acf['lat'])) ? $gmap_acf['lat'] : '43.538585';//43.538585, 4.133967 le Grau -du-roi
		$gmap_long = (isset($gmap_acf['lng']) && !empty($gmap_acf['lng'])) ? $gmap_acf['lng'] : '4.133967';

		echo '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-60 small-100">';
		WCVendors_Pro_Form_Helper::input( array(
				'post_id'			=> $post_id,
				'id'				=> 'address',
				'class'				=> 'half',
				'label'				=> __('Adresse de la prestation', 'wcvendors-pro'),
				'placeholder'       => 'Le Grau-du-roi',
				'type'              => 'text',
				'name'              => 'gmap-adress-geocoding',
				'value'             => $gmap_address

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
			    var userAddress = ".$is_address_defined.";
			    var myLatLng = {lat: ".$gmap_lat.", lng: ".$gmap_long."};
				var map = new google.maps.Map(document.getElementById('map'), {
			    zoom: 10,
			    center: myLatLng
			  });
			  if(userAddress){
			    var marker = new google.maps.Marker({
				    position: myLatLng,
				    map: map,
				    title: 'Adresse de la prestation'
				  });
			  }
			   
			}
			
			function geocodeAddress(geocoder, resultsMap) {
				var address = document.getElementById('address').value;
				geocoder.geocode({'address': address}, function(results, status) {
					if (status === google.maps.GeocoderStatus.OK) {
						var latitude = results[0].geometry.location.lat();
						var longitude = results[0].geometry.location.lng();
			             var mapOptions = {
			                 zoom: 10,
			                 center: latlng,
			                 mapTypeId: google.maps.MapTypeId.ROADMAP
			             };
			
			             map = new google.maps.Map(document.getElementById('map'), mapOptions);
			
			             var latlng = new google.maps.LatLng(latitude, longitude);
			             map.setCenter(latlng);
			
			             var marker = new google.maps.Marker({
			                 map: map,
			                 position: latlng,
			                 zoom:10
			             });
			             jQuery('#address-lat').val(latitude);
			             jQuery('#address-long').val(longitude);
			    } else {
						console.warn('Geocode was not successful for the following reason: ' + status);
						$('input#address').css('background-color','rgba(255, 0, 0, 0.14)');
					}
				});
			}
			jQuery('.js-show-gmap').click(function(){
			    setTimeout(function(){
			        initMap();
			    },300);
			    
			});
			jQuery('#gmap-geocoding-btn').click(function(e) {
			  e.preventDefault();
			  var gmapAdress = $('#gmap-geocoding').val();
			  var geocoder = new google.maps.Geocoder();
			  geocodeAddress(geocoder, map);
			  
			});
    	</script>";
		$map .= '<script src="https://maps.googleapis.com/maps/api/js?key='.$gmap_key
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
		$places = (isset($_POST['product_lieu'])) ? $_POST['product_lieu']: array();
		$meta_value_lieu_desc = (isset($_POST[ 'wcv_custom_product_lieu_desc' ])) ? $_POST[ 'wcv_custom_product_lieu_desc' ]: '';
		$term_theme = (isset($_POST[ 'tax_theme' ])) ?$_POST[ 'tax_theme' ]: '';
		//save custom field on settings tab
		$meta_value_people = (isset($_POST[ 'nombre_de_personnes' ])) ? $_POST[ 'nombre_de_personnes' ]: 1;
		$meta_value_infos_pratiques = (isset($_POST[ 'wcv_custom_product_infos_pratiques' ])) ? $_POST[ 'wcv_custom_product_infos_pratiques' ]: '';
		$meta_value_duree_j = (isset($_POST[ 'duree-j' ])) ? intval($_POST[ 'duree-j' ]): '0';
		$meta_value_duree_m = (isset($_POST[ 'duree-m' ])) ? intval($_POST[ 'duree-m' ]): '0';
		$meta_value_duree = (isset($_POST[ 'duree' ])) ? intval($_POST[ 'duree' ]): '0';

		//GMAP localisation
		$meta_value_address = (isset($_POST[ 'gmap-adress-geocoding' ])) ? $_POST[ 'gmap-adress-geocoding' ]: '';
		$meta_value_address_long = (isset($_POST[ 'address-long' ])) ? $_POST[ 'address-long' ]: '';
		$meta_value_address_lat = (isset($_POST[ 'address-lat' ])) ? $_POST[ 'address-lat' ]: '';
		$gmap_value = array(
			'address'  =>   $meta_value_address,
			'lng'       =>  $meta_value_address_long,
			'lat'       =>  $meta_value_address_lat
		);

		$meta_sold_individually = (isset($_POST[ 'address-lat' ])) ? true: false;
		$term_type = (isset($_POST[ 'tax_type' ])) ?$_POST[ 'tax_type' ]: '';

		//update fields
		update_post_meta($post_id, 'gps',$gmap_value );
		wp_set_post_terms( $post_id, $term_theme, 'theme' );
		wp_set_post_terms( $post_id, $places, 'lieu' );
		update_post_meta($post_id, 'lieu', $meta_value_lieu_desc);
		wp_set_post_terms( $post_id, $term_type, 'reservation_type' );
		update_post_meta($post_id, 'nombre_de_personnes', $meta_value_people);
		update_post_meta($post_id, 'infos_pratiques', $meta_value_infos_pratiques);
		update_post_meta($post_id, 'duree-j', $meta_value_duree_j);
		update_post_meta($post_id, 'duree', $meta_value_duree);
		update_post_meta($post_id, 'duree-m', $meta_value_duree_m);
		update_post_meta($post_id, 'sold_individually',$meta_sold_individually);
	}

	/**
	 * load_payment_page
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



	/**
	 * product_header_table
	 * product listing vendor head & footer
	 * /Applications/MAMP/htdocs/onlyoo/wp-content/plugins/online-booking/
	 * add_filter( 'wcv_product_table_actions_path', 'product_header_table' );
	 * @return string
	 */
	function product_header_table(){

		return get_wp_attachment_filter_plugin_dir().'public/wcvendors/partials/product/wcvendors-pro-table-actions.php';
	}


	/**
	 * Could be used to automatically populate the dashboard menu
	 * $this->loader->add_filter( 'wp_nav_menu_items',$plugin_wcvendors, 'vendor_menu_items', 10, 2 );
	 * @param $items
	 * @param $args
	 *
	 * @return string
	 */
	public function vendor_menu_items( $items, $args ) {

		$class_attr = 'class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1407"';
		$page_obj = get_page_by_path('parent-page/sub-page');

		if (is_user_logged_in() && $args->theme_location == 'vendor') {
			$items .= '<li ><a href="'.get_bloginfo('url').'/'.MESSENGER.'">Messagerie</a></li>';
		}
		elseif (!is_user_logged_in() && $args->theme_location == 'vendor') {
			$items .= '<li><a href="'. site_url('wp-login.php') .'">Log In</a></li>';
		}
		return $items;
	}


	/**
	 * VENDOR DASHBOARD PAGE
	 * add_filter( 'the_content', 'dashboard_vendor_page' );
	 *
	 * @param $content
	 *
	 * @return mixed
	 */
	public function dashboard_vendor_page($content) {
		global $post,$wp_query;
		// assuming you have created a page/post entitled 'debug'
		$uri = get_page_uri($post->ID);
		$query_vars = $wp_query->query;
		$not_allowed = 'Aucune autorisation pour cette page, merci de vous connecter';

		if ($uri == VENDOR_CUSTOM_DASHBOARD) {
			return var_export($GLOBALS['post'], TRUE );
		} elseif (isset($query_vars['object']) && $query_vars['object'] == 'order'){
			if(is_user_logged_in()){
				include 'partials/running-orders.php';
				return $content;
			} else {
				return $not_allowed;
			}

		} elseif ($uri == VENDOR_CUSTOM_NEWS){
			if(is_user_logged_in()){
				include 'partials/dashboard-manager-news.php';
				return $content;
			} else {
				return $not_allowed;
			}

		} elseif (isset($query_vars['object']) && $query_vars['object'] == 'registration'){
			if(is_user_logged_in()){
				include 'partials/running-orders.php';
			return $content;
			} else {
				return $not_allowed;
			}
		} elseif($uri == 'dashboard' && !isset($query_vars['object'])) {
			if(is_user_logged_in()){
				include 'partials/dashboard-manager.php';
			} else {
				return $not_allowed;
			}
		} else {
			return $content;
		}
		// otherwise returns the database content

	}



}