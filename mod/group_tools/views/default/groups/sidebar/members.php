<?php
/**
 * Group members sidebar
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] Group entity
 * @uses $vars['limit']  The number of members to display
 */
$group = elgg_extract("entity", $vars);

if(!empty($group) && elgg_instanceof($group, "group")){
	if($group->getPrivateSetting("group_tools:cleanup:members") != "yes"){
		$limit = elgg_extract('limit', $vars, 14);
		
		$all_link = elgg_view('output/url', array(
			'href' => 'groups/members/' . $group->getGUID(),
			'text' => elgg_echo('groups:members:more'),
			'is_trusted' => true,
		));
		
		$body = elgg_list_entities_from_relationship(array(
			'relationship' => 'member',
			'relationship_guid' => $group->getGUID(),
			'inverse_relationship' => true,
			'types' => 'user',
			'limit' => $limit,
			'list_type' => 'gallery',
			'gallery_class' => 'elgg-gallery-users',
			'pagination' => false
		));
		
		$body .= "<div class='left mts'>$all_link</div>";
		
		// group mail options
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner->canEdit() && (elgg_get_plugin_setting("mail", "group_tools") == "yes")) {
			$mail_link = elgg_view('output/url', array(
				'href' => "groups/mail/" . $page_owner->getGUID(),
				'text' => elgg_echo('group_tools:menu:mail'),
				'is_trusted' => true,
			));
			$body .= "<div class='left mts'>$mail_link</div>";
		}
		
		// invitation management
		if($page_owner->canEdit()){
			$request_options = array(
				"type" => "user",
				"relationship" => "membership_request", 
				"relationship_guid" => $page_owner->getGUID(), 
				"inverse_relationship" => true, 
				"count" => true
			);
			
			$invite_options = array(
				"type" => "user",
				"relationship" => "invited",
				"relationship_guid" => $page_owner->getGUID(),
				"count" => true
			);
			
			$postfix = "";
			if($requests = elgg_get_entities_from_relationship($request_options)){
				$postfix = " [" . $requests . "]";
			} elseif($invited = elgg_get_entities_from_relationship($invite_options)){
				$postfix = " [" . $invited . "]";
			}
			
			if(!$page_owner->isPublicMembership() || !empty($requests)){
				$membershiprequests_link = elgg_view('output/url', array(
					'text' => elgg_echo('groups:membershiprequests') . $postfix,
					'href' => "groups/requests/" . $page_owner->getGUID(),
					'is_trusted' => true,
				));
				$body .= "<div class='left mts'>$membershiprequests_link</div>";
			} elseif(!empty($invited)){
				$invitations_link = elgg_view('output/url', array(
					'text' => elgg_echo('group_tools:menu:invitations') . $postfix,
					'href' => "groups/requests/" . $page_owner->getGUID(),
					'is_trusted' => true,
				));
				$body .= "<div class='left mts'>$invitations_link</div>";
			}
			
			if(elgg_is_active_plugin('group_operators')){
				$manageadmins_link = elgg_view('output/url', array(
					'text' => elgg_echo('group_operators:manage'),
					'href' => elgg_get_site_url() . "group_operators/manage/{$page_owner->getGUID()}",
					'is_trusted' => true,
				));
				$body .= "<div class='left mts'>$manageadmins_link</div>";
			}
		}
		
		echo elgg_view_module('aside', elgg_echo('groups:members'), $body);
	}
}