//<script>

(function($){

function stopEvent(e) {
    e = e ? e : window.event;
    if (e.stopPropagation) e.stopPropagation();
    if (e.preventDefault) e.preventDefault();
    e.cancelBubble = true;
    e.cancel = true;
    e.returnValue = false;
    return false;
}

$.fn.reverse = [].reverse;

$.fn.extend({
    // param: (boolean) onlyWhenScrollbarVisible
    // If set to true, target container will not intercept mouse wheel
    //     event if it doesn't have its own scrollbar, i.e. if there is too
    //     few content in it. I prefer to use it, because if user don't see
    //     any scrollable content on a page, he don't expect mouse wheel to
    //     stop working somewhere.

    scrollStop: function(onlyWhenScrollbarVisible) {
        return this.each(function(){
            $(this).bind('mousewheel DOMMouseScroll', function(e) {
                if (onlyWhenScrollbarVisible && this.scrollHeight <= this.offsetHeight)
                    return;

                e = e.originalEvent;
                var delta = (e.wheelDelta) ? -e.wheelDelta : e.detail;
                var isIE = Math.abs(delta) >= 120;
                var scrollPending = isIE ? delta / 2 : 0;
                if (delta < 0 && (this.scrollTop + scrollPending) <= 0) {
                    this.scrollTop = 0;
                    stopEvent(e);
                }
                else if (delta > 0 && (this.scrollTop + scrollPending >= (this.scrollHeight - this.offsetHeight))) {
                    this.scrollTop = this.scrollHeight - this.offsetHeight;
                    stopEvent(e);
                }
            });
        });
    }
});
})(jQuery);

elgg.provide('elgg.wespot_msg');

elgg.wespot_msg.init = function() {
        $('.elgg-widget-content-wespot_msg').scrollTop($('.elgg-widget-content-wespot_msg')[0].scrollHeight);

	var form = $('form[name=elgg-wespot_msg]');
	form.find('input[type=submit]').live('click', elgg.wespot_msg.submit);
        $(document).bind('arlearn.notify.message', elgg.wespot_msg.refresh);
        $(".wespot_msg-new-post-notify").live("click", function(e){
            $(this).parent().animate({scrollTop: $(this).parent()[0].scrollHeight}).end().fadeOut("slow", function(){
		$(this).prevAll(".elgg-list-entity").find(".wespot_msg-new-post").removeClass("wespot_msg-new-post");
	    });
	    e.preventDefault();
        });

};

elgg.wespot_msg.submit = function(e) {
	var form = $(this).parents('form');
	form.find('input[type=submit]').toggleClass("elgg-state-disabled");
	var data = form.serialize();
	elgg.action('wespot_msg/add', {
		data: data,
		success: function(json) {
			form.find('textarea').val('');
			form.find('input[type=submit]').toggleClass("elgg-state-disabled");
			form.siblings('.elgg-widget-content-wespot_msg').scrollTop(form.siblings('.elgg-widget-content-wespot_msg')[0].scrollHeight);
		},
		error: function() {
			form.find('input[type=submit]').toggleClass("elgg-state-disabled");
		}
	});

	e.preventDefault();
};

elgg.wespot_msg.refresh = function(event) {
	var form = $('form[name=elgg-wespot_msg]').has("input:hidden[value=" + event.threadId + "]");
	var widget = form.parents('.elgg-widget-content').first();
	
	var params = {}; 
	params.guid = widget.attr('id').split('-').pop();
	var el = widget.find('ul.elgg-list-entity li.elgg-item').last().find('span.elgg-subtext');
	params.offset = (el.length > 0)?el.attr('data-date'):0;
	elgg.action('wespot_msg/ajax/get', {
		data: params,
		success: function(json) {
			var ul = widget.find('ul.elgg-list-entity');
                        if (ul.length > 0) {
			  $li = $(json.output).find('li.elgg-item');
			  $li.filter(function() {
                              return $(this).find('div.me').length === 0;
                          }).addClass("wespot_msg-new-post");
			  $li.appendTo(ul);
                          if(ul.find(".wespot_msg-new-post:below-the-fold").length > 0) {		
				var notify = widget.find(".wespot_msg-new-post-notify");
				if (!notify.is(":visible")) {
				  notify.fadeIn("slow");
        			  ul.find("li.elgg-item:last").waypoint(
					function(e, direction) {
						$(this).waypoint('destroy');
						$(this).parent().siblings(".wespot_msg-new-post-notify").trigger("click");
        				},
	        			{
        	        			context: '.elgg-widget-content .elgg-widget-content-wespot_msg',
                				offset: '100%'
        				}
				  );
				}
			  }
                        } 
			else {
			  widget.find('.elgg-widget-content-wespot_msg').prepend($(json.output).find('ul.elgg-list-entity'));
			}
		}
	});

	event.preventDefault();
};

elgg.register_hook_handler('init', 'system', elgg.wespot_msg.init);
