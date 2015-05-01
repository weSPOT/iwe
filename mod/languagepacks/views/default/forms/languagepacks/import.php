<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div>
	<div class="elgg-head">
		<h3><?php echo elgg_echo('languagepacks:import:title'); ?></h3>
	</div>
	<br>
	<div class="elgg-body">
		<p>
	<?php
		echo elgg_echo('languagepacks:import:filename') . ' <br/>';
		echo elgg_view('input/file', array(
			'name' => 'upload',
		)) . ' </p><p>';
		echo elgg_view('input/checkbox', array(
			'name' => 'overwrite',
		)) . ' ';
		echo elgg_echo('languagepacks:import:overwrite') . ' </p><p>';
		echo elgg_view('input/checkbox', array(
			'name' => 'ignore-en',
		)) . ' ';
		echo elgg_echo('languagepacks:import:ignore_en');
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
			'value' => elgg_echo('languagepacks:import:button'),
		));
	?>
		</p>
	</div>
</div>
