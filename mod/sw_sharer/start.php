<?php

function sharer_init() 
		{
		global $CONFIG;
		elgg_extend_view('page/elements/elgg','page/elements/sidebar');
		}
		
	register_elgg_event_handler('init','system','sharer_init');

?>