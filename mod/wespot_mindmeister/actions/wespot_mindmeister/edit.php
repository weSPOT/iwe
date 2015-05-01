<?php
/**
 * Create or edit a MindMeister map
 */

$variables = elgg_get_config('wespot_mindmeister');
$input = array();
foreach ($variables as $name => $type) {
	$input[$name] = get_input($name);
	if ($name == 'title') {
		$input[$name] = strip_tags($input[$name]);
	}
	if ($type == 'tags') {
		$input[$name] = string_to_tag_array($input[$name]);
	}
}

// Get guids
$mindmap_guid = (int)get_input('mindmap_guid');
$container_guid = (int)get_input('container_guid');

elgg_make_sticky_form('mindmeistermap');

if (!$input['title']) {
	register_error(elgg_echo('wespot_mindmeister:error:no_title'));
	forward(REFERER);
}

$mindmap = get_entity($mindmap_guid);
if (!$mindmap || !$mindmap->canEdit()) {
	register_error(elgg_echo('wespot_mindmeister:error:no_save'));
	forward(REFERER);
}

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$mindmap->$name = $value;
	}
}

if(get_input('recommended_tags')) {
  $mindmap->recommended_tags = get_input('recommended_tags');
}

if(get_input('tag_recommender_algorithm')) {
  $mindmap->tag_recommender_algorithm = get_input('tag_recommender_algorithm');
}

// need to add check to make sure user can write to container
$mindmap->container_guid = $container_guid;

// Hang over from pages - which have children ?
$parent_guid = (int)get_input('parent_guid');
if ($parent_guid) {
	$mindmap->parent_guid = $parent_guid;
}

if ($mindmap->save()) {
	elgg_clear_sticky_form('mindmeistermap');

	// Now save description as an annotation
	$mindmap->annotate('mindmeistermap', $mindmap->description, $mindmap->access_id);

	system_message(elgg_echo('wespot_mindmeister:saved'));
	add_to_river('river/object/mindmeistermap/create', 'update', $mindmap->owner_guid, $mindmap->guid);

	forward($mindmap->getURL());
} else {
	register_error(elgg_echo('wespot_mindmeister:error:no_save_map'));
	forward(REFERER);
}

