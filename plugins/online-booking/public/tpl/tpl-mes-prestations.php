<?php get_header(); ?>

<div class="pure-g inner-content ob-account-nav">
	<div class="pure-u-1">
		<?php
		do_action( 'woocommerce_account_navigation' );
		?>
	</div>
</div>

	<div id="primary-invite" class="content-area pure-g inner-content">
		<div id="content-b" class="site-content-invite pure-u-1 pure-u-md-2-3">

			<?php
			while ( have_posts() ) : the_post();
				echo '<h1 class="page-title">'.get_the_title().'</h1>';

				the_content();
			endwhile;
			?>

			<?php echo online_booking_user::get_user_booking(1); ?>
			<?php echo online_booking_user::get_user_booking(2); ?>

		</div><!-- #content -->

		<div id="secondary" class="sidebar pure-u-1 pure-u-md-1-3">

			<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
				<div id="text-2" class="widget widget_text">			<div class="textwidget">Des questions ?
						0811 202 101</div>
				</div><div id="text-3" class="widget widget_text">			<div class="textwidget">Du Lundi au Vendredi
						De 9h00 à 18h00</div>
				</div>    </div><!-- #primary-sidebar -->
		</div></div>

<?php get_footer(); ?>