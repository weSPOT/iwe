<?php
/**
 * Answers plugin search and submit question form
 *
 */
echo '<h3 class="mbs">' . elgg_echo('answers:search_and_submit') . '</h3>';

echo elgg_view('input/text', array(
	'name' => 'body',
	'class' => 'mbm',
	'id' => 'answers-textarea',
));

echo elgg_view('input/hidden', array('id' => 'answers-phase', 'name' => 'phase', 'value' => $vars['phase']));
echo elgg_view('input/hidden', array('id' => 'answers-activity_id', 'name' => 'activity_id', 'value' => $vars['activity_id']));

?>

<div id="answers-characters-remaining">
	<span>140</span>&nbsp;<?php echo elgg_echo('answers:charleft'); ?>
</div>
<div id="answers-search-response"></div>