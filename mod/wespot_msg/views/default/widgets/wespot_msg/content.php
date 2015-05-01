<?php
/**
 * Elgg wespot_msg widget view
 *
 */

set_context('wespot_msg');

elgg_load_js('google_channel');
elgg_load_js('elgg.wespot_msg.channel');
elgg_load_js('elgg.wespot_msg');
elgg_load_js('elgg.wespot_msg.infinite_scroll');
elgg_load_js('jquery-waypoints');
elgg_load_js('elgg.wespot_msg.infinite_scroll.automatic_pagination');
elgg_load_js('jquery-viewport');
	
elgg_load_library('elgg:wespot_arlearn');
elgg_load_library('elgg:wespot_msg');

$group = elgg_get_page_owner_entity();
wespot_msg_sync_messages($group, $vars['entity']->threadId);


$offset = $vars['offset'];

$metadata_name_value_pair = array();
$metadata_name_value_pair[] = array('name'=>'threadId', 'value'=>$vars['entity']->threadId, 'operand' => '=');
if (!empty($offset)) {
  $metadata_name_value_pair[] = array('name'=>'post_date','value'=>$offset,'operand'=>'>');
}
$num_display = $vars['entity']->num_display;
$options = array(
    'type' => 'object',
    'subtype' => 'arlearn_msg',
    'container_guid' => $group->getGUID(),
    'metadata_name_value_pair' => $metadata_name_value_pair,
    'order_by_metadata' => array('name' => 'post_date', 'direction' => DESC, 'as' => integer),
    'limit' => $num_display,
    'offset' => 0,
    'full_view' => true,
    'view_type_toggle' => false,
    'reverse_order_by' => false,
    'pagination' => true
);

$messages = elgg_get_entities_from_metadata($options);

$defaults = array(
    'items' => array_reverse($messages),
    'list_class' => 'elgg-list elgg-list-entity',
    'full_view' => true
);

echo '<div class="elgg-widget-content-wespot_msg">';

echo '<div class="elgg-infinite-scroll-bottom">';
echo '<a href="" class="elgg-button">'.elgg_echo('infinite_scroll:load_more').'</a>';
echo '</div>';

//echo elgg_view('page/components/list', $defaults);
if ($group instanceof ElggGroup && !empty($messages)) {
  $url = 'wespot_msg/group/'.$group->getGUID().'/'.$vars['entity']->threadId.'/all';
  echo elgg_view('output/url', array(
    'href' => $url,
    'text' => elgg_echo('wespot_msg:loadmore'),
    'is_trusted' => true,
    'class' => 'wespot_msg-pagination hidden'
  ));
}

echo elgg_view('page/components/list', $defaults);

echo '<a href="#" class="wespot_msg-new-post-notify" style="display: none"><span class="wespot_msg-arr-up"></span> '.elgg_echo('wespot_msg:new_message').'</a>';
echo '</div>';

if (elgg_is_logged_in() && can_write_to_container(0, $group->getGUID())) {
    $channel_token = wespot_msg_get_channel_token(elgg_get_logged_in_user_entity());
    echo elgg_view_form('wespot_msg/add', array(
        'name' => 'elgg-wespot_msg'
    ), array('threadId' => $vars['entity']->threadId, 'num_display' => $vars['entity']->num_display, 'channel_token' => $channel_token));
}

//echo '<script>elgg.wespot_msg.infinite_scroll.automatic_pagination.init();</script>';
