<?php
/**
 * Created by PhpStorm.
 * User: dfieffe
 * Date: 29/05/2015
 * Time: 13:43
 */
?>
<?php
global $wp_query;
$page_id = $wp_query->post->ID;
$user_id = get_current_user_id();
$class_ux = new online_booking_ux();
?>
<div class="pure-u-1 pure-u-md-6-24" id="sidebar-vendor-profile">
	<div id="secondary" class="sidebar sidebar-vendor vendor-profile clean-avatar">
		<?php
		echo $class_ux->get_avatar_form(141);
		?>

	</div>
	<?php if ( is_active_sidebar( 'sidebar-vendor-profile' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-vendor-profile' ); ?>
	<?php endif; ?>



</div>
