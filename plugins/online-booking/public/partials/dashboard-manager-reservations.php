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
echo 'TEST';
$ids = $pm->get_vendors_affiliated_id();
foreach ($ids as $id){
	echo $pm->get_booking_by_user_id(1,array(1,2,3),$id);
}


