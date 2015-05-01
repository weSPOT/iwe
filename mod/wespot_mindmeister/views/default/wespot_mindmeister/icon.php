<?php
/**
 * Page icon
 *
 * Uses a separate icon view due to dependency on annotation
 *
 * @uses $vars['entity']
 * @uses $vars['annotation']
 */

$annotation = $vars['annotation'];
$entity = get_entity($annotation->entity_guid);

if ($annotation) {

	// Get size
	if (!in_array($vars['size'], array('small', 'medium', 'large', 'tiny', 'master', 'topbar'))) {
		$vars['size'] = "medium";
	}

	$size = elgg_strtolower($vars['size']);
	$type = $entity->getType();
	$params = array(
		'entity' => $entity,
		'size' => $size,
	);

	$url = elgg_trigger_plugin_hook('entity:icon:url', $type, $params, null);
	if ($url == null) {
		$url = "_graphics/icons/default/$size.png";
	}

?>
<a href="<?php echo $annotation->getURL(); ?>">
	<img src="<?php echo elgg_normalize_url($url); ?>" />
</a>
<?php } ?>