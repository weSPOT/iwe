<?php if(elgg_is_logged_in() && (is_group_member(elgg_get_page_owner_entity()->guid, elgg_get_logged_in_user_guid ()))){

$group = elgg_get_page_owner_entity();
$isOwner = false;
$operators= get_group_operators($group);
$globalUserId = elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login');

foreach ($operators as $op){
	if ($op->getGUID()==elgg_get_logged_in_user_guid()){
		$isOwner = true;
	}
}

//elgg_log("isOwner ".$isOwner, 'DEBUG');
 	
if (!$isOwner) {?>	
<?php $basedir = $CONFIG->url . "/mod/wespot_medoky/"; ?>
<link type="text/css" href="<?php echo $basedir; ?>css/smoothness/jquery-ui.css" rel="Stylesheet" />
<link type="text/css" href="<?php echo $basedir; ?>css/medoky.css" rel="Stylesheet" />

<script src="<?php echo $basedir; ?>js/jquery-create.js"></script>
<script src="<?php echo $basedir; ?>js/medoky.js"></script>

<script type="text/javascript">
$(function(){
  if(medoky_backend.init("<?php echo $GLOBALS['server']."/MEDoKyService/rest/"; ?>")){
	logServer_backend.init("<?php echo $GLOBALS['server']."/weSpotLogServer/rest/v1/contentData/"; ?>");  
	console.log("userId in sidebar php = "+"<?php echo $globalUserId; ?>");
    medoky_ui.prepareDialogs("<?php echo $globalUserId; ?>");
    medoky.fetchRecommendations(medoky.resetView);
    setInterval(medoky.pollRecommendations, 150000);
  }
 });
</script>

<div class="elgg-module  elgg-module-aside elgg-menu-owner-block medoky_main" id="medoky_main">
  <div id="medoky_recommendation_title" class="elgg-head medoky_main" style="display:none">
    <h3 class="medoky_main"><?php echo elgg_echo('wespot_medoky:title');?></h3>
  </div>
  <ul class="elgg-menu medoky_main" id="medoky_sidebar_recommendations">
    <li id="medoky_sidebar_recommendations_LearningActivity"></li>
    <li id="medoky_sidebar_recommendations_LearningPeer"></li>
    <li id="medoky_sidebar_recommendations_LearningResource"></li>
  </ul>
</div>
<div id="dia_medoky_detail" class="medoky_main">
  <div id="medoky_recommmendation_detail_header" class="medoky_main"></div>
  <div id="medoky_recommendation_detail_top3" class="medoky_main"></div>
  <div id="medoky_recommendation_detail_footer" class="medoky_main"></div>
</div>

<input type="hidden" id="resourceInstruction" value="<?php echo elgg_echo('wespot_medoky:resource:instruction') ?>" />
<input type="hidden" id="activityInstruction" value="<?php echo elgg_echo('wespot_medoky:activity:instruction') ?>" />
<input type="hidden" id="peerInstruction" value="<?php echo elgg_echo('wespot_medoky:peer:instruction') ?>" />

<!--
  <a href="<?php echo $CONFIG->url?>medoky">MEDOKY</a>
    -->
<?php }}?>