<?php
/**
 * Remove a wespot MindMeister map
 */

elgg_load_library('elgg:wespot_mindmeisterservices');

$guid = get_input('guid');
$mindmap = get_entity($guid);
if ($mindmap) {
	if ($mindmap->canEdit()) {
		$container = get_entity($mindmap->container_guid);

		// Delete MINDMEISTER map in data area
		if (deleteMindMeisterMap($guid)) {

			if ($mindmap->delete()) {
                elgg_trigger_event('delete', 'annotation_from_ui', $mindmap);
				system_message(elgg_echo('wespot_mindmeister:delete:success'));
				if ($parent) {
					if ($parent = get_entity($parent)) {
						forward($parent->getURL());
					}
				}
				if (elgg_instanceof($container, 'group')) {
					forward("wespot_mindmeister/group/$container->guid/all");
				} else {
					forward("wespot_mindmeister/owner/$container->username");
				}
			}
		}
	}
}

register_error(elgg_echo('wespot_mindmeister:delete:failure'));
forward(REFERER);
