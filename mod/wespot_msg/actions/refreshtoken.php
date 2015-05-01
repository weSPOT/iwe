<?php
elgg_load_library('elgg:wespot_arlearnservices');
elgg_load_library('elgg:wespot_arlearnmsgservices');
elgg_load_library('elgg:wespot_arlearn');
elgg_load_library('elgg:wespot_msg');

$token = wespot_msg_get_channel_token(elgg_get_logged_in_user_entity(), true);
echo json_encode(array('__elgg_wespot_msg_channel_token' => $token));
