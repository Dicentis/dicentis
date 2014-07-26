<?php

namespace Dicentis\Feed;

use Dicentis\Feed\Dipo_RSS;
use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

require_once ( ABSPATH . WPINC . '/feed.php' );

/**
* Feed Importer Class
*/
class Dipo_Feed_Import {
	private $feed_url;
	private $try_match = false;
	private $updated_episodes;
	private $created_episodes;

	function __construct( $url ) {
		$this->feed_url = $url;
		$this->updated_episodes = 0;
		$this->created_episodes = 0;
		define( 'ITUNES_NAMESPACE', 'http://www.itunes.com/dtds/podcast-1.0.dtd' );
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
			$maxitems = $rss->get_item_quantity(); 

			// Build an array of all the items, starting with element 0 (first element).
			$rss_items = $rss->get_items( 0, $maxitems );

			return array( $maxitems, $rss_items );

		endif;

		return array( -1, $rss );
	}

	public function save_new_episode( $item, $show_slug ) {

		$item_date = $item->get_date('Y-m-d H:i:s');

		$enclosure = $item->get_enclosure();

		$post = array(
			'post_title'   => $item->get_title(),
			'post_date'    => $item_date,
			'post_content' => $item->get_description(),
			'post_status'  => 'publish',
			'post_type'    => Dipo_Podcast_Post_Type::POST_TYPE,
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

		$this->created_episodes++;
		return $post_id;
	}

	public function save_mediafile_to_episode( $item, $show_slug ) {
			$args = array(
				'post_type' => Dicentis_Podcast_CPT::POST_TYPE,
				'tax_query' => array(
					array(
						'taxonomy' => 'podcast_show',
						'field' => 'slug',
						'terms' => $show_slug
					)
				),
				'date_query' => array(
					array(
						'year'  => $item->get_date('Y'),
						'month' => $item->get_date('m'),
						'day'   => $item->get_date('d'),
					),
				),
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() and 1 === $query->post_count ) :

				$query->the_post();
				$post_id = $query->post->ID;

				$enclosure = $item->get_enclosure();

				$medialink = esc_url_raw( $enclosure->get_link() );
				$type = htmlspecialchars( $enclosure->get_type() );
				$duration = htmlspecialchars( $enclosure->get_duration('hh:mm:ss') );
				$length = htmlspecialchars( $enclosure->get_length() );

				$next_number = get_post_meta( $post_id, '_dipo_max_mediafile_number', true );

				if ( 0 < $next_number ) {
					$next_number++;
				} else {
					$next_number = 1;
				}

				$mediafile = array(
					'id'        => $next_number,
					'medialink' => $medialink,
					'mediatype' => $type,
					'duration'  => $duration,
					'filesize'  => $length
				);

				update_post_meta( $post_id, '_dipo_mediafile' . $next_number, $mediafile );
				update_post_meta( $post_id, '_dipo_max_mediafile_number', $next_number );

				$this->updated_episodes++;
				return $post_id;

			else :

				// Either no match is found or multiple episodes exists (ambiguous) -> create new post
				$post_id = $this->save_new_episode( $item, $show_slug );
				return $post_id;

			endif;
	}

	public function import_feed( $maxitems, $rss_items, $show_slug ) {
		$result = array( 'imported' => $maxitems );

		if ( 0 === $maxitems )
			return false;
		else if ( is_wp_error( $rss_items ) ) {
			array_push( $result, $rss_items->get_error_message() );
			return $result;
		}

		foreach ( $rss_items as $item ) :

			if ( $this->get_try_match() ) {
				$post_id = $this->save_mediafile_to_episode( $item, $show_slug );
			} else {
				$post_id = $this->save_new_episode( $item, $show_slug );
			}

			array_push( $result, $post_id );
		endforeach;
			$result['updated'] = $this->updated_episodes;
			$result['created'] = $this->created_episodes;
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

	public function set_try_match( $match ) {
		if ( 'on' == $match )
			$this->try_match = true;
	}

	public function get_try_match() {
		return $this->try_match;
	}
}
