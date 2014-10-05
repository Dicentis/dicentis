<?php

namespace Dicentis\Feed;

use Dicentis\Core;

/**
 *
 * @author Hans-Helge Buerger <mail@hanshelgebuerger.de>
 * @version 0.2.0
 */
class Dipo_RSS_Model {

	private $properties;
	private $itunes_opt;

	public function __construct() {
		$this->properties = Core\Dipo_Property_List::get_instance();
	}

	public function get_feed_template() {
		return $this->properties->get( 'dipo_templates' ) . '/feed-itunes-template.php';
	}

	public function get_itunes_options() {
		if ( ! isset( $this->itunes_opt ) ) {
			$this->itunes_opt = get_option( 'dipo_itunes_options' );
		}

		return $this->itunes_opt;
	}

	public function get_show_details( $type = 'name' ) {
		$slug = '';

		if ( isset( $_GET['podcast_show'] ) ) {
			$slug = esc_attr( $_GET['podcast_show'] );

			switch ( $type ) {
				case 'name':
					$value = get_term_by( 'slug', $slug, 'podcast_show' )->name;
					echo ' > ' . esc_html( $value );
				break;

				case 'description':
					echo esc_html( get_term_by( 'slug', $slug, 'podcast_show' )->description );
				break;

				default:
					echo '';
			}
		} else {
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				$request_uri = esc_url( $_SERVER['REQUEST_URI'] );
			} else {
				$request_uri = '';
			}
			$path = explode( '/', $request_uri );

			if ( $path[sizeof( $path ) - 1] !== '' ) {
				$ext = $path[sizeof( $path ) - 1];
			} else {
				$ext = $path[sizeof( $path ) - 2];
			}

			$index_show = array_search( 'show', $path );

			if ( false == $index_show ) {
				echo '';
				return false;
			}

			$value = get_term_by( 'slug', $path[$index_show + 1], 'podcast_show' )->name;

			echo ' > ' . esc_html( $value );
		}
	}

	public function get_option_by_key( $key ) {

		$options = $this->get_itunes_options();

		if ( isset( $options[$key] ) ) {
			return $options[$key];
		}

		return '';
	}

	public function print_itunes_categories() {
		require_once DIPO_LIB_DIR . '/itunes-categories.php';

		$podcast_category1 = '';
		$podcast_category2 = "\t";
		$podcast_category3 = "\t";

		foreach ( $cats as $catname => $subcats ) :
			$catvalue = strtolower( $catname );
			$cat_text = htmlspecialchars( $catname );

			if ( $this->itunes_opt['itunes_category1'] == $catvalue ) {
				$podcast_category1 .= "<itunes:category text='$cat_text' />\n";
			} else if ( $this->itunes_opt['itunes_category2'] == $catvalue ) {
				$podcast_category2 .= "<itunes:category text='$cat_text' />\n";
			} else if ( $this->itunes_opt['itunes_category3'] == $catvalue ) {
				$podcast_category3 .= "<itunes:category text='$cat_text' />\n";
			} else {
				foreach ( $subcats as $subcat => $subcatname ) :
					$subcatvalue = strtolower( $subcatname );
					$parent = htmlspecialchars( $catname );
					$child = htmlspecialchars( $subcatname );

					if ( $this->itunes_opt['itunes_category1'] == $subcatvalue ) {
						$podcast_category1 .= "<itunes:category text='$parent'>";
						$podcast_category1 .= "<itunes:category text='$child' />";
						$podcast_category1 .= "</itunes:category>\n";
					} else if ( $this->itunes_opt['itunes_category2'] == $subcatvalue ) {
						$podcast_category2 .= "<itunes:category text='$parent'>";
						$podcast_category2 .= "<itunes:category text='$child' />";
						$podcast_category2 .= "</itunes:category>\n";
					} else if ( $this->itunes_opt['itunes_category3'] == $subcatvalue ) {
						$podcast_category3 .= "<itunes:category text='$parent'>";
						$podcast_category3 .= "<itunes:category text='$child' />";
						$podcast_category3 .= '</itunes:category>';
					}
				endforeach;
			}
		endforeach;

		echo $podcast_category1;
		echo $podcast_category2;
		echo $podcast_category3;
	}

	public function get_mediatype() {
		if ( isset( $_GET['type'] ) ) {
			return esc_attr( $_GET['type'] );
		} else {

			$extensions = $this->get_file_extensions();
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				$request_uri = esc_attr( $_SERVER['REQUEST_URI'] );
			} else {
				$request_uri = '';
			}
			$path = explode( '/', $request_uri );
			if ( $path[sizeof( $path ) - 1] !== '' ) {
				$ext = $path[sizeof( $path ) - 1];
			} else {
				$ext = $path[sizeof( $path ) - 2];
			}

			$mime = null;
			if ( 'pod' !== $ext ) {
				$mime = array_search( $ext, $extensions );
			}

			return ( $mime ) ? $mime : 'audio/mpeg';
		}
	}


	public function get_file_extensions() {
		return $ext = array(
			'audio/mpeg' => 'mp3',
			'application/x-bittorrent' => 'mp3.torrent',
			'video/mpeg' => 'mpg',
			'audio/mp4' => 'm4a',
			'audio/mp4' => 'm4a',
			'video/mp4' => 'mp4',
			'video/x-m4v' => 'm4v',
			'audio/ogg' => 'oga',
			'audio/ogg' => 'ogg',
			'video/ogg' => 'ogv',
			'audio/webm' => 'webm',
			'video/webm' => 'webm',
			'audio/flac' => 'flac',
			'audio/ogg;codecs=opus' => 'opus',
			'audio/x-matroska' => 'mka',
			'video/x-matroska' => 'mkv',
			'application/pdf' => 'pdf',
			'application/epub+zip' => 'epub',
			'image/png' => 'png',
			'image/jpeg' => 'jpg',
			);
	}

	public function get_episodes_mediafile( $id = -1 ) {
		if ( -1 == $id ) {
			return false;
		}

		$type = $this->get_mediatype();
		$max_mediafiles = get_post_meta( $id, '_dipo_max_mediafile_number', true );

		for ( $i = 1; $i <= $max_mediafiles; $i++ ) {
			$field_name = '_dipo_mediafile' . $i;
			$file = get_post_meta( $id, $field_name, true );
			if ( ! empty($file) and $type == $file['mediatype'] ) {
				return $file;
			}
		}
	}

	public function exists_mediafile( $id = -1 ) {
		if ( -1 == $id ) {
			return false;
		}

		$file = $this->get_episodes_mediafile( $id );
		return ! empty( $file );
	}

	public function get_speaker( $id ) {
		$text = '';

		$terms = get_the_terms( $id , 'podcast_speaker' );

		if ( ! is_wp_error( $terms ) and $terms ) {
			$count = 1;
			foreach ( $terms as $term ) {
				$text .= $term->name;
				if ( count( $terms ) > $count ) {
					$text .= ', ';
					$count++;
				}
			}
		}

		return $text;
	}

	public function get_episodes_subtitle( $id = -1 ) {
		return ( -1 == $id ) ? '' : get_post_meta( $id, '_dipo_subtitle', true );
	}

	public function get_episodes_summary( $id = -1 ) {
		return ( -1 == $id ) ? '' : get_post_meta( $id, '_dipo_summary', true );
	}

	public function get_episodes_image( $id = -1 ) {
		return ( -1 == $id ) ? '' : get_post_meta( $id, '_dipo_image', true );
	}

	public function get_episodes_keywords( $id = -1 ) {
		if ( -1 == $id ) {
			return '';
		}

		$i = 0;
		$tags_string = '';
		$tags = wp_get_post_tags( $id, array( 'fields' => 'names' ) );

		foreach ( $tags as $key => $value ) {
			$tags_string .= $value;

			if ( ++$i !== count( $tags ) ) {
				$tags_string .= ', ';
			}
		}

		return $tags_string;
	}

	public function episode_has_keywords( $id = -1 ) {
		if ( -1 == $id ) {
			return false;
		}

		$tag_count = wp_get_post_tags( $id, array( 'fields' => 'names' ) );
		return ( 0 < count( $tag_count ) ) ? true : false;
	}

	public function get_cover_art() {
		$coverart = $this->get_option_by_key( 'itunes_coverart' );

		if ( ! isset( $coverart ) || empty( $coverart ) ) {
			// @TODO: double dirname. Can this be refactored?
			$coverart = plugins_url( 'assets/img/cover-art.jpg' , dirname( dirname( __FILE__ ) ) );
		}

		return $coverart;
	}

}