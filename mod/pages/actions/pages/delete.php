<?php
/**
 * Remove a page
 *
 * Subpages are not deleted but are moved up a level in the tree
 *
 * @package ElggPages
 */

$guid = get_input('guid');
$page = get_entity($guid);
if (elgg_instanceof($page, 'object', 'page') || elgg_instanceof($page, 'object', 'page_top')) {
	// only allow owners and admin to delete
	if (elgg_is_admin_logged_in() || elgg_get_logged_in_user_guid() == $page->getOwnerGuid()) {
		$container = get_entity($page->container_guid);

		// Bring all child elements forward
		$parent = $page->parent_guid;
		$children = elgg_get_entities_from_metadata(array(
			'metadata_name' => 'parent_guid',
			'metadata_value' => $page->getGUID()
		));
		if ($children) {
			foreach ($children as $child) {
				$child->parent_guid = $parent;
			}
		}

    $phase = $page->phase;
    $activity_id = $page->activity_id;

		if ($page->delete()) {
			system_message(elgg_echo('pages:delete:success'));
      elgg_trigger_event('delete', 'annotation_from_ui', $page);
			if ($parent) {
				if ($parent = get_entity($parent)) {
					forward($parent->getURL());
				}
			}
			if (elgg_instanceof($container, 'group')) {
				forward("pages/group/$container->guid/all?phase=".$phase . '&activity_id=' . $activity_id);
			} else {
				forward("pages/owner/$container->username");
			}
		}
	}
}

register_error(elgg_echo('pages:delete:failure'));
forward(REFERER);
