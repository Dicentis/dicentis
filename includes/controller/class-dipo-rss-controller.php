<?php

namespace Dicentis\Feed;

use \Dicentis\Core;

/**
 * RSS Class for creating Podcast Feeds.
 *
 * This class is responsible for the podcast feeds. It generates them if
 * requested and add new feeds to the WordPress installation. Several methods
 * are implemented as getter methods for episode information which are in
 * the feeds.
 *
 * @author Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @version 0.2.0
 */
class Dipo_RSS_Controller {

	public  $itunes_opt;
	protected $properties;
	private $model;
	private $view;

	public function __construct() {

		$this->model = new Dipo_RSS_Model();
		$this->view  = new Dipo_RSS_View();
		$this->properties = Core\Dipo_Property_List::get_instance();

	}

	public function generate_podcast_feed() {
		global $wp_query;

		if ( $wp_query->is_comment_feed() ) {
			load_template( ABSPATH . WPINC . '/feed-rss2-comments.php' );
		} else if ( $this->is_podcast_feed() ) {
			// load rss template and exit afterwards to exclude html code
			load_template( $this->model->get_feed_template() );
			exit();
		}
	}

	public function is_podcast_feed() {

		$get_array = array( 'podcast', 'itunes', 'rss', 'rss2' );
		if ( isset( $_GET['post_type'] )
				and isset( $_GET['feed'] )
				and in_array( esc_attr( $_GET['post_type'] ), $get_array )
				and in_array( esc_attr( $_GET['feed'] ), $get_array ) ) {

			return true;

		} else {

			return false;

		}
	}

	public function add_podcast_feeds() {
		add_feed( 'pod', array( $this, 'do_podcast_feed' ) );

		$extensions = $this->model->get_file_extensions();
		foreach ( $extensions as $mime => $ext ) {
			add_feed( $ext, array( $this, 'do_podcast_feed' ) );
		}
	}

	public function do_podcast_feed( $in ) {

		$feed = $this->model->get_feed_template();
		$this->view->do_podcast_feed( $feed );

	}

}
