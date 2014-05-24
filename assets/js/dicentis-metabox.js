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
		char_counter( this, $('#subtitle_counter'), 255 );
	}).triggerHandler('keyup');

	$('#dipo_summary').keyup( function() {
		char_counter( this, $('#summary_counter'), 4000 );
	}).triggerHandler('keyup');

	$('#add_mediafile').on('click', function() {
		var id = parseInt( $('#dipo_mediafiles_count').val() ) + 1;
		$('#dipo_mediafiles_count').val(id);

		var $cp = $('#dipo_div_wrapper1').clone();
		$cp.removeAttr('style');
		$cp.attr('id', 'dipo_div_wrapper' + id);

		var file = 'dipo_mediafile' + id;
		$cp.children().children('#dipo_mediafile1').attr('id', file ).attr('name', file );

		var link = file + '_link';
		$cp.children().children().children('.dipo_media_link_label').attr('name', link );
		$cp.children().children().children('.remove_mediafile').attr('file', id );
		$cp.children().children('#dipo_mediafile1_link').attr('id', link ).attr('name', link ).val('');

		var type     = file + '_type';
		var duration = file + '_duration';
		var size     = file + '_size';
		$cp.children().children().children('.dipo_media_type_label').attr('name', type );
		$cp.children().children().children('.dipo_media_duration_label').attr('name', duration );
		$cp.children().children().children('.dipo_media_size_label').attr('name', size );

		$cp.children().children('#dipo_mediafile1_type').attr('id', type).attr('name', type).children('option:selected').val('audio/mpeg');
		$cp.children().children('#dipo_mediafile1_duration').attr('id', duration).attr('name', duration).val('');
		$cp.children().children('#dipo_mediafile1_size').attr('id', size).attr('name', size).val('');

		$('#dipo_tab_media').append( $cp );
	});

	$(document).on('click', '.remove_mediafile', function() {
		var id = $(this).attr('file');
		$(this).parent().parent().parent().css('display', 'none');
		$('#dipo_mediafile' + id).val('remove');
	});
});

function char_counter ( input, counter, limit ) {
	var n = input.value.replace(/{.*?}/g, '').length;
	if ( n > limit ) {
		input.value = input.value.substr(0, input.value.length + limit - n );
		n = limit;
	}

	counter.text( limit - n );
}
