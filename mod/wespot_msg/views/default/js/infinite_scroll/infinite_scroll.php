<?php
/**
 * Load next page of a listing through ajax when a button clicked
 *
 * @package ElggInfiniteScroll
 */
?>

elgg.provide('elgg.wespot_msg.infinite_scroll');

elgg.wespot_msg.infinite_scroll.load_next = function(event, direction) {
	var $bottom = $(this).parent();
	elgg.wespot_msg.infinite_scroll.bottom = $bottom;
	
	$bottom.addClass('elgg-infinite-scroll-ajax-loading');
//		.find('.elgg-button').css('visibility', 'hidden');
	
	var $list = $bottom.siblings('.elgg-list');

	var $params = elgg.parse_str(elgg.parse_url($bottom.siblings('.wespot_msg-pagination').attr('href')).query);
	$params = {
		path: elgg.parse_url($bottom.siblings('.wespot_msg-pagination').attr('href')).path,
		items_type: 'entity',
		offset: $list.children().length 
	};
	
	var url = "/ajax/view/infinite_scroll/list?" + $.param($params);
	elgg.get(url, elgg.wespot_msg.infinite_scroll.append);
	return false;
}

elgg.wespot_msg.infinite_scroll.append = function(data) {
	var $bottom = elgg.wespot_msg.infinite_scroll.bottom;
	$bottom.removeClass('elgg-infinite-scroll-ajax-loading');
	var $list = $bottom.siblings('.elgg-list-entity');

	if (data) {
		$last = $list.first();
		$list.prepend($(data).children().reverse());
		$bottom.find('.elgg-button').trigger('append', data);
		$bottom.parent().animate({scrollTop: $last.offset().top}, 300);
	} else {
		$bottom.css('visibility', 'hidden');
	}
}

elgg.wespot_msg.infinite_scroll.init = function() {
	$(".elgg-infinite-scroll-bottom a").live("click", elgg.wespot_msg.infinite_scroll.load_next);	
};

elgg.register_hook_handler('init', 'system', elgg.wespot_msg.infinite_scroll.init);
