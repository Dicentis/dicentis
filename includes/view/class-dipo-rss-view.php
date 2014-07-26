<?php

namespace Dicentis\Feed;


/**
 * @author Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @version 0.2.0
 */
class Dipo_RSS_View {

	public function do_podcast_feed( $template ) {

		load_template( $template );
		exit();

	}
}