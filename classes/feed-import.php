<?php

include_once plugin_dir_path( __FILE__ ) . '../dicentis-define.php';
include_once( ABSPATH . WPINC . '/feed.php' );

/**
* Feed Importer Class
*/
class FeedImport extends RSS {

	function __construct() {
		parent::__construct();
	}

	public function import_feed( $url ) {
		// Get a SimplePie feed object from the specified feed source.
		if ( !empty($url) ) {
			$url = esc_url( $url );
		} else {
			return 1;
		}

		$rss = fetch_feed( $url );

		if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

			// Figure out how many total items there are, but limit it to 5. 
			$maxitems = $rss->get_item_quantity(); 

			// Build an array of all the items, starting with element 0 (first element).
			$rss_items = $rss->get_items( 0, $maxitems );

			return array( $maxitems, $rss_items );
		endif;

		return false;
	}
}