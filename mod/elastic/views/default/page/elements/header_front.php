<?php
/**
 * Elgg page header
 * In the default theme, the header lives between the topbar and main content area.
 */



echo elgg_view('page/elements/header_picture');

// link back to main site.
echo elgg_view('page/elements/header_logo', $vars);



// insert site-wide navigation
echo elgg_view_menu('site');