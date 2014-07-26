<div id="dipo-dashboard" class="wrap">
	<div id="icon-themes" class="icon32"></div><h2>Dashboard</h2>
	<p><?php _e( 'This Dashboard will be your control dashboard and will display many useful information.', $this->textdomain ); ?></p>

	<div id="dipo-beta-info" class="metabox-holder postbox dipo-floated-postbox">
		<h3 class="hndle"><span><?php _e( 'Beta Information', $this->textdomain ) ?></span></h3>
		<div class="inside">
			<?php
				_e( 'First of all, thank you that you take a look at my new plugin.<br><br>Dicentis is a new podcast plugin for WordPress and is still in Beta. T', $this->textdomain );
			?>
		</div>
	</div>

	<div id="dipo-about" class="metabox-holder postbox dipo-floated-postbox">
		<h3 class="hndle"><span><?php _e( 'About', $this->textdomain ) ?></span></h3>
		<div class="inside">
			<?php
				_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ergo, si semel tristior effectus est, hilara vita amissa est? Tu quidem reddes; Facit igitur Lucius noster prudenter, qui audire de summo bono potissimum velit; Deprehensus omnem poenam contemnet. Nihilne est in his rebus, quod dignum libero aut indignum esse ducamus? Quid, quod res alia tota est? Iam in altera philosophiae parte. Duo Reges: constructio interrete.', $this->textdomain );
			?>
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
