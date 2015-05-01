<?php
/**
 *
 */
$insert_view = elgg_view('blti_consumer/extend');

$organizationid_string = elgg_echo('blti_consumer:organizationid');
$organizationid_view = elgg_view('input/text', array(
	'name' => 'params[organizationid]',
	'value' => $vars['entity']->organizationid,
	'class' => 'text_input',
));


?>
<div id="blti_consumer_site_settings">
	<div><?php echo $insert_view ?></div>
	<div><?php echo $organizationid_string.$organizationid_view?></div>
</div>

