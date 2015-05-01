/**
 * Google Channel API token
 */ 
elgg.provide('elgg.wespot_msg.channel'); 

elgg.wespot_msg.channel.tokenRefreshTimer = null; 

elgg.wespot_msg.channel.open = function() {
        var token = $('input:hidden[name=__elgg_wespot_msg_channel_token]').first().val();
        if (typeof goog != "undefined" && typeof token !== "undefined") {
          var channel = new goog.appengine.Channel(token);
          var handler = {
                'onopen': elgg.wespot_msg.channel.onOpened,
                'onmessage': elgg.wespot_msg.channel.onMessage,
                'onerror': elgg.wespot_msg.channel.onError,
                'onclose': elgg.wespot_msg.channel.onClose
          };
          channel.open(handler);
       }
}

elgg.wespot_msg.channel.reconnect = function() {
    elgg.wespot_msg.channel.refreshToken();
    setTimeout(function() { elgg.wespot_msg.channel.open() }, 4000);
}

elgg.wespot_msg.channel.onOpened = function() {
    //console.log("Channel opened!");
}

elgg.wespot_msg.channel.onMessage = function(data) {
	//console.log("Channel new message ...");
        var e = $.Event('arlearn.notify.message');
        var msg = JSON.parse(data.data);
        e.threadId = msg.threadId || {};
        $(document).trigger(e);
}

elgg.wespot_msg.channel.onError = function(e) {
	//console.log("Channel error ...");
        elgg.wespot_msg.channel.reconnect();
}

elgg.wespot_msg.channel.onClose = function() {
	//console.log("Channel closed ...");
        elgg.wespot_msg.channel.reconnect();
}

/**
 * Sets the currently active Google Channel API token and updates all forms and links on the current page.
 *
 * @param {Object} json The json representation of a token containing __elgg_wespot_msg_channel_token
 * @return {Void}
 */ 
elgg.wespot_msg.channel.setToken = function(data) {
	//update all forms
	$('[name=__elgg_wespot_msg_channel_token]').val(data.output.__elgg_wespot_msg_channel_token);
};

/**
 * Google Channel API token time out so we refresh
 *
 * @private
 */ 
elgg.wespot_msg.channel.refreshToken = function() {
	elgg.action('wespot_msg/refreshtoken', {
		success: function(data) {
			if (data && data.output.__elgg_wespot_msg_channel_token) {
				elgg.wespot_msg.channel.setToken(data);
			} else {
                    		//elgg.register_error(elgg.echo('wespot_msg:channel:failed'));
                	}
		}
	});
};

elgg.wespot_msg.channel.init = function() {
	elgg.wespot_msg.channel.open();
};

elgg.register_hook_handler('init', 'system', elgg.wespot_msg.channel.init);
