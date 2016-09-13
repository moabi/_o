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


$width_page = (is_user_logged_in()) ? 'pure-u-1 pure-u-md-18-24' : 'pure-u-1';
$is_vendor = ( current_user_can('vendor') || current_user_can('pending_vendor'));
$sidebar_type = $is_vendor ? 'vendor-account' : 'account';


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
			?>
			</div><!-- .site-content-invite -->
			</div><!-- .pure -->
			<?php
			if(is_user_logged_in()){
				get_sidebar( $sidebar_type );
			}
			?>
			</div><!-- .pure-g -->
		</div><!-- #account-wrapper -->
	</main><!-- .site-main -->
</section><!-- .content-area -->

<?php get_footer(); ?>