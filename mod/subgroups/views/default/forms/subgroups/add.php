<?php
	echo elgg_echo('subgroups:add:label'); echo '<br />';
	echo elgg_view("input/text", array("name" => "new_name"));
?>
<?php
	echo elgg_view('input/hidden', array(
		'name' => 'group',
		'value' => $vars['group']->guid));
	echo elgg_view('input/submit', array(
		'value' => elgg_echo('subgroups:add:button')));
?>
