<?php
/*
 * weSPOT web services plugin
 * Web Services to expose required functionality for external tools integration
 *
 * @package wespot_web_services
 * @author a.mikroyannidis@open.ac.uk; david.krmpotic@gmail.com
 *
 */

include 'wespot_pim_services.php';

function my_echo($string) {
    return $string;
}

expose_function("test.echo",
                "my_echo",
                 array("string" => array('type' => 'string')),
                 'A testing method which echos back a string',
                 'GET',
                 false,
                 false
                );

// http://localhost/elgg/services/api/rest/json/?method=elgg.process_events
// http://localhost/elgg/services/api/rest/json/?method=elgg.process_events&option=reset -> if table schema changes
// http://localhost/elgg/services/api/rest/json/?method=elgg.process_events&option=inspect -> debug info, current content of the table
expose_function("elgg.process_events",
    "process_stepup_requests",
    array("option" => array('type' => 'string', 'required' => false)),
    'desc',
    'GET',
    false,
    false
);

function process_stepup_requests($option = '') {
    elgg_load_library('elgg:wespot_stepup');
    if($option == 'inspect') {
        return get_db_contents();
    } else {
        return process_requests($option == 'reset');
    }
}


expose_function("inquiry.phases",
    "inquiry_phases",
    array("inquiry_id" => array('type' => 'string')),
    'desc',
    'GET',
    false,
    false
);


// http://localhost/elgg/services/api/rest/json/?method=inquiry.phases&inquiry_id=41650
function inquiry_phases($inquiry_id) {
    elgg_load_library('elgg:wespot_phases');
    global $phases_data;

    elgg_set_ignore_access(true);

    $inquiry = get_entity($inquiry_id);

    if($inquiry) {
      $phases = $inquiry->phases;

      elgg_set_ignore_access(false);

      return Array("phases" => $phases, "details" => array_map(function($v) use ($phases_data) { return Array((int)$v, $phases_data[(int)$v-1]['name']); }, str_split($phases)));
    } else {
      return "No inquiry with ID=".$inquiry_id;
    }
}

expose_function("inquiry.configuration",
    "inquiry_config",
    null,
    'desc',
    'GET',
    false,
    false
);

// http://localhost/elgg/services/api/rest/json/?method=inquiry.configuration
function inquiry_config($inquiry_id, $phase = null) {
    elgg_load_library('elgg:wespot_phases');
    global $phases_data;

    return $phases_data;
}

expose_function("inquiry.activities",
    "inquiry_activities",
    array("inquiry_id" => array('type' => 'string'), "phase" => array('type' => 'string', 'required' => false)),
    'desc',
    'GET',
    false,
    false
);

// http://localhost/elgg/services/api/rest/json/?method=inquiry.activities&inquiry_id=42473
function inquiry_activities($inquiry_id, $phase = null) {
    elgg_load_library('elgg:wespot_phases');

    elgg_set_ignore_access(true);

    $result = activities_for_api($inquiry_id, enabled_activities($inquiry_id, $phase));

    elgg_set_ignore_access(false);

    return $result;
}

expose_function("inquiry.skills",
    "inquiry_skills",
    array("inquiry_id" => array('type' => 'string'), "phase" => array('type' => 'string', 'required' => false)),
    'desc',
    'GET',
    false,
    false
);

// http://localhost/elgg/services/api/rest/json/?method=inquiry.skills&inquiry_id=42473
function inquiry_skills($inquiry_id, $_phase = null) {
    elgg_load_library('elgg:wespot_phases');
    global $phases_data;

    elgg_set_ignore_access(true);

    $skills = Array();
    $activities = enabled_activities($inquiry_id, $_phase);

    foreach($phases_data as $phase) {
        foreach($phase['tasks'] as $task) {
            if(in_array($task['activity_id'], $activities)) {
                foreach($task['skills'] as $skill) {
                    if(!in_array($skill, $skills)) {
                        array_push($skills, $skill);
                    }
                }
            }
        }
    }

    $skills_with_ids = Array();
    foreach($skills as $skill) {
        array_push($skills_with_ids, Array("skill" => $skill, "skill_id" => substr(md5($skill), 0, 5)));
    }

    elgg_set_ignore_access(false);

    return $skills_with_ids;
}


expose_function("inquiry.activities_per_skill",
    "activities_per_skill",
    array("inquiry_id" => array('type' => 'string'), "skill_ids" => array('type' => 'string')),
    'desc',
    'GET',
    false,
    false
);

// http://localhost/elgg/services/api/rest/json/?method=inquiry.activities_per_skill&inquiry_id=42473&skill_ids=26d93,7dc5c
function activities_per_skill($inquiry_id, $skill_ids) {
    elgg_load_library('elgg:wespot_phases');
    global $phases_data;

    elgg_set_ignore_access(true);

    $activities = enabled_activities($inquiry_id);
    $skills = explode(',', $skill_ids);

    $activities_per_skill = Array();
    foreach($skills as $skill_id) {
        $activities_per_skill[$skill_id] = Array();
    }

    $data = data_by_activity_ids();

    foreach($phases_data as $phase) {
        foreach($phase['tasks'] as $task) {
            if(in_array($task['activity_id'], $activities)) {
                foreach($task['skills'] as $skill) {
                    if(in_array(substr(md5($skill), 0, 5), $skills)) {
                        array_push($activities_per_skill[substr(md5($skill), 0, 5)], activity_for_api_form_task($inquiry_id, $data[$task['activity_id']]));
                    }
                }
            }
        }
    }

    elgg_set_ignore_access(false);

    return $activities_per_skill;
}

function count_active_users($minutes=10) {
    $seconds = 60 * $minutes;
    $count = count(find_active_users($seconds, 9999));
    return $count;
}

expose_function("users.active",
                "count_active_users",
                 array("minutes" => array('type' => 'int',
                                          'required' => false)),
                 'Number of users who have used the site in the past x minutes',
                 'GET',
                 true,
                 false
                );

function user_activity($minutes) {

	elgg_set_ignore_access(true);
	$seconds = time() - (60 * $minutes);
//	$types = array('user', 'group', 'object', 'site');
	$options = array(
//		'types' => $types,
		'posted_time_lower' => $seconds,
		'limit' => false,
	);

	$river_items = elgg_get_river($options);

	// parse Elgg river items
	foreach ($river_items as $v1) {
		foreach ($v1 as $key => &$value) {
			if ($key == "subject_guid" || $key == "object_guid"){
				if (get_entity($value)->getType() == 'user')
					$value = elgg_get_plugin_user_setting('uid', $value, 'elgg_social_login');
				else
					$value = get_entity_url($value);
			}
		}
    }
	elgg_set_ignore_access(false);
	return $river_items;
}

expose_function("user.activity",
                "user_activity",
                 array("minutes" => array('type' => 'int',
                                          'required' => true)),
                 'User activity in the past x minutes',
                 'GET',
                 true,
                 false
                );

function entity_url($guid) {

	return get_entity_url($guid);
}

expose_function("entity.url",
                "entity_url",
                 array("guid" => array('type' => 'int',
                                          'required' => true)),
                 'Entity URL for a given Globally Unique IDentifier (GUID)',
                 'GET',
                 true,
                 false
                );

function site_inquiries() {

	elgg_set_ignore_access(true);
    $options = array('limit'=>false, 'type'=>'group');
    $groups = elgg_get_entities($options);

    $return = array();
    if ($groups) {
        foreach ($groups as $group){
            $return[] = array('inquiryId'=>$group->getGUID(), 'title'=>$group->name, 'url'=>$group->getURL(), 'description'=>$group->description, 'icon'=>$group->getIcon());
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("site.inquiries",
                "site_inquiries",
                 array(),
                 'All inquiries of the site',
                 'GET',
                 true,
                 false
                );

function site_users($offset) {

	elgg_set_ignore_access(true);
    $options = array('limit'=>100, 'type'=>'user', 'offset'=>$offset);
    $users = elgg_get_entities($options);

    $return = array();
    if ($users) {
        foreach ($users as $user){
            $guid = $user->getGUID();
			$provider = elgg_get_plugin_user_setting('provider', $guid, 'elgg_social_login');
			$uid = str_replace("{$provider}_", '', elgg_get_plugin_user_setting('uid', $guid, 'elgg_social_login'));
			$return[] = array('oauthId'=>$uid, 'oauthProvider'=>$provider, 'name'=>$user->name, 'icon'=>$user->getIcon());
        }
    }
    elgg_set_ignore_access(false);
    return $return;
}

expose_function("site.users",
                "site_users",
                 array("offset" => array('type' => 'int',
                                          'required' => true)),
                 'All users of the site',
                 'GET',
                 true,
                 false
                );

function inquiry_users($inquiryId) {

	elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if($inquiry instanceof ElggGroup)
		$users = $inquiry->getMembers(false, 0, false);
	else throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$return = array();
	if ($users) {
		foreach ($users as $user){
			$guid = $user->getGUID();
			$provider = elgg_get_plugin_user_setting('provider', $guid, 'elgg_social_login');
			$uid = str_replace("{$provider}_", '', elgg_get_plugin_user_setting('uid', $guid, 'elgg_social_login'));
			$return[] = array('oauthId'=>$uid, 'oauthProvider'=>$provider, 'name'=>$user->name, 'icon'=>$user->getIcon());
		}
	}
	elgg_set_ignore_access(false);
	return $return;

}

expose_function("inquiry.users",
                "inquiry_users",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Users associated to an inquiry',
                 'GET',
                 true,
                 false
                );

 function get_guid_from_oauth($provider, $token) {
	global $CONFIG;
	$identifier = strtolower($provider);
	foreach (array("LinkedIn", "MySpace", "AOL") as $prov) {
		$identifier = str_replace(strtolower($prov), $prov, $identifier);
	}
	$identifier = ucfirst($identifier) . "_" . $token;
	$query = "SELECT entity_guid from {$CONFIG->dbprefix}private_settings where name = 'plugin:user_setting:elgg_social_login:uid' and value = '{$identifier}'";
	$guid = get_data_row($query)->entity_guid;
	if($guid) return $guid; else throw new Exception("User with OAuth identifier {$identifier} not found");
}

function user_inquiries($oauthId, $oauthProvider) {

	elgg_set_ignore_access(true);
	$user_id = get_guid_from_oauth($oauthProvider, $oauthId);
	$user_groups = elgg_get_entities_from_relationship(array('relationship'=> 'member', 'relationship_guid'=> $user_id, 'inverse_relationship'=> false, 'type'=> 'group', 'limit'=> false));

	$return = array();
	if ($user_groups) {
		foreach ($user_groups as $users_group){
			$return[] = array('inquiryId'=>$users_group->getGUID(), 'title'=>$users_group->name, 'url'=>$users_group->getURL(), 'description'=>$users_group->description, 'icon'=>$users_group->getIcon());
		}
	}
	elgg_set_ignore_access(false);
	return $return;

}

expose_function("user.inquiries",
                "user_inquiries",
                 array("oauthId" => array('type' => 'string',
                                          'required' => true),
                        "oauthProvider" => array('type' => 'string',
                                          'required' => true)),
                 'Inquiries associated to a user',
                 'GET',
                 true,
                 false
                );

function user_inquiries_admin($oauthId, $oauthProvider) {

	elgg_set_ignore_access(true);
	$user_id = get_guid_from_oauth($oauthProvider, $oauthId);
	$user_groups = elgg_get_entities_from_relationship(array('relationship'=> 'member', 'relationship_guid'=> $user_id, 'inverse_relationship'=> false, 'type'=> 'group', 'limit'=> false));

	$return = array();
	if ($user_groups) {
		foreach ($user_groups as $users_group){
			if(check_entity_relationship($user_id, 'operator', $users_group->getGUID()) || ($user_id == $users_group->getOwnerGUID()))
				$return[] = array('inquiryId'=>$users_group->getGUID(), 'title'=>$users_group->name, 'url'=>$users_group->getURL(), 'description'=>$users_group->description, 'icon'=>$users_group->getIcon());
		}
	}
	elgg_set_ignore_access(false);
	return $return;

}

expose_function("user.inquiriesAdmin",
                "user_inquiries_admin",
                 array("oauthId" => array('type' => 'string',
                                          'required' => true),
                        "oauthProvider" => array('type' => 'string',
                                          'required' => true)),
                 'Inquiries that the user is an admin of',
                 'GET',
                 true,
                 false
                );

function user_friends($oauthId, $oauthProvider) {

	elgg_set_ignore_access(true);
	$user_id = get_guid_from_oauth($oauthProvider, $oauthId);
	$user_friends_array = get_user_friends($user_id, ELGG_ENTITIES_ANY_VALUE, 0);

	$return = array();
	if ($user_friends_array) {
		foreach ($user_friends_array as $user_friend){
			$guid = $user_friend->getGUID();
			$provider = elgg_get_plugin_user_setting('provider', $guid, 'elgg_social_login');
			$uid = str_replace("{$provider}_", '', elgg_get_plugin_user_setting('uid', $guid, 'elgg_social_login'));
			$return[] = array('oauthId'=>$uid, 'oauthProvider'=>$provider, 'name'=>$user_friend->name, 'icon'=>$user_friend->getIcon());
		}
	}
	elgg_set_ignore_access(false);
	return $return;

}

expose_function("user.friends",
                "user_friends",
                 array("oauthId" => array('type' => 'string',
                                          'required' => true),
                        "oauthProvider" => array('type' => 'string',
                                          'required' => true)),
                 'Friends of a user',
                 'GET',
                 true,
                 false
                );

function user_email($oauthId, $oauthProvider) {

	elgg_set_ignore_access(true);
	$user_id = get_guid_from_oauth($oauthProvider, $oauthId);
	$email = get_entity($user_id)->contactemail;
	elgg_set_ignore_access(false);
	return $email;

}

expose_function("user.email",
                "user_email",
                 array("oauthId" => array('type' => 'string',
                                          'required' => true),
                        "oauthProvider" => array('type' => 'string',
                                          'required' => true)),
                 'Email address of a user',
                 'GET',
                 true,
                 false
                );

function inquiry_arlearngame($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$gamearray = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'arlearngame',
		'owner_guid' => $inquiryId
	));

	if ($gamearray === FALSE || count($gamearray) == 0) {
		$gameid = null;
	} else {
		$game = $gamearray[0];
		$gameid = $game->arlearn_gameid;
	}
	elgg_set_ignore_access(false);
	return $gameid;
}

expose_function("inquiry.arlearngame",
                "inquiry_arlearngame",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'ARLearn game associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_arlearnrun($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$gamearray = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'arlearngame',
		'owner_guid' => $inquiryId
	));

	if ($gamearray === FALSE || count($gamearray) == 0) {
		$runid = null;
	} else {
		$game = $gamearray[0];
		$runid = $game->arlearn_runid;
	}
	elgg_set_ignore_access(false);
	return $runid;

}

expose_function("inquiry.arlearnrun",
                "inquiry_arlearnrun",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'ARLearn run associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function arlearngame_inquiry($gameId) {

	elgg_set_ignore_access(true);
	$gameArray = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'arlearngame',
		'metadata_name' => 'arlearn_gameid',
		'metadata_value' => $gameId,
	));

	if (!$gameArray or count($gameArray) == 0) {
		throw new Exception("ARLearnGame with identifier {$gameId} not found");
	} else {
		$game = $gameArray[0];
		$inquiryId = $game->owner_guid;
		$inquiry = get_entity($inquiryId);
		$return = array('inquiryId'=>$inquiryId, 'title'=>$inquiry->name, 'url'=>$inquiry->getURL(), 'description'=>$inquiry->description, 'icon'=>$inquiry->getIcon());
	}
	elgg_set_ignore_access(false);
	return $return;

}

expose_function("arlearngame.inquiry",
                "arlearngame_inquiry",
                 array("gameId" => array('type' => 'int',
                                          'required' => true)),
                 'Inquiry associated to an ARLearn game',
                 'GET',
                 true,
                 false
                );

function arlearnrun_inquiry($runId) {

	elgg_set_ignore_access(true);
	$gameArray = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'arlearngame',
		'metadata_name' => 'arlearn_runid',
		'metadata_value' => $runId,
	));

	if (!$gameArray or count($gameArray) == 0) {
		throw new Exception("ARLearnRun with identifier {$runId} not found");
	} else {
		$game = $gameArray[0];
		$inquiryId = $game->owner_guid;
		$inquiry = get_entity($inquiryId);
		$return = array('inquiryId'=>$inquiryId, 'title'=>$inquiry->name, 'url'=>$inquiry->getURL(), 'description'=>$inquiry->description, 'icon'=>$inquiry->getIcon());
	}
	elgg_set_ignore_access(false);
	return $return;

}

expose_function("arlearnrun.inquiry",
                "arlearnrun_inquiry",
                 array("runId" => array('type' => 'int',
                                          'required' => true)),
                 'Inquiry associated to an ARLearn run',
                 'GET',
                 true,
                 false
                );

function inquiry_hypothesis($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => array('hypothesis_top', 'hypothesis'),
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $hypothesis){
			$return[] = array('hypothesisId'=>$hypothesis->getGUID(), 'title'=>$hypothesis->title, 'description'=>$hypothesis->description, 'url'=>get_entity_url($hypothesis->getGUID()));
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.hypothesis",
                "inquiry_hypothesis",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Hypothesis associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_notes($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => array('notes_top', 'notes'),
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $notes){
			$return[] = array('notesId'=>$notes->getGUID(), 'title'=>$notes->title, 'description'=>$notes->description, 'url'=>get_entity_url($notes->getGUID()));
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.notes",
                "inquiry_notes",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Notes associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_conclusions($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => array('conclusions_top', 'conclusions'),
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $conclusions){
			$return[] = array('conclusionsId'=>$conclusions->getGUID(), 'title'=>$conclusions->title, 'description'=>$conclusions->description, 'url'=>get_entity_url($conclusions->getGUID()));
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.conclusions",
                "inquiry_conclusions",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Conclusions associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_reflection($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => array('reflection_top', 'reflection'),
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $reflection){
			$return[] = array('reflectionId'=>$reflection->getGUID(), 'title'=>$reflection->title, 'description'=>$reflection->description, 'url'=>get_entity_url($reflection->getGUID()));
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.reflection",
                "inquiry_reflection",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Reflection associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_files($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => 'file',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $file){
//			$return[] = array('title'=>$file->title, 'description'=>$file->description, 'url'=>get_entity_url($file->getGUID()));
			$return[] = array('fileId'=>$file->getGUID(), 'title'=>$file->title, 'description'=>$file->description, 'url'=>elgg_get_site_url().'file/download/'.$file->getGUID());
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.files",
                "inquiry_files",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Files associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_pages($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => array('page_top', 'page'),
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $page){
			$return[] = array('pageId'=>$page->getGUID(), 'title'=>$page->title, 'description'=>$page->description, 'url'=>get_entity_url($page->getGUID()));
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.pages",
                "inquiry_pages",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Pages associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_questions($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => 'question',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $question){
			$return[] = array('questionId'=>$question->getGUID(), 'question'=>$question->title, 'description'=>$question->description, 'url'=>get_entity_url($question->getGUID()), 'tags'=>$question->tags);
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.questions",
                "inquiry_questions",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Questions associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_answers($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => 'answer',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $answer){
        	$question = get_entity($answer->question_guid);
			$return[] = array('questionId'=>$question->getGUID(), 'question'=>$question->title, 'description'=>$question->description, 'answerId'=>$answer->getGUID(), 'answer'=>$answer->description, 'url'=>get_entity_url($answer->getGUID()));
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.answers",
                "inquiry_answers",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Answers to the questions of an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_mindmaps($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => 'mindmeistermap',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $mindmap){
			$return[] = array('mindmapId'=>$mindmap->getGUID(), 'title'=>$mindmap->title, 'description'=>$mindmap->description, 'url'=>get_entity_url($mindmap->getGUID()));
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.mindmaps",
                "inquiry_mindmaps",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Mind maps associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_blogs($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $blog){
			$return[] = array('blogId'=>$blog->getGUID(), 'title'=>$blog->title, 'description'=>$blog->description, 'url'=>get_entity_url($blog->getGUID()));
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.blogs",
                "inquiry_blogs",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Blogs associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_discussions($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'object',
		'subtype' => 'groupforumtopic',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $discussion){
			$return[] = array('discussionId'=>$discussion->getGUID(), 'title'=>$discussion->title, 'description'=>$discussion->description, 'url'=>get_entity_url($discussion->getGUID()));
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.discussions",
                "inquiry_discussions",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Discussions associated to an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_subinquiries($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$options = array(
		'type' => 'group',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);

	$return = array();
    if ($content) {
        foreach ($content as $subgroup){
            $return[] = array('inquiryId'=>$subgroup->getGUID(), 'title'=>$subgroup->name, 'url'=>$subgroup->getURL(), 'description'=>$subgroup->description, 'icon'=>$subgroup->getIcon());
        }
    }
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.subinquiries",
                "inquiry_subinquiries",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Sub-inquiries of an inquiry',
                 'GET',
                 true,
                 false
                );

function inquiry_parent($inquiryId) {

    elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");

	$return = array();
	if($inquiry->container_guid) {
		$parent = get_entity($inquiry->container_guid);
		$return[] = array('inquiryId'=>$parent->getGUID(), 'title'=>$parent->name, 'url'=>$parent->getURL(), 'description'=>$parent->description, 'icon'=>$parent->getIcon());
	}
    elgg_set_ignore_access(false);
    return $return;

}

expose_function("inquiry.parent",
                "inquiry_parent",
                 array("inquiryId" => array('type' => 'int',
                                          'required' => true)),
                 'Parent of an inquiry',
                 'GET',
                 true,
                 false
                );

/*
function site_fixinquiries() {

	elgg_set_ignore_access(true);
    $options = array('limit'=>false, 'type'=>'group');
    $groups = elgg_get_entities($options);

    $count = 0;
    if ($groups) {
        foreach ($groups as $group){
            if(($group->access_id > 2) && ($group->access_id != $group->group_acl)){
				$group->access_id = $group->group_acl;
				$group->save();
				$count++;
			}
        }
    }
    elgg_set_ignore_access(false);
    return $count;

}

expose_function("site.fixinquiries",
                "site_fixinquiries",
                 array(),
                 'Fix access permissions for the inquiries of the site',
                 'GET',
                 true,
                 false
                );
*/

function webservice_init() {
}
register_elgg_event_handler('init', 'system', 'webservice_init');

?>
