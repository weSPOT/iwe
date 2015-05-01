<?php
/**
 * MindMeister Maps
 */

elgg_register_event_handler('init', 'system', 'wespot_mindmeister_init');

/**
 * Initialize the MindMeister maps plugin.
 *
 */
function wespot_mindmeister_init() {

	// register a library of helper functions
	elgg_register_library('elgg:wespot_mindmeister', elgg_get_plugins_path() . 'wespot_mindmeister/lib/wespot_mindmeister.php');
	elgg_register_library('elgg:wespot_mindmeisterservices', elgg_get_plugins_path() . 'wespot_mindmeister/lib/mindmeisterservices.php');

	// Site navigation
	$item = new ElggMenuItem('wespot_mindmeister', elgg_echo('wespot_mindmeister'), 'wespot_mindmeister/all');
	elgg_register_menu_item('site', $item);

	// Register a map handler, so we can have nice URLs
	elgg_register_page_handler('wespot_mindmeister', 'wespot_mindmeister_page_handler');

	// Register a url handler
	elgg_register_entity_url_handler('object', 'mindmeistermap', 'wespot_mindmeister_url');
	elgg_register_annotation_url_handler('mindmeistermap', 'wespot_mindmeister_revision_url');

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'wespot_mindmeister/actions/wespot_mindmeister';
	elgg_register_action("wespot_mindmeister/new", "$action_base/new.php");
	elgg_register_action("wespot_mindmeister/edit", "$action_base/edit.php");
	elgg_register_action("wespot_mindmeister/delete", "$action_base/delete.php");

	// Extend the main css view
	elgg_extend_view('css/elgg', 'wespot_mindmeister/css');

	// Register entity type for search
	elgg_register_entity_type('object', 'mindmeistermap');

	// Register granular notification for this type
	register_notification_object('object', 'mindmeistermap', elgg_echo('wespot_mindmeister:new'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'mindmap_notify_message');

	// add to groups
//	add_group_tool_option('wespot_mindmeister', elgg_echo('groups:enablewespot_mindmeister'), true);
	elgg_extend_view('groups/tool_latest', 'wespot_mindmeister/group_module');

	//add a widget just on Groups - MB: Should this be to all?
	//elgg_register_widget_type('wespot_mindmeister', elgg_echo('wespot_mindmeister'), elgg_echo('wespot_mindmeister:widget:description'));
	elgg_register_widget_type('wespot_mindmeister', elgg_echo('wespot_mindmeister'), elgg_echo('wespot_mindmeister:widget:description'), "groups");

	// Language short codes must be of the form "wespot_mindmeister:key"
	// where key is the array key below
	elgg_set_config('wespot_mindmeister', array(
		'title' => 'text',
		'description' => 'longtext',
		'tags' => 'tags',
//		'access_id' => 'access',
		'write_access_id' => 'write_access',
	));

	// menus
//	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'wespot_mindmeister_owner_block_menu');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'wespot_mindmeister_entity_menu_setup');

	// write permission plugin hooks
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'wespot_mindmeister_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'wespot_mindmeister_container_permission_check');

	// icon url override
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'wespot_mindmeister_icon_url_override');

	// register ecml views to parse
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'wespot_mindmeister_ecml_views_hook');

	$url = elgg_normalize_url('mod/wespot_mindmeister/views/default/js/wespot_mindmeister/wespot_mindmeisterlib.php');
	elgg_register_js('elgg:wespot_mindmeister', $url);

	// NEW FOR THIS PLUGIN - SUPPLIMENT THE REST SERVICES WITH OUR OWN SO WE CAN HANDLE MINDMEISTER FILE TRANSFERS
	register_service_handler('wespot_mindmeister', 'wespot_mindmeister_service_handler');
}

/**
 * Dispatcher for wespot_mindmeister.
 * URLs take the form of
 *  All wespot_mindmeister:        wespot_mindmeister/all
 *  User's wespot_mindmeister:     wespot_mindmeister/owner/<username>
 *  Friends' wespot_mindmeister:   wespot_mindmeister/friends/<username>
 *  View map:        wespot_mindmeister/view/<guid>/<title>
 *  New map:         wespot_mindmeister/add/<guid> (container: user, group, parent)
 *  Edit map:        wespot_mindmeister/edit/<guid>
 *  History of map:  wespot_mindmeister/history/<guid>
 *  Revision of map: wespot_mindmeister/revision/<id>
 *  Group wespot_mindmeister:      wespot_mindmeister/group/<guid>/all
 *
 * Title is ignored
 *
 * @param array $mindmap
 * @return bool
 */
function wespot_mindmeister_page_handler($mindmap) {
	if (!isset($mindmap[0])) {
		$mindmap[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('groups'), 'groups/all');
	$group = elgg_get_page_owner_entity();
	if (elgg_instanceof($group, 'group'))
		elgg_push_breadcrumb($group->name, $group->getURL());
		
	$base_dir = elgg_get_plugins_path() . 'wespot_mindmeister/pages/wespot_mindmeister';

	$type = $mindmap[0];
	switch ($type) {
		case 'owner':
			include "$base_dir/owner.php";
			break;
		case 'friends':
			include "$base_dir/friends.php";
			break;
		case 'view':
			set_input('guid', $mindmap[1]);
			include "$base_dir/view.php";
			break;
		case 'add':
			set_input('guid', $mindmap[1]);
			include "$base_dir/new.php";
			break;
		case 'edit':
			set_input('guid', $mindmap[1]);
			include "$base_dir/edit.php";
			break;
		case 'group':
			include "$base_dir/owner.php";
			break;
		case 'history':
			set_input('guid', $mindmap[1]);
			include "$base_dir/history.php";
			break;
		case 'revision':
			set_input('id', $mindmap[1]);
			include "$base_dir/revision.php";
			break;
		case 'all':
			include "$base_dir/world.php";
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Override the MindMeister map url
 *
 * @param ElggObject $entity Page object
 * @return string
 */
function wespot_mindmeister_url($entity) {
	$title = elgg_get_friendly_title($entity->title);
	return "wespot_mindmeister/view/$entity->guid/$title";
}

/**
 * Override the MindMiester map annotation url
 *
 * @param ElggAnnotation $annotation
 * @return string
 */
function wespot_mindmeister_revision_url($annotation) {
	return "wespot_mindmeister/revision/$annotation->id";
}

/**
 * Override the default entity icon for wespot_mindmeister
 *
 * @return string Relative URL
 */
function wespot_mindmeister_icon_url_override($hook, $type, $returnvalue, $params) {

	$base_dir = elgg_get_site_url(). 'mod/wespot_mindmeister/images/';

	$entity = $params['entity'];
	$size = $params['size'];
	if (elgg_instanceof($entity, 'object', 'mindmeistermap')) {
		switch ($size) {
			case 'tiny':
				return $base_dir.'mindmeister_16.png';
				break;
			case 'small':
				return $base_dir.'mindmeister_32.png';
				break;
			case 'medium':
				return $base_dir.'mindmeister_48.png';
				break;
			case 'large':
				return $base_dir.'mindmeister_64.png';
				break;
		}
	}
}

/**
 * Add a menu item to the user ownerblock
 */
function wespot_mindmeister_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "wespot_mindmeister/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('wespot_mindmeister', elgg_echo('wespot_mindmeister'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->wespot_mindmeister_enable != "no") {
			$url = "wespot_mindmeister/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('wespot_mindmeister', elgg_echo('wespot_mindmeister:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add links/info to entity menu particular to wespot_mindmeister plugin
 */
function wespot_mindmeister_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'wespot_mindmeister') {
		return $return;
	}

	// remove delete if not owner or admin
	if (!elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != $entity->getOwnerGuid()) {
		foreach ($return as $index => $item) {
			if ($item->getName() == 'delete') {
				unset($return[$index]);
			}
		}
	}

	$options = array(
		'name' => 'history',
		'text' => elgg_echo('wespot_mindmeister:history'),
		'href' => "wespot_mindmeister/history/$entity->guid",
		'priority' => 150,
	);
	$return[] = ElggMenuItem::factory($options);

	return $return;
}

/**
* Returns a more meaningful message
*
* @param unknown_type $hook
* @param unknown_type $entity_type
* @param unknown_type $returnvalue
* @param unknown_type $params
*/
function mindmap_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];
	if ( ($entity instanceof ElggEntity) && ($entity->getSubtype() == 'mindmeistermap') ) {
		$desc = $entity->description;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();
		return $owner->name . ' ' . elgg_echo("wespot_mindmeister:via") . ': ' . $title . "\n\n" . $desc . "\n\n" . $entity->getURL();
	}
	return null;
}

/**
 * Extend permissions checking to extend can-edit for write users.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function wespot_mindmeister_write_permission_check($hook, $entity_type, $returnvalue, $params) {

	if ($params['entity']->getSubtype() == 'mindmeistermap') {
		$mindmap = $params['entity'];

		$write_permission = $params['entity']->write_access_id;
		$user = $params['user'];

		if (($write_permission) && ($user)) {
			$list = get_access_array($user->guid);
			if (($write_permission!=0) && (in_array($write_permission,$list))) {
				return true;
			}
		}
	}
}

/**
 * Extend container permissions checking to extend can_write_to_container for write users.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function wespot_mindmeister_container_permission_check($hook, $entity_type, $returnvalue, $params) {

	if (elgg_get_context() == "wespot_mindmeister") {
		if (elgg_get_page_owner_guid()) {
			if (can_write_to_container(elgg_get_logged_in_user_guid(), elgg_get_page_owner_guid())) return true;
		}
		if ($mindmap_guid = get_input('mindmap_guid',0)) {
			$entity = get_entity($mindmap_guid);
		} else if ($parent_guid = get_input('parent_guid',0)) {
			$entity = get_entity($parent_guid);
		}
		if ($entity instanceof ElggObject) {
			if (
					can_write_to_container(elgg_get_logged_in_user_guid(), $entity->container_guid)
					|| in_array($entity->write_access_id,get_access_list())
				) {
					return true;
			}
		}
	}

}

/**
 * Return views to parse for wespot_mindmeister.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 */
function wespot_mindmeister_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/mindmeistermap'] = elgg_echo('item:object:mindmeistermap');
	return $return_value;
}


/************************************/
/** NEW FUNCTIONS ADDED FOR WESPOT **/
/************************************/

/**
 * REST API handler for wespot_mindmeister.
 * This function was added to add a new rest service to Elgg
 * as the Elgg rest service only returns xml and json and
 * this plugin required file transer handling from MindMeister
 *
 * @return void
 * @access private
 *
 * @throws SecurityException|APIException
 */
function wespot_mindmeister_service_handler() {
	global $CONFIG;

	// Register the error handler
	error_reporting(E_ALL);
	set_error_handler('_php_api_error_handler');

	// Register a default exception handler
	set_exception_handler('_php_api_exception_handler');

	// Check to see if the api is available
	if ((isset($CONFIG->disable_api)) && ($CONFIG->disable_api == true)) {
		throw new SecurityException(elgg_echo('SecurityException:APIAccessDenied'));
	}

	// plugins should return true to control what API and user authentication handlers are registered
	if (elgg_trigger_plugin_hook('rest', 'init', null, false) == false) {
		// for testing from a web browser, you can use the session PAM
		// do not use for production sites!!
		//register_pam_handler('pam_auth_session');

		// user token can also be used for user authentication
		register_pam_handler('pam_auth_usertoken');

		// simple API key check
		register_pam_handler('api_auth_key', "sufficient", "api");
		// hmac
		register_pam_handler('api_auth_hmac', "sufficient", "api");
	}

	// Get parameter variables
	$method = get_input('method');
	$result = null;

	// this will throw an exception if authentication fails
	authenticate_method($method);

	return wespot_mindmeister_exectute_method($method);
}

/**
 * This function executes the wespot_mindmeister restful api method given to it from the service handler.
 *
 * @param $method, the rest method to invoke.
 */
function wespot_mindmeister_exectute_method($method) {
	global $API_METHODS, $CONFIG;

	// method must be exposed
	if (!isset($API_METHODS[$method])) {
		$msg = elgg_echo('APIException:MethodCallNotImplemented', array($method));
		throw new APIException($msg);
	}

	// function must be callable
	if (!(isset($API_METHODS[$method]["function"]))
	|| !(is_callable($API_METHODS[$method]["function"]))) {

		$msg = elgg_echo('APIException:FunctionDoesNotExist', array($method));
		throw new APIException($msg);
	}

	// check http call method
	if (strcmp(get_call_method(), $API_METHODS[$method]["call_method"]) != 0) {
		$msg = elgg_echo('CallException:InvalidCallMethod', array($method,
		$API_METHODS[$method]["call_method"]));

		throw new CallException($msg);
	}

	$parameters = get_parameters_for_method($method);

	if (verify_parameters($method, $parameters) == false) {
		// if verify_parameters fails, it throws exception which is not caught here
	}

	$serialised_parameters = serialise_parameters($method, $parameters);

	// Execute function: Construct function and calling parameters
	$function = $API_METHODS[$method]["function"];
	$serialised_parameters = trim($serialised_parameters, ", ");

	// @todo document why we cannot use call_user_func_array here
	eval("return $function($serialised_parameters);");
}

/**
 * Expose the function for the wespot rest service that allows MindMeister to load a given map
 */
expose_function("wespot_mindmeister.loadmap",
                "load_mindmeistermap",
                 array("filename" => array('type' => 'string', 'required' => true),
                 	"guid" => array('type' => 'string', 'required' => true),
                 	"sig" => array('type' => 'string', 'required' => true)),
                 'A method for MindMeister to call to load a map file from Elgg',
                 'GET',
                 false,
                 false
                );

/**
 * Expose the function for the wespot rest service that allows MindMeister to save a given map
 */
expose_function("wespot_mindmeister.savemap",
                "save_mindmeistermap",
                 array("filename" => array('type' => 'string', 'required' => true),
                 	"guid" => array('type' => 'string', 'required' => true),
                 	"sig" => array('type' => 'string', 'required' => true)),
                 'A method for MindMeister to call to save a map to Elgg',
                 'POST',
                 false,
                 false
                );

/**
 * The function for the wespot rest service that allows MindMeister to load a given map
 * Calls a fucntion in the lib/mindmeisterservices.php to do tha actual work
 *
 * @param $filename, the name of the file being handled.
 * @param $mindmap_guid, the guid for the mind map that this file belongs to.
 * @param $sig, the signature to test against to make sure the passed data has not been tampered with in transit.
 */
function load_mindmeistermap($filename, $mindmap_guid, $sig) {
	elgg_load_library('elgg:wespot_mindmeisterservices');
	return loadMindMeisterMap($filename, $mindmap_guid, $sig);
}

/**
 * The function for the wespot rest service that allows MindMeister to save a given map
 * Calls a fucntion in the lib/mindmeisterservices.php to do tha actual work
 *
 * @param $filename, the name of the file being handled.
 * @param $mindmap_guid, the guid for the mind map that this file belongs to.
 * @param $sig, the signature to test against to make sure the passed data has not been tampered with in transit.
 */
function save_mindmeistermap($filename, $mindmap_guid, $sig) {
	elgg_load_library('elgg:wespot_mindmeisterservices');
	return saveMindMeisterMap($filename, $mindmap_guid, $sig);
}