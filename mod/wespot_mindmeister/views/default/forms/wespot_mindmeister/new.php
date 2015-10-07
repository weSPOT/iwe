<?php
/**
 * Page edit form body
 */

$variables = elgg_get_config('wespot_mindmeister');

foreach ($variables as $name => $type) {
?>
 <?php
   if ($type == 'access' || $type == 'write_access') {
     echo "<div class='access_selector'>";
   } else {
     echo '<div>';
   }
 ?>
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
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'mindmap_guid',
		'value' => $vars['guid'],
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

echo elgg_view('input/submit', array('value' => elgg_echo('wespot_mindmeister:createnew')));

echo '</div>';
