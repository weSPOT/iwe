<?php
	
	/**
	 * Invite a user to join a group
	 *
	 * @package ElggGroups
	 */
	
	$logged_in_user = elgg_get_logged_in_user_entity();
	
	$user_guids = get_input("user_guid");
	if (!empty($user_guids) && !is_array($user_guids)){
		$user_guids = array($user_guids);
	}

	$adding = false;
	if(elgg_is_admin_logged_in()){
		// if the current user is an admin, add all users?
		if(get_input("all_users") == "yes"){
			$site = elgg_get_site_entity();
			
			$options = array(
				"limit" => false,
				"callback" => "group_tools_guid_only_callback"
			);
			
			$user_guids = $site->getMembers($options);
		}
		
		// add users directly rather than inviting (different button on form)
		if(get_input("submit") == elgg_echo("group_tools:add_users")){
			$adding = true;
		}
	}
	
	$group_guid = (int) get_input("group_guid");
	$text = get_input("comment"); // any additional invitation email text
	
  // not sure how this works given there's no form field for email addresses
  // is that only for non-admin users?
	$emails = get_input("user_guid_email"); 
	if(!empty($emails) && !is_array($emails)){
		$emails = array($emails);
	}
	
	$csv = get_uploaded_file("csv");
	if(get_input("resend") == "yes"){
		$resend = true;
	} else {
		$resend = false;
	}
	
	if ((!empty($user_guids) || !empty($emails) || !empty($csv)) && ($group = get_entity($group_guid))){
    // we have a group or there are emails to send or there is a CSV to import AND we can get the group as an entity...
  
    $parent_guid = isset($group->container_guid) ? $group->container_guid : 0; // group id of *parent* group (zero if not a sub-group)
    // error_log("invite.php | group_guid=".$group_guid." | parent=".$parent_guid); # DEBUG
  
		if(($group instanceof ElggGroup) && $group->canEdit()){
			// show hidden (unvalidated) users
			$hidden = access_get_show_hidden_status();
			access_show_hidden_entities(true);
			
			// counters
			$already_invited = 0;
			$invited = 0;
			$member = 0;
			$join = 0;
			
			// invite existing users
			if(!empty($user_guids)){
				if(!$adding){
					// invite users
					foreach ($user_guids as $u_id) {
						if ($user = get_user($u_id)) {
							if(!$group->isMember($user)){
                // error_log("invite.php | users | this user is not already a member: ".$u_id); # DEBUG
                // only if they're not already a member
								if (!check_entity_relationship($group->getGUID(), "invited", $user->getGUID()) || $resend) {
                  // only invite if not already invited - or if we've asked to re-invite
									if (group_tools_invite_user($group, $user, $text, $resend)) {
										$invited++;
                    if ($parent_guid != 0) {
                      // if there's a parent, just join that too
                      $parent = get_entity($parent_guid);
                      group_tools_invite_user($parent, $user, $text, $resend);
                      // error_log("invite.php | users | there appears to be a parent inquiry too: ".$parent_guid); # DEBUG
                    }
									}
								} else {
									// user was already invited
									$already_invited++;
								}
							} else {
                // user is already a member
								$member++;
							}
						}
					}
				} else {
					// add users directly
					foreach($user_guids as $u_id){
						if($user = get_user($u_id)){
							if(!$group->isMember($user)){
								if(group_tools_add_user($group, $user, $text)){
									$join++;
                  if ($parent_guid != 0) groups_join_group($parent, $user, $text); // if there's a parent, just join that too
								}
							} else {
								$member++;
							}
						}
					}
				}
			}
			
			// invite member by email address
      // not sure how this works given there's no form field for email addresses
			if(!empty($emails)){
				foreach($emails as $email){
          // if ($parent_guid != 0) $text2 = $text.elgg_echo("group_tools:groups:invite:email:parentbody"); // append to body
					$invite_result = group_tools_invite_email($group, $email, $text, $resend);
					if($invite_result === true){
						$invited++;
            if ($parent_guid != 0) {
              // if there's a parent, send an invite to that too - although that might be confusing for the user
              $parent = get_entity($parent_guid);
              group_tools_invite_email($parent, $email, $text, $resend);
              // error_log("invite.php | email | there appears to be a parent inquiry too: ".$parent_guid); # DEBUG
            }
					} elseif($invite_result === null){
						$already_invited++;
					}
				}
			}
			
			// invite from csv
			if(!empty($csv)){
				$file_location = $_FILES["csv"]["tmp_name"];
				
				if($fh = fopen($file_location, "r")){
          // error_log("invite.php | CSV invitation");
					while(($data = fgetcsv($fh, 0, ";")) !== false){
						/*
						 * data structure
						 * data[0] => displayname
						 * data[1] => e-mail address
						 */
            // email address no longer required
						$email = "";
						if(isset($data[1])){
							$email = trim($data[1]);
						}
						
            /*
						if(!empty($email) && is_email_address($email)){
							if($users = get_user_by_email($email)){
								// found a user with this email on the site, so invite (or add)
								$user = $users[0];
								
								if(!$group->isMember($user)){
									if(!$adding){
										if (!check_entity_relationship($group->getGUID(), "invited", $user->getGUID()) || $resend) {
											// invite user
											if(group_tools_invite_user($group, $user, $text, $resend)){
												$invited++;
											}
										} else {
											// user was already invited
											$already_invited++;
										} 
									} else {
										if(group_tools_add_user($group, $user, $text)){
											$join++;
										}
									}
								} else {
									$member++;
								}
							} else {
								// user not found so invite based on email address
								$invite_result = group_tools_invite_email($group, $email, $text, $resend);
								
								if($invite_result === true){
									$invited++;
								} elseif($invite_result === null){
									$already_invited++;
								}
							}
						}
            */
            
            // get user from weSPOT OAuth id
            global $CONFIG;
            $identifier = "wespot_" . $data[0];
            $query = "SELECT entity_guid FROM {$CONFIG->dbprefix}private_settings WHERE name = 'plugin:user_setting:elgg_social_login:uid' AND value = '{$identifier}'";
            // error_log("invite.php | CSV query ".$query); # DEBUG
            $guid = get_data_row($query)->entity_guid;
            $user = get_entity($guid);

            if($user) {
              // error_log("invite.php | considering CSV invitation to ".$data[0]." UID ".$user->getGUID()); # DEBUG
              if(!$group->isMember($user)){
                // only if not already a group member
                if(!$adding){
                  // inviting - if not already invited or we're re-sending the invites...
                  if (!check_entity_relationship($group->getGUID(), "invited", $user->getGUID()) || $resend) {
                    // invite user
                    if(group_tools_invite_user($group, $user, $text, $resend)){
                      $invited++;
                      if ($parent_guid != 0) {
                        // if there's a parent, invite user to that too
                        $parent = get_entity($parent_guid);
                        group_tools_invite_user($parent, $user, $text, $resend);
                        // error_log("invite.php | CSV | inviting to parent inquiry too: ".$parent_guid); # DEBUG
                      }
                    }
                  } else {
                    // user was already invited
                    $already_invited++;
                  } 
                } else {
                  // just adding
                  if(group_tools_add_user($group, $user, $text)){
                    $join++;
                    if ($parent_guid != 0) {
                      // if there's a parent, join that too
                      $parent = get_entity($parent_guid);
                      group_tools_add_user($parent, $user, $text);
                      // error_log("invite.php | CSV | adding to parent inquiry too: ".$parent_guid); # DEBUG
                    }
                  }
                }
              } else {
                // already a member
                $member++;
              }
            } 
/*
            else {

              // user not found so invite based on email address
              $invite_result = group_tools_invite_email($group, $user->email, $text, $resend);
              
              if($invite_result === true){
                $invited++;
              } elseif($invite_result === null){
                $already_invited++;
              }
            }
*/
					}
				}
			}
		
			// restore hidden users
			access_show_hidden_entities($hidden);
			
			// which alert message to show
			if(!empty($invited) || !empty($join)){
				if(!$adding){
					system_message(elgg_echo("group_tools:action:invite:success:invite", array($invited, $already_invited, $member)));
				} else {
					system_message(elgg_echo("group_tools:action:invite:success:add", array($join, $already_invited, $member)));
				}
			} else {
				if(!$adding){
					register_error(elgg_echo("group_tools:action:invite:error:invite", array($already_invited, $member)));
				} else {
					register_error(elgg_echo("group_tools:action:invite:error:add", array($already_invited, $member)));
				}
			}
		} else {
			register_error(elgg_echo("group_tools:action:error:edit"));
		}
	} else {
		register_error(elgg_echo("group_tools:action:error:input"));
	}
	
	forward(REFERER);
