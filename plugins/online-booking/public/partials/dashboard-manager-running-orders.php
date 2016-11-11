<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 22/09/16
 * Time: 08:30
 */
$ob_user = new online_booking_vendor();
$ob_budget = new online_booking_budget();
$args = array(
	'validation'    => 1,
	'status'        => array(1,2,3)
);
$user_id = get_current_user_id();
$trips = $ob_user->get_vendor_booking($args);

//display new packs
echo '<h2> <i class="fa fa-clock-o" aria-hidden="true"></i> Réservations en cours</h2>';
	//add user booking at the state of
	// 1: paid, current
	// 2: paid, archived

echo '<div id="vendor-bookings" class="bk-listing pure-table">';
//loop through trips to find vendors activities sold
foreach ( $trips['trip_uuid'] as $unique_trip_id ) {

	$manager_email = $ob_budget->get_trip_informations('manager-email',$unique_trip_id);

	//booking header
	echo '<div id="trip-'.$unique_trip_id.'" class="table-header brown-head"><div class="pure-g">';
	echo '<div class="pure-u-7-24">Réservations en cours</div>';
	echo '<div class="pure-u-6-24">Dates</div>';
	echo '<div class="pure-u-3-24">Référence</div>';
	echo '<div class="pure-u-4-24">Contact</div>';
	echo '<div class="pure-u-4-24">Chef de projet</div>';
	echo '</div></div>';

	echo '<div class="event-body"><div class="pure-g">';
	echo '<div class="pure-u-1"><div class="pure-g">';

	echo '<div class="pure-u-7-24 resa">';
	echo '<span class="ttrip-title">' . $ob_budget->get_trip_informations('booking-name',$unique_trip_id) . '</span><br />';
	echo '<span class="ttrip-title users"><i class="fa fa-users" aria-hidden="true"></i> ' . $ob_budget->get_trip_informations('participants',$unique_trip_id) . ' personne(s)</span>';
	echo '</div>';

	echo '<div class="pure-u-6-24">';
	echo '<span class="ttrip-title">' . $ob_budget->get_trip_informations('dates',$unique_trip_id) . '</span>';
	echo '</div>';
    
    echo '<div class="pure-u-3-24">';
	echo '<span class="ttrip-ref">'.$unique_trip_id.'</span>';
	echo '</div>';

	echo '<div class="pure-u-4-24">';
	echo '<span class="ttrip-id"><i class="fa fa-phone" aria-hidden="true"></i> ' . $ob_budget->get_trip_informations('manager-phone',$unique_trip_id) . '</span>';
	echo '</div>';

	echo '<div class="pure-u-4-24">';

	echo '<span class="ttrip-avatar align-center">';
	echo get_avatar( $manager_email, 48 );
	echo '</span>';

	echo '<span class="ttrip-client align-center">';
	echo '<span class="ttrip-title">' . $ob_budget->get_trip_informations('manager',$unique_trip_id) . '</span>';
	echo '</span>';
	echo '</div>';


	echo '</div></div></div></div>';


		echo '<div class="table-body inner-table">';
		//TABLE EVENTS HEADER
		echo '<div class="events-header brown-head-light"><div class="pure-g">';
		echo '<div class="pure-u-11-24">Réservation</div>';
		echo '<div class="pure-u-2-24">Prix</div>';
		echo '<div class="pure-u-2-24">Acompte</div>';
		echo '<div class="pure-u-2-24">Solde</div>';
		echo '<div class="pure-u-7-24">Actions</div>';

		echo '</div></div>';

		//SUB TR - display events
		//display each event
		$i = 0;
		foreach ( $trips['results'] as $result ) {

			//var_dump($result);
			if($result->trip_id == $unique_trip_id && $result->vendor == $user_id){
				$i++;
				$even_class = ($i%2 == 0)? 'row-even': 'row-odd';
				$status = (isset($result->status)) ? $result->status : 0;
				$activity_id = $result->activity_id;
				$default_attr = array(
					'class'	=> "centered",
				);
				$date = date_create($result->activity_date);

				echo '<div class="pure-u-1 '.$even_class.'"><div class="pure-g row1">';
				//image
				echo '<div class="pure-u-3-24 thumb">';
				echo get_the_post_thumbnail($activity_id, array(180,60),$default_attr);
				echo '</div>';
				//activity
				echo '<div class="pure-u-8-24 ref">';
				echo '<span class="ttrip-ref">Ref: '. $result->activity_uuid.'</span><br />';
				echo '<strong>' . get_the_title($activity_id).'</strong>';
				echo '</div>';
				//PRICE
				echo '<div class="pure-u-2-24 price">';
				echo '<span class="ttrip-price">';
				echo $result->price.' <i class="fa fa-euro"></i>';
				echo '</span>';
				echo '</div>';
				//Acompte
				echo '<div class="pure-u-2-24 acompte">';
				echo '<span class="ttrip-status">';
				echo '<i class="fa fa-check" aria-hidden="true"></i>';
				//echo $ob_user->get_activity_status_wording($status);
				echo '</span>';
				echo '</div>';
				//Solde
				echo '<div class="pure-u-2-24 solde">';
				echo '<span class="ttrip-status">';
				echo '<i class="fa fa-check" aria-hidden="true"></i>';
				echo '</span>';
				echo '</div>';

				echo '<div class="pure-u-7-24 actions">';
				echo '<a class="btn btn-border border-black" href="#" onclick="setActivityStatus(2,'.$result->activity_uuid.');">Refuser</a>';

				echo '<a title="En validant cette réservation vous vous engagez à sa bonne réalisation le Jour J" class="button" href="#" onclick="setActivityStatus(3,'.$result->activity_uuid.');">Valider</a><br />';
				echo '<i class="fa fa-info-circle" aria-hidden="true"></i> Dés validation de cette réservation, vous vous engagez à sa réalisation.';
				echo '</div>';

				echo '</div>';

				echo '<div class="pure-g row2">';

				echo '<div class="pure-u-3-24">';
                echo '</div>';
				echo '<div class="pure-u-5-24">';
				echo '<span class="ttrip-participants"><i class="fa fa-users" aria-hidden="true"></i> ' . $result->quantity . ' participant(s)</span>';
				echo '</div>';
				echo '<div class="pure-u-5-24">';
				echo '<span class="ttrip-date"><i class="fa fa-calendar-o" aria-hidden="true"></i> ' . date_format($date,"d F Y").'</span>';
				echo '</div>';
				echo '<div class="pure-u-2-24">';
				echo '<span class="ttrip-date"><i class="fa fa-clock-o" aria-hidden="true"></i> ' . date_format($date,"H:i").'</span>';
				echo '</div>';
				echo '<div class="pure-u-3-24">';
				echo '<span class="btn btn-border border-black" onclick="">Debrief</span>';
				echo '</div>';
				echo '<div class="pure-u-6-24">';
				echo '<span class="btn btn-border border-orange" onclick="modifyActivityTime('.$result->activity_uuid.',\''.date_format($date,"H:i").'\');">Proposer une modification</span>';
				echo '</div>';

				echo '</div>';

				echo '</div>';


			}


		}
		echo '</div>';


}
echo '</div>';

//display archived packs

echo '<h2> <i class="fa fa-flag-checkered" aria-hidden="true"></i> Vos commandes</h2>';



