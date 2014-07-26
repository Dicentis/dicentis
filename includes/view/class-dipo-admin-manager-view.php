<?php

namespace Dicentis\Admin;

use Dicentis\Core;

class Dipo_Admin_Manager_View {

	private $properties;
	private $textdomain;

	public function __construct() {

		$this->properties = Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
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


	/**
	 * @param  [type] $hook [description]
	 * @return [type]       [description]
	 */
	public function load_dashboard_feed_style( $hook ) {

		if ( 'dipo_podcast_page_dicentis_dashboard' !== $hook ) {
			return;
		}

		wp_enqueue_script( 'dipo_dashboard_script',
			DIPO_ASSETS_URL . '/js/dipo_dashboard.js',
			array( 'jquery' ) );
		wp_register_style( 'dipo_dashboard_style',
			DIPO_ASSETS_URL . '/css/dipo_dashboard.css' );
		wp_enqueue_style( 'dipo_dashboard_style' );
	}
}