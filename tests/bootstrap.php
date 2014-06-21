<?php
/**
 * Bootstrap the plugin unit testing environment.
 *
 * Edit 'active_plugins' setting below to point to your main plugin file.
 *
 * @package wordpress-plugin-tests
 */

// Activates this plugin in WordPress so it can be tested.
$GLOBALS['wp_tests_options'] = array(
	'active_plugins' => array( 'dicentis-podcast/dicentis-podcast.php' ),
);

// If the develop repo location is defined (as WP_TESTS_DIR), use that
// location. Otherwise, we'll just assume that this plugin is installed in a
// WordPress develop SVN checkout.

if( false !== getenv( 'WP_TESTS_DIR' ) ) {
	require getenv( 'WP_TESTS_DIR' ) . 'includes/bootstrap.php';
} else {
	require '/Users/RetinaObst/Documents/Code/PHP/WordPress/plugins/vagrant-local/www/wordpress-develop/tests/phpunit/includes/bootstrap.php';
}

if (!defined('DIPO_ROOT')) {
	define('DIPO_ROOT', dirname( dirname(__FILE__) ) );
}