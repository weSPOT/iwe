<?php
/**
 * Delete a bookmark
 *
 * @package Bookmarks
 */

$guid = get_input('guid');
$bookmark = get_entity($guid);

if (elgg_instanceof($bookmark, 'object', 'bookmarks') && $bookmark->canEdit()) {

	$phase = $bookmark->phase;
	$activity_id = $bookmark->activity_id;

	$container = $bookmark->getContainerEntity();
	if ($bookmark->delete()) {
		elgg_trigger_event('delete', 'annotation_from_ui', $bookmark);
		system_message(elgg_echo("bookmarks:delete:success"));
		if (elgg_instanceof($container, 'group')) {
			forward("bookmarks/group/$container->guid/all?phase=".$phase . '&activity_id=' . $activity_id);
		} else {
			forward("bookmarks/owner/$container->username");
		}
	}
}

register_error(elgg_echo("bookmarks:delete:failed"));
forward(REFERER);
