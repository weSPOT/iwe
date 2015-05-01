<div>
<div id="blti_consumer_newregistration">
<?php

echo elgg_view_title(elgg_echo('blti_consumer:register:title'));

$name = elgg_view('input/text', array('name' => 'name'));
$toolurl = elgg_view('input/text', array('name' => 'toolurl'));
$description = elgg_view('input/longtext', array('name' => 'desc'));
$callbackUrl = elgg_view('input/url', array('name' => 'callbackUrl'));
$key = elgg_view('input/text', array('name' => 'key'));
$secret = elgg_view('input/text', array('name' => 'secret'));

$preferheight = elgg_view('input/text', array('name' => 'preferheight'));

$options = array(elgg_echo('blti_consumer:yes')=>1, elgg_echo('blti_consumer:no')=>0);


$sendname = str_replace('<br />','&nbsp;', elgg_view('input/radio',array(
				'options' => $options,
				'value' => 1,
				'name' => 'sendname'
				)));
$sendemail = str_replace('<br />','&nbsp;', elgg_view('input/radio',array(
				'options' => $options,
				'value' => 1,
				'name' => 'sendemail'
				)));
$sendprofile = str_replace('<br />','&nbsp;', elgg_view('input/radio',array(
				'options' => $options,
				'value' => 1,
				'name' => 'sendprofile'
				)));
$options_value = array(1=>elgg_echo('blti_consumer:register:lti_launch:elgg'),2=>elgg_echo('blti_consumer:register:lti_launch:popup'),3=>elgg_echo('blti_consumer:register:lti_launch:standalone'));
$launch = str_replace('<br />','&nbsp;', elgg_view('input/dropdown',array(
				'options_values' => $options_value,
				'value' => 1,
				'name' => 'launch'
				)));
$debug = str_replace('<br />','&nbsp;', elgg_view('input/radio',array(
						'options' => $options,
						'value' => 0,
						'name' => 'debug'
						)));
	
$custom_params = elgg_view('input/longtext', array('name' => 'custom_params'));

$submit = elgg_view('input/submit', array('value' => elgg_echo('blti_consumer:register:submit')));

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
$formbody .= $submit;

$form = elgg_view('input/form', array('action' => $CONFIG->wwwroot . 'action/blti_consumer/register', 
				      'body' => $formbody));

echo $form;

?>
</div>
<input id="blti_consumer_shownewregistration" type="submit" value="<?php echo elgg_echo('blti_consumer:register:show') ?>" />
<script type="text/javascript">
	$(document).ready(function() {
	  $("#blti_consumer_newregistration").hide();
	});

	$("#blti_consumer_shownewregistration").click(function(event) {
	  event.preventDefault();
	  $("#blti_consumer_shownewregistration").slideUp("slow");
	  $("#blti_consumer_newregistration").slideDown("slow");
	});

</script>
</div>