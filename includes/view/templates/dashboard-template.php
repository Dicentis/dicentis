<div id="dipo-dashboard" class="wrap">
	<div id="icon-themes" class="icon32"></div><h2>Dashboard</h2>
	<p><?php _e( 'This Dashboard will be your control dashboard and will display many useful information.', $this->textdomain ); ?></p>

	<div id="dipo-beta-info" class="metabox-holder postbox dipo-floated-postbox">
		<h3 class="hndle"><span><?php _e( 'Beta Information', $this->textdomain ) ?></span></h3>
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
		<h3 class="hndle"><span><?php _e( 'About', $this->textdomain ) ?></span></h3>
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
		<h3 class="hndle"><span><?php _e( 'RSS Feeds', $this->textdomain ) ?></span></h3>
		<div class="inside">
			<ul class="open-if-no-js">
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
		<h3 class="hndle"><span><?php _e( 'Dicentis News', $this->textdomain ) ?></span></h3>
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
				<?php if ( $maxitems == 0 ) : ?>
					<li><?php _e( 'No items', 'my-text-domain' ); ?></li>
				<?php else : ?>
					<?php // Loop through each feed item and display each item as a hyperlink. ?>
					<?php foreach ( $rss_items as $item ) : ?>
						<li>
							<a href="<?php echo esc_url( $item->get_permalink() ); ?>"
								title="<?php printf( __( 'Posted %s', '$this->textdomain' ), $item->get_date( 'j. F Y | G:i a' ) ); ?>">
								<?php echo esc_html( $item->get_title() ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
