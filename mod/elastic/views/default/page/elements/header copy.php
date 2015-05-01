<?php
/**
 * Elgg page header
 * In the default theme, the header lives between the topbar and main content area.
 */

// link back to main site.
// 
//echo elgg_view('page/elements/header_logo', $vars);


// insert site-wide navigation
echo '<div id="elastic-main-menu-wrapper">';
echo '<div class="elastic-menu-wrapper">';
echo '<div id="elastic-main-menu">';
echo elgg_view_menu('site');
echo '</div>';
echo '</div>';
echo '</div>';
