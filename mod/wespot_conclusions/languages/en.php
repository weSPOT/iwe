<?php
/**
 * Pages languages
 *
 * @package ElggPages
 */

$english = array(

	/**
	 * Menu items and titles
	 */

	'conclusions' => "Conclusions",
	'conclusions:owner' => "%s's conclusions",
	'conclusions:friends' => "Friends' conclusions",
	'conclusions:all' => "All site conclusions",
	'conclusions:add' => "Add conclusions",
	'conclusions:more' => 'More conclusions',

	'conclusions:group' => "Inquiry conclusions",
	'groups:enableconclusions' => 'Enable inquiry conclusions',

	'conclusions:edit' => "Edit these conclusions",
	'conclusions:delete' => "Delete these conclusions",
	'conclusions:history' => "History",
	'conclusions:view' => "View conclusions",
	'conclusions:revision' => "Revision",
    'conclusions:instruction' => "My conclusions of the inquiry.",
	'conclusions:navigation' => "Navigation",
	'conclusions:new' => "New conclusions",
	'conclusions:notification' =>
'%s added new conclusions:

%s
%s

View and comment on the new conclusions:
%s
',
	'item:object:conclusions_top' => 'Top-level conclusions',
	'item:object:conclusions' => 'Conclusions',
	'conclusions:none' => 'No conclusions created yet',

	/**
	* River
	**/

	'river:create:object:conclusions' => '%s created conclusions %s',
	'river:create:object:conclusions_top' => '%s created conclusions %s',
	'river:update:object:conclusions' => '%s updated conclusions %s',
	'river:update:object:conclusions_top' => '%s updated conclusions %s',
	'river:comment:object:conclusions' => '%s commented on conclusions titled %s',
	'river:comment:object:conclusions_top' => '%s commented on conclusions titled %s',

	/**
	 * Form fields
	 */

	'conclusions:title' => 'Conclusions title',
	'conclusions:description' => 'Conclusions text',
	'conclusions:tags' => 'Tags',
	'conclusions:parent_guid' => 'Parent conclusions',
	'conclusions:access_id' => 'Read access',
	'conclusions:write_access_id' => 'Write access',

	/**
	 * Status and error messages
	 */
	'conclusions:saved' => 'Conclusions saved',
	'conclusions:notsaved' => 'Conclusions could not be saved',
	'conclusions:error:no_title' => 'You must specify a title for these conclusions.',
	'conclusions:delete:success' => 'The conclusions were successfully deleted.',
	'conclusions:delete:failure' => 'The conclusions could not be deleted.',
	'conclusions:revision:delete:success' => 'The conclusions revision was successfully deleted.',
	'conclusions:revision:delete:failure' => 'The conclusions revision could not be deleted.',
	'conclusions:revision:not_found' => 'Cannot find this revision.',

	/**
	 * Page
	 */
	'conclusions:strapline' => 'Last updated %s by %s',

	/**
	 * Widget
	 **/
	'conclusions:widget:description' => "Conclusions for the inquiry.",
);

add_translation("en", $english);
