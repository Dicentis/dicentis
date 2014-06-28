<?php

add_action( 'init', function () {

	$database_version = get_option( 'dipo_db_version' );

	if ( $database_version === false ) {
		// plugin has just been installed
		update_option( 'dipo_db_version', DIPO_DB_Version );
	} elseif ( $database_version < DIPO_DB_Version ) {
		// run one or multiple migrations
		for ( $i = $database_version+1; $i <= DIPO_DB_Version; $i++ ) { 
			dipo_update_database( $i );
			update_option( 'dipo_db_version', $i );
		}
	}
} );

function dipo_update_database( $version ) {
	global $wpdb;

	switch ( $version ) {
		case 2:
			break;
	}
}