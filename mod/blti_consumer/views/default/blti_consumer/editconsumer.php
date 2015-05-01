<div>
<?php

$consumEnt = $vars['entity'];

if ($consumEnt->canEdit()) {

	$name = elgg_view('input/text', array('name' => 'name', 'value' => $consumEnt->name));
	$toolurl = elgg_view('input/text', array('name' => 'toolurl', 'value' => $consumEnt->toolurl));
	$description = elgg_view('input/longtext', array('name' => 'desc', 'value' => $consumEnt->description));
	$callbackUrl = elgg_view('input/url', array('name' => 'callbackUrl', 'value' => $consumEnt->callbackUrl));
	$key = elgg_view('input/text', array('name' => 'key', 'value' => $consumEnt->key));
	$secret = elgg_view('input/text', array('name' => 'secret', 'value' => $consumEnt->secret));
	
	$preferheight = elgg_view('input/text', array('name' => 'preferheight', 'value' => $consumEnt->preferheight));
	
	$options = array(elgg_echo('blti_consumer:yes')=>1, elgg_echo('blti_consumer:no')=>0);
	
	
	$sendname = str_replace('<br />','&nbsp;', elgg_view('input/radio',array(
					'options' => $options,
					'value' => $consumEnt->sendname,
					'name' => 'sendname'
					)));
	$sendemail = str_replace('<br />','&nbsp;', elgg_view('input/radio',array(
					'options' => $options,
					'value' => $consumEnt->sendemail,
					'name' => 'sendemail'
					)));
	$sendprofile = str_replace('<br />','&nbsp;', elgg_view('input/radio',array(
					'options' => $options,
					'value' => $consumEnt->sendprofile,
					'name' => 'sendprofile'
					)));
	$options_value = array(1=>elgg_echo('blti_consumer:register:lti_launch:elgg'),2=>elgg_echo('blti_consumer:register:lti_launch:popup'),3=>elgg_echo('blti_consumer:register:lti_launch:standalone'));
	$launch = str_replace('<br />','&nbsp;', elgg_view('input/dropdown',array(
					'options_values' => $options_value,
					'value' => $consumEnt->launch,
					'name' => 'launch'
					)));
					

	$debug = str_replace('<br />','&nbsp;', elgg_view('input/radio',array(
						'options' => $options,
						'value' => $consumEnt->debug,
						'name' => 'debug'
						)));
					
	$custom_params = elgg_view('input/longtext', array('name' => 'custom_params', 'value' => $consumEnt->custom_params));

	$submit = elgg_view('input/submit', array('value' => elgg_echo('blti_consumer:register:submit')));
	$guid = elgg_view('input/hidden', array('name' => 'guid', 'value' => $consumEnt->getGUID()));
	$cancel = elgg_view('input/submit', array('name' => 'cancel', 'class' => 'cancel_button', 'value' => elgg_echo('blti_consumer:consumer:edit:cancel')));
	
	
	$formbody = '<p><label>' . elgg_echo('blti_consumer:register:name:label') . '</label>' . '<br />' . elgg_echo('blti_consumer:register:name:desc') . $name . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:remotetoolurl:label') . '</label>' . '<br />' . elgg_echo('blti_consumer:register:remotetoolurl:desc') . $toolurl . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:key:label') . '</label>' . '<br />' . elgg_echo('blti_consumer:register:key:desc') . $key . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:secret:label') . '</label>' . '<br />' . elgg_echo('blti_consumer:register:secret:desc') . $secret . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:desc:label') . '</label>' . '<br />' . elgg_echo('blti_consumer:register:desc:desc') . $description . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:callback:label') . '</label>' . '<br />' . elgg_echo('blti_consumer:register:callback:label:desc') . $callbackUrl . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:lti_preferheight:label') . '</label>' . $preferheight . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:lti_sendname:label') . '</label>' . $sendname . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:lti_sendemail:label') . '</label>' . $sendemail . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:lti_sendprofile:label') . '</label>' . $sendprofile . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:lti_customparams:label') . '</label>' . '<br />' . elgg_echo('blti_consumer:register:lti_custom_params:desc')  . $custom_params . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:register:lti_launch:label') . '</label>' . $launch . "</p>\n";
	$formbody .= '<p><label>' . elgg_echo('blti_consumer:debugLaunch') . '</label>' . $debug . "</p>\n";
	$formbody .= $guid . "\n";
	$formbody .= $submit . "\n";
	$formbody .= $cancel . "\n";

	$form = elgg_view('input/form', array('action' => $CONFIG->wwwroot . 'action/blti_consumer/register', 
					      'body' => $formbody));

	echo $form;

} else {

	echo 'Permission denied';

}
?>
</div>
