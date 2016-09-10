<?php
/**
 * Template Name: compte
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

get_header();

if(is_user_logged_in()){
	$width_page = 'pure-u-1 pure-u-md-18-24';
} else {
	$width_page = 'pure-u-1';
}
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

	<section id="primary" class="content-area archive-reservations tpl-dasboard-vendor.php">
		<main id="main" class="site-main" role="main">

<div id="account-wrapper" class="inner-content">
	<div class="pure-g">
		<div class="<?php echo $width_page; ?>">
<div class="site-content-invite">
<!-- NAVIGATION -->
	<?php
if(!is_user_logged_in()){ ?>
	<header class="page-header">
		<h2><?php _e('Mon compte', 'online-booking'); ?></h2>
	</header><!-- .page-header -->
 <?php } ?>


	<?php
	/*
	if(is_page(MY_ACCOUNT) && is_user_logged_in()){
		echo '<div class="wcvendors-pro-dashboard-wrapper">';
		echo '<div class="wcv-grid">';
		if( current_user_can('vendor') || current_user_can('pending_vendor') ) {
			//echo do_shortcode('[wcv_pro_dashboard_nav]');
		} else {
			//do_action( 'woocommerce_account_navigation' );
			//do_action( 'woocommerce_account_content' );
		}

		echo '</div></div>';
	}*/
	?>
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		} // end while
	} // end if
	?>
</div>
	</div>
		<?php
		if(is_user_logged_in()){
			get_sidebar( $sidebar_type );
		}
		?>
	</div>
	
</div><!-- #account-wrapper -->


		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>