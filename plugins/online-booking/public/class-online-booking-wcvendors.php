<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 07/09/16
 * Time: 19:44
 */

class online_booking_wcvendors{


	function custom_menu_link( $pages ) {

		//mon compte
		$pages[ 'my_account' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MY_ACCOUNT,
			'label'			=> __('Mon compte', 'wcvendors-pro' ),
			'actions'		=> array()
		);
/*
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

	function login_redirect( $redirect_to, $user ) {


		// WCV Pro Dashboard
		if (class_exists('WCV_Vendors') && class_exists('WCVendors_Pro') && WCV_Vendors::is_vendor( $user->id ) ) {
			$redirect_to = get_permalink(WCVendors_Pro::get_option( 'dashboard_page_id' ));
		}
		return $redirect_to;
	}


}