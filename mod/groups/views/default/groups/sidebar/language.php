<?php
/**
 * Group search
 *
 * @package ElggGroups
 */
$body = '<a href="' . elgg_get_site_url() . 'search?q=Bosnian&entity_type=group&search_type=tags">' . elgg_echo('bs') . '</a><br/>';
$body .= '<a href="' . elgg_get_site_url() . 'search?q=Bulgarian&entity_type=group&search_type=tags">' . elgg_echo('bg') . '</a><br/>';
$body .= '<a href="' . elgg_get_site_url() . 'search?q=Dutch&entity_type=group&search_type=tags">' . elgg_echo('nl') . '</a><br/>';
$body .= '<a href="' . elgg_get_site_url() . 'search?q=English&entity_type=group&search_type=tags">' . elgg_echo('en') . '</a><br/>';
$body .= '<a href="' . elgg_get_site_url() . 'search?q=French&entity_type=group&search_type=tags">' . elgg_echo('fr') . '</a><br/>';
$body .= '<a href="' . elgg_get_site_url() . 'search?q=German&entity_type=group&search_type=tags">' . elgg_echo('de') . '</a><br/>';
$body .= '<a href="' . elgg_get_site_url() . 'search?q=Greek&entity_type=group&search_type=tags">' . elgg_echo('el') . '</a><br/>';
$body .= '<a href="' . elgg_get_site_url() . 'search?q=Italian&entity_type=group&search_type=tags">' . elgg_echo('it') . '</a><br/>';
$body .= '<a href="' . elgg_get_site_url() . 'search?q=Portuguese&entity_type=group&search_type=tags">' . elgg_echo('pt') . '</a><br/>';
$body .= '<a href="' . elgg_get_site_url() . 'search?q=Slovenian&entity_type=group&search_type=tags">' . elgg_echo('sl') . '</a><br/>';
$body .= '<a href="' . elgg_get_site_url() . 'search?q=Spanish&entity_type=group&search_type=tags">' . elgg_echo('es') . '</a>';

echo elgg_view_module('aside', elgg_echo('groups:listlanguage'), $body);
