<?php

$consumEnt = $vars['entity'];

global $CONFIG;

// copied and adapted from object/default

$title = $consumEnt->title;
if (!$title) $title = $consumEnt->name;
if (!$title) $title = get_class($consumEnt);

$controls = '';

$info = '<div><p><b>' . $title . '</b> ' . $controls . ' </p>' . $consumEnt->desc . "</div>\n";


$info .= '<div>';

$info .= ' <a href="' . $CONFIG->wwwroot . 'pg/blti_consumer/launch?guid='.$consumEnt->getGUID() . '">' . elgg_echo('blti_consumer:launch') . '</a><br />';
$info .= elgg_echo('blti_consumer:registeredby') . ' <a href="' . $consumEnt->getOwnerEntity()->getUrl() . '">' . $consumEnt->getOwnerEntity()->name . '</a><br />';
$info .= '</div>' . "\n";

$icon = '<img src="' . $CONFIG->wwwroot . 'mod/blti_consumer/graphics/IMSGLCLogo.jpg" />';
echo elgg_view_listing($icon, $info);

?>