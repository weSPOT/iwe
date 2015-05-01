<?php
/**
 * View a revision of MindMeister map.
 */
elgg_load_library('elgg:wespot_mindmeister');

$id = get_input('id');
$annotation = elgg_get_annotation_from_id($id);
if (!$annotation) {
	forward();
}

$mindmap = get_entity($annotation->entity_guid);
elgg_set_page_owner_guid($mindmap->getContainerGUID());

group_gatekeeper();
$container = elgg_get_page_owner_entity();

$title = $mindmap->title . ": " . elgg_echo('wespot_mindmeister:revision');

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb(elgg_echo('wespot_mindmeister:owner', array($container->name)), "wespot_mindmeister/group/$container->guid/all");
} else {
	elgg_push_breadcrumb(elgg_echo('wespot_mindmeister:owner', array($container->name)), "wespot_mindmeister/owner/$container->username");
}
wespot_mindmeister_prepare_parent_breadcrumbs($mindmap);
elgg_push_breadcrumb($mindmap->title, $mindmap->getURL());
elgg_push_breadcrumb(elgg_echo('wespot_mindmeister:revision'));

$content = elgg_view('object/mindmeistermap', array(
	'entity' => $mindmap,
	'revision' => $annotation,
	'full_view' => true,
));

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
