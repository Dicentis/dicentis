<?php

namespace Dicentis\Frontend;

use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

/**
* This class will handle the single page of episodes in the frontend
*/
class Dipo_Single_Page {

	private $properties;
	private $textdomain;

	public function __construct() {
		$this->properties = \Dicentis\Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
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
			$this->enqueue_scripts();

			$content .= '<br>';

			// $rss_model = new \Dicentis\Feed\Dipo_RSS_Model();
			$rss_model = new \Dicentis\Podcast_Post_Type\Dipo_Episode_Model();
			$mediafiles = $rss_model->get_all_audio_files( $GLOBALS['post']->ID );

			// Add Audioplayer
			$player = '<audio controls="controls" preload="none" style="width: 100%;">';
			foreach ( $mediafiles as $key => $value ) {
				$ext     = esc_attr( $value['mediatype'] );
				$link    = esc_url( $value['medialink'] );

				$player .= "<source src='{$link}' type='{$ext}'>";

			}
			$player .= '</audio>';
			$content .= $player;

			// Add Download Links
			$content .= '<br>';
			$separator = false;
			foreach ( $mediafiles as $key => $value ) {

				if ( $separator ) {
					$content .= '&nbsp;|&nbsp;';
				} else {
					$separator = true;
				}

				$ext     = esc_attr( $value['mediatype'] );
				$link    = esc_url( $value['medialink'] );

				$download = __( 'Download', $this->textdomain );
				$content .= "<a href='{$link}' title='{$download} {$ext}'>";
				$content .= "{$download} {$ext}";
				$content .= '</a>';
			}
			// Reference of a $value and the last array element remain
			// even after the foreach loop. It is recommended to
			// destroy it by unset().
			unset($value);
			$content .= '<br>';

			$mediafiles = $rss_model->get_all_video_files( $GLOBALS['post']->ID );

			// Add Audioplayer
			$player = '<video controls="controls" preload="none" style="width: 100%;">';
			foreach ( $mediafiles as $key => $value ) {
				$ext     = esc_attr( $value['mediatype'] );
				$link    = esc_url( $value['medialink'] );

				$player .= "<source src='{$link}' type='{$ext}'>";

			}
			$player .= '</video>';
			$content .= $player;

			// Add Download Links
			$content .= '<br>';
			$separator = false;
			foreach ( $mediafiles as $key => $value ) {

				if ( $separator ) {
					$content .= '&nbsp;|&nbsp;';
				} else {
					$separator = true;
				}

				$ext     = esc_attr( $value['mediatype'] );
				$link    = esc_url( $value['medialink'] );

				$download = __( 'Download', $this->textdomain );
				$content .= "<a href='{$link}' title='{$download} {$ext}'>";
				$content .= "{$download} {$ext}";
				$content .= '</a>';
			}
			// Reference of a $value and the last array element remain
			// even after the foreach loop. It is recommended to
			// destroy it by unset().
			unset($value);
		}

		return $content;
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'wp-mediaelement' );
	}

}