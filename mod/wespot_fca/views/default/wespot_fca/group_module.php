<?php
/**
 * Group FCA
 *
 */
$img = $CONFIG->url . "/mod/wespot_fca/img/icon.png";
$group = elgg_get_page_owner_entity ();

if ($group->fca_enable == "no") {
  return true;
}

$all_link = elgg_view ( 'output/url', array (
    // 'href' => "wespot_fca/main/$group->guid/all",
    // 'text' => elgg_echo('link:view:all'),
    'text' => '',
    'is_trusted' => true 
) );

elgg_push_context ( 'widgets' );
$options = array (
    'type' => 'object',
    'subtype' => 'fca_top',
    'container_guid' => elgg_get_page_owner_guid (),
    'limit' => 6,
    'full_view' => false,
    'pagination' => false 
);
$content = elgg_list_entities ( $options );
elgg_pop_context ();

if (! $content) {
  // $content = '<p>' . elgg_echo('wespot_fca:none') . '</p>';
  $content = '<p style="text-align:center;"><img src=' . $img . '></p><hr>'; // .echo htmlspecialchars(json_encode($group));
}

$new_link = elgg_view ( 'output/url', array (
    'href' => "/fca/main?gid=$group->guid&name=$group->name&uid=$group->owner_guid",
    'text' => '<h3 style="text-align:center;">' . elgg_echo ( 'wespot_fca:launch' ) . '</h3>',
    'is_trusted' => true 
) );

echo elgg_view ( 'groups/profile/module', array (
    'title' => elgg_echo ( 'wespot_fca:group' ),
    'content' => $content,
    'all_link' => $all_link,
    'add_link' => $new_link 
) );
