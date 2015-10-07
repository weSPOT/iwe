<?php

elgg_load_library('elgg:group_operators');

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
$inquiryserver = str_replace("http://", "", elgg_get_site_url());
$inquiryserver = str_replace("/", "", $inquiryserver);
$group = elgg_get_page_owner_entity();
$admins = get_group_operators($group);

$useradmin = false;

foreach ($admins as &$admin) {
    if ($admin->guid == elgg_get_logged_in_user_guid()){
		$useradmin=true;
	}
}

if ($useradmin==true)
	$url='http://openbadgesapi.appspot.com/menu.jsp?userid='.strtolower(elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login')).'&context='.$group->guid.'&inquiryserver='.$inquiryserver;
else
	$url='http://openbadgesapi.appspot.com/listAwardedBadgesPerUser.jsp?userid='.strtolower(elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login')).'&context='.$group->guid.'&inquiryserver='.$inquiryserver;
$link = '<div class="left mts"><a href="'.$url.'" target="_blank">'.elgg_echo('badges').'</a></div>';
echo $link;