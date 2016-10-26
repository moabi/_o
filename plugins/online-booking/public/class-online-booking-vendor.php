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
	 * @param $pm integer project manager user ID, view data as PM
	 * @param $details bool show detailed trip
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
	 */
	public function get_vendor_booking( $validation, $status = 0,$pm = 0, $details = true ) {
		global $wpdb;
		$ob_budget = new online_booking_budget();
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
			$manager_email = $ob_budget->get_trip_informations('manager-email',$unique_trip_id);

			//booking header
			$output .= '<div class="table-header brown-head"><div class="pure-g">';
			$output .= '<div class="pure-u-10-24">Réservations en cours</div>';
			$output .= '<div class="pure-u-6-24">Dates</div>';
			$output .= '<div class="pure-u-4-24">Réference</div>';
			$output .= '<div class="pure-u-4-24">Chef de projet</div>';
			$output .= '</div></div>';

			$output .= '<div class="event-body"><div class="pure-g">';
			$output .= '<div class="pure-u-1"><div class="pure-g">';

			$output .= '<div class="pure-u-10-24">';
			$output .= '<span class="ttrip-title">' . $ob_budget->get_trip_informations('booking-name',$unique_trip_id) . '</span><br />';

			$output .= '<span class="ttrip-title">' . $ob_budget->get_trip_informations('participants',$unique_trip_id) . ' personne(s)</span>';
			$output .= '</div>';

			$output .= '<div class="pure-u-6-24">';
			$output .= '<span class="ttrip-title">' . $ob_budget->get_trip_informations('dates',$unique_trip_id) . '</span>';
			$output .= '</div>';

			$output .= '<div class="pure-u-4-24">';
			$output .= '<span class="ttrip-id">' . $unique_trip_id . '</span>';
			$output .= '</div>';

			$output .= '<div class="pure-u-4-24">';

			$output .= '<span class="ttrip-avatar align-center">';
			$output .= get_avatar( $manager_email, 48 );
			$output .= '</span>';

			$output .= '<span class="ttrip-client align-center">';
			$output .= '<span class="ttrip-title">' . $ob_budget->get_trip_informations('manager',$unique_trip_id) . '</span>';
			$output .= '</span>';
			$output .= '</div>';


			$output .= '</div></div></div></div>';

				if($details == true){
					$output .= '<div class="table-body">';
					//TABLE EVENTS HEADER
					$output .= '<div class="events-header brown-head"><div class="pure-g">';
					$output .= '<div class="pure-u-3-24">Référence</div>';
					$output .= '<div class="pure-u-9-24"><i class="fa fa-flag" aria-hidden="true"></i> Prestation</div>';
					$output .= '<div class="pure-u-2-24"><i class="fa fa-euro" aria-hidden="true"></i> Prix</div>';
					$output .= '<div class="pure-u-3-24"><i class="fa fa-bullseye" aria-hidden="true"></i> Statut</div>';
					$output .= '<div class="pure-u-6-24"><i class="fa fa-flag" aria-hidden="true"></i> Actions</div>';

					$output .= '</div></div>';

					//SUB TR - display events
					//display each event
					$i = 0;
					foreach ( $results as $result ) {

						//var_dump($result);
						if($result->trip_id == $unique_trip_id && $result->vendor == $user_id){
							$i++;
							$even_class = ($i%2 == 0)? 'row-even': 'row-odd';
							$status = (isset($result->status)) ? $result->status : 0;
							$activity_id = $result->activity_id;

							$output .= '<div class="pure-u-1 '.$even_class.'"><div class="pure-g">';

							$output .= '<div class="pure-u-3-24">';
							$output .= $result->activity_uuid;
							$output .= '</div>';

							$output .= '<div class="pure-u-9-24">';
							$output .= '<span class="ttrip-title">' . get_the_title($activity_id).'</span>';
							$output .= '<br /><span class="ttrip-participants"><i class="fa fa-users" aria-hidden="true"></i> ' . $result->quantity . ' participants</span>';
							$output .= '<br /><span class="ttrip-date"><i class="fa fa-calendar-o" aria-hidden="true"></i> ' . $result->activity_date.'</span>';
							$output .= '</div>';


							$output .= '<div class="pure-u-2-24">';
							$output .= '<span class="ttrip-price">';
							$output .= $result->price.' <i class="fa fa-euro"></i>';
							$output .= '</span>';
							$output .= '</div>';



							$output .= '<div class="pure-u-3-24">';
							$output .= '<span class="ttrip-status">';
							$output .= $this->get_activity_status_wording($status);
							$output .= '</span>';
							$output .= '</div>';

							$output .= '<div class="pure-u-6-24">';
							$output .= '<a class="btn-border" href="#" onclick="setActivityStatus(2,'.$result->activity_uuid.');">Refuser</a>';
							$output .= '<a title="En validant cette réservation vous vous engagez à sa bonne réalisation le Jour J" class="btn btn-reg ttrip-btn" href="#" onclick="setActivityStatus(3,'.$result->activity_uuid.');">Valider</a><br />';

							$output .= 'Dés validation de cette réservation, vous vous engagez à sa réalisation.';
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


}