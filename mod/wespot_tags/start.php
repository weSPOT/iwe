<?php
elgg_register_event_handler ( 'init', 'system', 'medoky_tags_init' );
function medoky_tags_init() {
  $css_url = 'mod/wespot_tags/css/medoky_tags.css';
  $multi_url = 'mod/wespot_tags/css/jquery.multiselect.css';
  elgg_register_css('medoky_tags', $css_url);
  elgg_register_js('medoky_tags',"mod/wespot_tags/js/medoky_tags.js");
  
  elgg_register_css('jquery.multiselect', $multi_url);
  elgg_register_js('jquery.multiselect',"mod/wespot_tags/js/jquery.multiselect.js");
}
