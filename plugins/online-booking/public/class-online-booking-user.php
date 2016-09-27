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
class online_booking_user {

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
	 * clear_reservation_cookie
	 *
	 * @return bool
	 */
	public function clear_reservation_cookie() {
		if ( isset( $_COOKIE['reservation'] ) ) {
			unset( $_COOKIE['reservation'] );

			return true;
		} else {
			return false;
		}
	}

	/**
	 * get_invoiceID
	 *
	 * @param $bookingObject
	 *
	 * @return string
	 */
	public function get_invoiceID( $bookingObject ) {
		//var_dump($bookingObject);
		$trip_id      = $bookingObject->ID;
		$user_id      = $bookingObject->user_ID;
		$tripDate     = $bookingObject->booking_date;
		$newDate      = date( "d/m/y", strtotime( $tripDate ) );
		$newDateDevis = date( "dmy", strtotime( $tripDate ) );
		$invoiceID    = $newDateDevis . $trip_id;

		return $invoiceID;
	}

	/**
	 * get_invoice_date
	 *
	 *
	 * @param $bookingObject
	 *
	 * @return bool|string
	 */
	public function get_invoice_date( $bookingObject ) {
		$tripDate = $bookingObject->booking_date;
		$newDate  = date( "d/m/y", strtotime( $tripDate ) );

		return $newDate;
	}


	/**
	 * get_user_booking GLOBAL
	 *
	 * @param $validation integer
	 * 0 : the user has saved his trip
	 * 1 : user has validated and ask for validation (can't edit anymore)
	 * 2 : trip is validated by vendors and project manager
	 * 3 : trip is paid
	 * 4 : trip is finished ?
	 *
	 * @return string
	 */
	public function get_user_booking( $validation ) {

		global $wpdb;
		$userID = get_current_user_id();
		//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID
		$sql = $wpdb->prepare( " 
						SELECT *
						FROM " . $wpdb->prefix . "online_booking a	
						WHERE a.user_ID = %d
						AND a.validation = %d
						ORDER BY a.ID DESC
						", $userID, $validation );

		$results = $wpdb->get_results( $sql );
		//var_dump($results);
		$obp = new Online_Booking_Public( 'ob', 1 );

		$output = '<ul id="userTrips">';
		foreach ( $results as $result ) {
			$booking      = $result->booking_object;
			$bdate        = $result->booking_date;
			$tripID       = $result->ID;
			$tripName     = $result->booking_ID;
			$tripDate     = $result->booking_date;
			$newDate      = date( "d/m/y", strtotime( $tripDate ) );
			$newDateDevis = date( "dmy", strtotime( $tripDate ) );
			$uri          = get_bloginfo( "url" ) . '/public/?ut=';
			$uri_var      = $tripID . '-' . $userID;
			$public_url   = $uri . $obp->encode_str( $uri_var );


			$output .= '<li id="ut-' . $tripID . '">';
			if ( $validation == 0 ) {
				$output .= '<script>var trip' . $result->ID . ' = ' . $booking . '</script>';
			}


			$output .= '<div class="pure-g head"><div class="pure-u-1">';
			$output .= $tripName;
			if ( $validation == 0 ) {
				$output .= '<div class="fs1 js-delete-user-trip fa fa-trash" aria-hidden="true"  onclick="deleteUserTrip(' . $tripID . ')">Supprimer ce devis</div>';
			}
			$output .= '</div>';
			$output .= '</div>';

			$output .= '<div class="pure-g">';
			$output .= '<div class="pure-u-md-10-24"><div class="padd-l">';
			//BUDGET
			if ( $validation == 0 ) {
				//online_booking_user::the_budget($tripID, $booking,$tripDate,$result);
				$output .= '<span class="user-date-invoice"><a href="' . $public_url . '">' . __( 'Devis n°', 'online-booking' ) . '' . $newDateDevis . $tripID . ' (daté du ' . $newDate . ')</a></span>';
			} else {
				$output .= 'Commande n°' . $tripID;
			}

			$output .= '<div class="sharetrip">' . __( 'Partager/Voir votre évènement :', 'online-booking' );
			$output .= '<br /><a target="_blank" href="' . $public_url . '"><div class="btn fs1 fa fa-link" aria-hidden="true"></div></a><input type="text" value="' . $public_url . '" readonly="readonly" />';
			$output .= '<br /><em>' . __( 'Cette adresse publique,mais anonyme, vous permet de partage votre event', 'online-booking' ) . '</em>';
			$output .= '</div></div>';
			$output .= '</div>';
			$output .= '<div class="pure-u-md-7-24">';
			if ( $validation == 0 ) {
				$output .= '<div class="btn btn-border twobtn" onclick="loadTrip(trip' . $result->ID . ',true)"><i class="fs1 fa fa-pencil" aria-hidden="true"></i>' . __( 'Modifier votre séjour', 'online-booking' ) . '</div>';
				$output .= '<a class="btn btn-border scnd" href="' . $public_url . '"><i class="fa fa-book"></i>' . __( 'Voir votre devis', 'online-booking' ) . '</a>';
			} elseif ( $validation == 1 ) {
				$output .= '<div class="progress-step">' . __( 'En cours de traitement', 'online-booking' ) . '<br />';
				$output .= '<div class="in-progress s-' . $validation . '"><span></span></div></div>';
			} elseif ( $validation == 2 ) {
				$output .= '<div class="progress-step">' . __( 'Terminée', 'online-booking' ) . '<br />';
				$output .= '<div class="in-progress s-' . $validation . '"><span></span></div></div>';
			}
			$output .= '</div>';
			$output .= '<div class="pure-u-md-7-24">';
			if ( $validation == 0 ) {
				$output .= '<div class="btn-orange btn quote-it js-quote-user-trip" onclick="estimateUserTrip(' . $tripID . ')"><i class="fa fa-check"></i>Valider ma demande</div>';
			} else {
				$output .= '<a class="btn btn-border" href="' . $public_url . '"><i class="fa fa-search"></i>' . __( 'Voir le détail', 'online-booking' ) . '</a>';
			}
			$output .= '</div></div>';
			$output .= '</li>';
		}
		$output .= '</ul>';

		return $output;
	}


	/**
	 * the_budget
	 * deprecated
	 *
	 *
	 * @param $tripID
	 * @param $item
	 * @param $tripDate
	 * @param $bookingObject
	 */
	private static function the_budget( $tripID, $item, $tripDate, $bookingObject ) {


		$budget         = json_decode( $item, true );
		$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];

		$newDate      = date( "d/m/Y", strtotime( $tripDate ) );
		$newDateDevis = date( "dmy", strtotime( $tripDate ) );
		//var_dump($bookingObject);

		//$this::get_user_invoiceID($bookingObject);

		//VISIBLE LINK
		echo '<span class="user-date-invoice"><a class="open-popup-link" href="#tu-' . $tripID . '">' . __( 'Devis n°', 'online-booking' ) . '' . $newDateDevis . $tripID . ' (daté du ' . $newDate . ')</a></span>';


		//var_dump($budget);
		echo '<div class="mfp-hide" id="tu-' . $tripID . '">';
		echo '<div class="trip-budget-user">';
		echo '<h3>Le budget de votre event</h3>';
		echo '<div class="excerpt-user pure-g">';
		echo '<div class="pure-u-1-3">' . $budget['days'] . ' jours</div>';
		echo '<div class="pure-u-1-3">' . $budget['participants'] . ' participants </div>';
		echo '<div class="pure-u-1-3">Buget Max Total : ' . $budgetMaxTotal . ' </div>';

		//echo 'Budget Minimum par personne : '.$budget['budgetPerMin'].'<br />';
		//echo 'Budget Minimum : '.$budget['budgetPerMin'] * $budget['participants'].'<br />';
		//echo 'Budget Maximum par personne : '.$budget['budgetPerMax'].'<br />';
		//echo 'Budget Maximum : '.$budget['budgetPerMax'] * $budget['participants'].'<br />';
		echo '<div class="pure-u-1-3">Budget par personne : ' . $budget['currentBudget'] . '</div>';
		echo '<div class="pure-u-1-3">Budget Total : ' . $budget['currentBudget'] * $budget['participants'] . '</div>';
		echo '</div>';

		echo '<h4>Détails de votre event : </h4>';
		$trips        = $budget['tripObject'];
		$budgetSingle = array();
		//var_dump(is_array($trips));
		echo '<div class="activity-budget-user pure-g">';
		echo '<div class="pure-u-1-3">Activité</div>';
		//echo $value['type'].'<br />';
		echo '<div class="pure-u-1-3">prix/pers</div>';
		echo '<div class="pure-u-1-3">prix total ' . $budget['participants'] . ' personnes</div>';
		echo '</div>';

		$trip_dates = array_keys( $trips );
		$days_count = 0;
		foreach ( $trips as $trip ) {
			echo '<div class="pure-g budget-day">' . $trip_dates[ $days_count ] . '</div>';
			//  Check type
			if ( is_array( $trip ) ) {
				//  Scan through inner loop
				//var_dump($trip);
				$trip_id = array_keys( $trip );
				$i       = 0;
				foreach ( $trip as $value ) {
					//calculate

					array_push( $budgetSingle, $value['price'] );
					//html
					echo '<div data-id="' . $trip_id[ $i ] . '" class="activity-budget-user pure-g">';
					echo '<div class="pure-u-1-3">';
					echo '<a href="' . get_permalink( $trip_id[ $i ] ) . '" target="_blank">';
					echo '<span class="bdg ' . $value['type'] . '"></span>' . $value['name'] . '</div>';
					echo "</a>";
					echo '<div class="pure-u-1-3">' . $value['price'] . '</div>';
					echo '<div class="pure-u-1-3">' . $value['price'] * $budget['participants'] . '</div>';
					echo '</div>';
					$i ++;
				}
				$days_count ++;
			} else {
				// one, two, three
				echo $trip;
			}
		}
		//ADD A BILLING PRICE 
		//array_push($budgetSingle, 300);
		$frais_de_mes = 300;
		$single_budg  = array_sum( $budgetSingle );
		$global_budg  = $single_budg * $budget['participants'] + $frais_de_mes;
		echo '<div class="activity-budget-user pure-g">';
		echo '<div class="pure-u-1-3">Frais de dossier</div>';
		echo '<div class="pure-u-1-3"></div>';
		echo '<div class="pure-u-1-3">300</div>';
		echo '</div>';

		echo '<div class="activity-budget-user pure-g total-line">';
		echo '<div class="pure-u-1-3">Budget Total</div>';
		echo '<div class="pure-u-1-3">' . $single_budg . '</div>';
		echo '<div class="pure-u-1-3">' . $global_budg . '</div>';
		echo '</div>';

		echo '</div>';
		echo '</div>';

	}

	/**
	 * Generate a trans_id.
	 * format (000000-899999) and has great chances to be unique.
	 *
	 * @param int $timestamp
	 * @return string the generated trans_id
	 */
	public function generateTransId($timestamp = null) {
		if ( ! $timestamp ) {
			$timestamp = time();
		}
		$parts = explode( ' ', microtime() );
		$id    = ( $timestamp + $parts[0] - strtotime( 'today 00:00' ) ) * 10;
		$id    = sprintf( '%06d', $id );

		return $id;
	}

		/**
	 * save_trip
	 * save to db
	 *
	 * TODO: check if trip exist with tripName, update, save or do nothing
	 * Trip status :
	 *  not existing : save
	 *  0 : update
	 *  1 or > 1 : do nothing for Client
	 *  1 or 2 : update for Project Manager  or admin ONLY
	 *  TODO: update cookie with eventid , check if is client
	 * @param $trip_id integer
	 *
	 * @return string
	 */
	public function save_trip( $trip_id = 0 ) {

		global $wpdb;

		$is_new_trip = (intval($trip_id) == 0 || $trip_id == '') ? true: false;

		//check for cookie 'reservation' to tell if we do something
		if ( is_user_logged_in() && ! empty( $_COOKIE['reservation'] ) ) {

			$userID     = get_current_user_id();

			$bookink_json    = stripslashes( $_COOKIE['reservation'] );
			$data            = json_decode( $bookink_json, true );
			$bookink_obj     = json_encode( $data );
			$is_valid_client = (is_user_logged_in() ) ? true : false;

			$table = $wpdb->prefix . 'online_booking';

			//get all users trip to check if exists
			$userTrips = $wpdb->get_results( $wpdb->prepare( "
					SELECT * 
					FROM $table
					WHERE user_ID = %d 
					AND trip_id = %d
					",
				$userID, intval($trip_id)
			) );
			$count_user_trips = $wpdb->get_results( $wpdb->prepare( "
					SELECT * 
					FROM $table
					WHERE user_ID = %d 
					",
				$userID
			) );
			//fill with tmp array of trip complex ID
			$trips     = array();
			foreach ( $userTrips as $userTrip ) {
				array_push( $trips, $userTrip->trip_id );
			}

			//check if trip exists and the user has the right to store it
			if ( !$is_new_trip && $is_valid_client && count($userTrips) != 0 ) {
				//var_dump($trips.$is_new_trip.$userTrips);
				//exit;
				//update the trip with the existing trip ID
				$this->updateTrip( $bookink_obj, $trip_id );
				return 'updated';

			} elseif ( count( $count_user_trips ) < MAX_BOOKINGS_CLIENT && $is_valid_client && count($userTrips) == 0) {
				//var_dump($trips.$is_valid_client.$userTrips.count($userTrips));
				//exit;
				//save the new trip
				$session_id_trip = (isset($data['eventid'])) ? $data['eventid'] : 0;
				$trip_name       = (isset($data['name'])) ? $data['name'] : 'Votre séjour';
				$date  = current_time( 'mysql', 1 );
				$table = $wpdb->prefix . 'online_booking';

				$wpdb->insert(
					$table,
					array(
						'user_ID'        => $userID,
						'trip_id'        => $session_id_trip,
						'booking_date'   => $date,
						'booking_object' => $bookink_obj,
						'booking_ID'     => $trip_name,

					),
					array(
						'%d',
						'%d',
						'%s',
						'%s',
						'%s'
					)
				);

				//STORE to online_booking_order table individual trips
				$user_trips = $this->get_individual_activities($bookink_obj);
				$this->save_individual_activities($user_trips,$session_id_trip);

				return "stored";

			} elseif ( count( $trips ) >= MAX_BOOKINGS_CLIENT  && $is_valid_client) {

				return "nombre de sejours maximums atteints - CODE 03";
			} //return $bookink_obj;
			else {
				return "Aucun enregistrement possible, merci de nous contacter - CODE 01";
			}
		} else {
			return "Aucun enregistrement possible, merci de nous contacter - CODE 02";
		}

	}

	/**
	 * save_individual_activities
	 * or update !
	 * TODO: when saving, check for multiple activities, how do we delete it ??
	 * @param $user_trips array existing user activities
	 * @param $session_id_trip integer unique trip ID
	 * @param $update bool
	 */
	public function save_individual_activities($user_trips,$session_id_trip,$update = false){
		global $wpdb;
		$table = $wpdb->prefix . 'online_booking_orders';

		$activities_id = $this->get_individual_activities_id($user_trips);//new activities ID
		$stored_activites = $this->get_stored_activites($session_id_trip);//stored activities ID

		$added_activities = array_diff($activities_id, $stored_activites);//insert into table
		$activities_to_delete = array_diff($stored_activites, $activities_id );//delete from table

		foreach ($user_trips as $user_trip){
			$activity_id = (isset($user_trip['id'])) ? $user_trip['id']: 0;
			$activity_date = (isset($user_trip['date'])) ? $user_trip['date']: '00/00/0000';//D/M/Y received
			$activity_price = (isset($user_trip['price'])) ? $user_trip['price']: 0;
			$activity_vendor = (isset($user_trip['vendor'])) ? $user_trip['vendor']: 0;
			$status = 0;
			//YEAR-MONTH-DAY format
			$dateFormated = explode('/', $activity_date);
			$date = $dateFormated[2].'-'.$dateFormated[1].'-'.$dateFormated[0];

			if(in_array($user_trip['id'],$added_activities)){
				//SAVE ORIGINAL TRIP - add activities to table
				$wpdb->insert(
					$table,
					array(
						'activity_id'        => $activity_id,
						'trip_id'        => $session_id_trip,
						'activity_date'   => $date,
						'price' => $activity_price,
						'vendor'        => $activity_vendor,
						'status'     => $status,

					),
					array(
						'%d',
						'%s',
						'%d',
						'%d',
						'%d'
					)
				);
			} elseif(in_array($activity_id,$activities_id)) {
				//UPDATE ORIGINAL TRIP - update items
				$result = $wpdb->update(
					$table,
					array(
						'activity_id'        => $activity_id,
						'trip_id'        => $session_id_trip,
						'activity_date'   => $date,
						'price'     => $activity_price,
						'vendor'        => $activity_vendor,
						'status'     => $status

					),
					array(
						'trip_id' => $session_id_trip,
						'activity_id' => $activity_id
					)
				);
			}
		}

		//delete activities for the specified DATE
		foreach ($activities_to_delete as $activity_id){
			$wpdb->query(
				$wpdb->prepare(
					"
                DELETE FROM $table
		 		WHERE activity_id = %d
		 		AND trip_id = %s
				",
					$activity_id, $session_id_trip
				)
			);
		}
	}

	/**
	 * get_individual_activities
	 *
	 * @param $bookink_obj
	 *
	 * @return array
	 */
	public  function get_individual_activities($bookink_obj){
		$activites = array();
		$booking_obj = json_decode($bookink_obj);
		$booking_dates = $booking_obj->tripObject;

		foreach ($booking_dates as $key => $booking_date){
			$date_tmp = $key;
			foreach ($booking_date as $post_id => $booking_trip){
				$post_tmp = get_post($post_id);
				$_product = wc_get_product( $post_id );
				$post_author = get_post_field( 'post_author', $post_id );

				$activites[] = array(
					'id'        => $post_id,
					'price'     => $_product->get_price(),
					'date'      => $date_tmp,
					'vendor'    => $post_author,
					'sold_individually'    => 0
				);
			}
		}
		return $activites;
	}

	/**
	 * extract IDS
	 * @param $activites
	 * return array
	 */
	public function get_individual_activities_id($activities){
		$activites_id = array();
		foreach ($activities as $activity){
			array_push($activites_id,$activity['id']);
		}
		return $activites_id;
	}

	/**
	 * get_stored_activites
	 * will retrieve from online-booking-orders table the trip ID
	 * @param $session_id_trip
	 *
	 * @return array|null|object
	 */
	public  function get_stored_activites($session_id_trip){
		global $wpdb;
		$table = $wpdb->prefix . 'online_booking_orders';
		$sql = $wpdb->prepare("
						SELECT activity_id
						FROM $table a
						WHERE a.trip_id = %s
						",$session_id_trip);

		$results = $wpdb->get_results($sql);
		$activities_id = array();
		foreach ($results as $result){
			array_push($activities_id, $result->activity_id);
		}
		return $activities_id;
	}

	/**
	 * estimateUserTrip
	 *
	 * @param $tripIDtoEstimate
	 *
	 * @return string
	 */
	public function estimateUserTrip( $tripIDtoEstimate ) {
		$oba = new Online_Booking_Admin( 'online-booking', '1.0' );
		$obb = new online_booking_budget;
		global $wpdb;

		$userID = get_current_user_id();
		$date   = current_time( 'mysql', 1 );
		//Security - user logged
		//relation between user && trip
		if ( ! empty( $userID ) && is_user_logged_in() ) {
			$table             = $wpdb->prefix . 'online_booking';
			$rowToEstimate     = $wpdb->update(
				$table,
				array(
					'validation' => '1'
				),
				array(
					'ID' => $tripIDtoEstimate,
				),
				array(
					'%d'
				),
				array( '%d' )
			);
			$userTripsEstimate = "success";
			//send confirmation email
			$mailer = new Online_Booking_Mailer;
			$mailer->confirmation_mail( $userID );
			//generate pdf quote
			$html_content = $obb->the_trip( $tripIDtoEstimate, false, 0, true );
			$pdf_name     = 'ob-devis-' . $userID . $tripIDtoEstimate . '-v0';
			$oba->ob_generate_pdf( $html_content, $pdf_name );

		} else {
			$userTripsEstimate = 'failed to update trip status';
		}

		return $userTripsEstimate;
	}


	/**
	 * delete_trip_action
	 * check if user has the right to delete its trip
	 * @param $tripIDtoDelete integer
	 * @param $state integer
	 */
	public function delete_trip_action($tripIDtoDelete,$state = 0){
		$userID = get_current_user_id();
		global $wpdb;
		$table = $wpdb->prefix . 'online_booking';
		$sql = $wpdb->prepare("
						SELECT *
						FROM $table a
						WHERE a.ID = %d
						AND a.user_ID = %d
						AND a.validation = %d
						",$tripIDtoDelete, $userID, $state);

		$results = $wpdb->get_results($sql);

		if($results && isset($results[0]->trip_id)){
			$this->delete_trip( $tripIDtoDelete, $results[0]->trip_id,$state);
		} else {
			return 'Echec de la suppression';
		}
	}

	/**
	 * delete_trip
	 * will delete if it's the user trip, validation is 0
	 * TODO: get result of DELETE action
	 * @param $tripIDtoDelete
	 *
	 * @return string
	 */
	public function delete_trip( $tripIDtoDelete,$trip_id,$state ) {
		global $wpdb;

		$userID = get_current_user_id();
		$date   = current_time( 'mysql', 1 );
		if ( ! empty( $userID ) && is_user_logged_in() ) {
			$table = $wpdb->prefix . 'online_booking';
			$results = $wpdb->query(
				$wpdb->prepare(
					"
                DELETE FROM $table
		 		  WHERE ID = %d
		 		  AND user_ID = %d
		 		  AND trip_id = %s
		 		  AND validation = %d
				",
					$tripIDtoDelete, $userID,$trip_id, $state
				)
			);
			if($results){
				$table_orders = $wpdb->prefix . 'online_booking_orders';
				$table_orders_results = $wpdb->query(
					$wpdb->prepare(
						"
                		DELETE FROM $table_orders
		 		  		WHERE trip_id = %s
		 		  		AND status = $state
						",
						$trip_id, $state
					)
				);
			} else {
				$table_orders_results = false;
			}


			if($table_orders_results && $table){
				$userTripsDelete = "success";
			} else {
				$userTripsDelete = "failed to delete";
			}



		} else {
			$userTripsDelete = 'failed to delete';
		}

		return $userTripsDelete;


	}

	/**
	 * updateTrip
	 * TODO: get update result
	 *
	 * @param $bookink_obj string (json format)
	 * @param $trip_id integer unique trip ID
	 *
	 * @return string
	 */
	private function updateTrip( $bookink_obj, $trip_id ) {

		global $wpdb;
		$userID     = get_current_user_id();
		$date   = current_time( 'mysql', 1 );//date the trip is updated
		$data = json_decode( $bookink_obj, true );
		$name = (isset($data['name'])) ? $data['name'] : 'undefined';
		$result = $wpdb->update(
			$wpdb->prefix . 'online_booking',
			array(
				'booking_object' => $bookink_obj,    // string
				'booking_date'   => $date,
				'booking_ID'     => $name,

			),
			array(
				'trip_id' => $trip_id, //where trip ID is
				'user_ID'   => $userID // and user ID is
			)
		);

		//UPDATE INDIVIDUAL PRODUCTS
		$user_trips = $this->get_individual_activities($bookink_obj);
		$this->save_individual_activities($user_trips,$trip_id,true);

		if($result){
			return 'updated';
		} else {
			return 'Echec de la mise à jour';
		}

	}

}
