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
?>
<div>
<iframe width="100%" height="400px" src="http://ariadne.cs.kuleuven.be/wespot/dashboard_v2/<?php echo $uid; ?>/<?php echo $provider; ?>/<?php echo $_GET['gid']?>/"></iframe>
</div>