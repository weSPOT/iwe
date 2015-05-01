<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
$powered_url = elgg_get_site_url() . "_graphics/powered_by_elgg_badge_drk_bckgnd.gif";

echo '<div class="mts clearfloat float-alt">';
echo elgg_view('output/url', array(
	'href' => 'http://elgg.org',
	'text' => "<img src=\"$powered_url\" alt=\"Powered by Elgg\" width=\"106\" height=\"15\" />",
	'class' => '',
	'is_trusted' => true,
	'target' => '_blank',
));
echo '</div>';