<?php

$ob_user = new online_booking_vendor();
$ux = new online_booking_ux();
$user_id = get_current_user_id();
?>



<h1><?php echo get_the_title(); ?></h1>


<?php echo $ob_user->get_legal_documents(false); ?>
