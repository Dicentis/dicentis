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

	private $model;

	public function __construct( $model ) {
		$this->properties = \Dicentis\Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );
		$this->register_hooks();
		$this->model = $model;
	}

	public function register_hooks() {
		// register hooks for this class
	}

	public function append_media_links( $content ) {
		if ( $GLOBALS['post']->post_type == Dipo_Podcast_Post_Type::POST_TYPE ) {
			$this->enqueue_scripts();

			$content .= '<br>';

			$this->model->set_episode_id( $GLOBALS['post']->ID );

			$mediafiles = $this->model->get_all_audio_files();


			if ( 0 < count( $mediafiles ) ) {
				// Add Audioplayer
				$player = '<audio controls="controls" preload="none" style="width: 100%;">';
				foreach ( $mediafiles as $key => $value ) {
					$ext     = esc_attr( $value['mediatype'] );
					$link    = esc_url( $value['medialink'] );

					$player .= "<source src='{$link}' type='{$ext}'>";

				}
				$player .= '</audio>';
				$content .= $player;
			}

			$mediafiles = $this->model->get_all_video_files();

			if ( 0 < count( $mediafiles ) ) {
				// Add Videoplayer
				$player = '<video controls="controls" preload="none" style="width: 100%;">';
				foreach ( $mediafiles as $key => $value ) {
					$ext     = esc_attr( $value['mediatype'] );
					$link    = esc_url( $value['medialink'] );

					$player .= "<source src='{$link}' type='{$ext}'>";

				}
				$player .= '</video>';
				$content .= $player;
			}

			// Add Download Links
			$mediafiles = $this->model->get_all_episodes_mediafiles();
			$content .= $this->create_download_dropdown( $mediafiles );
		}

		return $content;
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'wp-mediaelement' );
		wp_enqueue_script(
				'dipo_singlepage_js',
				DIPO_ASSETS_URL . '/js/dipo_singlepage.js',
				array( 'jquery' )
			);
		wp_enqueue_style(
				'dipo_singelpage_style',
				DIPO_ASSETS_URL . '/css/dipo_singlepage.css'
			);
	}

	public function create_download_dropdown( $mediafiles ) {
		if ( 1 > count( $mediafiles ) ) {
			return null;
		}
		$dropdown = '<br>';

		$dropdown .= "<div id='dipo_files_js' class='dipo_right'>";
		$dropdown .= "<select id='dipo_file_select' name='dipo_file_select'>";
		foreach ( $mediafiles as $key => $value ) {
			$dropdown .= "<option value={$value['medialink']}>";
			$dropdown .= "{$value['mediatype']}";
			$dropdown .= '</option>';
		}
		$dropdown .= '</select>';
		$first_link = $mediafiles[0]['medialink'];
		$dropdown .= "<a href={$first_link} id='dipo_downlod_btn'>Download</a>";
		$dropdown .= "</div>";

		$dropdown .= "<div id='dipo_files_nojs' class='nojs'>";
		$i = 0;
		foreach ( $mediafiles as $key => $value ) {
			if ( 0 < $i++) {
				$dropdown .= "| ";
			}

			$link = $value['medialink'];
			$type = $value['mediatype'];
			$dropdown .= "<a href={$link} title='Download {$type}'>Download {$type}</a> ";
		}
		$dropdown .= "</div>";

		// Reference of a $value and the last array element remain
		// even after the foreach loop. It is recommended to
		// destroy it by unset().
		unset($value);

		return $dropdown;
	}
}
