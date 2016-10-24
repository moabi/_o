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
$ob_budget = new online_booking_budget;
$online_booking_user   = new online_booking_user;
//UT GET VAR
$trip_uuid             = ( isset( $_GET['trip'] ) ) ? intval($_GET['trip']) : false;
$current_user_id = get_current_user_id();


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
		$booking_obj     = $ob_budget->get_trip_informations('booking-object',$trip_uuid);
		$invoiceID   = $trip_uuid;
		$invoicedate = $ob_budget->get_trip_informations('invoice-date',$trip_uuid);


		//ADD ITEMS TO THE CART
		$obwc->wc_add_to_cart( $trip_uuid, $booking_obj, $state, true );
		$is_the_client = ( intval($ob_budget->get_trip_informations('client-id',$trip_uuid)) == intval($current_user_id) && $state == 0 ) ? true : false;




} else {
	$state        = 'undefined';
	$booking      = null;
	$results      = null;
	$user         = null;
	$trip         = null;
	$invoiceID    = null;
	$invoicedate  = null;

	$is_the_client = false;
}

$editPen = ( $is_the_client ) ? '<i class="fa fa-pencil" onclick="loadTrip(trip' . $trip_uuid . ',true)"></i>' : '';

get_header();
?>

<?php
	if($is_the_client){
		echo $ux->get_dahsboard_menu();
	}
?>
	<section id="primary" class="content-area archive-reservations tpl-public">
		<div id="main" class="site-main" role="main">
			<div id="account-wrapper" class="inner-content">
				<div class="pure-g">
					<div class="pure-u-1">
						<div id="content-b" class="site-content-invite">
							<div class="breadcrumb">
								<a href="<?php echo get_bloginfo('url'); ?>/mon-comte/mes-devis">Mes projets</a> <span>></span> <span><?php echo $ob_budget->get_trip_informations('booking-name',$trip_uuid); ?></span>
							</div>
							<?php
							$output = '';
							if ( isset( $public_url ) && $trip_uuid ) {

								if ( $results ) {



									if ( $is_the_client ) {
										$output .= '<script>var trip' . $trip_uuid . ' = ' . $booking_obj . '</script>';
									}
									$output .= '<div id="page-header" class="post-content">';
									$output .= '<div class="pure-g">';

									$output .= '<div class="pure-u-3-4">';
									$output .= '<h1>'.$ob_budget->get_trip_informations('booking-name',$trip_uuid). $editPen . '</h1>';
									$output .= '</div>';

									$output .= '<div class="pure-u-1-4 devis-line">';
									if ( $is_the_client ) {
										$output .= 'Devis n°' . $invoiceID . '<br />';
										$output .= 'du ' . $invoicedate;
									}
									$output .= '</div>';

									$output .= '</div>';
									$output .= '</div>';

									$output .= $ob_budget->the_trip( $trip_uuid, false, $state, true);

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

							<?php
							if ( $results && $is_the_client ) {
								echo do_shortcode( '[woocommerce_cart]' );
							}
							?>
						</div><!-- #content -->
					</div><!-- #primary -->


				</div>

			</div>
		</div>
	</section>
<?php get_footer(); ?>