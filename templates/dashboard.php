<div class="wrap">
	<div id="icon-themes" class="icon32"></div><h2>Dashboard</h2>
	<p><?php _e('This Dashboard provides you alle the needed information about your podcast shows.', 'dicentis'); ?></p>

	<?php foreach ( $show_feeds as $show => $fields) : ?>
	<h3><?php echo $fields['name']; ?></h3>
	
	<ul>
		<li><?php echo $fields['feed']; ?></li>
		<li><?php echo $fields['pretty_feed']; ?></li>
	</ul>

	<?php endforeach; ?>

</div>
