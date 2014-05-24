<?php

include_once plugin_dir_path( __FILE__ ) . '../../dicentis-define.php';
include_once DIPO_CLASSES_DIR . '/post-type-podcast.php';

if ( !function_exists('dipo_get_speaker_slug') ) {
	/**
	 * checks if other plugins are active which registered new speaker
	 * taxonomies and returns the correct slug for the active speaker
	 * taxonomy
	 * @return string returns the correct active slug for speaker taxonomy
	 */
	function dipo_get_speaker_slug() {
		// assume no plugin is active
		$speaker_slug = 'podcast_speaker';

		if ( dipo_is_celebration_plugin_active() ) {
			if ( taxonomy_exists( 'celebration_preachers' ) )
				$speaker_slug = 'celebration_preachers';
		}

		return $speaker_slug;
	} // end function dipo_get_speaker_slug()
}