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
 
class online_booking_user  {

		/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	

	/**
	 * clear_reservation_cookie
	 *
	 * @return bool
	 */
	public function clear_reservation_cookie(){
		if (isset($_COOKIE['reservation'])) {
			unset($_COOKIE['reservation']);
			return true;
		} else{
			return false;
		}
	}

	/**
	 * get_invoiceID
	 *
	 * @param $bookingObject
	 * @return string
	 */
	 public function get_invoiceID($bookingObject){
		//var_dump($bookingObject);
		$trip_id = $bookingObject->ID;
		$user_id = $bookingObject->user_ID;
		$tripDate = $bookingObject->booking_date;
		$newDate = date("d/m/y", strtotime($tripDate));
		$newDateDevis = date("dmy", strtotime($tripDate));
		$invoiceID = $newDateDevis.$trip_id;
		
		return $invoiceID;
	 }

	/**
	 * get_invoice_date
	 *
	 *
	 * @param $bookingObject
	 * @return bool|string
	 */
	 public function get_invoice_date($bookingObject){
		 $tripDate = $bookingObject->booking_date;
		 $newDate = date("d/m/y", strtotime($tripDate));
		 
		 return $newDate;
	 }
	

	/**
	 * get_user_booking
	 *
	 * @param $validation integer
	 * @return string
	 */
	public static function get_user_booking($validation){
	
			global $wpdb;
			$userID = get_current_user_id();
			//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID	
			$sql = $wpdb->prepare(" 
						SELECT *
						FROM ".$wpdb->prefix."online_booking a	
						WHERE a.user_ID = %d
						AND a.validation = %d
						ORDER BY a.ID DESC
						",$userID,$validation); 
				
			$results = $wpdb->get_results($sql);
			//var_dump($results);	
			$obp = new Online_Booking_Public('ob',1);		
			
			$output = '<ul id="userTrips">';
			foreach ( $results as $result ) 
				{
					$booking = $result->booking_object; 
					$bdate = $result->booking_date;
					$tripID = $result->ID;
					$tripName = $result->booking_ID;
					$tripDate = $result->booking_date;
					$newDate = date("d/m/y", strtotime($tripDate));
					$newDateDevis = date("dmy", strtotime($tripDate));
					$uri = get_bloginfo("url").'/public/?ut=';
					$uri_var = $tripID.'-'.$userID;
					$public_url = $uri.$obp->encode_str($uri_var);


					$output .= '<li id="ut-'.$tripID.'">';
					if($validation == 0){
						$output .= '<script>var trip'.$result->ID.' = '.$booking.'</script>';
					}


					$output .= '<div class="pure-g head"><div class="pure-u-1">';
					$output .= $tripName;
					if($validation == 0){
						$output .= '<div class="fs1 js-delete-user-trip" aria-hidden="true" data-icon="" onclick="deleteUserTrip('.$tripID.')">Supprimer ce devis</div>';
					}
					$output .= '</div>';
					$output .= '</div>';

					$output .= '<div class="pure-g">';
					$output .= '<div class="pure-u-md-10-24"><div class="padd-l">';
					//BUDGET
					if($validation == 0){
					//online_booking_user::the_budget($tripID, $booking,$tripDate,$result);
						$output .= '<span class="user-date-invoice"><a href="'.$public_url.'">'.__('Devis n°','online-booking').''.$newDateDevis.$tripID.' (daté du '.$newDate.')</a></span>';
					} else {
						$output .= 'Commande n°'.$tripID;
					}

					$output .= '<div class="sharetrip">'.__('Partager/Voir votre évènement :','online-booking');
					$output .= '<br /><a target="_blank" href="'.$public_url.'"><div class="btn fs1" aria-hidden="true" data-icon=""></div></a><input type="text" value="'.$public_url.'" readonly="readonly" />';
					$output .= '<br /><em>'.__('Cette adresse publique,mais anonyme, vous permet de partage votre event','online-booking').'</em>';
					$output .= '</div></div>';
					$output .= '</div>';
					$output .= '<div class="pure-u-md-7-24">';
					if($validation == 0){
						$output .= '<div class="btn btn-border twobtn" onclick="loadTrip(trip'.$result->ID.',true)"><i class="fs1" aria-hidden="true" data-icon="j"></i>'.__('Modifier votre séjour','online-booking').'</div>';
						$output .= '<a class="btn btn-border scnd" href="'.$public_url.'"><i class="fa fa-book"></i>'.__('Voir votre devis','online-booking').'</a>';
					}elseif($validation == 1) {
						$output .= '<div class="progress-step">'.__('En cours de traitement','online-booking').'<br />';
						$output .= '<div class="in-progress s-'.$validation.'"><span></span></div></div>';
					}elseif($validation == 2) {
						$output .= '<div class="progress-step">'.__('Terminée','online-booking').'<br />';
						$output .= '<div class="in-progress s-'.$validation.'"><span></span></div></div>';
					}
					$output .= '</div>';
					$output .= '<div class="pure-u-md-7-24">';
					if($validation == 0){
						$output .= '<div class="btn-orange btn quote-it js-quote-user-trip" onclick="estimateUserTrip('.$tripID.')"><i class="fa fa-check"></i>Valider ma demande</div>';
					} else  {
						$output .= '<a class="btn btn-border" href="'.$public_url.'"><i class="fa fa-search"></i>'.__('Voir le détail','online-booking').'</a>';
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
	private static function the_budget($tripID , $item, $tripDate,$bookingObject){
		

		$budget = json_decode($item, true);
		$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];
		
		$newDate = date("d/m/Y", strtotime($tripDate));
		$newDateDevis = date("dmy", strtotime($tripDate));
		//var_dump($bookingObject);
		
		//$this::get_user_invoiceID($bookingObject);
		
		//VISIBLE LINK
		echo '<span class="user-date-invoice"><a class="open-popup-link" href="#tu-'.$tripID.'">'.__('Devis n°','online-booking').''.$newDateDevis.$tripID.' (daté du '.$newDate.')</a></span>';
		
		
		//var_dump($budget);
		echo '<div class="mfp-hide" id="tu-'.$tripID.'">';
		echo '<div class="trip-budget-user">';
		echo '<h3>Le budget de votre event</h3>';
		echo '<div class="excerpt-user pure-g">';
		echo '<div class="pure-u-1-3">'.$budget['days'].' jours</div>';
		echo '<div class="pure-u-1-3">'.$budget['participants'].' participants </div>';
		echo '<div class="pure-u-1-3">Buget Max Total : '.$budgetMaxTotal.' </div>';
		
		//echo 'Budget Minimum par personne : '.$budget['budgetPerMin'].'<br />';
		//echo 'Budget Minimum : '.$budget['budgetPerMin'] * $budget['participants'].'<br />';
		//echo 'Budget Maximum par personne : '.$budget['budgetPerMax'].'<br />';
		//echo 'Budget Maximum : '.$budget['budgetPerMax'] * $budget['participants'].'<br />';
		echo '<div class="pure-u-1-3">Budget par personne : '.$budget['currentBudget'].'</div>';
		echo '<div class="pure-u-1-3">Budget Total : '.$budget['currentBudget'] * $budget['participants'].'</div>';
		echo '</div>';

		echo '<h4>Détails de votre event : </h4>';
		$trips = $budget['tripObject'];
		$budgetSingle = array();
		//var_dump(is_array($trips));
		echo '<div class="activity-budget-user pure-g">';
			        echo '<div class="pure-u-1-3">Activité</div>';
			        //echo $value['type'].'<br />';
		            echo '<div class="pure-u-1-3">prix/pers</div>';
		            echo '<div class="pure-u-1-3">prix total '.$budget['participants'].' personnes</div>';
		echo '</div>';
		
		$trip_dates =  array_keys($trips);
		$days_count = 0;
		foreach ($trips as $trip) {
			echo '<div class="pure-g budget-day">'.$trip_dates[$days_count].'</div>';
		    //  Check type
		    if (is_array($trip)){
		        //  Scan through inner loop
		        //var_dump($trip);
		        $trip_id =  array_keys($trip);
		        $i = 0;
		        foreach ($trip as $value) {
			        //calculate 
			        
			        array_push($budgetSingle, $value['price']);
			        //html
			        echo '<div data-id="'.$trip_id[$i].'" class="activity-budget-user pure-g">';
			        echo '<div class="pure-u-1-3">';
			        echo '<a href="'.get_permalink($trip_id[$i]).'" target="_blank">';
			        echo '<span class="bdg '.$value['type'].'"></span>'.$value['name'].'</div>';
			        echo "</a>";
		            echo '<div class="pure-u-1-3">'.$value['price'].'</div>';
		            echo '<div class="pure-u-1-3">'.$value['price'] * $budget['participants'].'</div>';
		            echo '</div>';
		            $i++;
		        }
		        $days_count++;
		    }else{
		        // one, two, three
		        echo $trip;
		    }
		}
		//ADD A BILLING PRICE 
		//array_push($budgetSingle, 300);
		$frais_de_mes = 300;
		$single_budg = array_sum($budgetSingle);
		$global_budg = $single_budg * $budget['participants'] + $frais_de_mes;
		echo '<div class="activity-budget-user pure-g">';
			        echo '<div class="pure-u-1-3">Frais de dossier</div>';
		            echo '<div class="pure-u-1-3"></div>';
		            echo '<div class="pure-u-1-3">300</div>';
		echo '</div>';
		
		echo '<div class="activity-budget-user pure-g total-line">';
			        echo '<div class="pure-u-1-3">Budget Total</div>';
		            echo '<div class="pure-u-1-3">'.$single_budg.'</div>';
		            echo '<div class="pure-u-1-3">'.$global_budg.'</div>';
		echo '</div>';
		
		echo '</div>';
		echo '</div>';

	}
	

	/**
	 * save_trip
	 * save to db
	 *
	 * @param $tripName
	 * @return string
	 */
	public static function  save_trip($tripName){
		
		global $wpdb;
		$userID = get_current_user_id();
		
		if(!empty($userID) &&  is_user_logged_in() ):
			$date =  current_time('mysql', 1);
			if(!empty($_COOKIE['reservation'])): 
				$session_id = session_id(); 
				$bookink_json = stripslashes( $_COOKIE['reservation'] );
				$data = json_decode($bookink_json, true);
				 $session_id_trip = 'tid_'.$userID.'_'.$session_id;
				 $data['eventid'] = $session_id_trip;
				$trip_name = $data['name'];
				$bookink_obj = json_encode($data);
				
			else: 
				$bookink_obj = 'nothing was recorded';
			endif;
			$table = $wpdb->prefix.'online_booking';
			$userTrips = $wpdb->get_results( $wpdb->prepare("
					SELECT * 
					FROM ".$wpdb->prefix."online_booking
					WHERE user_ID = %d 
					",
					$userID
					) );
			$trips = array();
			foreach ($userTrips as $userTrip) { 
				array_push($trips, $userTrip->trip_id);       
			}
			
			if (in_array($session_id_trip, $trips) && count($trips) < 11 ) {
			    online_booking_user::updateTrip($bookink_obj,$session_id_trip,$trip_name);
			    return 'updated';
			    
			} elseif (!in_array($session_id_trip, $trips) && count($trips) < 11 ) {

				$date =  current_time('mysql', 1);
				$table = $wpdb->prefix.'online_booking';
				$wpdb->insert( 
					$table, 
					array( 
						'user_ID' => $userID, 
						'trip_id' => $session_id_trip,
						'booking_date' => $date,
						'booking_object' => $bookink_obj,
						'booking_ID' => $trip_name,	
							
					), 
					array( 
						'%d', 
						'%s',
						'%s',
						'%s',
						'%s' 
					) 
				);
				return "stored";
			} else {
				return "10";
			}
			//return $bookink_obj;
		else:
			return "fail to  store trip";
		endif;
		
		
		
	}
	

	/**
	 * estimateUserTrip
	 *
	 * @param $tripIDtoEstimate
	 * @return string
	 */
	 public function estimateUserTrip($tripIDtoEstimate){
		 $oba = new Online_Booking_Admin( 'online-booking','1.0');
		 $obb = new online_booking_budget;
		 global $wpdb;
		
		$userID = get_current_user_id();
		$date =  current_time('mysql', 1);
		 //Security - user logged
		 //relation between user && trip
		if(!empty($userID) &&  is_user_logged_in() ) {
			$table = $wpdb->prefix . 'online_booking';
			$rowToEstimate = $wpdb->update(
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
				array('%d')
			);
			$userTripsEstimate = "success";
			//send confirmation email
			$mailer = new Online_Booking_Mailer;
			$mailer->confirmation_mail($userID);
			//generate pdf quote
			$html_content = $obb->the_trip($tripIDtoEstimate, false,0,true);
			$pdf_name = 'ob-devis-'.$userID.$tripIDtoEstimate.'-v0';
			$oba->ob_generate_pdf($html_content, $pdf_name);

		} else {
			$userTripsEstimate = 'failed to update trip status';
		}

		return $userTripsEstimate;
	 }
	 

	/**
	 * delete_trip
	 *
	 * @param $tripIDtoDelete
	 * @return string
	 */
	public static function  delete_trip($tripIDtoDelete){
		global $wpdb;
		
		$userID = get_current_user_id();
		$date =  current_time('mysql', 1);
		if(!empty($userID) &&  is_user_logged_in() ):
			$table = $wpdb->prefix.'online_booking';
			$rowToDelete = $wpdb->delete( $table, array( 
				'ID' 	=> $tripIDtoDelete,
			 ) );
			$userTripsDelete = "success";
		else: 
			$userTripsDelete = 'failed to delete';
		endif;

		return $userTripsDelete;
		
		
		
	}

	/**
	 * updateTrip
	 *
	 * @param $bookink_obj
	 * @param $session_id_trip
	 * @param $trip_name
	 * @return string
	 */
	private static function updateTrip($bookink_obj,$session_id_trip,$trip_name){
		
		global $wpdb;
		$date =  current_time('mysql', 1);

		$wpdb->update( 
			$wpdb->prefix.'online_booking', 
			array( 
				'booking_object' => $bookink_obj,	// string
				'booking_date' => $date,
				'booking_ID' => $trip_name,	

			), 
			array( 'trip_id' => $session_id_trip ), 
			array( 
				'%s',
			), 
			array( '%s','$s','$s', ) 
		);
		
		return 'updated';
	}

}
