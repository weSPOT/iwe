<?php
	
function wespot_web_search_init() {
    		
	//add a widget
	elgg_register_widget_type('wespot_web_search', elgg_echo('wespot_web_search:widget'), elgg_echo('wespot_web_search:add:to:page'), 'all,index,groups', FALSE);
}
		
elgg_register_event_handler('init','system','wespot_web_search_init');

