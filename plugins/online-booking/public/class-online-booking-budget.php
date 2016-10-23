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
class online_booking_budget {

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

	public function the_budget( $tripID, $item, $tripDate ) {


		$budget         = json_decode( $item, true );
		$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];

		$newDate      = date( "d/m/y", strtotime( $tripDate ) );
		$newDateDevis = date( "dmy", strtotime( $tripDate ) );

		//VISIBLE LINK
		echo '<span class="user-date-invoice"><a class="open-popup-link" href="#tu-' . $tripID . '">Devis n°ol' . $newDateDevis . $tripID . ' (' . $newDate . ')</a></span>';


		//var_dump($budget);
		echo '<div class="mfp-hide" id="tu-' . $tripID . '">';
		echo '<div class="trip-budget-user">';
		echo '<h3>Le budget de votre event</h3>';
		echo '<div class="excerpt-user pure-g">';
		echo '<div class="pure-u-1-3">' . $budget['days'] . ' jours</div>';
		echo '<div class="pure-u-1-3">' . $budget['participants'] . ' participants </div>';
		echo '<div class="pure-u-1-3">Buget Max Total : ' . $budgetMaxTotal . ' </div>';

		echo 'Budget Minimum par personne : ' . $budget['budgetPerMin'] . '<br />';
		echo 'Budget Minimum : ' . $budget['budgetPerMin'] * $budget['participants'] . '<br />';
		echo 'Budget Maximum par personne : ' . $budget['budgetPerMax'] . '<br />';
		echo 'Budget Maximum : ' . $budget['budgetPerMax'] * $budget['participants'] . '<br />';
		echo 'Budget global par personne : ' . $budget['currentBudget'] . '<br />';
		echo '</div>';
		echo 'Budget Total : ' . $budget['currentBudget'] * $budget['participants'] . '<br />';
		echo '<h4>Détails de votre event : </h4>';
		$trips        = $budget['tripObject'];
		$budgetSingle = array();
		//var_dump(is_array($trips));
		echo '<div class="activity-budget-user pure-g">';
		echo '<div class="pure-u-1-3">Activité</div>';
		//echo $value['type'].'<br />';
		echo '<div class="pure-u-1-3">prix/pers</div>';
		echo '<div class="pure-u-1-3">prix total</div>';
		echo '</div>';
		foreach ( $trips as $trip ) {
			//  Check type
			if ( is_array( $trip ) ) {
				//  Scan through inner loop

				foreach ( $trip as $value ) {
					//calculate
					array_push( $budgetSingle, $value['price'] );
					//html
					echo '<div class="activity-budget-user pure-g">';
					echo '<div class="pure-u-1-3">' . $value['name'] . '</div>';
					//echo $value['type'].'<br />';
					echo '<div class="pure-u-1-3">' . $value['price'] . '</div>';
					echo '<div class="pure-u-1-3">' . $value['price'] * $budget['participants'] . '</div>';
					echo '</div>';
				}
			} else {
				// one, two, three
				echo $trip;
			}
		}
		$single_budg = array_sum( $budgetSingle );
		$global_budg = $single_budg * $budget['participants'];
		echo '<div class="activity-budget-user pure-g">';
		echo '<div class="pure-u-1-3">Budget Total</div>';
		//echo $value['type'].'<br />';
		echo '<div class="pure-u-1-3">' . $single_budg . '</div>';
		echo '<div class="pure-u-1-3">' . $global_budg . '</div>';
		echo '</div>';

		echo '</div>';
		echo '</div>';
		echo '</div>';

	}


	/**
	 * the_trip
	 * Display a SEJOUR from the jSON file in DB
	 * TODO: check the trip status to display cart > 0 || 1 ?
	 *
	 * @param $trip_uuid integer tripID as in the DB
	 * @param $item object the booking original object json
	 * @param $state integer (0 - 4 )
	 * @param $from_db bool
	 * @param $is_the_client bool
	 *
	 * @return $output Quote/invoice
	 */
	public function the_trip( $trip_uuid, $trip_object = false, $state = 0, $is_the_client = false ) {
		global $wpdb;

		if ( $trip_uuid ) {
			//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID
			$sql = $wpdb->prepare( "
						SELECT *
						FROM " . $wpdb->prefix . "online_booking a
						WHERE a.trip_id = %d
						", $trip_uuid );

			$results = $wpdb->get_results( $sql );

			$it     = (isset($results[0])) ? $results[0] : false;
			$item   = ( isset( $results[0] ) ) ? $it->booking_object : false;
			$budget = json_decode( $item, true );

		} else {
			$budget = json_decode( $trip_object, true );
		}
		$output = '';

		$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];

		$trips               = $budget['tripObject'];
		$budgetSingle        = array();
		$days                = ( $budget['days'] > 1 ) ? $budget['days'] . ' jours' : $budget['days'] . ' jour';
		$place_id            = $budget['lieu'];
		$place_trip          = get_term_by( 'id', $place_id, 'lieu' );
		$dates               = ( $budget['arrival'] == $budget['departure'] ) ? $budget['arrival'] : ' du ' . $budget['arrival'] . ' au ' . $budget['departure'];
		$number_participants = ( isset( $budget['participants'] ) ) ? $budget['participants'] : 1;
		$trip_dates = array_keys( $trips );
		$days_count = 0;


		$output .= '<div id="event-trip-planning" class="trip-public">';
		$output .= '<div class="trip-public-user">';

		$output .= '<h2>Déroulement de votre séjour</h2>';//#activity-budget-user

		/**
		 *
		 *
		 */

		foreach ( $trips as $trip ) {
			$dayunit = $days_count + 1;


			$output .= '<div class="table-header brown-head">';
			$output .= '<div class="pure-g">';
			$output .= '<div class="pure-u-1-4">';
			$output .= '<i class="fa fa-calendar"></i> Journée ' . $dayunit . ' - ' . $trip_dates[ $days_count ];
			$output .= '</div>';
			$output .= '<div class="pure-u-1-4"> Descriptif</div>';
			$output .= '<div class="pure-u-1-4">Infos pratiques</div>';
			$output .= '<div class="pure-u-1-4">Adresse</div>';
			$output .= '</div>';
			$output .= '</div>';

			$output .= '<div class="event-day day-content post-content">';




			/**
			 * loop through products
			 * destroy any previous woocommerce session
			 * create a new cart
			 * add each product to cart with quantities (based on number of participants)
			 */
			//  Check type
			if ( is_array( $trip ) ) {
				//  Scan through inner loop
				$products_ids = array_keys( $trip );
				$i       = 0;
				$output .= '<div class ="etp-days" >';
				foreach ( $trip as $value ) {
					//calculate
					//var_dump($value);
					$product_id   = ( isset( $products_ids[ $i ] ) ) ? $products_ids[ $i ] : 0;
					$productPrice = ( isset( $value['price'] ) ) ? $value['price'] : 0;
					$productName  = ( isset( $value['name'] ) ) ? $value['name'] : 'Undefined Name';
					//old way to calculate price
					array_push( $budgetSingle, $productPrice );
					//woocommerce calculate price
					//$obwc->wc_items_to_cart($product_id,$number_participants,0,array(),array());
					//do_action( 'wc_items_to_cart', $product_id,$number_participants,0,array(),array());
					//global $woocommerce;
					//WC()->cart->add_to_cart($product_id, $number_participants);

					$content_post = get_post($product_id);
					$content_ex = get_the_excerpt();
					$content = (empty($content_ex)) ? substr($content_post->post_content, 0, 250) : $content_ex;
					$geo = get_field('gps',$product_id);
					$geo_adress = (isset($geo['address'])) ? $geo['address'] : '';


					//html - display each product
					$output .= '<div data-id="' . $product_id . '" class="pure-u-1 single-activity-row">';
					$output .= '<div class="pure-u-1 pure-u-md-1-4">';
					$output .= get_the_post_thumbnail( $product_id, array( 180, 120 ) , array( 'class' => 'img-rounded' ));
					$output .= '<h3><a href="' . get_permalink( $product_id ) . '" target="_blank">';
					$output .= $productName . '</a></h3>';
					//$output .= $ux->get_reservation_type( $product_id );
					$output .= '</div>';

					$output .= '<div class="pure-u-1 pure-u-md-1-4 sejour-type">';
					$output .= get_the_excerpt();
					$output .= $content;
					$output .= '</div>';

					$output .= '<div class="pure-u-1 pure-u-md-1-4 ">';

					if($is_the_client){
						//$output .= do_shortcode( '[add_to_cart id=' . $product_id . ']' );
					}
					$output .= get_field('infos_pratiques',$product_id);

					$output .= '</div>';

					$output .= '<div class="pure-u-1 pure-u-md-1-4 sejour-type">';
					$output .= $geo_adress;
					$output .= '</div>';

					$output .= '</div>';
					$i ++;
				}

				$output .= '</div>';

				//$output .= '<h2>Localisation activités :</h2>';
				//$output .= '<div class="acf-map" id="google-map"></div>';

				$days_count ++;
			}
			$output .= '</div>';

		}

		/*
		 * Budget display
		 * User is logged In
		 * Estimate or invoice Step
		 */
		if ( $is_the_client && $state < 2 ) {
			$budgetPerParticipant    = array_sum( $budgetSingle );
			$budgetPerParticipantTtc = $budgetPerParticipant * 1.2;
			$output .= '<div class="event-day">';
			$output .= '<div class="pure-g">';

			$output .= '<div class="pure-u-1-2">';
			$output .= 'Nos prix sont calculés sur la base de nombre de participants indiqués dans votre devis. Le prix et la disponibilité de la prestation sont garantis le jour de l\'émission du devis et sont suceptibles d\'être réajustés lors de votre validation.';
			$output .= '</div>';

			$output .= '<div class="pure-u-1-2" style="text-align:right;">';
			$output .= 'Total budget HT : ' . $budgetPerParticipant . '€<br />';
			$output .= 'Total budget TTC : ' . $budgetPerParticipantTtc . '€<br />';
			$output .= '</div>';

			$output .= '</div>';//pure-g

			//estimate step
			if ( $state == 0 ) {


				$output .= '<div class="pure-g" id="userTrips">';

				$output .= '<div class="pure-u-1-2">';
				$output .= '<div class="btn btn-border" onclick="loadTrip(trip,true)"><i class="fs1 fa fa-pencil" aria-hidden="true"></i>' . __( 'Modifier votre séjour', 'online-booking' );
				$output .= '</div></div>';

				$output .= '<div class="pure-u-1-2">';
				$output .= '<div class="btn-orange btn quote-it js-quote-user-trip" onclick="estimateUserTrip(' . $trip_id . ')"><i class="fa fa-check"></i>Valider mon devis</div>';
				$output .= '</div>';

				$output .= '</div>';
			}
			$output .= '</div>';
			$output .= '</div>';//event-day

			//#budget
		}


		$output .= '</div>';


		return $output;

	}


	/**
	 * get_trip_map
	 * TODO: allow from object
	 * @param $trip_uuid
	 *
	 * @return string
	 */
	public function get_trip_map($trip_uuid){

		global $wpdb;
		$output = '';
		$gmap_key = esc_attr( get_option('ob_gmap_key') );

		//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID
		$sql = $wpdb->prepare( "
					SELECT *
					FROM " . $wpdb->prefix . "online_booking_orders a
					WHERE a.trip_id = %d
					", $trip_uuid );

		$results = $wpdb->get_results( $sql );
		$activities = array();
		$i = 0;
		foreach ($results as $result){
			$activity_id =  $result->activity_id;
			$gps = get_field('gps',$activity_id);
			$address = (isset($gps['address'])) ? $gps['address'] : '';
			$lat = (isset($gps['lat'])) ? $gps['lat'] : '';
			$lng = (isset($gps['lng'])) ? $gps['lng'] : '';
			$activity = array(
				'lat'=> $lat,
				'lng'=> $lng,
				'center' => array('lat' => floatval($lat), 'lng' => floatval($lng)),
				'address'   => $address
			);
			if(!empty($lat)){
				array_push($activities,$activity);
			}

			$i++;
		}

		$output .= '<script type="text/javascript">';
		$output .= '$activities = ';
		$output .= json_encode($activities, JSON_FORCE_OBJECT);
		$output .= ';';
		$output .= '</script>';
		$output .= '<div id="map" class="single-map" style="width: 100%; display: block; min-height: 350px; margin: 1em 0px; position: relative; overflow: hidden;    background: #53463e;"></div>';


		return $output;

	}

	public function get_trip_informations($field,$trip_uuid){

		global $wpdb;

		//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID
		$sql = $wpdb->prepare( "
					SELECT *
					FROM " . $wpdb->prefix . "online_booking a
					WHERE a.trip_id = %d
					", $trip_uuid );

		$results = $wpdb->get_results( $sql );

		$it     = (isset($results[0])) ? $results[0] : false;
		$item   = ( isset( $results[0] ) ) ? $it->booking_object : false;
		$budget = json_decode( $item, true );

		$trips               = $budget['tripObject'];

		$place_id            = $budget['lieu'];
		$place_trip          = get_term_by( 'id', $place_id, 'lieu' );


		$trip_dates = array_keys( $trips );
		$days_count = 0;

		$arrival = (isset($budget['arrival'])) ? $budget['arrival'] : '';
		$departure = (isset($budget['departure'])) ? $budget['departure'] : '';
		$participants = ( isset( $budget['participants'] ) ) ? $budget['participants'] : 1;
		$budgetMaxTotal = intval($budget['participants'] * $budget['budgetPerMax']);
		$dates = ( $budget['arrival'] == $budget['departure'] ) ? $budget['arrival'] : ' du ' . $budget['arrival'] . ' au ' . $budget['departure'];
		$booking_date = (isset($it->booking_date)) ? $it->booking_date : '';
		$booking_name = (isset($it->booking_ID)) ? $it->booking_ID : '';
		$manager_id = (isset($it->manager)) ? get_userdata( intval($it->manager) ) : false;
		$client_id = (isset($it->user_ID)) ? get_userdata( intval($it->user_ID) ) : false;
		$manager_display_name = ($manager_id) ?  $manager_id->display_name : '';
		$client_display_name = ($client_id) ?  $client_id->display_name : '';
		if(isset($manager_id->ID)){
			$manager_phone = get_user_meta($manager_id->ID,'billing_phone',true);
		} else {
			$manager_phone = '';
		}
		$tripDate = (isset($it->booking_date)) ? $it->booking_date : '';
		$invoice_date  = date( "d/m/y", strtotime( $tripDate ) );
		$days  = ( $budget['days'] > 1 ) ? $budget['days'] . ' jours' : $budget['days'] . ' jour';

		switch ($field) {
			case 'arrival':
				$value = $arrival;
				break;
			case 'departure':
				$value = $departure;
				break;
			case 'participants':
				$value = $participants;
				break;
			case 'dates':
				$value = $dates;
				break;
			case 'manager-id':
				$value = $manager_id;
				break;
			case 'manager':
				$value = $manager_display_name;
				break;
			case 'manager-phone':
				$value = $manager_phone;
				break;
			case 'client':
				$value = $client_display_name;
				break;
			case 'client-id':
				$value = $client_id;
				break;
			case 'booking-date':
				$value = $booking_date;
				break;
			case 'booking-name':
				$value = $booking_name;
				break;
			case 'invoice-date':
				$value = $invoice_date;
				break;
			default:
				$value = '';
		}


		return $value;

	}
}