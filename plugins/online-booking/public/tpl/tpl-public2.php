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
$not_found = '<h1>' . __( 'Désolé, nous ne parvenons pas à retrouver cette reservation', 'online-booking' ) . '</h1>';
$ux                    = new online_booking_ux;
$roadbook = new online_booking_roadbook();
$obp                   = new Online_Booking_Public( 'ob', 1 );
$obwc                  = new onlineBookingWoocommerce( 'ob', 1 );
$ob_budget = new online_booking_budget;
$online_booking_user   = new online_booking_user;

//UT GET VAR
global $post;
$post_id = $post->ID;
$current_user_id = get_current_user_id();
$rb_meta = $roadbook->get_roadbook_meta($post_id);



//TRIP INFORMATIONS

$trip_name = get_the_title();
$state       = $rb_meta['status'];
$booking_obj = $ob_budget->get_trip_informations('booking-object',$rb_meta['trip_id']);
$invoiceID   = $rb_meta['trip_id'];
$trip_uuid   = $rb_meta['trip_id'];

$editPen = ( $rb_meta['is_the_client'] ) ? '<i class="fa fa-pencil" onclick="loadTrip(trip' . $trip_uuid . ',true)"></i>' : '';

//ADD ITEMS TO THE CART IF IS THE CLIENT
//$obwc->wc_add_to_cart( $trip_uuid, $booking_obj, $state, true );



get_header();
?>


<?php
	if($rb_meta['is_the_client']){
		echo $ux->get_dahsboard_menu();
	}
?>
	<section id="primary" class="content-area archive-reservations tpl-public">
		<div id="main" class="site-main" role="main">
			<div id="account-wrapper" class="inner-content">
				<div class="pure-g">
					<div class="pure-u-1">
						<div id="content-b" class="site-content-invite">
	<?php while ( have_posts() ) : the_post(); ?>
							<div class="breadcrumb">
								<a href="<?php echo get_bloginfo('url'); ?>/mon-comte/mes-devis">Mes projets</a> <span>></span> <span><?php the_title(); ?></span>
							</div>
							<?php
							$output = '';




									if ( $rb_meta['is_the_client'] ) {
										$args2 = array(
											'trip_id'    => $trip_uuid
										);
										$output .= $roadbook->get_roadbook_js($args2);
									}

									$output .= '<div id="page-header" class="post-content">';
									$output .= '<div class="pure-g">';

									$output .= '<div class="pure-u-3-4">';
									$output .= '<h1>'.get_the_title(). $editPen . '</h1>';
									$output .= '</div>';

									$output .= '<div class="pure-u-1-4 devis-line">';
									if ( $rb_meta['is_the_client'] ) {
										$output .= 'Devis n°' . $invoiceID . '<br />';
										$output .= 'du ' . $rb_meta['created'];;
									}
									$output .= '</div>';

									$output .= '</div>';
									$output .= '</div>';

									//$output .= $ob_budget->the_trip( $trip_uuid, false, $state, true);
		$output .= '<div id="event-trip-planning" class="trip-public">';
		$output .= '<div class="trip-public-user">';

		$output .= '<h2>Déroulement de votre séjour</h2>';//#activity-budget-user
		$days_count = 0;
		if( have_rows('day') ){
			while( have_rows('day') ): the_row();
			$dayunit = $days_count + 1;
				$day = get_sub_field('daytime');


			$output .= '<div class="table-header brown-head">';
			$output .= '<div class="pure-g">';
			$output .= '<div class="pure-u-1-4">';
			$output .= '<i class="fa fa-calendar"></i> Journée ' . $dayunit . ' - ' . $day;
			$output .= '</div>';
			$output .= '<div class="pure-u-1-4"> Descriptif</div>';
			$output .= '<div class="pure-u-1-4">Infos pratiques</div>';
			$output .= '<div class="pure-u-1-4">Adresse</div>';
			$output .= '</div>';
			$output .= '</div>';

			$output .= '<div class="event-day day-content post-content">';




			/**
			 * loop through products
			 * destroy any previous woocommerce session
			 * create a new cart
			 * add each product to cart with quantities (based on number of participants)
			 */
			//  Check type
				if( have_rows('products') ){

				$i       = 0;
				$output .= '<div class ="etp-days" >';
					while( have_rows('products') ): the_row();
					//calculate
					//var_dump($value);
						$product_id = (get_sub_field('id')) ? get_sub_field('id') : 0;
						$time = get_sub_field('time');
						$uuid = intval(get_sub_field('uuid'));
						$productPrice = intval(get_sub_field('price'));
						$type_icon = $ux->get_reservation_type($id,true);

					$productName  = get_the_title($product_id);

					//woocommerce calculate price
					//$obwc->wc_items_to_cart($product_id,$number_participants,0,array(),array());
					//do_action( 'wc_items_to_cart', $product_id,$number_participants,0,array(),array());
					//global $woocommerce;
					//WC()->cart->add_to_cart($product_id, $number_participants);

					$content_post = get_post($product_id);
					$content_ex = get_the_excerpt();
					$content = (empty($content_ex)) ? substr($content_post->post_content, 0, 250) : $content_ex;
					$geo = get_field('gps',$product_id);
					$geo_adress = (isset($geo['address'])) ? $geo['address'] : '';


					//html - display each product
					$output .= '<div data-id="' . $product_id . '" class="pure-u-1 single-activity-row">';
					$output .= '<div class="pure-u-1 pure-u-md-1-4">';
					$output .= get_the_post_thumbnail( $product_id, array( 180, 120 ) , array( 'class' => 'img-rounded' ));
					$output .= '<h3><a href="' . get_permalink( $product_id ) . '" target="_blank">';
					$output .= $productName . '</a></h3>';
					//$output .= $ux->get_reservation_type( $product_id );
					$output .= '</div>';

					$output .= '<div class="pure-u-1 pure-u-md-1-4 sejour-type">';
					$output .= get_the_excerpt();
					$output .= $content;
					$output .= '</div>';

					$output .= '<div class="pure-u-1 pure-u-md-1-4 ">';

					if($rb_meta['is_the_client']){
						//$output .= do_shortcode( '[add_to_cart id=' . $product_id . ']' );
					}
					$output .= get_field('infos_pratiques',$product_id);

					$output .= '</div>';

					$output .= '<div class="pure-u-1 pure-u-md-1-4 sejour-type">';
					$output .= $geo_adress;
					$output .= '</div>';

					$output .= '</div>';
					$i ++;
						endwhile;
				}

				$output .= '</div>';

				//$output .= '<h2>Localisation activités :</h2>';
				//$output .= '<div class="acf-map" id="google-map"></div>';

				$days_count ++;
				endwhile;
			}
			$output .= '</div>';


		/*
		 * Budget display
		 * User is logged In
		 * Estimate or invoice Step
		 */
		if ( $rb_meta['is_the_client'] && $state < 2 ) {

			$output .= '<div class="event-day">';
			$output .= '<div class="pure-g">';

			$output .= '<div class="pure-u-1-2">';
			$output .= 'Nos prix sont calculés sur la base de nombre de participants indiqués dans votre devis. Le prix et la disponibilité de la prestation sont garantis le jour de l\'émission du devis et sont suceptibles d\'être réajustés lors de votre validation.';
			$output .= '</div>';

			$output .= '<div class="pure-u-1-2" style="text-align:right;">';
			$output .= 'Total budget HT : ' . $rb_meta['budgetpermin'] . '€<br />';
			$output .= 'Total budget TTC : ' . $rb_meta['budgetpermin'] . '€<br />';
			$output .= '</div>';

			$output .= '</div>';//pure-g

			//estimate step
			if ( $state == 0 ) {


				$output .= '<div class="pure-g" id="userTrips">';

				$output .= '<div class="pure-u-1-2">';
				$output .= '<div class="btn btn-border" onclick="loadTrip(trip,true)"><i class="fs1 fa fa-pencil" aria-hidden="true"></i>' . __( 'Modifier votre séjour', 'online-booking' );
				$output .= '</div></div>';

				$output .= '<div class="pure-u-1-2">';
				$output .= '<div class="btn-orange btn quote-it js-quote-user-trip" onclick="estimateUserTrip('. $trip_uuid . ')"><i class="fa fa-check"></i>Valider mon devis</div>';
				$output .= '</div>';

				$output .= '</div>';
			}
			$output .= '</div>';
			$output .= '</div>';//event-day

			//#budget
		}
		$output .= '</div>';



							 echo $output;
							?>
	<?php endwhile; ?>

							<?php
							if (  $rb_meta['is_the_client'] ) {
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