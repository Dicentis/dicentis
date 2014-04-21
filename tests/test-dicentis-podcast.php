<?php

/**
 * Test cases for dicentis-podcast.php
 */
class Dipo_Tests_Dicentis_Podcast extends WP_UnitTestCase {

	/**
	 * Ensure that the plugin has been installed and activated.
	 */
	function test_plugin_activated() {
		$this->assertTrue( is_plugin_active( 'dicentis-podcast/dicentis-podcast.php' ) );
	}

	function test_is_textdomain_loaded() {
		

		$this->assertTrue( class_exists('Dicentis') );
	}

}