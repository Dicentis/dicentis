<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<form method="POST" action="options.php">
		<?php settings_fields( 'dipo_general_options' ); ?>
		<?php do_settings_sections( 'dipo_general' ); ?>

		<?php submit_button(); ?>
	</form>
</div>
