<?php
$basedir = $CONFIG->url . "/mod/wespot_badges/";
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
$widget = $vars['entity'];
$inquiryserver = str_replace("http://", "", elgg_get_site_url());
$inquiryserver = str_replace("/", "", $inquiryserver);
if ($_GET['uid'] == elgg_get_logged_in_user_guid()) {
?>
	<div>
	<iframe width="100%" height="800px" src="http://openbadgesapi.appspot.com/menu.jsp?userid=<?php echo strtolower(elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login'))?>&context=<?php echo $_GET['gid']?>&inquiryserver=<?php echo $inquiryserver?>"></iframe>
	</div>
<?php
}else{
?>
	<div>
	<iframe width="100%" height="800px" src="http://openbadgesapi.appspot.com/listAwardedBadgesPerUser.jsp?userid=<?php echo strtolower(elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login'))?>&context=<?php echo $_GET['gid']?>&inquiryserver=<?php echo $inquiryserver?>"></iframe>
	</div>
<?php	
}
?>
