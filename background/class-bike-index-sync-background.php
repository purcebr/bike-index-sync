<?php
/**
 * Plugin Name.
 *
 * @package   Bike_Index_Sync_Admin
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

/**
 *
 * @package Bike_Index_Sync_Admin
 * @author  Your Name <email@example.com>
 */
class Bike_Index_Sync_Background {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 */
		$plugin = Bike_Index_Sync::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( 'wp', array($this, 'sync_setup_schedule' ));
		add_action( 'prefix_hourly_event', array($this, 'prefix_do_this_hourly' ));
	}


/**
 * On an early action hook, check if the hook is scheduled - if not, schedule it.
 */
function sync_setup_schedule() {
	if ( ! wp_next_scheduled('prefix_hourly_event' ) ) {
		wp_schedule_event( time(), 'hourly', 'prefix_hourly_event');
	}
}


/**
 * On the scheduled action hook, run a function.
 */
function prefix_do_this_hourly() {
	// do something every hour
	$last_synced = get_option("bikeindex_last_synced");
	$options = get_option("bike-index-sync-settings");
	// $data = array("last_updated" => $last_synced, "organization_id" => $options['org_id'], "api_key" => $options['api_key']);
	// 		$action = 'was_updated';
	// 		$req = $this->api->post_json($data, $action);

}



	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function activate_schedule() {
	}


	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

}
