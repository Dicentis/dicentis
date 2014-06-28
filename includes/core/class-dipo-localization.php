<?php

namespace Dicentis\Core;

class Dipo_Localization {

	public function load_localisation() {

		load_plugin_textdomain( 'dicentis', false,
			'dicentis-podcast/languages/' );

	} // END public function load_localisation()
}