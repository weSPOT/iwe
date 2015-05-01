<?php
/**
 * mindmeister_url plugin settings
 */

// set default value
if (!isset($vars['entity']->mindmeister_url)) {
	$vars['entity']->mindmeister_url = 'https://www.mindmeister.com/';
}

// set default value
if (!isset($vars['entity']->mindmeister_apikey)) {
	$vars['entity']->mindmeister_apikey = '';
}

echo '<div>';
echo elgg_echo('wespot_mindmeister:mindmeisterurl');
echo ' ';
echo elgg_view('input/text', array(
	'name' => 'params[mindmeister_url]',
	'value' => $vars['entity']->mindmeister_url,
));
echo '</div>';

echo '<div>';
echo elgg_echo('wespot_mindmeister:mindmeisterkey');
echo ' ';
echo elgg_view('input/text', array(
	'name' => 'params[mindmeister_apikey]',
	'value' => $vars['entity']->mindmeister_apikey,
));
echo '</div>';

echo '<div>';
echo elgg_echo('wespot_mindmeister:mindmeistersecret');
echo ' ';
echo elgg_view('input/text', array(
	'name' => 'params[mindmeister_apisecret]',
	'value' => $vars['entity']->mindmeister_apisecret,
));
echo '</div>';
?>