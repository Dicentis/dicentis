<?php
/*
Plugin Name: dicentis Podcast
Plugin URI: http://
Description: Manage multiple podcasts with ease in one plugin
Version: 0.1dev
Author: Hans-Helge Buerger
Author URI: http://hanshelgebuerger.de

Copyright 2013 Hans-Helge Buerger (http://hanshelgebuerger.de)
License: GPL (http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt)
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('Dicentis') ) {
	class Dicentis {

		public function __construct() {
			// Load the plugin's translated strings
			add_action( 'init' , array( $this, 'load_localisation' ) );

			// define default settings and save
			$dicentis_options = array(
				'option1' => 'value1',
				'option2' => 'value2',
				'option3' => 'value3',
			);
			update_option( 'dicentis_options', $dicentis_options );

			// register actions
			add_action( 'admin_init', array($this, 'admin_init') );
			add_action( 'admin_menu', array($this, 'add_menu') );
			require_once( sprintf("%s/post-types/post-type-podcast.php", dirname(__FILE__)) );
			$PostTypePodcast = new PostTypePodcast();
		}

		/**
		 * Activate the plugin
		 * this plugin needs at least WP v3.6
		 */
		public static function activate() {
			// If WP version is not > 3.6 this plugin dies and cannot be used
			if ( version_compare( get_bloginfo( 'version' ), '3.6', '<' ) ) {
				// deactivate plugin
				die( __( 'This Plugin requires WordPress version 3.6 or higher.', 'dicentis' ) );
			}

			// register deactivation hook only then plugin is activated
			// and not on every plugin load.
			register_deactivation_hook( __FILE__, array('Dicentis', 'deactivate') );
		}

		/**
		 * Deactivate the plugin
		 * @return [type] [description]
		 */
		public static function deactivate() {

		}

		/**
		 * hook into WP's admin_init action hook
		 */
		public function admin_init() {
			// Set up the settings for this plugin
			$this->init_settings();
		}

		/**
		 * Initialize some custom settings
		 */
		public function init_settings() {
			// register the settings for this plugin
			register_setting( 'dicentis-group', 'setting_a' );
			register_setting( 'dicentis-group', 'setting_b' );
		}

		/**
		 * add a menu
		 */
		public function add_menu() {
			add_options_page(
					__( 'Dicentis Settings', 'dicentis' ),
					__( 'Dicentis', 'dicentis' ),
					'manage_options',
					'dicentis',
					array( $this, 'dicentis_settings_page' )
				);
		}

		/**
		 * Menu Callback
		 */
		public function dicentis_settings_page() {
			if( !current_user_can('manage_options') ) {
				wp_die( __('You do not have sufficient permissions to access this page.', 'dicentis' ) );
			}

			// Render the settings template
			include( sprintf( "%s/templates/settings.php", dirname(__FILE__) ) );
		}

		public function load_localisation() {
			load_plugin_textdomain( 'dicentis', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}
	}
}


if ( class_exists('Dicentis') ) {

	// Installation and uninstallation hooks
	register_activation_hook( __FILE__, array('Dicentis', 'activate') );

	$dicentis = new Dicentis();

	// Add a link to the settings page onto the plugin page
	if( isset( $dicentis ) ) {
		// Add the settings link to the plugin page
		function dicentis_settings_link( $links ) {
			$settings_link = __( '<a href="options-general.php?page=dicentis">Settings</a>', 'dicentis' );
			array_unshift( $links, $settings_link );
			return $links;
		}

		$plugin = plugin_basename( __FILE__ );
		add_filter( "plugin_action_links_$plugin", 'dicentis_settings_link' );
	}
}

?>
