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

	$('#add_mediafile').click( function() {
		var id = parseInt( $('#dipo_mediafiles_count').val() ) + 1;
		$('#dipo_mediafiles_count').val(id);

		var new_file = '<tr>' +
			'<input id="dipo_mediafile' + id +
				'" type="hidden" name="dipo_mediafile' + id +
				'" value="update" />';
		new_file += '<td><input id="dipo_mediafile' + id +
				'_link" type="text" name="dipo_mediafile' + id +
				'_link" value="" /></td>';
		new_file += '<td class="mediatype-list"></td>';
		new_file += '<td><input id="dipo_mediafile' + id +
				'_duration" type="text" name="dipo_mediafile' + id +
				'_duration" value="" /></td>';
		new_file += '<td><input id="dipo_mediafile' + id +
				'_size" type="text" name="dipo_mediafile' + id +
				'_size" value="" /></td>' +
			'<td>';
		new_file += '<div file="' + id +
			'" class="remove_mediafile button-secondary"><i class="dashicons-before dashicons-trash"></i>' +
			'Remove</div>' +
			'</td>' +
			'</tr>';
		$('#dipo_mediafiles_table > tbody:last').append( new_file );

		$('.mediatype-list').append( get_select_mediatypes( id, $('#dipo_mediafile1_type') ) );
		$('.mediatype-list').removeClass('mediatype-list')
	});

	$('.remove_mediafile').live("click", function( event ) {
		var id = $(this).attr('file');
		$(this).parent().parent().css('display', 'none');
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

function get_select_mediatypes( id, se ) {
	var select_tag = se.clone().attr('id', 'dipo_mediafile' + id + '_type' );
	select_tag.attr('name', 'dipo_mediafile' + id + '_type' );
	return select_tag;
}
