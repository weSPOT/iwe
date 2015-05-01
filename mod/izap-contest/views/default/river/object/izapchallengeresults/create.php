<?php
/**
 * Quiz result river view
 */

$subject = $vars['item']->getSubjectEntity();
$object = $vars['item']->getObjectEntity();
$quiz = get_entity($object->container_guid);

	$subject_link = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'class' => 'elgg-river-subject',
		'is_trusted' => true,
	));
	
	$object_link = elgg_view('output/url', array(
		'href' => izapbase::setHref(array('context' => GLOBAL_IZAP_CONTEST_CHALLENGE_PAGEHANDLER, 'action' => 'result', 'page_owner' => FALSE, 'vars' => array($object->container_guid, $object->guid, elgg_get_friendly_title($object->title)))),
		'text' => elgg_echo('izap-contest:river:took'),
		'class' => 'elgg-river-object',
		'is_trusted' => true,
	));

if($quiz){
	$quiz_link = elgg_view('output/url', array(
		'href' => $quiz->getURL(),
		'text' => $quiz->title,
		'class' => 'elgg-river-object',
		'is_trusted' => true,
	));

	$excerpt = 'Score: '.strip_tags(elgg_get_excerpt($object->total_percentage)).'%';

	$summary = elgg_echo("izap-contest:river:answered", array($subject_link, $object_link, $quiz_link));

	echo elgg_view('river/elements/layout', array(
		'item' =>  $vars['item'],
		'summary' => $summary,
		'message' => $excerpt,
	));
}
