<?php
/**
 * Template Name: booking-invite
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$errormsg              = "<p>Une erreur est survenue pendant le traitement de votre séjour, merci de revenir vers nous et de nous contacter directement. Nous sommes désolé de cet inconvénient.</p>";
$ux                    = new online_booking_ux;
$obp                   = new Online_Booking_Public( 'ob', 1 );
$obwc                  = new onlineBookingWoocommerce( 'ob', 1 );
$online_booking_budget = new online_booking_budget;
$online_booking_user   = new online_booking_user;
//UT GET VAR
$uri             = ( isset( $_GET['trip'] ) ) ? intval($_GET['trip']) : false;
$current_user_id = get_current_user_id();


if ( $uri ) {
	//we should encode the get params at min ?
	$public_url = $obp->decode_str( $uri );

	global $wpdb;
	$data = explode( '-', $public_url );

	//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID
	$sql = $wpdb->prepare( "
						SELECT *
						FROM " . $wpdb->prefix . "online_booking a
						WHERE a.user_ID = %d
						AND a.trip_id = %d
						", $current_user_id, $uri );

	$results = $wpdb->get_results( $sql );

	$trip_id = (isset($results[0]->ID)) ? intval($results[0]->ID) : false;
	if($trip_id){
		$trip_name = (isset($results[0]->booking_ID)) ? (string) $results[0]->booking_ID :
			false;
		$state       = ( isset( $results[0]->validation ) ) ? $results[0]->validation : null;
		$booking     = ( isset( $results[0]->booking_object ) ) ? $results[0]->booking_object : null;
		$invoiceID   = $online_booking_user->get_invoiceID( $results[0]);
		$invoicedate = $online_booking_user->get_invoice_date( $results[0] );
		//ADD ITEMS TO THE CART
		$obwc->wc_add_to_cart( $trip_id, $booking, $state, true );
		$is_the_client = ( $state == 0 ) ? true : false;
	} else {
		$is_the_client = false;
	}

	$layout_class  = ( $is_the_client ) ? 'pure-u-14-24' : 'pure-u-1';


} else {
	$state        = 'undefined';
	$booking      = null;
	$results      = null;
	$user         = null;
	$trip         = null;
	$invoiceID    = null;
	$invoicedate  = null;
	$layout_class = 'pure-u-1';
	$is_the_client = false;
}

$editPen = ( $is_the_client ) ? '<i class="fa fa-pencil" onclick="loadTrip(trip' . $trip . ',true)"></i>' : '';

get_header();
?>

<?php
if($is_the_client){
	echo $ux->get_dahsboard_menu();
}
?>
	<section id="primary" class="content-area archive-reservations tpl-feuille-de-route">
		<div id="main" class="site-main" role="main">
			<div id="account-wrapper" class="inner-content">
				<div class="pure-g">
					<div class="<?php echo $layout_class; ?>">
						<div id="content-b" class="site-content-invite">
							<?php
							$output = '';
							if ( isset( $public_url ) && $uri ) {

								if ( $results ) {



									if ( $is_the_client ) {
										$output .= '<script>var trip' . $trip . ' = ' . $booking . '</script>';
										$output .= '<script>var trip = ' . $booking . '</script>';
									}
									$output .= '<div id="page-header" class="post-content">';
									$output .= '<div class="pure-g">';

									$output .= '<div class="pure-u-1">';
									$output .= '<h1 class="text-center"><i class="fa fa-map-marker" aria-hidden="true"></i>' .
									           get_the_title().
									           '</h1>';
									$output .= '<h2 class="text-center">' . $trip_name . ' ' . $editPen . '</h2>';
									$output .= '</div>';

									$output .= '<div class="pure-u-11-24">';
									$output .= '<div class="activity-budget-user">';
									$output .= '<ul>';
									$output .= '<li><i class="fa fa-map-marker" aria-hidden="true"></i> Organisateur:
									 '.$online_booking_budget->get_trip_informations('manager',$uri).'</li>';
									$output .= '<li><i class="fa fa-users" aria-hidden="true"></i> Participants: '.$online_booking_budget->get_trip_informations('participants',$uri).' personne(s)</li>';
									$output .= '<li><i class="fa fa-calendar-o" aria-hidden="true"></i> Date: '.$online_booking_budget->get_trip_informations('dates',$uri).'</li>';
									$output .= '</ul>';
									$output .= '</div>';
									$output .= '</div>';

									$output .= '<div class="pure-u-2-24">';
									$output .= '</div>';

									$output .= '<div class="pure-u-11-24">';
									$output .= '<div class="activity-budget-user">';
									$output .= '<ul>';
									$output .= '<li><i class="fa fa-user" aria-hidden="true"></i> 
									Conseiller Onlyoo:</li>';
									$output .= '<li><i class="fa fa-phone" aria-hidden="true"></i> Contact:</li>';
									$output .= '</ul>';
									$output .= '</div>';
									$output .= '</div>';

									$output .= '<div class="pure-u-1-4 devis-line">';
									if ( $is_the_client ) {
										$output .= 'Devis n°' . $invoiceID . '<br />';
										$output .= 'du ' . $invoicedate;
									}
									$output .= '</div>';

									$output .= '</div>';
									$output .= '</div>';

									$output .= $online_booking_budget->the_trip( $trip_id, $booking, $state,
										true, $is_the_client );

									$output .= '<div class="post-content">';
									$output .= $ux->socialShare();
									$output .= '</div>';


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