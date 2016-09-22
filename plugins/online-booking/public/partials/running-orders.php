<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 22/09/16
 * Time: 08:30
 */
$ob_user = new online_booking_vendor();

echo '<h2> <i class="fa fa-clock-o" aria-hidden="true"></i> Réservations en cours</h2>';

	//add user booking at the state of
	// 1: paid, current
	// 2: paid, archived
	echo $ob_user->get_vendor_booking(1);


echo '<h2> <i class="fa fa-flag-checkered" aria-hidden="true"></i> Vos projets terminés</h2>';