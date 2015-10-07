<?php
elgg_register_event_handler('init', 'system', 'medoky_init');


function medoky_init() {
elgg_load_library('elgg:group_operators');
//productionserver
$GLOBALS['server'] = "http://css-kti.tugraz.at:8080";

//testserver
//$GLOBALS['server'] = "http://css-kti.tugraz.at";

elgg_register_css('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css');
elgg_register_css('font-awesome-animation', $basedir . 'mod/wespot_medoky/css/font-awesome-animation.min.css');

elgg_load_css('font-awesome');
elgg_load_css('font-awesome-animation');


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
