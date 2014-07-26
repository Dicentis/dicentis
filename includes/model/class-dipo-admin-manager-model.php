<?php

namespace Dicentis\Admin;

use Dicentis\Core;
use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

class Dipo_Admin_Manager_Model {

	public function prepend_dashboard_link() {
		// $submenu contains the order of the complete menu
		// i.e. top-level menus and submenus
		global $submenu;

		// Look for $find_page and the submenu $find_sub
		$find_page = 'edit.php?post_type=' . Dipo_Podcast_Post_Type::POST_TYPE;
		$find_sub  = 'Dashboard';

		// Loop thru $submenu until $find_page is found
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
	}

	public function add_dashboard_menu( $view ) {

		add_submenu_page(
			'edit.php?post_type=' . Dipo_Podcast_Post_Type::POST_TYPE, // add to podcast menu
			__( 'Dashboard' ),
			__( 'Dashboard' ),
			'edit_posts',
			'dicentis_dashboard',
			array( $view, 'render_dashboard_page' )
		);

	}

}