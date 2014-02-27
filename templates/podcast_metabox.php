<?php
	// retrieve the metadata values if they exist
	$dipo_subtitle = get_post_meta( $post->ID, '_dipo_subtitle', true );
	$dipo_summary = get_post_meta( $post->ID, '_dipo_summary', true );
	$dipo_medialink = get_post_meta( $post->ID, '_dipo_medialink', true );
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
			<input id="dipo-podcast-medialink" type="text" size="75"
		name="dipo-podcast-medialink" value="<?php echo esc_url( $dipo_medialink ); ?>" />
			<input id="upload_media_button" type="button" value="Media Library" class="button-secondary" />
			<p class="description"><?php _e('Enter a media URL or use a file from the Media Library', 'dicentis' ); ?></p>
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo-mediatype">Media type</label>
		</th>
		<td>
			<input type="text" id="dipo-mediatype" name="dipo-mediatype" value="<?php echo esc_attr( $dipo_mediatype ); ?>" />
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo_image">Episode Image</label>
		</th>
		<td>
			<input id="dipo-image" type="text" size="75"
		name="dipo-image" value="<?php echo esc_url( $dipo_image ); ?>" />
			<input id="upload_media_button" type="button" value="Media Library" class="button-secondary" />
			<p class="description"><?php _e('Enter a media URL or use a file from the Media Library', 'dicentis' ); ?></p>
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo-guid">guid</label>
		</th>
		<td>
			<input type="text" id="dipo-guid" name="dipo-guid" value="<?php echo esc_attr( $dipo_guid ); ?>" />
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo-duration">Duration</label>
		</th>
		<td>
			<input type="number" id="dipo-duration" name="dipo-duration" value="<?php echo esc_attr( $dipo_duration ); ?>" />
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dipo-explicit">Explicit</label>
		</th>
		<td>
			<input type="checkbox" id="dipo-explicit" name="dipo-explicit" value="<?php echo esc_attr( $dipo_explicit ); ?>" />
			<p class="description"><?php _e( 'Use of explicit content in the title, description, language or cover art of the podcast.', 'dicentis' ); ?></p>
		</td>
	</tr>
</table>
