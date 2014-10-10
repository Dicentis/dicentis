<?php

namespace Dicentis\Podcast_Post_Type;

class Dipo_Episode_Model {

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

}