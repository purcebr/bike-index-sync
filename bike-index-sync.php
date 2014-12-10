<?php
/**
 * Bike Index Sync
 *
 * A WP Widget for displaying nearby stolen bikes.
 *
 * @package   Bike_Index_Sync
 * @author    Bryan Purcell <purcebr@gmail.com>
 * @license   GPL-2.0+
 * @link      http://bikeindex.org
 * @copyright 2014 Bryan Purcell
 *
 * @bike-index-widget
 * Plugin Name:       Bike Index Sync
 * Plugin URI:        http://bikeindex.org
 * Description:       A WP Plugin for displaying nearby stolen bikes.
 * Version:           1.0.1
 * Author:            Bryan Purcell
 * Author URI:        http://github.com/purcebr
 * Text Domain:       bike-index-widget-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: http://github.com/purcebr
 * bike-index-widget: v1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
require_once( plugin_dir_path( __FILE__ ) . 'includes/api.class.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-bike-index-sync.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Bike_Index_Sync', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Bike_Index_Sync', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Bike_Index_Sync', 'get_instance' ) );

add_filter('http_request_timeout','bikindex_sync_callback');
/**
 * @param integer $time
 * @return integer
*/
function bikindex_sync_callback($time){
    return 25; // Rewrite the timeout to 25 seconds.
}

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-bike-index-sync-admin.php' );
	add_action( 'plugins_loaded', array( 'Bike_Index_Sync_Admin', 'get_instance' ) );
}

if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
	require_once( plugin_dir_path( __FILE__ ) . 'background/class-bike-index-sync-background.php' );
	add_action( 'plugins_loaded', array( 'Bike_Index_Sync_Background', 'get_instance' ) );
}

function bikeindex_convert_date_to_timestamp($datetime) {
	if(isset($date)) {
		$date_data = explode("T", $date);
		$timestamp = new DateTime($date_date[0]);
		return $timestamp;
	}
	return false;
}
