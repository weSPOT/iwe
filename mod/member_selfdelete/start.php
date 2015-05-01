<?php

include 'lib/functions.php';

function member_selfdelete_init() {
  
    // prevent people from seeing the profile of disabled users
    elgg_extend_view('profile/details', 'profile/member_selfdelete_pre_userdetails', 0);
	
	elgg_register_page_handler('selfdelete','member_selfdelete_page_handler');
	
	elgg_register_action("selfdelete", elgg_get_plugins_path() . "member_selfdelete/actions/delete.php");
	
	elgg_register_event_handler('pagesetup','system','member_selfdelete_pagesetup');
	
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'member_selfdelete_hover_menu');
	elgg_register_plugin_hook_handler('email', 'system', 'member_selfdelete_email_system');
//	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'member_selfdelete_owner_block_menu');

}


function member_selfdelete_page_handler($page){
  if(!include(elgg_get_plugins_path() . "member_selfdelete/pages/form.php")){
    return FALSE;
  }    
  return TRUE;
}

function member_selfdelete_pagesetup() {
    if (elgg_get_context() == 'admin' && elgg_is_admin_logged_in() && elgg_get_plugin_setting('feedback', 'member_selfdelete') == "yes") {
	    elgg_register_admin_menu_item('administer', 'member_selfdelete/reasons', 'users');
	}
	
	if (elgg_get_context() == 'settings' && elgg_is_logged_in()) {
	  $item = new ElggMenuItem('member_selfdelete', elgg_echo('member_selfdelete:delete:account'), elgg_get_site_url() . 'selfdelete/');
	  elgg_register_menu_item('page', $item);
	}
}

// function member_selfdelete_owner_block_menu($hook, $type, $return, $params) {
//     if (elgg_instanceof($params['entity'], 'user') && elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
//         $url = elgg_get_site_url() . 'selfdelete/';
//         $item = new ElggMenuItem('member_selfdelete', elgg_echo('member_selfdelete:delete:account'), $url);
// //        $item->setLinkClass('elgg-button elgg-button-delete');
//         $return[] = $item;
//     }
//     return $return;
// }

register_elgg_event_handler('init','system','member_selfdelete_init');