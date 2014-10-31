<?php

namespace Dicentis\Settings;

use Dicentis\Core;
use Dicentis\Feed\Dipo_Feed_port;
use Dicentis\Feed\Dipo_RSS_Model;

/**
* Settings page for dicentis plugin
*/
class Dipo_Settings_Controller {

	private $properties;
	private $textdomain;

	private $model;
	private $view;

	public function __construct() {

		$this->view = new Dipo_Settings_View( $this );
		$this->model = new Dipo_Settings_Model( $this );

		$this->properties = Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
		$this->register_settings_hooks();


	} // END function __construct()

	private function register_settings_hooks() {
		add_action( 'admin_enqueue_scripts',
			array( $this->view, 'admin_settings_scripts' ) );
		add_action('create_podcast_show',
			array( $this, 'register_show_settings' ), 10, 1);
	}

	/**
	 * hook into WP's admin_init action hok
	 */
	public function admin_init() {

		$this->init_settings();

	} // END public function admin_init()

	/**
	 * Add a page to podcast post type to manage
	 * this plugin's settings
	 */
	public function add_menu() {
		$this->model->add_settings_menu( $this->view );
	} // END public function add_menu()


	// Add the settings link to the plugin page
	public function plugin_action_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=dicentis_settings">' . __( 'Settings', $this->textdomain ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	public function init_settings() {
		$show_model = new \Dicentis\Podcast_Post_Type\Dipo_Podcast_Shows_Model();
		$shows = $show_model->get_shows( false );

		$this->register_show_settings( -1 );
		foreach ( $shows as $show ) {
			$this->register_show_settings( $show->term_id );
		}
	}

	public function register_show_settings( $term_id ) {
		if ( -1 < $term_id ) {
			$term = get_term( $term_id, 'podcast_show' );
			$slug = $term->slug;
		} else {
			$slug = 'all_shows';
		}


		$option = $this->model->get_option_name( $slug );

		// register settings for new show
		register_setting(
			$option,
			$option,
			array( $this->model, 'validate_show_options' ) );

		$sec_id = $this->model->add_show_settings_section( $slug );
	}

	public function get_view() {
		return $this->view;
	}

	public function get_model() {
		return $this->model;
	}

} // END class Dicentis_Settings
