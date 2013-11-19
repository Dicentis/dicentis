<?php
	// retrieve the metadata values if they exist
	$dicentis_meta_a = get_post_meta( $post->ID, '_meta_a', true );
	$dicentis_meta_b = get_post_meta( $post->ID, '_meta_b', true );
	$dicentis_meta_c = get_post_meta( $post->ID, '_meta_c', true );
	$dicentis_medialink = get_post_meta( $post->ID, '_dicentis_podcast_medialink', true );
?>

<table>
	<tr valign="top">
		<th class="metabox_label_column">
			<label for="dicentis_medialink">Media Link</label>
		</th>
		<td>
			<input id="dicentis-podcast-medialink" type="text" size="75"
		name="dicentis-podcast-medialink" value="<?php echo esc_url( $dicentis_medialink ); ?>" />
			<input id="upload_media_button" type="button" value="Media Library" class="button-secondary" />
			<br /> Enter a media URL or use a file from the Media Library
		</td>
	</tr>
	<tr valign="top">
		<th class="metabox_label_column">
			<label for="meta_a">Meta A</label>
		</th>
		<td>
			<input type="text" id="meta_a" name="meta_a" value="<?php echo esc_attr( $dicentis_meta_a ); ?>" />
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="meta_b">Meta B</label>
		</th>
		<td>
			<input type="text" id="meta_b" name="meta_b" value="<?php echo esc_attr( $dicentis_meta_b ); ?>" />
		</td>
	</tr>

	<tr valign="top">
		<th class="metabox_label_column">
			<label for="meta_c">Meta C</label>
		</th>
		<td>
			<input type="text" id="meta_ac" name="meta_c" value="<?php echo esc_attr( $dicentis_meta_c ); ?>" />
		</td>
	</tr>
</table>
