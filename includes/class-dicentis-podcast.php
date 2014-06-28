<?php

namespace Dicentis;

use Dicentis\Settings\Dipo_Settings;
use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;
use Dicentis\Feed\Dipo_RSS;
use Dicentis\Core;

class Dicentis_Podcast {

	/**
	 * Dicentis Settings Object which is responsible for all settings
	 * regarding dicentis.
	 * 
	 * @since  0.2.0
	 * @access private
	 * @var Dipo_Settings $settings Dipo_Settings object
	 */
	private $settings;

	/**
	 * The core of this plugin is its Custom Post Type "Podcast"
	 * representing through this variable.
	 * 
	 * @since  0.2.0
	 * @access private
	 * @var Dipo_Podcast_Post_Type $podcast_cpt Custom Post Tye object which
	 *      registeres the custom post type for this plugin
	 */
	private $podcast_cpt;

	/**
	 * RSS Object for creating, instantiating a valid RSS Feed
	 * 
	 * @since  0.2.0
	 * @access private
	 * @var Dipo_RSS $feed adds new feeds to WordPress and is responsible
	 *      for rendering the feeds
	 */
	private $feed;

	/**
	 * Loader class for holding all action and filter hooks in arrays
	 * 
	 * @since  0.2.0
	 * @access private
	 * @var Dipo_Hook_Loader $hook_loader responsible for adding actions and
	 *      filter hook into wordpress
	 */
	private $hook_loader;

	/**
	 * Admin object which is responsible for the backend pages
	 * 
	 * @since  0.2.0
	 * @access private
	 * @var [type]
	 */
	private $admin;

	public function __construct() {

		$this->settings     = new Dipo_Settings();
		$this->podcast_cpt  = new Dipo_Podcast_Post_Type();
		$this->feed         = new Dipo_RSS();
		$this->hook_loader  = new Core\Dipo_Hook_Loader();
		$this->admin        = new Core\Dipo_Admin_Manager();

		$this->register_hooks();

		// add_filter( 'single_template', array( $this, 'single_template' ) );
		// add_filter( 'archive_template', array( $this, 'podcast_archive_template' ) );

		add_filter( 'plugin_action_links_dicentis-podcast/dicentis-podcast.php', array( $this->settings, 'plugin_action_settings_link' ) );
	} // END public function __construct()

	public function register_hooks() {
		/**
		 * Admin Hooks
		 */
		$this->hook_loader->add_filter( 'admin_init',
			$this->admin,
			'admin_init' );

		$this->hook_loader->add_action( 'admin_menu',
			$this->admin,
			'add_menu' );

		/**
		 * Localization Hooks
		 */
		$this->hook_loader->add_action( 'plugins_loaded',
			new Core\Dipo_Localization(),
			'load_localisation' );

		/**
		 * RSS / Feed Hooks
		 */
		$this->hook_loader->add_action( 'init',
			$this->feed,
			'add_podcast_feeds');

		$this->hook_loader->add_action( 'template_redirect',
			$this->feed,
			'generate_podcast_feed' );
	}



	/**
	 * Activate the plugin
	 * this plugin needs at least WP v3.6
	 */
	public static function activate() {
		// If WP version is not > 3.6 this plugin dies and cannot be used
		if ( version_compare( get_bloginfo( 'version' ), '3.6', '<' ) ) {
			// deactivate plugin
			die( __( 'This Plugin requires WordPress version 3.6 or higher.', DIPO_TEXTDOMAIN ) );
		}

		// register deactivation hook only then plugin is activated
		// and not on every plugin load.
		register_deactivation_hook( __FILE__, array('Dicentis_Podcast', 'deactivate') );

		// Dipo_RSS::add_podcast_feed();
		// flush_rewrite_rules();
	} // END public static function activate()

	/**
	 * Deactivate the plugin
	 * @return [type] [description]
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	} // END public static function deactivate()

	public function single_template( $single_template ) {
		global $post;

		if ( $post->post_type == Dipo_Podcast_Post_Type::POST_TYPE ) {
			$single_template = DIPO_TEMPLATES_DIR . '/episode-single.php';
		}

		return $single_template;
	}

	public function podcast_archive_template( $archive_template ) {
		global $post;

		//  if ( is_post_type_archive ( Dicentis_Podcast_CPT::POST_TYPE ) ) {
		// 	$archive_template = dirname( __FILE__ ) . '/templates/podcast-archive.php';
		// }

		return $archive_template;
	}

	public function run() {
		$this->hook_loader->run();
	}
}
