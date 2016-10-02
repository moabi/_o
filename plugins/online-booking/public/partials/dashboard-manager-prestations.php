<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 01/10/16
 * Time: 11:02
 */
$pm_id = get_current_user_id();
$pm = new OnlineBookingProjectManager();
$vendor = new online_booking_vendor();

$ids = $pm->get_vendors_affiliated_id();
foreach ($ids as $id){
	echo $vendor->get_vendor_booking(1,array(1,2,3),$id);
}


