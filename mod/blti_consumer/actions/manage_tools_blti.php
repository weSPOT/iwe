<?php

  // must be logged in to use this page

admin_gatekeeper();

$user = get_loggedin_user();
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
$custom_params = trim(get_input('custom_params'));


$save = get_input('save', False);
$cancel = get_input('cancel', False);

$guid = get_input('guid');

$consumEnt = get_entity($guid);

if ($save && $consumEnt && $consumEnt->canEdit() && $name && $desc && $key && $secret) {

	$consumEnt->name = $name;
	$consumEnt->description = $desc;
	$consumEnt->callbackUrl = $callbackUrl;
	$consumEnt->toolurl = $toolurl;
	$consumEnt->key = $key;
	$consumEnt->secret = $secret;
	$consumEnt->preferheight = $preferheight;
	$consumEnt->sendname = $sendname;
	$consumEnt->sendemail = $sendemail;
	$consumEnt->sendprofile = $sendprofile;
	$consumEnt->launch = $launch;
	$consumEnt->custom_params = $custom_params;
	
	$consumEnt->save(); // probably unnecessary, but safe

	system_message('Your application ' . $name . ' has been updated.');	
} else if ($cancel) {

} else {
	register_error('Permission denied');
}

forward($CONFIG->wwwroot . 'pg/blti_consumer/register');