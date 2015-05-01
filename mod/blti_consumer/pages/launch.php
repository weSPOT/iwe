<?php

// Make sure we're logged in (send us to the front page if not)
gatekeeper();

// Get input data
$guid = (int) get_input('guid', -1);
$consumEnt = get_entity($guid);

if ($guid>0 && $consumEnt) {

	$content = blti_consumer_launch($consumEnt);
				  
	// format
	$layout = ""; //"two_column_left_sidebar";
	$body = elgg_view_layout($layout, array(
		'filter_context' => 'listing',
		'content' => $content,
	));
	
	// Draw page
	echo elgg_view_page($consumEnt->name, $body);
	
} else {
	register_error('Unknow tool blti '.$guid);
	forward($_SERVER['HTTP_REFERER']);
}

?>