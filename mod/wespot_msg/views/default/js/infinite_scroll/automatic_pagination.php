<?php
/**
 * Load next page of a listing through ajax automatically
 *
 * @package ElggInfiniteScroll
 */
?>

elgg.require('elgg.wespot_msg.infinite_scroll');
elgg.provide('elgg.wespot_msg.infinite_scroll.automatic_pagination');

elgg.wespot_msg.infinite_scroll.automatic_pagination.add_waypoint = function() {
	$(this).unbind('append');
	$(this).waypoint(
		elgg.wespot_msg.infinite_scroll.automatic_pagination.remove_waypoint, 
		{
                	context: '.elgg-widget-content .elgg-widget-content-wespot_msg',
                	offset: '0'
		});
	
};

elgg.wespot_msg.infinite_scroll.automatic_pagination.remove_waypoint = function(event, direction) {
	if ( direction == "up" ) {
            $(this).waypoint('destroy');
	    $(this).click();
            $(this).bind('append', elgg.wespot_msg.infinite_scroll.automatic_pagination.add_waypoint);
	}
};

elgg.wespot_msg.infinite_scroll.automatic_pagination.init = function() {
	$('.elgg-infinite-scroll-bottom .elgg-button').css('visibility', 'hidden');
	$('.elgg-infinite-scroll-bottom .elgg-button').waypoint(
		elgg.wespot_msg.infinite_scroll.automatic_pagination.remove_waypoint,
                {
                	context: '.elgg-widget-content .elgg-widget-content-wespot_msg',
                        offset: '0'
                });
};

elgg.register_hook_handler('init', 'system', elgg.wespot_msg.infinite_scroll.automatic_pagination.init);
