<?php
if ( !class_exists('Dicentis_Settings') ) {

	/**
	* Settings page for dicentis plugin
	*/
	class Dicentis_Settings {

		public function __construct() {
			// register actions
			add_action( 'admin_init', array( $this, 'admin_init') );
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
		} // END function __construct() 


		/**
		 * hook into WP's admin_init action hok
		 */
		public function admin_init() {
			// register the settings for this plugin
			register_setting( 'dicentis-group', 'setting_a' );
			register_setting( 'dicentis-group', 'setting_b' );
		} // END public function admin_init()

		/**
		 * Add a page to podcast post type to manage
		 * this plugin's settings
		 */
		public function add_menu() {
			add_submenu_page(
				'edit.php?post_type=podcast', // add to podcast menu
				__( 'Dicentis Podcast Settings', 'dicentis' ),
				__( 'Seetings', 'dicentis' ),
				'manage_options', // capabilities
				'dicentis_settings', // slug
				array( $this, 'dicentis_settings_page' )
			);
		} // END public function add_menu()

		/**
		 * Menu Callback
		 */
		public function dicentis_settings_page() {
			if( !current_user_can('manage_options') ) {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}

			// Render the settings template
			include( sprintf( "%s/../templates/settings.php", dirname(__FILE__) ) );
		} // END public function dicentis_settings_page()
	} // END class Dicentis_Settings
} // END if ( !class_exists('Dicentis_Settings') )
?>
