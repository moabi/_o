<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 07/09/16
 * Time: 19:44
 */

class online_booking_wcvendors{


	function custom_menu_link( $pages ) {

/*
		//products
		$pages[ 'prestations' ] = array(
			'slug'			=> get_bloginfo('url').'/'.PARTNER_PRESTATIONS,
			'label'			=> __('Prestations', 'wcvendors-pro' ),
			'actions'		=> array()
		);

		//mon compte -> not necessary, use dasboard
		$pages[ 'my_account' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MY_ACCOUNT,
			'label'			=> __('Mon compte', 'wcvendors-pro' ),
			'actions'		=> array()
		);

		//edit-address
		$pages[ 'edit_adress' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MY_ACCOUNT.'/edit-address/',
			'label'			=> __('Mes adresses', 'wcvendors-pro' ),
			'actions'		=> array()
		);
*/
		//edit-account
		$pages[ 'edit_account' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MY_ACCOUNT.'/edit-account/',
			'label'			=> __('Détails du compte', 'wcvendors-pro' ),
			'actions'		=> array()
		);
		return $pages;
	}

	/**
	 * @param $redirect_to
	 * @param $user
	 *
	 * @return false|string
	 */
	public function login_redirect( $redirect_to, $user ) {
		$userId = get_current_user_id();
		// WCV Pro Dashboard
		if (class_exists('WCV_Vendors') && class_exists('WCVendors_Pro') && WCV_Vendors::is_vendor( $userId ) ) {
			$redirect_to = get_permalink(WCVendors_Pro::get_option( 'dashboard_page_id' ));
		}
		return $redirect_to;
	}


}