<?php
	// retrieve the metadata values if they exist
	$dipo_subtitle = get_post_meta( $post->ID, '_dipo_subtitle', true );
	$dipo_summary = get_post_meta( $post->ID, '_dipo_summary', true );
	$dipo_medialink = get_post_meta( $post->ID, '_dipo_medialink', true );
	$dipo_general_options = get_option( 'dipo_general_options' );
	$assets = '';
	if ( isset( $dipo_medialink ) ):
		if ( isset( $dipo_general_options['general_assets_url'] ) ):
			$assets = $dipo_general_options['general_assets_url'];
			
			if ( 0 < strlen( strstr( $dipo_medialink, 'http://' ) ) ):
				if ( 0 < strlen( strstr( $dipo_medialink, $assets ) ) ):
					$dipo_medialink = str_replace( $assets, '', $dipo_medialink );
				else:
					$assets = '';
				endif;
			endif;
		endif;
	else:
		// get option 'dipo_general_options'
		if ( isset( $dipo_general_options['general_assets_url'] ) ):
			$assets = $dipo_general_options['general_assets_url'];
		endif;
	endif;

	$dipo_image = get_post_meta( $post->ID, '_dipo_image', true );
	$dipo_guid = get_post_meta( $post->ID, '_dipo_guid', true );
	$dipo_explicit = get_post_meta( $post->ID, '_dipo_explicit', true );

	function get_select_mediatypes( $mediatypes, $mediafile_num = '1', $selected = 'mp3' ) {
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

?>

<div class="inside">
	<div>
		<label for="dipo_subtitle"><strong><?php _e( 'Subtitle', DIPO_TEXTDOMAIN ); ?></strong></label>
		<input type="text" id="dipo_subtitle" name="dipo_subtitle" value="<?php echo esc_attr( $dipo_subtitle ); ?>" />
		<p id="subtitle_counter" class="counter">0</p>
	</div>

	<div>
		<label for="dipo_summary"><strong><?php _e( 'Summary', DIPO_TEXTDOMAIN ); ?></strong></label>
		<textarea id="dipo_summary" name="dipo_summary" ><?php echo esc_attr( $dipo_summary ); ?></textarea>
		<p id="summary_counter" class="counter">0</p>
	</div>

	<div>
		<label for="dipo_mediafiles"><strong><?php _e( 'Media Files', DIPO_TEXTDOMAIN ); ?></strong></label>
		<table id="dipo_mediafiles_table">
			<tbody>
				<tr>
					<th><?php _e( 'Medialink', DIPO_TEXTDOMAIN ); ?></th>
					<th><?php _e( 'Media Type', DIPO_TEXTDOMAIN ); ?></th>
					<th><?php _e( 'Duration', DIPO_TEXTDOMAIN ); ?></th>
					<th><?php _e( 'Size', DIPO_TEXTDOMAIN ); ?></th>
					<th></th>
				</tr>
				<?php 
				/* @TODO Information for mediatypes and that one type should only appear once per post */
				if ( empty($mediafiles) ) { 
					$media_count = 1; ?>
					<tr>
						<input id="dipo_mediafile1" type="hidden" name="dipo_mediafile1" value="update" />
						<td><input id="dipo_mediafile1_link" type="text" name="dipo_mediafile1_link" value="" /></td>
						<td><?php echo get_select_mediatypes( $mediatypes ); ?></td>
						<td><input id="dipo_mediafile1_duration" type="text" name="dipo_mediafile1_duration" value="" /></td>
						<td><input id="dipo_mediafile1_size" type="text" name="dipo_mediafile1_size" value="" /></td>
						<td>
							<div file="1" class="remove_mediafile button-secondary"><i class="dashicons-before dashicons-trash"></i><?php _e('Remove', DIPO_TEXTDOMAIN ); ?></div>
						</td>
					</tr>
				<?php } else {
					foreach ( $mediafiles as $key => $mediafile ) { ?>
						<tr>
							<input id="dipo_mediafile<?php echo $mediafile['id']; ?>" type="hidden" name="dipo_mediafile<?php echo $mediafile['id']; ?>" value="update" />
							<td><input id="dipo_mediafile<?php echo $mediafile['id']; ?>_link" type="text" name="dipo_mediafile<?php echo $mediafile['id']; ?>_link" value="<?php echo $mediafile['medialink']; ?>" /></td>
							<td><?php echo get_select_mediatypes( $mediatypes, $mediafile['id'], $mediafile['mediatype'] ); ?></td>
							<td><input id="dipo_mediafile<?php echo $mediafile['id']; ?>_duration" type="text" name="dipo_mediafile<?php echo $mediafile['id']; ?>_duration" value="<?php echo $mediafile['duration']; ?>" /></td>
							<td><input id="dipo_mediafile<?php echo $mediafile['id']; ?>_size" type="text" name="dipo_mediafile<?php echo $mediafile['id']; ?>_size" value="<?php echo $mediafile['filesize']; ?>" /></td>
							<td>
								<div file="<?php echo $mediafile['id']; ?>" class="remove_mediafile button-secondary"><i class="dashicons-before dashicons-trash"></i><?php _e('Remove', DIPO_TEXTDOMAIN ); ?></div>
							</td>
						</tr>
			<?php }
				} ?>
			</tbody>
		</table>
		<input id="dipo_mediafiles_count" name="dipo_mediafiles_count" type="hidden" value="<?php echo $media_count ?>" />
		<div id="add_mediafile" class="button-primary"><i class="dashicons-before dashicons-admin-media"></i><?php _e('Add Mediafile', DIPO_TEXTDOMAIN ); ?></div>
		<p class="description"><?php _e('Enter a media URL or use a file from the Media Library', DIPO_TEXTDOMAIN ); ?></p>
	</div>

	<div>
		<label for="dipo_image"><strong><?php _e( 'Episode Image', DIPO_TEXTDOMAIN ); ?></strong></label>
		<input id="dipo_image" type="text" name="dipo_image" value="<?php echo esc_url( $dipo_image ); ?>" />
		<input id="upload_media_button" type="button" value="Media Library" class="button-secondary" />
		<p class="description"><?php _e('Enter a media URL or use a file from the Media Library', DIPO_TEXTDOMAIN ); ?></p>
	</div>

	<div>
		<label for="dipo_guid"><strong><?php _e( 'GUID', DIPO_TEXTDOMAIN ); ?></strong></label>
		<p><?php echo $dipo_guid; ?> <a href=#><?php _e( 'Regenerate', DIPO_TEXTDOMAIN ); ?></a></p>
	</div>

	<div>
		<label for="dipo_explicit"><strong><?php _e( 'Explicit', DIPO_TEXTDOMAIN ); ?></strong></label>
		<input type="checkbox" id="dipo_explicit" name="dipo_explicit" value="explicit"
			<?php if ( strcmp( $dipo_explicit, 'explicit' ) == 0 ) echo "checked"; ?> />
		<p class="description"><?php _e( 'Use of explicit content in the title, description, language or cover art of the podcast.', 'dicentis' ); ?></p>
	</div>
</div>
