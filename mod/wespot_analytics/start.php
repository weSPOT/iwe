<?php
function analytics_init() {        
   // elgg_register_widget_type('analytics', elgg_echo('analytics'), elgg_echo('analytics:widget:description'), "all,groups");
}
 
elgg_register_event_handler('init', 'system', 'analytics_init');       
?>