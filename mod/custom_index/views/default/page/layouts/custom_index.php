<?php
/**
 * Elgg custom index layout
 * 
 * You can edit the layout of this page with your own layout and style. 
 * Whatever you put in this view will appear on the front page of your site.
 * 
 */

$mod_params = array('class' => 'elgg-module-highlight');

?>

<div class="custom-index elgg-main elgg-grid clearfix">
	<div class="elgg-col elgg-col-1of2">
		<div class="elgg-inner pvm prl">
<?php
// left column

// Top box for login or welcome message
if (elgg_is_logged_in()) {
	$top_box = "<h2>" . elgg_echo("welcome") . " ";
	$top_box .= elgg_get_logged_in_user_entity()->name;
	$top_box .= "</h2>";
	$top_box .= "<p><b>THIS SITE IS FOR TESTING PURPOSES ONLY</b></p>";
} else {
	$top_box = $vars['login'];
}
echo elgg_view_module('featured',  '', $top_box, $mod_params);

// a view for plugins to extend
echo elgg_view("index/lefthandside");

if (elgg_is_logged_in()) {
	
	// groups
	if (elgg_is_active_plugin('groups')) {
		echo elgg_view_module('featured',  elgg_echo("groups:yours"), $vars['user_groups'], $mod_params);
		echo elgg_view_module('featured',  elgg_echo("custom:groups"), $vars['groups'], $mod_params);
	}
	
}
?>
		</div>
	</div>
	<div class="elgg-col elgg-col-1of2">
		<div class="elgg-inner pvm">
<?php
// right column

// a view for plugins to extend
echo elgg_view("index/righthandside");

if (elgg_is_logged_in()) {

	// members
	echo elgg_view_module('featured',  elgg_echo("custom:members"), $vars['members'], $mod_params);
	
	// activity
	echo elgg_view_module('featured',  elgg_echo("custom:activity"), $vars['activity'], $mod_params);
}
?>
		</div>
	</div>
</div>
