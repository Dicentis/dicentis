<?php

namespace Dicentis\Dipo_Podcast_Post_Type;

use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

/**
 * Controller class for episodes and their mediafiles
 *
 * @author Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @since 0.2.2
 * @package Dicentis
 */
class Dipo_Episode_Controller {

	/**
	 * Property object with information for Dicentis
	 *
	 * @since  0.2.0
	 * @access private
	 * @var Dipo_Property_List $properties includes useful information e.g. textdomain
	 */
	private $properties;

	/**
	 * Textdomain for this plugin
	 *
	 * @since  0.1.0
	 * @access private
	 * @var String $textdomain
	 */
	private $textdomain;

	public function __construct() {
		$this->properties = \Dicentis\Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
		$this->register_hooks();
	}

	public function register_hooks() {
	   // register hooks for this class
	}
}
