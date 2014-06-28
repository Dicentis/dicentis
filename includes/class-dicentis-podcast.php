<?php

namespace Dicentis;

use Dicentis\Settings\Dipo_Settings;
use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;
use Dicentis\Feed\Dipo_RSS;
use Dicentis\Core;

/**
 * The primary class for Dicentis Podcast.
 * 
 * This class is called by the plugin file dicentis-podcast.php and is the
 * first class which is instantiated. It is responsible for loading the
 * necessary hooks and create an object for the Podcast Custom Post Type.
 *
 * @author Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @since 0.2.0
 * @package Dicentis
 * @subpackage Core
 */
class Dicentis_Podcast {

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
	 * Loader class for holding all action and filter hooks in arrays
	 * 
	 * @since  0.2.0
	 * @access private
	 * @var Dipo_Hook_Loader $hook_loader responsible for adding actions and
	 *      filter hook into wordpress
	 */
	private $hook_loader;

	/**
	 * Constructor loads dependencies and registers hooks.
	 *
	 * @see Dicentis_Podcast::load_dependencies()   instantiates necessary
	 *                                              objects
	 * @see Dicentis_Podcast::register_hooks()      registers important hooks
	 */
	public function __construct() {

		$this->load_dependencies();
		$this->register_hooks();

	}

	/**
	 * Load dependencies: Hook Loader and Podast CPT.
	 *
	 * @see Dipo_Hook_Loader        Loader object for registering filter
	 *                              and action hooks
	 * @see Dipo_Podcast_Post_Type  Custom post type object for podcasts
	 */
	public function load_dependencies() {

		$this->hook_loader = new Core\Dipo_Hook_Loader();
		$this->podcast_cpt  = new Dipo_Podcast_Post_Type();

	}

	/**
	 * Method registers serveral hooks for this plugin.
	 *
	 * The Dipo_Hook_Loader object is used to register action and filter
	 * hooks for Admin, Settings, Localization, RSS.
	 * 
	 * @see Dipo_Hook_Loader    Loader object for registering filter
	 *                          and action hooks
	 * @see Dipo_Admin_Manager  Admin object for pages in backend
	 * @see Dipo_Settings       Settings object for saving and rendering
	 * @see Dipo_Localization   L10n object loads textdomain
	 * @see Dipo_RSS            Feed object for generating feeds
	 */
	public function register_hooks() {

		// add_filter( 'single_template', array( $this, 'single_template' ) );
		// add_filter( 'archive_template', array( $this, 'podcast_archive_template' ) );

		/**
		 * Admin Hooks
		 */
		$admin = new Core\Dipo_Admin_Manager();
		$this->hook_loader->add_filter( 'admin_init',
			$admin,
			'admin_init' );

		$this->hook_loader->add_action( 'admin_menu',
			$admin,
			'add_menu' );

		/**
		 * Settings Hooks
		 */
		$settings = new Dipo_Settings();
		$this->hook_loader->add_filter(
			'plugin_action_links_dicentis-podcast/dicentis-podcast.php',
			$settings,
			'plugin_action_settings_link' );

		$this->hook_loader->add_action( 'admin_init',
			$settings,
			'admin_init' );

		$this->hook_loader->add_action( 'admin_menu',
			$settings,
			'add_menu' );

		/**
		 * Localization Hooks
		 */
		$localization = new Core\Dipo_Localization();
		$this->hook_loader->add_action( 'plugins_loaded',
			$localization,
			'load_localisation' );

		/**
		 * RSS / Feed Hooks
		 */
		$feed = new Dipo_RSS();
		$this->hook_loader->add_action( 'init',
			$feed,
			'add_podcast_feeds');

		$this->hook_loader->add_action( 'template_redirect',
			$feed,
			'generate_podcast_feed' );

	}

	/**
	 * Getting Shit Real.
	 *
	 * After instantiating a Dicentis_Podcast object, loading all dependencies,
	 * and regestering hooks in the loader this function sets all in motions
	 * and finally registers all hooks for real. After this run the plugin
	 * is fully functional.
	 * 
	 * @see Dipo_Hook_Loader  Loader object for registering filter
	 *                        and action hooks
	 */
	public function run() {
		$this->hook_loader->run();
	}

}
