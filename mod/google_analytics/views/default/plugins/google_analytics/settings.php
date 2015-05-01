<br/>
  <p>
    <b><?php echo elgg_echo('google-analytics:lblID'); ?></b> <?php echo elgg_echo('google-analytics:lblExample'); ?><br /><br/>
	<?php
		echo elgg_view('input/text', array(
			'name' => 'params[analytics]',
			'value' => $vars['entity']->analytics,
		));
	?>
     <br/><br/> <i><?php echo elgg_echo('google-analytics:lblHelp'); ?> <a href="http://www.google.com/analytics/">Google Analytics</a></i>.
  </p>