<?php
elgg_register_event_handler('init', 'system', 'lara_init');       

function lara_init() {        
	elgg_register_page_handler('lara', 'lara_page_handler');
//    elgg_register_widget_type('badges', elgg_echo('badges'), elgg_echo('badges:widget:description'), "all,groups");
	// add a file link to owner blocks
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'wespot_lara_sidebar');
}

function lara_page_handler($segments) {
    $base_dir = elgg_get_plugins_path() . 'wespot_lara/pages/lara';
    $group_guid = elgg_get_page_owner_guid();
       include "$base_dir/main.php";
        return true;
}

/**
 * Add a menu item to the user ownerblock
 */
function wespot_lara_sidebar($hook, $type, $return, $params) {
    $group = elgg_get_page_owner_entity();
//    if (elgg_instanceof($params['entity'], 'user') && elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
//        $url='/lara/main?gid=' . $group->guid . '&name='. $group->name .'&uid=' . $group->owner_guid;
//		$item = new ElggMenuItem('wespot_lara', elgg_echo('lara'), $url);
//		$return[] = $item;
//	} else 
	if (is_group_member ( $group->guid, elgg_get_logged_in_user_guid () )) {
        $url='/lara/main?gid=' . $group->guid . '&name='. $group->name .'&uid=' . $group->owner_guid;
        $item = new ElggMenuItem('wespot_lara', elgg_echo('lara'), $url);
        $return[] = $item;
	}
//    file_put_contents('php://stderr', print_r($group->owner_guid, TRUE));
//    file_put_contents('php://stderr', print_r('GROUP', TRUE));
	return $return;
}
