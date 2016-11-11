<?php

class User_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'user_widget',
			'description' => 'display avatart,name and links',
		);
		parent::__construct( 'my_widget', 'My Widget', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		global $current_user;
		wp_get_current_user();
		$class_ux = new online_booking_ux();
		$logoutUrl = get_bloginfo('url').'/coming-soon';
		$login_url = get_bloginfo('url').'/'.MY_ACCOUNT;
		$is_vendor = ( current_user_can('pending_vendor') || current_user_can('vendor') ) ;
		$access_account_url = ($is_vendor) ? get_bloginfo('url') . '/'.MY_ACCOUNT_PARTNER : get_bloginfo('url') . '/'
		                                                                                    .MY_ACCOUNT;
		$output = '<div class="widget user-infos"><div id="user-account-widget">';

		$userName = (isset($current_user->user_firstname) && !empty($current_user->user_firstname)) ? $current_user->user_firstname.' '.$current_user->user_lastname : $current_user->user_login;

		$output .= '<span class="wp-user-avatar">';
		$output .= $class_ux->get_custom_avatar($current_user->ID,120);
		$output .= '</span>';
		$output .=  '<span>'.$userName. '</span><br />';
		$output .= '<a class="log-out-sidebar clearfix" href="' . wp_logout_url($logoutUrl) . '">'._("Deconnexion")
		           .'</a>';
		$output .= '</div></div>';

		return $output;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
}