<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2><?php _e( 'Dicentis Podcast Settings', 'dicentis'); ?></h2>
	<form method="POST" action="options.php">
		<?php settings_fields( 'dipo_itunes_options' ); ?>
		<?php do_settings_sections( 'dipo_itunes' ); ?>

		<?php submit_button(); ?>
	</form>
</div>
