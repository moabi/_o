<?php get_header();
$onlineBookingPartner = new online_booking_partners('','');
?>

<div class="pure-g inner-content ob-account-nav">
	<div class="pure-u-1">
		<?php
		do_action( 'woocommerce_account_navigation' );
		?>
	</div>
</div>

	<div id="primary-invite" class="content-area tpl-mes-prestations inner-content">
		<div class="pure-g">
		<div class="pure-u-1 pure-u-md-18-24">
			<div id="content-b" class="site-content-invite">
			<?php
			while ( have_posts() ) : the_post();
				echo '<h2 class="page-title">'.get_the_title().'</h2>';
				the_content();
			endwhile;
			?>

			<?php
			echo $onlineBookingPartner->get_partner_activites();
			?>

		</div><!-- #content -->
		</div>
		<?php get_sidebar('account'); ?>

	</div>
	</div>

<?php get_footer(); ?>