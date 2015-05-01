<?php
/**
 * History of this page
 *
 * @uses $vars['page']
 */

$title = elgg_echo('pages:history');

if ($vars['conclusions']) {
	$options = array(
		'guid' => $vars['conclusions']->guid,
		'annotation_name' => 'conclusions',
		'limit' => 20,
		'reverse_order_by' => true
	);
	elgg_push_context('widgets');
	$content = elgg_list_annotations($options);
}

echo elgg_view_module('aside', $title, $content);
