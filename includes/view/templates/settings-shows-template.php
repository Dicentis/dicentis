<div id="dipo_subheader" class="wrap">
<?php
	$all_shows = __( 'All Shows', $this->textdomain );
	echo "<a href='?post_type=dipo_podcast&page=dicentis_settings&tab=shows&show=all'>{$all_shows}</a>";
	foreach ( $shows as $show ) { ?>
 | <a href='?post_type=dipo_podcast&page=dicentis_settings&tab=shows&show=<?php echo $show->slug; ?>'><?php echo esc_attr( $show->name ); ?></a>
	<?php }
?>
</div>
<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<?php
	$a = array_search( $active_show, $shows );
	var_dump( $a );
	?>
	<h1><?php echo $active_show; ?></h1>
	<form method="POST" action="options.php">
		<?php settings_fields( 'dipo_general_options' ); ?>
		<?php do_settings_sections( 'dipo_general' ); ?>

		<?php submit_button(); ?>
	</form>
</div>

	<?php
	// <debug>
echo "<pre>";
var_dump( $shows );
echo "</pre>";
// </debug>
?>