<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 22/09/16
 * Time: 08:30
 */
echo '<h2> <i class="fa fa-clock-o" aria-hidden="true"></i> Réservations en cours</h2>';

	//add user booking at the state of
	// 1: paid, current
	// 2: paid, archived
	echo online_booking_user::get_user_booking(1);
	echo online_booking_user::get_user_booking(2);


echo '<h2> <i class="fa fa-flag-checkered" aria-hidden="true"></i> Vos projets terminés</h2>';