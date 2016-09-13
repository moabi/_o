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
?>
<div class="pure-u-1 pure-u-md-6-24">
<div id="secondary" class="sidebar sidebar-vendor sidebar-account">
	<div class="widget user-infos">
		<?php
		/**
		 * ADD IN WIDGET
		 */
		global $current_user;
		wp_get_current_user();
		$logoutUrl = get_bloginfo('url').'/coming-soon';
		$login_url = get_bloginfo('url').'/'.MY_ACCOUNT;
		$is_vendor = ( current_user_can('pending_vendor') || current_user_can('vendor') ) ;
		$access_account_url = ($is_vendor) ? get_bloginfo('url') . '/'.MY_ACCOUNT_PARTNER : get_bloginfo('url') . '/'
		                                                                                    .MY_ACCOUNT;
		$output = '<div id="user-account-widget">';
		//__('Mon compte', 'online-booking')
		//' . __('Déconnexion', 'online-booking') . '
		$userName = (isset($current_user->user_firstname) && !empty($current_user->user_firstname)) ? $current_user->user_firstname.' '.$current_user->user_lastname : $current_user->user_login;

		if(get_avatar( $current_user->ID, 52 )){
			$output .= '<span class="wp-user-avatar">'.get_avatar( $current_user->ID, 120 ).'</span>';
		}
		$output .=  '<span>'.$userName. '</span><br />';
		$output .= '<a class="log-out-sidebar clearfix" href="' . wp_logout_url($logoutUrl) . '">'._("Deconnexion")
		           .'</a>';
		$output .= '</div>';
		echo $output
		?>
	</div>
  <?php if ( is_active_sidebar( 'sidebar-vendor-account' ) ) : ?>
      <?php dynamic_sidebar( 'sidebar-vendor-account' ); ?>
  <?php endif; ?>
</div>
</div>