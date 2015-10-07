<?php
elgg_register_event_handler('init', 'system', 'mici_init');       

function mici_init() {        
	elgg_register_page_handler('mici', 'mici_page_handler');
}

function mici_page_handler($segments) {
    $base_dir = elgg_get_plugins_path() . 'wespot_mici/pages/mici';
    //$group_guid = elgg_get_page_owner_guid();
	include "$base_dir/main.php";
	return true;
}

