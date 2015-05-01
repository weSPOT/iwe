<?php
/**
 * arlearn_msg river view
 */

$object     = $vars['item']->getObjectEntity();
$excerpt    = strip_tags($object->title);
$excerpt    = elgg_get_excerpt($excerpt);

$subject = $vars['item']->getSubjectEntity();
$subject_link = elgg_view('output/url', array(
        'href' => $subject->getURL(),
        'text' => $subject->name,
        'class' => 'elgg-river-subject',
        'is_trusted' => true,
));

$object_link = elgg_view('output/url', array(
        'href' => $object->getURL(),
        'text' => $object->name,
        'class' => 'elgg-river-object',
        'is_trusted' => true,
));

$summary = elgg_echo("river:create:object:arlearn_thread", array($subject_link, $object_link));

echo elgg_view('river/elements/layout', array(
        'item' => $vars['item'],
        'message' => $excerpt,
        'summary' => $summary,
));
