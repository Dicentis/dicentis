<?php

include_once DIPO_INC_DIR . '/controller/class-dipo-podcast-post-type.php';

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