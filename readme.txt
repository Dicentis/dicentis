=== Dicentis Podcast ===
Contributors: obstschale
Tags: podcast, podcasts, feed, rss, episode, episodes, audio, video, cm,
Donate link: http://bit.ly/hhb-paypal
Requires at least: 3.6
Tested up to: 4.3
Stable tag: 0.2.3
License: GPLv3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Dicentis is a new podcast plugin which has its main focus on CMS based Websites. With Dicentis it is possible to offer multiple podcasts with one WordPress installation.

== Description ==
Most podcast plugin for WordPress are great but have one weakness: They focus on single podcast sites. It is not easy or impossible to create multiple podcast feeds with on installation. This plugin wants to change that. It is possible to add multiple podcast and support individual feeds for different media types. In addition, you can add series and speaker to episodes for a better archive and to enhance episodes with more meta data.

**Key Features**

- Create as many podcast you want
- Organize podcast episodes within different series (like categories)
- Assign speaker to episodes
- Provide episode in different formats (mp3, ogg, m4a) and assign it to one episode
- Listen / Watch your episode direct with built-in WordPress media player
- Custom capabilities: Create specific Podcast user
- Full i18n support - Translation Ready

**Where to find help**

You can either use [the support forum](https://wordpress.org/support/plugin/dicentis-podcast) or create a new [issue on GitHub](https://github.com/Dicentis/dicentis/issues).

**How to contribute**

Contributing to Dicentis is easy, please [fork the GitHub repository](https://github.com/dicentis/dicentis) and create new Pull Requests. You can also contribute without the need to code. Dicentis can be translated and also a documentation is meant to be written. If you're interested please contact me: [Contact](http://dicentis.io/contact/).

== Installation ==
You can download and install Dicentis using the built in WordPress plugin installer. If you download Dicentis manually, make sure it is uploaded to \"/wp-content/plugins/dicentis-podcast/\". Activate Dicentis in the \"Plugins\" admin panel using the \"Activate\" link.

== Frequently Asked Questions ==
A separated page on the website is dedicated to FAQs. See: http://dicentis.io/faq.

== Changelog ==

= 0.2.3 =

* [NEW] There exists now a Dicentis Slack Channel: http://slack.dicentis.io
* [FIX] All shows (i.e. also empty shows) are shown in dashboard. (#16)
* [FIX] Now feed link is shown if no show exists
* [UPDATE] Init composer project
* [UPDATE] Start using Travis CI and Unit Tests
* [UPDATE] Add Dicentis Icon Font and use new Logo for CPT
* [REMOVE] Remove TGM Plugin dependency
* [TEST] Add Test to check if Icon Font is enqueued properly

= 0.2.2 =

* [FIX] Warning during `join` operation. Fix #13 thx @pierreberchtold
* [UPDATE] This version introduces show specific settings
* [UPDATE] Audio and / or Video player are added to content
* [UPDATE] If JS: Show dropdown to download mediafiles
* [UPDATE] If no JS: Download links for files are displayed
* [FICTION] Superman is now using Dicentis Podcast to broadcast his superhero stories

= 0.2.1 =

* [FIX] Cover Art Image URL
* [UPDATE] If no cover art is given a placeholder cover art is used
* [UPDATE] Some <itunes> tags in feeds are using CDATA now
* [UPDATE] If no duration is given <itunes:duration> tag is left out

= 0.2.0 (Brass Monkey) =

* [UPDATE] new Dashboard with more useful information and a feed generator which gives you the correct feed you need
* [UPDATE] complete restructured plugin with a better OOP approach

= 0.1.3 =

* [FIX] Revert changes. Fix from 0.1.2 cause more trouble than it solve -.-

= 0.1.2 =

* [FIX] Redirect `/podcasts/` to a page and not to archive if a page with that slug exists

= 0.1.1 =

* [FEATURE] Feeds for file extentions are available
	* Example: http://www.your-domain.com/podcasts/feed/mp3
* [FEATURE] Copyright is added to feed and buttons for icons on settings page
* [UPDATE] Dicentis DB-Version added to DB
* [FIX] Fix CSS for metabox

= 0.1.0 (Bloddy Mary) =

* [FEATURE] Create multiple podcast shows
* [FEATURE] Create multiple speaker
* [FEATURE] Create multiple series
* [FEATURE] Add global iTunes information to your feed
* [FEATURE] Add multiple media files to one episode
* [FEATURE] Import existing Podcast Feeds
