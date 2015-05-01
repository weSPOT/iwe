<?php
/**
 * Elgg pages widget
 *
 * @package ElggPages
 */

$num = (int) $vars['entity']->pages_num;

$options = array(
	'type' => 'object',
	'subtype' => 'blti_consumer',
	'limit' => $num,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$content = elgg_list_entities($options);

echo $content;

if ($content) {
	$url = $CONFIG->wwwroot . 'blti_consumer/listing';
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('blti_consumer:tools_blti:more'),
		'is_trusted' => true,
		'target' => "_blank",
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else 
	echo elgg_echo('blti_consumer:register:none');

