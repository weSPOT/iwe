<?php
/**
 * Elgg bookmarks widget
 *
 * @package Bookmarks
 */

$max = (int) $vars['entity']->num_display;

$options = array(
	'type' => 'object',
	'subtype' => 'bookmarks',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => $max,
	'full_view' => FALSE,
	'pagination' => FALSE,
);
$content = elgg_list_entities($options);

echo $content;

if ($content) {
	$url = "bookmarks/group/" . elgg_get_page_owner_entity()->getGUID();
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('bookmarks:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
} else {
	echo elgg_echo('bookmarks:none');
	echo "<br><br>";
	echo elgg_view('output/url', array(
          'href' => "bookmarks/add/".$vars['entity']->owner_guid,
          'text' => elgg_echo('bookmarks:add'),
          'is_trusted' => true,
      ));
}
