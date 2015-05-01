<?php
elgg_register_event_handler('init', 'system', 'lara_init');       

function lara_init() {        
	elgg_register_page_handler('lara', 'lara_page_handler');
	elgg_register_event_handler('pagesetup', 'system', 'wespot_lara_sidebar');
}

function lara_page_handler($segments) {
    $base_dir = elgg_get_plugins_path() . 'wespot_lara/pages/lara';
    $group_guid = elgg_get_page_owner_guid();
       include "$base_dir/main.php";
        return true;
}

/**
 * Add a menu item to the inquiry sidebar
 */
function wespot_lara_sidebar() {
	if (elgg_in_context('group_profile')) {
		$group = elgg_get_page_owner_entity();
		if (is_group_member ( $group->guid, elgg_get_logged_in_user_guid () )) {
		
			$uid = elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login');
			$uid = strtolower($uid);
			$provider = "";
			$inquiry_id = elgg_get_page_owner_guid();
			if (strpos($uid,'google') !== false) {
				$provider="google";
			}elseif (strpos($uid,'facebook') !== false) {
				$provider="facebook";
			}elseif (strpos($uid,'linkedin') !== false) {
				$provider="linkedin";
			}elseif (strpos($uid,'wespot') !== false) {
				$provider="wespot";
			}

			$providers = array("google_", "facebook_", "linkedin_","wespot_");
			$uid = str_replace($providers, "", $uid);		
			$url='http://ariadne.cs.kuleuven.be/wespot/dashboard_v2/'.$uid.'/'.$provider.'/'.$group->guid;
			elgg_register_menu_item('page', array(
				'name' => 'wespot_lara',
				'text' => elgg_echo('lara'),
				'href' => $url,
				'target' => "_blank",
			));
		}
	}
}
