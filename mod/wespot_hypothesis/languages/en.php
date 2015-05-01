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

	'hypothesis' => "Hypotheses",
	'hypothesis:owner' => "%s's hypotheses",
	'hypothesis:friends' => "Friends' hypotheses",
	'hypothesis:all' => "All site hypotheses",
	'hypothesis:add' => "Add hypothesis",
	'hypothesis:more' => 'More hypotheses',

	'hypothesis:group' => "Inquiry hypothesis",
	'groups:enablehypothesis' => 'Enable inquiry hypothesis',

	'hypothesis:edit' => "Edit this hypothesis",
	'hypothesis:delete' => "Delete this hypothesis",
	'hypothesis:history' => "History",
	'hypothesis:view' => "View hypothesis",
	'hypothesis:revision' => "Revision",

    'hypothesis:instruction' => "A hypothesis is a prediction that you can test. Write your hypothesis here.",

	'hypothesis:navigation' => "Navigation",
	'hypothesis:new' => "A new hypothesis",
	'hypothesis:notification' =>
'%s added a new hypothesis:

%s
%s

View and comment on the new hypothesis:
%s
',
	'item:object:hypothesis_top' => 'Top-level hypothesis',
	'item:object:hypothesis' => 'Hypothesis',
	'hypothesis:none' => 'No hypotheses created yet',

	/**
	* River
	**/

	'river:create:object:hypothesis' => '%s created a hypothesis %s',
	'river:create:object:hypothesis_top' => '%s created a hypothesis %s',
	'river:update:object:hypothesis' => '%s updated a hypothesis %s',
	'river:update:object:hypothesis_top' => '%s updated a hypothesis %s',
	'river:comment:object:hypothesis' => '%s commented on a hypothesis titled %s',
	'river:comment:object:hypothesis_top' => '%s commented on a hypothesis titled %s',

	/**
	 * Form fields
	 */

	'hypothesis:title' => 'Hypothesis title',
	'hypothesis:description' => 'Hypothesis text',
	'hypothesis:tags' => 'Tags',
	'hypothesis:parent_guid' => 'Parent hypothesis',
	'hypothesis:access_id' => 'Read access',
	'hypothesis:write_access_id' => 'Write access',

	/**
	 * Status and error messages
	 */
	'hypothesis:saved' => 'Hypothesis saved',
	'hypothesis:notsaved' => 'Hypothesis could not be saved',
	'hypothesis:error:no_title' => 'You must specify a title for this hypothesis.',
	'hypothesis:delete:success' => 'The hypothesis was successfully deleted.',
	'hypothesis:delete:failure' => 'The hypothesis could not be deleted.',
	'hypothesis:revision:delete:success' => 'The hypothesis revision was successfully deleted.',
	'hypothesis:revision:delete:failure' => 'The hypothesis revision could not be deleted.',
	'hypothesis:revision:not_found' => 'Cannot find this revision.',

	/**
	 * Page
	 */
	'hypothesis:strapline' => 'Last updated %s by %s',

	/**
	 * Widget
	 **/
	'hypothesis:widget:description' => "Hypothesis for the inquiry.",
);

add_translation("en", $english);
