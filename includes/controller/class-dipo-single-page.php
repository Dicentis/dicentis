
	public function single_template( $single_template ) {
		global $post;

		if ( $post->post_type == Dipo_Podcast_Post_Type::POST_TYPE ) {
			$single_template = dirname( __FILE__ ) . '/templates/episode-single-template.php';
		}

		return $single_template;
	}