<?php
/**
 * Delete blog entity
 *
 * @package Blog
 */

$blog_guid = get_input('guid');
$blog = get_entity($blog_guid);

if (elgg_instanceof($blog, 'object', 'blog') && $blog->canEdit()) {

	$phase = $blog->phase;
	$activity_id = $blog->activity_id;

	$container = get_entity($blog->container_guid);
	if ($blog->delete()) {
		elgg_trigger_event('delete', 'annotation_from_ui', $blog);
		system_message(elgg_echo('blog:message:deleted_post'));
		if (elgg_instanceof($container, 'group')) {
			forward("blog/group/$container->guid/all?phase=".$phase . '&activity_id=' . $activity_id);
		} else {
			forward("blog/owner/$container->username");
		}
	} else {
		register_error(elgg_echo('blog:error:cannot_delete_post'));
	}
} else {
	register_error(elgg_echo('blog:error:post_not_found'));
}

forward(REFERER);