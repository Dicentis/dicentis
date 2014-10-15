<?php

namespace Dicentis\Frontend;

use Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type;

/**
 * View for Single Page
 *
 * @author Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @since 0.2.2
 * @package Dicentis
 */
class Dipo_Single_Page_View {

	/**
	 * Property object with information for Dicentis
	 * 
	 * @since  0.2.0
	 * @access private
	 * @var Dipo_Property_List $properties includes useful information e.g. textdomain 
	 */
	private $properties;

	/**
	 * Textdomain for this plugin
	 * 
	 * @since  0.1.0
	 * @access private
	 * @var String $textdomain
	 */
	private $textdomain;

	public function __construct() {
		$this->properties = \Dicentis\Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
		$this->register_hooks();
	}

	public function register_hooks() {
		// register hooks for this class
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