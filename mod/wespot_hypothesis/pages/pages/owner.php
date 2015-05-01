<?php
/**
 * List a user's or group's pages
 *
 * @package ElggPages
 */

$owner = elgg_get_page_owner_entity();
if (!$owner) {
	forward('pages/all');
}

// access check for closed groups
group_gatekeeper();

$title = elgg_echo('hypothesis:owner', array($owner->name));

elgg_push_breadcrumb(elgg_echo('hypothesis:owner', array($owner->name)));

$phase = $_GET['phase'];
$activity_id = $_GET['activity_id'];

if($phase) {
    elgg_register_title_button(null, 'add', 'phase=' . $phase . '&activity_id=' . $activity_id);
}

//------

$offset = (int) max(get_input('offset', 0), 0);
$limit = (int) max(get_input('limit', 10), 0);

$options = array(
	'type' => 'object',
	'subtype' => 'hypothesis_top',
	'container_guid' => elgg_get_page_owner_guid(),
	'full_view' => false,
    'limit' => 0 // retrieves all entities
);

$entities = array_filter(elgg_get_entities($options), function($element) use ($phase, $activity_id) {
  return ($element->phase == $phase || (!$element->phase && $phase == 1)) && ($element->activity_id == $activity_id || !$activity_id); });

$content = elgg_view_entity_list(array_slice($entities, $offset, $limit), array_merge($options, array(
    'full_view' => false,
    'pagination' => true,
    'count' => count($entities),
    'offset' => $offset,
    'limit' => $limit,
)));

//------

if (!$content) {
	$content = '<p>' . elgg_echo('hypothesis:none') . '</p>';
}

$filter_context = '';
if (elgg_get_page_owner_guid() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
}

//$sidebar = elgg_view('pages/sidebar/navigation');
$sidebar = elgg_view('pages/sidebar');

$params = array(
	'filter_context' => $filter_context,
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
);

if (elgg_instanceof($owner, 'group')) {
	$params['filter'] = '';
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
