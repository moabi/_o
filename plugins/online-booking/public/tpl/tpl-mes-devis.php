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

			<?php
			//add user booking at the state of
			// 1: paid, current
			// 2: paid, archived
			echo online_booking_user::get_user_booking(1);
			echo online_booking_user::get_user_booking(2);
			?>

		</div><!-- #content -->

		<?php get_sidebar('account'); ?>

	</div>

<?php get_footer(); ?>