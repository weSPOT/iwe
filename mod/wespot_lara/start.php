<?php
elgg_register_event_handler('init', 'system', 'lara_init');       

function lara_init() {        
	elgg_register_page_handler('lara', 'lara_page_handler');
}

function lara_page_handler($segments) {
    $base_dir = elgg_get_plugins_path() . 'wespot_lara/pages/lara';
    $group_guid = elgg_get_page_owner_guid();
       include "$base_dir/main.php";
        return true;
}

