<?php
/**
 * External tools
 *
 * @package ElggGroups
 */

$group = elgg_get_page_owner_entity();
if (is_group_member ( $group->guid, elgg_get_logged_in_user_guid () )) {

	$body = '';

	if (elgg_is_active_plugin('wespot_lara')) {
		$body .= elgg_view('wespot_lara/lara', array('entity' => $group));
	}

	if (elgg_is_active_plugin('wespot_badges')) {
		$body .= elgg_view('wespot_badges/badges', array('entity' => $group));
	}

	if (elgg_is_active_plugin('wespot_fca')) {
		$body .= elgg_view('wespot_fca/fca', array('entity' => $group));
	}

	if (elgg_is_active_plugin('wespot_mici')) {
		$body .= elgg_view('wespot_mici/mici', array('entity' => $group));
	}

	echo elgg_view_module('aside', elgg_echo('groups:external_tools'), $body);
}
