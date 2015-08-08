<?php

namespace Dicentis\Tests\Unit\Controller;

/**
 * Class Dipo_Podcast_Post_Type_Test
 * @package Dicentis\Tests\Unit\Controller
 * @author Hans-Helge Buerger <mail@hanshelgebuerger.de>
 */
class Dipo_Podcast_Post_Type_Test extends \PHPUnit_Framework_TestCase {

	/**
	 * @var SUT
	 */
	protected $dipo_post_type;

	/**
	 * Setup \WP_Mock which is used to mock WordPress functions
	 */
	public function setUp() {
		\WP_Mock::setUp();
	}

	/**
	 * Remove \WP_Mock which is used to mock WordPress functions*
	 */
	public function tearDown() {
		\WP_Mock::tearDown();
	}

	/**
	 * Test function checks if the Dicentis Font is properly enqueued when
	 * Dipo_Podcast_Post_Type::load_custom_wp_admin_style() is called.
	 */
	public function test_dipo_font_is_enqueued() {

		// Create mock Constant, which is used within test method
		define( 'DIPO_ASSETS_URL', '/assets' );

		/* Create Partial Mock of Class
		 * - This will avoid constructor
		 * - makes actual test method available for real ( passthru() )
		 */
		$mock = \Mockery::mock( 'Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type' )->makePartial();
		$mock->shouldReceive( 'load_custom_wp_admin_style' )->passthru();

		// Expect wp_register_style to be called once with given parameters
		\WP_Mock::wpFunction( 'wp_register_style', array(
			'times' => 1,
			'args' => array( 'dipo_font', '/assets/css/dicentis-font.css' ),
			'return' => NULL,
		) );
		// Expect wp_enqueue_style to be called once with given parameters
		\WP_Mock::wpFunction( 'wp_enqueue_style', array(
			'times' => 1,
			'args' => array( 'dipo_font' ),
			'return' => NULL,
		) );

		/* Lets test method:
		 * @param '' empty string to exit after loading dipo_font because $hook is not 'post.php' and 'post-new.php'
		 */
		$mock->load_custom_wp_admin_style( '' );
	}
}
