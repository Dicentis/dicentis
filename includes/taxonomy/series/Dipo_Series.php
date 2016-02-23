<?php

namespace Dicentis\Taxonomy\Series;

use \Dicentis\Taxonomy;

/**
 * Series-Taxonomy Class
 *
 * @author  Hans-Helge Buerger
 * @since   0.2.6
 * @package Dicentis\Taxonomy\Series
 */
class Dipo_Series implements Taxonomy\Dipo_Taxonomy_Interface {

	public $term_meta;

	public function __construct() {
		$this->term_meta = new Dipo_Series_Term_Meta();
	}

	public function init_term_meta() {
		add_action( 'admin_print_scripts', [ $this->term_meta, 'include_media_button_js_file' ] );
		add_action( 'podcast_series_add_form_fields', [ $this->term_meta, 'add_picture_field' ], 10, 2 );
		add_action( 'created_podcast_series', [ $this->term_meta, 'save_feature_meta' ], 10, 2 );
		add_action( 'podcast_series_edit_form_fields', [ $this->term_meta, 'edit_picture_field' ], 10, 2 );
		add_action( 'edited_podcast_series', [ $this->term_meta, 'update_feature_meta' ], 10, 2 );
		add_filter('manage_edit-podcast_series_columns', [ $this->term_meta, 'add_picture_column' ] );
		add_filter('manage_podcast_series_custom_column', [ $this->term_meta, 'add_picture_column_content' ], 10, 3 );

	}

	/**
	 * Function to register series taxonomy
	 */
	public function register_taxonomy() {
		// Set up the series taxonomy
		$series_args = array(
			'hierarchical' => true,
			'query_var'    => 'podcast_series',
			'rewrite'      => array(
				'slug' => 'podcast/series',
			),
			'labels'       => array(
				'name'              => __( 'Series', 'dicentis' ),
				'singular_name'     => __( 'Series', 'dicentis' ),
				'edit_item'         => __( 'Edit Series', 'dicentis' ),
				'update_item'       => __( 'Update Series', 'dicentis' ),
				'add_new_item'      => __( 'Add New Series', 'dicentis' ),
				'new_item_name'     => __( 'New Series Name', 'dicentis' ),
				'all_items'         => __( 'All Series', 'dicentis' ),
				'search_items'      => __( 'Search Series', 'dicentis' ),
				'parent_item'       => __( 'Parent Series', 'dicentis' ),
				'parent_item_colon' => __( 'Parent Series:', 'dicentis' ),
			),
		);

		// register series taxonomy
		register_taxonomy( 'podcast_series', array( 'dipo_podcast' ), $series_args );
	}

}
