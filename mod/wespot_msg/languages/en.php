<?php

$english = array(

	/**
	 * Menu items and titles
	 */

	'groups:enablewespot_msg' => 'Enable Inquiry chat',
	'wespot_msg:board' => "Inquiry chat",
	'wespot_msg:messageboard' => "message board",
	'wespor_msg:mustbeingroup' => "You must be a member of %s in order to post a message",
	'wespot_msg:viewall' => "View all",
	'wespot_msg:loadmore' => "Load more",
	'wespot_msg:postit' => "Post",
	'wespot_msg:none' => "There is nothing on this message board yet",
        'wespot_msg:thread_display' => "Thread to display",
	'wespot_msg:num_display' => "Number of messages to display",
	'wespot_msg:desc' => "This is a message board that you can put on your groups where users can comment.",

	'wespot_msg:owner' => '%s\'s message board',
        'wespot_msg:option:none' => 'Please select one',

	/**
	 * Message board widget river
	 */
        'river:create:object:arlearn_msg' => "%s posted on %s's message board",

	/**
	 * Status messages
	 */

	'wespot_msg:posted' => "You successfully posted on the message board.",
	'wespot_msg:deleted' => "You successfully deleted the message.",
        'wespot_msg:new_message' => "New Message",
        'wespot_msg:channel:failed' => 'You may experience problems with real-time messaging. Please refresh this page.',
        'wespot_msg:channel:error' => 'An error occurred while obtaining a token: %s',

	/**
	 * Error messages
	 */

	'wespot_msg:blank' => "Sorry. You need actually to put something in the message area before we can post it.",
	'wespot_msg:notfound' => "Sorry. We could not find the specified item.",
	'wespot_msg:notdeleted' => "Sorry. We could not delete this message.",
	'wespot_msg:somethingwentwrong' => "Something went wrong when trying to save your message, make sure you actually wrote a message.",

	'wespot_msg:failure' => "An unexpected error occurred when adding your message. Please try again.",

	/**
	 * Infinite scroll
	 */
        'infinite_scroll:list_end' => "You've reached the end of the list, there is no more elements to show.",
        'infinite_scroll:settings:pagination_type' => "Which kind of pagination do you like?",
        'infinite_scroll:settings:pagination:classic' => 'Classic',
        'infinite_scroll:settings:pagination:button' => 'Next page with a button',
        'infinite_scroll:settings:pagination:automatic' => 'Automatic on scroll',
        'infinite_scroll:load_more' => "Load more...",

);


add_translation("en", $english);
