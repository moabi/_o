<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 22/09/16
 * Time: 08:30
 */
$ob_user = new online_booking_vendor();
$ob_budget = new online_booking_budget();
$class_ux = new online_booking_ux();
$roadbook = new online_booking_roadbook();
$output = '';
$args = array(
	'validation'    => 1,
	'status'        => array(1,2,3)
);
$user_id = get_current_user_id();
$trips = $ob_user->get_vendor_booking($args);

//display new packs
$output .= '<h2> <i class="fa fa-clock-o" aria-hidden="true"></i> Réservations en cours</h2>';
	//add user booking at the state of
	// 1: paid, current
	// 2: paid, archived


/**
 * my_posts_where
 * https://www.advancedcustomfields.com/resources/query-posts-custom-fields/
 * @param $where
 *
 * @return mixed|string
 */

function my_posts_where_vendor_activities( $where ) {

	$where = str_replace("meta_key = 'day_%", "meta_key LIKE 'day_%", $where);
	$where .= str_replace("meta_key = 'products_%", "meta_key LIKE 'products_%", $where);

	return $where;
}

add_filter('posts_where', 'my_posts_where_vendor_activities');

// args
$vendor_posts = $ob_user->get_vendor_activities_ids($user_id);
$args = array(
	'numberposts'	=> 99,
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

		//booking header
		$output .= '<div id="trip-'.$rb_meta['trip_id'].'" class="table-header brown-head"><div class="pure-g">';
		$output .= '<div class="pure-u-7-24">Réservations en cours</div>';
		$output .= '<div class="pure-u-6-24">Dates</div>';
		$output .= '<div class="pure-u-3-24">Référence</div>';
		$output .= '<div class="pure-u-4-24">Contact</div>';
		$output .= '<div class="pure-u-4-24">Chef de projet</div>';
		$output .= '</div></div>';

		$output .= '<div class="event-body"><div class="pure-g">';
		$output .= '<div class="pure-u-1"><div class="pure-g">';

		$output .= '<div class="pure-u-7-24 resa">';
		$output .= '<span class="ttrip-title">' . get_the_title() . '</span><br />';
		$output .= '<span class="ttrip-title users"><i class="fa fa-users" aria-hidden="true"></i> ' . $rb_meta['participants'] . ' personne(s)
		</span>';
		$output .= '</div>';

		$output .= '<div class="pure-u-6-24">';
		$output .= '<span class="ttrip-title">' . $rb_meta['dates'] . '</span>';
		$output .= '</div>';

		$output .= '<div class="pure-u-3-24">';
		$output .= '<span class="ttrip-ref">'.$rb_meta['trip_id'].'</span>';
		$output .= '</div>';

		$output .= '<div class="pure-u-4-24">';
		$output .= '<span class="ttrip-id"><i class="fa fa-phone" aria-hidden="true"></i> ' . $rb_meta['manager_phone']. '</span>';
		$output .= '</div>';

		$output .= '<div class="pure-u-4-24">';

		$output .= '<span class="ttrip-avatar align-center">';
		$output .= $class_ux->get_custom_avatar($rb_meta['manager_id'],48);
		$output .= '</span>';

		$output .= '<span class="ttrip-client align-center">';
		$output .= '<span class="ttrip-title">' . $rb_meta['manager_name']. '</span>';
		$output .= '</span>';
		$output .= '</div>';


		$output .= '</div></div></div></div>';


		$output .= '<div class="table-body inner-table">';
		//TABLE EVENTS HEADER
		$output .= '<div class="events-header brown-head-light"><div class="pure-g">';
		$output .= '<div class="pure-u-11-24">Réservation</div>';
		$output .= '<div class="pure-u-2-24">Prix</div>';
		$output .= '<div class="pure-u-2-24">Acompte</div>';
		$output .= '<div class="pure-u-2-24">Solde</div>';
		$output .= '<div class="pure-u-7-24">Actions</div>';

		$output .= '</div></div>';


		if( have_rows('day') ){
			while( have_rows('day') ): the_row();
				$day = get_sub_field('daytime');
				$products = get_sub_field('products');
				$i = 0;
				$formated_date = explode('/',$day);
				$date = date_create($formated_date[2].'-'.$formated_date[1].'-'.$formated_date[0]);
				//date_format($date,"Y/m/d");

				if( have_rows('products') ){
					while( have_rows('products') ): the_row();
						$id = (get_sub_field('id')) ? get_sub_field('id') : 0;
						if(in_array($id, $vendor_posts)){
						$time = get_sub_field('time');
						$uuid = intval(get_sub_field('uuid'));
						$price = intval(get_sub_field('price'));
						$participants_activite = intval(get_sub_field('participants'));
						$type_icon = $class_ux->get_reservation_type($id,true);

//var_dump($result);

						$i++;
						$even_class = ($i%2 == 0)? 'row-even': 'row-odd';
						$status = (get_sub_field('status')) ? get_sub_field('status') : 0;
						$activity_id = $id;
						$default_attr = array(
							'class'	=> "centered",
						);


						$output .= '<div class="pure-u-1 '.$even_class.'"><div class="pure-g row1">';
						//image
						$output .= '<div class="pure-u-3-24 thumb">';
						$output .= get_the_post_thumbnail($activity_id, array(180,60),$default_attr);
						$output .= '</div>';
						//activity
						$output .= '<div class="pure-u-8-24 ref">';
						$output .= '<span class="ttrip-ref">Ref: '. $uuid.'</span><br />';
						$output .= '<strong>' . get_the_title($activity_id).'</strong><br />';
						$output .= '<span>Durée :' . $class_ux->get_activity_time($activity_id).'</span>';
						$output .= '</div>';
						//PRICE
						$output .= '<div class="pure-u-2-24 price">';
						$output .= '<span class="ttrip-price">';
						$output .= $price.' <i class="fa fa-euro"></i>';
						$output .= '</span>';
						$output .= '</div>';
						//Acompte
						$output .= '<div class="pure-u-2-24 acompte">';
						$output .= '<span class="ttrip-status">';
						$output .= '<i class="fa fa-check" aria-hidden="true"></i>';
						//$output .= $ob_user->get_activity_status_wording($status);
						$output .= '</span>';
						$output .= '</div>';
						//Solde
						$output .= '<div class="pure-u-2-24 solde">';
						$output .= '<span class="ttrip-status">';
						$output .= '<i class="fa fa-check" aria-hidden="true"></i>';
						$output .= '</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-7-24 actions">';
						$output .= '<a class="btn btn-border border-black" href="#" onclick="setActivityStatus(2,'.$uuid.');">Refuser</a>';

						$output .= '<a title="En validant cette réservation vous vous engagez à sa bonne réalisation le Jour J" class="button" href="#" onclick="setActivityStatus(3,'.$uuid.');">Valider</a><br />';
						$output .= '<i class="fa fa-info-circle" aria-hidden="true"></i> Dés validation de cette réservation, vous vous engagez à sa réalisation.';
						$output .= '</div>';

						$output .= '</div>';

						$output .= '<div class="pure-g row2">';
						$output .= '<div class="pure-u-3-24">';

						$output .= '</div>';
						$output .= '<div class="pure-u-5-24">';
						$output .= '<span class="ttrip-participants"><i class="fa fa-users" aria-hidden="true"></i> ' . $participants_activite . ' participant(s)</span>';
						$output .= '</div>';
						$output .= '<div class="pure-u-5-24">';
						$output .= '<span class="ttrip-date"><i class="fa fa-calendar-o" aria-hidden="true"></i> ' . date_format($date,"d F Y").'</span>';
						$output .= '</div>';
						$output .= '<div class="pure-u-2-24">';
						$output .= '<span class="ttrip-date"><i class="fa fa-clock-o" aria-hidden="true"></i> ' . substr($time,0,5).'</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-3-24">';
						$output .= '<span class="btn btn-border border-black" onclick="">Debrief</span>';
						$output .= '</div>';
						$output .= '<div class="pure-u-6-24">';
						$output .= '<span class="btn btn-border border-orange" onclick="modifyActivity('.$uuid.',\''.substr($time,0,5).'\');">Proposer une modification</span>';
						$output .= '</div>';

						$output .= '</div>';

						$output .= '</div>';
						}

					endwhile;
				}


			endwhile;
		}


		$output .= '</div>';




	endwhile;
} else {
	$output .= '<div id="trip-no-result" class="table-header brown-head"><div class="pure-g">';
	$output .= '<div class="pure-u-7-24">Réservations en cours</div>';
	$output .= '<div class="pure-u-6-24">Dates</div>';
	$output .= '<div class="pure-u-3-24">Référence</div>';
	$output .= '<div class="pure-u-4-24">Contact</div>';
	$output .= '<div class="pure-u-4-24">Chef de projet</div>';
	$output .= '</div></div>';

	$output .= '<div class="event-body"><div class="pure-g">';
	$output .= '<div class="pure-u-1"><div class="pure-g">';
	$output .= 'Aucune réservation pour le moment.';
	$output .= '</div></div>';
	$output .= '</div></div>';
}
$output .= '</div>';
wp_reset_query();


$output .= '<h2> <i class="fa fa-flag-checkered" aria-hidden="true"></i> Vos commandes</h2>';

return $output;



