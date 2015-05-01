<?php

  // Load Elgg engine
  //require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
// must be logged in to see this page
admin_gatekeeper();

elgg_set_context('admin');

$user = elgg_get_logged_in_user_entity();

$guid = get_input('guid');
if (!$guid) {
	register_error(elgg_echo('noaccess'));
	forward('');
}

$consumEnt = get_entity($guid);
$area2 = '';
if ($consumEnt->canEdit()) {

	$area2 = elgg_view('blti_consumer/editconsumer', array('entity' => $consumEnt));

} else {

	$area2 .= 'Permission Denied';

}
			  
// format
$body = elgg_view_layout('two_column_left_sidebar', array(
	'filter_context' => 'editconsumer',
	'content' => $area2,
	'title' => elgg_echo('blti_consumer:consumer:edit:title'),
));

// Draw page
echo elgg_view_page(elgg_echo('blti_consumer:consumer:edit:title'), $body);

?>