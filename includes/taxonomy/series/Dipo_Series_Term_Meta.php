<?php

namespace Dicentis\Taxonomy\Series;

use Dicentis\Taxonomy;

class Dipo_Series_Term_Meta {

	public function add_picture_field( $taxonomy ) {
		$feature_groups = array(
			'bedroom' => __('Bedroom', 'my_plugin'),
			'living' => __('Living room', 'my_plugin'),
			'kitchen' => __('Kitchen', 'my_plugin')
		);
			?><div class="form-field term-group">
			<label for="feature-group"><?php _e('Feature Group', 'my_plugin'); ?></label>
			<select class="postform" id="equipment-group" name="feature-group">
				<option value="-1"><?php _e('none', 'my_plugin'); ?></option><?php foreach ($feature_groups as $_group_key => $_group) : ?>
					<option value="<?php echo $_group_key; ?>" class=""><?php echo $_group; ?></option>
				<?php endforeach; ?>
			</select>
			</div><?php
		}

	public function save_feature_meta( $term_id, $tt_id ) {
		if ( isset( $_POST['feature-group'] ) && '' !== $_POST['feature-group'] ) {
			$group = sanitize_title( $_POST['feature-group'] );
			add_term_meta( $term_id, 'feature-group', $group, true );
		}
	}

	public function edit_picture_field( $term, $taxonomy ){

		$feature_groups = array(
			'bedroom' => __('Bedroom', 'my_plugin'),
			'living' => __('Living room', 'my_plugin'),
			'kitchen' => __('Kitchen', 'my_plugin')
		);

		// get current group
		$feature_group = get_term_meta( $term->term_id, 'feature-group', true );

		?><tr class="form-field term-group-wrap">
		<th scope="row"><label for="feature-group"><?php _e( 'Feature Group', 'my_plugin' ); ?></label></th>
		<td><select class="postform" id="feature-group" name="feature-group">
				<option value="-1"><?php _e( 'none', 'my_plugin' ); ?></option>
				<?php foreach( $feature_groups as $_group_key => $_group ) : ?>
					<option value="<?php echo $_group_key; ?>" <?php selected( $feature_group, $_group_key ); ?>><?php echo $_group; ?></option>
				<?php endforeach; ?>
			</select></td>
		</tr><?php
	}


	public function update_feature_meta( $term_id, $tt_id ){

		if( isset( $_POST['feature-group'] ) && '' !== $_POST['feature-group'] ){
			$group = sanitize_title( $_POST['feature-group'] );
			update_term_meta( $term_id, 'feature-group', $group );
		}
	}

	public function add_picture_column( $columns ){
		$columns['feature_group'] = __( 'Group', 'my_plugin' );
		return $columns;
	}

	public function add_picture_column_content( $content, $column_name, $term_id ){
		$feature_groups = array(
			'bedroom' => __('Bedroom', 'my_plugin'),
			'living' => __('Living room', 'my_plugin'),
			'kitchen' => __('Kitchen', 'my_plugin')
		);

		if( $column_name !== 'feature_group' ){
			return $content;
		}

		$term_id = absint( $term_id );
		$feature_group = get_term_meta( $term_id, 'feature-group', true );

		if( !empty( $feature_group ) ){
			$content .= esc_attr( $feature_groups[ $feature_group ] );
		}

		return $content;
	}
}
