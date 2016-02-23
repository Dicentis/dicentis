<?php

namespace Dicentis\Taxonomy\Series;

use Dicentis\Taxonomy;

class Dipo_Series_Term_Meta {

	public function include_media_button_js_file() {
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		wp_enqueue_script( 'media_button', DIPO_ASSETS_URL . '/js/media_button.js', [ 'media-views' ] );

	}

	public function include_tax_style() {
		wp_enqueue_style( 'dipo_tax_style', DIPO_ASSETS_URL . '/css/dipo_taxonomies.css' );
	}

	public function add_picture_field() { ?>
		<div class="form-field term-group">
			<label for="series-picture"><?php _e( 'Series Picture', 'dicentis' ); ?></label>
			<input type="button" class="button open-media-button" id="open-media-lib" value="Open Media Library" data-title="Select An Image" data-button-text="Select" />

			<fieldset id="attachment-details" class="attachment-fieldset">
				<label><?php _e( 'Picture URL:', 'dicentis' ); ?></label>
				<input class="postform" name="dipo-series-picture" type="text" id="attachment-url" class="regular-text" />

				<label><?php _e( 'Picture Preview:', 'dicentis' ); ?></label>
				<img id="attachment-src" />

			</fieldset>
		</div>
		<?php
	}

	public function save_feature_meta( $term_id, $tt_id ) {
		if ( isset( $_POST['dipo-series-picture'] ) && '' !== $_POST['dipo-series-picture'] ) {
			$group = esc_url_raw( $_POST['dipo-series-picture'] );
			add_term_meta( $term_id, 'dipo-series-picture', $group, true );
		}
	}

	public function edit_picture_field( $term, $taxonomy ) {

		// get current group
		$picture_url = get_term_meta( $term->term_id, 'dipo-series-picture', true );
		$picture_url = isset( $picture_url ) ? esc_url( $picture_url ) : '';

		?>
		<tr class="form-field term-group-wrap">
		<th scope="row"><label for="feature-group"><?php _e( 'Feature Group', 'my_plugin' ); ?></label></th>
		<td>
			<label for="series-picture"><?php _e( 'Series Picture', 'dicentis' ); ?></label>
			<input type="button" class="button open-media-button" id="open-media-lib" value="Open Media Library" data-title="Select An Image" data-button-text="Select" />

			<fieldset id="attachment-details" class="attachment-fieldset">
				<label><?php _e( 'Picture URL:', 'dicentis' ); ?></label>
				<input class="postform" name="dipo-series-picture" type="text" id="attachment-url" class="regular-text" value="<?php echo $picture_url; ?>" />

				<label><?php _e( 'Picture Preview:', 'dicentis' ); ?></label>
				<div><img id="attachment-src" class="dipo_tax_preview_img" src="<?php echo $picture_url; ?>"/></div>

			</fieldset>
		</td>
		</tr><?php
	}


	public function update_feature_meta( $term_id, $tt_id ) {

		if ( isset( $_POST['dipo-series-picture'] ) && '' !== $_POST['dipo-series-picture'] ) {
			$group = esc_url_raw( $_POST['dipo-series-picture'] );
			update_term_meta( $term_id, 'dipo-series-picture', $group );
		}
	}

	public function add_picture_column( $columns ) {
		$columns['dipo_series_picture'] = __( 'Series Picture', 'dicentis' );

		return $columns;
	}

	public function add_picture_column_content( $content, $column_name, $term_id ) {

		if ( $column_name !== 'dipo_series_picture' ) {
			return $content;
		}

		$term_id       = absint( $term_id );
		$term = get_term( $term_id, 'podcast_series' );
		$picture_url = get_term_meta( $term_id, 'dipo-series-picture', true );

		if ( ! empty( $picture_url ) ) {
			$content .= '<img class="dipo_tax_preview_img_column" src="' . esc_url( $picture_url ) . '" title="Series Picture for: ' . esc_attr( $term->name ) . '" />';
		}

		return $content;
	}
}
