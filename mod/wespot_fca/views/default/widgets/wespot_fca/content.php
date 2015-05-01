<?php
$img = $CONFIG->url . "/mod/wespot_fca/img/icon.png";
$group = elgg_get_page_owner_entity ();
?>

<p style="text-align: center;">
  <img src="<?php echo $img?>" style="width: 100%; height: 100%;">
</p>
<?php if(is_group_member($group->guid, elgg_get_logged_in_user_guid ())):?>
<hr>
<h3 style="text-align: center;">
  <a href="/fca/main?gid=<?php echo $group->guid ?>&name=<?php echo $group->name?>&uid=<?php echo $group->owner_guid ?>">
    <?php echo elgg_echo('wespot_fca:launch')?></a>
</h3>
<?php endif;?>