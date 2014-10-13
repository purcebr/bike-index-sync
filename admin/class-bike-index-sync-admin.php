<?php
/**
 * Bike Index WP Sync
 *
 * @package   Bike_Index_Sync_Admin
 * @author    Bryan Purcell <purcebr@gmail.com>
 * @license   GPL-2.0+
 * @link      http://bikeindex.org
 * @copyright 2014 Bryan Purcell or Company Name
 */

/**
 *
 * @package Bike_Index_Sync_Admin
 * @author  Bryan Purcell <purcebr@gmail.com>
 */
class Bike_Index_Sync_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

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

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_init', array( $this, 'bikeindex_sync_settings_init' ) );
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Bike_Index_Sync::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Bike_Index_Sync::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Bike Index Sync Settings', $this->plugin_slug ),
			__( 'Bike Index Sync Settings', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);
	}

	/**
	 *
	 * @since    1.0.0
	 */

	public function bikeindex_sync_settings_init()
	{
		register_setting( 'bike-index-sync-settings-group', 'bike-index-sync-settings', array( $this, 'bike_index_sync_settings_validate' ));
		add_settings_section('bike-index-sync-settings-section-one', 'API Connection Settings', array( $this, 'bike_index_sync_settings_text_general'), 'bike-index-sync-settings');

		add_settings_field('api_key', 'API Key', array( $this, 'bike_index_settings_api_key'), 'bike-index-sync-settings', 'bike-index-sync-settings-section-one');
		add_settings_field('api_secret', 'Organization ID', array( $this, 'bike_index_settings_org_key'), 'bike-index-sync-settings', 'bike-index-sync-settings-section-one');

		add_settings_field('zipcode', 'Location (Zipcode)', array( $this, 'bike_index_settings_zipcode'), 'bike-index-sync-settings', 'bike-index-sync-settings-section-one');
		add_settings_field('radius', 'Radius (Miles)', array( $this, 'bike_index_settings_radius'), 'bike-index-sync-settings', 'bike-index-sync-settings-section-one');

		add_settings_field('attribution_author', 'Bike Posts Attribution Author', array( $this, 'bike_index_settings_attribution_author'), 'bike-index-sync-settings', 'bike-index-sync-settings-section-one');

		add_settings_field('sync_records', 'Sync Records Per Interval (One Hour)', array( $this, 'bike_index_settings_sync_records'), 'bike-index-sync-settings', 'bike-index-sync-settings-section-one');
		
		// bdh reworded this
		add_settings_field('manual_update', 'Clear & set hourly update cron to YES?', array( $this, 'bike_index_settings_manual_update'), 'bike-index-sync-settings', 'bike-index-sync-settings-section-one');
		
		// bdh added NEW call to force immediate sync, non-cron
		add_settings_field('hard_update', 'Force sync-new-bikes?', array( $this, 'bike_index_settings_hard_update'), 'bike-index-sync-settings', 'bike-index-sync-settings-section-one');
		// bdh added NEW call do a check for bikes needing local updates
		add_settings_field('hard_checkforupdates', 'Force check-for-updates?', array( $this, 'bike_index_settings_hard_checkforupdates'), 'bike-index-sync-settings', 'bike-index-sync-settings-section-one');
		// bdh added NEW call do a check for bikes needing deleted from local blog
		add_settings_field('hard_checkfordeleted', 'Force check-for-deletes?', array( $this, 'bike_index_settings_hard_checkfordeletes'), 'bike-index-sync-settings', 'bike-index-sync-settings-section-one');

		
	}

	public function bike_index_sync_settings_text_general() {
		echo '<p>General settings for Bike Index Sync</p>';
		$queue_option = get_option('bikeindex_sync_queue');
		
		if(isset($queue_option) && $queue_option != false)
			$queue_number = sizeof($queue_option);
		else
			$queue_number = 0;

		echo '<p>Items in Queue: <strong>' . $queue_number . '</strong></p>';
		$last_updated_option = get_option('bikeindex_sync_last_updated');
		if(isset($last_updated_option) && $last_updated_option != false)
			$last_updated = date("m/d/y h:i:s", $last_updated_option);
		else
			$last_updated = "Never";

		echo '<p>Last Updated: <strong>' . $last_updated . ' UTC</strong></p>';
	}

	public function bike_index_sync_settings_text() {
		echo '<p>Sync Settings</p>';
	}

	public function bike_index_settings_api_key() {
		$options = get_option('bike-index-sync-settings');
		echo "<input id='api_key' name='bike-index-sync-settings[api_key]' size='40' type='text' value='{$options['api_key']}' />";
	}

	public function bike_index_settings_org_key() {
		$options = get_option('bike-index-sync-settings');
		echo "<input id='organization_id' name='bike-index-sync-settings[organization_id]' size='40' type='text' value='{$options['organization_id']}' />";
	}

	public function bike_index_settings_zipcode() {
		$options = get_option('bike-index-sync-settings');
		echo "<input id='zipcode' name='bike-index-sync-settings[zipcode]' size='10' type='text' value='{$options['zipcode']}' />";
	}

	public function bike_index_settings_radius() {
		$options = get_option('bike-index-sync-settings');
		echo "<input id='radius' name='bike-index-sync-settings[radius]' size='5' type='text' value='{$options['radius']}' />";
	}

	public function bike_index_settings_attribution_author() {
		$options = get_option('bike-index-sync-settings');
		if(isset($options['attribution_author']))
			$selected_user = $options['attribution_author'];
		else
			$selected_user = false;

		wp_dropdown_users(array('name' => 'bike-index-sync-settings[attribution_author]', 'selected' => $selected_user));

	}


	public function bike_index_settings_sync_records() {
		$options = get_option('bike-index-sync-settings');
		echo "<input id='sync_records' name='bike-index-sync-settings[sync_records]' size='40' type='text' value='{$options['sync_records']}' />";
	}

	public function bike_index_settings_manual_update() {
		$options = get_option('bike-index-sync-settings');
		echo '<input type="checkbox" name="bike-index-sync-settings[manual_update]" value="Yes"/>';
	}


	// bdh added this for hard instant sync,  non-cron
	public function bike_index_settings_hard_update() {
		$options = get_option('bike-index-sync-settings');
		echo '<input type="checkbox" name="bike-index-sync-settings[hard_update]" value="Yes"/>';
	}
	
	// bdh ladded to check for UPDATES
	public function bike_index_settings_hard_checkforupdates() {
		$options = get_option('bike-index-sync-settings');
		echo '<input type="checkbox" name="bike-index-sync-settings[hard_checkforupdates]" value="Yes"/>';
	}
	
	// bdh ladded to check for DELETES
	public function bike_index_settings_hard_checkfordeletes() {
		$options = get_option('bike-index-sync-settings');
		echo '<input type="checkbox" name="bike-index-sync-settings[hard_checkfordeletes]" value="Yes"/>';
	}

	/*
	* Validate bike-index connection credentials with the Service, display warning if not valid.
	*/

	public function bike_index_sync_settings_validate($input) {
		$this->api_key = $input['api_key'];
		$this->attribution_author = $input['attribution_author'];
		$this->organization_id = $input['organization_id'];
		$this->manual_update = '';

		if(isset($input['manual_update']) && $input['manual_update'] != "")
		{
			global $wpdb;
			update_option('bikeindex_sync_last_updated', false); //Load it manually
			update_option('bikeindex_sync_queue', ''); //Load it manually
			$sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = 'bikeindex_bike'";
			$results = $wpdb->get_results($sql);
			
			foreach($results as $bike) {
				$wpdb->query("DELETE FROM " . $wpdb->posts . " WHERE ID = '" . $bike->ID . "'");
				$wpdb->query("DELETE FROM " . $wpdb->postmeta . " WHERE post_id = '" . $bike->ID . "'");
			}
		}

		// Manual Check for updates
		if(isset($input['hard_update']) && $input['hard_update'] != "")
		{
			error_log("BIKEINDEX class-bike-index-sync-admin HARD UPDATE DETECTED". $input['hard_update']);
			$background = Bike_Index_Sync_Background::get_instance();
			$hard_run = $background->bikeindexsync_do_this_hourly();
		}

		// Hard Check for deletes
		if(isset($input['hard_checkfordeletes']) && $input['hard_checkfordeletes'] != "")
		{
			error_log("BIKEINDEX class-bike-index-sync-admin hard_checkfordeletes DETECTED". $input['hard_checkfordeletes']);
			$background = Bike_Index_Sync_Background::get_instance();
			$hard_run = $background->bikeindex_check_for_deletes();
		}

		return $input;
	}


}
