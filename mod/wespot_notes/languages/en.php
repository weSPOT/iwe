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

	'notes' => "Notes",
	'notes:owner' => "%s's notes",
	'notes:friends' => "Friends' notes",
	'notes:all' => "All site notes",
	'notes:add' => "Add notes",
	'notes:more' => 'More notes',

	'notes:group' => "Inquiry notes",
	'groups:enablenotes' => 'Enable inquiry notes',

	'notes:edit' => "Edit these notes",
	'notes:delete' => "Delete these notes",
	'notes:history' => "History",
	'notes:view' => "View notes",
	'notes:revision' => "Revision",

    'notes:instruction' => "My notes on the inquiry.",

	'notes:navigation' => "Navigation",
	'notes:new' => "New notes",
	'notes:notification' =>
'%s added new notes:

%s
%s

View and comment on the new notes:
%s
',
	'item:object:notes_top' => 'Top-level notes',
	'item:object:notes' => 'Notes',
	'notes:none' => 'No notes created yet',

	/**
	* River
	**/

	'river:create:object:notes' => '%s created notes %s',
	'river:create:object:notes_top' => '%s created notes %s',
	'river:update:object:notes' => '%s updated notes %s',
	'river:update:object:notes_top' => '%s updated notes %s',
	'river:comment:object:notes' => '%s commented on notes titled %s',
	'river:comment:object:notes_top' => '%s commented on notes titled %s',

	/**
	 * Form fields
	 */

	'notes:title' => 'Notes title',
	'notes:description' => 'Notes text',
	'notes:tags' => 'Tags',
	'notes:parent_guid' => 'Parent notes',
	'notes:access_id' => 'Read access',
	'notes:write_access_id' => 'Write access',

	/**
	 * Status and error messages
	 */
	'notes:saved' => 'Notes saved',
	'notes:notsaved' => 'Notes could not be saved',
	'notes:error:no_title' => 'You must specify a title for these notes.',
	'notes:delete:success' => 'The notes were successfully deleted.',
	'notes:delete:failure' => 'The notes could not be deleted.',
	'notes:revision:delete:success' => 'The notes revision was successfully deleted.',
	'notes:revision:delete:failure' => 'The notes revision could not be deleted.',
	'notes:revision:not_found' => 'Cannot find this revision.',

	/**
	 * Page
	 */
	'notes:strapline' => 'Last updated %s by %s',

	/**
	 * Widget
	 **/
	'notes:widget:description' => "Notes for the inquiry.",
);

add_translation("en", $english);
