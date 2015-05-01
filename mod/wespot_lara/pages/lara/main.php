<?php
gatekeeper(); //only logged in users can see this

$title = elgg_echo('lara');
$content = elgg_view("lara_iframe");
// layout the page
// $body = elgg_view_layout('one_column', array(
//    'content' => $content
// ));

elgg_set_page_owner_guid($_GET['gid']);
$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

?>
