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
class online_booking_vendor {

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
	 * get_vendors_from_booking
	 * extract vendors ID from a bookink object
	 *
	 * @param $booking
	 *
	 * @return string vendors ID comma separated
	 */
	public function get_vendors_from_booking( $booking = '' ) {
		$booking_obj   = json_decode( $booking );
		$booking_dates = $booking_obj->tripObject;
		$vendors       = array();
		foreach ( $booking_dates as $booking_date ) {
			foreach ( $booking_date as $key => $booking_trip ) {
				$post_tmp  = get_post( $key );
				$vendors[] = $post_tmp->post_author;
			}
		}

		$vendors_ids = rtrim( implode( ',', $vendors ), ',' );

		return $vendors_ids;
	}

	/**
	 * get_user_booking
	 * TODO: put the right $status where client has done validation (step 1)
	 * TODO: switch to post
	 *
	 * @param $validation integer status of the global trip
	 * @param $status integer||array status of each activity
	 * @param $pm integer project manager user ID, view data as PM
	 * @param $output string define what to get
	 * 0 : trip is not visible, no user validation
	 * 1 : user has validated and ask for validation (can't edit anymore)
	 * 2 : trip is refused
	 * 3 : trip is validated by vendors
	 * 4 : trip is validated by project manager && vendors
	 * 5 : trip is validated by project manager && vendors && client
	 * 6 : trip has not been done by vendor (problems...)
	 * 7 : trip is done
	 * 8 : trip is archived
	 *
	 * @return string
	 * $validation, $status = 0,$pm = false
	 */
	public function get_vendor_booking( $args ) {
		global $wpdb;

		$pm = (isset($args['project_manager_id'])) ? $args['project_manager_id'] : 0 ;
		$status = (isset($args['status'])) ? $args['status'] : 0 ;
		$validation = (isset($args['validation'])) ? $args['validation'] : 0 ;

		$user_id = ($pm == 0) ? get_current_user_id() : $pm;

		$status = esc_sql( $status );
		//If its an array, convert to string
		if( is_array( $status ) ){
			$status = implode( ', ', $status ); //e.g. "publish, draft"
		}

		$table = $wpdb->prefix . 'online_booking_orders';
		$sql   = $wpdb->prepare( " 
						SELECT *
						FROM $table a	
						WHERE a.vendor = %d
						AND a.status IN( {$status} )  
						ORDER BY a.trip_id DESC
						", $user_id );

		$results = $wpdb->get_results( $sql );

		//get unique trip ID to order results
		if ( ! empty( $results ) ) {
			$trip_uuid = array();
			foreach ( $results as $result ) {
				array_push( $trip_uuid, $result->trip_id );
			}
			$unique_trip_uuids = array_unique( $trip_uuid );

			//var_dump( $unique_trip_ids );
		} else {
			$unique_trip_uuids = array();
		}

		$output = array(
			'trip_uuid' => $unique_trip_uuids,
			'results'   => $results
		);

		return $output;

	}

	public function get_activity_status_wording($status){

		if($status == 1){
			$wording = 'En attente de validation';
		} elseif($status == 2){
			$wording = 'Refusé';
		} elseif($status == "3"){
			$wording = 'Validé';
		} elseif($status == "4"){
			$wording = 'Validé manager';
		} elseif($status == "5"){
			$wording = 'Validé manager & client';
		} elseif($status == "6"){
			$wording = 'Non effectué';
		} elseif($status == "7"){
			$wording = 'Effectué';
		} elseif($status == "8"){
			$wording = 'Archivé';
		} else {
			$wording = 'sans statut';
		}
		return $wording;
	}
	/**
	 * get_general_trip_infos from trip ID
	 * deprecated
	 * use online-booking-budget
	 * @param $session_id_trip integer
	 * @param  $validation integer
	 * @return array|null|object
	 */
	public function get_general_trip_infos( $trip_uuid, $validation = 0) {
		global $wpdb;
		//GET GENERAL TRIPS INFOS
		$sql = $wpdb->prepare( " 
						SELECT *
						FROM " . $wpdb->prefix . "online_booking a	
						WHERE a.trip_id = %d
						AND a.validation = %d
						", $trip_uuid, $validation );

		$results = $wpdb->get_results( $sql );
		$trip = (isset($results[0])) ? $results[0] : false;

		return (array) $trip;
	}

	/**
	 * get_activity_status
	 * @param $id integer uuid
	 */
	public function get_activity_status($trip_uuid,$activity_uuid){
		global $wpdb;
		//GET GENERAL TRIPS INFOS
		$sql = $wpdb->prepare( " 
						SELECT status
						FROM " . $wpdb->prefix . "online_booking_orders a	
						WHERE a.trip_id = %d
						AND a.activity_uuid = %d
						", $trip_uuid, $activity_uuid );

		$results = $wpdb->get_results( $sql );
		$trip = (isset($results[0])) ? $results[0] : false;

		return (array) $trip;
	}

	/**
	 * @param $user_id
	 */
	public function get_vendor_activities_ids($user_id){
		$user = (!$user_id) ? get_current_user_id() : intval($user_id);
		wp_reset_postdata();
		wp_reset_query();
		$args = array(
			'post_type' => 'product',
			'author' => $user,
			'posts_per_page' => 999,
			'post_status' => 'publish',

		);
		$trip_auth = new WP_Query( $args );

		$authors_post = array();
		if ( $trip_auth->have_posts() ) {
			while ( $trip_auth->have_posts() ) {
				$trip_auth->the_post();
				global $post;
				$authors_post[] =  $post->ID;
			}


		}

		return $authors_post;
	}
	/**
	 * set_activity_status
	 * Change the activity status, either done by the vendor or the project manager...(or admin)
	 * TODO: check the rights
	 * @param $status
	 * @param $activity_uuid
	 *
	 * @return false|int
	 */
	public function set_activity_status($status,$activity_uuid){
		global $wpdb;
		$table = $wpdb->prefix . 'online_booking_orders';
		$is_capable = (current_user_can('vendor') || current_user_can('administrator') || current_user_can('project_manager')) ? true : false;
		if($is_capable){
			$result = $wpdb->update(
				$table,
				array(
					'status' => $status,    // integer
				),
				array(
					'activity_uuid' => $activity_uuid, //where activity_uuid ID is
				)
			);
		} else {
			$result = false;
		}


		return $result;
	}

	/**
	 * set_activity_status
	 * Change the activity status, either done by the vendor or the project manager...(or admin)
	 *
	 * @param $status
	 * @param $activity_uuid
	 *
	 * @return false|int
	 */
	public function set_all_activities_status($status,$trip_id){
		global $wpdb;
		$table = $wpdb->prefix . 'online_booking_orders';
		$trip_id = intval($trip_id);

		$activities_update = $wpdb->update(
			$table,
			array(
				'status'      => $status
			),
			array(
				'trip_id' => $trip_id
			)
		);
		return $activities_update;
	}

	/**
	 * @param $user_id
	 *
	 * @return array
	 */
	public function get_legal_documents($user_id){
		$user_id = (!isset($user_id)) ? get_current_user_id() : $user_id;
		//nf_sub
		$args = array(
			'post_type' => 'nf_sub',
			'author' => $user_id,
			'posts_per_page' => 1
		);

		$the_query = new WP_Query( $args );
		//var_dump($the_query);
		$data = array();
		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				global $post;

				$cie_meta = get_post_meta($post->ID,'_field_1',true);
				$data['cie_name'] = (!empty($cie_meta) && is_string($cie_meta)) ? $cie_meta : '-';
				$kbis_validation = get_field('kbis', 'user_'.$user_id);
				$data['kbis_validation_label'] = (isset($kbis_validation['label'])) ? $kbis_validation['label'] : '-';
				$urssaf_validation = get_field('urssaf', 'user_'.$user_id);
				$data['urssaf_validation_label'] = (isset($urssaf_validation['label'])) ? $urssaf_validation['label'] : '-';
				$identite_validation = get_field('identite', 'user_'.$user_id);

				$data['identite_validation_label'] = (isset($identite_validation['label'])) ? $identite_validation['label'] : '-';

				$id_files = get_post_meta($post->ID,'_field_2',true);
				$data['id_name']  = '-';
				if(is_array($id_files)){
					foreach ($id_files as $id_file){
						$data['id_name'] = $id_file['user_file_name'];
						$data['id_url'] = $id_file['file_url'];
					}
				}


				$KBis_files = get_post_meta($post->ID,'_field_4',true);
				$data['kbis_name'] = '-';
				if(is_array($KBis_files)){
					foreach ($KBis_files as $file){
						$data['kbis_name'] = $file['user_file_name'];
						$data['kbis_url'] = $file['file_url'];
					}
				}


				$urssaf_files = get_post_meta($post->ID,'_field_5',true);
				$data['urssaf_name'] = '-';
				if(is_array($KBis_files)){
					foreach ($urssaf_files as $id_file){
						$data['urssaf_name'] = $id_file['user_file_name'];
						$data['urssaf_url'] = $id_file['file_url'];
					}
				}
			}

			/* Restore original Post Data */
			wp_reset_postdata();
		}

		return $data;
	}

	/**
	 * @param bool $page
	 */
	public function add_action_on_page($page = false){


	}

	/**
	 * my_posts_where_vendor_activities
	 * add_filter('posts_where', 'my_posts_where_vendor_activities');
	 *
	 * @param $where
	 *
	 * @return mixed|string
	 */
	public function my_posts_where_vendor_activities( $where ) {

		$where = str_replace("meta_key = 'day_%", "meta_key LIKE 'day_%", $where);
		$where .= str_replace("meta_key = 'products_%", "meta_key LIKE 'products_%", $where);

		return $where;
	}

	/**
	 * Display messages if vendor did not fill any of the required documents
	 * @return string
	 */
	public function get_warning_messages(){
		$user_id = get_current_user_id();
		$output = '';

		$bad_values = array(0,3);
		//KBIS
		$kbis_value = get_field('kbis','user_'.$user_id);
		$kbis = (!in_array($kbis_value['value'],$bad_values)) ? true : false;
		//URSSAF
		$urssaf_value = get_field('urssaf','user_'.$user_id);
		$urssaf = (!in_array($urssaf_value['value'],$bad_values)) ? true : false;
		//ID
		$identite_value = get_field('identite','user_'.$user_id);
		$identite = (!in_array($identite_value['value'],$bad_values)) ? true : false;

		if(!$kbis || !$urssaf || !$identite){
			$output .= '<div class="bk-listing pure-table wcvendors-pro-dashboard-wrapper">';
			$output .= '<ul class="text-warning">';

			if($kbis == false){
				$output .= '<li><i class="fa fa-exclamation-triangle -warning" aria-hidden="true"></i> ';
				$output .= 'Votre kBis n\'est pas renseigné ou a été refusé</li>';
			}
			if($urssaf == false){
				$output .= '<li><i class="fa fa-exclamation-triangle -warning" aria-hidden="true"></i> ';
				$output .= 'Votre attestation de vigilance URSSAF n\'est pas renseigné ou a été refusé</li>';
			}
			if($identite == false){
				$output .= '<li><i class="fa fa-exclamation-triangle -warning" aria-hidden="true"></i> ';
				$output .= 'Votre pièce d\'identité n\'est pas renseigné ou a été refusé</li>';
			}

			$output .= '</ul>';
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * save_program_form
	 * @param $post_id
	 */
	public function save_program_form($post_id){
		$utils = new online_booking_utils();
		// bail early if not a contact_form post
		if( get_post_type($post_id) !== 'sejour' ) {
			return;
		}

		// bail early if editing in admin
		if( is_admin() ) {
			return;
		}

		//update taxonomies
		$taxonomies = (isset($_POST['tax_filter'])) ? $_POST['tax_filter'] : false ;

		$lieu_values = (isset($taxonomies['lieu'])) ?$taxonomies['lieu'] : false;
		if($lieu_values){
			wp_set_post_terms( $post_id,$lieu_values, 'lieu', false );
		}
		$theme_values = (isset($taxonomies['theme'])) ?$taxonomies['theme'] : false;
		if($theme_values){
			wp_set_post_terms( $post_id,$theme_values, 'theme', false );
		}

		//add post thumbnail
		$post_thumbnail = (isset($_POST['image_url'])) ? $_POST['image_url'] : false ;
		if($post_thumbnail){
			$thumbnail_id = $utils->get_attachment_id($post_thumbnail);
			set_post_thumbnail( $post_id, $thumbnail_id );
		}



		// vars
		$post = get_post( $post_id );

		// get custom fields (field group exists for content_form)
		/*
		$name = get_field('name', $post_id);
		$email = get_field('email', $post_id);


		// email data
		$to = 'contact@website.com';
		$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n";
		$subject = $post->post_title;
		$body = $post->post_content;


		// send email
		wp_mail($to, $subject, $body, $headers );
		*/
	}
}