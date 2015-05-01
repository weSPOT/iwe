<?php
/**
 * List all MindMeister maps
 */

$title = elgg_echo('wespot_mindmeister:all');

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('wespot_mindmeister'));

elgg_register_title_button();

$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'mindmeistermap',
	'full_view' => false,
));
if (!$content) {
	$content = '<p>' . elgg_echo('wespot_mindmeister:none') . '</p>';
}

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
