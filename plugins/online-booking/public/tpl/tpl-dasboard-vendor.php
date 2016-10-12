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
$is_vendor = ( current_user_can('vendor') || current_user_can('administrator') || current_user_can('project_manager'));
$width_page = (is_user_logged_in() && $is_vendor) ? 'pure-u-1 pure-u-md-18-24' : 'pure-u-1';
$sidebar_type = $is_vendor ? 'vendor-account' : 'account';
$class_ux = new online_booking_ux;
?>

<?php echo $class_ux->get_dahsboard_menu(); ?>
<section id="primary" class="content-area archive-reservations tpl-dasboard-vendor.php">
	<main id="main" class="site-main" role="main">
		<div id="account-wrapper" class="inner-content">
			<div class="pure-g">
				<div class="<?php echo $width_page; ?>">
					<?php include 'tpl-dasboard-vendor-top.php'; ?>
					<div class="site-content-invite">
		<!-- NAVIGATION -->
				<?php if(!is_user_logged_in()){ ?>
					<header class="page-header">
						<h1><?php _e('Mon compte', 'online-booking'); ?></h1>
					</header><!-- .page-header -->
			    <?php } ?>

			<?php
			if ( have_posts() && ($is_vendor || !is_user_logged_in()) ) {
				while ( have_posts() ) {
					the_post();
					the_content();
				} // end while
			} elseif ( current_user_can('project_manager')){
				include get_wp_attachment_filter_plugin_dir().'public/partials/dashboard-manager.php';
			} else {
				$pending_message = get_page_by_path(PM_DASHBOARD,OBJECT);
				if(isset($pending_message->post_content)){
					echo $pending_message->post_content;
				} else {
					echo 'Aucune information';
				}

			}
			?>
			</div><!-- .site-content-invite -->
			</div><!-- .pure -->
			<?php
			if($is_vendor && is_user_logged_in()){
				get_sidebar( $sidebar_type );
			}
			 ?>
			</div><!-- .pure-g -->
		</div><!-- #account-wrapper -->
	</main><!-- .site-main -->
</section><!-- .content-area -->

<?php get_footer(); ?>