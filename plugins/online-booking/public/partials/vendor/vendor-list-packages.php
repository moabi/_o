<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 17/01/17
 * Time: 00:07
 */

$pub = new Online_Booking_Public('onlyoo','1');

$output = '<div class="archive-reservations">';
$output .= '<div class="white-block vendor-list-packages sejour-content">';
$output .= '<h1>Mes programmes</h1>';



$args = array(
	'post_type' => 'sejour',
	'posts_per_page' => 90,
	'post_status' => 'publish',
	'author'      => get_current_user_id()
);


$the_query = new WP_Query($args);

if ( $the_query->have_posts() ) {
	$output .= '<div class="pure-g">';
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		$post_id = get_the_ID();
		$post_author = get_post_field( 'post_author', $post_id );
		$output .= '<div class="block block-trip-container pure-u-1 pure-u-md-1-3 ">';
		$output .= '<div class="block-trip card padd-l">';
		$output .= '<h4><a href="';
		$output .= get_the_permalink() . '" >';
		$output .= get_the_title();
		$output .= get_current_user_id().$post_author;
		$output .= '</a></h4>';
		if(has_post_thumbnail()){
			$output .= get_the_post_thumbnail(null, 'thumbnail');
		} else {
			$output .= '<img src="'.get_wp_attachment_filter_plugin_uri().'/public/img/sejour-placeholder.gif" alt="onlyoo - '.get_the_title().'"/>';
		}
		$output .= '<a target="_blank" class="loadit" href="'.home_url('dashboard/ajouter-un-programme?edit=').get_the_ID().'">Modifier</a>';
		$output .= '<a target="_blank" class="seeit" href="'.get_the_permalink().'">Voir</a>';
		$output .= '</div>';
		$output .= '</div>';

		//$output .= $pub->get_sejour_card($post_id,90, true);
	}
	wp_reset_postdata();
	$output .= '</div>';
} else {
	$output .= '<p>Aucun programme pour le moment.</p>';
}

$output .= '</div>';
$output .= '</div>';
return $output;