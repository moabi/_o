<?php get_header();
$ob_user = new online_booking_user();
$width_page = 'pure-u-1';
$sidebar_type = ( current_user_can('vendor') || current_user_can('pending_vendor')) ? 'vendor' : 'account';

?>

<?php if( is_user_logged_in() ){ ?>
	<div class="ob-account-nav">
		<?php
		if( current_user_can('vendor') || current_user_can('pending_vendor') ) {
			echo do_shortcode('[wcv_pro_dashboard_nav]');
		} else {
			do_action( 'woocommerce_account_navigation' );
		}
		?>
	</div>
<?php } ?>

	<section id="primary" class="content-area archive-reservations tpl-mes-devis">
		<main id="main" class="site-main" role="main">
			<div id="account-wrapper" class="inner-content">
				<div class="pure-g">
					<div class="<?php echo $width_page; ?>">
						<div class="site-content-invite">
							<!-- NAVIGATION -->
							<?php if(!is_user_logged_in()){ ?>
								<header class="page-header">
									<h1><?php _e('Mon compte', 'online-booking'); ?></h1>
								</header><!-- .page-header -->
							<?php } ?>

							<?php
							if ( have_posts() ) {
								while ( have_posts() ) {
									the_post();
									the_content();
								} // end while
							} // end if
							wp_reset_postdata();
							?>
							<?php
							//add user booking at the state of
							// 1: paid, current
							// 2: paid, archived
							echo '<div class="wcvendors-pro-dashboard-wrapper">';
							echo '<h2><i class="fa fa-clock-o" aria-hidden="true"></i>'.__('Mes réservations en cours','online-booking').'</h2>';
							echo $ob_user->get_user_booking(0);
							echo '</div>';

							echo '<div class="wcvendors-pro-dashboard-wrapper">';
							echo '<h2><i class="fa fa-flag-checkered" aria-hidden="true"></i>'.__("Mes réservations en cours de validation","online-booking").'</h2>';
							echo $ob_user->get_user_booking(1);
							echo '</div>';

							echo '<div class="wcvendors-pro-dashboard-wrapper">';
							echo '<h2><i class="fa fa-archive" aria-hidden="true"></i>'.__("Mes réservations archivés","online-booking").'</h2>';
							echo $ob_user->get_user_booking(2);
							echo '</div>';
							?>

						</div><!-- .site-content-invite -->
					</div><!-- .pure -->

				</div><!-- .pure-g -->
			</div><!-- #account-wrapper -->
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>

