<?php
/**
 *
 */
$consumer_key_string = elgg_echo('blti_consumer:consumer_key');
$consumer_key_view = elgg_view('input/text', array(
	'name' => 'params[consumer_key]',
	'value' => $vars['entity']->consumer_key,
	'class' => 'text_input',
));

$consumer_secret_string = elgg_echo('blti_consumer:consumer_secret');
$consumer_secret_view = elgg_view('input/text', array(
	'name' => 'params[consumer_secret]',
	'value' => $vars['entity']->consumer_secret,
	'class' => 'text_input',
));

$sendName_string = elgg_echo('blti_consumer:sendName');
$sendName_view = 
		"<input type=\"hidden\" name=\"params[sendName]\" value=\"0\" />
			<label><input type=\"checkbox\" name=\"params[sendName]\" value=\"1\" $checked />"
			. '</label>
			';

//TODO Fer el checked
//$checked = (twitterservice_can_tweet($plugin, $user_guid)) ? 'checked = checked' : '';

$sendEmail_string = elgg_echo('blti_consumer:sendEmail');
// can't use input because it doesn't work correctly for sending a single checkbox.
$sendEmail_view = 
		"<input type=\"hidden\" name=\"params[sendEmail]\" value=\"0\" />
			<label><input type=\"checkbox\" name=\"params[sendEmail]\" value=\"1\" $checked />"
			. '</label>
			';
//TODO Fer el checked
//$checked = (twitterservice_can_tweet($plugin, $user_guid)) ? 'checked = checked' : '';
			
$sendProfileDetails_string = elgg_echo('blti_consumer:sendProfileDetails');
// can't use input because it doesn't work correctly for sending a single checkbox.
$sendProfileDetails_view = 
		"<input type=\"hidden\" name=\"params[sendProfileDetails]\" value=\"0\" />
			<label><input type=\"checkbox\" name=\"params[sendProfileDetails]\" value=\"1\" $checked />"
			. '</label>
			';
			
$debug_string = elgg_echo('blti_consumer:debugLaunch');
// can't use input because it doesn't work correctly for sending a single checkbox.
$debug_view = 
		"<input type=\"hidden\" name=\"params[debugLaunch]\" value=\"0\" />
			<label><input type=\"checkbox\" name=\"params[debugLaunch]\" value=\"1\" $checked />"
			. '</label>
			';

$customParameters_string = elgg_echo('blti_consumer:customParameters');
$customParameters_view = elgg_view('input/longtext', array(
	'name' => 'params[customParameters]',
	'value' => $vars['entity']->customParameters,
	'class' => 'general-textarea',
));


$settings = <<<__HTML
<div id="blti_consumer_site_settings">
	<div>$consumer_key_string $consumer_key_view</div>
	<div>$consumer_secret_string $consumer_secret_view</div>
	<div>$sendName_string $sendName_view</div>
	<div>$sendEmail_string $sendEmail_view</div>
	<div>$sendProfileDetails_string $sendProfileDetails_view</div>
	<div>$debug_string $debug_view</div>
	<div>$customParameters_string $customParameters_view</div>
</div>
__HTML;

echo $settings;
