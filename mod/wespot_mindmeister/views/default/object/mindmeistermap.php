<?php
/**
 * View for an MindMeister map object
 *
 * @package ElggPages
 *
 * @uses $vars['entity']    The mind map object
 * @uses $vars['full_view'] Whether to display the full view
 * @uses $vars['revision']  This parameter not supported by elgg_view_entity()
 */

elgg_load_library('elgg:wespot_mindmeister');

$full = elgg_extract('full_view', $vars, FALSE);
$mindmap = elgg_extract('entity', $vars, FALSE);
$revision = elgg_extract('revision', $vars, FALSE);

if (!$mindmap) {
	return TRUE;
}

if ($revision) {
	$annotation = $revision;
} else {
	$annotation = $mindmap->getAnnotations('mindmeistermap', 1, 0, 'desc');
	if ($annotation) {
		$annotation = $annotation[0];
	}
}

$metadata = '';
$size = 'small';
if (elgg_in_context('widgets')) {
	$size = 'tiny';
}

$mindmap_icon = elgg_view('wespot_mindmeister/icon', array('annotation' => $annotation, 'size' => $size));
// if (!elgg_in_context('widgets')) {

// 	$editor = get_entity($annotation->owner_guid);
// 	$editor_link = elgg_view('output/url', array(
// 		'href' => "profile/$editor->username",
// 		'text' => $editor->name,
// 		'is_trusted' => true,
// 	));
// 
// 	$date = elgg_view_friendly_time($annotation->time_created);
// 	$editor_text = elgg_echo('wespot_arlearn:strapline', array($date, $editor_link));

	$owner = $vars['entity']->getOwnerEntity();
	$ownertxt = elgg_echo('unknown');
	if ($owner)
		$ownertxt = "<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>";
	$date = elgg_view_friendly_time($vars['entity']->time_created);
	$editor_text = elgg_echo('entity:default:strapline', array($date, $ownertxt));

	$tags = elgg_view('output/tags', array('tags' => $mindmap->tags));
	$categories = elgg_view('output/categories', $vars);

	$comments_count = $mindmap->countComments();
	//only display if there are commments
	if ($comments_count != 0 && !$revision) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $mindmap->getURL() . '#mindmap-comments',
			'text' => $text,
			'is_trusted' => true,
		));
	} else {
		$comments_link = '';
	}

	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'wespot_mindmeister',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));

	$subtitle = "$editor_text $comments_link $categories";
// }

// do not show the metadata and controls in widget view
if ($revision) {
	$metadata = '';
}

if ($full) {
	$body = elgg_view('output/longtext', array('value' => $annotation->value));

	$params = array(
		'entity' => $mindmap,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		'entity' => $mindmap,
		'title' => false,
		'icon' => $mindmap_icon,
		'summary' => $summary,
		'body' => $body,
	));
} else {
	$params = array(
		'entity' => $mindmap,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => '',
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		'entity' => $mindmap,
		'title' => false,
		'icon' => $mindmap_icon,
		'summary' => $summary,
		'body' => '',
	));
}