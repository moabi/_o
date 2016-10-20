<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 22/09/16
 * Time: 08:30
 */
$ob_user = new online_booking_vendor();

echo '<h2> <i class="fa fa-flag-checkered" aria-hidden="true"></i> Vos projets terminés</h2>';
echo $ob_user->get_vendor_booking(2,2);

