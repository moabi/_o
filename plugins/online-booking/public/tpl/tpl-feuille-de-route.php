<?php
/**
 * Template Name: booking-invite
 * DEPRECATED
 * USE TPL-PUBLIC 2 NOW
 */
$errormsg              = "<p>Une erreur est survenue pendant le traitement de votre séjour, merci de revenir vers nous et de nous contacter directement. Nous sommes désolé de cet inconvénient.</p>";
$ux                    = new online_booking_ux;
$obp                   = new Online_Booking_Public( 'ob', 1 );
$obwc                  = new onlineBookingWoocommerce( 'ob', 1 );
$ob_budget = new online_booking_budget;
$online_booking_user   = new online_booking_user;
//UT GET VAR
$trip_uuid             = ( isset( $_GET['trip'] ) ) ? intval($_GET['trip']) : false;
$current_user_id = get_current_user_id();
$is_the_client = true;

if ( $trip_uuid ) {
	//we should encode the get params at min ?
	$public_url = $obp->decode_str( $trip_uuid );

	global $wpdb;


	//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID
	$sql = $wpdb->prepare( "
						SELECT *
						FROM " . $wpdb->prefix . "online_booking a
						WHERE a.user_ID = %d
						AND a.trip_id = %d
						", $current_user_id, $trip_uuid );

	$results = $wpdb->get_results( $sql );



		$trip_name = $ob_budget->get_trip_informations('booking-name',$trip_uuid);
		$state       = $ob_budget->get_trip_informations('state',$trip_uuid);
		$booking     = ( isset( $results[0]->booking_object ) ) ? $results[0]->booking_object : null;

		//ADD ITEMS TO THE CART

	$is_the_client = ( intval($ob_budget->get_trip_informations('client-id',$trip_uuid)) == intval($current_user_id) && $state == 0 ) ? true : false;

}
//$obwc->wc_add_to_cart( $trip_uuid, $booking, $state, true );
$layout_class  = 'pure-u-1';
//$editPen = ( $is_the_client ) ? '<i class="fa fa-pencil" onclick="loadTrip(trip' . $trip . ',true)"></i>' : '';

get_header();
?>

<?php

	echo $ux->get_dahsboard_menu();

?>
	<section id="primary" class="content-area archive-reservations tpl-feuille-de-route">
		<div id="main" class="site-main" role="main">
			<div id="account-wrapper" class="inner-content">
				<div class="pure-g">
					<div class="<?php echo $layout_class; ?>">
						<div id="content-b" class="site-content-invite">
							<?php
							$output = '';
							if ( isset( $public_url ) && $trip_uuid ) {

								if ( $results ) {

									$output .= '<div id="page-header" class="post-content">';
									$output .= '<div class="pure-g">';

									$output .= '<div class="pure-u-1">';
									$output .= '<h1 class="text-center"><i class="fa fa-map-marker" aria-hidden="true"></i> ' .get_the_title().'</h1>';
									$output .= '<h2 class="text-center">'.$ob_budget->get_trip_informations('booking-name',$trip_uuid).'</h2>';
									$output .= '</div>';

									$output .= '<div class="pure-u-11-24">';
									$output .= '<div class="activity-budget-user">';
									$output .= '<ul>';
									$output .= '<li><i class="fa fa-map-marker" aria-hidden="true"></i> Organisateur: '.$ob_budget->get_trip_informations('client',$trip_uuid).'</li>';
									$output .= '<li><i class="fa fa-users" aria-hidden="true"></i> Participants: '.$ob_budget->get_trip_informations('participants',$trip_uuid).' personne(s)</li>';
									$output .= '<li><i class="fa fa-calendar-o" aria-hidden="true"></i> Date: '.$ob_budget->get_trip_informations('dates',$trip_uuid).'</li>';
									$output .= '</ul>';
									$output .= '</div>';
									$output .= '</div>';

									$output .= '<div class="pure-u-2-24">';
									$output .= '</div>';

									$output .= '<div class="pure-u-11-24">';
									$output .= '<div class="activity-budget-user">';
									$output .= '<ul>';
									$output .= '<li><i class="fa fa-user" aria-hidden="true"></i> 
									Conseiller Onlyoo: '.$ob_budget->get_trip_informations('manager',$trip_uuid).'</li>';
									$output .= '<li><i class="fa fa-phone" aria-hidden="true"></i> Contact: '.$ob_budget->get_trip_informations('manager-phone',$trip_uuid).'</li>';
									$output .= '</ul>';
									$output .= '</div>';
									$output .= '</div>';

									$output .= '</div>';
									$output .= '</div>';

									$output .= $ob_budget->the_trip( $trip_uuid, false);

									$output .= '<h2>Localisation activités</h2>';
									$output .= $ob_budget->get_trip_map($trip_uuid);

								} else {
									$output .= __( '<h1>Désolé, nous ne parvenons pas à retrouver cette reservation</h1>' . $errormsg, 'online-booking' );
								}
							} else {
								$output .= '<h1>' . __( 'Désolé, nous ne parvenons pas à retrouver cette reservation', 'online-booking' ) . '</h1>';
							}
							echo $output;
							?>
						</div><!-- #content -->
					</div><!-- #primary -->

				</div>

			</div>
		</div>
	</section>
<?php get_footer(); ?>