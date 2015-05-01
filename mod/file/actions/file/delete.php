<?php
/**
* Elgg file delete
*
* @package ElggFile
*/

$guid = (int) get_input('guid');

$file = new FilePluginFile($guid);
if (!$file->guid) {
	register_error(elgg_echo("file:deletefailed"));
	forward('file/all');
}

if (!$file->canEdit()) {
	register_error(elgg_echo("file:deletefailed"));
	forward($file->getURL());
}

$container = $file->getContainerEntity();

$phase = $file->phase;

if (!$file->delete()) {
	register_error(elgg_echo("file:deletefailed"));
} else {
    elgg_trigger_event('delete', 'annotation_from_ui', $file);
	system_message(elgg_echo("file:deleted"));
}

if (elgg_instanceof($container, 'group')) {
	forward("file/group/$container->guid/all?phase=".$phase);
} else {
	forward("file/owner/$container->username");
}
