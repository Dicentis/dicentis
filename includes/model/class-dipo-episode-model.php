<?php

namespace Dicentis\Podcast_Post_Type;

use Dicentis\Core;

class Dipo_Episode_Model {

	private $properties;
	private $episode_id = null;
	private $episode_meta = null;

	public function __construct() {
		$this->properties = Core\Dipo_Property_List::get_instance();
	}

	public function set_episode_id( $id ) {
		$this->episode_id = $id;
		$this->update();
	}

	public function get_episode_id() {
		return $this->episode_id;
	}

	public function set_episode_meta( $meta ) {
		$this->episode_meta = $meta;
	}

	public function get_episode_meta() {
		return $this->episode_meta;
	}

	public function get_meta_by_key( $key ) {
		if ( '' == $key || !isset( $key ) ) {
			return '';
		} else {
			return $this->get_episode_meta()[$key][0];
		}
	}

	public function update() {
		$episode_meta = get_post_meta( $this->get_episode_id() );
		$this->set_episode_meta( $episode_meta );
	}

	public function get_all_audio_files() {

		$files = $this->get_all_episodes_mediafiles( $this->get_episode_id() );

		// Remove all non-audio files from array
		foreach ( $files as $index => $file ) {
			if ( ! $this->is_audio( $file['mediatype'] ) ) {
				unset( $files[$index] );
			}
		}

		return $files;
	}

	public function get_all_video_files() {

		$files = $this->get_all_episodes_mediafiles( $this->get_episode_id() );

		// Remove all non-video files from array
		foreach ( $files as $index => $file ) {
			if ( ! $this->is_video( $file['mediatype'] ) ) {
				unset( $files[$index] );
			}
		}

		return $files;
	}

	public function get_all_episodes_mediafiles() {

		$max_mediafiles = $this->get_meta_by_key( '_dipo_max_mediafile_number' );
		$files = array();

		for ( $i = 1; $i <= $max_mediafiles; $i++ ) {
			$field_name = '_dipo_mediafile' . $i;
			$file = get_post_meta( $this->get_episode_id(), $field_name, true );
			if ( ! empty($file) ) {
				array_push( $files, $file );
			}
		}

		return $files;
	}

	public function get_episodes_mediafile( $type = '' ) {

		if ( !isset( $type ) || empty( $type) ) {
			$type = $this->get_mediatype();
			$ext  = $this->get_file_extensions();
			foreach ($ext as $key => $value) {
				if ( $type == $value ) {
					$type = $key;
				}
			}
		}

		$max_mediafiles = $this->get_meta_by_key( '_dipo_max_mediafile_number' );

		for ( $i = 1; $i <= $max_mediafiles; $i++ ) {
			$field_name = '_dipo_mediafile' . $i;
			$file = get_post_meta( $this->get_episode_id(),  $field_name, true );
			if ( ! empty($file) and $type == $file['mediatype'] ) {
				return $file;
			}
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

	public function is_audio( $mime ) {
		$audio_mime = array(
			'audio/mpeg',
			'audio/mp4',
			'audio/ogg',
			'audio/webm',
			'audio/flac',
			'audio/ogg;codecs=opus',
			'audio/x-matroska',
		);

		if ( in_array( $mime, $audio_mime ) ) {
			return true;
		} else {
			return false;
		}
	}

	public function is_video( $mime ) {
		$audio_mime = array(
			'video/mpeg',
			'video/mp4',
			'video/x-m4v',
			'video/ogg',
			'video/webm',
			'video/x-matroska',
		);

		if ( in_array( $mime, $audio_mime ) ) {
			return true;
		} else {
			return false;
		}
	}


	public function get_mediatype() {
		if ( isset( $_GET['feed'] ) ) {
			return esc_attr( $_GET['feed'] );
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

	public function exists_mediafile() {
		$mf = $this->get_episodes_mediafile();
		return ( isset( $mf ) ) ? true : false;
	}

	public function get_speaker() {
		$text = '';

		$terms = get_the_terms( $this->get_episode_id() , 'podcast_speaker' );

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

	public function episode_has_keywords() {
		$tag_count = wp_get_post_tags( $this->get_episode_id(), array( 'fields' => 'names' ) );
		return ( 0 < count( $tag_count ) ) ? true : false;
	}



	public function get_episodes_keywords() {
		$i = 0;
		$tags_string = '';
		$tags = wp_get_post_tags( $this->get_episode_id(), array( 'fields' => 'names' ) );

		foreach ( $tags as $key => $value ) {
			$tags_string .= $value;

			if ( ++$i !== count( $tags ) ) {
				$tags_string .= ', ';
			}
		}

		return $tags_string;
	}

}
