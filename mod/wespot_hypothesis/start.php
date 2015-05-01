<?php
/**
 * Elgg Pages
 *
 * @package ElggPages
 */

elgg_register_event_handler('init', 'system', 'hypothesis_init');

/**
 * Initialize the pages plugin.
 *
 */
function hypothesis_init() {

	// register a library of helper functions
	elgg_register_library('elgg:hypothesis', elgg_get_plugins_path() . 'wespot_hypothesis/lib/pages.php');

	$item = new ElggMenuItem('hypothesis', elgg_echo('hypothesis'), 'hypothesis/all');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('hypothesis', 'hypothesis_page_handler');

	// Register a url handler
	elgg_register_entity_url_handler('object', 'hypothesis_top', 'hypothesis_url');
	elgg_register_entity_url_handler('object', 'hypothesis', 'hypothesis_url');
	elgg_register_annotation_url_handler('hypothesis', 'hypothesis_revision_url');

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'wespot_hypothesis/actions';
	elgg_register_action("hypothesis/edit", "$action_base/hypothesis/edit.php");
	elgg_register_action("hypothesis/delete", "$action_base/hypothesis/delete.php");
	elgg_register_action("annotations/hypothesis/delete", "$action_base/annotations/hypothesis/delete.php");

	// Extend the main css view
	elgg_extend_view('css/elgg', 'pages/css');

	// Register javascript needed for sidebar menu
	$js_url = 'mod/wespot_hypothesis/vendors/jquery-treeview/jquery.treeview.min.js';
	elgg_register_js('jquery-treeview', $js_url);
	$css_url = 'mod/wespot_hypothesis/vendors/jquery-treeview/jquery.treeview.css';
	elgg_register_css('jquery-treeview', $css_url);

	// Register entity type for search
	elgg_register_entity_type('object', 'hypothesis');
	elgg_register_entity_type('object', 'hypothesis_top');

	// Register granular notification for this type
	register_notification_object('object', 'hypothesis', elgg_echo('hypothesis:new'));
	register_notification_object('object', 'hypothesis_top', elgg_echo('hypothesis:new'));
	elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'hypothesis_notify_message');

	// add to groups
//	add_group_tool_option('hypothesis', elgg_echo('groups:enablehypothesis'), true);
	elgg_extend_view('groups/tool_latest', 'hypothesis/group_module');

	//add a widget
	elgg_register_widget_type('hypothesis', elgg_echo('hypothesis'), elgg_echo('hypothesis:widget:description'), "all,groups");

	// Language short codes must be of the form "pages:key"
	// where key is the array key below
	elgg_set_config('hypothesis', array(
		'title' => 'text',
		'description' => 'longtext',
		'tags' => 'tags',
		'parent_guid' => 'parent',
		'access_id' => 'access',
		'write_access_id' => 'write_access',
	));

//	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'hypothesis_owner_block_menu');

	// write permission plugin hooks
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'hypothesis_write_permission_check');
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'hypothesis_container_permission_check');

	// icon url override
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'hypothesis_icon_url_override');

	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'hypothesis_entity_menu_setup');

	// hook into annotation menu
	elgg_register_plugin_hook_handler('register', 'menu:annotation', 'pages_annotation_menu_setup');

	// register ecml views to parse
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'hypothesis_ecml_views_hook');

	elgg_register_event_handler('upgrade', 'system', 'hypothesis_run_upgrades');
}

/**
 * Dispatcher for pages.
 * URLs take the form of
 *  All pages:        pages/all
 *  User's pages:     pages/owner/<username>
 *  Friends' pages:   pages/friends/<username>
 *  View page:        pages/view/<guid>/<title>
 *  New page:         pages/add/<guid> (container: user, group, parent)
 *  Edit page:        pages/edit/<guid>
 *  History of page:  pages/history/<guid>
 *  Revision of page: pages/revision/<id>
 *  Group pages:      pages/group/<guid>/all
 *
 * Title is ignored
 *
 * @param array $page
 * @return bool
 */
function hypothesis_page_handler($page) {

	elgg_load_library('elgg:hypothesis');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('groups'), 'groups/all');
	$group = elgg_get_page_owner_entity();
  if (elgg_instanceof($group, 'group')) {
    $tab_url = '';
    $phase = $_GET['phase'];
	  if(!$phase) { $phase = get_entity($page[1])->phase; }
    if($phase) {
      $profiles = elgg_get_entities(array('types' => 'object', 'subtypes' => 'tabbed_profile', 'container_guid' => $group->guid));
      foreach ($profiles as $profile) {
        if($profile->order == $phase) {
          $tab_url = '/tab/' . $profile->guid;
        }
      }
    }
    elgg_push_breadcrumb($group->name, $group->getURL() . $tab_url);
  }

	$base_dir = elgg_get_plugins_path() . 'wespot_hypothesis/pages/pages';

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			include "$base_dir/owner.php";
			break;
		case 'friends':
			include "$base_dir/friends.php";
			break;
		case 'view':
			set_input('guid', $page[1]);
			include "$base_dir/view.php";
			break;
		case 'add':
			set_input('guid', $page[1]);
			include "$base_dir/new.php";
			break;
		case 'edit':
			set_input('guid', $page[1]);
			include "$base_dir/edit.php";
			break;
		case 'group':
			include "$base_dir/owner.php";
			break;
		case 'history':
			set_input('guid', $page[1]);
			include "$base_dir/history.php";
			break;
		case 'revision':
			set_input('id', $page[1]);
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
 * Override the page url
 *
 * @param ElggObject $entity Page object
 * @return string
 */
function hypothesis_url($entity) {
	$title = elgg_get_friendly_title($entity->title);
	return "hypothesis/view/$entity->guid/$title";
}

/**
 * Override the page annotation url
 *
 * @param ElggAnnotation $annotation
 * @return string
 */
function hypothesis_revision_url($annotation) {
	return "hypothesis/revision/$annotation->id";
}

/**
 * Override the default entity icon for pages
 *
 * @return string Relative URL
 */
function hypothesis_icon_url_override($hook, $type, $returnvalue, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'hypothesis_top') ||
		elgg_instanceof($entity, 'object', 'hypothesis')) {
		switch ($params['size']) {
			case 'topbar':
			case 'tiny':
			case 'small':
				return 'mod/wespot_hypothesis/images/pages.gif';
				break;
			default:
				return 'mod/wespot_hypothesis/images/pages_lrg.gif';
				break;
		}
	}
}

/**
 * Add a menu item to the user ownerblock
 */
function hypothesis_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "hypothesis/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('hypothesis', elgg_echo('hypothesis'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->pages_enable != "no") {
			$url = "hypothesis/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('hypothesis', elgg_echo('hypothesis:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add links/info to entity menu particular to pages plugin
 */
function hypothesis_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'hypothesis') {
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
		'text' => elgg_echo('pages:history'),
		'href' => "hypothesis/history/$entity->guid",
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
function hypothesis_notify_message($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];

	if (elgg_instanceof($entity, 'object', 'hypothesis') || elgg_instanceof($entity, 'object', 'hypothesis_top')) {
		$descr = $entity->description;
		$title = $entity->title;
		$owner = $entity->getOwnerEntity();

		return elgg_echo('hypothesis:notification', array(
			$owner->name,
			$title,
			$descr,
			$entity->getURL()
		));
	}
	return null;
}

/**
 * Extend permissions checking to extend can-edit for write users.
 *
 * @param string $hook
 * @param string $entity_type
 * @param bool   $returnvalue
 * @param array  $params
 */
function hypothesis_write_permission_check($hook, $entity_type, $returnvalue, $params) {
	if ($params['entity']->getSubtype() == 'hypothesis'
		|| $params['entity']->getSubtype() == 'hypothesis_top') {

		$write_permission = $params['entity']->write_access_id;
		$user = $params['user'];

		if ($write_permission && $user) {
			switch ($write_permission) {
				case ACCESS_PRIVATE:
					// Elgg's default decision is what we want
					return;
					break;
				case ACCESS_FRIENDS:
					$owner = $params['entity']->getOwnerEntity();
					if ($owner && $owner->isFriendsWith($user->guid)) {
						return true;
					}
					break;
				default:
					$list = get_access_array($user->guid);
					if (in_array($write_permission, $list)) {
						// user in the access collection
						return true;
					}
					break;
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
function hypothesis_container_permission_check($hook, $entity_type, $returnvalue, $params) {

	if (elgg_get_context() == "hypothesis") {
		if (elgg_get_page_owner_guid()) {
			if (can_write_to_container(elgg_get_logged_in_user_guid(), elgg_get_page_owner_guid())) return true;
		}
		if ($page_guid = get_input('page_guid',0)) {
			$entity = get_entity($page_guid);
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
 * Return views to parse for pages.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $return_value
 * @param unknown_type $params
 */
function hypothesis_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/hypothesis'] = elgg_echo('item:object:hypothesis');
	$return_value['object/hypothesis_top'] = elgg_echo('item:object:hypothesis_top');

	return $return_value;
}
