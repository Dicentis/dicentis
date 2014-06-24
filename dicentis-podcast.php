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

if ( ! class_exists( 'Dicentis_Podcast' ) )
	require_once plugin_dir_path( __FILE__ ) . 'includes/Dicentis_Podcast.php';

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function dipo_load_plugin() {

	$path = plugin_dir_path( __FILE__ );

	if ( ! class_exists( 'Dipo_Load_Controller' ) )
		require $path . 'includes/autoload/Dipo_Load_Controller.php';

	$loader = new Autoload\Dipo_Load_Controller( $path . 'includes' );

	$dipo = new Dicentis_Podcast();
	$dipo->run();

}
dipo_load_plugin();
