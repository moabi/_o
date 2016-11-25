<?php

$ob_user = new online_booking_vendor();
$ob_budget = new online_booking_budget();
$ux = new online_booking_ux();
$user_id = get_current_user_id();
$output = '';


	$output .= '<div class="wcvendors-pro-dashboard-wrapper">';

	$unread_count = (fep_get_user_message_count('unread') )?fep_get_user_message_count( 'unread' ):0;

	$output .='<h3 class="title-bordered">';
	$output .= $unread_count;
	$output .= ' messages non lu(s)</h3>';
	$output .= $ux->get_unread_news();
	$output .= '</div>';
	$output .= '<div class="wcvendors-pro-dashboard-wrapper strange-blue-box">';
	$output .= '<h3 class="title-bordered">Projets en cours</h3>';

	$args = array(
		'validation'    => 1,
		'status'        => array(1,2,3)
	);
	$trips =  $ob_user->get_vendor_booking($args);

	if(isset($trips['trip_uuid']) && !empty($trips['trip_uuid'])){
		//loop through trips to find vendors activities sold
		foreach ( $trips['trip_uuid'] as $unique_trip_id ) {
			$output .= '<div  class="bk-listing pure-table white-rounded">';
			$manager_email = $ob_budget->get_trip_informations('manager-email',$unique_trip_id);
			$output .= '<div class="event-body"><div class="pure-g">';
			$output .= '<div class="pure-u-1"><div class="pure-g">';

			$output .= '<div class="pure-u-17-24 text-left">';
			$output .= '<strong>' . $ob_budget->get_trip_informations('dates',$unique_trip_id) . '</strong><br />';
			$output .= '<span class="ttrip-title">' . $ob_budget->get_trip_informations('booking-name',$unique_trip_id) . '</span><br />';
			$output .= '</div>';

			$output .= '<div class="pure-u-7-24">';
			$output .= '<a class="button btn-border-orange"  href="'.get_bloginfo('url').'/'.VENDOR_ORDER.'#trip-'.$unique_trip_id.'">Voir détails</a>';
			$output .= '</div>';


			$output .= '</div></div></div></div>';

			$output .= '</div>';
		}
	} else {

		$output .= 'Aucun projet pour le moment.';
	}




	$output .= '<div class="pure-g">';
	$output .= '<div class="pure-u-1">';
	$output .= '<a href="'.get_bloginfo('url').'/'.VENDOR_ORDER.'" class="btn btn-reg btn-blue push-right">Voir tous les projets</a>';
	$output .= '</div>';
	$output .= '</div>';

$output .= '</div>';

$output .= $ux->get_private_news(false, 2);

return $output;



