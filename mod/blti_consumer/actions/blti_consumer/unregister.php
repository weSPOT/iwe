<?php

  // must be logged in to use this page

admin_gatekeeper();

$guid = get_input('guid', -1);



if ($guid) {

	// create a new entity
	$consumEnt = blti_consumer_delete_consumer($guid);

	//
	// NOTE: 
	//   this action and its associated pages are intended for
	//   inbound clients only.
	// 
	//   outbound consumers should be registered indirectly 
	//   by the plugins implementing the oauth client.
	//

	system_message('Your application, ' . $name . ' has been successfully unregistered.');
    
} else {
	register_error('You must fill out both the name and description fields.');
}


forward($_SERVER['HTTP_REFERER']);

?>