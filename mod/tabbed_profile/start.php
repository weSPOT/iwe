<?php

define(TABBED_PROFILE_WIDGET_RELATIONSHIP, 'widget_of_profile_tab');
define(TABBED_PROFILE_MAX_TABS, 6);

elgg_register_event_handler('init', 'system', 'tabbed_profile_init');

// include our global functions
include dirname(__FILE__) . '/lib/hooks.php';

function tabbed_profile_init() {

  // Extend our views
  elgg_extend_view('css/elgg', 'tabbed_profile/css');
  elgg_extend_view('page/layouts/widgets', 'tabbed_profile/navigation', 0);
  elgg_extend_view('groups/profile/layout_bottom', 'tabbed_profile/navigation', 0);
  elgg_extend_view('page/layouts/tabbed_profile_widgets', 'tabbed_profile/navigation', 0);
  elgg_extend_view('tabbed_profile/iframe', 'tabbed_profile/navigation', 0);

  // register our js
  $js = elgg_get_simplecache_url('js', 'tabbed_profile/js');
  elgg_register_simplecache_view('js/tabbed_profile/js');
  elgg_register_js('tabbed_profile.js', $js);

  // create urls for tabs
  elgg_register_entity_url_handler('object', 'tabbed_profile', 'tabbed_profile_url_handler');

  elgg_register_library('tabbed_profile', dirname(__FILE__) . '/lib/tabbed_profile.php');

  // register our plugin hooks
 elgg_register_plugin_hook_handler('route', 'profile', 'tabbed_profile_user_router');
 elgg_register_plugin_hook_handler('route', 'groups', 'tabbed_profile_group_router');
 elgg_register_plugin_hook_handler('permissions_check', 'all', 'tabbed_profile_permissions_check');
 elgg_register_plugin_hook_handler('available_widgets_context', 'widget_manager', 'tabbed_profile_widget_context_normalize');
 elgg_register_plugin_hook_handler('action', 'widgets/add', 'tabbed_profile_widgets_add_action_handler');
 elgg_register_plugin_hook_handler("action", "groups/edit", "group_create_or_edit");

 // register actions
 elgg_register_action('tabbed_profile/edit', dirname(__FILE__) . '/actions/tabbed_profile/edit.php');
 elgg_register_action('tabbed_profile/order', dirname(__FILE__) . '/actions/tabbed_profile/order.php');

 // register other events
 elgg_register_event_handler('create', 'object', 'tabbed_profile_widget_create');

 // hook for creating inquiry widgets
// elgg_register_event_handler('create', 'group', 'group_create');

 elgg_register_ajax_view('tabbed_profile/edit');

 // register our widgets
 elgg_register_widget_type('group_avatar', elgg_echo("tabbed_profile:group_avatar:widget:title"), elgg_echo("tabbed_profile:group_avatar:widget:description"), 'groups', TRUE);
 elgg_register_widget_type('group_profile_stats', elgg_echo("tabbed_profile:group_stats:widget:title"), elgg_echo("tabbed_profile:group_stats:widget:description"), 'groups', TRUE);
 elgg_register_widget_type('group_profile_block', elgg_echo("tabbed_profile:group_profile:widget:title"), elgg_echo("tabbed_profile:group_profile:widget:description"), 'groups', TRUE);
 elgg_register_widget_type('user_avatar', elgg_echo("tabbed_profile:user_avatar:widget:title"), elgg_echo("tabbed_profile:user_avatar:widget:description"), 'profile', TRUE);
 elgg_register_widget_type('user_profile_block', elgg_echo("tabbed_profile:user_details:widget:title"), elgg_echo("tabbed_profile:user_details:widget:description"), 'profile', TRUE);
 elgg_register_widget_type('user_menu_block', elgg_echo("tabbed_profile:user_menu:widget:title"), elgg_echo("tabbed_profile:user_menu:widget:description"), 'profile', TRUE);

 // add phase options
// add_group_tool_option('phase1', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase1:title'), true);
// add_group_tool_option('phase2', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase2:title'), true);
// add_group_tool_option('phase3', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase3:title'), true);
// add_group_tool_option('phase4', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase4:title'), true);
// add_group_tool_option('phase5', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase5:title'), true);
// add_group_tool_option('phase6', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase6:title'), true);
}

// generate urls for profile tabs
function tabbed_profile_url_handler($object) {
	$container = $object->getContainerEntity();
//  return $container->getURL() . '/tab/' . $object->getGUID() . '/' . elgg_get_friendly_title($object->title);

    if($container->phases && strpos($container->phases, (string)$object->order) === false) {
        $profiles = elgg_get_entities(array("type" => "object", "container_guid" => $container->getGUID(), 'limit' => 1000));
        $subtype = $object->subtype;
        // "subtype" => $object->subtype in elgg_get_entities didn't work!?
        for ($phase = 1; $phase <= 6; $phase++) {
            $filter = function($element) use ($phase, $subtype) { return $element->order == $phase && $element->subtype == $subtype; };
            $profile = array_filter($profiles, $filter);
            if(count($profile) == 1 && strpos($container->phases, (string)$phase) !== false) {
                return $container->getURL() . '/tab/' . reset($profile)->getGUID();
            }
        }
    }

	return $container->getURL() . '/tab/' . $object->getGUID();
}


// modify widgets context and create relationship if necessary
function tabbed_profile_widget_create($event, $type, $object) {
  if ($object->getSubtype() == 'widget') {

	$profile_guid = get_input('tabbed_profile_guid', false);
	$profile = get_entity($profile_guid);

	if (elgg_instanceof($profile, 'object', 'tabbed_profile')) {

	  // if not a default profile, we need to add the relationship
	  if (!$profile->default) {
	    add_entity_relationship($object->guid, TABBED_PROFILE_WIDGET_RELATIONSHIP, $profile->guid);
	  }
	}

  }
}

// sets up group tabs according to group options
function group_create_or_edit($hook, $entity_type, $returnvalue, $params) {

	$group_guid = (int)get_input('group_guid');
	$group = get_entity($group_guid);
	//$tool_options = elgg_get_config('group_tool_options');
	//if (!$tool_options)
	//	return;

	elgg_load_library('tabbed_profile');
	create_or_edit_all_tabs($group);

}
