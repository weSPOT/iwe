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
 
 // register actions
 elgg_register_action('tabbed_profile/edit', dirname(__FILE__) . '/actions/tabbed_profile/edit.php');
 elgg_register_action('tabbed_profile/order', dirname(__FILE__) . '/actions/tabbed_profile/order.php');
 
 // register other events
 elgg_register_event_handler('create', 'object', 'tabbed_profile_widget_create');
 
 // hook for creating inquiry widgets
 elgg_register_event_handler('create', 'group', 'group_create');
 
 elgg_register_ajax_view('tabbed_profile/edit');
 
 // register our widgets
 elgg_register_widget_type('group_avatar', elgg_echo("tabbed_profile:group_avatar:widget:title"), elgg_echo("tabbed_profile:group_avatar:widget:description"), 'groups', TRUE);
 elgg_register_widget_type('group_profile_stats', elgg_echo("tabbed_profile:group_stats:widget:title"), elgg_echo("tabbed_profile:group_stats:widget:description"), 'groups', TRUE);
 elgg_register_widget_type('group_profile_block', elgg_echo("tabbed_profile:group_profile:widget:title"), elgg_echo("tabbed_profile:group_profile:widget:description"), 'groups', TRUE);
 elgg_register_widget_type('user_avatar', elgg_echo("tabbed_profile:user_avatar:widget:title"), elgg_echo("tabbed_profile:user_avatar:widget:description"), 'profile', TRUE);
 elgg_register_widget_type('user_profile_block', elgg_echo("tabbed_profile:user_details:widget:title"), elgg_echo("tabbed_profile:user_details:widget:description"), 'profile', TRUE);
 elgg_register_widget_type('user_menu_block', elgg_echo("tabbed_profile:user_menu:widget:title"), elgg_echo("tabbed_profile:user_menu:widget:description"), 'profile', TRUE);

 // add phase options
 add_group_tool_option('phase1', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase1:title'), true); 
 add_group_tool_option('phase2', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase2:title'), true); 
 add_group_tool_option('phase3', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase3:title'), true); 
 add_group_tool_option('phase4', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase4:title'), true); 
 add_group_tool_option('phase5', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase5:title'), true); 
 add_group_tool_option('phase6', elgg_echo('tabbed_profile:enablephase') . ' ' . elgg_echo('tabbed_profile:phase6:title'), true); 
}

// generate urls for profile tabs
function tabbed_profile_url_handler($object) {
	$container = $object->getContainerEntity();
//  return $container->getURL() . '/tab/' . $object->getGUID() . '/' . elgg_get_friendly_title($object->title);
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


// creates the inquiry tabs and widgets

function create_widget($handler, $column, $group_guid, $profile_guid, $default_tab) {
    static $count = 0;
 
    $widget = new ElggWidget;
    $widget->owner_guid = $group_guid;
    $widget->container_guid = $group_guid;
    $widget->access_id = get_default_access();
    $widget->save();
    $widget->handler = $handler;
    $widget->context = "groups";
    $widget->column = $column;
    $widget->order = $count - 99;
    if(!$default_tab) {
        add_entity_relationship($widget->guid, "widget_of_profile_tab", $profile_guid);
    }
    $count++;
}
 
// modify widgets context and create relationship if necessary
function group_create($event, $type, $object) {

// Initialise phases and pre-populate them with widgets
// columns start with number 1 and go from right to left 
    $inquiry_tabs = array();	
	$tool_options = elgg_get_config('group_tool_options');
	if ($tool_options) {
		foreach ($tool_options as $group_option) {
			if ($group_option->name == "phase1" && check_group_option($group_option))
					$inquiry_tabs['tabbed_profile:phase1:label'] = array(1 => array("answers", "hypothesis", "wespot_mindmeister"), 2 => "phase1_help");
			if ($group_option->name == "phase2" && check_group_option($group_option))
					$inquiry_tabs['tabbed_profile:phase2:label'] = array(1 => array("notes", "pages", "wespot_mindmeister"), 2 => "phase2_help");
			if ($group_option->name == "phase3" && check_group_option($group_option))
					$inquiry_tabs['tabbed_profile:phase3:label'] = array(1 => array("wespot_arlearn", "wespot_mindmeister"), 2 => "phase3_help");
			if ($group_option->name == "phase4" && check_group_option($group_option))
    				$inquiry_tabs['tabbed_profile:phase4:label'] = array(1 => array("filerepo", "wespot_mindmeister"), 2 => "phase4_help");
			if ($group_option->name == "phase5" && check_group_option($group_option))
    				$inquiry_tabs['tabbed_profile:phase5:label'] = array(1 => array("group_forum_topics", "answers", "wespot_mindmeister"), 2 => "phase5_help");
			if ($group_option->name == "phase6" && check_group_option($group_option))
    				$inquiry_tabs['tabbed_profile:phase6:label'] = array(1 => array("conclusions", "reflection", "sets", "wespot_mindmeister"), 2 => "phase6_help");
    	}
    }
 
    $group = $object;
    $group->tabbed_profile_setup = 1; //causes the link to group to redirect to the first (smallest 'order') tab -> doesn't check for default flag, gladly
 
    $i = 0;
 
    foreach ($inquiry_tabs as $tab_name => $data) {
        $default_tab = false;//$i == 0; -- we don't use this anymore because the default profile doesn't have settings
        if(is_int($tab_name)) {
            $tab_name = $data;
            $data = null;
        }
 
        $profile = new ElggObject();
        $profile->subtype = 'tabbed_profile';
        $profile->owner_guid = elgg_get_logged_in_user_entity()->guid;
        $profile->container_guid = $group->guid;
        $profile->order = $i + 1;
        if($default_tab) {
            $profile->default = 1;
            $profile->md_version = '1.5';
        }
 
        $profile->title = $tab_name;
        $profile->access_id = get_default_access();
        $profile->save();
 
        $profile->profile_type = "widgets";
        $profile->widget_layout = "2";
        $profile->widget_profile_display = "yes";
        $profile->iframe_url = "http://";
        $profile->iframe_height = 500;
        $profile->group_sidebar = "yes";
 
        if($data) {
            if(is_string($data)) {
                create_widget($data, 1, $group->guid, $profile->guid, $default_tab);
            } else if(is_array($data))
            {
                $is_indexed = array_values($data) === $data;
                if($is_indexed){
                    foreach ($data as $handler) {
                        create_widget($handler, 1, $group->guid, $profile->guid, $default_tab);
                    }
                } else {
                    foreach ($data as $column => $handlers) {
                        if(is_string($handlers)) {
                            create_widget($handlers, $column, $group->guid, $profile->guid, $default_tab);
                        } else {
                            foreach ($handlers as $handler) {
                                create_widget($handler, $column, $group->guid, $profile->guid, $default_tab);
                            }
                        }
                    }
                }
            }
        }
 
        $i++;
    }
}

function check_group_option($group_option){
	$hasTasksModule = true;
	$option_toggle_name = $group_option->name . "_enable";
	$taskOptionName = $option_toggle_name;
	$option_default = $group_option->default_on ? 'yes' : 'no';
	$nextOn = get_input($option_toggle_name, $option_default);
	if ($nextOn == 'yes')
		return true;
	else 
		return false;
}