<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h3><?php _e( 'Import old Podcast Feeds', DIPO_TEXTDOMAIN ); ?></h3>

	<form method="POST" action="">
		<div>
			<label name="dipo_feed_url"><?php _e( 'Feed URL', DIPO_TEXTDOMAIN ); ?></label>
			<input type="text" id="dipo_feed_url" name="dipo_feed_url" />
		</div>

	<?php if ( !is_wp_error( $shows ) ) : ?>
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

<?php if ( isset( $feed_array ) and !empty( $feed_array ) ) : ?>
	<ul>
<?php foreach ( $feed_array[1] as $item ) : ?>
		<li><?php echo $item->get_title(); ?></li>
	<?php endforeach; ?>
	</ul>
<?php endif;//else : ?>
</div>
