<?php

namespace Dicentis\Feed;

/**
 * RSS2 Feed Template for displaying RSS2 Posts feed.
 *
 * @package WordPress
 */

$feed = new Dipo_RSS_Model();


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
	<title><?php bloginfo_rss('name'); $feed->get_show_details(); ?></title>
	<link><?php bloginfo_rss( 'url' ) ?></link>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php
		$iso_code = preg_replace('/[_]/', '-', $feed->get_option_by_key( 'itunes_language' ) );
		echo $iso_code;
	?></language>
	<atom:link href="<?php echo esc_url( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>" rel="self" type="application/rss+xml" />
	<copyright><?php echo ent2ncr($feed->get_option_by_key( 'itunes_copyright' ) ); ?></copyright>
	<itunes:subtitle><?php echo $feed->get_option_by_key( 'itunes_subtitle' ) ; ?></itunes:subtitle>
	<itunes:author><?php echo $feed->get_option_by_key( 'itunes_author' ) ; ?></itunes:author>
	<!-- @TODO: Take Summary from Show -->
	<itunes:summary><![CDATA[<?php $feed->get_show_details( 'description' ); ?>]]></itunes:summary>
	<!-- @TODO: Take Description from Show -->
	<description><![CDATA[<?php $feed->get_show_details( 'description' ); ?>]]></description>
	<itunes:owner>
		<itunes:name><?php echo $feed->get_option_by_key( 'itunes_owner' ) ; ?></itunes:name>
		<itunes:email><?php echo $feed->get_option_by_key( 'itunes_owner_mail' ) ; ?></itunes:email>
	</itunes:owner>
	<itunes:image href="<?php echo esc_url( $feed->get_cover_art() ); ?>" />
<?php $feed->print_itunes_categories();

	do_action( 'rss2_head' ); ?>
<?php while( have_posts()) : the_post(); ?>
<?php if ( $feed->exists_mediafile( $post->ID ) ) : ?>
		<item>
			<title><?php the_title_rss() ?></title>
			<link><?php the_permalink() ?></link>
			<itunes:author><?php echo $feed->get_speaker( $post->ID ); ?></itunes:author>
			<itunes:subtitle><![CDATA[<?php echo $feed->get_episodes_subtitle( $post->ID ); ?>]]></itunes:subtitle>
			<itunes:summary><![CDATA[<?php echo $feed->get_episodes_summary( $post->ID ); ?>]]></itunes:summary>
<?php $image = $feed->get_episodes_image( $post->ID ) ?>
<?php if ( isset( $image ) && strlen( $image ) > 0 ) : ?>
			<itunes:image href="<?php echo esc_url( $image ); ?>" />
<?php endif; ?>
<?php $post_mediafile = $feed->get_episodes_mediafile( $post->ID ); ?>
			<enclosure url="<?php echo $post_mediafile['medialink'] ?>" length="<?php echo $post_mediafile['filesize']; ?>" type="<?php echo $post_mediafile['mediatype']; ?>" />
			<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
			<guid><?php echo get_permalink( $post->ID ); ?></guid>
<?php if ( isset($post_mediafile['duration']) && 0 < $post_mediafile['duration'] ) : ?>
			<itunes:duration><?php echo $post_mediafile['duration']; ?></itunes:duration>
<?php endif; ?>
<?php if ( $feed->episode_has_keywords( $post->ID ) ) : ?>
			<itunes:keywords><?php echo $feed->get_episodes_keywords( $post->ID ); ?></itunes:keywords>
<?php endif; ?>
<?php do_action( 'rss2_item' ); ?>
		</item>
<?php endif; ?>
<?php endwhile; ?>
</channel>
</rss>
