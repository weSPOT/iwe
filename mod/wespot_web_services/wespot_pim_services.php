<?php
/* 
 * Addition to the weSPOT web services plugin for use by PIM
 *
 * @package wespot_web_services
 * @author a.mikroyannidis@open.ac.uk
 */

function somefunction_ovveride_permissions($hook_name, $entity_type, $return_value, $parameters){

    return true;
}

// returns the domain of the client
function domain_echo() {

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	$domain = gethostbyaddr($ip);
	return $domain;

}

expose_function("test.domain", 
                "domain_echo", 
                 array(),
                 'A testing method which echos back the domain of the client',
                 'GET',
                 false,
                 false
                );

// checks the domain of the client           
function is_allowed_domain(){

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	$domain = gethostbyaddr($ip);
	
	if(stristr($domain,"gae.googleusercontent.com") === FALSE)
		return false;
	else 
		return true;	
}

                
/**
 * Create Inquiry through an API call.
 *
 * @param string $name (Inquiry name)
 * @param string $description  (Description)
 * @param string $interests (Tags, comma separated)
 * @param int $membership (Membership: 0 -> Closed, 2 -> Open)
 * @param int $vis (Visibility: 0 -> Inquiry members only, 1 -> logged in users, 2 -> Public)
 * @param string $arlearn_enable (Enable ARLearn for Data Collection: Yes/No)
 * @param string $multiple_admin_allow_enable (Allow multiple admins: Yes/No)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function inquiry_create(  $name, 
                          $description, 
                          $interests,
                          $membership,
                          $vis,
                          $provider,
                          $user_uid)
{ 

	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    elgg_register_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    $user = get_user_by_credentials($provider, $user_uid);
    
    if (!check_privileges($user)){
      throw new Exception("User has no privileges for creating a group");
    }

    elgg_trigger_plugin_hook('permissions_check', 'all', array('string' => 'NewString'), true);
    elgg_trigger_plugin_hook('container_permissions_check', 'all', array('string' => 'NewString'), true);

    $group = new ElggGroup();
    $group->name = $name;
    $group->description = $description;
    $group->interests = $interests;
    $group->owner_guid = $user->guid;
    $group->membership = $membership;
    $group->access_id = $vis;
    $group->group_acl = $group->access_id;
    
    $group_guid = $group->save();
    $group->join($user);
    add_to_river('river/group/create', 'create', $user->guid, $group->guid, $group->access_id);

    elgg_load_library('elgg:wespot_arlearnservices');
    set_input('group_guid',$group_guid);
	$success = initARLearnGame($group->guid, $group->name);
	if (!$success) {
	  echo "error Creating ARLearn";
	  register_error(elgg_echo('wespot_arlearn:error:no_save_game'));    
	} 
	
	elgg_load_library('tabbed_profile');
    $tool_options = elgg_get_config('group_tool_options');
    if ($tool_options)
		create_or_edit_all_tabs($group, $tool_options);

    elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    return $group_guid;

}

expose_function(
        "inquiry.create",
        "inquiry_create",
        array(
                        'name' => array ('type' => 'string'),
                        'description' => array ('type' => 'string'),
                        'interests' => array ('type' => 'string'),
                        'membership' => array ('type' => 'int'),
                        'vis' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Create an Inquiry',
        'POST',
        true,
        false
    );

/**
 * Add a Hypothesis through an API call.
 *
 * @param string $name (Inquiry name)
 * @param string $description  (Description)
 * @param string $tags (Tags same as $interests ininquiry.create function )
 * @param int $access_id (Read Access: 0 -> Private, 1 -> Logged In, 2 -> Public, xxx -> Access Collection ID)
 * @param int $write_access_id (Write Access: 0 -> Private, 1 -> logged in users, xxx -> Access Collection ID)
 * @param int $container_guid (Container GUID: i.e. Inquiry GUID)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function add_hypothesis(  $name,
                          $description,
                          $tags,
                          $access_id,
                          $write_access_id,
                          $container_guid,
                          $provider,
                          $user_uid)
{ 
    
	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    $user = get_user_by_credentials($provider, $user_uid);

    elgg_register_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    //---------------------------
    $variables = elgg_get_config('hypothesis');

    elgg_trigger_plugin_hook('permissions_check', 'all', array('string' => 'NewString'), true);
    elgg_trigger_plugin_hook('container_permissions_check', 'all', array('string' => 'NewString'), true);


    $hypothesis = new ElggObject();
    $hypothesis->subtype = 'hypothesis_top';
    $hypothesis->title = $name;
    $hypothesis->description = $description;
    $hypothesis->container_guid = $container_guid;
    $hypothesis->owner_guid = $user->guid;
    $hypothesis->access_id = $access_id;
    $save_outcome = $hypothesis->save();
    // echo "</br>". $save_outcome ."</br>";
    
    if(!$save_outcome) {
      return "Could not add hypothesis.";
    }
    
    $hypothesis->annotate('hypothesis', $hypothesis->description, $hypothesis->access_id, $hypothesis->owner_guid);
    //add_to_river('river/object/hypothesis/create', 'create', $user->guid, $hypothesis->guid);

    // echo "Now printing Hypothesis </br>";
    // print_r($hypothesis);    
    // exit();

    elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    return $save_outcome ;
}

expose_function(
        "add.hypothesis",
        "add_hypothesis",
        array(
                        'name' => array ('type' => 'string'),
                        'description' => array ('type' => 'string', 'required' => false),
                        'tags' => array ('type' => 'string', 'required' => false),
                        'access_id' => array ('type' => 'int', 'required' => false, 'default' => '2'),
                        'write_access_id' => array ('type' => 'int', 'required' => false, 'default' => '1'),
                        'container_guid' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Add Hypothesis to an Inquiry',
        'POST',
        true,
        false
    );

/**
 * Add a Question through an API call.
 *
 * @param string $title (Question title)
 * @param string $description  (Description)
 * @param string $tags (Tags same as $interests ininquiry.create function )
 * @param int $access_id (Read Access: 0 -> Private, 1 -> Logged In, 2 -> Public, xxx -> Access Collection ID)
 * @param int $write_access_id (Write Access: 0 -> Private, 1 -> logged in users, xxx -> Access Collection ID)
 * @param int $container_guid (Container GUID: i.e. Inquiry GUID)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function add_question(    $title,
                          $description,
                          $tags,
                          $access_id,
                          $write_access_id,
                          $container_guid,
                          $provider,
                          $user_uid)
{ 

	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    elgg_register_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    elgg_trigger_plugin_hook('permissions_check', 'all', array('string' => 'NewString'), true);
    elgg_trigger_plugin_hook('container_permissions_check', 'all', array('string' => 'NewString'), true);

    $user = get_user_by_credentials($provider, $user_uid);
    
    $question = new ElggObject();
    $question->subtype = 'question';
    $question->owner_guid = $user->guid;
    $question->access_id = $access_id;
    $question->title = $title;
    $question->description = $description;
    $question->tags = string_to_tag_array($tags);
    $question->container_guid = $container_guid;

    $save_outcome = $question->save();
    //add_to_river('river/object/question/create', 'create', $user->owner_guid, $question->guid);

    if(!$save_outcome) {
      return "Could not add a question.";
    }

    elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    return $save_outcome ;
}

expose_function(
        "add.question",
        "add_question",
        array(
                        'name' => array ('type' => 'string'),
                        'description' => array ('type' => 'string', 'required' => false),
                        'tags' => array ('type' => 'string', 'required' => false),
                        'access_id' => array ('type' => 'int', 'required' => false, 'default' => '2'),
                        'write_access_id' => array ('type' => 'int', 'required' => false, 'default' => '1'),
                        'container_guid' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Add Question to an Inquiry',
        'POST',
        true,
        false
    );


/**
 * Add an Answer through an API call.
 *
 * @param int $questionId (Question id)
 * @param string $description  (Description)
 * @param string $tags (Tags same as $interests ininquiry.create function )
 * @param int $access_id (Read Access: 0 -> Private, 1 -> Logged In, 2 -> Public, xxx -> Access Collection ID)
 * @param int $write_access_id (Write Access: 0 -> Private, 1 -> logged in users, xxx -> Access Collection ID)
 * @param int $container_guid (Container GUID: i.e. Inquiry GUID)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function add_answer(      $questionId,
                          $description,
                          $tags,
                          $access_id,
                          $write_access_id,
                          $container_guid,
                          $provider,
                          $user_uid)
{ 

	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    elgg_register_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    elgg_trigger_plugin_hook('permissions_check', 'all', array('string' => 'NewString'), true);
    elgg_trigger_plugin_hook('container_permissions_check', 'all', array('string' => 'NewString'), true);

    $user = get_user_by_credentials($provider, $user_uid);
    
    $answer = new ElggObject();
    $answer->subtype = 'answer';
    $answer->owner_guid = $user->guid;
    $answer->access_id = $access_id;
    $answer->description = $description;
    $answer->tags = string_to_tag_array($tags);
    $answer->container_guid = $container_guid;
    $answer->question_guid = $questionId;

    $save_outcome = $answer->save();

    if(!$save_outcome) {
      return "Could not add an answer.";
    }
    
    add_entity_relationship($questionId, "answer", $answer->getGUID());

    elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    return $save_outcome ;
}

expose_function(
        "add.answer",
        "add_answer",
        array(
                        'questionId' => array ('type' => 'int'),
                        'description' => array ('type' => 'string', 'required' => false),
                        'tags' => array ('type' => 'string', 'required' => false),
                        'access_id' => array ('type' => 'int', 'required' => false, 'default' => '2'),
                        'write_access_id' => array ('type' => 'int', 'required' => false, 'default' => '1'),
                        'container_guid' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Add Answer to an Inquiry',
        'POST',
        true,
        false
    );

    
/**
 * Add a Reflection through an API call.
 *
 * @param string $name (Reflection title)
 * @param string $description  (Description/Reflection)
 * @param string $tags (Tags same as $interests ininquiry.create function )
 * @param int $access_id (Read Access: 0 -> Private, 1 -> Logged In, 2 -> Public, xxx -> Access Collection ID)
 * @param int $write_access_id (Write Access: 0 -> Private, 1 -> logged in users, xxx -> Access Collection ID)
 * @param int $container_guid (Container GUID: i.e. Inquiry GUID)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function add_reflection(  $title,
                          $description,
                          $tags,
                          $access_id,
                          $write_access_id,
                          $container_guid,
                          $provider,
                          $user_uid)
{ 
    
	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    elgg_register_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    elgg_trigger_plugin_hook('permissions_check', 'all', array('string' => 'NewString'), true);
    elgg_trigger_plugin_hook('container_permissions_check', 'all', array('string' => 'NewString'), true);

    $user = get_user_by_credentials($provider, $user_uid);
    
    $reflection = new ElggObject();
    $reflection->subtype = 'reflection_top';
    $reflection->owner_guid = $user->guid;
    $reflection->access_id = $access_id;
    $reflection->title = $title;
    $reflection->description = $description;
    $reflection->tags = string_to_tag_array($tags);
    $reflection->container_guid = $container_guid;
    
 
    $save_outcome = $reflection->save();

    if(!$save_outcome) {
      return "Could not add a reflection.";
    }

    $reflection->annotate('reflection', $reflection->description, $reflection->access_id, $reflection->owner_guid);

    elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    return $save_outcome;
}

expose_function(
        "add.reflection",
        "add_reflection",
        array(
                        'name' => array ('type' => 'string'),
                        'description' => array ('type' => 'string', 'required' => false),
                        'tags' => array ('type' => 'string', 'required' => false),
                        'access_id' => array ('type' => 'int', 'required' => false, 'default' => '2'),
                        'write_access_id' => array ('type' => 'int', 'required' => false, 'default' => '1'),
                        'container_guid' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Add Reflection to an Inquiry',
        'POST',
        true,
        false
    );


/**
 * Add a Conclusion through an API call.
 *
 * @param string $title (Conclusion title)
 * @param string $description  (Description)
 * @param string $tags (Tags same as $interests ininquiry.create function )
 * @param int $access_id (Read Access: 0 -> Private, 1 -> Logged In, 2 -> Public, xxx -> Access Collection ID)
 * @param int $write_access_id (Write Access: 0 -> Private, 1 -> logged in users, xxx -> Access Collection ID)
 * @param int $container_guid (Container GUID: i.e. Inquiry GUID)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function add_conclusion(  $title,
                          $description,
                          $tags,
                          $access_id,
                          $write_access_id,
                          $container_guid,
                          $provider,
                          $user_uid)
{ 
    
    if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    elgg_register_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    elgg_trigger_plugin_hook('permissions_check', 'all', array('string' => 'NewString'), true);
    elgg_trigger_plugin_hook('container_permissions_check', 'all', array('string' => 'NewString'), true);

    $user = get_user_by_credentials($provider, $user_uid);
    
    $conclusion = new ElggObject();
    $conclusion->subtype = 'conclusions_top';
    $conclusion->owner_guid = $user->guid;
    $conclusion->access_id = $access_id;
    $conclusion->title = $title;
    $conclusion->description = $description;
    $conclusion->tags = string_to_tag_array($tags);
    $conclusion->container_guid = $container_guid;

    $save_outcome = $conclusion->save();

    if(!$save_outcome) {
      return "Could not add a conclusion.";
    }

    $conclusion->annotate('conclusions', $conclusion->description, $conclusion->access_id, $conclusion->owner_guid);

    elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    return $save_outcome ;
}

expose_function(
        "add.conclusion",
        "add_conclusion",
        array(
                        'name' => array ('type' => 'string'),
                        'description' => array ('type' => 'string', 'required' => false),
                        'tags' => array ('type' => 'string', 'required' => false),
                        'access_id' => array ('type' => 'int', 'required' => false, 'default' => '2'),
                        'write_access_id' => array ('type' => 'int', 'required' => false, 'default' => '1'),
                        'container_guid' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Add Conclusion to an Inquiry',
        'POST',
        true,
        false
    );

/**
 * Add a Note through an API call.
 *
 * @param string $title (Note title)
 * @param string $description  (Description)
 * @param string $tags (Tags same as $interests ininquiry.create function )
 * @param int $access_id (Read Access: 0 -> Private, 1 -> Logged In, 2 -> Public, xxx -> Access Collection ID)
 * @param int $write_access_id (Write Access: 0 -> Private, 1 -> logged in users, xxx -> Access Collection ID)
 * @param int $container_guid (Container GUID: i.e. Inquiry GUID)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function add_note(        $title,
                          $description,
                          $tags,
                          $access_id,
                          $write_access_id,
                          $container_guid,
                          $provider,
                          $user_uid)
{ 

	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");
    
    elgg_register_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    elgg_trigger_plugin_hook('permissions_check', 'all', array('string' => 'NewString'), true);
    elgg_trigger_plugin_hook('container_permissions_check', 'all', array('string' => 'NewString'), true);

    $user = get_user_by_credentials($provider, $user_uid);
    
    $note = new ElggObject();
    $note->subtype = 'notes_top';
    $note->owner_guid = $user->guid;
    $note->access_id = $access_id;
    $note->title = $title;
    $note->description = $description;
    $note->tags = string_to_tag_array($tags);
    $note->container_guid = $container_guid;

    $save_outcome = $note->save();

    if(!$save_outcome) {
      return "Could not add a note.";
    }

    $note->annotate('notes', $note->description, $note->access_id, $note->owner_guid);

    elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    return $save_outcome ;
}

expose_function(
        "add.note",
        "add_note",
        array(
                        'name' => array ('type' => 'string'),
                        'description' => array ('type' => 'string', 'required' => false),
                        'tags' => array ('type' => 'string', 'required' => false),
                        'access_id' => array ('type' => 'int', 'required' => false, 'default' => '2'),
                        'write_access_id' => array ('type' => 'int', 'required' => false, 'default' => '1'),
                        'container_guid' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Add Note to an Inquiry',
        'POST',
        true,
        false
    );

/**
 * Add a Page through an API call.
 *
 * @param string $title (Page title)
 * @param string $description  (Description)
 * @param string $tags (Tags same as $interests ininquiry.create function )
 * @param int $access_id (Read Access: 0 -> Private, 1 -> Logged In, 2 -> Public, xxx -> Access Collection ID)
 * @param int $write_access_id (Write Access: 0 -> Private, 1 -> logged in users, xxx -> Access Collection ID)
 * @param int $container_guid (Container GUID: i.e. Inquiry GUID)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function add_page(        $title,
                          $description,
                          $tags,
                          $access_id,
                          $write_access_id,
                          $container_guid,
                          $provider,
                          $user_uid)
{ 

	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");
    
    elgg_register_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    elgg_trigger_plugin_hook('permissions_check', 'all', array('string' => 'NewString'), true);
    elgg_trigger_plugin_hook('container_permissions_check', 'all', array('string' => 'NewString'), true);

    $user = get_user_by_credentials($provider, $user_uid);
    
    $page = new ElggObject();
    $page->subtype = 'page_top';
    $page->owner_guid = $user->guid;
    $page->access_id = $access_id;
    $page->title = $title;
    $page->description = $description;
    $page->tags = string_to_tag_array($tags);
    $page->container_guid = $container_guid;

    $save_outcome = $page->save();

    if(!$save_outcome) {
      return "Could not add a page.";
    }

    $page->annotate('page', $page->description, $page->access_id, $page->owner_guid);

    elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    return $save_outcome;
}

expose_function(
        "add.page",
        "add_page",
        array(
                        'name' => array ('type' => 'string'),
                        'description' => array ('type' => 'string', 'required' => false),
                        'tags' => array ('type' => 'string', 'required' => false),
                        'access_id' => array ('type' => 'int', 'required' => false, 'default' => '2'),
                        'write_access_id' => array ('type' => 'int', 'required' => false, 'default' => '1'),
                        'container_guid' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Add Page to an Inquiry',
        'POST',
        true,
        false
    );

/**
 * Add a Blog through an API call.
 *
 * @param string $name (Blog title)
 * @param string $description  (Description)
 * @param string $tags (Tags same as $interests ininquiry.create function )
 * @param int $access_id (Read Access: 0 -> Private, 1 -> Logged In, 2 -> Public, xxx -> Access Collection ID)
 * @param int $write_access_id (Write Access: 0 -> Private, 1 -> logged in users, xxx -> Access Collection ID)
 * @param int $container_guid (Container GUID: i.e. Inquiry GUID)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function add_blog(        $title,
                          $description,
                          $tags,
                          $access_id,
                          $write_access_id,
                          $container_guid,
                          $provider,
                          $user_uid)
{ 
    
	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    elgg_register_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    elgg_trigger_plugin_hook('permissions_check', 'all', array('string' => 'NewString'), true);
    elgg_trigger_plugin_hook('container_permissions_check', 'all', array('string' => 'NewString'), true);

    $user = get_user_by_credentials($provider, $user_uid);
    
    $blog = new ElggObject();
    $blog->subtype = 'blog';
    $blog->owner_guid = $user->guid;
    $blog->access_id = $access_id;
    $blog->title = $title;
    $blog->description = $description;
    $blog->tags = string_to_tag_array($tags);
    $blog->container_guid = $container_guid;
    
    $save_outcome = $blog->save();

    if(!$save_outcome) {
      return "Could not add a blog.";
    }

    $blog->annotate('blog', $discussion->description, $discussion->access_id, $discussion->owner_guid);

    elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    return $save_outcome;
}

expose_function(
        "add.blog",
        "add_blog",
        array(
                        'name' => array ('type' => 'string'),
                        'description' => array ('type' => 'string', 'required' => false),
                        'tags' => array ('type' => 'string', 'required' => false),
                        'access_id' => array ('type' => 'int', 'required' => false, 'default' => '2'),
                        'write_access_id' => array ('type' => 'int', 'required' => false, 'default' => '1'),
                        'container_guid' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Add Blog to an Inquiry',
        'POST',
        true,
        false
    );
    
/**
 * Add a Discussion through an API call.
 *
 * @param string $name (Discussion title)
 * @param string $description  (Description)
 * @param string $tags (Tags same as $interests ininquiry.create function )
 * @param int $access_id (Read Access: 0 -> Private, 1 -> Logged In, 2 -> Public, xxx -> Access Collection ID)
 * @param int $write_access_id (Write Access: 0 -> Private, 1 -> logged in users, xxx -> Access Collection ID)
 * @param int $container_guid (Container GUID: i.e. Inquiry GUID)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function add_discussion(  $title,
                          $description,
                          $tags,
                          $access_id,
                          $write_access_id,
                          $container_guid,
                          $provider,
                          $user_uid)
{ 
    
	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    elgg_register_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    elgg_trigger_plugin_hook('permissions_check', 'all', array('string' => 'NewString'), true);
    elgg_trigger_plugin_hook('container_permissions_check', 'all', array('string' => 'NewString'), true);

    $user = get_user_by_credentials($provider, $user_uid);
    
    $discussion = new ElggObject();
    $discussion->subtype = 'groupforumtopic';
    $discussion->owner_guid = $user->guid;
    $discussion->access_id = $access_id;
    $discussion->title = $title;
    $discussion->description = $description;
    $discussion->tags = string_to_tag_array($tags);
    $discussion->container_guid = $container_guid;
    
    $save_outcome = $discussion->save();

    if(!$save_outcome) {
      return "Could not add a discussion.";
    }

    $discussion->annotate('groupforumtopic', $discussion->description, $discussion->access_id, $discussion->owner_guid);

    elgg_unregister_plugin_hook_handler('permissions_check', 'object', 'somefunction_ovveride_permissions');
    elgg_unregister_plugin_hook_handler('container_permissions_check', 'object', 'somefunction_ovveride_permissions');

    return $save_outcome;
}

expose_function(
        "add.discussion",
        "add_discussion",
        array(
                        'name' => array ('type' => 'string'),
                        'description' => array ('type' => 'string', 'required' => false),
                        'tags' => array ('type' => 'string', 'required' => false),
                        'access_id' => array ('type' => 'int', 'required' => false, 'default' => '2'),
                        'write_access_id' => array ('type' => 'int', 'required' => false, 'default' => '1'),
                        'container_guid' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Add Discussion to an Inquiry',
        'POST',
        true,
        false
    );
    

/**
 * Edit an object through an API call.
 *
 * @param int $objectId (Object id)
 * @param string $title (Object title)
 * @param string $description (Description)
 * @param string $tags (Tags same as $interests ininquiry.create function )
 * @param int $access_id (Read Access: 0 -> Private, 1 -> Logged In, 2 -> Public, xxx -> Access Collection ID)
 * @param int $write_access_id (Write Access: 0 -> Private, 1 -> logged in users, xxx -> Access Collection ID)
 * @param int $container_guid (Container GUID: i.e. Inquiry GUID)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return int guid
 * @access public
 */
function edit_object(     $objectId,
						  $title,
                          $description,
                          $tags,
                          $access_id,
                          $write_access_id,
                          $container_guid,
                          $provider,
                          $user_uid)
{ 

	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    $object = get_entity($objectId);
    if(!$object)
    	throw new Exception("Object with identifier {$objectId} not found");

    $user = get_user_by_credentials($provider, $user_uid);
    
    if($object->canEdit($user->getGUID())){
    	elgg_set_ignore_access(true);
		$object->access_id = $access_id;
		$object->title = $title;
		$object->description = $description;
		$object->tags = string_to_tag_array($tags);
		$object->container_guid = $container_guid;
		$save_outcome = $object->save();
    	elgg_set_ignore_access(false);
    }
    else throw new Exception("The user does not have permission to edit this object");
    
    if(!$save_outcome) {
      return "Could not edit object.";
    }    
    
    return $save_outcome;
    
}

expose_function(
        "edit.object",
        "edit_object",
        array(
                        'objectId' => array ('type' => 'int'),
                        'name' => array ('type' => 'string'),
                        'description' => array ('type' => 'string', 'required' => false),
                        'tags' => array ('type' => 'string', 'required' => false),
                        'access_id' => array ('type' => 'int', 'required' => false, 'default' => '2'),
                        'write_access_id' => array ('type' => 'int', 'required' => false, 'default' => '1'),
                        'container_guid' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Edit an object',
        'POST',
        true,
        false
    );
    

/**
 * Remove an object through an API call.
 *
 * @param string $objectId (Object id)
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return string message
 * @access public
 */
function remove_object(   $objectId,
                          $provider,
                          $user_uid)
{ 

 	if(!is_allowed_domain())
 		throw new Exception("Your domain is not authorised to call this service");
    
    $object = get_entity($objectId);
    if(!$object)
    	throw new Exception("Object with identifier {$objectId} not found");

    $user = get_user_by_credentials($provider, $user_uid);
    
    if($object->canEdit($user->getGUID())){
    	elgg_set_ignore_access(true);
    	$object->delete();
    	elgg_set_ignore_access(false);
    }
    else throw new Exception("The user does not have permission to remove this object");
    
    return "Object successfully removed";

}

expose_function(
        "remove.object",
        "remove_object",
        array(
                        'objectId' => array ('type' => 'int'),
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string')
        ),
        'Remove an object',
        'POST',
        true,
        false
    );


function check_privileges($user){
    $admin = $user;
    login($admin);
    return true;
}

function get_user_by_credentials($provider, $token) {
	global $CONFIG;
	$identifier = strtolower($provider);
	foreach (array("LinkedIn", "MySpace", "AOL") as $prov) {
		$identifier = str_replace(strtolower($prov), $prov, $identifier);
	}
	$identifier = ucfirst($identifier) . "_" . $token;
	$query = "SELECT entity_guid from {$CONFIG->dbprefix}private_settings where name = 'plugin:user_setting:elgg_social_login:uid' and value = '{$identifier}'";
	$guid = get_data_row($query)->entity_guid;
	if($guid) 
		return get_entity($guid); 
	else 
		throw new Exception("User with OAuth identifier {$identifier} not found");
}


///////////////////////////////////
// Friendship management services
///////////////////////////////////

/**
 * Adds a friend or approve a friend request.
 *
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 * @param string $friend_provider (Friend's provider of the login: e.g. Google)
 * @param string $friend_user_uid (Friend's user ID)
 *
 * @return string message
 * @access public
 */
function add_friend(      $provider,
                          $user_uid,
                          $friend_provider,
                          $friend_user_uid)
{ 

	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    $user = get_user_by_credentials($provider, $user_uid);
    $friend = get_user_by_credentials($friend_provider, $friend_user_uid);
      
	//Now we need to attempt to create the relationship
	if(empty($user) || empty($friend)) {
		$errors = true;
		throw new Exception(elgg_echo("friend_request:add:failure"));
	} else {
		//New for v1.1 - If the other user is already a friend (fan) of this user we should auto-approve the friend request...
		if(check_entity_relationship($friend->getGUID(), "friend", $user->getGUID())) {
			try {
				if(isset($CONFIG->events["create"]["friend"])) {
					$oldEventHander = $CONFIG->events["create"]["friend"];
					$CONFIG->events["create"]["friend"] = array();			//Removes any event handlers
				}
				
				$user->addFriend($friend->getGUID());
				$result = elgg_echo("friends:add:successful", array($friend->name));
				
				if(isset($CONFIG->events["create"]["friend"])) {
					$CONFIG->events["create"]["friend"] = $oldEventHander;
				}
				
				return $result;
			} catch (Exception $e) {
				throw new Exception(elgg_echo("friends:add:failure", array($friend->name)));
				$errors = true;
			}
		} elseif(check_entity_relationship($friend->getGUID(), "friendrequest", $user->getGUID())){
			// Check if your potential friend already invited you, if so make friends
			if(remove_entity_relationship($friend->getGUID(), "friendrequest", $user->getGUID())){
				if(isset($CONFIG->events["create"]["friend"])) {
					$oldEventHander = $CONFIG->events["create"]["friend"];
					$CONFIG->events["create"]["friend"] = array();			//Removes any event handlers
				}
				
				$user->addFriend($friend->getGUID());
				$friend->addFriend($user->getGUID());			//Friends mean reciprical...
				
				if(isset($CONFIG->events["create"]["friend"])) {
					$CONFIG->events["create"]["friend"] = $oldEventHander;
				}
				
				$result = elgg_echo("friend_request:approve:successful", array($friend->name));
				// add to river
				add_to_river("river/relationship/friend/create", "friend", $user->getGUID(), $friend->getGUID());
				add_to_river("river/relationship/friend/create", "friend", $friend->getGUID(), $user->getGUID());
				
				return $result;
			} else {
				throw new Exception(elgg_echo("friend_request:approve:fail", array($friend->name)));
			}
		} else {
			try {
				$result = add_entity_relationship($user->getGUID(), "friendrequest", $friend->getGUID());
				if($result == false) {
					$errors = true;
					throw new Exception(elgg_echo("friend_request:add:exists", array($friend->name)));
				}
			} catch(Exception $e) {	//register_error calls insert_data which CAN raise Exceptions.
				$errors = true;
				throw new Exception(elgg_echo("friend_request:add:exists", array($friend->name)));
			}
		}
	}
	
	if(!$errors) 
		return elgg_echo("friend_request:add:successful", array($friend->name));

}

expose_function(
        "add.friend",
        "add_friend",
        array(
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string'),
                        'friend_provider' => array ('type' => 'string'),
                        'friend_user_uid' => array ('type' => 'string'),
        ),
        'Add a friend or approve a friend request.',
        'POST',
        true,
        false
    );

/**
 * Removes a friend or revokes/declines a friend request.
 *
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 * @param string $friend_provider (Friend's provider of the login: e.g. Google)
 * @param string $friend_user_uid (Friend's user ID)
 *
 * @return string message
 * @access public
 */
function remove_friend(   $provider,
                          $user_uid,
                          $friend_provider,
                          $friend_user_uid)
{ 

	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

    $user = get_user_by_credentials($provider, $user_uid);    
    $friend = get_user_by_credentials($friend_provider, $friend_user_uid);
 
 	// decline a friend request
 	if(remove_entity_relationship($friend->getGUID(), "friendrequest", $user->getGUID())) {
		$subject = elgg_echo("friend_request:decline:subject", array($user->name));
		$message = elgg_echo("friend_request:decline:message", array($friend->name, $user->name));
		notify_user($friend->getGUID(), $user->getGUID(), $subject, $message);
		
		return elgg_echo("friend_request:decline:success");
	}
	
	// revoke a friend request
	if(remove_entity_relationship($user->getGUID(), "friendrequest", $friend->getGUID()))
		return elgg_echo("friend_request:revoke:success");
     
	try{
		$user->removeFriend($friend->getGUID());
		
		// remove river items
		elgg_delete_river(array(
			"view" => "river/relationship/friend/create",
			"subject_guid" => $user->getGUID(),
			"object_guid" => $friend->getGUID()
		));
		
		try {	
			//V1.1 - Old relationships might not have the 2 as friends...
			$friend->removeFriend($user->getGUID());
			
			// remove river items
			elgg_delete_river(array(
				"view" => "river/relationship/friend/create",
				"subject_guid" => $friend->getGUID(),
				"object_guid" => $user->getGUID()
			));
		} catch(Exception $e) {
			throw new Exception(elgg_echo("friends:remove:failure", array($friend->name)));
			$errors = true;
		}
	} catch (Exception $e) {
		throw new Exception(elgg_echo("friends:remove:failure", array($friend->name)));
		$errors = true;
	}
	
	if(!$errors) 
		return elgg_echo("friends:remove:successful", array($friend->name));		

}

expose_function(
        "remove.friend",
        "remove_friend",
        array(
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string'),
                        'friend_provider' => array ('type' => 'string'),
                        'friend_user_uid' => array ('type' => 'string'),
        ),
        'Remove a friend or revoke/decline a friend request.',
        'POST',
        true,
        false
    );

/**
 * Gets received friend requests.
 *
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return array received requests
 * @access public
 */
function received_friendrequests(   $provider,
                          			$user_uid)
{ 

    $user = get_user_by_credentials($provider, $user_uid);  
      
	$options = array(
		"type" => "user",
		"limit" => false,
		"relationship" => "friendrequest",
		"relationship_guid" => $user->getGUID(),
		"inverse_relationship" => true
	);
	
	// Get all received requests
	$received_requests = elgg_get_entities_from_relationship($options);

    $return = array();
    if ($received_requests) {
        foreach ($received_requests as $received_request){
            $guid = $received_request->getGUID();
			$provider = elgg_get_plugin_user_setting('provider', $guid, 'elgg_social_login');
			$uid = str_replace("{$provider}_", '', elgg_get_plugin_user_setting('uid', $guid, 'elgg_social_login'));
			$return[] = array('oauthId'=>$uid, 'oauthProvider'=>$provider, 'name'=>$received_request->name, 'icon'=>$received_request->getIcon());
        }
    }
    return $return;
}

expose_function(
        "received.friendrequests",
        "received_friendrequests",
        array(
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string'),
        ),
        'Get received friend requests.',
        'GET',
        true,
        false
    );

/**
 * Get sent friend requests.
 *
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 *
 * @return array sent requests
 * @access public
 */
function sent_friendrequests(   $provider,
                          		$user_uid)
{ 

    $user = get_user_by_credentials($provider, $user_uid);  
      
	$options = array(
		"type" => "user",
		"limit" => false,
		"relationship" => "friendrequest",
		"relationship_guid" => $user->getGUID(),
		"inverse_relationship" => false
	);
	
	// Get all sent requests
	$sent_requests = elgg_get_entities_from_relationship($options);

    $return = array();
    if ($sent_requests) {
        foreach ($sent_requests as $sent_request){
            $guid = $sent_request->getGUID();
			$provider = elgg_get_plugin_user_setting('provider', $guid, 'elgg_social_login');
			$uid = str_replace("{$provider}_", '', elgg_get_plugin_user_setting('uid', $guid, 'elgg_social_login'));
			$return[] = array('oauthId'=>$uid, 'oauthProvider'=>$provider, 'name'=>$sent_request->name, 'icon'=>$sent_request->getIcon());
        }
    }
    return $return;
}

expose_function(
        "sent.friendrequests",
        "sent_friendrequests",
        array(
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string'),
        ),
        'Get sent friend requests.',
        'GET',
        true,
        false
    );

/**
 * Creates a new user profile.
 *
 * @param string $provider (Provider of the login: e.g. Google)
 * @param string $user_uid (User ID)
 * @param string $first_name (User's first name)
 * @param string $last_name (User's last name)
 * @param string $email (User's email)
 *
 * @return string message
 * @access public
 */
function add_user(      $provider,
                        $user_uid,
                        $first_name,
                        $last_name,
                        $email)
{ 

	if(!is_allowed_domain())
		throw new Exception("Your domain is not authorised to call this service");

	$user_uid = $provider . "_" . $user_uid;

	// attempt to find the user 
	$options = array(
		'type' => 'user',
		'plugin_id' => 'elgg_social_login',
		'plugin_user_setting_name_value_pairs' => array(
			'uid' => $user_uid,
			'provider' => $provider,
		),
		'plugin_user_setting_name_value_pairs_operator' => 'AND',
		'limit' => 0
	);
	
	$users = elgg_get_entities_from_plugin_user_settings($options);
	
	if ( $users ) 
		throw new Exception('A user profile already exists for the ' . $user_uid . ' account.');

	$userlogin = $first_name . $last_name;

	if ( ! $userlogin ){
		$userlogin = $provider . '_user_' . rand( 1000, 9999 );
	}

	while ( get_user_by_username( $userlogin ) ){
		$userlogin = $first_name . $last_name . '_' . rand( 1000, 9999 );
	}

	$password = generate_random_cleartext_password();
	
	$user = new ElggUser();
	$user->username = $userlogin;
	$user->name = $first_name . ' ' . $last_name;
	$user->access_id = ACCESS_PUBLIC;
	$user->salt = generate_random_cleartext_password();
	$user->password = generate_user_password($user, $password);
	$user->email = $email;
	$user->owner_guid = 0;
	$user->container_guid = 0;

	if ( ! $user->save() )
		throw new Exception( elgg_echo('registerbad') ); 

	// register user && provider
	elgg_set_plugin_user_setting( 'uid', $user_uid, $user->guid, 'elgg_social_login' ); 
	elgg_set_plugin_user_setting( 'provider', $provider, $user->guid, 'elgg_social_login' ); 
	create_metadata( $user->guid, "contactemail", html_entity_decode( $email, ENT_COMPAT, 'UTF-8'), "text", $user->guid, 1 );

	return elgg_echo('A new user profile has been created for the ' . $user_uid . ' account.');

}

expose_function(
        "add.user",
        "add_user",
        array(
                        'provider' => array ('type' => 'string'),
                        'user_uid' => array ('type' => 'string'),
                        'first_name' => array ('type' => 'string'),
                        'last_name' => array ('type' => 'string'),
                        'email' => array ('type' => 'string'),
        ),
        'Creates a new user profile.',
        'POST',
        true,
        false
    );

?>
