<?php
// if uninstall is not called from WordPress exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

/**
 * The first thing is to delete all plugin options from the datebase and
 * delete all custom tables (if created).
 *
 * Removing the plugin files is not my business. WordPress is doing that
 * for me :)
 */

// Delete option from options table
delete_option( 'dicentis_options' );

?>
