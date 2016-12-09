<?php

$ob_user = new online_booking_vendor();
$ob_budget = new online_booking_budget();
$ux = new online_booking_ux();
$user_id = get_current_user_id();
$roadbook = new online_booking_roadbook();
$output = '';


//Warning box
$output .= $ob_user->get_warning_messages();

$output .= '<div class="wcvendors-pro-dashboard-wrapper">';

	$unread_count = (fep_get_user_message_count('unread') )?fep_get_user_message_count( 'unread' ):0;

	$output .='<h3 class="title-bordered">';
	$output .= $unread_count;
	$output .= ' messages non lu(s)</h3>';
	$output .= $ux->get_unread_news();
	$output .= '</div>';
	$output .= '<div class="wcvendors-pro-dashboard-wrapper strange-blue-box">';
	$output .= '<h3 class="title-bordered">Réservations en cours</h3>';


$vendor_posts = $ob_user->get_vendor_activities_ids($user_id);
$args = array(
	'numberposts'	=> 3,
	'post_type' => 'private_roadbook',
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key' => 'day_%_products_%_id',
			'value' => $vendor_posts,
			'compare' => 'IN'
		),
		array(
			'key' => 'status',
			'value' => 1,
			'compare' => '='
		),
	)


);

// query
$the_query = new WP_Query( $args );
$output .= '<div id="vendor-bookings" class="bk-listing pure-table">';
if( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) : $the_query->the_post();
		global $post;
		$rb_meta = $roadbook->get_roadbook_meta($post->ID);

		$output .= '<div  class="bk-listing pure-table white-rounded">';

		$output .= '<div class="event-body"><div class="pure-g">';
		$output .= '<div class="pure-u-1"><div class="pure-g">';

		$output .= '<div class="pure-u-17-24 text-left">';
		$output .= '<strong>' . $rb_meta['dates'] . '</strong><br />';
		$output .= '<span class="ttrip-title">' . $rb_meta['title']. '</span><br />';
		$output .= '</div>';

		$output .= '<div class="pure-u-7-24">';
		$output .= '<a class="button btn-border-orange"  href="'.get_bloginfo('url').'/'.VENDOR_ORDER.'#trip-'.$rb_meta['trip_id'].'">Voir détails</a>';
		$output .= '</div>';


		$output .= '</div></div></div></div>';

		$output .= '</div>';

	endwhile;
} else {
	$output .= '<div  class="bk-listing pure-table white-rounded">';
	$output .= '<div class="event-body"><div class="pure-g">';
	$output .= '<div class="pure-u-1"><div class="pure-g">';

	$output .= '<div class="pure-u-1 text-left">';
	$output .= 'Aucun projet pour le moment.';
	$output .= '</div>';
	$output .= '</div></div></div></div>';

	$output .= '</div>';


}


$output .= '</div>';


	$output .= '<div class="pure-g">';
	$output .= '<div class="pure-u-1">';
	$output .= '<a href="'.get_bloginfo('url').'/'.VENDOR_ORDER.'" class="btn btn-reg btn-blue push-right">Voir tous les réservations</a>';
	$output .= '</div>';
	$output .= '</div>';

$output .= '</div>';

$output .= $ux->get_private_news(false, 2,'vendeurs');

return $output;



