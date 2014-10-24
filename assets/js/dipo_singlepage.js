jQuery(document).ready(function() {

	jQuery('#dipo_files_js').css('visibility', 'visible');
	jQuery('#dipo_files_js').css('position', 'relative');
	jQuery('#dipo_files_nojs').css('visibility', 'hidden');

	jQuery('#dipo_file_select').change(function() {
		var link = jQuery('#dipo_file_select').val();
		jQuery('#dipo_downlod_btn').attr('href', link);
	});
});