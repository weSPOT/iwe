<?php

if(!empty($vars["entity"])){

	$group = elgg_extract("entity", $vars);

	$title = elgg_echo("group_tools:duplicate:title");

	$result = '<p>'.elgg_echo("group_tools:duplicate:label").'</p>';
	$result .= elgg_echo("group_tools:duplicate:name");
	$result .= elgg_view("input/hidden", array("name" => "group_guid", "value" => $group->getGUID()));
	$result .= elgg_view("input/text", array("name" => "new_name"));
	$result .= elgg_view("input/submit", array("value" => elgg_echo("group_tools:duplicate:submit")));

	$body = elgg_view("input/form", array("body" => $result,
											"action" => $vars["url"] . "action/group_tools/duplicate",
											"id" => "group_tools_duplicate_form"));
										
	echo elgg_view_module("info", $title, $body);
}
