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
elgg_load_js ( 'jquery_create' );
elgg_load_js ( 'medoky_tags' );

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
$response = file_get_contents('http://css-kti.tugraz.at:8080/MEDoKyService/rest/getTagRecommendations/userId/'.$userId.'/courseId/'.$inquiryId);
echo "<script>console.log('http://css-kti.tugraz.at:8080/MEDoKyService/rest/getTagRecommendations/userId/".$userId."/courseId/".$inquiryId."')</script>";
//$response = file_get_contents('http://css-kti.tugraz.at/MEDoKyService/rest/getTagRecommendations/userId/'.$userId.'/courseId/'.$inquiryId);
//echo "<script>console.log('http://css-kti.tugraz.at/MEDoKyService/rest/getTagRecommendations/userId/".$userId."/courseId/".$inquiryId."')</script>"; 

//$response = file_get_contents('http://192.168.222.30:8080/MEDoKyService/restt/getTagRecommendations/userId/'.$userId.'/courseId/'.$inquiryId);

// NEW Server: 
//$response = file_get_contents('http://css-kti.tugraz.at:8080/MEDoKyService/rest/getTagRecommendations/userId/'.$userId.'/courseId/'.$inquiryId);


$response = json_decode($response);
$tags = $response->{'recommendations'};
$rec_algo = $response->{'algorithm'};
//echo "<script>console.log('recommended algo ".$rec_algo."')</script>"; 
$basedir = $CONFIG->url . "/mod/wespot_tags/";
?>

<input type="hidden" id="rec_algo" name="tag_recommender_algorithm" <?php  $recalgo ['value'] = $rec_algo; echo elgg_format_attributes($recalgo); ?> />
<input type="hidden" id="recommended_tags" name="recommended_tags" <?php  $rectags ['value'] = implode ( ", ", $tags ); echo elgg_format_attributes($rectags); ?> />
<input type="text" id="input_tags" <?php echo elgg_format_attributes($vars); ?> />

<?php if (is_array ( $tags) && sizeof($tags)>0) { ?>
<div>
<label for="medoky_tags_select"><?php echo elgg_echo('wespot_tags:label'); ?></label>
<?php foreach($tags as $tag) {?>
<div class="medoky_tag" height="16px" width="16px" type="image" onclick="medoky_tags.select_tag(this)"><?php echo $tag;?></div>
<?php } ?>
</div>
<?php } ?>
