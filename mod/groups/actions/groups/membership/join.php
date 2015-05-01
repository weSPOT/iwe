<?php
/**
 * Join a group
 *
 * Three states:
 * open group so user joins
 * closed group so request sent to group owner
 * closed group with invite so user joins
 * 
 * @package ElggGroups
 */

global $CONFIG;

$user_guid = get_input('user_guid', elgg_get_logged_in_user_guid());
$group_guid = get_input('group_guid');

$user = get_entity($user_guid);

error_log("join.php | group_guid=".$group_guid." | user_guid=".$user_guid); # DEBUG

// access bypass for getting invisible group
$ia = elgg_set_ignore_access(true);
$group = get_entity($group_guid); // get *this* group object
$container_guid = $group->container_guid; // group id of *parent* group (zero if not a sub-group)
$parent_guid = $group->parent_guid;

error_log("join.php | parent_guid=".$parent_guid." | container_guid=".$container_guid); # DEBUG

if ($parent_guid != 0) $parent = get_entity($parent_guid); // get the parent group object
elgg_set_ignore_access($ia);

if (($user instanceof ElggUser) && ($group instanceof ElggGroup)) {

	// do a straight join or request membership
	$join = false;
	if ($group->isPublicMembership() || $group->canEdit($user->guid)) {
		// anyone can join public groups and admins can join any group
		$join = true;
	} else if (check_entity_relationship($group->guid, 'invited', $user->guid)) {
    // user has already been invite to closed group
    $join = true;
	}

	if ($join) {
		if (groups_join_group($group, $user)) {
      // just join direct
      if ($parent_guid != 0) groups_join_group($parent, $user); // if there's a parent, just join that too
			system_message(elgg_echo("groups:joined"));
			forward($group->getURL());
		} else {
      // cannot join
			register_error(elgg_echo("groups:cantjoin"));
		}
	} else {
    // invite
		add_entity_relationship($user->guid, 'membership_request', $group->guid);

		// create email to notify group owner
		$url = "{$CONFIG->url}groups/requests/".$group->guid;
		$subject = elgg_echo('groups:request:subject', array(
			$user->name,
			$group->name,
		));
		$body = elgg_echo('groups:request:body', array(
			$group->getOwnerEntity()->name,
			$user->name,
			$group->name,
			$user->getURL(),
			$url,
		));
    
    // if this is a sub-group, also request membership of parent
    if ($parent_guid != 0) {
      // invite to parent
      add_entity_relationship($user->guid, 'membership_request', $parent_guid);
      // append to outgoing email body text
      // no need for separate email as sub-group owner and parent group owner are same
      $body .= elgg_echo("groups:invite:parentbody");
    }
    
    // notify then record system or error message
		if (notify_user($group->owner_guid, $user->getGUID(), $subject, $body)) {
			system_message(elgg_echo("groups:joinrequestmade"));
		} else {
			register_error(elgg_echo("groups:joinrequestnotmade"));
		}
	}
} else {
  // not a user or group - error
	register_error(elgg_echo("groups:cantjoin"));
}

forward(REFERER);
