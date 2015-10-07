<?php
gatekeeper(); //only logged in users can see this
$title="MICI";
$content = elgg_view("view");
// layout the page
$body = elgg_view_layout('one_column', array(
   'content' => $content
));
echo elgg_view_page($title, $body);

?>
