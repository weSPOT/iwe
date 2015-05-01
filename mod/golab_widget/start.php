<?php
	
function golab_widget_init() {
    		
	//add a widget
	elgg_register_widget_type('golab_widget', elgg_echo('golab_widget:widgets'), elgg_echo('golab_widget:add:to:page'), 'all,index,groups', TRUE);
}
		
elgg_register_event_handler('init','system','golab_widget_init');

