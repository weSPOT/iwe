<?php
/**
 * Save a question action
 */

// Get input data
$title = get_input('title');
$description = get_input('description');
$tags = get_input('tags');
$container_guid = (int) get_input('container_guid');
if(get_input('phase')) {
    $phase = (int) get_input('phase');
}
$guid = (int) get_input('guid');

$user_guid = elgg_get_logged_in_user_guid();

if (!can_write_to_container($user_guid, $container_guid)) {
	register_error(elgg_echo('answers:error'));
	forward(REFERER);
}

elgg_make_sticky_form('question');

// Make sure the title / description aren't blank
if (empty($title)) {
	register_error(elgg_echo('answers:question:blank'));
	forward(REFERER);
}

// Otherwise, save the question
if ($guid) {
	$question = get_entity($guid);
	$new = false;
} else {
	$question = new ElggObject();
	$question->subtype = 'question';
	$new = true;
}
$question->access_id = ACCESS_PUBLIC;
$question->title = $title;
$question->description = $description;
$question->tags = string_to_tag_array($tags);
$question->container_guid = $container_guid;

if(get_input('recommended_tags')) {
	$question->recommended_tags = get_input('recommended_tags');
}

if(get_input('tag_recommender_algorithm')) {
	$question->tag_recommender_algorithm = get_input('tag_recommender_algorithm');
}

if($phase) { $question->phase = $phase; } # tab will not exist when editing an existing topic... we should just keep the existing tab
if(get_input('activity_id')) { $question->activity_id = get_input('activity_id'); }

if ($question->save()) {
	elgg_clear_sticky_form('question');
	system_message(elgg_echo('answers:question:posted'));

	if ($new) { // only add river item when this is a new question
		add_to_river('river/object/question/create', 'create', $user_guid, $question->guid);
	}

	forward($question->getURL());
} else {
	register_error(elgg_echo('answers:error'));
	forward(REFERER);
}
