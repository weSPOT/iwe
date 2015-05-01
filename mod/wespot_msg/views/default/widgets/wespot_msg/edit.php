<?php
/**
 * wespot_msg widget edit view
 */

$widget_id = $vars['entity']->guid;

elgg_load_library('elgg:wespot_msg');

$owner = elgg_get_page_owner_entity();
wespot_msg_sync_threads($owner->getGUID());

// set default value
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 10;
}

if (!isset($vars['entity']->threadId)) {
	$vars['entity']->threadId = wespot_msg_get_default_thread_id($owner->getGUID());
}

$num_display_params = array(
	'name' => 'params[num_display]',
	'value' => $vars['entity']->num_display,
	'options' => range(10,20),
);
$dropdown_num_display = elgg_view('input/dropdown', $num_display_params);


$thread_display_params = array(
        'name' => 'params[threadId]',
        'value' => $vars['entity']->threadId,
        'options_values' => wespot_msg_get_thread_options($owner->getGUID()),
);
$dropdown_threads = elgg_view('input/dropdown', $thread_display_params);

?>
<div>
	<?php echo elgg_echo('wespot_msg:num_display'); ?>:
	<?php echo $dropdown_num_display; ?>
</div>
<div>
        <?php echo elgg_echo('wespot_msg:thread_display'); ?>:
        <?php echo $dropdown_threads; ?>
</div>
