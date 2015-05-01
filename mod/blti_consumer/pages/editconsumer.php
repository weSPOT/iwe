<?php

  // Load Elgg engine
  //require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// must be logged in to see this page
admin_gatekeeper();

set_context('admin');

$user = get_loggedin_user();

$guid = get_input('guid');

$consumEnt = get_entity($guid);

if ($consumEnt->canEdit()) {

	$area2 .= elgg_view_title(elgg_echo('blti_consumer:consumer:edit:title'));

	$form = elgg_view('blti_consumer/editconsumer', array('entity' => $consumEnt));
	$area2 .= elgg_view('page_elements/contentwrapper', array('body' => $form));

} else {

	$area2 .= 'Permission Denied';

}
			  
// format
$body = elgg_view_layout("two_column_left_sidebar", '', $area2);

// Draw page
page_draw(elgg_echo('blti_consumer:consumer:edit:title'), $body);

?>