<?php
/**
 * Page edit form body
 *
 * @package ElggPages
 */

echo elgg_echo("hypothesis:instruction");
echo("<br><br>");

$variables = elgg_get_config('hypothesis');
$user = elgg_get_logged_in_user_entity();
$entity = elgg_extract('entity', $vars);
$can_change_access = true;
if ($user && $entity) {
	$can_change_access = ($user->isAdmin() || $user->getGUID() == $entity->owner_guid);
}

foreach ($variables as $name => $type) {
	// don't show read / write access inputs for non-owners or admin when editing
	if (($type == 'access' || $type == 'write_access') && !$can_change_access) {
		continue;
	}

	// don't show parent picker input for top or new pages.
	if ($name == 'parent_guid' && (!$vars['parent_guid'] || !$vars['guid'])) {
		continue;
	}

	if ($type == 'parent') {
		$input_view = "pages/input/$type";
	} else {
		$input_view = "input/$type";
	}

?>
<div>
	<label><?php echo elgg_echo("hypothesis:$name") ?></label>
	<?php
		if ($type != 'longtext') {
			echo '<br />';
		}

		echo elgg_view($input_view, array(
			'name' => $name,
			'value' => $vars[$name],
			'entity' => ($name == 'parent_guid') ? $vars['entity'] : null,
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
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'page_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));
if (!$vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	));
}

echo elgg_view('input/hidden', array('name' => 'phase', 'value' => $_GET['phase']));
echo elgg_view('input/hidden', array('name' => 'activity_id', 'value' => $_GET['activity_id']));

echo elgg_view('input/submit', array('value' => elgg_echo('save')));

echo '</div>';
