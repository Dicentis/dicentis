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

	public function get_feed_items( $url ) {
		// Get a SimplePie feed object from the specified feed source.
		if ( !empty($url) ) {
			$url = esc_url( $url );
		} else {
			return 1;
		}

		$rss = fetch_feed( $url );
		if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

			// Figure out how many total items there are, but limit it to 5. 
			$maxitems = $rss->get_item_quantity( 1 ); 

			// Build an array of all the items, starting with element 0 (first element).
			$rss_items = $rss->get_items( 0, $maxitems );

			return array( $maxitems, $rss_items );
		endif;

		return false;
	}

	public function import_feed( $maxitems, $rss_items, $show_slug ) {
		if ( 0 === $maxitems )
			return false;

		$result = array( 'maxitems' => $maxitems );

		foreach ( $rss_items as $item ) {
			$item_date = $item->get_date('Y-m-d H:i:s');

			$post = array(
				'post_title'   => $item->get_title(),
				'post_date'    => $item_date,
				'post_content' => $item->get_description(),
				'post_status'  => 'publish',
				'post_type'    => 'podcast'
			);
			$post_id = wp_insert_post($post);

			if ( !empty( $show_slug ) ) {
				$term_id = term_exists( $show_slug, 'podcast_show' );
				wp_set_post_terms( $post_id, $term_id, 'podcast_show' );
			}

			array_push( $result, $post_id );
		}

		return $result;
	}
}