<?php
elgg_register_event_handler ( 'init', 'system', 'fcatool_init' );

function fcatool_init() {
  elgg_load_library('elgg:group_operators');
  elgg_load_js ( 'wespot_stepup' );
  elgg_register_page_handler ( 'fca', 'fcatool_page_handler' );
}

function fcatool_page_handler($segments) {
  $base_dir = elgg_get_plugins_path () . 'wespot_fca/pages/fca';
  $group_guid = elgg_get_page_owner_guid ();
  include "$base_dir/main.php";
  return true;
}
