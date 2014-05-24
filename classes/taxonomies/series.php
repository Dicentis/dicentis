<?php

include_once plugin_dir_path( __FILE__ ) . '../../dicentis-define.php';
include_once DIPO_CLASSES_DIR . '/post-type-podcast.php';

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
		$series_id = 0
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

		$taxonomy = array(
			'taxonomy' => $series_slug,
			'field' => 'term_id',
			'terms' => (int) $series_id,
		);
		array_push( $args['tax_query'], $taxonomy ); 

		$series_posts = new WP_Query($args);

		return $series_posts->posts;
	} // end function dipo_get_episodes()
}

if ( !function_exists('dipo_get_slug') ) {
	/**
	 * checks if other plugins are active which registered new series
	 * taxonomies and returns the correct slug for the active series
	 * taxonomy
	 * @return string returns the correct active slug for series taxonomy
	 */
	function dipo_get_series_slug() {
		// assume no plugin is active
		$series_slug = 'podcast_series';

		if ( dipo_is_celebration_plugin_active() ) {
			if ( taxonomy_exists( 'celebration_series' ) )
				$series_slug = 'celebration_series';
		}

		return $series_slug;
	} // end function dipo_get_series_slug()
}

if ( !function_exists('dipo_is_celebration_plugin_active') ) {
	/**
	 * checks if the ICF Avantgarde Celebration plugin is active
	 * @return boolean `true` if the Avantgarde Celebration plugin is
	 * active, otherwise `false`
	 */
	function dipo_is_celebration_plugin_active() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		return is_plugin_active( 'avantgarde-celebrations/avantgarde-celebrations.php' );
	} // end function dipo_is_celebration_plugin_active()
}
