<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h3><?php _e( 'Import old Podcast Feeds', DIPO_TEXTDOMAIN ); ?></h3>

<?php if ( isset( $result ) && -1 === $result[0] ) : ?>

	<div class="error settings-error">
		<h3><?php _e( 'Import Error', DIPO_TEXTDOMAIN ); ?></h3>
		<strong><?php echo $result[1]; ?></strong>
	</div>

<?php elseif ( isset( $result ) && 1 === $result[0] ) : ?>

	<div class="updated settings-error">
		<h3><?php _e( 'Import Successful', DIPO_TEXTDOMAIN ); ?></h3>
		<strong><?php echo sprintf( __( 'Congratulation! %s episode was successfully imported.', DIPO_TEXTDOMAIN ), $result[0] ); ?></strong>
	</div>

<?php elseif ( isset( $result ) && 0 < $result[0] ) : ?>

	<div class="updated settings-error">
		<h3><?php _e( 'Import Successful', DIPO_TEXTDOMAIN ); ?></h3>
		<strong><?php echo sprintf( __( 'Congratulation! %s episodes were successfully imported.', DIPO_TEXTDOMAIN ), $result[0] ); ?></strong>
	</div>

<?php elseif ( isset( $result ) ) : ?>

	<div class="error settings-error">
		<h3><?php _e( 'Import Error', DIPO_TEXTDOMAIN ); ?></h3>
		<strong><?php _e( 'An unknown Error occured. :( If the error persists please contact the plugin author and provide detailed information.', DIPO_TEXTDOMAIN ); ?></strong>
	</div>

<?php endif; ?>

	<div><p class="description"><?php _e( 'You can import episodes from an old RSS Feed. Just enter the RSS Url in the form below, choose a show for these episodes and hit Import Feed.', DIPO_TEXTDOMAIN ); ?></p></div>
	<form method="POST" action="">
		<div>
			<label name="dipo_feed_url"><?php _e( 'Feed URL', DIPO_TEXTDOMAIN ); ?></label>
			<?php if ( isset( $_POST['dipo_feed_url'] ) ) $feed_url = $_POST['dipo_feed_url'];
				  else $feed_url = ''; ?>
			<input type="text" id="dipo_feed_url" name="dipo_feed_url" value="<?php echo $feed_url; ?>" />
		</div>

	<?php if ( !empty( $shows ) && !is_wp_error( $shows ) ) : ?>
		<div>
			<label name="dipo_show_select"><?php _e( 'Import as Show', DIPO_TEXTDOMAIN ); ?></label>
			<select id="dipo_show_select" name="dipo_show_select">
				<?php foreach ( $shows as $show ) { ?>
					<option value="<?php echo $show->slug; ?>"><?php echo $show->name ?></option>
				<?php } ?>
			</select>
		</div>
	<?php endif; ?>
		<?php submit_button( __( 'Import Feed', DIPO_TEXTDOMAIN ), 'primary', 'dipo_import_btn' ); ?>
	</form>
</div>
