<?php
/**
 * Bike Index Widget
 *
 * A WP Widget for displaying nearby stolen bikes.
 *
 * @package   Bike_Index_Widget
 * @author    Bryan Purcell <purcebr@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Bryan Purcell
 *
 * @bike-index-widget
 * Plugin Name:       Bike Index Widget
 * Plugin URI:        http://bikeindex.org
 * Description:       A WP Widget for displaying nearby stolen bikes.
 * Version:           1.0.0
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

require_once( plugin_dir_path( __FILE__ ) . 'public/class-bike-index-widget.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Bike_Index_Widget', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Bike_Index_Widget', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Bike_Index_Widget', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * if ( is_admin() ) {
 *   ...
 * }
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-bike-index-widget-admin.php' );
	add_action( 'plugins_loaded', array( 'Bike_Index_Widget_Admin', 'get_instance' ) );

}
