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
global $wp_query;

$class_ux = new online_booking_ux;
$is_vendor = ( current_user_can('vendor') || current_user_can('administrator') || current_user_can('project_manager'));
$is_client = ( current_user_can('customer') || current_user_can('administrator'));
$current_page_id = get_the_ID();
$page_uri = get_page_uri( $current_page_id );
$query_vars = $wp_query->query;
//var_dump($query_vars);
$width_page = (is_user_logged_in() && ($is_vendor || $is_client)) ? 'pure-u-1 pure-u-md-18-24' : 'pure-u-1';

$my_account_pages = array(
	'mon-compte','mon-compte/edit-account','dashboard/settings','dashboard/documents-legaux','mon-compte/mon-entreprise','mon-compte/historique-des-paiements','mon-compte/notifications'
);

if(in_array($page_uri,$my_account_pages)){
	$sidebar_type = 'vendor-profile';
} else {
	$sidebar_type = $is_vendor ? 'vendor-account' : 'account';
}


$no_sidebar = false;
$left_sidebar = false;
$bg = '';
$bg_inner = '';
if(isset($query_vars['object']) && $query_vars['object'] == 'order'){
	$width_page = 'pure-u-1';
	$no_sidebar = true;
} elseif( isset($query_vars['pagename']) && $query_vars['pagename'] == MESSENGER ){
	$width_page = 'pure-u-1';
	$no_sidebar = true;
}elseif(!isset($query_vars['object']) && is_user_logged_in()){
	$width_page = 'pure-u-1 pure-u-md-18-24';
	$no_sidebar = true;
	$left_sidebar = true;
	$bg = 'active-background';
}elseif(!isset($query_vars['object']) && !is_user_logged_in()){
	$width_page = 'pure-u-1 ';
	$no_sidebar = false;
	$bg_inner = '';
}

if(is_user_logged_in()){
	$wrapper_width_class = 'inner-content';
} else {
	$wrapper_width_class = 'full-width';
}

?>

<?php echo $class_ux->get_dahsboard_menu(); ?>
<div class="background-wrapper-dashboard <?php echo $bg; ?>">
<section id="primary" class="content-area archive-reservations tpl-dasboard-vendor.php <?php echo $bg_inner; ?> ">
	<main id="main" class="site-main" role="main">
		<div id="account-wrapper" class="<?php echo $wrapper_width_class; ?>">
			<div class="pure-g">
				<?php
				if(is_user_logged_in() && $left_sidebar == true){
					get_sidebar( 'vendor-profile' );
				}
				?>
				<div class="<?php echo $width_page; ?>">
					<?php //include 'tpl-dasboard-vendor-top.php'; ?>
					<div class="site-content-invite">
		<!-- NAVIGATION -->


			<?php
			if ( have_posts() && ($is_vendor || !is_user_logged_in() || $is_client) ) {
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
					echo 'Aucune information/non connecté comme manager';
				}

			}
			?>
			</div><!-- .site-content-invite -->
			</div><!-- .pure -->
			<?php
			if(is_user_logged_in() && !$no_sidebar){
				get_sidebar( $sidebar_type );
			}
			 ?>
			</div><!-- .pure-g -->
		</div><!-- #account-wrapper -->
	</main><!-- .site-main -->
</section><!-- .content-area -->
</div>
<?php get_footer(); ?>