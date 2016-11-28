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
		<div class="avatar-change">
			<?php
			echo $class_ux->get_custom_avatar($user_id,92);
			?>
			<a href="#set-avatar" class="js-change-avatar camera open-popup-link">
				<i class="fa fa-camera" aria-hidden="true"></i>
			</a>

			<div id="set-avatar" class="white-popup mfp-hide">
				<?php
				$avatar_form = esc_attr( get_option('ob_avatar_shortcode') );
				echo do_shortcode($avatar_form); ?>
			</div>
		</div>

	</div>
	<?php if ( is_active_sidebar( 'sidebar-vendor-profile' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-vendor-profile' ); ?>
	<?php endif; ?>



</div>
