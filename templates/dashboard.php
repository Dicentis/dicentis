<?php
function dp_get_feed_link( $show_slug, $feed = 'rss' ) {
	$show_feed_rss2 = trailingslashit( get_home_url() ) .
		"?post_type=podcast&podcast_show=" . $show_slug . "&feed=rss2";
	$show_feed_itunes = trailingslashit( get_home_url() ) .
		"?post_type=podcast&podcast_show=" . $show_slug . "&feed=itunes";

	switch ( $feed ) {
		case 'itunes':
			$link = $show_feed_itunes;
			break;

		case 'rss':
		default:
			$link = $show_feed_rss2;
			break;
	}

	return $link;
}
?>
<div class="wrap">
	<div id="icon-themes" class="icon32"></div><h2>Dashboard</h2>
	<p><?php _e('This Dashboard provides you alle the needed information about your podcast shows.', 'dicentis'); ?></p>

	<?php
		if ( taxonomy_exists( 'podcast_show' ) ) {
			$terms = get_terms('podcast_show');
			foreach ( $terms as $show ) { ?>
				<div class="dp_dashboard_feeds">
				<?php foreach ( $show as $key => $value) {
					if ( !strcmp( $key, 'name' ) ) { ?>
							<h4><?php echo $value; ?></h4>
					<?php } else if ( !strcmp( $key, 'slug' ) ) { ?>
							<p class="dp_dashboard_feeds_rss"><?php echo dp_get_feed_link( $value, 'rss' ); ?></p>
							<p class="dp_dashboard_feeds_itunes"><?php echo dp_get_feed_link( $value, 'itunes' ); ?></p>
					<?php }
				} ?>
				</div>
			<?php }
		}
	?>

</div>
