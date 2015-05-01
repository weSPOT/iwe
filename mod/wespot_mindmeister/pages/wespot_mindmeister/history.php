<?php
/**
 * History of revisions of a MindMeister map
 */
elgg_load_library('elgg:wespot_mindmeister');

$mindmap_guid = get_input('guid');
$mindmap = get_entity($mindmap_guid);
$container = $mindmap->getContainerEntity();

elgg_set_page_owner_guid($container->getGUID());

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb(elgg_echo('wespot_mindmeister:owner', array($container->name)), "wespot_mindmeister/group/$container->guid/all");
} else {
	elgg_push_breadcrumb(elgg_echo('wespot_mindmeister:owner', array($container->name)), "wespot_mindmeister/owner/$container->username");
}
wespot_mindmeister_prepare_parent_breadcrumbs($mindmap);
elgg_push_breadcrumb($mindmap->title, $mindmap->getURL());
elgg_push_breadcrumb(elgg_echo('wespot_mindmeister:history'));

$title = $mindmap->title . ": " . elgg_echo('wespot_mindmeister:history');

$content = list_annotations($mindmap_guid, 'mindmeistermap', 20, false);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
