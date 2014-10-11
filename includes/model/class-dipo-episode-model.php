<?php

namespace Dicentis\Podcast_Post_Type;

class Dipo_Episode_Model {

	public function get_all_audio_files( $id = -1 ) {
		if ( -1 == $id ) {
			return false;
		}

		$files = $this->get_all_episodes_mediafiles( $id );

		// Remove all non-audio files from array
		foreach ( $files as $index => $file ) {
			if ( ! $this->is_audio( $file['mediatype'] ) ) {
				unset( $files[$index] );
			}
		}

		return $files;
	}

	public function get_all_video_files( $id = -1 ) {
		if ( -1 == $id ) {
			return false;
		}

		$files = $this->get_all_episodes_mediafiles( $id );

		// Remove all non-video files from array
		foreach ( $files as $index => $file ) {
			if ( ! $this->is_video( $file['mediatype'] ) ) {
				unset( $files[$index] );
			}
		}

		return $files;
	}

	public function get_all_episodes_mediafiles( $id = -1 ) {
		if ( -1 == $id ) {
			return false;
		}

		$max_mediafiles = get_post_meta( $id, '_dipo_max_mediafile_number', true );
		$files = array();

		for ( $i = 1; $i <= $max_mediafiles; $i++ ) {
			$field_name = '_dipo_mediafile' . $i;
			$file = get_post_meta( $id, $field_name, true );
			if ( ! empty($file) ) {
				array_push( $files, $file );
			}
		}

		return $files;
	}

	public function get_episodes_mediafile( $id = -1, $type = 'audio/mpeg' ) {
		if ( -1 == $id ) {
			return false;
		}

		$max_mediafiles = get_post_meta( $id, '_dipo_max_mediafile_number', true );

		for ( $i = 1; $i <= $max_mediafiles; $i++ ) {
			$field_name = '_dipo_mediafile' . $i;
			$file = get_post_meta( $id, $field_name, true );
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
}