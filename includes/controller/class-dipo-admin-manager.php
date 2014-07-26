<?php

namespace Dicentis\Admin;

use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;
use Dicentis\Core;

class Dipo_Admin_Manager {

	private $properties;

	private $model;
	private $view;

	public function __construct() {
		$this->properties = Core\Dipo_Property_List::get_instance();

		$this->view  = new Dipo_Admin_Manager_View();
		$this->model = new Dipo_Admin_Manager_Model();
		$this->register_admin_hooks();

	}

	private function register_admin_hooks() {
		$loader = $this->properties->get( 'hook_loader' );

		// script & style action with page detection
		$loader->add_action(
			'admin_enqueue_scripts',
			$this->view,
			'load_dashboard_feed_style' );

	}

	/**
	 * Hook into WP's admin_init hook and do some admin stuff
	 * 		1. reorder Podcast's submenu
	 */
	public function admin_init() {
		$this->menu_order();
	}

	/**
	 * create a custom menu order to display the dashboard
	 * menu always at the top of this post type
	 */
	public function menu_order() {
		$this->model->prepend_dashboard_link();
	}

	/**
	 * Add admin menu pages to podcast post type
	 */
	public function add_menu() {
		$this->model->add_dashboard_menu( $this->view );
	}

}