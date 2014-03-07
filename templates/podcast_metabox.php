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
	$dipo_duration = get_post_meta( $post->ID, '_dipo_duration', true );
	$dipo_explicit = get_post_meta( $post->ID, '_dipo_explicit', true );
	$dipo_mediatype = get_post_meta( $post->ID, '_dipo_mediatype', true );
?>

<table>
	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo_subtitle"><?php _e( 'Subtitle', 'dicentis' ); ?></label>
		</th>
		<td>
			<input type="text" id="dipo_subtitle" name="dipo_subtitle" value="<?php echo esc_attr( $dipo_subtitle ); ?>" style="width: 100%;"/>
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo_summary"><?php _e( 'Summary', 'dicentis' ); ?></label>
		</th>
		<td>
			<textarea id="dipo_summary" name="dipo_summary" style="width: 100%;" ><?php echo esc_attr( $dipo_summary ); ?></textarea>
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo_medialink">Media Link</label>
		</th>
		<td>
			<span><?php echo $assets; ?></span>
			<input id="dipo_medialink" type="text" name="dipo_medialink" value="<?php echo $dipo_medialink; ?>" />
			<!-- <input id="upload_media_button" type="button" value="Media Library" class="button-secondary" /> -->
			<p class="description"><?php _e('Enter a media URL or use a file from the Media Library', 'dicentis' ); ?></p>
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo_mediatype">Media type</label>
		</th>
		<td>
			<input type="text" id="dipo_mediatype" name="dipo_mediatype" value="<?php echo esc_attr( $dipo_mediatype ); ?>" />
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo_image">Episode Image</label>
		</th>
		<td>
			<input id="dipo_image" type="text" name="dipo_image" value="<?php echo esc_url( $dipo_image ); ?>" />
			<input id="upload_media_button" type="button" value="Media Library" class="button-secondary" />
			<p class="description"><?php _e('Enter a media URL or use a file from the Media Library', 'dicentis' ); ?></p>
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo_guid">guid</label>
		</th>
		<td>
			<input type="text" id="dipo_guid" name="dipo_guid" value="<?php echo esc_attr( $dipo_guid ); ?>" />
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo_duration">Duration</label>
		</th>
		<td>
			<input type="number" id="dipo_duration" name="dipo_duration" value="<?php echo esc_attr( $dipo_duration ); ?>" />
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo_explicit">Explicit</label>
		</th>
		<td>
			<input type="checkbox" id="dipo_explicit" name="dipo_explicit" value="explicit"
				<?php if ( strcmp( $dipo_explicit, 'explicit' ) == 0 ) echo "checked"; ?> />
			<p class="description"><?php _e( 'Use of explicit content in the title, description, language or cover art of the podcast.', 'dicentis' ); ?></p>
		</td>
	</tr>
</table>
