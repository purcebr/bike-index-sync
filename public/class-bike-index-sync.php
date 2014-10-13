<?php
/**
 * Bike Index Sync
 *
 * @package   Bike_Index_Sync
 * @author    Bryan Purcell <purcebr@gmail.com>
 * @license   GPL-2.0+
 * @link      http://bikeindex.org
 * @copyright 2014 Bryan Purcell or Company Name
 */

/**
 * @package Bike_Index_Sync
 * @author  Bryan Purcell <purcebr@gmail.com>
 */

class Bike_Index_Sync {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.2.0';

	protected $plugin_slug = 'bike-index-sync';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'the_content', array( $this, 'single_bike_content' ) );
		add_shortcode( 'bike_table', array( $this, 'show_bike_table'));
		add_shortcode( 'bike_submit_form', array( $this, 'show_bike_submit_form'));		
		add_action( 'init', array( $this, 'register_types' ) );
	}

	public function cron_test() {
		
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	public function register_types( ) {

		register_post_type('bikeindex_bike', array(
		  'labels' => array(
		    'name' => 'Bikes',
		    'singular_name' => 'Bike',
		    'add_new_item' => 'Add New Bike',
		    'edit_item' => 'Edit Bike',
		    'new_item' => 'New Bike',
		    'view_item' => 'View Bike',
		    'search_items' => 'Search Bikes',
		    'not_found' => 'No Bikes found',
		    'not_found_in_trash' => 'No Bikes found in Trash',
		    'view' => 'Bike'
		  ),
		  'has_archive' => false, 
		  'supports' => array( 'title','editor','custom-fields' ),
		  'exclude_from_search' => false,
		  'public' => true,
		));
		register_taxonomy_for_object_type( 'category', 'beer' );

	}
	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {

		$background = Bike_Index_Sync_Background::get_instance();
		$background->activate_schedule();
		self::set_up_option_defaults();
	}

	/**
	 * Set up the Bike Index Sync default options.
	 *
	 * @since    1.0.0
	 *
	 */
	private static function set_up_option_defaults() {

		$sync_settings_defaults = array(
			"api_key" => "",
			"organization_id" => "",
			"zipcode" => "",
			"radius" => "25",
			"attribution_author" => 1,
			"sync_records" => "50",
		);

		add_option('bike-index-sync-settings', $sync_settings_defaults);

	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing DataTables style sheet.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-datatables-styles', plugins_url( 'assets/datatables/css/jquery.dataTables.min.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );

	}

	/**
	 * Register and enqueues public-facing DataTables JavaScript files.
	 *
	 * @since    1.1.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script( $this->plugin_slug . '-datatables-script', plugins_url( 'assets/datatables/js/jquery.dataTables.min.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		wp_enqueue_script( $this->plugin_slug . '-public-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery', $this->plugin_slug . '-datatables-script' ), self::VERSION );
	}

	public function single_bike_content($content) {
		global $post;
		if ($post->post_type == 'bikeindex_bike' && is_single()) {
			ob_start();
			include('views/single-bike.php');
			$bike_content = ob_get_contents();
			ob_end_clean();
			return $bike_content;
		}
  		// otherwise returns the database content
  		return $content;
	}
	public function show_bike_table() {
		ob_start();
		include('views/archive-table.php');
		$bike_table_content = ob_get_contents();
		ob_end_clean();
		return $bike_table_content;
	}
	public function bikeindex_formatted_date($datetime) {
		if(isset($datetime)) {
			$date_data = explode("T", $datetime);
			$date = new DateTime($date_data[0]);
			return $date->format("Y.m.d");
		}
		return "";
	}

	public function show_bike_submit_form() {
		ob_start();
		include('views/bike_submit_form.php');
		$bike_form_content = ob_get_contents();
		ob_end_clean();
		return $bike_form_content;
	}
}