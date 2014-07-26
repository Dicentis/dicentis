<?php

namespace DicentisTest\Settings;

use Dicentis\Settings\Dipo_Settings;

require_once DIPO_ROOT . '/dicentis-define.php';

class Dipo_Settings_Test extends \WP_UnitTestCase {

	protected $settings;

	/**
	 * Create a Load Controller for this Test class to automatically
	 * include necessary classes during tests.
	 *
	 * And instanciate a new Dicentis_Podcast object for testing
	 * purposes.
	 */
	public function setUp() {
		$this->settings = new \Dicentis\Settings\Dipo_Settings();
	}

	/**
	 * Check if a action link to the settings page is set on the plugin page
	 */
	public function test_action_link() {
		$links = $this->settings->plugin_action_settings_link( array() );

		$settings_link = '<a href="options-general.php?page=dicentis_settings">Settings</a>';
		$this->assertTrue( in_array( $settings_link, $links ) );
	}
}