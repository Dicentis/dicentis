<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h3><?php _e( 'Import old Podcast Feeds', $this->textdomain ); ?></h3>

<?php if ( isset( $result ) && -1 === $result['imported'] ) : ?>

	<div class="error settings-error">
		<h3><?php _e( 'Import Error', $this->textdomain ); ?></h3>
		<p><strong><?php echo $result[0]; ?></strong></p>
	</div>

<?php elseif ( isset( $result ) && 1 === $result['imported'] ) : ?>

	<div class="updated settings-error">
		<h3><?php _e( 'Import Successful', $this->textdomain ); ?></h3>
		<p><strong><?php echo sprintf( __( 'Congratulation! %s episode was successfully imported.', $this->textdomain ), $result['imported'] ); ?></strong></p>
	</div>

<?php elseif ( isset( $result ) && 0 < $result['imported'] ) : ?>

	<div class="updated settings-error">
		<h3><?php _e( 'Import Successful', $this->textdomain ); ?></h3>
		<p><strong><?php echo sprintf( __( 'Congratulation! %s episodes were successfully imported.', $this->textdomain ), $result['imported'] ); ?></strong></p>
		<p><?php echo sprintf( __( 'Out of %s: New episodes (%s); Updated episodes (%s)', $this->textdomain ), $result['imported'], $result['created'], $result['updated'] ); ?></p>
	</div>

<?php elseif ( isset( $result ) ) : ?>

	<div class="error settings-error">
		<h3><?php _e( 'Import Error', $this->textdomain ); ?></h3>
		<p><strong><?php _e( 'An unknown Error occured. :( If the error persists please contact the plugin author and provide detailed information.', $this->textdomain ); ?></strong></p>
	</div>

<?php endif; ?>

	<div><p class="description"><?php _e( 'You can import episodes from an old RSS Feed. Just enter the RSS Url in the form below, choose a show for these episodes and hit Import Feed.', $this->textdomain ); ?></p></div>

	<form method="POST" action="">
		<div class="dipo_field_wrapper">

			<div class="dipo_label">
				<label name="dipo_feed_url"><?php _e( 'Feed URL', $this->textdomain ); ?></label>
			</div>

			<?php if ( isset( $_POST['dipo_feed_url'] ) ) $feed_url = $_POST['dipo_feed_url'];
				  else $feed_url = ''; ?>
			<div class="dipo_field">
				<input type="text" id="dipo_feed_url" name="dipo_feed_url" placeholder="http://www.my-blog.com/feed" value="<?php echo $feed_url; ?>" />
			</div>
		</div>

	<?php if ( !empty( $shows ) && !is_wp_error( $shows ) ) : ?>
		<div class="dipo_field_wrapper">

			<div class="dipo_label">
				<label name="dipo_show_select"><?php _e( 'Import as Show', $this->textdomain ); ?></label>
			</div>

			<div class="dipo_field">
				<select id="dipo_show_select" name="dipo_show_select">
					<?php foreach ( $shows as $show ) { ?>
						<option value="<?php echo $show->slug; ?>"><?php echo $show->name ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	<?php endif; ?>

		<div class="dipo_field_wrapper">

			<div class="dipo_label">
				<label name="dipo_feed_match"><?php _e( 'Try to match with existing episodes', $this->textdomain ); ?></label>
			</div>

			<div class="dipo_field">
				<input type="checkbox" id="dipo_feed_match" name="dipo_feed_match" />
				<p class="description"><?php _e( 'If this option is checked Dicentis tries to find a match. That means if an episode with the same date already exists the episode will be merge with the one in WordPress and only the additional medialink is added.', $this->textdomain ); ?></p>
			</div>
		</div>
		<?php submit_button( __( 'Import Feed', $this->textdomain ), 'primary', 'dipo_import_btn' ); ?>
	</form>
</div>
