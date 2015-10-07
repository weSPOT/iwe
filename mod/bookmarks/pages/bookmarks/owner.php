<?php
/**
 * Elgg bookmarks plugin everyone page
 *
 * @package Bookmarks
 */

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	forward('bookmarks/all');
}

elgg_push_breadcrumb($page_owner->name);

$phase = $_GET['phase'];
$activity_id = $_GET['activity_id'];

elgg_register_title_button(null, 'add', 'phase='.$phase.'&activity_id='.$activity_id);

$options = array(
	'type' => 'object',
	'subtype' => 'bookmarks',
	'container_guid' => $page_owner->guid,
	'limit' => 10,
	'full_view' => false,
	'view_toggle_type' => false
);

$entities = array_filter(elgg_get_entities($options), function($element) use ($phase, $activity_id) {
	return ($element->phase == $phase || (!$element->phase && $phase == 1)) && ($element->activity_id == $activity_id || (!$element->activity_id && $phase == 1)); });

$content = elgg_view_entity_list($entities, $options);

if (!$content) {
	$content = elgg_echo('bookmarks:none');
}

$title = elgg_echo('bookmarks:owner', array($page_owner->name));

$filter_context = '';
if ($page_owner->getGUID() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
}

$vars = array(
	'filter_context' => $filter_context,
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('bookmarks/sidebar'),
);

// don't show filter if out of filter context
if ($page_owner instanceof ElggGroup) {
	$vars['filter'] = false;
}

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);