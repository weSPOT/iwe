<?php
/**
 * ARLearn Message Board index page
 *
 * @package MessageBoard
 */
elgg_load_library('elgg:wespot_arlearnservices');

$group = elgg_get_page_owner_entity();
$threadId = get_input('threadId');

// sync with ARLearn 
wespot_msg_sync_messages($group, $threadId);

elgg_push_breadcrumb($group->name, $group->getURL());

$title  = elgg_echo('wespot_msg:owner', array(
    $group->name
));
$mb_url = '';

elgg_push_breadcrumb(elgg_echo('wespot_msg:board'), $mb_url);

$options = array(
    'type' => 'object',
    'subtype' => 'arlearn_msg',
    'container_guids' => $group->getGUID(),
    'metadata_names' => array('threadId'),
    'metadata_values' => array($threadId),
    'order_by_metadata' => array('name' => 'post_date', 'direction' => DESC, 'as' => integer),
    'limit' => 10,
    'full_view' => true,
    'view_type_toggle' => false,
    'pagination' => true
);

$messages = elgg_list_entities_from_metadata($options);

if (empty($messages)) {
    $messages = elgg_echo('wespot_msg:none');
} 

$vars = array(
    'filter' => false,
    'content' => $messages,
    'title' => $title,
    'reverse_order_by' => false
);

$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);
