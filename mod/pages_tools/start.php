<?php

	require_once(dirname(__FILE__) . "/lib/functions.php");
	require_once(dirname(__FILE__) . "/lib/hooks.php");

	function pages_tools_init(){
		// register DOM PDF as a library
		elgg_register_library("dompdf", dirname(__FILE__) . "/vendors/dompdf.php");
		
		// extend site css
		elgg_extend_view("css/elgg", "pages_tools/css/site");
		
		// extend site js
		elgg_extend_view("js/elgg", "pages_tools/js/site");
		
		// register JS library
		elgg_register_js("jquery.tree", elgg_get_site_url() . "mod/pages_tools/vendors/jstree/jquery.tree.min.js");
		elgg_register_css("jquery.tree", elgg_get_site_url() . "mod/pages_tools/vendors/jstree/themes/classic/style.css");
		
		// add widgets (overrule default pages widget, to add group support)
//		elgg_register_widget_type("pages", elgg_echo("pages"), elgg_echo("pages:widget:description"), "profile,dashboard,groups");
//		elgg_register_widget_type("index_pages", elgg_echo("pages"), elgg_echo("pages_tools:widgets:index_pages:description"), "index", true);
		
		// register plugin hooks
		elgg_register_plugin_hook_handler("route", "pages", "pages_tools_route_pages_hook");
		elgg_register_plugin_hook_handler("register", "menu:entity", "pages_tools_entity_menu_hook");
//		elgg_register_plugin_hook_handler("permissions_check:comment", "object", "pages_tools_permissions_comment_hook");
//		elgg_register_plugin_hook_handler("widget_url", "widget_manager", "pages_tools_widget_url_hook");
//		elgg_register_plugin_hook_handler("cron", "daily", "pages_tools_daily_cron_hook");
		
		// register actions
		elgg_register_action("pages/export", dirname(__FILE__) . "/actions/export.php", "public");
		elgg_register_action("pages/reorder", dirname(__FILE__) . "/actions/reorder.php");
		
		// overrule action
//		elgg_register_action("pages/edit", dirname(__FILE__) . "/actions/pages/edit.php");
	}

	// register default Elgg events
	elgg_register_event_handler("init", "system", "pages_tools_init");

 /**
  * Set of functions for retrieving all subsections of an Inquiry for export
  */
  
function export_inquiry_hypothesis($inquiryId) {
        
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => array('hypothesis_top', 'hypothesis'),
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
  if ($content) {
    foreach ($content as $hypothesis){
      $return[] = array('title'=>$hypothesis->title, 'description'=>strip_tags($hypothesis->description,'<p><br><b><i><strong><em>'), 'url'=>get_entity_url($hypothesis->getGUID()));
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
}
                
function export_inquiry_notes($inquiryId) {
        
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => array('notes_top', 'notes'),
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
  if ($content) {
    foreach ($content as $notes){
      $return[] = array('title'=>$notes->title, 'description'=>strip_tags($notes->description,'<p><br><b><i><strong><em>'), 'url'=>get_entity_url($notes->getGUID()));
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
}

function export_inquiry_conclusions($inquiryId) {
        
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => array('conclusions_top', 'conclusions'),
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
  if ($content) {
    foreach ($content as $conclusions){
    $return[] = array('title'=>$conclusions->title, 'description'=>strip_tags($conclusions->description,'<p><br><b><i><strong><em>'), 'url'=>get_entity_url($conclusions->getGUID()));
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
}

function export_inquiry_reflection($inquiryId) {
        
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => array('reflection_top', 'reflection'),
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
  if ($content) {
    foreach ($content as $reflection){
      $return[] = array('title'=>$reflection->title, 'description'=>strip_tags($reflection->description,'<p><br><b><i><strong><em>'), 'url'=>get_entity_url($reflection->getGUID()));
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
}

function export_inquiry_files($inquiryId) {
    
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => 'file',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
    if ($content) {
    foreach ($content as $file){
//	$return[] = array('title'=>$file->title, 'description'=>$file->description, 'url'=>get_entity_url($file->getGUID()));
    $return[] = array('title'=>$file->title, 'description'=>$file->description, 'url'=>elgg_get_site_url().'file/download/'.$file->getGUID());
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
}

function export_inquiry_pages($inquiryId) {
    
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => array('page_top', 'page'),
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
  if ($content) {
    foreach ($content as $page){
      $return[] = array('title'=>$page->title, 'description'=>strip_tags($page->description,'<p><br><b><i><strong><em>'), 'url'=>get_entity_url($page->getGUID()));
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
} 
                                                                                                    
function export_inquiry_questions($inquiryId) {
        
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => 'question',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
  if ($content) {
    foreach ($content as $question){
      $return[] = array('title'=>$question->title, 'description'=>strip_tags(trim($question->description),'<p><br><b><i><strong><em>'), 'url'=>get_entity_url($question->getGUID()));
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
}
                
function export_inquiry_answers($inquiryId) {
        
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => 'answer',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
  if ($content) {
    foreach ($content as $answer) {
      $question = get_entity($answer->question_guid);
      $return[] = array('title'=>$question->title, 'description'=>strip_tags($question->description,'<p><br><b><i><strong><em>'), 'answer'=>$answer->description, 'url'=>get_entity_url($answer->getGUID()));
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
}

function export_inquiry_mindmaps($inquiryId) {
    
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => 'mindmeistermap',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
  if ($content) {
    foreach ($content as $mindmap){
      $return[] = array('title'=>$mindmap->title, 'description'=>strip_tags($mindmap->description,'<p><br><b><i><strong><em>'), 'url'=>get_entity_url($mindmap->getGUID()));
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
}
                
function export_inquiry_blogs($inquiryId) {
    
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
  if ($content) {
    foreach ($content as $blog){
      $return[] = array('title'=>$blog->title, 'description'=>strip_tags($blog->description,'<p><br><b><i><strong><em>'), 'url'=>get_entity_url($blog->getGUID()));
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
}

function export_inquiry_discussions($inquiryId) {
    
  elgg_set_ignore_access(true);
	$inquiry = get_entity($inquiryId);
	if(!($inquiry instanceof ElggGroup))
		throw new Exception("Inquiry with identifier {$inquiryId} not found");
		
	$options = array(
		'type' => 'object',
		'subtype' => 'groupforumtopic',
		'container_guid' => $inquiryId,
		'limit' => false,
	);
	$content = elgg_get_entities($options);
	
	$return = array();
  if ($content) {
    foreach ($content as $discussion){
      $return[] = array('title'=>$discussion->title, 'description'=>strip_tags($discussion->description,'<p><br><b><i><strong><em>'), 'url'=>get_entity_url($discussion->getGUID()));
    }
  }
  elgg_set_ignore_access(false);
  return $return;
            
}
            
  /* end of export functions */

