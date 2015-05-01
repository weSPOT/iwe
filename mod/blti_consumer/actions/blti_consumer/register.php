<?php

  // must be logged in to use this page

admin_gatekeeper();

$cancel = get_input('cancel', False);
if ($cancel) {
	forward($CONFIG->wwwroot . 'pg/blti_consumer/manage_tools_blti');
} else {
$user = elgg_get_logged_in_user_entity();
$name = trim(get_input('name'));
$desc = trim(get_input('desc'));
$toolurl = trim(get_input('toolurl'));
$callbackUrl = trim(get_input('callbackUrl'));
$key = trim(get_input('key'));
$secret = trim(get_input('secret'));
$preferheight = trim(get_input('preferheight'));
$sendname = get_input('sendname',0);
$sendemail = get_input('sendemail',0);
$sendprofile = get_input('sendprofile',0);
$launch = get_input('launch',0);
$debug = get_input('debug',0);
$custom_params = trim(get_input('custom_params'));

$guid = get_input('guid', -1);



if ($name && $desc) {

	if (!$key || !$secret) {
		// generate a key and secret
		$key = md5(time());
		$secret = md5(md5(time() + time()));
	}

	// create a new entity
	$consumEnt = blti_consumer_create_consumer($guid,$name, $desc, $key, $secret, $toolurl, $callbackUrl, 
					$preferheight, $sendname, $sendemail, $sendprofile, $launch, $debug, $custom_params);

	//
	// NOTE: 
	//   this action and its associated pages are intended for
	//   inbound clients only.
	// 
	//   outbound consumers should be registered indirectly 
	//   by the plugins implementing the oauth client.
	//

	system_message('Your application, ' . $name . ' has been successfully registered.');
    
} else {
	register_error('You must fill out both the name and description fields.');
}


forward($_SERVER['HTTP_REFERER']);
}
?>