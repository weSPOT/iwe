<?php
/**
 * Group Operators languages
 *
 * @package ElggGroupOperators
 */

$english = array(

	/**
	 * Titles
	 */
	"group_operators:title" => 'Admins of %s',
	"group_operators:manage" => 'Manage group admins',
	"group_operators:operators" => 'Admins',
	"group_operators:members" => 'Members',
	
	/**
	 * Menus
	 */
	"group_operators:operators:drop" => 'Drop privileges',
	"group_operators:owner" => 'Is the owner',
	"group_operators:owner:make" => 'Make owner',
	
	/**
	 * Form fields
	 */
	"group_operators:new" => 'Add another admin',
	"group_operators:new:button" => 'Make admin',
	"group_operators:selectone" => 'select one...',
	
	/**
	 * System messages
	 */
	"group_operators:added" => '%s successfully added as group admin',
	"group_operatros:add:error" => 'It was impossible to add %s as group admin',
	"group_operators:owner_changed" => '%s is the new owner',
	"group_operators:change_owner:error" => 'Only the group owner can assign a new owner',
	"group_operators:removed" => 'Admin successfully removed',

);

add_translation("en", $english);
