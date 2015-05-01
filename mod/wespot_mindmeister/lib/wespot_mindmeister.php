<?php
/**
 * Prepare the add/edit form variables
 *
 * @param ElggObject $mindmap
 * @param String $parent_guid (optional) guid of the parent to the ElggObject passed. Defaults to 0.
 * @return array
 */
function wespot_mindmeister_prepare_form_vars($mindmap = null, $parent_guid = 0) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_PUBLIC,
		'write_access_id' => ACCESS_PRIVATE,
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $mindmap,
		'parent_guid' => $parent_guid,
	);

	if ($mindmap) {
		foreach (array_keys($values) as $field) {
			if (isset($mindmap->$field)) {
				$values[$field] = $mindmap->$field;
			}
		}
	}

	if (elgg_is_sticky_form('mindmeistermap')) {
		$sticky_values = elgg_get_sticky_values('mindmeistermap');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('mindmeistermap');

	return $values;
}

/**
 * Adds the breadcrumbs for all ancestors
 *
 * @param ElggObject $mindmap Page entity
 */
function wespot_mindmeister_prepare_parent_breadcrumbs($mindmap) {
	if ($mindmap && $mindmap->parent_guid) {
		$parents = array();
		$parent = get_entity($mindmap->parent_guid);
		while ($parent) {
			array_push($parents, $parent);
			$parent = get_entity($parent->parent_guid);
		}
		while ($parents) {
			$parent = array_pop($parents);
			elgg_push_breadcrumb($parent->title, $parent->getURL());
		}
	}
}