<?php
/**
 * ARLearn Message Board add form body
 *
 * @package MessageBoard
 */

echo elgg_view('input/plaintext', array(
    'name' => 'message_content',
    'class' => 'wespot_msg-input mbs'
));

echo elgg_view('input/hidden', array(
    'name' => 'owner_guid',
    'value' => elgg_get_page_owner_guid()
));

echo elgg_view('input/hidden', array(
    'name' => 'threadId',
    'value' => $vars['threadId']
));

echo elgg_view('input/hidden', array(
    'name' => 'num_display',
    'value' => $vars['num_display']
));

echo elgg_view('input/hidden', array(
    'name' => '__elgg_wespot_msg_channel_token',
    'value' => $vars['channel_token']
));

echo elgg_view('input/submit', array(
    'value' => elgg_echo('post')
));
