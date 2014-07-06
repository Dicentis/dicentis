<?php

namespace Dicentis\Core;

/**
 * Localization class for loading Dicentis' textdomain.
 *
 * @author Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @version 0.2.0
 */
class Dipo_Localization {

	/**
	 * Loads the plugin textdomain for Dicentis.
	 */
	public function load_localisation() {

		load_plugin_textdomain( 'dicentis', false,
			'dicentis-podcast/languages/' );

	} // END public function load_localisation()
}