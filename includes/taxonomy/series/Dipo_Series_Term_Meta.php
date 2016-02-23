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

	public function add_picture_field( $taxonomy ) { ?>
		<div class="form-field term-group">
			<label for="series-picture"><?php _e( 'Series Picture', 'dicentis' ); ?></label>
			<input type="button" class="button open-media-button" id="open-media-lib" value="Open Media Library" data-title="Select An Image" data-button-text="Select" />

			<fieldset id="attachment-details" class="attachment-fieldset">
				<p><?php _e( 'The recommended picture size is 300x300 pixels.', 'dicentis' ); ?></p>
				<label><?php _e( 'Picture URL:', 'dicentis' ); ?></label>
				<input class="postform" name="dipo-series-pictures" type="text" id="attachment-url" class="regular-text" />

				<label><?php _e( 'Picture Preview:', 'dicentis' ); ?></label>
				<img id="attachment-src" />

			</fieldset>
		</div>
		<?php
	}

	public function save_feature_meta( $term_id, $tt_id ) {
		if ( isset( $_POST['dipo-series-pictures'] ) && '' !== $_POST['dipo-series-pictures'] ) {
			$group = esc_url_raw( $_POST['dipo-series-pictures'] );
			add_term_meta( $term_id, 'dipo-series-pictures', $group, true );
		}
	}

	public function edit_picture_field( $term, $taxonomy ) {
		$feature_groups = array(
			'bedroom' => __( 'Bedroom', 'my_plugin' ),
			'living'  => __( 'Living room', 'my_plugin' ),
			'kitchen' => __( 'Kitchen', 'my_plugin' )
		);

		// get current group
		$feature_group = get_term_meta( $term->term_id, 'feature-group', true );

		?>
		<tr class="form-field term-group-wrap">
		<th scope="row"><label for="feature-group"><?php _e( 'Feature Group', 'my_plugin' ); ?></label></th>
		<td><select class="postform" id="feature-group" name="feature-group">
				<option value="-1"><?php _e( 'none', 'my_plugin' ); ?></option>
				<?php foreach ( $feature_groups as $_group_key => $_group ) : ?>
					<option
						value="<?php echo $_group_key; ?>" <?php selected( $feature_group, $_group_key ); ?>><?php echo $_group; ?></option>
				<?php endforeach; ?>
			</select></td>
		</tr><?php
	}


	public function update_feature_meta( $term_id, $tt_id ) {

		if ( isset( $_POST['feature-group'] ) && '' !== $_POST['feature-group'] ) {
			$group = sanitize_title( $_POST['feature-group'] );
			update_term_meta( $term_id, 'feature-group', $group );
		}
	}

	public function add_picture_column( $columns ) {
		$columns['feature_group'] = __( 'Group', 'my_plugin' );

		return $columns;
	}

	public function add_picture_column_content( $content, $column_name, $term_id ) {
		$feature_groups = array(
			'bedroom' => __( 'Bedroom', 'my_plugin' ),
			'living'  => __( 'Living room', 'my_plugin' ),
			'kitchen' => __( 'Kitchen', 'my_plugin' )
		);
		if ( $column_name !== 'feature_group' ) {
			return $content;
		}

		$term_id       = absint( $term_id );
		$feature_group = get_term_meta( $term_id, 'feature-group', true );

		if ( ! empty( $feature_group ) ) {
			$content .= esc_attr( $feature_groups[ $feature_group ] );
		}

		return $content;
	}
}
