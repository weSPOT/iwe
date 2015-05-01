<?php
elgg_register_event_handler ( 'init', 'system', 'fcatool_init' );
function fcatool_init() {
  elgg_load_library('elgg:group_operators');
  elgg_load_js ( 'wespot_stepup' );
  elgg_register_page_handler ( 'fca', 'fcatool_page_handler' );
  
  // add to groups
  // add_group_tool_option('wespot_fca', 'FCA', true);
  //elgg_extend_view ( 'groups/tool_latest', 'wespot_fca/group_module' );
  // elgg_register_widget_type('wespot_fca', elgg_echo('wespot_fca'), elgg_echo('wespot_fca'));
  //elgg_register_widget_type ( 'wespot_fca', elgg_echo ( 'wespot_fca:group' ), elgg_echo ( 'wespot_fca:launch' ), "groups" );
  
  // add a file link to owner blocks
  elgg_register_plugin_hook_handler ( 'register', 'menu:owner_block', 'wespot_fca_sidebar' );
}
function fcatool_page_handler($segments) {
  $base_dir = elgg_get_plugins_path () . 'wespot_fca/pages/fca';
  $group_guid = elgg_get_page_owner_guid ();
  include "$base_dir/main.php";
  return true;
}

/**
 * Add a menu item to the user ownerblock
 */
function wespot_fca_sidebar($hook, $type, $return, $params) {
  $group = elgg_get_page_owner_entity ();
  
  if (is_group_member ( $group->guid, elgg_get_logged_in_user_guid () )) {
    if (elgg_instanceof ( $params ['entity'], 'user' )) {
      
      $url = '/fca/main?gid=' . $group->guid . '&name=' . $group->name . '&uid=' . $group->owner_guid;
      $item = new ElggMenuItem ( 'wespot_fca', elgg_echo ( 'wespot_fca' ), $url );
      $return [] = $item;
    } else {
      
      $url = '/fca/main?gid=' . $group->guid . '&name=' . $group->name . '&uid=' . $group->owner_guid;
      $item = new ElggMenuItem ( 'wespot_fca', elgg_echo ( 'wespot_fca:group' ), $url );
      $return [] = $item;
    }
  }
  return $return;
}
