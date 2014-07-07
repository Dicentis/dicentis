<?php

namespace Dicentis\Core;

use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

class Dipo_Admin_Manager {
	/**
	 * Hook into WP's admin_init hook and do some admin stuff
	 * 		1. reorder Podcast's submenu
	 */
	public function admin_init() {
		$this->menu_order();
	} // END public function admin_init()

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
	} // END public function menu_order()

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
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$show_feeds = array();
		$show_terms = get_terms( dipo_get_podcast_show_slug() );
		foreach ( $show_terms as $show_index => $show ) {

			$show_feed = trailingslashit( get_home_url() )
				. '?post_type=' . Dipo_Podcast_Post_Type::POST_TYPE
				. '&podcast_show=' . $show->slug
				. '&feed=pod';

			$show_pretty_feed = trailingslashit( get_home_url() )
				. 'podcast/show/' . $show->slug
				. '/feed/pod';

			$show_array = array(
				'name' => $show->name,
				'slug' => $show->slug,
				'feed' => $show_feed,
				'pretty_feed' => $show_pretty_feed,
			);

			array_push( $show_feeds, $show_array );
		}

		// Render the dashboard template
		include( dirname( __FILE__ ) . '/templates/dashboard-template.php' );
	} // END public function render_dashboard_page()
}