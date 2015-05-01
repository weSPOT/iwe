<?php

global $LOOP_COUNT_MSG;

function getUserToken($entity_guid)
{
    $entity        = get_entity($entity_guid);
    $owner_guid    = $entity->owner_guid;
    $ownerprovider = elgg_get_plugin_user_setting('provider', $owner_guid, 'elgg_social_login');
    $owneroauth    = str_replace("{$ownerprovider}_", '', elgg_get_plugin_user_setting('uid', $owner_guid, 'elgg_social_login'));
    return createARLearnUserToken($ownerprovider, $owneroauth);
}

/**
 * Call ARLearn to check if there are new messages.
 *
 * At present it gets the full list and filters out the ones it has already.
 *
 * It makes the call using the group owner user information,
 * but they don't have to be logged in at the time.
 */
function wespot_msg_sync_messages($group, $threadId)
{
    global $LOOP_COUNT_MSG;
    
    elgg_load_library('elgg:wespot_arlearnservices');
    elgg_load_library('elgg:wespot_arlearnmsgservices');
    
    if (!($game = wespot_msg_get_game($group->getGUID()))) {
        return false;
    }

    $usertoken = getUserToken($group->getGUID());
    
    if (isset($usertoken) && $usertoken != "") {
        wespot_msg_get_messages($usertoken, $group, $game, $threadId);
    }
}

/**
 * Call ARLearn to check if there are new threads.
 *
 * At present it gets the full list and filters out the ones it has already.
 *
 * It makes the call using the group owner user information,
 * but they don't have to be logged in at the time.
 */
function wespot_msg_sync_threads($group_guid)
{    
    elgg_load_library('elgg:wespot_arlearnservices');
    elgg_load_library('elgg:wespot_arlearnmsgservices');
    
    if (!($game = wespot_msg_get_game($group_guid))) {
        return false;
    }

    $group         = get_entity($group_guid);
    $owner_guid    = $group->owner_guid;
    $ownerprovider = elgg_get_plugin_user_setting('provider', $owner_guid, 'elgg_social_login');
    $owneroauth    = str_replace("{$ownerprovider}_", '', elgg_get_plugin_user_setting('uid', $owner_guid, 'elgg_social_login'));
    $usertoken     = createARLearnUserToken($ownerprovider, $owneroauth);
    
    if (isset($usertoken) && $usertoken != "") {
        wespot_msg_get_threads($usertoken, $group, $game);
    }
}

function wespot_msg_get_game($group_guid)
{
    $gamearray = elgg_get_entities(array(
        'type' => 'object',
        'subtype' => 'arlearngame',
        'owner_guid' => $group_guid
    ));
    
    if ($gamearray === FALSE || count($gamearray) == 0) {
        return false;
    }
    
    return $gamearray[0];    
}

function wespot_msg_get_thread($threadId)
{
    $entities = elgg_get_entities_from_metadata(array(
        'type' => 'object',
        'subtype' => 'arlearn_thread',
        'metadata_name' => 'threadId',
        'metadata_value' => $threadId
    ));
    
    if ($entities === FALSE || count($entities) == 0) {
        return false;
    }
    
    return $entities[0];    
}

function wespot_msg_get_user($providerCode, $oauthId)
{
    $providername     = getElggProviderName($providerCode);
    $user_uid         = $providername . "_" . $oauthId;
    $options          = array(
        'type' => 'user',
        'plugin_id' => 'elgg_social_login',
        'plugin_user_setting_name_value_pairs' => array(
            'uid' => $user_uid,
            'provider' => $providername
        ),
        'plugin_user_setting_name_value_pairs_operator' => 'AND',
        'limit' => 0
    );
    $users            = elgg_get_entities_from_plugin_user_settings($options);
    if (count($users) == 1) {
        return $users[0];
    }
    return false;
}

function wespot_msg_get_thread_options($group_guid)
{
    $returnvalue = array(0 => elgg_echo("wespot_msg:option:none"));
    $threads = elgg_get_entities(array(
        'types' => 'object',
        'subtypes' => 'arlearn_thread',
        'container_guids' => $group_guid, // this works for group widgets
        'limit' => 0
    ));
    foreach ($threads as $thread) {
        $returnvalue[$thread->threadId] = $thread->name;
    } 

    return $returnvalue;
}

function wespot_msg_get_messages($usertoken, $group, $game, $threadId, $fromtime = "", $resumptiontoken = "")
{
    global $LOOP_COUNT_MSG;
    
    // Just in case, to stop never ending loops.
    if ($LOOP_COUNT_MSG > 10) {
        return;
    }
    
    $LOOP_COUNT_MSG = $LOOP_COUNT_MSG + 1;
    
    $gameid = $game->arlearn_gameid;
    $runid  = $game->arlearn_runid;
    $thread = wespot_msg_get_thread($threadId);
    $args = array();
    
    if (isset($thread->lastSyncTime)) {
        $args['from'] = $thread->lastSyncTime;
    }

    $results = getARLearnThreadMessages($usertoken, $threadId, $args);
    
    if ($results == false) {
        return false;
    }
     
    $datareturned = json_decode($results);
    if (isset($datareturned->error)) {
        //@TODO: check documentation if function returns false
        register_error(elgg_echo("wespot_msg:failure")); 
    }
    
    $messages = $datareturned->messages;
    if (!$messages || count($messages) <= 0) {
        return false;
    }

    foreach ($messages as $message) {
        // Check if message already loaded
        $_messages = elgg_get_entities_from_metadata(array(
            'type' => 'object',
            'subtype' => 'arlearn_msg',
            'metadata_name' => 'messageId',
            'metadata_value' => $message->messageId
        ));
        // If not saved locally do it
        if (!$_messages || count($_messages) == 0) {
            //@TODO: if poster is not registered as Elgg user message will be ignored
            //discuss whether this is the correct behavior
            if ( $user = wespot_msg_get_user($message->senderProviderId, $message->senderId)) {
                $result = wespot_msg_add_message($user, $group, $message, $group->access_id);
                if ($result) {
                    //system_message(elgg_echo("wespot_msg:posted"));
                } else {
                    register_error(elgg_echo("wespot_msg:failure"));
                }
            }
        }
    }
    //store last sync time
    $thread->lastSyncTime = $datareturned->serverTime;
	elgg_set_ignore_access(true);
    $thread->save();
	elgg_set_ignore_access(false);
}


function wespot_msg_get_threads($usertoken, $group, $game, $fromtime = "", $resumptiontoken = "")
{    
    $gameid = $game->arlearn_gameid;
    $runid  = $game->arlearn_runid;

    //trigger default thread creation
    //TODO: default thread should be created with run creation
    getARLearnRunDefaultThread($usertoken, $runid);

    
    $results = getARLearnRunThreads($usertoken, $runid);
    
    if ($results == false) {
        return false;
    }
        
    $datareturned = json_decode($results);
    if (isset($datareturned->error)) {
        register_error(elgg_echo("wespot_msg:failure"));
    }
    
    $threads = $datareturned->threads;

    if (!$threads || count($threads) <= 0) {
        return false;
    }

    foreach ($threads as $thread) {
        // Check if thread is already loaded
        $_threads = elgg_get_entities_from_metadata(array(
            'type' => 'object',
            'subtype' => 'arlearn_thread',
            'metadata_name' => 'threadId',
            'metadata_value' => $thread->threadId
        ));
        // If not saved locally do it
        if (!$_threads || count($_threads) == 0) {
            $user_guid    = $group->owner_guid;
            $user = get_user($user_guid);
            if ($user instanceof ElggUser) {
                $result = wespot_msg_add_thread($user, $group, $thread, $group->access_id);
                if ($result) {
                    //system_message(elgg_echo("wespot_msg:posted"));
                } else {
                    register_error(elgg_echo("wespot_msg:failure"));
                }
            }
        }
    }
    
}

/**
 * Get Channel API token for a given user
 * @param $user logged in user
 * @return false, if the attempt failed, else the Channel API token
 */
function wespot_msg_get_channel_token($user, $refresh=false)
{
    global $SESSION;

    $token = NULL;
    if ( !$refresh && 
	 isset($SESSION['__elgg_wespot_msg_channel_token_created']) &&
	 (time() - $SESSION['__elgg_wespot_msg_channel_token_created'] < 7200) &&
	 isset($SESSION['__elgg_wespot_msg_channel_token']) 
    ) {
        $token = $SESSION['__elgg_wespot_msg_channel_token'];
    } else {
        $arlearn_user_token = getUserToken($user->getGUID());
        $results = getARLearnChannelAPIToken($arlearn_user_token);
        if ($results != false) {
            $datareturned = json_decode($results);
            if (!isset($datareturned->error)) {
                $token = $datareturned->token;
                $SESSION['__elgg_wespot_msg_channel_token_created'] = time();
                $SESSION['__elgg_wespot_msg_channel_token'] = $token;
            } else {
                 register_error(elgg_echo("wespot_msg:channel:error", $datareturned->error));
            }
        } else {
            //register_error(elgg_echo("wespot_msg:channel:failed"));
        }
    }
    

    return $token;
}

function wespot_msg_get_default_thread_id($group_guid) 
{
    elgg_load_library('elgg:wespot_arlearnservices');
    elgg_load_library('elgg:wespot_arlearnmsgservices');

    if (!($game = wespot_msg_get_game($group_guid))) {
        return false;
    }

    $group         = get_entity($group_guid);
    $runId         = $game->arlearn_runid;
    $owner_guid    = $group->owner_guid;
    $ownerprovider = elgg_get_plugin_user_setting('provider', $owner_guid, 'elgg_social_login');
    $owneroauth    = str_replace("{$ownerprovider}_", '', elgg_get_plugin_user_setting('uid', $owner_guid, 'elgg_social_login'));
    $usertoken     = createARLearnUserToken($ownerprovider, $owneroauth);
    
    if (isset($usertoken) && $usertoken != "") {
        return getARLearnRunDefaultThreadId($usertoken, $runId);
    }
    
    return false;
    
}
