<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Fourteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage dicentis
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						_e( 'Podcast Archives', 'dicentis' );
					?>
				</h1>
			</header><!-- .page-header -->

			<table>
				<thead>
					<tr>
						<th>Thema</th>
						<th>Datum</th>
						<th>Speaker</th>
						<th>Show 1</th>
					</tr>
				</thead>
				<tbody>
				
			<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();
						echo "<tr>";

						the_title( '<td>', '</td>');
						echo "<td>";
							echo get_the_date( 'd - m - Y');
						echo "</td>";

						echo "<td>";
							$terms = get_the_terms( $post->ID , 'podcast_speaker' );
							$count = 1;
							foreach ( $terms as $term ) {
								$term_link = get_term_link( $term, 'podcast_speaker' );
								// echo "<a href='".$term_link."'>" . $term->name . "</a>";
								if ( count( $terms ) > $count ) {
									echo " | ";
									$count++;
								}
							}
						echo "</td>";

						echo "<td>";
							$terms = get_the_terms( $post->ID , 'podcast_show' );
							$count = 1;
							foreach ( $terms as $term ) {
								$term_link = get_term_link( $term, 'podcast_show' );
								echo "<a href='".$term_link."'>" . $term->name . "</a>";
								if ( count( $terms ) > $count ) {
									echo " | ";
									$count++;
								}
							}
						echo "</td>";

						echo "</tr>";
					endwhile;
			?>
				</tbody>
			</table>
			<?php

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php
// get_sidebar( 'content' );
// get_sidebar();
get_footer();
