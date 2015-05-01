<?php
/**
 * Edit a file
 *
 * @package ElggFile
 */

elgg_load_library('elgg:file');

gatekeeper();

$file_guid = (int) get_input('guid');
$file = new FilePluginFile($file_guid);
if (!$file) {
	forward();
}
if (!$file->canEdit()) {
	forward();
}

$title = elgg_echo('file:edit');

//elgg_push_breadcrumb(elgg_echo('file'), "file/all");
$owner = elgg_get_page_owner_entity();
if (elgg_instanceof($owner, 'group')) {
	elgg_push_breadcrumb(elgg_echo('file:user', array($owner->name)), "file/group/$owner->guid/all?phase=" . $file->phase . '&activity_id=' . $file->activity_id);
} else {
	elgg_push_breadcrumb(elgg_echo('file:user', array($owner->name)), "file/owner/$owner->username");
}
elgg_push_breadcrumb($file->title, $file->getURL());
elgg_push_breadcrumb($title);

elgg_set_page_owner_guid($file->getContainerGUID());

$form_vars = array('enctype' => 'multipart/form-data');
$body_vars = file_prepare_form_vars($file);

$content = elgg_view_form('file/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
