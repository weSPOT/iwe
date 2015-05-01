<?php
elgg_register_event_handler('init', 'system', 'badges_init');       

function badges_init() {        
	elgg_register_page_handler('badges', 'badges_page_handler');
	elgg_register_event_handler('pagesetup', 'system', 'wespot_badges_sidebar');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'wespot_badges_hover_menu');

}

function badges_page_handler($segments) {
	
    $base_dir = elgg_get_plugins_path() . 'wespot_badges/pages/badges';
    $group_guid = elgg_get_page_owner_guid();
       include "$base_dir/main.php";
        return true;

}

/**
 * Add a menu item to the inquiry sidebar
 */
function wespot_badges_sidebar() {
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
			$inquiryserver = str_replace("http://", "", elgg_get_site_url());
			$inquiryserver = str_replace("/", "", $inquiryserver);

			if ($group->owner_guid == elgg_get_logged_in_user_guid())
				$url='http://openbadgesapi.appspot.com/menu.jsp?userid='.strtolower(elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login')).'&context='.$group->guid.'&inquiryserver='.$inquiryserver;
			else
				$url='http://openbadgesapi.appspot.com/listAwardedBadgesPerUser.jsp?userid='.strtolower(elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login')).'&context='.$group->guid.'&inquiryserver='.$inquiryserver;

			elgg_register_menu_item('page', array(
				'name' => 'wespot_badges',
				'text' => elgg_echo('badges'),
				'href' => $url,
				'target' => "_blank",
			));
		}
	}
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
