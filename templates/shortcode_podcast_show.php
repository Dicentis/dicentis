<?php if( $category_posts->have_posts() ) :
		$show_tax = get_term_by( 'slug', $show, 'podcast_show' ); ?>
		<h2><?php echo $show_tax->name; ?></h2>
		<table>
		<tr>
			<td><?php _e( 'Title', 'dicentis' ); ?></td>
			<td><?php _e( 'Date', 'dicentis' ); ?></td>
			<td><?php _e( 'Speaker', 'dicentis' ); ?></td>
			<td><?php _e( 'Series', 'dicentis' ); ?></td>
			<td><?php _e( 'Media', 'dicentis' ); ?></td>
		</tr>
		<?php foreach ( $episodes as $id => $post ) :
				$speaker = NULL;
				if ( isset( $post->taxonomies[$speaker_tax] ) ) {
					// Find first : and start after whitespace which comes after :
					$pos = strpos( $post->taxonomies[$speaker_tax], ':' );
					$speaker = substr( $post->taxonomies[$speaker_tax], $pos+2 );
					// skip period at end
					$speaker = substr( $speaker, 0, strlen( $speaker)-1 );
				}

				$series = NULL;
				if ( isset( $post->taxonomies[$series_tax] ) ) {
					// Find first : and start after whitespace which comes after :
					$pos = strpos( $post->taxonomies[$series_tax], ':' );
					$series = substr( $post->taxonomies[$series_tax], $pos+2 );
					// skip period at end
					$series = substr( $series, 0, strlen( $series)-1 );
				}

				$date = new DateTime( $post->post_date ); ?>

				<tr>
				<td><a href="<?php echo $post->guid; ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a></td>
				<td><?php echo $date->format('jS F'); ?></td>
				<td><?php echo ( !is_null( $speaker ) ) ? $speaker : "" ; ?></td>
				<td><?php echo ( !is_null( $series ) ) ? $series : "" ; ?></td>
				<td><a href="<?php echo $post->metadata['_dipo_medialink'][0]; ?>" title="Medialink for '<?php echo $post->post_title; ?>'">Medialink</a></td>
				</tr>

		<?php endforeach; ?>
		</table>

	<?php else:

	switch ( $err ) {
		case 1:
		default:
			echo "<p>";
			_e( 'Error #1: No Episodes Found!', 'dicentis' );
			echo "</p>";
			break;

		case 2:
			echo "<p>";
			_e( 'Error #2: No Showname in Shortcode Given!', 'dicentis' );
			echo "</p>";
			break;

		case 3:
			echo "<p>";
			_e( 'Error #3: No Show Exists with That Name!', 'dicentis' );
			echo "</p>";
			break;
	}

endif; ?>