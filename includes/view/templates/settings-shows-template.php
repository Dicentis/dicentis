<div id="dipo_subheader" class="wrap">
<?php
	$all_shows = __( 'All Shows', $this->textdomain );
	echo "<a href='?post_type=dipo_podcast&page=dicentis_settings&tab=shows&show=all_shows'>{$all_shows}</a>";
	foreach ( $shows as $show ) { ?>
 | <a href='?post_type=dipo_podcast&page=dicentis_settings&tab=shows&show=<?php echo $show->slug; ?>'><?php echo esc_attr( $show->name ); ?></a>
	<?php }
?>
</div>

<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h1><?php  _e( sprintf( 'Settings for "%s"', $active_show_title ), $this->textdomain ); ?></h1>
	<hr>
	<form method="POST" action="options.php">
		<?php settings_fields( 'dipo_' . $active_show . '_options' ); ?>
		<?php do_settings_sections( 'dipo_' . $active_show ); ?>
		<hr />
		<?php do_settings_sections( 'dipo_' . $active_show . '_iTunes' ); ?>
		<hr />

		<?php submit_button(); ?>
	</form>
</div>