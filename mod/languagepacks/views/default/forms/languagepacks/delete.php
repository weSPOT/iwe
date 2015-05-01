<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div>
	<div class="elgg-head">
		<h3><?php echo elgg_echo('languagepacks:delete:title'); ?></h3>
	</div>
	<br>
	<div class="elgg-body">
		<p>
	<?php
		echo elgg_echo('languagepacks:delete:explain') . ' <br/>';
	?>
		</p>
	</div>
	<div class="elgg-foot">
		<p>
	<?php
		echo elgg_view('input/hidden', array(
			'name' => 'locales-selection',
		));
        echo elgg_view('input/hidden', array(
            'name' => 'cores-selection',
        ));
		echo elgg_view('input/hidden', array(
			'name' => 'plugins-selection',
		));
		echo elgg_view('input/submit', array(
			'value' => elgg_echo('languagepacks:delete:button'),
		));
	?>
		</p>
	</div>
</div>