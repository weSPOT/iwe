<?php
$img = $CONFIG->url . "/mod/wespot_fca/img/icon.png";
$group = elgg_get_page_owner_entity();
$options = array (
    'type' => 'object',
    'subtype' => 'file',
    'owner_guid' => get_entity ( $inquiryId )->owner_guid,
    'container_guid' => $inquiryId 
);
?>
<h3 style="text-align:center;">
<a href="/badges/main?gid=<?php echo $group->guid; ?>&name=<?php echo $group->name; ?>&uid=<?php echo $group->owner_guid; ?>">
<?php echo elgg_echo('badges') ?></a></h3>