<?php
/**
 * Elgg tag input
 * Displays a tag input field
 *
 * @uses $vars['disabled']
 * @uses $vars['class']    Additional CSS class
 * @uses $vars['value']    Array of tags or a string
 * @uses $vars['entity']   Optional. Entity whose tags are being displayed (metadata ->tags)
 */
elgg_load_css ( 'medoky_tags' );
elgg_load_css ( 'jquery.multiselect' );
elgg_load_js ( 'jquery_create' );
elgg_load_js ( 'medoky_tags' );
elgg_load_js ( 'jquery.multiselect');

if (isset ( $vars ['class'] )) {
  $vars ['class'] = "elgg-input-tags {$vars['class']}";
} else {
  $vars ['class'] = "elgg-input-tags";
}

$defaults = array (
  'value' => '',
  'disabled' => false 
);

if (isset ( $vars ['entity'] )) {
  $defaults ['value'] = $vars ['entity']->tags;
  unset ( $vars ['entity'] );
}

$vars = array_merge ( $defaults, $vars );

if (is_array ( $vars ['value'] )) {
  $tags = array ();
  
  foreach ( $vars ['value'] as $tag ) {
    if (is_string ( $tag )) {
      $tags [] = $tag;
    } else {
      $tags [] = $tag->value;
    }
  }
  
  $vars ['value'] = implode ( ", ", $tags );
}

//$userId = elgg_get_logged_in_user_guid(); 
$userId=elgg_get_plugin_user_setting('uid', elgg_get_logged_in_user_guid(), 'elgg_social_login');
$inquiryId = elgg_get_page_owner_guid();
//production server: http://css-kti.tugraz.at:8080/ 
//dev server: http://css-kti.tugraz.at/

$baseurl = 'http://css-kti.tugraz.at:8080/MEDoKyService/rest/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseurl.'getTagRecommendations/userId/'.$userId.'/courseId/'.$inquiryId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
//var_dump($output);
curl_close($ch);
echo "<script>medoky_backend.init('".$baseurl."');</script>";
//$response = file_get_contents($baseurl.'getTagRecommendations/userId/'.$userId.'/courseId/'.$inquiryId);
//echo $response;
//var_dump($response);
//echo "<script>console.log('".$baseurl."getTagRecommendations/userId/".$userId."/courseId/".$inquiryId."')</script>";
//echo "<script>console.log('test')</script>";
$response = json_decode($output);
// echo "<script>console.log('".$response."')</script>";
// $response = file_get_contents('http://css-kti.tugraz.at/MEDoKyService/rest/getTagRecommendations/userId/weSPOT_test/courseId/43096');
// echo $response;
// var_dump($response);
// echo "<script>console.log('".$response."')</script>";
$tags = $response->{'recommendations'};
$rec_algo = $response->{'algorithm'};
$has_features = $response->{'hasFeatures'};
echo "<script>console.log('recommended algo ".$rec_algo."has features".$has_features."');</script>";

$basedir = $CONFIG->url . "/mod/wespot_tags/";
?>

<?php if ($has_features) { ?>
<div>
<label for="medoky_tags_select"><?php echo elgg_echo('wespot_semantics:label'); ?></label>
<select multiple="multiple" id="attribute-select" name="attribute-select">
<?php foreach($tags as $tag) {?>
      <option value='<?php echo $tag;?>'><?php echo $tag;?></option>
<?php } ?>
</select>
</div>

<script type="text/javascript">
//query-ui-multiselect-widget 

 $("#attribute-select").multiselect({
	   selectedText: "<?php echo elgg_echo('wespot_semantics:numSelected');?>",
	   header: false,
	   noneSelectedText: "<?php echo elgg_echo('wespot_semantics:select'); ?>",	   
	   close: function(event, ui){
              var values = $("#attribute-select").multiselect("getChecked").map(function(){
               return this.value; }).get();  
        medoky_backend.getTagRecommendationPerAlgorithm("<?php echo $userId ?>", "<?php echo $inquiryId ?>", "<?php echo $rec_algo ?>", values , medoky_tags.add_recTags);
	   } 	   
 }); 
</script>


<?php } ?>

<input type="hidden" id="rec_algo" name="tag_recommender_algorithm" <?php  $recalgo ['value'] = $rec_algo; echo elgg_format_attributes($recalgo); ?> />
<input type="hidden" id="recommended_tags" name="recommended_tags" <?php  $rectags ['value'] = implode ( ", ", $tags ); echo elgg_format_attributes($rectags); ?> />
<input type="hidden" id="sel_features" name="sel_features" value="" />
<input type="text" id="input_tags" <?php echo elgg_format_attributes($vars); ?> />


<?php if (is_array ( $tags) && sizeof($tags)>0 && !$has_features) { ?>
<div>
<label for="medoky_tags_select"><?php echo elgg_echo('wespot_tags:label'); ?></label>
<?php foreach($tags as $tag) {?>
<div class="medoky_tag" height="16px" width="16px" type="image" onclick="medoky_tags.select_tag(this)"><?php echo $tag;?></div>
<?php } ?>
</div>
<?php } ?>

<?php if ($has_features) { ?>
<div  id="meritsTags" style="display:none;">
</div>
<?php } ?>
