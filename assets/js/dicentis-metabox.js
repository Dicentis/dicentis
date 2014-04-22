jQuery(document).ready(function($) {

	var formfield = null;

	$('#upload_media_button').click(function() {
		$('html').addClass('Image');
		formfield = $('#dicentis-podcast-medialink').attr('name');
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		return false;
	});

	// user inserts file into post
	// only run custom if user started process using the above process
	// window.send_to_editor(html) is how wp normaly handle the received data

	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html) {
		var fileurl;

		if( formfield != null ) {
			fileurl = $('img', html).attr('src');

			$('#dicentis-podcast-medialink').val(fileurl);

			tb_remove();

			$('html').removeClass('Image');
			formfield = null;
		} else {
			window.original_send_to_editor(html);
		}
	};

	// Update Counter 
	$('#dipo_subtitle').keyup( function() {
		char_counter( this, $('#subtitle_counter'), 255 )
	}).triggerHandler('keyup');

	$('#dipo_summary').keyup( function() {
		char_counter( this, $('#summary_counter'), 4000 )
	}).triggerHandler('keyup');
});

function char_counter ( input, counter, limit ) {
	var n = input.value.replace(/{.*?}/g, '').length;
	if ( n > limit ) {
		input.value = input.value.substr(0, input.value.length + limit - n );
		n = limit;
	}

	counter.text( limit - n );
}
