<?php
/**
 * Invite users to join a group
 *
 * @package ElggGroups
 */

$logged_in_user = elgg_get_logged_in_user_entity();

$user_guid = get_input('user_guid');
if (!is_array($user_guid)) {
	$user_guid = array($user_guid); // if single user id, turn into array of just this id
}
$group_guid = get_input('group_guid');

error_log("invite.php | group_guid=".$group_guid); # DEBUG

if (sizeof($user_guid)) {
  // loop through all of the users
	foreach ($user_guid as $u_id) {
		$user = get_entity($u_id); // get user object
		$group = get_entity($group_guid); // this is inefficient - why get the group object every time?
    $container_guid = $group->container_guid; // group id of *parent* group (zero if not a sub-group)
    $parent_guid = $group->parent_guid;
    
    error_log("invite.php | parent_guid=".$parent_guid." | container_guid=".$container_guid); # DEBUG
    
		if ($user && $group && ($group instanceof ElggGroup) && $group->canEdit()) {

      // if user not already been invited
			if (!check_entity_relationship($group->guid, 'invited', $user->guid)) {

				// set invite to group
				add_entity_relationship($group->guid, 'invited', $user->guid);
        if ($parent_guid != 0) add_entity_relationship($parent_guid, 'invited', $user->guid); // parent exists so set invite to parent too

				// send notification email
				$url = elgg_normalize_url("groups/invitations/$user->username");
				$result = notify_user($user->getGUID(), $group->owner_guid,
						elgg_echo('groups:invite:subject', array($user->name, $group->name)),
						elgg_echo('groups:invite:body', array(
							$user->name,
							$logged_in_user->name,
							$group->name,
							$url,
						)),
						NULL);
				if ($result) {
					system_message(elgg_echo("groups:userinvited"));
				} else {
					register_error(elgg_echo("groups:usernotinvited"));
				}
			} else {
				register_error(elgg_echo("groups:useralreadyinvited"));
			}
		}
	}
}

forward(REFERER);
