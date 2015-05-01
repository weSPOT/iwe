<?php

// check if the user is inactive
// if so we're not sending email
function member_selfdelete_email_system($hook, $type, $return, $params){
  $email = $params['to'];
  
  $user = get_user_by_email($email);
  
  if($user->member_selfdelete == "anonymized"){
    return FALSE;
  }
}

/**
 * 	transfers all content to the anonymous user
 * 
 *  @param $user - ElggUser entity
 *  
 *  @return bool
 */
function member_selfdelete_transfer($user){
  
}

// called by menu:user_hover plugin hook
// $params['entity'] is the user
// $params['name'] is the menu name = "user_hover"
// $return is an array of items that are already registered to the menu
// function member_selfdelete_hover_menu($hook, $type, $return, $params) {
//   
//     if(is_array($return) && $params['entity']->member_selfdelete == "anonymized"){
//       foreach($return as $key => $item){
//         if($item->getSection() != "admin"){
//           unset($return[$key]);
//         }
//       }
//     }
// 	
// 	return $return;
// }

function member_selfdelete_hover_menu($hook, $type, $return, $params) {
	$user = $params['entity'];
	if (elgg_is_logged_in() && elgg_get_logged_in_user_guid() == $user->guid) {
		$url = elgg_get_site_url() . 'selfdelete/';
		$item = new ElggMenuItem('member_selfdelete', elgg_echo('member_selfdelete:delete:account'), $url);
		$item->setSection('action');
		$return[] = $item;
	}
	return $return;
}