<?php
elgg_register_event_handler ( 'init', 'system', 'fcatool_init' );
function fcatool_init() {
  elgg_load_library('elgg:group_operators');
  elgg_load_js ( 'wespot_stepup' );
  elgg_register_page_handler ( 'fca', 'fcatool_page_handler' );
  elgg_register_event_handler('pagesetup', 'system', 'wespot_fca_sidebar');
}

function fcatool_page_handler($segments) {
  $base_dir = elgg_get_plugins_path () . 'wespot_fca/pages/fca';
  $group_guid = elgg_get_page_owner_guid ();
  include "$base_dir/main.php";
  return true;
}

/**
 * Add a menu item to the inquiry sidebar
 */
function wespot_fca_sidebar() {
	if (elgg_in_context('group_profile')) {
		$group = elgg_get_page_owner_entity ();
		if (is_group_member ( $group->guid, elgg_get_logged_in_user_guid () )) {
			$url = '/fca/main?gid=' . $group->guid . '&name=' . $group->name . '&uid=' . $group->owner_guid;
			elgg_register_menu_item('page', array(
				'name' => 'wespot_fca',
				'text' => elgg_echo('wespot_fca:group'),
				'href' => $url,
				'target' => "_blank",
			));
		}
	}
}
