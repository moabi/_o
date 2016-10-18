<?php

$ob_user = new online_booking_vendor();
$ux = new online_booking_ux();
$user_id = get_current_user_id();
?>

<div class="wcvendors-pro-dashboard-wrapper strange-blue-box">
	<?php
	$unread_count = (fep_get_user_message_count('unread') )?fep_get_user_message_count( 'unread' ):0;
	?>
	<h2 class="title-bordered"><?php echo $unread_count; ?> Messages non lu(s)</h2>

	<?php echo $ux->get_unread_news(); ?>
</div>


<div class="wcvendors-pro-dashboard-wrapper">
	<h2 class="title-bordered">Projets en cours</h2>
	<?php
	echo $ob_user->get_vendor_booking(1,array(1),$user_id, false);
	$output = '<div class="pure-g">';
	$output .= '<div class="pure-u-1">';
	$output .= '<a href="'.get_bloginfo('url').'/'.VENDOR_ORDER.'" class="btn btn-reg btn-blue push-right">Voir tous les projets</a>';
	$output .= '</div>';
	$output .= '</div>';
	echo $output
	?>
</div>

<?php echo $ux->get_private_news(); ?>
