jQuery(document).ready(function() {
	jQuery('.dipo_copyright').click( function(){
		var copyright = jQuery(this).attr('data-copyright');
		var value = copyright + " " + jQuery('#dipo_itunes_copyright').val();
		jQuery('#dipo_itunes_copyright').val( value );
	});
});
