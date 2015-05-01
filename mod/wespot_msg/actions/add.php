<?php
/**
 * ARLearn Message board: add message action
 *
 * @package Elgg wespot_msg
 */

action_gatekeeper();

$owner_guid = get_input("owner_guid");
$owner      = get_entity($owner_guid);

$threadId = get_input("threadId");

// check if user is group memeber?
if ($owner instanceof ElggGroup && !can_write_to_container(0, $owner->getGUID())) {
    register_error(sprintf(elgg_echo("wespor_msg:mustbeingroup"), $owner->name));
    forward(REFERER);
}

$message_content = get_input('message_content');
if (empty($message_content)) {
    register_error(elgg_echo("wespot_msg:blank"));
    forward(REFERER);
}

$gamearray  = elgg_get_entities(array(
    'type' => 'object',
    'subtype' => 'arlearngame',
    'owner_guid' => $owner_guid
));

if ($gamearray === FALSE || count($gamearray) == 0) {
    forward(REFERER);
}

$game     = $gamearray[0];
$runId    = $game->arlearn_runid;

elgg_load_library('elgg:wespot_arlearnservices');
elgg_load_library('elgg:wespot_arlearnmsgservices');

$ownerprovider = elgg_get_plugin_user_setting('provider', elgg_get_logged_in_user_guid(), 'elgg_social_login');
$owneroauth    = str_replace("{$ownerprovider}_", '', elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login'));
$usertoken     = createARLearnUserToken($ownerprovider, $owneroauth);

if (!isset($usertoken) || $usertoken == "") {
    register_error(elgg_echo("wespot_msg:somethingwentwrong"));
    forward(REFERER);
}

$response = createARLearnMessage($usertoken, $runId, $threadId, $message_content);

if ($response == false) {
    register_error(elgg_echo("wespot_msg:failure"));
}

$datareturned = json_decode($response);
if (isset($datareturned->error)) {
    register_error(elgg_echo("wespot_msg:failure"));
}

$result = wespot_msg_add_message(elgg_get_logged_in_user_entity(), $owner, $datareturned, $owner->access_id);

if (!$result) {
    register_error(elgg_echo("wespot_msg:failure"));
}
//system_message(elgg_echo("wespot_msg:posted"));

forward(REFERER);
