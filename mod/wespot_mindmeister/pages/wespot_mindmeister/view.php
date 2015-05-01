<?php
/**
 * View a single MindMeister map
 */

elgg_load_library('elgg:wespot_mindmeister');
elgg_load_library('elgg:wespot_mindmeisterservices');

$mindmap_guid = get_input('guid');
$mindmap = get_entity($mindmap_guid);
if (!$mindmap) {
	forward();
}

elgg_set_page_owner_guid($mindmap->getContainerGUID());

group_gatekeeper();

$container = elgg_get_page_owner_entity();
$title = $mindmap->title;

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb(elgg_echo('wespot_mindmeister:owner', array($container->name)), "wespot_mindmeister/group/$container->guid/all");
} else {
	elgg_push_breadcrumb(elgg_echo('wespot_mindmeister:owner', array($container->name)), "wespot_mindmeister/owner/$container->username");
}

wespot_mindmeister_prepare_parent_breadcrumbs($mindmap);
elgg_push_breadcrumb($title);

elgg_register_title_button();

$content = elgg_view_entity($mindmap, array('full_view' => true));

/** ADD IFRAME FOR MINDMEISTER - VIEW ONLY MODE **/
$map_filename = $mindmap->map_filename;
if (isset($map_filename) && $map_filename != "") {
	$username = get_loggedin_user()->username;
	if ($mindmap->canEdit(get_loggedin_user()->getGUID()))
		$src = editMindMeisterMapURL($mindmap_guid, $mindmap->getURL(), $mindmap->map_filename, $username);
	else
		$src = viewMindMeisterMapURL($mindmap_guid, $mindmap->getURL(), $mindmap->map_filename, $username);
	$content .= '<p><h3><a href="'.$src.'" target="_blank">'.elgg_echo('wespot_mindmeister:openmessage').'</a></h3></p>';
}

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
