<?php

include plugin_dir_path( __FILE__ ) . '../lib/simple-term-meta.php';
include_once plugin_dir_path( __FILE__ ) . '../dicentis-define.php';

if( !class_exists( 'Dicentis_Podcast_CPT' ) ) {
	/**
	 * The Podcast Post Type
	 */
	class Dicentis_Podcast_CPT {
		const POST_TYPE = 'podcast';

		private $_meta  = array(
			'_dipo_subtitle',
			'_dipo_summary',
			'_dipo_medialink',
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
			// register actions
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			// setup new tables by simple-term-meta
			// used for additional meta data in podcast_show taxonomy
			simple_term_meta_install();
		} // END public function __construct()

		/**
		 * hook into WP's init action hook
		 */
		public function init() {
			// Initialize Post Type
			$this->register_podcast_post_type();
			$this->register_podcast_taxonomy();
			add_action( 'save_post', array( $this,'save_post' ) );

			// add taxonomy information for posts as new column
			// manage_podcast_posts_columns
			add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( $this, 'add_tax_column' ) );
			add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'podcast_custom_column' ), 10, 2 );

			// add additional filter options to podcast site
			add_action( 'restrict_manage_posts', array( $this, 'filter_posts' ) );

			// register shortcodes
			add_shortcode( 'podcasts', array( $this, 'shortcode_podcast_show' ) );

			// script & style action with page detection
			add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ) );

			// Creating Form Input on Show Tax Page
			// add_action( 'podcast_show_add_form_fields', array( $this, 'podcast_show_metabox_add' ), 10, 1 );
			// add_action( 'podcast_show_edit_form_fields', array( $this, 'podcast_show_metabox_edit' ), 10, 1 );
			// saving Term Metadata
			// add_action( 'created_podcast_show', array( $this, 'save_podcast_show_metadata' ), 10, 1 );
			// add_action( 'edited_podcast_show', array( $this, 'save_podcast_show_metadata' ), 10, 1 );

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
				),
				// 'menu_icon' => plugins_url( 'dicentis/assets/img/podcast-icon.png' ),
				'menu_icon' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4gPCEtLSBHZW5lcmF0b3I6IEljb01vb24uaW8gLS0+IDwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+IDxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSI0OHB4IiBoZWlnaHQ9IjQ4cHgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTYgMTYiIHhtbDpzcGFjZT0icHJlc2VydmUiIGZpbGw9IiMwMDAwMDAiPiA8cGF0aCBkPSJNIDM4Ljg1LDEyLjE1M2MtOC4yMDItOC4yMDItMjEuNDk4LTguMjAyLTI5LjY5NywwLjAwTCA0LjkwOCw3LjkwOCBjIDEwLjU0NS0xMC41NDUsIDI3LjY0Mi0xMC41NDUsIDM4LjE4NywwLjAwTCAzOC44NSwxMi4xNTN6IE0gMTUuNTE2LDE4LjUxNkwgMTEuMjc0LDE0LjI3MWMgNy4wMjktNy4wMjksIDE4LjQyNi03LjAyOSwgMjUuNDUyLDAuMDBsLTQuMjQyLDQuMjQ1IEMgMjcuODAxLDEzLjgyNywgMjAuMTk5LDEzLjgyNywgMTUuNTE2LDE4LjUxNnogTSAzOS4wMCw0NS4wMGMwLjAwLDEuNjU5LTEuMzQxLDMuMDAtMy4wMCwzLjAwTDEyLjAwLDQ4LjAwIGMtMS42NTksMC4wMC0zLjAwLTEuMzQxLTMuMDAtMy4wMGwwLjAwLC0zLjAwIGMgMC44Ny00LjI3NSwgMy44NTUtNy44OSwgNy41MTgtMTAuMDAyIEMgMTUuNTY0LDMwLjU2NywgMTUuMDAsMjguODUxLCAxNS4wMCwyNy4wMGMwLjAwLTQuOTcxLCA0LjAyOS05LjAwLCA5LjAwLTkuMDBzIDkuMDAsNC4wMjksIDkuMDAsOS4wMGMwLjAwLDEuODUxLTAuNTY0LDMuNTY3LTEuNTE4LDQuOTk4QyAzNS4xNDUsMzQuMTEsIDM4LjEzLDM3LjcyNSwgMzkuMDAsNDIuMDBMMzkuMDAsNDUuMDAgeiBNIDI0LjAwLDI0LjAwQyAyMi4zNDEsMjQuMDAsIDIxLjAwLDI1LjM0MSwgMjEuMDAsMjcuMDBzIDEuMzQxLDMuMDAsIDMuMDAsMy4wMHMgMy4wMC0xLjM0MSwgMy4wMC0zLjAwUyAyNS42NTksMjQuMDAsIDI0LjAwLDI0LjAweiBNIDMyLjQ0OCw0Mi4wMEMgMzEuMjA5LDM4LjUxNCwgMjcuOTE1LDM2LjAwLCAyNC4wMCwzNi4wMHMtNy4yMDksMi41MTQtOC40NDgsNi4wMCBMMzIuNDQ4LDQyLjAwIHoiID48L3BhdGg+PC9zdmc+',
				'can_export' => 'true',
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
		 */
		public function register_podcast_taxonomy() {


			$podcast_show_args = array(
				'hierarchical' => true,
				'query_var' => 'podcast_show',
				'rewrite' => array(
					'slug' => self::POST_TYPE . '/show',
				),
				'labels' => array(
					'name' => __( 'Podcast Shows', 'dicentis' ),
					'singular_name' => __( 'Podcast Show', 'dicentis' ),
					'edit_item' => __( 'Edit Podcast Show', 'dicentis' ),
					'update_item' => __( 'Update Podcast Show', 'dicentis' ),
					'add_new_item' => __( 'Add New Podcast Show', 'dicentis' ),
					'new_item_name' => __( 'New Podcast Show Name', 'dicentis' ),
					'all_items' => __( 'All Podcast Show', 'dicentis' ),
					'search_items' => __( 'Search Podcast Show', 'dicentis' ),
					'parent_item' => __( 'Parent Podcast Show', 'dicentis' ),
					'parent_item_colon' => __( 'Parent Podcast Show:', 'dicentis' ),
				),
			);

			// Set up the series taxonomy
			$series_args = array(
				'hierarchical' => true,
				'query_var' => 'podcast_series',
				'rewrite' => array(
					'slug' => self::POST_TYPE . '/series',
				),
				'labels' => array(
					'name' => __( 'Series', 'dicentis' ),
					'singular_name' => __( 'Series', 'dicentis' ),
					'edit_item' => __( 'Edit Series', 'dicentis' ),
					'update_item' => __( 'Update Series', 'dicentis' ),
					'add_new_item' => __( 'Add New Series', 'dicentis' ),
					'new_item_name' => __( 'New Series Name', 'dicentis' ),
					'all_items' => __( 'All Series', 'dicentis' ),
					'search_items' => __( 'Search Series', 'dicentis' ),
					'parent_item' => __( 'Parent Series', 'dicentis' ),
					'parent_item_colon' => __( 'Parent Series:', 'dicentis' ),
				),
			);

			// Set up the speaker taxonomy
			$speaker_args = array(
				'hierarchical' => true,
				'query_var' => 'podcast_speaker',
				'rewrite' => array(
					'slug' => self::POST_TYPE . '/speaker',
				),
				'labels' => array(
					'name' => __( 'Speakers', 'dicentis' ),
					'singular_name' => __( 'Speaker', 'dicentis' ),
					'edit_item' => __( 'Edit Speaker', 'dicentis' ),
					'update_item' => __( 'Update Speaker', 'dicentis' ),
					'add_new_item' => __( 'Add New Speaker', 'dicentis' ),
					'new_item_name' => __( 'New Speaker Name', 'dicentis' ),
					'all_items' => __( 'All Speaker', 'dicentis' ),
					'search_items' => __( 'Search Speaker', 'dicentis' ),
					'parent_item' => __( 'Parent Speaker', 'dicentis' ),
					'parent_item_colon' => __( 'Parent Speaker:', 'dicentis' ),
				),
			);

			// register show taxonomy
			if ( taxonomy_exists( 'podcast_show' ) ) {
				/* @TODO: show admin notice */
			} else {
				register_taxonomy( 'podcast_show', array( self::POST_TYPE ), $podcast_show_args );
				// array_push( $this->_tax, 'podcast_show' );
				$the_tax = get_taxonomy( 'podcast_show' );
				$this->_tax['podcast_show'] = $the_tax->labels->name;
			}

			/* push each taxonomy name, which is used in this plugin
			 * into this->_tax array. filter_posts() uses this array
			 * to know which taxonomy is used and display filter options
			 * for that
			 */
			// register series taxonomy
			if ( taxonomy_exists( 'celebration_series' ) ) {
				// avantgarde-celebration plugin is installed and active
				register_taxonomy_for_object_type( 'celebration_series', self::POST_TYPE );
			} elseif ( taxonomy_exists( 'podcast_series' ) ) {
				/* @TODO: show admin notice */
			} else {
				register_taxonomy( 'podcast_series', array( self::POST_TYPE ), $series_args );
				$the_tax = get_taxonomy( 'podcast_series' );
				$this->_tax['podcast_series'] = $the_tax->labels->name;
			}

			// register speaker taxonomy
			if ( taxonomy_exists( 'celebration_preachers' ) ) {
				// avantgarde-celebration plugin is installed and active
				register_taxonomy_for_object_type( 'celebration_preachers', self::POST_TYPE );
			} elseif ( taxonomy_exists( 'podcast_speaker' ) ) {
				/* @TODO: show admin notice */
			} else {
				register_taxonomy( 'podcast_speaker', array( self::POST_TYPE ), $speaker_args );
				$the_tax = get_taxonomy( 'podcast_speaker' );
				$this->_tax['podcast_speaker'] = $the_tax->labels->name;
			}
		} // END public function register_podcast_taxonomy()

		public function podcast_show_metabox_add( $tag ) { ?>
			<div class="form-field">
				<label for="asset-url"><?php _e( 'Asset URL', 'dicentis' ); ?></label>
				<input name="asset-url" id="asset-url" type="text" value="" size="40" aria-required="true" />
				<p class="description"><?php _e( 'This is the path / URL to the asset folder.', 'dicentis' ); ?></p>
			</div>
		<?php }

		public function podcast_show_metabox_edit( $tag ) { ?>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="asset-url"><?php _e( 'Asset URL', 'dicentis' ); ?></label>
				</th>
				<td>
					<input name="asset-url" id="asset-url" type="text" value="<?php echo get_term_meta( $tag->term_id, 'asset-url', true  ); ?>" size="40" aria-require="true" />
					<p class="description"><?php _e( 'This is the path / URL to the asset folder.', 'dicentis' ); ?></p>
				</td>
			</tr>
		<?php }

		public function save_podcast_show_metadata( $term_id ) {
			if ( isset( $_POST['asset-url'] ) ) {
				update_term_meta( $term_id, 'asset-url', $_POST['asset-url'] );
			}
		}

		/**
		 * add additional filter options to post type site for each
		 * taxonomy which is used for this plugin
		 */
		public function filter_posts() {
			global $typenow;

			if( 'podcast' == $typenow ){

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

				foreach ($this->_meta as $field_name):
					// update the post's meta field
					if ( strcmp( $field_name, '_dipo_medialink' ) == 0 ||
						 strcmp( $field_name, '_dipo_image' ) == 0 ) {
						switch ( $field_name ) {
							case '_dipo_medialink':
								if ( isset( $_POST['dipo_medialink'] ) ) {
									$dipo_medialink = $_POST['dipo_medialink'];
								} else {
									$dipo_medialink = "";
								}

								if ( '' == $asset_prefix ):
									update_post_meta( $post_id, $field_name, esc_url_raw( $dipo_medialink ) );
								elseif ( '' != $asset_prefix ):

									if ( FALSE == strstr( $dipo_medialink, 'http://' ) ):
										$link = $asset_prefix . $dipo_medialink;
									else:
										$link = $dipo_medialink;
									endif;

									update_post_meta( $post_id, $field_name, $link );								
								endif;
								break;

							case '_dipo_image':
								if ( isset( $_POST[ 'dipo_image' ] ) ) {
									$dipo_image = $_POST[ 'dipo_image' ];
								} else {
									$dipo_image = "";
								}

								update_post_meta( $post_id, $field_name, esc_url_raw( $dipo_image ) );
								break;

							default:
								break;
						}
					} else {
						if ( isset( $_POST[substr($field_name, 1)] ) ) {
							update_post_meta( $post_id, $field_name, $_POST[substr($field_name, 1)] );
						}
					}
				endforeach;
			else:
				return;
			endif; // if( $_POST['post_type'] == self::POST_TYPE && current_user_can( 'edit_post', $post_id ) )
		} // END public function save_post( $post_id )

		/**
		 * hook into WP's admin_init action hook
		 */
		public function admin_init() {
			// Add metaboxes
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		} // END public function admin_init()

		/**
		 * hook into WP's add_meta_boxes action hook
		 * @param string $value [description]
		 */
		public function add_meta_boxes() {
			// Add this metabox to every selected post
			add_meta_box(
				sprintf( 'dicentis_%s_selection', self::POST_TYPE ),
				sprintf( __( '%s Information', 'dicentis' ), ucwords( str_replace( "_", " ", self::POST_TYPE ) ) ),
				array( $this, 'add_inner_meta_boxes' ),
				self::POST_TYPE
			);
		} // END public function add_meta_boxes()

		/**
		 * called off of the add meta box
		 * @param [type] $post [description]
		 */
		public function add_inner_meta_boxes( $post ) {
			// Render the job order metabox
			include( sprintf( '%s/%s_metabox.php', DIPO_TEMPLATES_DIR, self::POST_TYPE ) );
		} // END public function add_inner_meta_boxes( $post )

		public function load_custom_wp_admin_style( $hook ) {

			if( 'post.php' != $hook and 'post-new.php' != $hook )
				return;

			wp_enqueue_script( 'dicentis-media-upload',
				DIPO_ASSETS_URL . '/js/dicentis-metabox.js',
				array( 'jquery', 'media-upload', 'thickbox' )
			);
			wp_enqueue_style( 'thickbox' );
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

			if ( $this->is_avantgarde_plugin_active() ) {
				$speaker_tax = 'celebration_preachers';
				$series_tax = 'celebration_series';
			} else {
				$speaker_tax = 'podcast_speaker';
				$series_tax = 'podcast_series';
			}

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
				'post_type' => 'podcast',
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

			include( sprintf('%s/../templates/shortcode_podcast_show.php', dirname(__FILE__) ) );
		}

		public function is_avantgarde_plugin_active() {
			/**
			 * Detect plugin. For use on Front End only.
			 */
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			return is_plugin_active( 'avantgarde-celebrations/avantgarde-celebrations.php' );
		}

		public function get_all_shows( $all_shows ) {
			$shows = array();
			foreach ( $all_shows as $id => $show ) {
				array_push( $shows, $show->slug );
			}
			return $shows;
		}
	} // END class Dicentis_Podcast_CPT
} // END if( !class_exists( 'Dicentis_Podcast_CPT' ) )
