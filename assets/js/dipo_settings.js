jQuery(document).ready(function() {
	jQuery('.dipo_copyright').click( function(){
		var copyright = jQuery(this).attr('data-copyright');
		var value = copyright + " " + jQuery('#dipo_itunes_copyright').val();
		jQuery('#dipo_itunes_copyright').val( value );
	});

	jQuery('#upload_image_button').click(function() {
		formfield = jQuery('#upload_image').attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery('#upload_image').val(imgurl);
		tb_remove();
	}
});