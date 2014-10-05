<div id="dipo-dashboard" class="wrap">
	<div id="icon-themes" class="icon32"></div><h2>Dashboard</h2>
	<p><?php _e( 'This Dashboard will be your control dashboard and will display many useful information.', $this->textdomain ); ?></p>

	<div id="dipo-beta-info" class="metabox-holder postbox dipo-floated-postbox">
		<h3><span><?php _e( 'Beta Information', $this->textdomain ) ?></span></h3>
		<div class="inside">
			<p>
			<?php _e( 'First of all, thank you that you take a look at my new plugin.', $this->textdomain ); ?>
			</p>
			<p>
				<?php printf( __( 'Dicentis is a new podcast plugin for WordPress and is still in Beta. That means things don\'t work like intended. If you encounter a bug please %stell me so I can fix it%s.', $this->textdomain ), "<a href='https://github.com/dicentis/dicentis/issues' title='Dicentis Issue Page' target='_blank'>", '</a>' ); ?>
			</p>
			<p>
				<?php _e( 'Now, I wish you good luck and have fun podcasting.<br>Cheers, Hans-Helge', $this->textdomain ); ?>
			</p>
		</div>
	</div>

	<div id="dipo-about" class="metabox-holder postbox dipo-floated-postbox">
		<h3><span><?php _e( 'About', $this->textdomain ) ?></span></h3>
		<div class="inside">
			<p>
			<?php
				_e( 'Dicentis is a new podcast plugin for WordPress. It is developed by myself and is not my day-job, but a hobby.', $this->textdomain );
			?>
			</p>
			<p>
			<?php
				printf( __( 'I will improve this plugin step by step. Dicentis is published under the GPL v3.0 license and the source code is %savailable on GitHub%s.', $this->textdomain ), "<a href='https://github.com/dicentis/dicentis' title='Dicentis on GitHub' target='_blank'>", '</a>'  );
			?>
			</p>
			<p>
			<?php
				printf( __( 'You can find more information and a documentation about the plugin on the %swebsite%s. If you want to contribute to this project please don\'t hesitate to contact me.', $this->textdomain ), "<a href='http://dicentis.io' title='Dicentis Website' target='_blank'>", '</a>'  );
			?>
			</p>
		</div>
	</div>

	<div id="dipo-rss-feeds" class="metabox-holder postbox dipo-cleared-postbox dipo-max-width">
		<h3><span><?php _e( 'RSS Feeds', $this->textdomain ) ?></span></h3>
		<div class="inside">
			<div id="dipo-link-generator" style="display: none;">
				<input type="hidden" id="dipo-home-url" value="<?php echo home_url('/'); ?>">
				<label for="dipo-gen-show"><?php _e( 'Podcast Show', $this->textdomain ); ?></label>
				<label for="dipo-gen-type"><?php _e( 'Media Type', $this->textdomain ); ?></label>

				<div class="clear"></div>

				<?php
					\Dicentis\Podcast_Post_Type\Dipo_Podcast_Shows_Model::echo_select_shows();
					\Dicentis\Podcast_Post_Type\Dipo_Podcast_Post_Type::echo_select_mediatypes();
				?>

				<p><strong><?php _e( 'Podcast Feed', $this->textdomain ); ?></strong>: <code id="dipo-feed-link">Feed</code></p>

			</div>
			<ul class="hide-if-js">
				<li><strong><?php _e( 'You can replace the <code>pod</code> at the end of a link with any file extension you use for you files. Example: <code>mp3</code> or <code>mp4</code>', $this->textdomain ); ?></strong></li>
				<?php foreach ( $show_feeds as $show => $fields ) : ?>
				<li>
					<h4><?php echo esc_attr( $fields['name'] ); ?></h4>
					
					<ul>
						<li><?php echo esc_attr( $fields['feed'] ); ?></li>
						<li><?php echo esc_attr( $fields['pretty_feed'] ); ?></li>
					</ul>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<div id="dipo-blog-posts" class="metabox-holder postbox dipo-cleared-postbox dipo-max-width">
		<h3><span><?php _e( 'Dicentis News', $this->textdomain ) ?></span></h3>
		<div class="inside">
			<?php $rss = fetch_feed( 'http://dicentis.io/feed' ); ?>

			<?php if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

				// Figure out how many total items there are, but limit it to 5. 
				$maxitems = $rss->get_item_quantity( 5 ); 

				// Build an array of all the items, starting with element 0 (first element).
				$rss_items = $rss->get_items( 0, $maxitems );

			endif;
			?>

			<ul>
				<?php if ( isset( $maxitems ) && $maxitems > 0 ) : ?>
					<?php // Loop through each feed item and display each item as a hyperlink. ?>
					<?php foreach ( $rss_items as $item ) : ?>
						<li>
							<a href="<?php echo esc_url( $item->get_permalink() ); ?>"
								title="<?php printf( __( 'Posted %s', '$this->textdomain' ), $item->get_date( 'j. F Y | G:i a' ) ); ?>">
								<?php echo esc_html( $item->get_title() ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				<?php else: ?>
					<li><?php _e( 'No blog posts found.', $this->textdomain ); ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<?php $perma = ( get_option( 'permalink_structure' ) ) ? 'enabled' : 'disabled' ?>
	<input id='permalink_structure' type='hidden' value="<?php echo esc_attr( $perma ) ?>" />
</div>
