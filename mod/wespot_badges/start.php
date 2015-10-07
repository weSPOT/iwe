<?php
elgg_register_event_handler('init', 'system', 'badges_init');       

function badges_init() {        
	elgg_register_page_handler('badges', 'badges_page_handler');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'wespot_badges_hover_menu');

}

function badges_page_handler($segments) {
	
    $base_dir = elgg_get_plugins_path() . 'wespot_badges/pages/badges';
    $group_guid = elgg_get_page_owner_guid();
       include "$base_dir/main.php";
        return true;

}

/**
 * Add a menu item to the user profile sidebar
 */
function wespot_badges_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];
	if (elgg_is_logged_in() && elgg_get_logged_in_user_guid() == $user->guid) {
		$url='/badges/main?gid=' . $user->guid . '&name='. $user->name .'&uid=' . $user->owner_guid;
		$item = new ElggMenuItem('wespot_badges', elgg_echo('badges'), $url);
		$item->setSection('action');
		$return[] = $item;
	}
	return $return;
}
