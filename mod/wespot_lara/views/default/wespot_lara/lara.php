<?php
$uid = elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login');
$uid = strtolower($uid);
$provider = "";
$inquiry_id = elgg_get_page_owner_guid();
if (strpos($uid,'google') !== false) {
    $provider="google";
}elseif (strpos($uid,'facebook') !== false) {
    $provider="facebook";
}elseif (strpos($uid,'linkedin') !== false) {
    $provider="linkedin";
}elseif (strpos($uid,'wespot') !== false) {
    $provider="wespot";
}

$providers = array("google_", "facebook_", "linkedin_","wespot_");
$uid = str_replace($providers, "", $uid);
$group = elgg_get_page_owner_entity();
$url = 'http://ariadne.cs.kuleuven.be/wespot/dashboard_v2/'.$uid.'/'.$provider.'/'.$group->guid;
$link = '<div class="left mts"><a href="'.$url.'" target="_blank">'.elgg_echo('lara').'</a></div>';
echo $link;