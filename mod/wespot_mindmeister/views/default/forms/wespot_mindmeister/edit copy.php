<?php
/**
 * Page edit form body
 */

elgg_load_js('elgg:wespot_mindmeister');
elgg_load_library('elgg:wespot_mindmeisterservices');

$guid = $vars['guid'];

$base_dir = elgg_get_site_url() . 'mod/wespot_mindmeister/images/';
$buttonright = elgg_normalize_url($base_dir.'arrow-right-blue.png');
$buttondown = elgg_normalize_url($base_dir.'arrow-down-blue.png');

echo '<span style="float:left; clearn:both;cursor: pointer;font-size:12pt;margin-bottom:10px;margin-top:10px;font-weight:bold;" onclick="toggleWespotMindMeisterDetails(\''.$guid.'\', \''.$buttonright.'\', \''.$buttondown.'\')">'.elgg_echo('wespot_mindmeister:editdetails').' <img style="vertical-align:middle" id="detailsdivbutton'.$guid.'" src="'.elgg_normalize_url($base_dir.'arrow-right-blue.png').'" border="0" /></span>';
echo '<fieldset id="detailsdiv'.$guid.'" style="float:left;clear:both;display:none;margin:0px;margin-bottom:10px;padding:0px"><legend style="font-weight:bold;">'.'</legend>';

$variables = elgg_get_config('wespot_mindmeister');

foreach ($variables as $name => $type) {
?>
<div>
	<label><?php echo elgg_echo("wespot_mindmeister:$name") ?></label>
	<?php
		if ($type != 'longtext') {
			echo '<br />';
		}
	?>
	<?php echo elgg_view("input/$type", array(
			'name' => $name,
			'value' => $vars[$name],
		));
	?>
</div>
<?php
}

$cats = elgg_view('input/categories', $vars);
if (!empty($cats)) {
	echo $cats;
}

echo '<div class="elgg-foot">';
if ($guid) {
	echo elgg_view('input/hidden', array(
		'name' => 'mindmap_guid',
		'value' => $guid,
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));
if ($vars['parent_guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo('save')));

echo '</div>';

echo '</fieldset>';

/** ADD THE MINDMEISTER MAP IFRAME ***/

//currently logged in user
$username = elgg_get_logged_in_user_entity()->username;

$mindmap = get_entity($guid);
$map_filename = $mindmap->map_filename;
$mindmap_guid = $mindmap->guid;

$src = editMindMeisterMapURL($mindmap_guid, $mindmap->getURL(), $mindmap->map_filename, $username);

echo '<div style="margin-top:20px;clear:both;float:left;font-size:12pt;">'.elgg_echo('wespot_mindmeister:mapsavemessage').'</div>';
$embedMindMeister= '<iframe style="clear:both;float:left;"  width=100% height="600" scrolloing="auto" ';
$embedMindMeister.= 'src="'.$src.'">';
$embedMindMeister.= '</iframe>';

echo $embedMindMeister;