<?php
/**
 * RSS2 Feed Template for displaying RSS2 Posts feed.
 *
 * @package WordPress
 */

include_once dirname( __FILE__ ) . '/../classes/rss.php';

$feed = new RSS();
$feed->get_itunes_options();


header( 'Content-Type: application/xml; charset=' . get_option( 'blog_charset' ), true );
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
	<title><?php bloginfo_rss('name'); $feed->get_show_details(); ?></title>
	<link><?php bloginfo_rss( 'url' ) ?></link>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php
		$iso_code = preg_replace('/[_]/', '-', $feed->itunes_opt['itunes_language']);
		echo $iso_code;
	?></language>
	<!-- @TODO: Add Copyright Setting -->
	<copyright><?php echo ent2ncr($feed->itunes_opt['itunes_copyright']); ?></copyright>
	<itunes:subtitle><?php echo $feed->itunes_opt['itunes_subtitle']; ?></itunes:subtitle>
	<itunes:author><?php echo $feed->itunes_opt['itunes_author']; ?></itunes:author>
	<!-- @TODO: Take Summary from Show -->
	<itunes:summary><?php $feed->get_show_details( 'description' ); ?></itunes:summary>
	<!-- @TODO: Take Description from Show -->
	<description><?php $feed->get_show_details( 'description' ); ?></description>
	<itunes:owner>
		<itunes:name><?php echo $feed->itunes_opt['itunes_owner']; ?></itunes:name>
		<itunes:email><?php echo $feed->itunes_opt['itunes_owner_mail']; ?></itunes:email>
	</itunes:owner>
	<!-- @TODO: Take Description from Show -->
	<itunes:image href="" />
<?php $feed->print_itunes_categories();

	do_action( 'rss2_head' ); ?>
<?php while( have_posts()) : the_post(); ?>
<?php if ( $feed->exists_mediafile( $post->ID ) ) : ?>
		<item>
			<title><?php the_title_rss() ?></title>
			<link><?php the_permalink() ?></link>
			<itunes:author><?php echo $feed->get_speaker( $post->ID ); ?></itunes:author>
			<itunes:subtile><?php echo $feed->get_episodes_subtitle( $post->ID ); ?></itunes:subtile>
			<itunes:summary><?php echo $feed->get_episodes_summary( $post->ID ); ?></itunes:summary>
			<itunes:image href="<?php echo $feed->get_episodes_image( $post->ID ); ?>" />
<?php $post_mediafile = $feed->get_episodes_mediafile( $post->ID ); ?>
			<enclosure url="<?php echo $post_mediafile['medialink'] ?>" length="<?php echo $post_mediafile['filesize']; ?>" type="<?php echo $post_mediafile['mediatype']; ?>" />
			<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
			<guid><?php echo get_permalink( $post->ID ); ?></guid>
			<itunes:duration><?php echo $post_mediafile['duration']; ?></itunes:duration>
<?php if ( $feed->episode_has_keywords( $post->ID ) ) : ?>
			<itunes:keywords><?php echo $feed->get_episodes_keywords( $post->ID ); ?></itunes:keywords>
<?php endif; ?>
<?php rss_enclosure(); ?>
<?php do_action( 'rss2_item' ); ?>
		</item>
	<?php endif; ?>
	<?php endwhile; ?>
</channel>
</rss>
