<?php
/**
 * @package   Dicentis Podcast
 * @author    Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @license   GPL-3.0
 * @link      http://dicentis.io
 * @copyright 2014 Hans-Helge Buerger
 *
 * Plugin Name: Dicentis Podcast
 * Plugin URI: http://hanshelgebuerger.de
 * Description: Manage multiple podcasts with ease in one plugin
 * Version: 0.2.1
 * Author: Hans-Helge Buerger
 * Author URI: http://hanshelgebuerger.de
 * Text Domain: dicentis
 * Domain Path: /languages/
 * GitHub Plugin URI: https://github.com/obstschale/dicentis-podcast
 *
 * Copyright 2014 Hans-Helge Buerger (http://hanshelgebuerger.de)
 * License: GPL (http://www.gnu.org/licenses/old-licenses/gpl-3.0.txt)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once __DIR__ . '/dicentis-define.php';

require_once DIPO_INC_DIR . '/controller/series.php';
require_once DIPO_INC_DIR . '/controller/speaker.php';
require_once DIPO_INC_DIR . '/controller/show.php';
require_once DIPO_LIB_DIR . '/tgm-plugin.php';
require_once DIPO_LIB_DIR . '/dipo-version.php';

function dipo_load_plugin() {

	$path = plugin_dir_path( __FILE__ );

	if ( ! class_exists( 'Dipo_Load_Controller' ) ) {
		require $path . 'includes/autoload/class-dipo-load-controller.php';
		$loader = new \Dicentis\Autoload\Dipo_Load_Controller( $path . 'includes' );
	}

	if ( ! class_exists( 'Dicentis_Podcast' ) ) {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-dicentis-podcast.php';
		$dipo = new \Dicentis\Dicentis_Podcast();
		$dipo->run();
	}

}
dipo_load_plugin();
