<?php

include_once plugin_dir_path( __FILE__ ) . '../dicentis-define.php';
include_once( ABSPATH . WPINC . '/feed.php' );

/**
* Feed Importer Class
*/
class FeedImport extends RSS {
	private $feed_url;

	function __construct( $url ) {
		parent::__construct();
		$this->feed_url = $url;
		define('ITUNES_NAMESPACE', 'http://www.itunes.com/dtds/podcast-1.0.dtd');
	}

	public function get_feed_url() {
		return $this->feed_url;
	}

	public function set_feed_url( $url = '' ) {
		$this->feed_url = $url;
	}

	public function get_feed() {
		$url = $this->get_feed_url();

		add_action('wp_feed_options', function($feed) {
			$feed->force_feed(true);
		}, 10, 1);

		return fetch_feed( $url );
	}

	public function get_feed_items() {
		$rss = $this->get_feed();

		if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

			// Figure out how many total items there are, but limit it to 5. 
			$maxitems = $rss->get_item_quantity( 5 ); 

			// Build an array of all the items, starting with element 0 (first element).
			$rss_items = $rss->get_items( 0, $maxitems );

			return array( $maxitems, $rss_items );

		endif;

		return array( -1, $rss );
	}

	public function import_feed( $maxitems, $rss_items, $show_slug ) {
		$result = array( $maxitems );

		if ( 0 === $maxitems )
			return false;
		else if ( is_wp_error( $rss_items ) ) {
			array_push( $result, $rss_items->get_error_message() );
			return $result;
		}


		foreach ( $rss_items as $item ) :
			$item_date = $item->get_date('Y-m-d H:i:s');

			$enclosure = $item->get_enclosure();

			$post = array(
				'post_title'   => $item->get_title(),
				'post_date'    => $item_date,
				'post_content' => $item->get_description(),
				'post_status'  => 'publish',
				'post_type'    => 'podcast',
				'tags_input'   => $enclosure->get_keywords(),
			);
			
			$post_id = wp_insert_post($post);

			if ( !empty( $show_slug ) ) {
				$term_id = term_exists( $show_slug, 'podcast_show' );
				wp_set_post_terms( $post_id, $term_id, 'podcast_show' );
			}

			// save episodes data as metadata for post
			$subtitle = htmlspecialchars( $this->get_subtitle( $item ) );
			update_post_meta( $post_id, '_dipo_subtitle', $subtitle );

			$summary = htmlspecialchars( $this->get_summary( $item ) );
			update_post_meta( $post_id, '_dipo_summary', $summary );

			$explicit = htmlspecialchars( strtolower( $this->get_explicit( $item ) ) );
			$possible_values = array( 'yes', 'no', 'clean' );
			if ( in_array( $explicit, $possible_values ) ) {
				update_post_meta( $post_id, '_dipo_explicit', $explicit );
			}

			$medialink = esc_url_raw( $enclosure->get_link() );
			$type = htmlspecialchars( $enclosure->get_type() );
			$duration = htmlspecialchars( $enclosure->get_duration('hh:mm:ss') );
			$length = htmlspecialchars( $enclosure->get_length() );
			$mediafile = array(
				'id'        => 1,
				'medialink' => $medialink,
				'mediatype' => $type,
				'duration'  => $duration,
				'filesize'  => $length
			);
			update_post_meta( $post_id, '_dipo_mediafile1', $mediafile );
			update_post_meta( $post_id, '_dipo_max_mediafile_number', '1' );


			array_push( $result, $post_id );
		endforeach;

		return $result;
	}

	private function get_summary( $item ) {
		$summary = $item->get_item_tags( ITUNES_NAMESPACE, 'summary' );
		return $summary[0]['data'];
	}

	private function get_subtitle( $item ) {
		$summary = $item->get_item_tags( ITUNES_NAMESPACE, 'subtitle' );
		return $summary[0]['data'];
	}

	private function get_explicit( $item ) {
		$summary = $item->get_item_tags( ITUNES_NAMESPACE, 'explicit' );
		return $summary[0]['data'];
	}
}