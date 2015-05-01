<?php
/**
 * Wespot Mind Meister languages
 *
 * @package wespot_mindmeister
 */

$english = array(
	/**
	 * Menu items and titles
	 */
	'wespot_mindmeister' => "MindMeister maps",
	'wespot_mindmeister:owner' => "%s's MindMeister maps",
	'wespot_mindmeister:friends' => "Friends' MindMeister maps",
	'wespot_mindmeister:all' => "All site MindMeister maps",
	'wespot_mindmeister:add' => "Add MindMeister map",

	'wespot_mindmeister:group' => "MindMeister maps",
	'groups:enablewespot_mindmeister' => 'Enable MindMeister maps',

	'wespot_mindmeister:edit' => "Edit this MindMeister map",
	'wespot_mindmeister:delete' => "Delete this MindMeister map",
	'wespot_mindmeister:history' => "History",
	'wespot_mindmeister:view' => "View MindMeister map",
	'wespot_mindmeister:revision' => "Revision",

	'wespot_mindmeister:navigation' => "Navigation",
	'wespot_mindmeister:via' => "via maps",
	'item:object:mindmeistermap' => 'MindMeister maps',
	'wespot_mindmeister:nogroup' => 'This inquiry does not have any MindMeister maps yet',
	'wespot_mindmeister:more' => 'More MindMeister maps',
	'wespot_mindmeister:none' => 'No MindMeister maps created yet',
	'wespot_mindmeister:new' => 'New MindMeister map',

	/**
	* River
	**/
	'river:create:object:mindmeistermap' => '%s created a MindMeister map %s',
	'river:update:object:mindmeistermap' => '%s updated a MindMeister map %s',
	'river:comment:object:mindmeistermap' => '%s commented on a MindMeister map titled %s',

	/**
	 * Forms
	 */
	'wespot_mindmeister:openmessage' => 'Open MindMeister map (opens in a new window)',
	'wespot_mindmeister:title' => 'Map title',
	'wespot_mindmeister:description' => 'Map description',
	'wespot_mindmeister:tags' => 'Tags',
	'wespot_mindmeister:access_id' => 'Read access',
	'wespot_mindmeister:write_access_id' => 'Write access',

	'wespot_mindmeister:createnew' => 'Create new map',
	'wespot_mindmeister:editdetails' => 'Edit map details',

	/**
	 * Status and error messages
	 */
	'wespot_mindmeister:noaccess' => 'No access to MindMeister map',
	'wespot_mindmeister:cantedit' => 'You cannot edit this MindMeister map',
	'wespot_mindmeister:saved' => 'MindMeister map saved',
	'wespot_mindmeister:notsaved' => 'MindMeister map could not be saved',
	'wespot_mindmeister:error:no_title' => 'You must specify a title for this MindMeister map.',
	'wespot_mindmeister:error:no_save_map' => 'The MindMeister map could not be saved at this time. Possibly due to communication errors with the web servcies.',
	'wespot_mindmeister:delete:success' => 'The MindMeister maps was successfully deleted.',
	'wespot_mindmeister:delete:failure' => 'The MindMeister map could not be deleted.',
	'wespot_mindmeister:load:failure' => 'The MindMeister map could not be loaded.',
	'wespot_mindmeister:save:failure' => 'The MindMeister map could not be saved.',
	'wespot_mindmeister:load:filemissing' => 'The MindMeister map file being requested does not exist.',
	'wespot_mindmeister:sig:failure' => 'The MindMeister map signature failed verification.',
	'wespot_mindmeister:upload:failure' => 'The MindMeister map was not uploaded correctly.',
	'wespot_mindmeister:move:failure' => 'The MindMeister map could not be moved.',
	'wespot_mindmeister:save:success' => 'The MindMeister map has been successfully uploaded and saved to this website.',
	'wespot_mindmeister:filetype:failure' => 'The MindMeister map passed was of an invalid file type.',


	/**
	 * Map
	 */
	'wespot_mindmeister:strapline' => 'Last updated %s by %s',

	/**
	 * History
	 */
	'wespot_mindmeister:revision:subtitle' => 'Revision created %s by %s',

	/**
	 * Widget
	 **/
	'wespot_mindmeister:num' => 'Number of maps to display',
	'wespot_mindmeister:widget:description' => "This is a list of MindMeister maps.",

	/**
	 * Submenu items
	 */
	'wespot_mindmeister:label:view' => "View MindMeister map",
	'wespot_mindmeister:label:edit' => "Edit MindMeister map",
	'wespot_mindmeister:label:history' => "MindMeister map history",

	/**
	 * Plugin Settings
	 */
	 'wespot_mindmeister:mindmeisterurl' => "MindMeister server url (please include a final '/')",
	 'wespot_mindmeister:mindmeisterkey' => "Mind Meister Shared API key",
	 'wespot_mindmeister:mindmeistersecret' => "Mind Meister Secret API key",
);

add_translation("en", $english);