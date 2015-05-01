<?php

	/**
	 * This page displays a lightbox where the settings for export can be set just before exporting the page
	 */
	$page_guid = (int) get_input("page_guid");
	$forward = true;
	
  // error_log("pages/export.php - GUID " . $page_guid); # DEBUG
	
	$group = get_entity($page_guid);
	
	
	if(!empty($page_guid)){
		// if(($page = get_entity($page_guid)) && (elgg_instanceof($page, "object", "page_top") || elgg_instanceof($page, "object", "page"))){
		// if (elgg_in_context('groups') && elgg_in_context('group_profile')) {
		if ($group && $group instanceof ElggGroup) {
			$forward = false;
			
			$form_vars = array(
				"id" => "pages-tools-export-form"
			);
			$body_vars = array(
				"entity" => $group
			);
			
			$lightbox = elgg_view_form("pages/export", $form_vars, $body_vars);
		} else {
		  // error_log("pages/export.php - GUID ".$page_guid." not found"); # DEBUG
			register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($page_guid)));
		}
	} else {
	  // error_log("pages/export.php - GUID not passed"); # DEBUG
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}
	
	if(!$forward){
		// show the lightbox content
		echo $lightbox;
	} else {
		forward(REFERER);
	}