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
if(is_page(VENDOR_ADD_PACKAGE)){
	acf_form_head();
}
get_header();
global $wp_query;

$class_ux = new online_booking_ux;
$is_vendor = ( current_user_can('vendor') || current_user_can('administrator') || current_user_can('project_manager'));
$is_client = ( current_user_can('customer') || current_user_can('administrator'));
$current_page_id = get_the_ID();
$page_uri = get_page_uri( $current_page_id );
$query_vars = $wp_query->query;
$query_obj = (isset($query_vars['object'])) ? $query_vars['object'] : false;
$query_action = (isset($query_vars['action'])) ? $query_vars['action'] : false;
$query_page_name = (isset($query_vars['pagename'])) ? $query_vars['pagename'] : false;
$query_fep = (isset($_GET['fepaction'])) ? $_GET['fepaction'] : '';
$width_page = (is_user_logged_in() && ($is_vendor || $is_client)) ? 'pure-u-1 pure-u-md-18-24' : 'pure-u-1';

$my_account_pages = array(
	'mon-compte/edit-account','dashboard/settings','dashboard/documents-legaux','mon-compte/mon-entreprise','mon-compte/historique-des-paiements','mon-compte/notifications','mon-compte/supprimer-mon-compte'
);

//var_dump($page_uri);
//'mon-compte/ajouter-un-programme'
if(in_array($page_uri,$my_account_pages) || isset($query_vars['edit-account']) ){
	$sidebar_type = 'vendor-profile';
} else {
	$sidebar_type = 'vendor-account';
}


$no_sidebar = false;
$left_sidebar = false;
$bg = '';
$bg_inner = '';
if($query_obj == 'order'){
	$width_page = 'pure-u-1';
	$no_sidebar = true;
} elseif($query_page_name == MESSENGER && ($query_fep == 'viewmessage' || $query_fep == 'newmessage')){
	//VIEW MESSAGE PAGE
	$width_page = 'pure-u-1 pure-u-md-18-24';
	$no_sidebar = true;
	$left_sidebar = true;
	$sidebar_type = 'vendor-messagerie';
} elseif($query_page_name == MESSENGER  ){
	//MESSAGE LISTING
	$width_page = 'pure-u-1';
	$no_sidebar = true;
} elseif(!$query_obj && $page_uri != VENDOR_ADD_PACKAGE && $page_uri != VENDOR_LIST_PACKAGE ){
	$width_page = 'pure-u-1 pure-u-md-18-24';
	$no_sidebar = true;
	$left_sidebar = true;
	$bg = 'active-background';
} elseif ($query_obj == 'settings'){
	$width_page = 'pure-u-1 pure-u-md-18-24';
	$no_sidebar = true;
	$left_sidebar = true;
	$sidebar_type = 'vendor-profile';
} elseif( ($query_obj == 'product' && $query_action == 'edit' ) || $page_uri == VENDOR_ADD_PACKAGE || $page_uri == VENDOR_LIST_PACKAGE){
	//PRODUCT EDIT PAGE
	$width_page = 'pure-u-1 pure-u-md-18-24';
	$no_sidebar = false;
	$left_sidebar = false;
	$sidebar_type = 'account';
} elseif($query_obj == 'product' && $query_page_name == 'dashboard' && !$query_action){
	//PRODUCT LISTING PAGE
	$width_page = 'pure-u-1 ';
	$no_sidebar = true;
	$left_sidebar = false;
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
					get_sidebar( $sidebar_type );
				}
				?>
				<div class="<?php echo $width_page; ?>">
				
					<div class="site-content-invite">
		<!-- NAVIGATION -->


			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					the_content();
				}
			} else {
				echo 'Aucune information/non connecté comme manager';

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