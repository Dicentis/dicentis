<?php

namespace Dicentis\Frontend;

use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

/**
* This class will handle the single page of episodes in the frontend
*/
class Dipo_Single_Page {

	private $dipo_properties;

	public function __construct() {
		$this->dipo_properties = \Dicentis\Core\Dipo_Property_List::get_instance();
		$this->register_hooks();
	}

	public function register_hooks() {
		// add_filter( 'single_template', array( $this, 'single_template' ) );
		add_filter( 'the_content', array( $this, 'append_media_links' ) );
	}

	public function single_template( $single_template ) {
		global $post;

		if ( $post->post_type == Dipo_Podcast_Post_Type::POST_TYPE ) {
			// $single_template = dirname( __FILE__ ) . '/templates/episode-single-template.php';
		}

		return $single_template;
	}

	public function append_media_links( $content ) {
		if ( $GLOBALS['post']->post_type == Dipo_Podcast_Post_Type::POST_TYPE ) {
			$content .= '<br>';

			// $rss_model = new \Dicentis\Feed\Dipo_RSS_Model();
			$rss_model = new \Dicentis\Podcast_Post_Type\Dipo_Episode_Model();
			$mediafiles = $rss_model->get_all_episodes_mediafiles( $GLOBALS['post']->ID );

			$separator = false;
			foreach ( $mediafiles as $key => $value ) {

				if ( $separator ) {
					$content .= '&nbsp;|&nbsp;';
				} else {
					$separator = true;
				}

				$ext     = esc_attr( $value['mediatype'] );
				$link    = esc_url( $value['medialink'] );

				$content .= "<a href='{$link}' title='Download {$ext}'>";
				$content .= "Download {$ext}";
				$content .= '</a>';
			}
			// Reference of a $value and the last array element remain
			// even after the foreach loop. It is recommended to
			// destroy it by unset().
			unset($value);
		}

		return $content;
	}

}