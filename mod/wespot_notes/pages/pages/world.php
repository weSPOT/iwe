<?php
/**
 * List all pages
 *
 * @package ElggPages
 */

$title = elgg_echo('notes:all');

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('notes'));

elgg_register_title_button();

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'notes_top',
	'full_view' => false,
));
if (!$content) {
	$content = '<p>' . elgg_echo('notes:none') . '</p>';
}

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar'),
));

echo elgg_view_page($title, $body);
