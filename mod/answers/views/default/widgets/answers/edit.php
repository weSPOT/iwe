<?php
/**
 * Answers widget edit view
 */

if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 4;
}

$params = array(
	'name' => 'params[num_display]',
	'value' => $vars['entity']->num_display,
	'options' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
);
$num_dropdown = elgg_view('input/dropdown', $params);

?>
<div>
	<?php echo elgg_echo('answers:widget:numbertodisplay'); ?>:
	<?php echo $num_dropdown; ?>
</div>

