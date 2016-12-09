<?php
$pm_id = get_current_user_id();
$pm = new OnlineBookingProjectManager();
$vendor = new online_booking_vendor();

$ids = $pm->get_vendors_affiliated_id();
if(empty($ids)){
	return  __('Aucun résultat','online-booking');
} else {
	$output = '';
	foreach ($ids as $id){
		$output .= $pm->get_booking_by_user_id(1,array(1,2,3),$id);
	}

	return $output;
}



