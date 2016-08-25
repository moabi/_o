<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://little-dream.fr
 * @since             1.0.0
 * @package           Online_Booking
 *
 * @wordpress-plugin
 * Plugin Name:       Online Booking
 * Plugin URI:        http://little-dream.fr/
 * Description:        Online Booking plugin
 * Version:           2.0.2
 * Author:            little-dream.fr
 * Author URI:        http://little-dream.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       online-booking
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


function get_wp_attachment_filter_plugin_dir(){
	$url_plugins =  plugin_dir_path( __FILE__ );
	return $url_plugins;
}
function get_wp_attachment_filter_plugin_uri(){
	$url_plugins = plugin_dir_url(__FILE__);
	return $url_plugins;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-online-booking-activator.php
 */
function activate_online_booking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-online-booking-activator.php';
	Online_Booking_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-online-booking-deactivator.php
 */
function deactivate_online_booking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-online-booking-deactivator.php';
	Online_Booking_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_online_booking' );
register_deactivation_hook( __FILE__, 'deactivate_online_booking' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-online-booking.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_online_booking() {

	$plugin = new Online_Booking();
	$plugin->run();

}
run_online_booking();
