<?php
/**
 * Individual's or group's files
 *
 * @package ElggFile
 */

// access check for closed groups
group_gatekeeper();

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('file/all');
}

//elgg_push_breadcrumb(elgg_echo('file'), "file/all");
elgg_push_breadcrumb(elgg_echo("file:user", array($owner->name)));


$phase = $_GET['phase'];
$activity_id = $_GET['activity_id'];

if($phase) {
    elgg_register_title_button(null, 'add', 'phase=' . $phase . '&activity_id=' . $activity_id);
}

$get_content = function ($options) use ($phase, $activity_id) {
    $filter = function($element) use ($phase, $activity_id) { return ($element->phase == $phase || (!$element->phase && $phase == 4)) && ($element->activity_id == $activity_id || !$activity_id); };
    if($options['count']) { # because of how elgg_list_entities works
        $options['count'] = FALSE;
        return count(array_filter(elgg_get_entities($options), $filter));
    } else {
        return array_filter(elgg_get_entities($options), $filter);
    }
};

$params = array();

if ($owner->guid == elgg_get_logged_in_user_guid()) {
	// user looking at own files
	$params['filter_context'] = 'mine';
} else if (elgg_instanceof($owner, 'user')) {
	// someone else's files
	// do not show select a tab when viewing someone else's posts
	$params['filter_context'] = 'none';
} else {
	// group files
	$params['filter'] = '';
}

$title = elgg_echo("file:user", array($owner->name));

// List files
$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'file',
	'container_guid' => $owner->guid,
	'limit' => 10,
	'full_view' => FALSE,
), $get_content, 'elgg_view_entity_list', true);
if (!$content) {
	$content = elgg_echo("file:none");
}

$sidebar = file_get_type_cloud(elgg_get_page_owner_guid());
$sidebar = elgg_view('file/sidebar');

$params['content'] = $content;
$params['title'] = $title;
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
