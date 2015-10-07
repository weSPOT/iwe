<?php
	
function golab_lab_init() {
    		
	//add a widget
	elgg_register_widget_type('golab_lab', elgg_echo('golab_lab:widgets'), elgg_echo('golab_lab:add:to:page'), 'all,index,groups', TRUE);
}
		
elgg_register_event_handler('init','system','golab_lab_init');

