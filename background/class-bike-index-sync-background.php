<?php
/**
 * Plugin Name.
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

		add_action( 'bikeindexsync_hourly_event', array($this, 'bikeindexsync_do_this_hourly' ));
		$this->api = new BikeIndexSyncAPI();
	}


	/**
	 * On an early action hook, check if the hook is scheduled - if not, schedule it.
	 */
	function activate_schedule() {
		if ( ! wp_next_scheduled('bikeindexsync_hourly_event' ) ) {
			wp_schedule_event( time(), 'hourly', 'bikeindexsync_hourly_event');
		}
	}

	/*
	* 
	* bikeindexsync_check_for_deletes()
	*
	* Routine to look for bikes in the local blog that need to be  
	* deleted because they are no longer listed in the BikeIndex API call.
	* Maybe they got found - or deleted - or the API scope call radius changed, 
	* but we want to sync DELETES from BI out to all the local blogs. 
	* 
	* Create two arrays - Bike OIDs here locally, bikes from the API
	* Diff them. Any appearing locally but not in the API  need deleted. 
	*
	*/
	function bikeindex_check_for_deletes() {
		
		$bikes_from_api=array();
		$bikes_in_this_blog=array();
		
		// Get all the bike's BikeIndex oids from the API
		$bikes = $this->get_bikes_from_endpoint(true); //force
		foreach($bikes as $api_pulled_bike_id) {
			//error_log("bikeindex: bikeindexsync_check_for_deletes -- API handed me: " . $api_pulled_bike_id);
			$bikes_from_api[]=$api_pulled_bike_id;
		}
		
		// Get all the post oids for these bike listings
		global $wpdb;
		$sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = 'bikeindex_bike'";
		//error_log("bikeindex: bikeindexsync_check_for_deletes -- running: " . $sql);
		$results = $wpdb->get_results($sql);
		
		// Iterate on these and remember $bikes_in_this_blog[]
		foreach($results as $bikepost) {
				$a_meta_id = $wpdb->get_var("select meta_value from " . $wpdb->postmeta . " WHERE post_id = '" . $bikepost->ID . "' and meta_key='bike_id'");
				//error_log("bikeindex: pulled a bike BI oid from local blog: " . $a_meta_id);
				$bikes_in_this_blog[] = $a_meta_id;
		}

		if(WP_DEBUG == true) {
			error_log("bikeindex: num bikes pulled from API -- : " . sizeof($bikes_from_api));		
			error_log("bikeindex: num bikes pulled from local records -- : " . sizeof($bikes_in_this_blog));			
		}			
		
		// Are there bikes listed in this blogs array of bikes but not in the API list? 
		// If there are, then delete them. 
			
		$difference_in_bikes = array_diff($bikes_in_this_blog,$bikes_from_api);

		foreach($difference_in_bikes as $one_to_delete) {			
			// get the POST OID from this BIKEINDEX OID
			$post_id_to_kill = $wpdb->get_var("select post_id from " . $wpdb->postmeta . " WHERE meta_key='bike_id' and meta_value='$one_to_delete'");
			
			// delete it from the meta and the posts
			$sql_kill_one = "DELETE FROM " . $wpdb->postmeta . " WHERE meta_key='bike_id' and meta_value='$one_to_delete'";
			$sql_kill_two = "DELETE FROM " . $wpdb->posts . " WHERE ID = '" . $post_id_to_kill . "'";
			// run the actual deletes
			$wpdb->query($sql_kill_one);
			$wpdb->query($sql_kill_two);
		}		
	}

	/**
	 * On the scheduled action hook, run a function.
	 */
	function bikeindexsync_do_this_hourly() {

		//First, check for deletes.

		$this->bikeindex_check_for_deletes();

		// Next - lets try to sync.
		$last_synced = get_option("bikeindex_sync_last_updated");
		$options = get_option("bike-index-sync-settings");

		if(isset($options['sync_records']) && $options['sync_records'] > 0){
			$sync_limit = $options['sync_records'];
		}
		else {
			return; //Dont run if the plugin isn't set up properly.
		}
		
		$queue = get_option("bikeindex_sync_queue");
		$bike_to_sync = array();
		//If Queue > max sync records, get that first amount and run with that.

		if(isset($queue) && $queue != '' && $queue != false && sizeof($queue) > 0) {
			//do a queue run
			for($i = 0; $i < $sync_limit; $i++) {
				$bikes_to_sync[] = array_shift($queue); //shave off the queue into the bikes pending array
			}
			$fresh_bikes = false;
		} else {
			$bikes = $this->get_bikes_from_endpoint();
			//If there are more than the allowed number of bikes, queue it up and call this same function. Don't actually start querying the endpoint until the number is less than the allowed sync record limit.
			if(sizeof($bikes) > $sync_limit) {
				for($i = 0; $i < $sync_limit; $i++) {
					$bikes_to_sync[] = array_shift($bikes);;
				}
				$queue = $bikes; //Save remaining bikes in the queue and proceed
			} else {
				$bikes_to_sync = $bikes;
			}
			$fresh_bikes = true;
		}

		if(isset($bikes_to_sync) && !empty($bikes_to_sync)){
			foreach($bikes_to_sync as $bike) {

				//first look up the bike record.

				if(!isset($bike))
					continue;

				$action = 'bikes/' . $bike;
				$req = $this->api->post_json(array(), $action);

				$bikes_response = json_decode($req['body']);
				if(!isset($bikes_response->bikes))
					continue;

				$bike = $bikes_response->bikes;

				global $wpdb;
				if(isset($bike)) {


					$bike_index_id = $bike->id;
					$sql = "SELECT * FROM " . $wpdb->postmeta . " WHERE meta_value = '" . $bike_index_id . "' AND meta_key = 'bike_id'";
					
					$results = $wpdb->get_results($sql);
					if(isset($results[0])) {
						$first_result = $results[0];


						$local_post_id = $first_result->post_id;
						$first_result = $results[0];
					}
					else {
						$local_post_id = '';
					}


					if(isset($bike->title)){
						$bike_name = $bike->title;
					}
					else
					{
						$bike_name = "No Name Available";
					}

					if(isset($options['attribution_author']))
						$author = $options['attribution_author'];
					else
						$author = 1;

					if($local_post_id == '') {
						// Create post object


						$bike_args = array(
						  'post_title'    => $bike_name,
						  'post_content'  => '',
						  'post_type'	  => "bikeindex_bike",
						  'post_status'   => 'publish',
						  'post_author'	  => $author,
						);

						// Insert the post into the database
						$local_post_id = wp_insert_post( $bike_args );
					} else {

						$bike_args = array(
						  'ID' => $local_post_id,
						  'post_title'    => $bike_name,
						  'post_content'  => '',
						  'post_type'	  => "bikeindex_bike",
						  'post_status'   => 'publish',
						  'post_author'	  => $author,
						);

						// Update the post in the database
						wp_update_post( $bike_args );
					}
					if($local_post_id != "") {
						foreach($bike as $key => $value) {
							if(is_object($value)) {
								foreach($value as $sub_key => $sub_value) {
							 		update_post_meta($local_post_id, "bike_" . $key . "_" . $sub_key, $sub_value);
							 	}
							}
						 	update_post_meta($local_post_id, "bike_" . $key, $value);
						}
				 	}
				}
			}
		}
		update_option('bikeindex_sync_queue', $queue);
		if($fresh_bikes == true)
			update_option('bikeindex_sync_last_updated', time());
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

	public function get_bikes_from_endpoint($force_past = false) {

		//First Check the Queue for any pending bike syncs

		$options = get_option('bike-index-sync-settings');

		//Break if no credentials available

		if(!isset($options['api_key']) || !isset($options['organization_id']))
			return;

		$last_updated_timestamp = get_option('bikeindex_sync_last_updated');
		
		// bdh added $force_past option to force past updates
		if(!isset($last_updated_timestamp) || ($last_updated_timestamp == false) || $force_past) {
			$last_updated_timestamp = "915148800"; //in the past... Willenium.
		}

		$data = array(
			"organization_slug" => $options['organization_id'],
			"access_token" => $options['api_key'],
			"stolen" => 1,
			"updated_since" => $last_updated_timestamp,
			"proximity" => $options['zipcode'],
			"proximity_radius" => $options['radius'],
		);

		if(isset($options['radius']) && isset($options['zipcode'])) {
			$data["proximity"] = $options['zipcode'];
			$data["proximity_radius"] = $options['radius'];			
		}

		$action = 'bikes/stolen_ids';
		$req = $this->api->post_json($data, $action);
		$bikes_response = json_decode($req['body']);
		$bikes = $bikes_response->bikes;
		// bdh sort them highest lowest
		arsort($bikes);
		return $bikes;
	}

}
