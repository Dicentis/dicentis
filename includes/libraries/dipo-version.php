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
			$general = get_option( 'dipo_general_options' );
			$itunes  = get_option( 'dipo_itunes_options' );
			$update = array();
			if ( $general ) {
				foreach ( $general as $key => $value ) {
					if ( 'general_assets_url' == $key ) {
						$update['show_assets_url'] = $value;
					} else {
						$update[$key] = $value;
					}
				}
			}
			if ( $itunes ) {
				foreach ( $itunes as $key => $value ) {
					$update[$key] = $value;
				}
			}
			update_option( 'dipo_all_shows_options', $update );
			delete_option( 'dipo_general_options' );
			delete_option( 'dipo_itunes_options' );
			break;
	}
}
