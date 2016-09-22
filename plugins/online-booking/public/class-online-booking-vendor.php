<?php


/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://little-dream.fr
 * @since      1.0.0
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/public
 * @author     little-dream.fr <david@loading-data.com>
 */

class online_booking_vendor {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;


	/**
	 * get_vendors_from_booking
	 * extract vendors ID from a bookink object
	 *
	 * @param $booking
	 *
	 * @return string vendors ID comma separated
	 */
	public  function get_vendors_from_booking($booking = ''){
		$booking_obj = json_decode($booking);
		$booking_dates = $booking_obj->tripObject;
		$vendors = array();
		foreach ($booking_dates as $booking_date){
			foreach ($booking_date as $key => $booking_trip){
				$post_tmp = get_post($key);
				$vendors[] = $post_tmp->post_author;
			}
		}

		$vendors_ids = rtrim(implode(',', $vendors), ',');

		return $vendors_ids;
	}
	/**
	 * get_user_booking
	 *
	 * @param $validation integer
	 * 0 :
	 * 1 :
	 * 2 :
	 *
	 * @return string
	 */
	public function get_vendor_booking($validation){
		global $wpdb;
		$obp = new Online_Booking_Public('ob',1);

		$user_id = get_current_user_id();
		//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID
		$sql = $wpdb->prepare(" 
						SELECT *
						FROM ".$wpdb->prefix."online_booking a	
						WHERE a.validation = %d
						ORDER BY a.ID DESC
						",$validation);

		$results = $wpdb->get_results($sql);

		$output = '<table id="vendor-bookings" class="bk-listing pure-table">';
		$output .='<thead><tr>';
		$output .= '<td>Réservations en cours</td> <td>Jour</td>';
		$output .= '<td>Interlocuteur</td>';
		$output .= '<td>Total</td>';
		$output .= '<td>Actions</td>';
		$output .= '</tr></thead>';

		$output .= '<tbody>';
		//loop through trips to find vendors activities sold
		foreach ( $results as $result ) {

			if(is_string($result->vendors)){
				$result_arr = explode(',',$result->vendors);
				if(in_array($user_id,$result_arr)){
					$booking_obj = json_decode($result->booking_object);
					//var_dump($booking_obj);
					$user_name = $result->user_ID;
					$booking_name = (isset($booking_obj->sejour) && !empty($booking_obj->sejour)) ? $booking_obj->sejour : 'Séjour du client';
					$output .= '<tr>';
					$output .= '<td>';
					$output .= '<span class="ttrip-title">'.$booking_name.'</span>';
					$output .= '<span class="ttrip-participants"><i class="fa fa-user"></i>'.$booking_obj->participants.' participants</span>';
					$output .= '</td>';

					$output .= '<td>';
					$output .= '<span class="ttrip-date">'.$booking_obj->arrival;
					$output .= '- '.$booking_obj->departure.'</span>';
					$output .= '</td>';

					$output .= '<td>';
					if(get_avatar($user_name)){
						$output .= '<span class="ttrip-avatar">';
						$output .= get_avatar($user_name,48);
						$output .= '</span>';
					}

					$output .= '<span class="ttrip-client">';
					if(get_the_author_meta('first_name',$user_name)){
						$output .= get_the_author_meta('first_name',$user_name);
						$output .= ' '.get_the_author_meta('last_name',$user_name);
					} else {
						$output .= get_the_author_meta('nicename',$user_name);
					}
					$output .= '</span>';
					$output .= '</td>';

					$output .= '<td>';
					$output .= '<span class="ttrip-price">';
					$output .= '0';
					$output .= '</span>';
					$output .= '</td>';

					$output .= '<td>';
					$output .= '<a class="btn btn-reg ttrip-btn" href="">Valider cette réservation</a>';
					$output .= '</td>';

					$output .= '</tr>';
				}
			}

		}
		$output .= '</tbody>';
		$output .= '</table>';

		return $output;
	}


}