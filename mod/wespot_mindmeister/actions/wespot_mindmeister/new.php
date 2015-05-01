<?php
/**
 * Create or edit a MindMeister map
 */

elgg_load_library('elgg:wespot_mindmeisterservices');

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
$container_guid = (int)get_input('container_guid');

elgg_make_sticky_form('mindmeistermap');

if (!$input['title']) {
	register_error(elgg_echo('wespot_mindmeister:error:no_title'));
	forward(REFERER);
}

$mindmap = new ElggObject();
$mindmap->subtype = 'mindmeistermap';

if (sizeof($input) > 0) {
	foreach ($input as $name => $value) {
		$mindmap->$name = $value;
	}
}

// need to add check to make sure user can write to container
$mindmap->container_guid = $container_guid;

// Hang over from pages - whihc have children ?
$parent_guid = (int)get_input('parent_guid');
if ($parent_guid) {
	$mindmap->parent_guid = $parent_guid;
}

// set access to public otherwise MindMeister won't load the map
$mindmap->access_id = ACCESS_PUBLIC;

if ($mindmap->save()) {
	elgg_clear_sticky_form('mindmeistermap');

	// Now save description as an annotation
	$mindmap->annotate('mindmeistermap', $mindmap->description, $mindmap->access_id);

	// Need to create Elgg object first to get guid for the filename.
	$mindmap->map_filename = 'map'.$mindmap->guid.$mindmap->owner_guid.".mind";
	if ($mindmap->save()) {
		// Copy over the MindMeister template file to the data area and
		// name it the new filename for this map instance as created above.
		createNewMindMeisterMap($mindmap->map_filename, $mindmap->owner_guid);

		system_message(elgg_echo('wespot_mindmeister:saved'));
		add_to_river('river/object/mindmeistermap/create', 'create', $mindmap->owner_guid, $mindmap->guid);

		// Now forward to the map view
		forward(elgg_get_site_entity()->url.'wespot_mindmeister/view/'.$mindmap->guid);
	} else {
		register_error(elgg_echo('wespot_mindmeister:error:no_save_map'));
		forward(REFERER);
	}
} else {
	register_error(elgg_echo('wespot_mindmeister:error:no_save_map'));
	forward(REFERER);
}

