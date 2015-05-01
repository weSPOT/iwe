
<?php
/**
 * Elgg sidebar contents
 *
 * @uses $vars['sidebar'] Optional content that is displayed at the bottom of sidebar
 */
?>
	

<?php
/*
echo elgg_view_menu('extras', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));
*/

if (elgg_get_context() != 'thewire')  {
	$text = '<!-- AddThis Button BEGIN -->';
	$text .='<div class="addthis_toolbox addthis_default_style">';	
	   
	$text .= '<a class="addthis_button_compact">&nbsp;' . elgg_echo('sw_sharer:share') . '&nbsp;</a>';
	$text .= '<a class="addthis_button_email">&nbsp;' . elgg_echo('sw_sharer:email') . '&nbsp;</a>';
	$text .= '<a class="addthis_button_print">&nbsp;' . elgg_echo('sw_sharer:print') . '&nbsp;</a>';
	$text .= '</div>
';
	$text .= '<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4f13c4d424ee2b29"></script>';
	$text .= '<!-- AddThis Button END -->&nbsp;';
	echo $text;
}

echo elgg_view('page/elements/owner_block', $vars);

echo elgg_view_menu('page', array('sort_by' => 'name'));

// optional 'sidebar' parameter
if (isset($vars['sidebar'])) {
	echo $vars['sidebar'];
}

// @todo deprecated so remove in Elgg 2.0
// optional second parameter of elgg_view_layout
if (isset($vars['area2'])) {
	echo $vars['area2'];
}

// @todo deprecated so remove in Elgg 2.0
// optional third parameter of elgg_view_layout
if (isset($vars['area3'])) {
	echo $vars['area3'];
}