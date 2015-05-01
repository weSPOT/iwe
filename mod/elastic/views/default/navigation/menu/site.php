<?php
/**
 * Site navigation menu
 *
 * @uses $vars['menu']['default']
 * @uses $vars['menu']['more']
 */

$default_items = elgg_extract('default', $vars['menu'], array());
//$more_items = elgg_extract('more', $vars['menu'], array());

//$default_items = array_merge($default_items, $more_items);
$more_items = false;

echo '<ul class="elastic-menu clearfix">';
foreach ($default_items as $menu_item) {
	echo elgg_view('navigation/menu/elements/site_item', array('item' => $menu_item));
}

if ($more_items) {
	echo '<li class="">';

	$more = elgg_echo('more');
	echo "<a href=\"#\">$more</a>";
	
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu elgg-menu-site elgg-menu-site-more', 
		'items' => $more_items,
	));
	
	echo '</li>';
}
echo '</ul>';
