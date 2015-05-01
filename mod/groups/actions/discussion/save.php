<?php
/**
 * Topic save action
 */

// Get variables
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$desc = get_input("description");
$status = get_input("status");
$access_id = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid');
if(get_input('phase')) {
    $phase = (int) get_input('phase');
}

if(get_input('activity_id')) {
  $activity_id = get_input('activity_id');
}

$guid = (int) get_input('topic_guid');
$tags = get_input("tags");

elgg_make_sticky_form('topic');

// validation of inputs
if (!$title || !$desc) {
	register_error(elgg_echo('discussion:error:missing'));
	forward(REFERER);
}

$container = get_entity($container_guid);
if (!$container || !$container->canWriteToContainer(0, 'object', 'groupforumtopic')) {
	register_error(elgg_echo('discussion:error:permissions'));
	forward(REFERER);
}

// check whether this is a new topic or an edit
$new_topic = true;
if ($guid > 0) {
	$new_topic = false;
}

if ($new_topic) {
	$topic = new ElggObject();
	$topic->subtype = 'groupforumtopic';
} else {
	// load original file object
	$topic = new ElggObject($guid);
	if (!$topic || !$topic->canEdit()) {
		register_error(elgg_echo('discussion:topic:notfound'));
		forward(REFERER);
	}
}

if(get_input('recommended_tags')) {
	$topic->recommended_tags = get_input('recommended_tags');
}

if(get_input('tag_recommender_algorithm')) {
	$topic->tag_recommender_algorithm = get_input('tag_recommender_algorithm');
}

$topic->title = $title;
$topic->description = $desc;
$topic->status = $status;
if($phase) { $topic->phase = $phase; } # tab will not exist when editing an existing topic... we should just keep the existing tab
if($activity_id) { $topic->activity_id = $activity_id; }
$topic->access_id = $access_id;
$topic->container_guid = $container_guid;

$tags = explode(",", $tags);
$topic->tags = $tags;

$result = $topic->save();

if (!$result) {
	register_error(elgg_echo('discussion:error:notsaved'));
	forward(REFERER);
}

// topic saved so clear sticky form
elgg_clear_sticky_form('topic');


// handle results differently for new topics and topic edits
if ($new_topic) {
	system_message(elgg_echo('discussion:topic:created'));
	add_to_river('river/object/groupforumtopic/create', 'create', elgg_get_logged_in_user_guid(), $topic->guid);
} else {
	system_message(elgg_echo('discussion:topic:updated'));
}

forward($topic->getURL());
