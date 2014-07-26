<?php

include_once DIPO_INC_DIR . '/controller/class-dipo-podcast-post-type.php';

if ( !function_exists('dipo_get_series') ) {
	/**
	 *function to retrieve series information
	 * whichever taxonomy for series are activated.
	 * @since  0.0.1
	 * @param  (string|array)  $args Change what is returned.
	 * @return (array|string|WP_Error)        Array of term objects or an
	 * empty array if no terms were found. WP_Error if a 'series' taxonomy
	 * does not exist. If the 'fields' argument was 'count', the number of
	 * terms found will be returned as a string.
	 */
	function dipo_get_series(
		$args = array(
			'orderby'       => 'name', 
			'order'         => 'ASC',
			'hide_empty'    => true,
			'cache_domain'  => 'core'
		)
	) {

		$series_slug = dipo_get_series_slug();
		$series = get_terms( $series_slug, $args );

		return isset($series) ? $series : null;
	} // end function dipo_get_series()
}

if ( !function_exists('dipo_get_episodes') ) {
	/**
	 * This method returns all posts in a series. A specific series can be
	 * named by passing the series ID. To get that ID use `dipo_get_series()`.
	 * If no specific series is named the first series is set as default.
	 * @param  integer $series_id ID of series
	 * @return array              returns all posts in this series as array
	 */
	function dipo_get_episodes(
		$series_id  = 0,
		$show_id    = 0,
		$speaker_id = 0
	) {

		$post_type = Dicentis_Podcast_CPT::POST_TYPE;
		$series_slug = dipo_get_series_slug();

		$args = array(
			'post_type' => $post_type,
			'order' => 'ASC',
			'oderby' => 'date',
			'tax_query' => array(
				'relation' => 'AND',
			),
		);

		if ( 0 === $series_id ) :
			$series = dipo_get_series();

			if ( is_wp_error( $series ) and isset( $series[0] ) )
				return false;

			$series_id = $series[0]->term_id;
		endif;

		if ( is_array( $series_id ) ) {
			foreach ($series_id as $series => $id) {
				$taxonomy = array(
					'taxonomy' => $series_slug,
					'field' => 'term_id',
					'terms' => (int) $id,
				);
				array_push( $args['tax_query'], $taxonomy ); 
			}
		} else {
			$taxonomy = array(
				'taxonomy' => $series_slug,
				'field' => 'term_id',
				'terms' => (int) $series_id,
			);
			array_push( $args['tax_query'], $taxonomy ); 
		}


		if ( 0 !== $speaker_id ) :
			$speaker_slug = dipo_get_speaker_slug();

			if ( is_array( $speaker_id ) ) {
				foreach ($speaker_id as $speaker => $id) {
					$taxonomy = array(
						'taxonomy' => $speaker_slug,
						'field' => 'term_id',
						'terms' => (int) $id,
					);
					array_push( $args['tax_query'], $taxonomy ); 
				}
			} else {
				$taxonomy = array(
					'taxonomy' => $speaker_slug,
					'field' => 'term_id',
					'terms' => (int) $speaker_id,
				);
				array_push( $args['tax_query'], $taxonomy ); 
			}
		endif;

		if ( 0 !== $show_id ) :
			$show_slug = dipo_get_podcast_show_slug();

			if ( is_array( $show_id ) ) {
				foreach ($show_id as $show => $id) {
					$taxonomy = array(
						'taxonomy' => $show_slug,
						'field' => 'term_id',
						'terms' => (int) $id,
					);
					array_push( $args['tax_query'], $taxonomy ); 
				}
			} else {
				$taxonomy = array(
					'taxonomy' => $show_slug,
					'field' => 'term_id',
					'terms' => (int) $show_id,
				);
				array_push( $args['tax_query'], $taxonomy ); 
			}
		endif;

		$series_posts = new WP_Query($args);

		return $series_posts->posts;
	} // end function dipo_get_episodes()
}

if ( !function_exists('dipo_get_episode_meta') ) {
	/**
	 * With a given episode ID this function gathers all relevant information
	 * for this episode. This includes: Subtitle, Summary, Image, GUID,
	 * Explicit, Number of mediafiles, Mediafiles (array), Show (array), 
	 * Speaker (array), Series (array), Tags (array)
	 * If $episode_id is not a valid episode of post type 'dipo_podcast' `null`
	 * is returned.
	 * @param  int $episode_id post ID of a episode
	 * @return array           episode information as array
	 */
	function dipo_get_episode_meta( $episode_id ) {

		if ( Dicentis_Podcast_CPT::POST_TYPE !== 
			get_post( $episode_id )->post_type )
			return null;

		$dipo_media_count = get_post_meta( $episode_id, '_dipo_max_mediafile_number', true );
		// $mediatypes  = $this->get_mediatypes();

		// retrieve the metadata values if they exist
		$dipo_subtitle = get_post_meta( $episode_id, '_dipo_subtitle', true );
		$dipo_summary  = get_post_meta( $episode_id, '_dipo_summary', true );
		$dipo_image    = get_post_meta( $episode_id, '_dipo_image', true );
		$dipo_guid     = get_post_meta( $episode_id, '_dipo_guid', true );
		$dipo_explicit = get_post_meta( $episode_id, '_dipo_explicit', true );
		$episode_tags  = wp_get_post_tags( $episode_id );

		$dipo_mediafiles = array();

		for ( $i=1; $i <= $dipo_media_count; $i++ ) { 
			$temp_mediafile = get_post_meta( $episode_id, '_dipo_mediafile' . $i, true );
			array_push( $dipo_mediafiles, $temp_mediafile );
		}


		$dipo_show  = wp_get_post_terms( $episode_id, 'podcast_show' );

		require_once DIPO_CLASSES_DIR . '/taxonomies/speaker.php';
		if ( function_exists('dipo_get_speaker_slug') )
			$dipo_speaker  = wp_get_post_terms( $episode_id, dipo_get_speaker_slug() );

		$dipo_series  = wp_get_post_terms( $episode_id, dipo_get_series_slug() );

		$episode_meta = array(
			'episode_id' => $episode_id,
			'dipo_subtitle' => $dipo_subtitle,
			'dipo_summary' => $dipo_summary,
			'dipo_image' => $dipo_image,
			'dipo_guid' => $dipo_guid,
			'dipo_explicit' => $dipo_explicit,
			'dipo_media_count' => $dipo_media_count,
			'dipo_mediafiles' => $dipo_mediafiles,
			'dipo_podcast_show' => $dipo_show,
			'dipo_speaker' => $dipo_speaker,
			'dipo_series' => $dipo_series,
			'dipo_tags' => $episode_tags,

		);
		return $episode_meta;
	}
}

if ( !function_exists('dipo_get_series_slug') ) {
	/**
	 * returns the slug for the active series
	 * taxonomy
	 * @return string returns the slug for series taxonomy
	 */
	function dipo_get_series_slug() {
		$series_slug = 'podcast_series';

		return $series_slug;
	} // end function dipo_get_series_slug()
}
