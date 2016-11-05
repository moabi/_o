<?php

$ob_user = new online_booking_vendor();
$ob_budget = new online_booking_budget();
$ux = new online_booking_ux();
$user_id = get_current_user_id();
?>

<div class="wcvendors-pro-dashboard-wrapper">
	<?php
	$unread_count = (fep_get_user_message_count('unread') )?fep_get_user_message_count( 'unread' ):0;
	?>
	<h3 class="title-bordered"><?php echo $unread_count; ?> Messages non lu(s)</h3>

	<?php echo $ux->get_unread_news(); ?>
</div>


<div class="wcvendors-pro-dashboard-wrapper strange-blue-box">
	<h3 class="title-bordered">Projets en cours</h3>
	<?php
	$args = array(
		'validation'    => 1,
		'status'        => array(1,2,3)
	);
	$trips =  $ob_user->get_vendor_booking($args);

	//loop through trips to find vendors activities sold
	foreach ( $trips['trip_uuid'] as $unique_trip_id ) {
		echo '<div  class="bk-listing pure-table white-rounded">';
		$manager_email = $ob_budget->get_trip_informations('manager-email',$unique_trip_id);
		echo '<div class="event-body"><div class="pure-g">';
		echo '<div class="pure-u-1"><div class="pure-g">';

		echo '<div class="pure-u-18-24 text-left">';
		echo '<strong>' . $ob_budget->get_trip_informations('dates',$unique_trip_id) . '</strong><br />';
		echo '<span class="ttrip-title">' . $ob_budget->get_trip_informations('booking-name',$unique_trip_id) . '</span><br />';
		echo '</div>';

		echo '<div class="pure-u-4-24">';
		echo '<a class="button"  href="'.get_bloginfo('url').'/'.VENDOR_ORDER.'#trip-'.$unique_trip_id.'">Voir détails</a>';
		echo '</div>';


		echo '</div></div></div></div>';

		echo '</div>';
	}



	echo '<div class="pure-g">';
	echo '<div class="pure-u-1">';
	echo '<a href="'.get_bloginfo('url').'/'.VENDOR_ORDER.'" class="btn btn-reg btn-blue push-right">Voir tous les projets</a>';
	echo '</div>';
	echo '</div>';
	?>
</div>

<?php echo $ux->get_private_news(false, 2); ?>



