<?php
 
    function google_analytics_init()
    {
 	elgg_extend_view('page/elements/head', 'google_analytics/head_extended');
    }
 
    register_elgg_event_handler('init','system','google_analytics_init');

?>