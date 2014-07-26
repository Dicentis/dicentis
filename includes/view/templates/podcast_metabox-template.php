<div class="metabox-tabs-div">

	<ul id="metabox-tabs" class="metabox-tabs">
		<li class="active dipo_tab_general">
			<a class="active" href="javascript:void(null);"><?php _e( 'General', $this->textdomain ); ?></a>
		</li>
		<li class="dipo_tab_mediafiles">
			<a href="javascript:void(null);"><?php _e( 'Mediafiles', $this->textdomain ); ?></a>
		</li>
	</ul>

	<div class="dipo_tab_general">
		<div class="dipo_metabox_field">

			<div class="dipo_label">
				<label for="dipo_subtitle"><strong><?php _e( 'Subtitle', $this->textdomain ); ?></strong></label>
			</div>

			<div class="dipo_field">
				<input type="text" id="dipo_subtitle" name="dipo_subtitle" value="<?php echo esc_attr( $dipo_subtitle ); ?>" />
				<p class="dipo_char_count"><?php _e( 'Characters left: ', $this->textdomain ); ?><span id="subtitle_counter" class="counter">0</span></p>
			</div>

		</div>

		<div class="dipo_metabox_field">

			<div class="dipo_label">
				<label for="dipo_summary"><strong><?php _e( 'Summary', $this->textdomain ); ?></strong></label>
			</div>

			<div class="dipo_field">
				<textarea id="dipo_summary" name="dipo_summary" ><?php echo esc_attr( $dipo_summary ); ?></textarea>
				<p class="dipo_char_count"><?php _e( 'Characters left: ', $this->textdomain ); ?><span id="summary_counter" class="counter">0</span></p>
			</div>

		</div>

		<div class="dipo_metabox_field">

			<div class="dipo_label">
				<label for="dipo_image"><strong><?php _e( 'Episode Image', $this->textdomain ); ?></strong></label>
			</div>

			<div class="dipo_field">
				<input id="dipo_image" type="text" name="dipo_image" value="<?php echo esc_url( $dipo_image ); ?>" />
				<!-- <input id="upload_media_button" type="button" value="Media Library" class="button-secondary" /> -->
				<p class="description"><?php _e('Enter a media URL or use a file from the Media Library', $this->textdomain ); ?></p>
			</div>

		</div>

		<!-- <div class="dipo_metabox_field">

			<div class="dipo_label">
				<label for="dipo_guid"><strong><?php _e( 'GUID', $this->textdomain ); ?></strong></label>
			</div>

			<div class="dipo_field">
				<p><?php echo $dipo_guid; ?> <a href=#><?php _e( 'Regenerate', $this->textdomain ); ?></a></p>
			</div>

		</div> -->

		<div class="dipo_metabox_field">

			<div class="dipo_label">
				<label for="dipo_explicit"><strong><?php _e( 'Explicit', $this->textdomain ); ?></strong></label>
			</div>

			<div class="dipo_field">
				<input type="checkbox" id="dipo_explicit" name="dipo_explicit" value="explicit"
					<?php if ( strcmp( $dipo_explicit, 'explicit' ) == 0 ) echo "checked"; ?> />
				<p class="description"><?php _e( 'Use of explicit content in the title, description, language or cover art of the podcast.', 'dicentis' ); ?></p>
			</div>

		</div>
	</div>

	<div class="dipo_tab_mediafiles">
		<div id="dipo_tab_media" class="dipo_metabox_field">

			<?php 
			/* @TODO Information for mediatypes and that one type should only appear once per post */
			if ( empty($mediafiles) ) :
				$media_count = 1; ?>
				<div id="dipo_div_wrapper1" class="dipo_file_wrapper">

					<div class="dipo_mediafile_wrapper dipo_medialink">
						<input id="dipo_mediafile1" type="hidden" name="dipo_mediafile1" value="update" />

						<div class="dipo_mediafile_labels" >
							<label class="dipo_media_link_label" name="dipo_mediafile1_link"><?php _e( 'Medialink', $this->textdomain ); ?></label>
							<div file="1" class="remove_mediafile button-secondary"><i class="dashicons-before dashicons-trash"></i><?php _e('Remove this mediafile', $this->textdomain ); ?></div>
						</div>

						<input id="dipo_mediafile1_link" type="text" name="dipo_mediafile1_link" value="" />

					</div>
					<div class="dipo_mediafile_wrapper dipo_mediafile_meta">

						<div class="dipo_mediafile_labels" >
							<label class="dipo_media_type_label" name="dipo_mediafile1_type"><?php _e( 'Media Type', $this->textdomain ); ?></label>
							<label class="dipo_media_duration_label" name="dipo_mediafile1_duration"><?php _e( 'Duration', $this->textdomain ); ?></label>
							<label class="dipo_media_size_label" name="dipo_mediafile1_size"><?php _e( 'Filesize', $this->textdomain ); ?></label>
						</div>

						<?php echo \Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type::get_select_mediatypes(); ?>
						<input id="dipo_mediafile1_duration" type="text" name="dipo_mediafile1_duration" value="" />
						<input id="dipo_mediafile1_size" type="text" name="dipo_mediafile1_size" value="" />

					</div>

				</div>
			<?php else :
				foreach ( $mediafiles as $key => $mediafile ) : ?>
				<div id="dipo_div_wrapper<?php echo $mediafile['id']; ?>" class="dipo_file_wrapper">

					<div class="dipo_mediafile_wrapper dipo_medialink">
						<input id="dipo_mediafile<?php echo $mediafile['id']; ?>" type="hidden" name="dipo_mediafile<?php echo $mediafile['id']; ?>" value="update" />

						<div class="dipo_mediafile_labels" >
							<label class="dipo_media_link_label" name="dipo_mediafile<?php echo $mediafile['id']; ?>_link"><?php _e( 'Medialink', $this->textdomain ); ?></label>
							<div file="<?php echo $mediafile['id']; ?>" class="remove_mediafile button-secondary"><i class="dashicons-before dashicons-trash"></i><?php _e('Remove this mediafile', $this->textdomain ); ?></div>
						</div>

						<input id="dipo_mediafile<?php echo $mediafile['id']; ?>_link" type="text" name="dipo_mediafile<?php echo $mediafile['id']; ?>_link" value="<?php echo esc_url( $mediafile['medialink'] ); ?>" />

					</div>
					<div class="dipo_mediafile_wrapper dipo_mediafile_meta">

						<div class="dipo_mediafile_labels" >
							<label class="dipo_media_type_label" name="dipo_mediafile<?php echo $mediafile['id']; ?>_type"><?php _e( 'Media Type', $this->textdomain ); ?></label>
							<label class="dipo_media_duration_label" name="dipo_mediafile<?php echo $mediafile['id']; ?>_duration"><?php _e( 'Duration', $this->textdomain ); ?></label>
							<label class="dipo_media_size_label" name="dipo_mediafile<?php echo $mediafile['id']; ?>_size"><?php _e( 'Filesize', $this->textdomain ); ?></label>
						</div>

						<?php echo \Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type::get_select_mediatypes( $mediafile['id'], $mediafile['mediatype'] ); ?>
						<input id="dipo_mediafile<?php echo $mediafile['id']; ?>_duration" type="text" name="dipo_mediafile<?php echo $mediafile['id']; ?>_duration" value="<?php echo $mediafile['duration']; ?>" />
						<input id="dipo_mediafile<?php echo $mediafile['id']; ?>_size" type="text" name="dipo_mediafile<?php echo $mediafile['id']; ?>_size" value="<?php echo $mediafile['filesize']; ?>" /> (<?php echo $this->human_readable_filesize($mediafile['filesize']); ?>)

					</div>

				</div>
			<?php endforeach;
				endif; ?>

		</div>

		<input id="dipo_mediafiles_count" name="dipo_mediafiles_count" type="hidden" value="<?php echo $media_count ?>" />
		<div id="add_mediafile" class="button-primary"><i class="dashicons-before dashicons-admin-media"></i><?php _e('Add Mediafile', $this->textdomain ); ?></div>
		<p class="description"><?php _e('Enter a media URL or use a file from the Media Library. The duration should be formatted HH:MM:SS, H:MM:SS, MM:SS, or M:SS (H = hours, M = minutes, S = seconds). The filesize should be in Bytes. If no filesize is given it trys to calculate the filesize automatically (saving takes longer).', $this->textdomain ); ?></p>
	</div>

</div>
