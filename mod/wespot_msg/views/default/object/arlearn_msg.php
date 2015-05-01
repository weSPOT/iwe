<?php
/**
 * View for arlearn_msg object
 *
 * @uses $vars['entity']
 * @uses $vars['full_view'] 
 */

$full    = elgg_extract('full_view', $vars, FALSE);
$message = elgg_extract('entity', $vars, FALSE);

$owner = get_entity($message->owner_guid);
// hack for ARLearn Service bug
if (!$owner) {
    return true;
}
$icon       = elgg_view_entity_icon($owner, 'tiny');
$owner_link = "<a href=\"{$owner->getURL()}\">$owner->name</a>";

$menu = elgg_view_menu('entity', array(
    'entity' => $vars['entity'],
    'sort_by' => 'priority',
    'class' => 'elgg-menu-hz float-alt'
));

$text = elgg_view("output/longtext", array(
    "value" => html_entity_decode($message->body, ENT_NOQUOTES, 'UTF-8')
));

$friendlytime = elgg_view_friendly_time($message->post_date / 1000);

$meta_class = 'mbn';
if (elgg_is_logged_in() && (elgg_get_logged_in_user_guid() == $message->owner_guid)) {
	$meta_class .= ' me';
}

$body = <<<HTML
<div class="$meta_class">
        $menu
        $owner_link
        <span class="elgg-subtext" data-date="$message->post_date">
                $friendlytime
        </span>
        $text
</div>
HTML;

echo elgg_view_image_block($icon, $body);
