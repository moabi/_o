<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 06/09/16
 * Time: 20:40
 */
class online_booking_utils{

	/**
	 * @param $location
	 *
	 * @return string
	 */
	public function get_circle_gmap($location){

		$lat = (isset($location['lat'])) ? $location['lat'] : false;
		$lng = (isset($location['lng'])) ? $location['lng'] : false;
		if($lat){
			$map = '<div id="map" data-lat="'.$lat.'" data-lng="'.$lng.'" class="single-map" style="width: 100%;display: 
		block;
		min-height: 
		350px;
		margin: 
		1em 0"></div>';
			$map .= '<script async defer
        src="https://maps.googleapis.com/maps/api/js?key='.GMAP_APIKEY.'&signed_in=true&callback=initSingleMap"></script>';
		} else {
			$map = false;
		}


		return $map;
	}

	/**
	 * add_action( 'wp_enqueue_scripts', 'single_product_enqueue_script' );
	 */
	public function single_product_enqueue_script() {
		wp_enqueue_script( 'gmap-single', get_wp_attachment_filter_plugin_uri().'public/js/gmap-single.js', false );
	}
}