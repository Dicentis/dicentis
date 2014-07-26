<?php

namespace Dicentis\Admin;

use Dicentis\Core;

class Dipo_Admin_Manager_View {

	private $properties;

	public function __construct() {
		$this->properties = Core\Dipo_Property_List::get_instance();
	}

	public function render_dashboard_page() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$show_feeds = array();
		$show_terms = get_terms( dipo_get_podcast_show_slug() );
		foreach ( $show_terms as $show_index => $show ) {

			$show_feed = trailingslashit( get_home_url() )
				. '?post_type=' . \Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type::POST_TYPE
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
		include( $this->properties->get( 'dipo_templates' ) . '/dashboard-template.php' );
	} // END public function render_dashboard_page()
}