<?php
/**
 * MindMeister map widget
 */

//print_r($vars);
$num = (int) $vars['entity']->wespot_mindmeister_num;

$options = array(
	'type' => 'object',
	'subtype' => 'mindmeistermap',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$content = elgg_list_entities($options);
echo $content;

if ($content) {
	$url = "wespot_mindmeister/group/" . elgg_get_page_owner_entity()->getGUID();
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('wespot_mindmeister:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('wespot_mindmeister:none');
    if (elgg_get_page_owner_entity()->canWriteToContainer())
    {
		echo "<br><br>";
		echo elgg_view('output/url', array(
			  'href' => "wespot_mindmeister/add/".$vars['entity']->owner_guid,
			  'text' => elgg_echo('wespot_mindmeister:add'),
			  'is_trusted' => true,
		  ));
	}
}
