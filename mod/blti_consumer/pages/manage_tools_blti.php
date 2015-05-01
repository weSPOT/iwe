<?php

  // Load Elgg engine
  //require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// must be logged in to see this page
admin_gatekeeper();

set_context('admin');

$user = get_loggedin_user();

// get a list of all apps
$apps = elgg_get_entities(array('types' => 'object', 'subtypes' => 'blti_consumer'));

// existing applications
$area2 = elgg_view_title(elgg_echo('blti_consumers'));
if ($apps) {
	$text = elgg_echo('blti_consumer:register:desc');
		
	$text .= elgg_view('blti_consumer/registerform');

	$area2 .= elgg_view('page_elements/contentwrapper', array('body' => $text));
		
	$tokList = elgg_view_entity_list($apps, count($apps), 0, 0, true, true, false);
	
	$area2 .= $tokList;
} else {
	$text = elgg_echo('blti_consumer:register:none');

	$text .= elgg_view('blti_consumer/registerform');

	$area2 .= elgg_view('page_elements/contentwrapper', array('body' => $text));
}


			  
// format
$body = elgg_view_layout("two_column_left_sidebar", '', $area2);

// Draw page
page_draw(elgg_echo('blti_consumer:registered'), $body);

?>