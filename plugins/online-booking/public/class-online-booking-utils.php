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
		$loc = (isset($location['address'])) ? $location['address'] : 'undefined-n';
		if($lat){
			$map = '<div id="map" data-loc="'.$loc.'" data-lat="'.$lat.'" data-lng="'.$lng.'" class="single-map" 
			style="width: 
			100%;display: 
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


	public function the_save_btn(){
		$eventid = 0;
		$btn_Name = __('Enregistrer','onlyoo');
		$btn_attr = '';
		$href = '#';
		$btn_class = '';

		if(is_user_logged_in()){
			//event is known
			if(isset($_COOKIE['reservation']) ){
				$bookink_json = stripslashes( $_COOKIE['reservation'] );
				$data = json_decode($bookink_json, true);
				$eventid = (isset($data['eventid'])) ? $data['eventid'] : '0';

				$btn_Name = __('Enregistrer','onlyoo');
				$btn_attr = 'onclick="saveTrip('.$eventid.')"';
				$href = 'javascript:void(0)';


			} else{
				//event is unknown/not saved
				$btn_attr = 'onclick="saveTrip(0)"';
				$btn_Name = __('Enregistrer','onlyoo');
				$href = 'javascript:void(0)';
			}

		} elseif(!is_user_logged_in()) {
			$btn_Name = __('Se connecter <br />pour sauvegarder','onlyoo');
			$href = get_bloginfo('url').'/'.MY_ACCOUNT;
			$btn_class = 'two-lines';
		}


		$output = '<div id="savetrip" >';
		$output .= '<a id="ob-btn-re" href="'.$href.'" '.$btn_attr.' class="btn btn-reg '.$btn_class.'">';
		$output .= $btn_Name;
		$output .= '<i class="fa fa-floppy-o"></i></a></div>';

		echo $output;
	}
}