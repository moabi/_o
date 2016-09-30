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
	 *
	 * @param $validation integer status of the global trip
	 * @param $status integer||array status of each activity
	 * 0 : trip is not visible, no user validation
	 * 1 : user has validated and ask for validation (can't edit anymore)
	 * 2 : trip is validated by vendors and project manager
	 * 3 : trip is paid
	 * 4 : trip is finished ?
	 *
	 * @return string
	 */
	public function get_vendor_booking( $validation, $status = 0 ) {
		global $wpdb;
		$user_id = get_current_user_id();

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
			$trip_ids = array();
			foreach ( $results as $result ) {
				array_push( $trip_ids, $result->trip_id );
			}
			$unique_trip_ids = array_unique( $trip_ids );

			//var_dump( $unique_trip_ids );
		} else {
			$unique_trip_ids = array();
		}

		$output = '<div id="vendor-bookings" class="bk-listing pure-table">';
		//loop through trips to find vendors activities sold
		foreach ( $unique_trip_ids as $unique_trip_id ) {
			//var_dump($unique_trip_id);
			//get general trip infos
			$general_infos = $this->get_general_trip_infos( $unique_trip_id, $validation );
			//var_dump($unique_trip_id);
			//get detailed events
			$user_name    = (isset($general_infos['user_ID'])) ? $general_infos['user_ID'] : false;
			$booking_name = ( isset( $general_infos['booking_ID'] ) && ! empty( $general_infos['booking_ID']) ) ? $general_infos['booking_ID'] : 'Séjour du client';
			$booking_id = (isset($general_infos['trip_id'])) ? $general_infos['trip_id'] : false;
			//check for an existing trip...
			if($booking_id){
			//booking header
			$output .= '<div class="table-header brown-head"><div class="pure-g">';
			$output .= '<div class="pure-u-1-4">Réservations en cours</div>';
			$output .= '<div class="pure-u-1-4">Période</div>';
			$output .= '<div class="pure-u-1-4">Interlocuteur</div>';
			$output .= '<div class="pure-u-1-4">Actions</div>';
			$output .= '</div></div>';

			$output .= '<div class="event-body"><div class="pure-g">';
			$output .= '<div class="pure-u-1"><div class="pure-g">';

			$output .= '<div class="pure-u-1-4">';
			$output .= '<span class="ttrip-title">' . $booking_name . '</span>';
			$output .= '</div>';

			$output .= '<div class="pure-u-1-4">';
			//$output .= '<span class="ttrip-date">'.$general_infos->booking_date.'</span>';
			$output .= '</div>';

			$output .= '<div class="pure-u-1-4">';
			if ( get_avatar( $user_name ) && $user_name ) {
				$output .= '<span class="ttrip-avatar">';
				$output .= get_avatar( $user_name, 48 );
				$output .= '</span>';
			}
			$output .= '<span class="ttrip-client">';
			if ( get_the_author_meta( 'first_name', $user_name ) ) {
				$output .= get_the_author_meta( 'first_name', $user_name );
				$output .= ' ' . get_the_author_meta( 'last_name', $user_name );
			} else {
				$output .= get_the_author_meta( 'nicename', $user_name );
			}
			$output .= '</span>';
			$output .= '</div>';

			$output .= '<div class="pure-u-1-4">';
			$output .= '<span class="ttrip-action"></span>';
			$output .= '</div>';

			$output .= '</div></div></div></div>';


				$output .= '<div class="table-body">';
				//TABLE EVENTS HEADER
				$output .= '<div class="events-header brown-head"><div class="pure-g">';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-flag"></i> Prestation</div>';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-users"></i> Participants</div>';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-calendar"></i> Date</div>';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-euro"></i> Prix</div>';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-bullseye" aria-hidden="true"></i> Statut</div>';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-flag"></i> Action</div>';
				$output .= '</div></div>';

				//SUB TR - display events
				//display each event
				$i = 0;
				foreach ( $results as $result ) {

					if($result->trip_id == $booking_id && $result->vendor == $user_id){
						$i++;
						$even_class = ($i%2 == 0)? 'row-even': 'row-odd';
						$status = (isset($result->status)) ? $result->status : 0;

						$output .= '<div class="pure-u-1 '.$even_class.'"><div class="pure-g">';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<span class="ttrip-title">' . get_the_title($result->activity_id).'</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<span class="ttrip-participants">' . $result->quantity . ' participants</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<span class="ttrip-date">' . $result->activity_date.'</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<span class="ttrip-price">';
						$output .= $result->price.' <i class="fa fa-euro"></i>';
						$output .= '</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<span class="ttrip-status">';
						$output .= $this->get_activity_status_wording($status);
						$output .= '</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<a title="En validant cette réservation vous vous engagez à sa bonne réalisation le Jour J" class="btn btn-reg ttrip-btn" href=""><i class="fa fa-check"></i></a>';
						$output .= '<a class="btn btn-reg ttrip-btn" href=""><i class="fa fa-times"></i></a>';
						$output .= '</div>';

						$output .= '</div></div>';
					}


				}
				$output .= '</div>';
			}
		}
		$output .= '</div>';


		return $output;
	}

	public function get_activity_status_wording($status){

		if($status == 1){
			$wording = 'En attente de validation';
		} elseif($status == 2){
			$wording = 'Refusé';
		} elseif($status == ""){
			$wording = 'Validé';
		} else {
			$wording = 'sans statut';
		}
		return $wording;
	}
	/**
	 * get_general_trip_infos from trip ID
	 *
	 * @param $session_id_trip integer
	 * @param  $validation integer
	 * @return array|null|object
	 */
	public function get_general_trip_infos( $session_id_trip, $validation = 0) {
		global $wpdb;
		//GET GENERAL TRIPS INFOS
		$sql = $wpdb->prepare( " 
						SELECT *
						FROM " . $wpdb->prefix . "online_booking a	
						WHERE a.trip_id = %d
						AND a.validation = %d
						", $session_id_trip, $validation );

		$results = $wpdb->get_results( $sql );
		$trip = (isset($results[0])) ? $results[0] : false;

		return (array) $trip;
	}

	/**
	 * get_activity_status
	 * @param $id integer uuid
	 */
	public function get_activity_status($id,$activity_uuid){
		global $wpdb;
		//GET GENERAL TRIPS INFOS
		$sql = $wpdb->prepare( " 
						SELECT status
						FROM " . $wpdb->prefix . "online_booking_orders a	
						WHERE a.trip_id = %d
						AND a.activity_uuid = %d
						", $id, $activity_uuid );

		$results = $wpdb->get_results( $sql );
		$trip = (isset($results[0])) ? $results[0] : false;

		return (array) $trip;
	}


	/**
	 * set_activity_status
	 * Change the activity status, either done by the vendor or the project manager...(or admin)
	 * TODO: check the right
	 * @param $status
	 * @param $activity_uuid
	 *
	 * @return false|int
	 */
	public function set_activity_status($status,$activity_uuid){
		global $wpdb;
		$table = $wpdb->prefix . 'online_booking_orders';
		$is_capable = (current_user_can('vendor') || current_user_can('administrator')) ? true : false;
		$result = $wpdb->update(
			$table,
			array(
				'status' => $status,    // integer
			),
			array(
				'activity_uuid' => $activity_uuid, //where activity_uuid ID is
			)
		);

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


}