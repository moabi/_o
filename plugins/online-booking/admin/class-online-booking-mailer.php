<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://little-dream.fr
 * @since      1.0.0
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/admin
 * @author     little-dream.fr <david@loading-data.com>
 */
class Online_Booking_Mailer {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {


	}
	
	
	/*
	 * send_mail
	 *
	 * @param $type
	 * @param $to
	 * @param $body
	 */
	public function send_mail($type,$to,$body){
		

		$subject = 'Onlyoo - '.$type.' - '. date("d/m/Y");
		$headers[] = 'Content-Type: text/html';
		$headers[] = 'charset=UTF-8';
		$headers[] = 'From: Onlyoo <no-reply@onlyoo.com>' . "\r\n";
		
		wp_mail( $to, $subject, $body, $headers );

	}

	/**
	 * confirmation_mail
	 *
	 *
	 * @param $userID
	 */
	public function confirmation_mail($userID){
		
		$user_info = get_userdata($userID);
		$username = $user_info->user_login;
		
		$type = 'confirmation';
		$admin_email = esc_attr( get_option('ob_admin_email','david@loading-data.com') );
		
		$body = esc_attr( get_option('ob_confirmation_content','Missing Content, please get back to Onlyoo website administrator') );
		$body = str_replace('[client-name]', $username, $body);
		
		self::send_mail($type,$admin_email,$body);
		
	}


}