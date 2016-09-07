<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 06/09/16
 * Time: 20:40
 */
class online_booking_utils{


	/**
	 * Redirect user after successful login.
	 *
	 * @param string $redirect_to URL to redirect to.
	 * @param string $request URL the user is coming from.
	 * @param object $user Logged user's data.
	 * @return string
	 */
	public function my_login_redirect( $redirect_to, $request, $user ) {
		//is there a user to check?
		$is_vendor = ( current_user_can('pending_vendor') || current_user_can('vendor') ) ;
		$vendor_roles = array('vendor','pending_vendor','partner','administrator');
		$dasboard_partners = get_bloginfo('url').MY_ACCOUNT_PARTNER;
		$dasboard_partners_2 = get_permalink(WCVendors_Pro::get_option( 'dashboard_page_id' ));

		if ( isset( $user->roles )  ) {
			//check for admins
			if(is_array( $user->roles )){
				if ( in_array( 'vendor', $user->roles ) || in_array( 'pending_vendor', $user->roles ) ) {
					// redirect them to the default place

					return $dasboard_partners_2;
				}
			} else {
				if(in_array($user->roles,$vendor_roles)){
					return $dasboard_partners_2;
				}
			}


		} else {
			return $redirect_to;
		}
	}
}