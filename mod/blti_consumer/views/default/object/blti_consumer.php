<?php

$consumEnt = $vars['entity'];

global $CONFIG;

// copied and adapted from object/default

$title = $consumEnt->title;
if (!$title) $title = $consumEnt->name;
if (!$title) $title = get_class($consumEnt);

$controls = '';
//if ($consumEnt->canEdit()) {
if (elgg_get_logged_in_user_entity()->isAdmin()) {
	$controls .= '( <a href="' .  $CONFIG->wwwroot . 'pg/blti_consumer/editconsumer?guid=' . $consumEnt->getGUID() . '">'
		. elgg_echo('blti_consumer:edit:link') . '</a>'
		. ' |';
	$controls .= ' ' . elgg_view('output/confirmlink', array(
					     'href' => $CONFIG->wwwroot . 'action/blti_consumer/unregister?guid=' . $consumEnt->getGUID(),
					     'text' => elgg_echo('blti_consumer:consumer:unregister'),
					     'confirm' => elgg_echo('deleteconfirm')
					     ))
		. ' )';
}

$info = '<div><p><b>' . $title . '</b> ' . $controls . ' </p>' . $consumEnt->description . "</div>\n";


$info .= '<div>';

// only show the key and secret to people that we're supposed to
//if ($consumEnt->canEdit()) {
if (elgg_get_logged_in_user_entity()->isAdmin()) {
	$info .= '<b>' . elgg_echo('blti_consumer:register:key:label') . ':</b> ' . $consumEnt->key . '<br />';
	$info .= '<b>' . elgg_echo('blti_consumer:register:secret:label') . ':</b> ' . $consumEnt->secret . '<br />';
	$info .= '<b>' . elgg_echo('blti_consumer:register:remotetoolurl:label') . ':</b> ' . $consumEnt->toolurl . '<br />';
}

$info .= ' <a href="' . $CONFIG->wwwroot . 'pg/blti_consumer/launch?guid='.$consumEnt->getGUID() . '">' . elgg_echo('blti_consumer:launch') . '</a><br />';
$info .= elgg_echo('blti_consumer:registeredby') . ' <a href="' . $consumEnt->getOwnerEntity()->getUrl() . '">' . $consumEnt->getOwnerEntity()->name . '</a><br />';
$info .= '</div>' . "\n";

$icon = '<img src="' . $CONFIG->wwwroot . 'mod/blti_consumer/graphics/IMSGLCLogo.jpg" />';
echo elgg_view('page/components/image_block', array('image' => $icon, 'body' => $info));

?>