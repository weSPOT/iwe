<?php

$id = 'widget-title-' . $vars['entity']->guid;

// title input
$options = array(
	'name' => 'params[title]',
	'value' => $vars['entity']->title,
	'id' => $id,
);

echo elgg_echo("golab_widget:title");
echo elgg_view('input/text', $options) . "<br><br>";


// url input
$options = array(
	'name' => 'params[url]',
	'value' => htmlentities($vars['entity']->url),
//	'onblur' => "this.value='x'+this.value.substr(1)",
);

echo elgg_echo('golab_widget:url');
echo elgg_view('input/text', $options) . "<br><br>";


// height
$options = array(
	'name' => 'params[height]',
	'value' => $vars['entity']->height,
);

echo elgg_echo('golab_widget:height');
echo elgg_view('input/text', $options) . "<br><br>";

echo elgg_echo('golab_widget:where');
?>


<script>
// live update of the widget title
$(document).ready( function() {
	$("#<?php echo $id; ?>").keyup( function() {
		var title = $("#<?php echo $id; ?>").val();
		$("#elgg-widget-<?php echo $vars['entity']->guid; ?> .elgg-widget-handle h3").text(title);
	});
});
</script>