<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage dicentis
 * @since dicentis 1.0
 */

$meta_link = get_post_meta( $post->ID, '_dicentis_podcast_medialink', true );
$file_parts = pathinfo( $meta_link );
switch( $file_parts['extension'] ) {
	case "mp3":
	case "ogg":
	case "wma":
	case "m4a":
	case "wav":
		$format = "audio";
	break;

	case "mp4":
	case "m4v":
	case "webm":
	case "ogv":
	case "wmv":
	case "flv":
		$format = "video";
	break;

	default:
		$format = "";
	break;
}
$episode = array(
	'src'      => $meta_link,
	'loop'     => '',
	'autoplay' => '',
	'preload' => 'none'
	);

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) ) : ?>
						<div class="entry-meta">
							<span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'dicentis' ) ); ?></span>
						</div>
						<?php
							endif;

							if ( is_single() ) :
								the_title( '<h1 class="entry-title">', '</h1>' );
							else :
								the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
							endif;
						?>

						<div class="entry-meta">
							<?php
								if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
							?>
							<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'dicentis' ), __( '1 Comment', 'dicentis' ), __( '% Comments', 'dicentis' ) ); ?></span>
							<?php
								endif;

								edit_post_link( __( 'Edit', 'dicentis' ), '<span class="edit-link">', '</span>' );
								$taxonomy_names = get_the_taxonomies();
								foreach ($taxonomy_names as $key => $value) {
									echo $value;
									echo " | ";
								}
							?>
						</div><!-- .entry-meta -->
					</header><!-- .entry-header -->

					<?php if ( is_search() ) : ?>

					<?php else : ?>
					<div class="entry-content">
						<?php

							if ( has_post_thumbnail() ) the_post_thumbnail();

							the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'dicentis' ) );

							if ( 0 == strcmp( $format, "audio" ) ) {
								echo wp_audio_shortcode( $episode );
							} else if ( 0 == strcmp( $format, "video" ) ) {
								echo wp_video_shortcode( $episode );
							}
						?>
					</div><!-- .entry-content -->
					<?php endif; ?>

					<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
				</article><!-- #post-## --> <?php 
					// Previous/next post navigation.
					twentyfourteen_post_nav();

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
