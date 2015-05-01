<?php
/**
 * MindMeister map widget edit
 */

// set default value
if (!isset($vars['entity']->wespot_mindmeister_num)) {
	$vars['entity']->wespot_mindmeister_num = 4;
}

$params = array(
	'name' => 'params[wespot_mindmeister_num]',
	'value' => $vars['entity']->wespot_mindmeister_num,
	'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
);
$dropdown = elgg_view('input/dropdown', $params);

?>
<div>
	<?php echo elgg_echo('wespot_mindmeister:num'); ?>:
	<?php echo $dropdown; ?>
</div>
