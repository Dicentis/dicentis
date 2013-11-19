<div class="wrap">
	<h2>Dicentis</h2>
	<form method="post" action="options.php">
		<?php @settings_fields( 'dicentis-group' ); ?>
		<?php @do_settings_fields( 'dicentis-group' ); ?>

		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="setting_a">Setting A</label></th>
				<td><input type="text" name="setting_a" id="setting_a" value="<?php echo get_option('setting_a'); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="setting_b">Setting B</label></th>
				<td><input type="text" name="setting_b" id="setting_b" value="<?php echo get_option('setting_b'); ?>" /></td>
			</tr>
		</table>

		<?php @submit_button(); ?>
	</form>
</div>
