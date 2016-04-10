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
 * @author  Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @version 0.2.0
 */
class Dipo_RSS_Controller {

	public $itunes_opt;
	protected $properties;
	private $model;
	private $view;

	public function __construct() {

		$this->model      = new Dipo_RSS_Model();
		$this->view       = new Dipo_RSS_View();
		$this->properties = Core\Dipo_Property_List::get_instance();

	}

	/**
	 * Check template for podcast feed. If feed with file extension is used without a podcast_show redirect to home page.
	 */
	public function generate_podcast_feed() {

		// Check if custom feed extension are used only in combination with a podcast_show
		switch ( $this->is_podcast_feed() ) {
			case 'no_show_defined':
				wp_redirect( home_url() );
				exit();
				break;

			default:
				// Just forward
		}
	}

	/**
	 * Method to see if a template is a valid podcast feed, normal feed or no feed at all.
	 *
	 * @return string indicator of template
	 */
	public function is_podcast_feed() {
		global $wp_query;


		if ( isset( $wp_query->query['feed'] ) ) {

			if ( ! isset( $wp_query->query['podcast_show'] ) ) {

				$extensions = $this->model->get_file_extensions();
				if ( ! in_array( $wp_query->query['feed'], $extensions ) ) {
					return 'no_podcast_feed';
				}

				return 'no_show_defined';
			}

			return 'is_podcast_feed';
		} else {
			return 'is_not_feed';
		}
	}

	public function add_podcast_feeds() {
		add_feed( 'pod', array( $this, 'do_podcast_feed' ) );

		$extensions = $this->model->get_file_extensions();
		foreach ( $extensions as $mime => $ext ) {
			add_feed( $ext, array( $this, 'do_podcast_feed' ) );
		}
	}

	public function do_podcast_feed() {

		$feed = $this->model->get_feed_template();
		$this->view->do_podcast_feed( $feed );

	}

}
