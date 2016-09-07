<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 07/09/16
 * Time: 19:44
 */

class online_booking_wcvendors{


	function custom_menu_link( $pages ) {

		$pages[ 'custom_link' ] = array(
			'slug'			=> get_bloginfo('url').'/'.MY_ACCOUNT,
			'label'			=> __('Mon compte', 'wcvendors-pro' ),
			'actions'		=> array()
		);

		return $pages;
	}
}