<?php
/**
 * Edit a MindMeister map
 */

elgg_load_library('elgg:wespot_mindmeisterservices');
elgg_load_library('elgg:wespot_mindmeister');

gatekeeper();

$mindmap_guid = (int)get_input('guid');
$mindmap = get_entity($mindmap_guid);
if (!$mindmap) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

$container = $mindmap->getContainerEntity();
if (!$container) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

elgg_set_page_owner_guid($container->getGUID());

elgg_push_breadcrumb($mindmap->title, $mindmap->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));

$title = elgg_echo("wespot_mindmeister:edit");

if ($mindmap->canEdit()) {
	$vars = wespot_mindmeister_prepare_form_vars($mindmap);
	$content = elgg_view_form('wespot_mindmeister/edit', array(), $vars);
} else {
	$content = elgg_echo("wespot_mindmeister:noaccess");
}

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
