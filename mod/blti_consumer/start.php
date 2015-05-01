<?php
/**
 * @name Plugin  Basic LTI Consumer
 * @abstract This plugin allows Elgg to launch Basic LTI Tools
 * @author Antoni Bertran (antoni@tresipunt.com)
 * @copyright 2011 Universitat Oberta de Catalunya
 * @license GPL
 * @version 1.0.0
 * @package ElggBLTI
 * Date April 2011
*/

elgg_register_event_handler('init', 'system', 'blti_consumer_init');

global $CONFIG;

function blti_consumer_init(){
	global $CONFIG;
	elgg_register_event_handler('pagesetup','system','blti_consumer_pagesetup');
	//	blti_consumer_launch();
	elgg_register_plugin_hook_handler('plugin:setting', 'all', 'blti_consumer_plugin_setting');
  	// page handler
	elgg_register_page_handler('blti_consumer', 'blti_consumer_page_handler');
	//register admin page handler
	elgg_register_page_handler('blti_consumer', 'blti_consumer_page_handler_admin');
  	// add our menu pieces
	elgg_register_menu_item('site', array('name' => elgg_echo('blti_consumer:menu'), 'text' => elgg_echo('blti_consumer:menu'),
			'href' => $CONFIG->wwwroot . 'blti_consumer/listing',));
	
	$action_path = elgg_get_plugins_path() . 'blti_consumer/actions/blti_consumer';
	elgg_register_action('blti_consumer/register', "$action_path/register.php");
	
	elgg_register_action('blti_consumer/unregister', $action_path . '/unregister.php', 'admin');
	elgg_register_action("blti_consumer/save",  $action_path . "/save.php", 'admin');

	//add a widget
	elgg_register_widget_type('blti_consumer', elgg_echo('blti_consumer:menu'), elgg_echo('menu:page:header:blti_consumer'), "all,groups");
	
	// allow plugin authors to hook into this service
//	register_plugin_hook('tweet', 'twitter_service', 'twitterservice_tweet');
}

//
//function blti_consumer_plugin_setting($hook, $entity_type, $returnvalue, $params) {
//	global $CONFIG;
//
//	$plugin = $params['plugin'];
//	$name = $params['name'];
//	$value = $params['value'];
//
//	if ($plugin == 'blti_consumer' && ($name == 'oauthKey' || $name == 'oauthSecret')) {
//		// have to check for name here --^ , otherwise we get
//		// into a bad loop ... 
//
//		$consumEnt = blti_consumer_oauth_consumer_entity();
//
//		if ($name == 'oauthKey' && $value) {
//			$consumEnt->key = $value;
//		} else if ($name == 'oauthSecret' && $value) {
//			$consumEnt->secret = $value;
//		}
//	}
//	return $returnvalue;
//}

//function blti_consumer_oauth_consumer_entity() {
//	$consGuid = get_plugin_setting('oauthConsumer', 'blti_consumer');
//
//	$consumEnt = NULL;
//
//	if ($consGuid) {
//		$consumEnt = get_entity($consGuid);
//	}
//
//	if ($consumEnt == NULL) {
//		// don't have a consumer entity yet
//		$consumEnt = oauth_create_consumer('My Plugin', 'My Plugin OAuth consumer', NULL, NULL, TRUE); // no key-secret yet, is revision a
//		set_plugin_setting('oauthConsumer', $consumEnt->getGUID(), 'blti_consumer');
//	}
//
//	return $consumEnt;
//}

function blti_consumer_launch($consumer) {
	
	if (!class_exists("bltiUocWrapper")) {
		require_once dirname(__FILE__).'/IMSBasicLTI/uoc-blti/bltiUocWrapper.php';
		require_once dirname(__FILE__).'/IMSBasicLTI/ims-blti/blti_util.php';
		require_once dirname(__FILE__).'/IMSBasicLTI/utils/UtilsPropertiesBLTI.php';
	}
	require_once dirname(__FILE__).'/constants.php';
	$user = elgg_get_logged_in_user_entity();
	global $CONFIG;

//    $context = get_context_instance(CONTEXT_COURSE, $instance->course);
    $role = $user->isAdmin()?'Instructor':'Student';
    
    $locale = isset($user->lang) && strlen($user->lang)>0?$user->lang:$CONFIG->language;

    $instance = get_entity($user->site_guid);
    $site = $CONFIG->site;
    $requestparams = array(
        BasicLTIConstants::RESOURCE_LINK_ID => $consumer->getGUID(),
        BasicLTIConstants::RESOURCE_LINK_TITLE => $consumer->name,
        BasicLTIConstants::RESOURCE_LINK_DESCRIPTION => $consumer->description,
        BasicLTIConstants::USER_ID => $user->getGUID(),
        BasicLTIConstants::ROLES => $role,
        BasicLTIConstants::CONTEXT_ID => $site->getGUID(),
        BasicLTIConstants::CONTEXT_LABEL => $site->name,
        BasicLTIConstants::CONTEXT_TITLE => $site->description,
        BasicLTIConstants::LAUNCH_PRESENTATION_LOCALE => $locale,
    );
//
//    $placementsecret = $instance->placementsecret;
//    if ( isset($placementsecret) ) {
//        $suffix = ':::' . $user->id . ':::' . $instance->id;
//        $plaintext = $placementsecret . $suffix;
//        $hashsig = hash('sha256', $plaintext, false);
//        $sourcedid = $hashsig . $suffix;
//    } 
     
//    if ( isset($placementsecret) && 
//         ( $typeconfig['acceptgrades'] == 1 ||
//         ( $typeconfig['acceptgrades'] == 2 && $instance->instructorchoiceacceptgrades == 1 ) ) ) {
//        $requestparams[BasicLTIConstants::LIS_RESULT_SOURCEDID] = $sourcedid;
//        $requestparams[BasicLTIConstants::EXT_IMS_LIS_BASIC_OUTCOME_URL] = $CONFIG->wwwroot.'/mod/basiclti/service.php';
//    }
//
//    if ( isset($placementsecret) && 
//         ( $typeconfig['allowroster'] == 1 ||
//         ( $typeconfig['allowroster'] == 2 && $instance->instructorchoiceallowroster == 1 ) ) ) {
//        $requestparams[BasicLTIConstants::EXT_IMS_LIS_MEMBERSHIPS_ID] = $sourcedid;
//        $requestparams[BasicLTIConstants::EXT_IMS_LIS_MEMBERSHIPS_URL] = $CONFIG->wwwroot.'/mod/basiclti/service.php';
//    }
//
//    if ( isset($placementsecret) && 
//         ( $typeconfig['allowsetting'] == 1 ||
//         ( $typeconfig['allowsetting'] == 2 && $instance->instructorchoiceallowsetting == 1 ) ) ) {
//        $requestparams[BasicLTIConstants::EXT_IMS_LIS_MEMBERSHIPS_ID] = $sourcedid;
//        $requestparams[BasicLTIConstants::EXT_IMS_LIS_MEMBERSHIPS_URL] = $CONFIG->wwwroot.'/mod/basiclti/service.php';
//        $setting = $instance->setting;
//        if ( isset($setting) ) { 
//             $requestparams[BasicLTIConstants::EXT_IMS_LTI_TOOL_SETTING] = $setting;
//        }
//    }
    
    // Send user's name and email data if appropriate
    if ( $consumer->sendname == 1 ||
         ( $consumer->sendname == 2 /*&& $instance->instructorchoicesendname == 1*/ ) ) {
         	$fullname = $user->name;
         	$name = '';
         	$lastname = '';
         	if ($p=strpos($fullname, ' ')) {
         		$name = substr($fullname, 0, $p);
         		$lastname = substr($fullname, $p+1);
         	} else {
         		$name = $fullname;
         	}
	        $requestparams[BasicLTIConstants::LIS_PERSON_NAME_GIVEN] =  $name;
	        $requestparams[BasicLTIConstants::LIS_PERSON_NAME_FAMILY] =  $lastname;
	        $requestparams[BasicLTIConstants::LIS_PERSON_NAME_FULL] =  $fullname;
    }

    if ( $consumer->sendemail  == 1 ||
         ( $consumer->sendemail == 2 /*&& $instance->instructorchoicesendemailaddr == 1 */) ) {
        $requestparams[BasicLTIConstants::LIS_PERSON_CONTACT_EMAIL_PRIMARY] =  $user->email;
    }

    $customstr = $consumer->custom_params;
    if ( $customstr ) {
        $custom = blti_consumer_split_custom_parameters($customstr);
        $requestparams = array_merge($custom, $requestparams);
    }
    
    $requestparams[BasicLTIConstants::LIS_PERSON_SOURCEDID] = elgg_get_plugin_setting('resourceKey', 'blti_consumer').':'.$user->username;
    //Adding all profile details canges of Antoni Bertran antoni@tresipunt.com
	if ( $consumer->sendprofile==1 ||
         ( $consumer->sendprofile == 2/* && $instance->instructorchoicesendprofiledet == 1 */) ) {
         	//Pass the picture if exists
         	$requestparams[BasicLTIConstants::USER_IMAGE] = $CONFIG->wwwroot.'/mod/profile/icondirect.php?guid='.$user->getGUID();
         	$requestparams['custom_username'] = $user->username;
//         	require_once($CFG->dirroot.'/tag/lib.php');
         	$profile_fields = elgg_get_metadata_from_id($user->getGUID()); 
         	foreach($profile_fields as $key => $field) {
        		$requestparams['custom_'.$key] = $user->$field;
         	}
    }
    
    $org_id = elgg_get_plugin_setting('organizationid', 'blti_consumer');
    $endpoint = $consumer->toolurl;
    $key = $consumer->key;
    $secret = $consumer->secret;
    $debug = $consumer->debug;
    $submit_text = elgg_echo('blti_consumer:submit');
    $height = $consumer->preferheight;
    $launch = $consumer->launch;
    $callbackUrl = $consumer->callbackUrl;
    return blti_consumer_sign_parameters($requestparams, $endpoint, $key, $secret, $submit_text, $org_id, $debug, $callbackUrl, $launch, $height);

}

function blti_consumer_sign_parameters($requestparams, $endpoint, $key, $secret, $submit_text, $org_id, $debuglaunch=false, $callbackUrl, $launch=2, $height) {
    // Make sure we let the tool know what LMS they are being called from
    $requestparams["ext_lms"] = "elgg";

    $makeiframe = $launch==1;
    // Add oauth_callback to be compliant with the 1.0A spec
    if (!isset($callbackUrl))
    	$requestparams["oauth_callback"] = "about:blank";
	else
		$requestparams["oauth_callback"] = $callbackUrl;
    
	$parms = signParameters($requestparams, $endpoint, "POST", $key, $secret, $submit_text, $org_id /*, $org_desc*/);

    if ( $makeiframe ) {
        $height = $instance->preferheight;
        if ( ! $height ) $height = "1200";
        $content = postLaunchHTML($parms, $endpoint, $debuglaunch,
            "width=\"100%\" height=\"".$height."\" scrolling=\"auto\" frameborder=\"1\" transparency");
    } else {
        $content = postLaunchHTML($parms, $endpoint, $debuglaunch, false);
        if ($launch == 2) { //popup 
        	$content= str_replace('name="ltiLaunchForm" id="ltiLaunchForm"','name="ltiLaunchForm" id="ltiLaunchForm" target="_blank"',$content);
        }
    }
    return $content;
}


function blti_consumer_split_custom_parameters($customstr) {
    $lines = preg_split("/[\n;]/",$customstr);
    $retval = array();
    foreach ($lines as $line){
        $pos = strpos($line,"=");
        if ( $pos === false || $pos < 1 ) continue;
        $key = trim(substr($line, 0, $pos));
        $val = trim(substr($line, $pos+1));
        $key = blti_consumer_map_keyname($key);
        $retval['custom_'.$key] = $val;
    }
    return $retval;
}

function blti_consumer_map_keyname($key) {
    $newkey = "";
    $key = strtolower(trim($key));
    foreach (str_split($key) as $ch) {
        if ( ($ch >= 'a' && $ch <= 'z') || ($ch >= '0' && $ch <= '9') ) {
            $newkey .= $ch;
        } else {
            $newkey .= '_';
        }
    }
    return $newkey;
}

// page setup
function blti_consumer_pagesetup() {
	global $CONFIG;
	// add our page menus as needed
	if (elgg_get_context() == 'admin' && elgg_is_admin_logged_in()) {
		//elgg_register_admin_menu_item('blti_consumer');
	/*	elgg_register_admin_menu_item('blti_consumer', 'manage_tools_blti');
		
		elgg_register_menu_item('page', array(
					'name' => 'blti_consumer_settings',
					'href' => 'admin/blti_consumer/settings',
					'text' => elgg_echo('settings'),
					'context' => 'admin',
					'priority' => 10,
					'section' => 'blti_consumer'
		));
		*/
		//elgg_register_admin_menu_item(
		//elgg_register_admin_menu_item('blti_consumer', 'manage_tools_blti');
		elgg_register_menu_item('page', array(
					'name' => elgg_echo('blti_consumer_tools:manage_tools_blti'),
					'text' => elgg_echo('blti_consumer_tools:manage_tools_blti'),
					'href' => "blti_consumer/manage_tools_blti",
					'title' => elgg_echo('blti_consumer_tools:manage_tools_blti'),
					'context' => 'admin',
					'rel' => 'nofollow',
					'section' => 'blti_consumer'
		));
	}
}


// create a consumer object with the given properties and return the resulting (saved) entity
function blti_consumer_create_consumer($guid, $name, $desc, $key, $secret, $toolurl, $callbackUrl, 
					$preferheight, $sendname=1, $sendemail=1, $sendprofile=1, $launch=1, $debug=0, $custom_params) {
	global $CONFIG;

	if ($guid>0) {
		$consumEnt = get_entity($guid);
		if (!($consumEnt && $consumEnt->canEdit() && $name && $desc && $key && $secret)) {
			if (!$consumEnt || !$consumEnt->canEdit())
				register_error('Consumer '.$guid.' not found or you can not edit it!!!');
			if (!($name && $desc && $key && $secret))
				register_error('You must fill out both the name, key, secret and description fields.');
			return false;
		} 
	} else {
		$consumEnt = new ElggObject();
		$consumEnt->subtype = 'blti_consumer';
		$consumEnt->access_id = ACCESS_PUBLIC; // this feels wrong, but it's the only way to make it accessible to multiple users
	}
	$consumEnt->name = $name;
	$consumEnt->description = $desc;
	$consumEnt->key = $key;
	$consumEnt->secret = $secret;
	$consumEnt->callbackUrl = $callbackUrl;
	$consumEnt->toolurl = $toolurl;
	$consumEnt->preferheight = $preferheight;
	$consumEnt->sendname = $sendname;
	$consumEnt->sendemail = $sendemail;
	$consumEnt->sendprofile = $sendprofile;
	$consumEnt->launch = $launch;
	$consumEnt->custom_params = $custom_params;
	$consumEnt->debug = $debug;
	$consumEnt->save();
	
	return $consumEnt;
}


// deletes a consumer object with the given properties and return the resulting (saved) entity
function blti_consumer_delete_consumer($guid) {
	global $CONFIG;

	if ($guid>0) {
		$consumEnt = get_entity($guid);
		if (!$consumEnt || !$consumEnt->canEdit()) {
			register_error('Consumer '.$guid.' not found or you can not edit it!!!');
		} else {
			return $consumEnt->delete();
		}
	} else {
		register_error('Consumer '.$guid.' not found or you can not edit it!!!');
	}
	return false;
}

/**
 * 
 * Gets the handlers
 * @param unknown_type $page
 */
function blti_consumer_page_handler($page) {
	global $CONFIG;
	switch ($page[0]) {
		case 'editconsumer':
			include($CONFIG->pluginspath . 'blti_consumer/pages/admin/' . $page[0] . '.php');
			return true;
		case 'listing':
		case 'launch':
			include($CONFIG->pluginspath . 'blti_consumer/pages/' . $page[0] . '.php');
			return true;
	}
	return false;
}

/**
 * 
 * Gets the admin page handler
 * @param unknown_type $page
 */
function blti_consumer_page_handler_admin($page) {
	global $CONFIG;
	switch ($page[0]) {
		case 'manage_tools_blti':
		case 'editconsumer':
			include($CONFIG->pluginspath . 'blti_consumer/pages/admin/' . $page[0] . '.php');
			return true;
		case 'listing':
		case 'launch':
			include($CONFIG->pluginspath . 'blti_consumer/pages/' . $page[0] . '.php');
			return true;
	}
	return false;
}


