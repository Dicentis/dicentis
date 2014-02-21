<?php
/**
 * RSS2 Feed Template for displaying RSS2 Posts feed.
 *
 * @package WordPress
 */

require_once dirname( __FILE__ ) . '/../lib/itunes-categories.php';

function dp_get_show_details( $type = 'name' ) {
	$slug = $_GET['podcast_show'];

	switch ( $type ):
		case 'name':
			$value = get_term_by( 'slug', $slug, 'podcast_show')->name;
			echo " > " . $value;
		break;

		case 'description':
			echo get_term_by( 'slug', $slug, 'podcast_show')->description;
		break;

		default:
			echo "";
	endswitch;
}

function dp_get_speaker( $id ) {
	$terms = get_the_terms( $id , 'podcast_speaker' );
	$text = "";
	$count = 1;
	foreach ($terms as $term) {
		$text .= $term->name;
		if ( count( $terms ) > $count ) {
			$text .= ", ";
			$count++;
		}
	}
	echo $text;
}

// get iTunes specific options from DB
$itunes_opt = get_option( 'dipo_itunes_options' );


header( 'Content-Type: application/rss+xml; charset=' . get_option( 'blog_charset' ), true );
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>
<rss version="2.0"
	xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php do_action('rss2_ns'); ?>
>

<channel>
	<title><?php bloginfo_rss('name'); dp_get_show_details(); ?></title>
	<link><?php bloginfo_rss( 'url' ) ?></link>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<!-- TODO: put in settings -->
	<language><?php
		$iso_code = preg_replace('/[_]/', '-', $itunes_opt['itunes_language']);
		echo $iso_code;
	?></language>
	<copyright>&#x2117; &amp; &#xA9; 2005 John Doe &amp; Family</copyright>
	<itunes:subtitle><?php echo $itunes_opt['itunes_subtitle']; ?></itunes:subtitle>
	<itunes:author><?php echo $itunes_opt['itunes_author']; ?></itunes:author>
	<itunes:summary><?php dp_get_show_details( 'description' ); ?></itunes:summary>
	<description><?php dp_get_show_details( 'description' ); ?></description>
	<itunes:owner>
		<itunes:name><?php echo $itunes_opt['itunes_owner']; ?></itunes:name>
		<itunes:email><?php echo $itunes_opt['itunes_owner_mail']; ?></itunes:email>
	</itunes:owner>
	<itunes:image href="http://example.com/podcasts/everything/AllAboutEverything.jpg" />

	<?php
		// TODO: It's ugly and needs to be refactored but it works
		foreach ($cats as $catname => $subcats) {
			$catvalue = strtolower( $catname );
			if ( !strcmp( $itunes_opt['itunes_category1'], $catvalue ) ) {
				echo "<itunes:category text='$catname' />";
			} else {
				foreach ($subcats as $subcat => $subcatname) {
					$subcatvalue = strtolower( $subcatname );
					if ( !strcmp( $itunes_opt['itunes_category1'], $subcatvalue ) ) {
						echo "<itunes:category text='$catname'>";
						echo "<itunes:category text='$subcatname' />";
						echo "</itunes:category>";
					}
				}
			}
		}

		foreach ($cats as $catname => $subcats) {
			$catvalue = strtolower( $catname );
			if ( !strcmp( $itunes_opt['itunes_category2'], $catvalue ) ) {
				echo "<itunes:category text='$catname' />";
			} else {
				foreach ($subcats as $subcat => $subcatname) {
					$subcatvalue = strtolower( $subcatname );
					if ( !strcmp( $itunes_opt['itunes_category2'], $subcatvalue ) ) {
						echo "<itunes:category text='$catname'>";
						echo "<itunes:category text='$subcatname' />";
						echo "</itunes:category>";
					}
				}
			}
		}
		foreach ($cats as $catname => $subcats) {
			$catvalue = strtolower( $catname );
			if ( !strcmp( $itunes_opt['itunes_category3'], $catvalue ) ) {
				echo "<itunes:category text='$catname' />";
			} else {
				foreach ($subcats as $subcat => $subcatname) {
					$subcatvalue = strtolower( $subcatname );
					if ( !strcmp( $itunes_opt['itunes_category3'], $subcatvalue ) ) {
						echo "<itunes:category text='$catname'>";
						echo "<itunes:category text='$subcatname' />";
						echo "</itunes:category>";
					}
				}
			}
		}
	?>

	<?php do_action( 'rss2_head' ); ?>
	<?php while( have_posts()) : the_post(); ?>
	<item>
		<title><?php the_title_rss() ?></title>
		<link><?php the_permalink() ?></link>
		<!-- TODO: put in settings -->
		<itunes:author><?php dp_get_speaker(); ?></itunes:author>
		<itunes:subtile>A short subtitle</itunes:subtile>
		<itunes:summary>a summary</itunes:summary>
		<itunes:image href="http://example.com/podcasts/everything/AllAboutEverything/Episode1.jpg" />
		<enclosure url="<?php echo get_post_meta( $post->ID, '_dicentis_podcast_medialink', true ); ?>" length="8727310" type="audio/mpeg" />
		<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
		<guid><?php echo get_permalink( $post->ID ); ?></guid>
		<itunes:duration>Media duration</itunes:duration>
	<?php rss_enclosure(); ?>
	<?php do_action( 'rss2_item' ); ?>
	</item>
	<?php endwhile; ?>
</channel>
</rss>
