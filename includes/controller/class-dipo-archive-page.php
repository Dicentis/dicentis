
	public function podcast_archive_template( $archive_template ) {
		global $post;

		//  if ( is_post_type_archive ( Dicentis_Podcast_CPT::POST_TYPE ) ) {
		// 	$archive_template = dirname( __FILE__ ) . '/templates/podcast-archive.php';
		// }

		return $archive_template;
	}