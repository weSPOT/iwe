<?php
$group = elgg_get_page_owner_entity ();

if (is_group_member ( $group->guid, elgg_get_logged_in_user_guid () )) {
	if (elgg_instanceof ( $params ['entity'], 'user' ))
	  $url = '/fca/main?gid=' . $group->guid . '&name=' . $group->name . '&uid=' . $group->owner_guid;
	else
	  $url = '/fca/main?gid=' . $group->guid . '&name=' . $group->name . '&uid=' . $group->owner_guid;
}
$link = '<div class="left mts"><a href="'.$url.'" target="_blank">'.elgg_echo('wespot_fca:group').'</a></div>';
echo $link;