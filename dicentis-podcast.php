<?php
/*
Plugin Name: Dicentis Podcast
Plugin URI: http://hanshelgebuerger.de
Description: Manage multiple podcasts with ease in one plugin
Version: 0.1.0
Author: Hans-Helge Buerger
Author URI: http://hanshelgebuerger.de
Text Domain: dicentis
Domain Path: /languages/
GitHub Plugin URI: https://github.com/obstschale/dicentis-podcast
GitHub Branch: master
GitHub Access Token: 06f0508db3f02e704d0e1bbdce452be7d50aa308

Copyright 2013 Hans-Helge Buerger (http://hanshelgebuerger.de)
License: GPL (http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt)
 */
namespace Dicentis;

require_once __DIR__ . '/dicentis-define.php';

require_once DIPO_INC_DIR . '/taxonomies/series.php';
require_once DIPO_INC_DIR . '/taxonomies/speaker.php';
require_once DIPO_INC_DIR . '/taxonomies/show.php';
require_once DIPO_LIB_DIR . '/tgm-plugin.php';
require_once DIPO_LIB_DIR . '/dipo-version.php';


require_once __DIR__ . '/includes/Dicentis_Podcast.php';

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function dipo_run_dicentis_podcast() {

	$dipo = new Dicentis_Podcast();
	// $dipo->run();

}

dipo_run_dicentis_podcast();

// Installation and uninstallation hooks
// register_activation_hook( __FILE__, array('Dicentis', 'activate') );

// $dicentis = new Dicentis();

// // Add a link to the settings page onto the plugin page
// if( isset( $dicentis ) ) {
// 	// Add the settings link to the plugin page
// 	function dicentis_settings_link( $links ) {
// 		$settings_link = '<a href="options-general.php?page=dicentis">' . __( 'Settings', DIPO_TEXTDOMAIN ) . '</a>';
// 		array_unshift( $links, $settings_link );
// 		return $links;
// 	}

// 	$plugin = plugin_basename( __FILE__ );
// 	add_filter( "plugin_action_links_$plugin", 'dicentis_settings_link' );
// }
