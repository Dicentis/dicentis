<?php
namespace Dicentis\Podcast_Post_Type;

use \Dicentis\Core;

/**
 * The Podcast Post Type
 */
class Dipo_Podcast_Post_Type {

	private $properties;
	private $textdomain;

	const POST_TYPE = 'dipo_podcast';
	const POST_TYPE_NAME = 'podcast';

	private $_meta  = array(
		'_dipo_subtitle',
		'_dipo_summary',
		'_dipo_mediafile',
		'_dipo_image',
		'_dipo_guid',
		'_dipo_duration',
		'_dipo_explicit',
		'_dipo_mediatype',
	);
	/* push each taxonomy name, which is used in this plugin
	 * into this->_tax array. filter_posts() uses this array
	 * to know which taxonomy is used and display filter options
	 * for that
	 */
	private $_tax = array();

	public function __construct() {

		$this->properties = Core\Dipo_Property_List::get_instance();
		$this->textdomain = $this->properties->get( 'textdomain' );

		$this->register_podcast_hooks();

	} // END public function __construct()

	private function register_podcast_hooks() {
		$loader = $this->properties->get( 'hook_loader' );

		$loader->add_action( 'init',
			$this,
			'init' );

		$loader->add_action( 'save_post',
			$this,
			'save_post' );

		$loader->add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column',
			$this,
			'podcast_custom_column',
			10, 2 );

		$loader->add_action( 'admin_menu',
			$this,
			'admin_init' );

		$loader->add_action( 'add_meta_boxes',
			$this,
			'add_meta_boxes' );

		// add additional filter options to podcast site
		$loader->add_action( 'restrict_manage_posts',
			$this,
			'filter_posts' );

		// script & style action with page detection
		$loader->add_action( 'admin_enqueue_scripts',
			$this,
			'load_custom_wp_admin_style' );

		// add taxonomy information for posts as new column
		// manage_podcast_posts_columns
		$loader->add_filter( 'manage_' . self::POST_TYPE . '_posts_columns',
			$this,
			'add_tax_column');
	}

	/**
	 * hook into WP's init action hook
	 */
	public function init() {
		// Initialize Post Type
		$this->register_podcast_post_type();
		$this->register_podcast_taxonomy();

		// register shortcodes
		add_shortcode( 'podcasts', array( $this, 'shortcode_podcast_show' ) );

	} // END public function init()

	/**
	 * Create the post type
	 */
	public function register_podcast_post_type() {
		// set up arguments for podcast post type
		$podcast_args = array(
			'labels' => array(
				'name' => __( 'Podcasts', 'dicentis' ),
				'singular_name' => __( 'Episode', 'dicentis' ),
				'add_new' => __( 'Add New Episode', 'dicentis' ),
				'add_new_item' => __( 'Add New Episode', 'dicentis' ),
				'edit_item' => __( 'Edit Episodes', 'dicentis' ),
				'new_item' => __( 'New Episodes', 'dicentis' ),
				'view_item' => __( 'View Episodes', 'dicentis' ),
				'search_items' => __( 'Search Episodes', 'dicentis' ),
				'not_found' => __( 'No Episodes Found', 'dicentis' ),
				'not_found_in_trash' => __( 'No Episodes Found In Trash', 'dicentis' )
			),
			'public' => true,
			'has_archive' => true,
			'description' => __( 'A podcast plugin which allows to define multipel podcasts with individual feeds', 'dicentis' ),
			'supports' => array(
				'editor',
				'thumbnail',
				'title',
				'comments',
			),
			'rewrite' => array(
				'slug' => 'podcasts',
			),
			 'menu_icon' => 'dashicons-dipo-logo',
			'can_export' => 'true',
			'taxonomies' => array(
				'post_tag'
			),
			// 'capabilities' => array(
			// 	'edit_post' => 'edit_podcast',
			// 	'edit_posts' => 'edit_podcasts',
			// 	'edit_other_posts' => 'edit_other_podcasts',
			// 	'publish_post' => 'publish_podcast',
			// 	'read_post' => 'read_podcast',
			// 	'read_private_posts' => 'read_private_podcast',
			// 	'delete_post' => 'delete_podcast',
			// ),
		);

		// Register the dicentis podcast post type
		if ( post_type_exists( self::POST_TYPE ) ) {
			/* @TODO: show admin notice */
			// don't register post type b/c there already exists one
			// with the same name.
		} else {
			register_post_type( self::POST_TYPE, $podcast_args );
		}
	} // END public function register_podcast_post_type()

/**
/$$$$$$$$
|__  $$__/
| $$  /$$$$$$  /$$   /$$  /$$$$$$  /$$$$$$$   /$$$$$$  /$$$$$$/$$$$  /$$   /$$
| $$ |____  $$|  $$ /$$/ /$$__  $$| $$__  $$ /$$__  $$| $$_  $$_  $$| $$  | $$
| $$  /$$$$$$$ \  $$$$/ | $$  \ $$| $$  \ $$| $$  \ $$| $$ \ $$ \ $$| $$  | $$
| $$ /$$__  $$  >$$  $$ | $$  | $$| $$  | $$| $$  | $$| $$ | $$ | $$| $$  | $$
| $$|  $$$$$$$ /$$/\  $$|  $$$$$$/| $$  | $$|  $$$$$$/| $$ | $$ | $$|  $$$$$$$
|__/ \_______/|__/  \__/ \______/ |__/  |__/ \______/ |__/ |__/ |__/ \____  $$
																	/$$  | $$
																   |  $$$$$$/
																	\______/
**/
	/**
	 * creates custom taxonomies for categorizing podcasts
	 * in series
	 * @link http://wordpress.stackexchange.com/questions/32934/removing-taxonomy-base-using-wp-rewrite
	 */
	public function register_podcast_taxonomy() {

		$podcast_show_args = array(
			'hierarchical' => true,
			'query_var' => 'podcast_show',
			'show_ui' => true,
			'show_tagcloud' => false,
			'rewrite' => array(
				'slug' => self::POST_TYPE_NAME . '/show',
			),
			'labels' => array(
				'name' => __( 'Podcast Shows', $this->textdomain ),
				'singular_name' => __( 'Podcast Show', $this->textdomain ),
				'edit_item' => __( 'Edit Podcast Show', $this->textdomain ),
				'update_item' => __( 'Update Podcast Show', $this->textdomain ),
				'add_new_item' => __( 'Add New Podcast Show', $this->textdomain ),
				'new_item_name' => __( 'New Podcast Show Name', $this->textdomain ),
				'all_items' => __( 'All Podcast Shows', $this->textdomain ),
				'search_items' => __( 'Search Podcast Show', $this->textdomain ),
				'parent_item' => __( 'Parent Podcast Show', $this->textdomain ),
				'parent_item_colon' => __( 'Parent Podcast Show:', $this->textdomain ),
			),
		);

		// Set up the series taxonomy
		$series_args = array(
			'hierarchical' => true,
			'query_var' => 'podcast_series',
			'rewrite' => array(
				'slug' => self::POST_TYPE_NAME . '/series',
			),
			'labels' => array(
				'name' => __( 'Series', $this->textdomain ),
				'singular_name' => __( 'Series', $this->textdomain ),
				'edit_item' => __( 'Edit Series', $this->textdomain ),
				'update_item' => __( 'Update Series', $this->textdomain ),
				'add_new_item' => __( 'Add New Series', $this->textdomain ),
				'new_item_name' => __( 'New Series Name', $this->textdomain ),
				'all_items' => __( 'All Series', $this->textdomain ),
				'search_items' => __( 'Search Series', $this->textdomain ),
				'parent_item' => __( 'Parent Series', $this->textdomain ),
				'parent_item_colon' => __( 'Parent Series:', $this->textdomain ),
			),
		);

		// Set up the speaker taxonomy
		$speaker_args = array(
			'hierarchical' => true,
			'query_var' => 'podcast_speaker',
			'rewrite' => array(
				'slug' => self::POST_TYPE_NAME . '/speaker',
			),
			'labels' => array(
				'name' => __( 'Speakers', $this->textdomain ),
				'singular_name' => __( 'Speaker', $this->textdomain ),
				'edit_item' => __( 'Edit Speaker', $this->textdomain ),
				'update_item' => __( 'Update Speaker', $this->textdomain ),
				'add_new_item' => __( 'Add New Speaker', $this->textdomain ),
				'new_item_name' => __( 'New Speaker Name', $this->textdomain ),
				'all_items' => __( 'All Speaker', $this->textdomain ),
				'search_items' => __( 'Search Speaker', $this->textdomain ),
				'parent_item' => __( 'Parent Speaker', $this->textdomain ),
				'parent_item_colon' => __( 'Parent Speaker:', $this->textdomain ),
			),
		);

		/* push each taxonomy name, which is used in this plugin
		 * into this->_tax array. filter_posts() uses this array
		 * to know which taxonomy is used and display filter options
		 * for that
		 */

		// register show taxonomy
		register_taxonomy( 'podcast_show', array( self::POST_TYPE ), $podcast_show_args );
		$the_tax = get_taxonomy( 'podcast_show' );
		$this->_tax['podcast_show'] = $the_tax->labels->name;

		// register series taxonomy
		register_taxonomy( 'podcast_series', array( self::POST_TYPE ), $series_args );
		$the_tax = get_taxonomy( 'podcast_series' );
		$this->_tax['podcast_series'] = $the_tax->labels->name;

		// register speaker taxonomy
		register_taxonomy( 'podcast_speaker', array( self::POST_TYPE ), $speaker_args );
		$the_tax = get_taxonomy( 'podcast_speaker' );
		$this->_tax['podcast_speaker'] = $the_tax->labels->name;

	} // END public function register_podcast_taxonomy()

	/**
	 * add additional filter options to post type site for each
	 * taxonomy which is used for this plugin
	 */
	public function filter_posts() {
		global $typenow;

		if( self::POST_TYPE == $typenow ){

			/* push each taxonomy name, which is used in this plugin
			 * into this->_tax array. filter_posts() uses this array
			 * to know which taxonomy is used and display filter options
			 * for that
			 */
			foreach ( $this->_tax as $tax_slug => $tax_name ) {
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);

				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Show All $tax_name</option>";

				foreach ( $terms as $term ) {
					$selected = '';
					if ( isset( $_GET[$tax_slug] ) ) {
						if ( $_GET[$tax_slug] == $term->slug )
							$selected = ' selected="selected"';
					}

					echo '<option value='. $term->slug, $selected,'>' . $term->name .' (' . $term->count .')</option>';
				}

				echo "</select>";
			}
		}
	}

	/**
	 * Save the metaboxes for this custom post type
	 * @param  [type] $post_id [description]
	 * @return [type]          [description]
	 */
	public function save_post( $post_id ) {
		// verify if this is an auto save routine
		// If it is our form has not been submitted, so we don't want to do anything
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		if( isset( $_POST['post_type'] ) && $_POST['post_type'] == self::POST_TYPE && current_user_can( 'edit_post', $post_id ) ):

			// get option 'dipo_general_options'
			$general_options = get_option( 'dipo_general_options' );
			$asset_prefix = ( isset( $general_options['general_assets_url'] ) ) ? $general_options['general_assets_url'] : '' ;

			$this->save_mediafiles_of_post( $post_id );

			foreach ($this->_meta as $field_name):
				// update the post's meta field
				switch ( $field_name ):
					case '_dipo_image':
						if ( isset( $_POST[ 'dipo_image' ] ) ) {
							$dipo_image = $_POST[ 'dipo_image' ];
						} else {
							$dipo_image = "";
						}

						update_post_meta( $post_id, $field_name, esc_url_raw( $dipo_image ) );
						break;

					default:
						if ( isset( $_POST[substr($field_name, 1)] ) ) {
							update_post_meta( $post_id, $field_name, $_POST[substr($field_name, 1)] );
						}
						break;
				endswitch;
			endforeach;
		else:
			return;
		endif; // if( $_POST['post_type'] == self::POST_TYPE && current_user_can( 'edit_post', $post_id ) )
	} // END public function save_post( $post_id )

	public function save_mediafiles_of_post( $post_id ) {
		/*
		 * 1. Check max number of mediafiles
		 * 2. remove all mediafiles from DB with numbern
		 * 3. look what to do for each mediafile (update|remove)
		 * 4. if update: create array and update (starting from 1)
		*/

		$max_number  = 0;
		$media_count = 0;
		if ( isset( $_POST['dipo_mediafiles_count'] ) )
			$max_number = $_POST['dipo_mediafiles_count'];
		else
			return;

		for ( $i=1; $i <= $max_number; $i++ ) {
			$next_file = 'dipo_mediafile' . $i;
			delete_post_meta( $post_id, '_' . $next_file );

			if ( isset( $_POST[$next_file] ) and "update" == $_POST[$next_file] ) {
				$media_count++;
				$medialink = ( isset($_POST[$next_file . "_link"]) ) ? esc_url_raw( $_POST[$next_file . "_link"] ): "";
				$mediatype = ( isset($_POST[$next_file . "_type"]) ) ? htmlspecialchars( $_POST[$next_file . "_type"] ): "";
				$duration  = ( isset($_POST[$next_file . "_duration"]) ) ? htmlspecialchars( $_POST[$next_file . "_duration"] ): "";
				$filesize  = ( isset($_POST[$next_file . "_size"]) ) ? htmlspecialchars( $_POST[$next_file . "_size"] ): "";

				if ( empty($filesize) ) {
					$filesize = $this->curl_get_file_size( $medialink );
				}

				$mediafile = array(
					'id'        => $media_count,
					'medialink' => $medialink,
					'mediatype' => $mediatype,
					'duration'  => $duration,
					'filesize'  => $filesize );

				$field_name = '_dipo_mediafile' . $media_count;
				update_post_meta( $post_id, $field_name, $mediafile );
			}
		}
		update_post_meta( $post_id, "_dipo_max_mediafile_number", $media_count );
	}

	/**
	 * Returns the size of a file without downloading it, or -1 if the file
	 * size could not be determined.
	 *
	 * @param $url - The location of the remote file to download. Cannot
	 * be null or empty.
	 *
	 * @return The size of the file referenced by $url, or -1 if the size
	 * could not be determined.
	 *
	 * @link http://stackoverflow.com/questions/2602612/php-remote-file-size-without-downloading-file
	 */
	public function curl_get_file_size( $url ) {
		// Assume failure.
		$result = 0;

		$curl = curl_init( $url );

		// Issue a HEAD request and follow any redirects.
		curl_setopt( $curl, CURLOPT_NOBODY, true );
		curl_setopt( $curl, CURLOPT_HEADER, true );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
		// curl_setopt( $curl, CURLOPT_USERAGENT, get_user_agent_string() );

		$data = curl_exec( $curl );
		curl_close( $curl );

		if ( $data ) {
			$content_length = 0;
			$status = 0;

			if ( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
				$status = (int)$matches[1];
			}

			if ( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
				$content_length = (int)$matches[1];
			}

			// http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
			if ( $status == 200 || ($status > 300 && $status <= 308) ) {
				$result = $content_length;
			}
		}

		return $result;
	}

	/**
	 * Returns a human readable filesize
	 *
	 * @author      wesman20 (php.net)
	 * @author      Jonas John
	 * @version     0.3
	 * @link        http://www.jonasjohn.de/snippets/php/readable-filesize.htm
	 */
	public function human_readable_filesize($size) {
	
		// Adapted from: http://www.php.net/manual/en/function.filesize.php
	 
		$mod = 1024;
	 
		$units = explode(' ','B KB MB GB TB PB');
		for ($i = 0; $size > $mod; $i++) {
			$size /= $mod;
		}
	 
		return round($size, 2) . ' ' . $units[$i];
	}

	/**
	 * hook into WP's admin_init action hook
	 */
	public function admin_init() {

	} // END public function admin_init()

	/**
	 * hook into WP's add_meta_boxes action hook
	 * @param string $value [description]
	 */
	public function add_meta_boxes() {
		// Add this metabox to every selected post
		add_meta_box(
			sprintf( 'dicentis_%s_selection', self::POST_TYPE_NAME ),
			sprintf( __( '%s Information', 'dicentis' ), ucwords( str_replace( "_", " ", self::POST_TYPE_NAME ) ) ),
			array( $this, 'add_inner_meta_boxes' ),
			self::POST_TYPE
		);
	} // END public function add_meta_boxes()

	/**
	 * called off of the add meta box
	 * @param [type] $post [description]
	 */
	public function add_inner_meta_boxes( $post ) {
		// get mediafile information for $post
		$mediafiles  = $this->get_mediafile_info( $post->ID );
		$media_count = get_post_meta( $post->ID, '_dipo_max_mediafile_number', true );
		$mediatypes  = $this->get_mediatypes();

		// retrieve the metadata values if they exist
		$dipo_subtitle = get_post_meta( $post->ID, '_dipo_subtitle', true );
		$dipo_summary  = get_post_meta( $post->ID, '_dipo_summary', true );
		$dipo_image    = get_post_meta( $post->ID, '_dipo_image', true );
		$dipo_guid     = get_post_meta( $post->ID, '_dipo_guid', true );
		$dipo_explicit = get_post_meta( $post->ID, '_dipo_explicit', true );

		// $dipo_general_options = get_option( 'dipo_general_options' );
		// $assets = '';
		// if ( isset( $dipo_medialink ) ):
		// 	if ( isset( $dipo_general_options['general_assets_url'] ) ):
		// 		$assets = $dipo_general_options['general_assets_url'];

		// 		if ( 0 < strlen( strstr( $dipo_medialink, 'http://' ) ) ):
		// 			if ( 0 < strlen( strstr( $dipo_medialink, $assets ) ) ):
		// 				$dipo_medialink = str_replace( $assets, '', $dipo_medialink );
		// 			else:
		// 				$assets = '';
		// 			endif;
		// 		endif;
		// 	endif;
		// else:
		// 	// get option 'dipo_general_options'
		// 	if ( isset( $dipo_general_options['general_assets_url'] ) ):
		// 		$assets = $dipo_general_options['general_assets_url'];
		// 	endif;
		// endif;

		include( $this->properties->get('dipo_templates') . '/podcast_metabox-template.php' );
	} // END public function add_inner_meta_boxes( $post )

	public function get_mediafile_info( $post_id ) {
		$media_count = get_post_meta( $post_id, '_dipo_max_mediafile_number', true );

		$mediafiles = array();

		for ( $i=1; $i <= $media_count; $i++ ) { 
			$temp_mediafile = get_post_meta( $post_id, '_dipo_mediafile' . $i, true );
			array_push( $mediafiles, $temp_mediafile );
		}

		return $mediafiles;
	}

	public static function get_select_mediatypes( $mediafile_num = '1', $selected = 'mp3' ) {
		$mediatypes = Dipo_Podcast_Post_Type::get_mediatypes();
		// prepare <select> mediatypes
		$select_mediatypes = "<select id='dipo_mediafile" . $mediafile_num . "_type' name='dipo_mediafile" . $mediafile_num . "_type'>";
		foreach ( $mediatypes as $file_type ) {
			if ( $selected == $file_type['mime_type'] ) {
				$select_mediatypes .= "<option value='" . $file_type['mime_type'] . "' selected>" . $file_type['extension'] . "</option>";
			} else {
				$select_mediatypes .= "<option value='" . $file_type['mime_type'] . "'>" . $file_type['extension'] . "</option>";
			}
		}
		$select_mediatypes .= "</select>";

		return $select_mediatypes;
	}

	public static function echo_select_mediatypes() {
		echo self::get_select_mediatypes();
	}

	public static function get_mediatypes() {
		return $default_types = array(
			array( 'name' => 'MP3 Audio',              'type' => 'audio',    'mime_type' => 'audio/mpeg',  'extension' => 'mp3' ),
			array( 'name' => 'BitTorrent (MP3 Audio)', 'type' => 'audio',    'mime_type' => 'application/x-bittorrent',  'extension' => 'mp3.torrent' ),
			array( 'name' => 'MPEG-1 Video',           'type' => 'video',    'mime_type' => 'video/mpeg',  'extension' => 'mpg' ),
			array( 'name' => 'MPEG-4 AAC Audio',       'type' => 'audio',    'mime_type' => 'audio/mp4',   'extension' => 'm4a' ),
			array( 'name' => 'MPEG-4 ALAC Audio',      'type' => 'audio',    'mime_type' => 'audio/mp4',   'extension' => 'm4a' ),
			array( 'name' => 'MPEG-4 Video',           'type' => 'video',    'mime_type' => 'video/mp4',   'extension' => 'mp4' ),
			array( 'name' => 'M4V Video (Apple)',      'type' => 'video',    'mime_type' => 'video/x-m4v', 'extension' => 'm4v' ),
			array( 'name' => 'Ogg Vorbis Audio',       'type' => 'audio',    'mime_type' => 'audio/ogg',   'extension' => 'oga' ),
			array( 'name' => 'Ogg Vorbis Audio',       'type' => 'audio',    'mime_type' => 'audio/ogg',   'extension' => 'ogg' ),
			array( 'name' => 'Ogg Theora Video',       'type' => 'video',    'mime_type' => 'video/ogg',   'extension' => 'ogv' ),
			array( 'name' => 'WebM Audio',             'type' => 'audio',    'mime_type' => 'audio/webm',  'extension' => 'webm' ),
			array( 'name' => 'WebM Video',             'type' => 'video',    'mime_type' => 'video/webm',  'extension' => 'webm' ),
			array( 'name' => 'FLAC Audio',             'type' => 'audio',    'mime_type' => 'audio/flac',  'extension' => 'flac' ),
			array( 'name' => 'Opus Audio',             'type' => 'audio',    'mime_type' => 'audio/ogg;codecs=opus',  'extension' => 'opus' ),
			array( 'name' => 'Matroska Audio',         'type' => 'audio',    'mime_type' => 'audio/x-matroska',  'extension' => 'mka' ),
			array( 'name' => 'Matroska Video',         'type' => 'video',    'mime_type' => 'video/x-matroska',  'extension' => 'mkv' ),
			array( 'name' => 'PDF Document',           'type' => 'ebook',    'mime_type' => 'application/pdf',  'extension' => 'pdf' ),
			array( 'name' => 'ePub Document',          'type' => 'ebook',    'mime_type' => 'application/epub+zip',  'extension' => 'epub' ),
			array( 'name' => 'PNG Image',              'type' => 'image',    'mime_type' => 'image/png',   'extension' => 'png' ),
			array( 'name' => 'JPEG Image',             'type' => 'image',    'mime_type' => 'image/jpeg',  'extension' => 'jpg' ),
			// array( 'name' => 'mp4chaps Chapter File',  'type' => 'chapters', 'mime_type' => 'text/plain',  'extension' => 'chapters.txt' ),
			// array( 'name' => 'Podlove Simple Chapters','type' => 'chapters', 'mime_type' => 'application/xml',  'extension' => 'psc' ),
			// array( 'name' => 'Subrip Captions',        'type' => 'captions', 'mime_type' => 'application/x-subrip',  'extension' => 'srt' ),
			// array( 'name' => 'WebVTT Captions',        'type' => 'captions', 'mime_type' => 'text/vtt',  'extension' => 'vtt' ),
			// array( 'name' => 'Auphonic Production Description', 'type' => 'metadata', 'mime_type' => 'application/json',  'extension' => 'json' ),
		);
	}

	public function load_custom_wp_admin_style( $hook ) {

		// Load everytime Dicentis Font if in Backend
		wp_register_style( 'dipo_font', DIPO_ASSETS_URL . '/css/dicentis-font.css' );
		wp_enqueue_style( 'dipo_font' );

		if( 'post.php' != $hook and 'post-new.php' != $hook )
			return;

		wp_enqueue_script( 'dicentis-media-upload',
			DIPO_ASSETS_URL . '/js/dicentis-metabox.js',
			array( 'jquery', 'media-upload', 'thickbox' )
		);
		wp_enqueue_style( 'thickbox' );

		// Load assets for metabox tabs
		// @source https://github.com/PeteMall/Metabox-Tabs
		/* use $color to get users color scheme and enqueue custom style */
		// $color = get_user_meta( get_current_user_id(), 'admin_color', true );
		// wp_enqueue_style(  "jf-$color",
		// 	DIPO_ASSETS_URL . "/css/metabox-$color.css" );

		wp_enqueue_style(  'jf-metabox-tabs',
			DIPO_ASSETS_URL . '/css/metabox-tabs.css' );
		wp_enqueue_style(  "jf-classic",
			DIPO_ASSETS_URL . "/css/metabox-classic.css" );
		wp_enqueue_script( 'jf-metabox-tabs',
			DIPO_ASSETS_URL . '/js/metabox-tabs.js',
			array( 'jquery' ) );
		// Custom metabox style
		wp_enqueue_style(  'dipo-metabox-style',
			DIPO_ASSETS_URL . '/css/dipo_metabox.css' );
	}

	public function add_tax_column( $columns ) {
		foreach ( $this->_tax as $tax_slug => $tax_name ) {
			$columns[$tax_slug] = __( $tax_name, 'dicentis' );
		}

		return $columns;
	}

	public function podcast_custom_column( $column_name, $post_id ) {
		$taxonomy = $column_name;
		$post_type = get_post_type($post_id);
		$terms = get_the_terms($post_id, $taxonomy);
		$post_terms = array();

		if ( !empty($terms) ) {
			foreach ( $terms as $term )
				$post_terms[] = "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " . esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
			echo join( ', ', $post_terms );
		}
		else echo '<i>No terms.</i>';
	}


	public function shortcode_podcast_show( $attr ) {
		$err = -1;
		$show = "";
		$all_shows = get_terms( 'podcast_show', 'hide_empty=0' );

		$speaker_tax = 'podcast_speaker';
		$series_tax = 'podcast_series';

		// Error #2: No showname in shortcode given!
		if ( empty( $attr ) )
			$err = 2;

		// if attributes are given check if these are
		// podcast_show slugs and list only these shows
		if ( !empty( $attr ) and isset( $attr['show'] ) ) {
			$possible_shows = $this->get_all_shows( $all_shows );

			// Asume: Error #3: No show exists with that name!
			$err = 3;
			foreach ( $attr as $key => $value ) {
				if ( in_array( $value, $possible_shows ) ) {
					$show = $value;
					$err = -1;
				}
			}
		}

		$args = array(
			'post_type' => self::POST_TYPE,
			'order' => 'DESC',
			'oderby' => 'date',
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'podcast_show',
					'field' => 'slug',
					'terms' => $show,
				),
			),
		);
		$category_posts = new WP_Query($args);

		$episodes = array();
		while( $category_posts->have_posts() ) : $category_posts->the_post();
			$i = array_push( $episodes, $category_posts->post );

			$postID = $episodes[$i-1]->ID;

			// $post_tax = array();
			// $post_tax['podcast_show'] = wp_get_post_terms( $postID, 'podcast_show' );
			// $post_tax['podcast_speaker'] = wp_get_post_terms( $postID, $speaker_tax );
			// $post_tax['podcast_series'] = wp_get_post_terms( $postID, $series_tax );
			// $episodes[$i-1]->taxonomies = $post_tax;
			$episodes[$i-1]->taxonomies = get_the_taxonomies( $postID );

			$episodes[$i-1]->metadata = get_metadata( 'post', $postID );
		endwhile;

		include( dirname( dirname( __FILE__ ) ) . 'shortcodes/templates/shortcode_podcast_show-template.php' );
	}

	public function get_all_shows( $all_shows ) {
		$shows = array();
		foreach ( $all_shows as $id => $show ) {
			array_push( $shows, $show->slug );
		}
		return $shows;
	}

} // END class Dipo_Podcast_Post_Type
