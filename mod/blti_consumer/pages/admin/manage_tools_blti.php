<?php

  // Load Elgg engine
  //require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// must be logged in to see this page
admin_gatekeeper();

elgg_set_context('admin');

$user = elgg_get_logged_in_user_entity();

// get a list of all apps
$apps = elgg_get_entities(array('types' => 'object', 'subtypes' => 'blti_consumer'));

// existing applications
$content = '';//
if ($apps) {
	$title = elgg_echo('blti_consumer:register:desc');
		
	$content = elgg_view_entity_list($apps, array(), 0, 0, true, true, false);
	$content .= elgg_view('blti_consumer/registerform');
	
} else {
	$title  = elgg_echo('blti_consumer:register:none');

	$content = elgg_view('blti_consumer/registerform');
}

// format
$body = elgg_view_layout('two_column_left_sidebar', array(
	'filter_context' => 'manage_tools_blti',
	'content' => $content,
	'title' => $title,
));

// Draw page
echo elgg_view_page(elgg_echo('blti_consumer:registered'), $body);

?>