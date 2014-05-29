<?php

include_once plugin_dir_path( __FILE__ ) . '../../dicentis-define.php';
include_once DIPO_CLASSES_DIR . '/post-type-podcast.php';

if ( !function_exists('dipo_get_speaker_slug') ) {
	/**
	 * returns the correct slug for the speaker taxonomy
	 * taxonomy
	 * @return string returns the active slug for speaker taxonomy
	 */
	function dipo_get_speaker_slug() {
		$speaker_slug = 'podcast_speaker';

		return $speaker_slug;
	} // end function dipo_get_speaker_slug()
}