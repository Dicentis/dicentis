<?php

namespace DicentisTest;

use Dicentis\Dicentis_Podcast;
use Dicentis\Autoload\Dipo_Load_Controller;

require_once DIPO_ROOT . '/includes/Dicentis_Podcast.php';

class Dicentis_Podcast_Test extends \WP_UnitTestCase {

	protected $dipo;

	/**
	 * Create a Load Controller for this Test class to automatically
	 * include necessary classes during tests.
	 *
	 * And instanciate a new Dicentis_Podcast object for testing
	 * purposes.
	 */
	public function setUp() {
		$path = DIPO_ROOT . "/";

		if ( ! class_exists( 'Dipo_Load_Controller' ) )
			require $path . 'includes/autoload/Dipo_Load_Controller.php';

		$loader = new Dipo_Load_Controller( $path . 'includes' );

		$this->dipo = new \Dicentis\Dicentis_Podcast();
	}

	/**
	 * Ensure that the plugin has been installed and activated.
	 */
	public function test_plugin_activated() {
		$this->assertTrue( is_plugin_active( 'dicentis-podcast/dicentis-podcast.php' ) );
	}
}