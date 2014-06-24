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
	 * @var Dipo_Settings $settings Dipo_Settings object
	 */
	private $settings;

	/**
	 * The core of this plugin is its Custom Post Type "Podcast"
	 * representing through this variable.
	 * 
	 * @var Dipo_Podcast_Post_Type $podcast_cpt Custom Post Tye object which
	 *      registeres the custom post type for this plugin
	 */
	private $podcast_cpt;

	/**
	 * Localization class responsible for everything regarding l10n and i18n 
	 * @var Dipo_Localization $localization loads textdomain
	 */
	private $localization;

	public function __construct() {

		$this->settings    = new Dipo_Settings();
		$this->podcast_cpt = new Dipo_Podcast_Post_Type();
		$this->localization = new Core\Dipo_Localization();

		// Load the plugin's translated strings
		add_action( 'init', array( $this, 'load_localisation' ) );
		add_action( 'init', '\Dicentis\Feed\Dipo_RSS::add_podcast_feed' );
		$this->register_hooks();

		add_filter( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'add_menu') );

		// Create CPT Podcast

		add_action( 'template_redirect', array( $this, 'create_rss_feed' ) );
		// add_filter( 'single_template', array( $this, 'single_template' ) );
		// add_filter( 'archive_template', array( $this, 'podcast_archive_template' ) );

		add_filter( "plugin_action_links_dicentis-podcast/dicentis-podcast.php", array( $this->settings, 'plugin_action_settings_link' ) );
	} // END public function __construct()

	public function register_hooks() {
		// Load the plugin's translated strings
		$this->hook_loader->add_action( 'init',
			$this->localization,
			'load_localisation' );
	}
	/**
	 * Hook into WP's admin_init hook and do some admin stuff
	 * 		1. reorder Podcast's submenu
	 */
	public function admin_init() {
		$this->menu_order();
	} // END public function admin_init()

	/**
	 * Add admin menu pages to podcast post type
	 */
	public function add_menu() {
		add_submenu_page(
			'edit.php?post_type=' . Dipo_Podcast_Post_Type::POST_TYPE, // add to podcast menu
			__( 'Dashboard' ),
			__( 'Dashboard' ),
			'edit_posts',
			'dicentis_dashboard',
			array( $this, 'render_dashboard_page' )
		);
	} // END public function add_menu()

	public function render_dashboard_page() {
		if ( !current_user_can('edit_posts') ) {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		$show_feeds = array();
		$show_terms = get_terms( dipo_get_podcast_show_slug() );
		foreach ( $show_terms as $show_index => $show ) {

			$show_feed = trailingslashit( get_home_url() )
				. "?post_type=" . Dipo_Podcast_Post_Type::POST_TYPE
				. "&podcast_show=" . $show->slug
				. "&feed=pod";

			$show_pretty_feed = trailingslashit( get_home_url() )
				. "podcast/show/" . $show->slug
				. "/feed/pod";

			$show_array = array(
				'name' => $show->name,
				'slug' => $show->slug,
				'feed' => $show_feed,
				'pretty_feed' => $show_pretty_feed
			 );

			array_push( $show_feeds, $show_array );
		}

		// Render the dashboard template
		include( sprintf( "%s/dashboard.php", DIPO_TEMPLATES_DIR ) );
	} // END public function render_dashboard_page()

	/**
	 * create a custom menu order to display the dashboard
	 * menu always at the top of this post type
	 */
	public function menu_order() {
		// $submenu contains the order of the complete menu
		// i.e. top-level menus and submenus
		global $submenu;

		// Look for $find_page and the submenu $find_sub
		$find_page = 'edit.php?post_type=' . Dipo_Podcast_Post_Type::POST_TYPE;
		$find_sub  = 'Dashboard';
		// pre_print($submenu);
		// Loop thru $submenu until $find_page is found
		// foreach ( $submenu as $page => $items ) {
			// if ( $page == $find_page ) {
				// loop thru $find_page item and look for
				// $find_sub b/c we want to reorder it
				if ( isset( $submenu[$find_page] ) ) {
					foreach ( $submenu[$find_page] as $id => $meta ) {
						if ( $meta[0] == $find_sub ) {
							// $find_sub is found so assing it to
							// first place (0-based) in sub-array and unset
							// its former entry.
							// Last but not least sort it again.
							// 'Dashboard' is now at the top of this submenu
							$submenu[$find_page][0] = $meta;
							unset( $submenu[$find_page][$id] );
							ksort( $submenu[$find_page] );
						}
					}
				}
			// }
		// }
	} // END public function menu_order() 

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

	public function create_rss_feed() {
		$feed = new Dipo_RSS();
		$feed->generate_podcast_feed();
	}

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
