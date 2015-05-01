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

	'reflection' => "Reflections",
	'reflection:owner' => "%s's reflections",
	'reflection:friends' => "Friends' reflections",
	'reflection:all' => "All site reflections",
	'reflection:add' => "Add reflection",
	'reflection:more' => 'More reflections',

	'reflection:group' => "Inquiry reflection",
	'groups:enablereflection' => 'Enable inquiry reflection',

	'reflection:edit' => "Edit this reflection",
	'reflection:delete' => "Delete this reflection",
	'reflection:history' => "History",
	'reflection:view' => "View reflection",
	'reflection:revision' => "Revision",

    'reflection:instruction' => "What I have learned and what I would do differently next time.",

	'reflection:navigation' => "Navigation",
	'reflection:new' => "A new reflection",
	'reflection:notification' =>
'%s added a new reflection:

%s
%s

View and comment on the new reflection:
%s
',
	'item:object:reflection_top' => 'Top-level reflection',
	'item:object:reflection' => 'Reflection',
	'reflection:none' => 'No reflections created yet',

	/**
	* River
	**/

	'river:create:object:reflection' => '%s created a reflection %s',
	'river:create:object:reflection_top' => '%s created a reflection %s',
	'river:update:object:reflection' => '%s updated a reflection %s',
	'river:update:object:reflection_top' => '%s updated a reflection %s',
	'river:comment:object:reflection' => '%s commented on a reflection titled %s',
	'river:comment:object:reflection_top' => '%s commented on a reflection titled %s',

	/**
	 * Form fields
	 */

	'reflection:title' => 'Reflection title',
	'reflection:description' => 'Reflection text',
	'reflection:tags' => 'Tags',
	'reflection:parent_guid' => 'Parent reflection',
	'reflection:access_id' => 'Read access',
	'reflection:write_access_id' => 'Write access',

	/**
	 * Status and error messages
	 */
	'reflection:saved' => 'Reflection saved',
	'reflection:notsaved' => 'Reflection could not be saved',
	'reflection:error:no_title' => 'You must specify a title for this reflection.',
	'reflection:delete:success' => 'The reflection was successfully deleted.',
	'reflection:delete:failure' => 'The reflection could not be deleted.',
	'reflection:revision:delete:success' => 'The reflection revision was successfully deleted.',
	'reflection:revision:delete:failure' => 'The reflection revision could not be deleted.',
	'reflection:revision:not_found' => 'Cannot find this revision.',

	/**
	 * Page
	 */
	'reflection:strapline' => 'Last updated %s by %s',

	/**
	 * Widget
	 **/
	'reflection:widget:description' => "Reflection for the inquiry.",
);

add_translation("en", $english);
