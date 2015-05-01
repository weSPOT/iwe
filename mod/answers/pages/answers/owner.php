<?php
/**
 * Owner's questions
 */

$sort = get_input('sort', 'newest');

// Get the current page's owner
$page_owner = elgg_get_page_owner_entity();
if (!$page_owner) {
	forward('answers/all');
}

$phase = $_GET['phase'];
$activity_id = $_GET['activity_id'];

elgg_push_breadcrumb(elgg_echo('answers:owner', array($page_owner->name)));

if($_GET['tab']) {
  elgg_register_title_button(null, 'add', 'phase=' . $phase . '&activity_id=' . $activity_id);
}

$filter = function($element) use ($phase, $activity_id) { return ($element->phase == $phase || (!$element->phase && $phase == 1)) && ($element->activity_id == $activity_id || !$activity_id); };

$offset = (int) max(get_input('offset', 0), 0);
$limit = (int) max(get_input('limit', 10), 0);

$questions = array_filter(answers_get_sorted_questions($page_owner->guid, $sort), $filter);

$content = elgg_view_entity_list(array_slice($questions, $offset, $limit), array(
    'full_view' => false,
    'pagination' => true,
    'type' => 'object',
    'subtype' => 'question',
    'count' => count($questions),
    'offset' => $offset,
    'limit' => $limit,
));

if (!$content) {
	$content = elgg_echo('answers:none');
}

$title = elgg_echo('answers:owner', array($page_owner->name));

$vars = array(
	'content' => $content,
	'title' => $title,
);

if ($page_owner instanceof ElggGroup || $page_owner->guid == elgg_get_logged_in_user_guid()) {
	$vars['filter'] = elgg_view('answers/search_and_submit_question', array(
		'phase' => $phase,
		'activity_id' => $activity_id
	));
	$vars['filter'].= elgg_view('answers/filter_questions', array(
		'sort' => $sort,
		'phase' => $phase,
		'activity_id' => $activity_id
	));
} else {
	$vars['filter_context'] = '';
}

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);
