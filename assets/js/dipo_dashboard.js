jQuery(document).ready(function() {
	jQuery('#dipo-link-generator').removeAttr('style');
	generate_feed();

	jQuery('#dipo-podcast-shows').change(function (evt) {
		evt.preventDefault();
		generate_feed();
	});

	jQuery('#dipo_mediafile1_type').change(function (evt) {
		evt.preventDefault();
		generate_feed();
	});
});

function generate_feed () {

	var permalink_structure = jQuery('#permalink_structure').val();
	var show = jQuery('#dipo-podcast-shows').val();
	var type = jQuery('#dipo_mediafile1_type  option:selected').text();
	var link = '';

	if ( permalink_structure === 'enabled' ) {
		link = generate_permalink_feed( show, type );
	} else {
		link = generate_default_feed( show, type );
	};

	jQuery('#dipo-feed-link').html( link );
}

function generate_permalink_feed( show, type ) {
	return "http://dicentis.dev/podcast/show/" + show + "/feed/" + type;
}

function generate_default_feed( show, type ) {
	return "http://dicentis.dev/?post_type=dipo_podcast&podcast_show=" + show + "&feed=" + type;
}