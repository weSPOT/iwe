<?php
/**
 * Elgg topbar
 * The standard elgg top toolbar
 */

// Elgg logo
echo elgg_view_menu('elastic_top', array('sort_by' => 'priority', array('elgg-menu-hz')));
echo '<div class="clearfix"></div>';

//echo elgg_view('input/dropdown', elgg_view_menu('elastic_drop', array('sort_by' => 'priority', array('elgg-menu-hz'))));

echo elgg_view('page/elements/drop_menu');

// elgg tools menu
// need to echo this empty view for backward compatibility.
$content = elgg_view("navigation/topbar_tools");
if ($content) {
	elgg_deprecated_notice('navigation/topbar_tools was deprecated. Extend the topbar menus or the page/elements/topbar view directly', 1.8);
	echo $content;
}
