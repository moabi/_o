<?php

$ob_user = new online_booking_vendor();
$ux = new online_booking_ux();
$user_id = get_current_user_id();

$output = $ux->get_private_news(true, 10);
return $output;
