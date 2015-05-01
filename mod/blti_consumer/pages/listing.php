<?php

  // Load Elgg engine
  //require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// must be logged in to see this page
gatekeeper();

// get a list of all apps
$apps = elgg_get_entities(array('types' => 'object', 'subtypes' => 'blti_consumer'));

$content = '';
// existing applications
$title = elgg_echo('blti_consumers');
if ($apps) {
	$content = elgg_view_entity_list($apps, array(), 0, 0, true, true, false);
	
} else {
	$text = elgg_echo('blti_consumer:register:none');
	$content .= $text;
}


// format
$body = elgg_view_layout('two_column_left_sidebar', array(
	'filter_context' => 'listing',
	'content' => $content,
	'title' => $title,
));
// Draw page
echo elgg_view_page($title, $body);

?>