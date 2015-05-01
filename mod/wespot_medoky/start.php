<?php
elgg_register_event_handler('init', 'system', 'medoky_init');


function medoky_init() {
elgg_load_library('elgg:group_operators');
//productionserver
$GLOBALS['server'] = "http://css-kti.tugraz.at:8080";

//testserver
//$GLOBALS['server'] = "http://css-kmi.tugraz.at";


elgg_load_js('wespot_stepup');
elgg_register_page_handler('medoky', 'medoky_page_handler');

// this was removed to shift the postion of the recommendation plugin
//elgg_extend_view('page/elements/sidebar','wespot_medoky/sidebar');
}


function medoky_page_handler($segments) {
    $base_dir = elgg_get_plugins_path() . 'wespot_medoky/pages/medoky';
    include "$base_dir/main.php";
    return true;
}