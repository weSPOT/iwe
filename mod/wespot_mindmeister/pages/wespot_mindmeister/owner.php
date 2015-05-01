<?php
/**
 * List a user's or group's MindMeister maps
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('wespot_mindmeister/all');
}

// access check for closed groups
group_gatekeeper();

$title = elgg_echo('wespot_mindmeister:owner', array($owner->name));

elgg_push_breadcrumb(elgg_echo('wespot_mindmeister:owner', array($owner->name)));

elgg_register_title_button();

// If group or user requires different call.
if ($owner instanceof ElggGroup) {
	$content = elgg_list_entities(array(
		'types' => 'object',
		'subtypes' => 'mindmeistermap',
		'container_guid' => $owner->guid,
		'limit' => $limit,
		'full_view' => false,
	));
} else {
	$content = elgg_list_entities(array(
		'types' => 'object',
		'subtypes' => 'mindmeistermap',
		'owner_guid' => $owner->guid,
		'limit' => $limit,
		'full_view' => false,
	));
}

if (!$content) {
	$content = '<p>' . elgg_echo('wespot_mindmeister:none') . '</p>';
}

$filter_context = '';
if (elgg_get_page_owner_guid() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
}

$params = array(
	'filter_context' => $filter_context,
	'content' => $content,
	'title' => $title,
);

if (elgg_instanceof($owner, 'group')) {
	$params['filter'] = '';
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
