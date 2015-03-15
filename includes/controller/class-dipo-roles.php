<?php

namespace Dicentis;

/**
* Roles and Capabilities
*/
class Dipo_Roles {

	private $properties;
	private $textdomain;

	private $model;
	private $view;

	public function __construct() {

		// $this->view = new Dipo_Settings_View( $this );
		// $this->model = new Dipo_Settings_Model( $this );

		$this->properties = Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
		$this->register_settings_hooks();

		add_filter( 'map_meta_cap', array( $this, 'meta_caps' ), 10, 4 );
	} // END function __construct()

	/**
	 * hook into WP's admin_init action hok
	 */
	public function admin_init() {

		$this->add_roles();
		$this->add_caps();

	} // END public function admin_init()

	private function register_settings_hooks() {
	}

	public function add_roles() {
		add_role( 'dipo_podcast_admin',
			__( 'Podcast Admin', $this->textdomain ),
				array(
					'read' => true,
				)
		);

		add_role( 'dipo_podcast_manager',
			__( 'Podcast Manager', $this->textdomain ),
				array(
					'read' => true,
				)
		);

		add_role( 'dipo_podcast_producer',
			__( 'Podcast Producer', $this->textdomain ),
				array(
					'read' => true,
				)
		);
	}

	private function get_user_roles() {
		$roles = array(
			'administrator',
			'dipo_podcast_admin',
			'dipo_podcast_manager',
			'dipo_podcast_producer',
		);

		return $roles;
	}

	private function get_capabilities() {
		/**
		 * Capability => [$this->get_user_roles()]
		 *
		 * First column for admin, second for podcast admin, etc.
		 * Bool-like notation. 1 capability granted, 0 refused
		 */
		$dipo_capabilities = array(
			/** Custom Post Type Capabilities */
			'edit_podcast'              => [1, 1, 1, 1],
			'read_podcast'              => [1, 1, 1, 1],
			'delete_podcast'            => [1, 1, 1, 1],
			'edit_podcasts'             => [1, 1, 1, 1],
			'edit_others_podcasts'      => [1, 1, 1, 0],
			'publish_podcasts'          => [1, 1, 1, 1],
			'read_private_podcasts'     => [1, 1, 1, 0],
			'delete_podcasts'           => [1, 1, 1, 1],
			'delete_private_podcasts'   => [1, 1, 1, 0],
			'delete_published_podcasts' => [1, 1, 1, 1],
			'delete_others_podcasts'    => [1, 1, 1, 0],
			'edit_private_podcasts'     => [1, 1, 1, 0],
			'edit_published_podcasts'   => [1, 1, 1, 1],

			/** Custom Taxonomy Capabilities */
			/** Podcast Shows */
			'manage_podcast_shows' => [1, 1, 1, 0],
			'edit_podcast_shows'   => [1, 1, 1, 0],
			'delete_podcast_shows' => [1, 1, 1, 0],
			'assign_podcast_shows' => [1, 1, 1, 1],

			/** Speaker */
			'manage_podcast_speaker' => [1, 1, 1, 0],
			'edit_podcast_speaker'   => [1, 1, 1, 0],
			'delete_podcast_speaker' => [1, 1, 1, 0],
			'assign_podcast_speaker' => [1, 1, 1, 1],

			/** Series */
			'manage_podcast_series' => [1, 1, 1, 0],
			'edit_podcast_series'   => [1, 1, 1, 0],
			'delete_podcast_series' => [1, 1, 1, 0],
			'assign_podcast_series' => [1, 1, 1, 1],

			/** Plugin Capabilities */
			'dipo_read_dashboard' => [1, 1, 1, 1],
			'dipo_manage_options' => [1, 1, 0, 0],
		);

		return $dipo_capabilities;
	}

	public function add_caps() {
		$roles = $this->get_user_roles();

		$dipo_capabilities = $this->get_capabilities();

		foreach ( $roles as $role_key => $role_name ) {
			foreach ( $dipo_capabilities as $cap => $cap_array ) {
				$role = get_role( $role_name );

				// Check if cap array has enough entries
				// otherwise a PHP notice would be shown if the array
				// is to small
				if ( count($cap_array) >= count($roles) ) {
					$set_cap = $cap_array[$role_key];
					if ( 1 == $set_cap) {
						$role->add_cap( $cap );
					}
				}
			}
		}

	}

	public function meta_caps( $caps, $cap, $user_id, $args ) {
		switch( $cap ) {
			case 'read_podcast':
				$caps[] = 'read';
			break;
		}

		return $caps;
	}

	public function get_view() {
		return $this->view;
	}

	public function get_model() {
		return $this->model;
	}

} // END class Dicentis_Settings
