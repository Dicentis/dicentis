<?php

require_once DIPO_ROOT . '/includes/Dicentis_Podcast.php';

class Dicentis_Podcast_Test extends WP_UnitTestCase {

	protected $dipo;

	public function setUp() {
		$this->dipo = new Dicentis\Dicentis_Podcast();
	}

	/**
	 * Ensure that the plugin has been installed and activated.
	 */
	public function test_plugin_activated() {
		$this->assertTrue( is_plugin_active( 'dicentis-podcast/dicentis-podcast.php' ) );
	}
}