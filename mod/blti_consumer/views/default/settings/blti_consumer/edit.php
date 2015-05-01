<?php
/**
 *
 */
$insert_view = elgg_view('blti_consumer/extend');

$organizationid_string = elgg_echo('blti_consumer:organizationid');
$organizationid_view = elgg_view('input/text', array(
	'internalname' => 'params[organizationid]',
	'value' => $vars['entity']->organizationid,
	'class' => 'text_input',
));



$settings = <<<__HTML
<div id="blti_consumer_site_settings">
	<div>$insert_view</div>
	<div>$organizationid_string $organizationid_view</div>
</div>
__HTML;

echo $settings;
