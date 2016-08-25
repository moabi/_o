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


class online_booking_budget  {

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
	
	public function the_budget($tripID , $item, $tripDate){
		

		$budget = json_decode($item, true);
		$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];
		
		$newDate = date("d/m/y", strtotime($tripDate));
		$newDateDevis = date("dmy", strtotime($tripDate));
		
		//VISIBLE LINK
		echo '<span class="user-date-invoice"><a class="open-popup-link" href="#tu-'.$tripID.'">Devis n°ol'.$newDateDevis.$tripID.' ('.$newDate.')</a></span>';
		
		
		//var_dump($budget);
		echo '<div class="mfp-hide" id="tu-'.$tripID.'">';
		echo '<div class="trip-budget-user">';
		echo '<h3>Le budget de votre event</h3>';
		echo '<div class="excerpt-user pure-g">';
		echo '<div class="pure-u-1-3">'.$budget['days'].' jours</div>';
		echo '<div class="pure-u-1-3">'.$budget['participants'].' participants </div>';
		echo '<div class="pure-u-1-3">Buget Max Total : '.$budgetMaxTotal.' </div>';
		
		echo 'Budget Minimum par personne : '.$budget['budgetPerMin'].'<br />';
		echo 'Budget Minimum : '.$budget['budgetPerMin'] * $budget['participants'].'<br />';
		echo 'Budget Maximum par personne : '.$budget['budgetPerMax'].'<br />';
		echo 'Budget Maximum : '.$budget['budgetPerMax'] * $budget['participants'].'<br />';
		echo 'Budget global par personne : '.$budget['currentBudget'].'<br />';
		echo '</div>';
		echo 'Budget Total : '.$budget['currentBudget'] * $budget['participants'].'<br />';
		echo '<h4>Détails de votre event : </h4>';
		$trips = $budget['tripObject'];
		$budgetSingle = array();
		//var_dump(is_array($trips));
		echo '<div class="activity-budget-user pure-g">';
			        echo '<div class="pure-u-1-3">Activité</div>';
			        //echo $value['type'].'<br />';
		            echo '<div class="pure-u-1-3">prix/pers</div>';
		            echo '<div class="pure-u-1-3">prix total</div>';
		echo '</div>';
		foreach ($trips as $trip) {
		    //  Check type
		    if (is_array($trip)){
		        //  Scan through inner loop
		        
		        foreach ($trip as $value) {
			        //calculate 
			        array_push($budgetSingle, $value['price']);
			        //html
			        echo '<div class="activity-budget-user pure-g">';
			        echo '<div class="pure-u-1-3">'.$value['name'].'</div>';
			        //echo $value['type'].'<br />';
		            echo '<div class="pure-u-1-3">'.$value['price'].'</div>';
		            echo '<div class="pure-u-1-3">'.$value['price'] * $budget['participants'].'</div>';
		            echo '</div>';
		        }
		    }else{
		        // one, two, three
		        echo $trip;
		    }
		}
		$single_budg = array_sum($budgetSingle);
		$global_budg = $single_budg * $budget['participants'];
		echo '<div class="activity-budget-user pure-g">';
			        echo '<div class="pure-u-1-3">Budget Total</div>';
			        //echo $value['type'].'<br />';
		            echo '<div class="pure-u-1-3">'.$single_budg.'</div>';
		            echo '<div class="pure-u-1-3">'.$global_budg.'</div>';
		echo '</div>';
		
		echo '</div>';
		echo '</div>';
		echo '</div>';

	}




	/**
	 * the_trip
	 * Display a SEJOUR from the jSON file in DB
	 *
	 *
	 * @param $tripID integer tripID as in the DB
	 * @param $item object the booking original object json
	 * @param $state integer (0 - 4 )
	 * @param bool $from_db
	 * @return $output Quote/invoice
	 */
	public function the_trip($tripID , $item, $state,$from_db = false){

		if($from_db == true){

			global $wpdb;
			//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID
			$sql = $wpdb->prepare("
						SELECT *
						FROM ".$wpdb->prefix."online_booking a
						WHERE a.ID = %d
						",$tripID);

			$results = $wpdb->get_results($sql);

			$it = $results[0];
			$item = (isset($results[0])) ? $it->booking_object : $item;
			$budget = json_decode($item, true);

		} else {
			$budget = json_decode($item, true);
		}
		$output = '';

		$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];
		$ux = new online_booking_ux;
		//var_dump($budget);
		$output .= '<div id="event-trip-planning" class="trip-public">';
		$output .= '<div class="trip-public-user">';

		$trips = $budget['tripObject'];
		$budgetSingle = array();
		//var_dump(is_array($trips));
		//var_dump($budget);
		$days = ($budget['days'] > 1) ? $budget['days'].' jours' : $budget['days'].' jour';
		$place_id = $budget['lieu'];
		$place_trip = get_term_by('id', $place_id, 'lieu');
		$dates = ($budget['arrival'] == $budget['departure']) ? $budget['arrival'] : ' du '.$budget['arrival'].' au '.$budget['departure'];
		$number_participants = (isset($budget['participants'])) ? $budget['participants'] : 1;
		
			$output .= '<div class="activity-budget-user pure-g"><div class="post-content" style="width:100%">';
			$output .= '<div class="pure-u-6-24"><i class="fa fa-map-marker"></i>Lieu: <strong>'.$place_trip->name.'</strong></div>';
		    $output .= '<div class="pure-u-6-24"><i class="fa fa-users"></i>Participants: <strong>'.$number_participants.' personnes</strong></div>';
		    $output .= '<div class="pure-u-6-24"><i class="fa fa-clock-o"></i>Durée : <strong>'.$days.'</strong></div>';
		    $output .= '<div class="pure-u-6-24"><i class="fa fa-calendar"></i>Date : <strong>'.$dates.'</strong></div>';
		    $output .= '</div></div>';
		
		$trip_dates =  array_keys($trips);
		$days_count = 0;
		foreach ($trips as $trip) {
			$dayunit = $days_count + 1;
			$output .= '<div class="event-day day-content post-content">';
			$output .= '<div class="etp-day">';
			$output .= '<div class="day-title">';
			$output .= '<i class="fa fa-calendar"></i>Journée '. $dayunit .' - '.$trip_dates[$days_count].'</div>';
			$output .= '</div>';
			

			/**
			 * loop through products
			 * destroy any previous woocommerce session
			 * create a new cart
			 * add each product to cart with quantities (based on number of participants)
			 */
			//  Check type
		    if (is_array($trip)){
		        //  Scan through inner loop
		        $trip_id =  array_keys($trip);
		        $i = 0;
		        $output .= '<div class ="etp-days" >';
		        foreach ($trip as $value) {
			        //calculate 
			        //var_dump($value);
					$product_id = (isset($trip_id[$i])) ? $trip_id[$i] : 0;
					$productPrice = (isset($value['price'])) ? $value['price'] : 0;
					$productName = (isset($value['name'])) ? $value['name'] : 'Undefined Name';
					//old way to calculate price
			        array_push($budgetSingle, $productPrice);
					//woocommerce calculate price
					//$obwc->wc_items_to_cart($product_id,$number_participants,0,array(),array());
					//do_action( 'wc_items_to_cart', $product_id,$number_participants,0,array(),array());
					//global $woocommerce;
					//WC()->cart->add_to_cart($product_id, $number_participants);

			        $excerpt = get_field('la_prestation_comprend',$product_id);
			        //html - display each product
			        $output .= '<div data-id="'.$product_id.'" class="pure-u-1 single-activity-row">';
			        $output .= '<div class="pure-u-1 pure-u-md-3-24">';
			        $output .= get_the_post_thumbnail($product_id,array(180,120));
					$output .= '</div>';
			        
			        $output .= '<div class="pure-u-1 pure-u-md-3-24 sejour-type">';
					$output .= $ux->get_reservation_type($product_id);
					$output .= '</div>';
			        
			        $output .= '<div class="pure-u-1 pure-u-md-17-24">';
			        $output .= '<h3><a href="'.get_permalink($product_id).'" target="_blank">';
			        $output .= $productName.'</a></h3>';
					$output .= do_shortcode('[add_to_cart id='.$product_id.']');
		            $output .= substr($excerpt, 0, 250) ;
		            $output .= '</div>';
		            $output .= '</div>';
		            $i++;
		        }
		        $output .= '</div>';
		        $days_count++;
		    }else{
		        // one, two, three
		        $output .= $trip;
		    }
		    $output .= '</div>';
		    
		}

			//bUdget display
			//var_dump($budgetSingle);
			/*
			 * User is logged In
			 * Estimate or invoice Step
			 *  */
			if(is_user_logged_in() && $state < 2){
				$budgetPerParticipant = array_sum($budgetSingle);
				$budgetPerParticipantTtc = $budgetPerParticipant*1.2;
				$output .= '<div class="pure-g post-content"><div class="event-day" style="padding:1em;background:#fafafa;">';
				$output .= '<div class="pure-g">';
				$output .= '<div class="pure-u-1-2">Nos prix sont calculés sur la base de nombre de participants indiqués dans votre devis. Le prix et la disponibilité de la prestation sont garantis le jour de l\'émission du devis et sont suceptibles d\'être réajustés lors de votre validation.</div>';
				$output .= '<div class="pure-u-1-2" style="text-align:right;">';
				$output .= 'Total budget HT : '.$budgetPerParticipant.'€<br />';
				$output .= 'Total budget TTC : '.$budgetPerParticipantTtc.'€<br />';
				$output .= '</div></div>';
		    /*
		     * estimate step
		     * */
		    if($state == 0){
				$output .= '<div class="pure-g" id="userTrips"><div class="pure-u-1-2">';
			    $output .= '<div class="btn btn-border" onclick="loadTrip(trip,true)"><i class="fs1" aria-hidden="true" data-icon="j"></i>'.__('Modifier votre séjour','online-booking').'</div>';
			    $output .= '</div><div class="pure-u-1-2">';
			    $output .= '<div class="btn-orange btn quote-it js-quote-user-trip" onclick="estimateUserTrip('.$tripID.')"><i class="fa fa-check"></i>Valider mon devis</div>';

			    $output .= '</div></div>';
		    }
		    $output .= '</div>';
		    $output .= '</div></div>';
		    
		    //#budget
		    }
		
		$output .= '</div>';
		$output .= '</div>';

		return $output;

	}





}