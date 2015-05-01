<?php
/**
 * Generic delete action for questions and answers
 */

$guid = (int) get_input('guid');
$entity = get_entity($guid);

if (!$entity->canEdit()) {
	// @todo
	register_error('answers:notdeleted');
	forward(REFERER);
}

$subtype = $entity->getSubtype();

elgg_load_library('answers:utilities');

if ($subtype == 'question') {
//	$owner = $entity->getOwnerEntity();
//	$forward_url = 'answers/owner/' . $owner->username;
	$forward_url = 'answers/group/' . $entity->container_guid . '?phase=' . $entity->phase . '&activity_id=' . $entity->activity_id;
	$answers = answers_get_question_answers($entity);
    elgg_trigger_event('delete', 'annotation_from_ui', $entity);
	if ($answers && is_array($answers)) {
		foreach ($answers as $answer) {
			$answer->delete(); // @todo answer of another user seems not deleted. Access problem ?
		}
	}
} else if ($subtype == 'answer') {
	$question = answers_get_question_for_answer($entity);
    $entity->temp_metadata['question_guid'] = $question->guid;
    elgg_trigger_event('delete', 'annotation_from_ui', $entity);
	$forward_url = $question->getURL();
} else {
	register_error('answers:notdeleted');
	forward(REFERER);
}

$rowsaffected = $entity->delete();
if ($rowsaffected > 0) {
	system_message(elgg_echo("answers:$subtype:deleted"));
} else {
	register_error(elgg_echo("answers:notdeleted"));
}

forward($forward_url);
