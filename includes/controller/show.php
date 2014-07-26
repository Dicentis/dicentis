<?php

if ( !function_exists('dipo_get_podcast_shows') ) {
	/**
	 *function to retrieve podcast show information
	 * whichever taxonomy for podcast show are activated.
	 * @since  0.0.1
	 * @param  (string|array)  $args Change what is returned.
	 * @return (array|string|WP_Error)        Array of term objects or an
	 * empty array if no terms were found. WP_Error if a 'podcast_show' taxonomy
	 * does not exist. If the 'fields' argument was 'count', the number of
	 * terms found will be returned as a string.
	 */
	function dipo_get_podcast_show(
		$args = array(
			'orderby'       => 'name', 
			'order'         => 'ASC',
			'hide_empty'    => true,
			'cache_domain'  => 'core'
		)
	) {

		$podcast_show_slug = dipo_get_podcast_show_slug();
		$podcast_show = get_terms( $podcast_show_slug, $args );

		return isset($podcast_show) ? $podcast_show : null;
	} // end function dipo_get_podcast_show()
}

if ( !function_exists('dipo_get_podcast_show_slug') ) {
	/** 
	 * returns the correct slug for the podcast_show taxonomy
	 * @return string returns the slug for podcast_show taxonomy
	 */
	function dipo_get_podcast_show_slug() {
		return 'podcast_show';
	} // end function dipo_get_podcast_show_slug()
}