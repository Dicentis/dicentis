<?php

namespace Dicentis\Settings;

use Dicentis\Core;
use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

/**
* Settings page for dicentis plugin
*/
class Dipo_Settings_Model {

	private $properties;
	private $textdomain;

	public function __construct() {
		$this->properties = Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
	}
	public function add_settings_menu( $view ) {

		add_submenu_page(
			'edit.php?post_type=' . Dipo_Podcast_Post_Type::POST_TYPE, // add to podcast menu
			__( 'dicentis Podcast Settings', $this->textdomain ),
			__( 'Settings' ),
			'manage_options', // capabilities
			'dicentis_settings', // slug
			array( $view, 'render_settings_page' )
		);

	}

}