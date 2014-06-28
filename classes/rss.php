<?php

include_once plugin_dir_path( __FILE__ ) . '../dicentis-define.php';

if( !class_exists( 'RSS' ) ) {
	/**
	 * RSS Class
	 */
	class RSS {

		private $feed_template;
		public  $itunes_opt;

		function __construct() {
			$this->feed_template = DIPO_TEMPLATES_DIR . '/feed-itunes.php';
		}

		public function generate_podcast_feed() {
			global $wp_query;

			if ( $wp_query->is_comment_feed() )
				load_template( ABSPATH . WPINC . '/feed-rss2-comments.php');
			else if ( $this->is_podcast_feed() ) {
				// load rss template and exit afterwards to exclude html code
				load_template( $this->get_feed_template() );
				exit();
			}
		}

		public function is_podcast_feed() {
			$get_array = array( 'podcast', 'itunes', 'rss', 'rss2' );
			if ( isset( $_GET['post_type'] )
				 and isset( $_GET['feed'] )
				 and in_array($_GET['post_type'], $get_array )
				 and in_array($_GET['feed'], $get_array )
				) {
				return true;
			} else {
				return false;
			}
		}

		public function get_feed_template() {
			return $this->feed_template;
		}

		public function get_itunes_options() {
			$this->itunes_opt = get_option( 'dipo_itunes_options' );
		}

		public function get_show_details( $type = 'name' ) {
			$slug = '';

			if ( isset( $_GET['podcast_show'] ) ):
				$slug = $_GET['podcast_show'];

				switch ( $type ):
					case 'name':
						$value = get_term_by( 'slug', $slug, 'podcast_show')->name;
						echo " > " . $value;
					break;

					case 'description':
						echo get_term_by( 'slug', $slug, 'podcast_show')->description;
					break;

					default:
						echo "";
				endswitch;
			endif;
		}

		public function get_speaker( $id ) {
			$text = "";

			$terms = get_the_terms( $id , 'podcast_speaker' );

			if ( !is_wp_error( $terms ) and $terms ):
				$count = 1;
				foreach ( $terms as $term ):
					$text .= $term->name;
					if ( count( $terms ) > $count ) {
						$text .= ", ";
						$count++;
					}
				endforeach;
			endif;

			return $text;
		}

		public function print_itunes_categories() {
			require_once DIPO_LIB_DIR . '/itunes-categories.php';

			$podcast_category1 = "";
			$podcast_category2 = "\t";
			$podcast_category3 = "\t";

			foreach ( $cats as $catname => $subcats ) :
				$catvalue = strtolower( $catname );
				$cat_text = htmlspecialchars( $catname );

				if ( $this->itunes_opt['itunes_category1'] == $catvalue )
					$podcast_category1 .= "<itunes:category text='$cat_text' />\n";
				else if ( $this->itunes_opt['itunes_category2'] == $catvalue )
					$podcast_category2 .= "<itunes:category text='$cat_text' />\n";
				else if ( $this->itunes_opt['itunes_category3'] == $catvalue )
					$podcast_category3 .= "<itunes:category text='$cat_text' />\n";
				else {
					foreach ($subcats as $subcat => $subcatname) :
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
							$podcast_category3 .= "</itunes:category>";
						}
					endforeach;
				}
			endforeach;

			echo $podcast_category1;
			echo $podcast_category2;
			echo $podcast_category3;
		}

		public function get_episodes_subtitle( $id = -1 ) {
			return ( -1 == $id ) ? "" : get_post_meta( $id, '_dipo_subtitle', true );
		}

		public function get_episodes_summary( $id = -1 ) {
			return ( -1 == $id ) ? "" : get_post_meta( $id, '_dipo_summary', true );
		}

		public function get_episodes_image( $id = -1 ) {
			return ( -1 == $id ) ? "" : get_post_meta( $id, '_dipo_image', true );
		}

		public function get_episodes_keywords( $id = -1 ) {
			if ( -1 == $id )
				return "";

			$i = 0;
			$tags_string = '';
			$tags = wp_get_post_tags( $id, array( 'fields' => 'names' ) );

			foreach ($tags as $key => $value) {
				$tags_string .= $value;

				if ( ++$i !== count( $tags ) )
					$tags_string .= ", ";
			}

			return $tags_string;
		}

		public function episode_has_keywords( $id = -1 ) {
			if ( -1 == $id )
				return false;

			$tag_count = wp_get_post_tags( $id, array( 'fields' => 'names' ) );
			return ( 0 < count($tag_count) ) ? true : false;
		}

		public function get_episodes_mediafile( $id = -1 ) {
			if ( -1 == $id )
				return false;

			$type = $this->get_mediatype();
			$max_mediafiles = get_post_meta( $id, '_dipo_max_mediafile_number', true );

			for ( $i=1; $i <= $max_mediafiles; $i++ ) { 
				$field_name = '_dipo_mediafile' . $i;
				$file = get_post_meta( $id, $field_name, true );
				if ( !empty($file) and $type == $file['mediatype'] ) {
					return $file;
				}
			}
		}

		public function exists_mediafile( $id = -1 ) {
			if ( -1 == $id )
				return false;

			$file = $this->get_episodes_mediafile( $id );
			return !empty( $file );
		}

		public function get_mediatype() {
			if ( isset( $_GET['type'] ) ) {
				return $_GET['type'];
			} else {

				$extensions = RSS::get_file_extensions();
				$path = explode('/', $_SERVER['REQUEST_URI'] );
				if ( $path[sizeof($path)-1] !== '' )
					$ext = $path[sizeof($path)-1];
				else
					$ext = $path[sizeof($path)-2];

				if ( 'pod' !== $ext ) {
					$mime = array_search( $ext, $extensions );
				}

				return ( $mime ) ? $mime : 'audio/mpeg';
			}
		}

		public static function add_podcast_feed() {
			add_feed( 'pod', 'RSS::do_podcast_feed' );

			$extensions = RSS::get_file_extensions();
			foreach ( $extensions as $mime => $ext ) {
				add_feed( $ext, 'RSS::do_podcast_feed' );
			}
		}

		public static function do_podcast_feed( $in ) {
			load_template( DIPO_TEMPLATES_DIR . '/feed-itunes.php' );
			exit();
		}

		public static function get_file_extensions() {
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
				'image/jpeg' => 'jpg'
			);
		}
	}
}