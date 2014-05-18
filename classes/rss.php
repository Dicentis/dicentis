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

			if ( $wp_query->is_comment_feed )
				load_template( ABSPATH . WPINC . '/feed-rss2-comments.php');
			else if ( $this->is_podcast_feed() ) {
				// load rss template and exit afterwards to exclude html code
				load_template( $this->get_feed_template() );
				exit();
			}
		}

		public function is_podcast_feed() {
			$get_array = array( 'podcast', 'itunes', 'rss' );
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

			if ( taxonomy_exists( 'celebration_preachers' ) ):
				$terms = get_the_terms( $id , 'celebration_preachers' );
			else:
				$terms = get_the_terms( $id , 'podcast_speaker' );
			endif;

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
			if ( isset( $_GET['type'] ) )
				return $_GET['type'];
			else
				return 'audio/mpeg';
		}
	}
}