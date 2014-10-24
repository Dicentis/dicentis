<?php

namespace Dicentis\Frontend;

use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

/**
* This class will handle the single page of episodes in the frontend
*/
class Dipo_Single_Page {

	private $view;
	private $properties;
	private $textdomain;

	public function __construct() {
		$this->properties = \Dicentis\Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );

		$rss_model = new \Dicentis\Podcast_Post_Type\Dipo_Episode_Model();
		$this->view       = new Dipo_Single_Page_View( $rss_model );

		$this->register_hooks();
	}

	public function register_hooks() {
		// add_filter( 'single_template', array( $this, 'single_template' ) );
		add_filter( 'the_content', array( $this->view, 'append_media_links' ) );
	}

	public function single_template( $single_template ) {
		global $post;

		if ( $post->post_type == Dipo_Podcast_Post_Type::POST_TYPE ) {
			// $single_template = dirname( __FILE__ ) . '/templates/episode-single-template.php';
		}

		return $single_template;
	}

}