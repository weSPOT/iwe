<?php


elgg_register_event_handler('init', 'system', 'elastic_init');

elgg_register_event_handler('pagesetup', 'system', 'elastic_topbar_menu', 0);


function elastic_init() {
        
//     if (false !== strpos($_SERVER['REQUEST_URI'], '/action/') && ! empty($_SERVER['CONTENT_LENGTH']) && empty($_POST)) {
//     register_error('File too large. It must be below 10mb. Please try another');
//     forward(REFERER);
//     }
    
	elgg_extend_view('page/elements/head', 'page/elements/head_lp');
	
	//load javascript
	elgg_extend_view('js/elgg', 'js/toggle_menu');
    elgg_register_simplecache_view('toggle_menu');
	$url = elgg_get_simplecache_url('js', 'toggle_menu');
	elgg_register_js('toggle_menu', $url);
	//elgg_load_js('toggle_menu');
	
	elgg_unregister_menu_item('topbar', 'elgg_logo');
	
	// add home menu item
//	$item = new ElggMenuItem('home', elgg_echo('elastic:home'), '/');
//	elgg_register_menu_item('site', $item);
	
	//elgg_extend_view('js/elgg', 'default/profile/el_js');
	
	$img_url = elgg_get_site_url().'/mod/elastic/graphics/logo.png';
	elgg_register_menu_item('elastic_top', array(
		'name' => 'site_logo',
		'href' => elgg_get_site_url(),
		'text' => "<div id=\"toplogo\"><img src=\"$img_url\" alt=\"Elgg logo\" width=\"100%\" /></div>",
//		'text' => elgg_get_site_entity()->name,
		'priority' => 1,
		'link_class' => 'comhype-logo',
		'section' => 'a_menu',
	));
	
	elgg_extend_view('page/elements/header', 'search/elastic_search_box');
	elgg_unextend_view('page/elements/header', 'search/header');
    // Replace the default register page
	//register_plugin_hook('register', 'system', 'bp_register');
    //elgg_register_page_handler('register','bp_register');
 
    // Replace the default index page
   register_plugin_hook('index','system','elastic_index');
   
   // no need for a seperate admin page to manage menu items
   elgg_unregister_menu_item("page", "appearance:menu_items");	
	
}
 
function elastic_index() {
    if (!include_once(dirname(dirname(__FILE__)) . "/elastic/index.php"))
        return false;
 
    return true;
}

/**
 * Sets up user-related menu items
 *
 * @return void
 * @access private
 */
function elastic_topbar_menu() {

	$owner = elgg_get_page_owner_entity();
	$viewer = elgg_get_logged_in_user_entity();
	$dropmenu = array();

	if ($owner) {
		$params = array(
			'name' => 'friends',
			'text' => elgg_echo('friends'),
			'href' => 'friends/' . $owner->username,
			'contexts' => array('friends')
		);
		elgg_register_menu_item('page', $params);

		$params = array(
			'name' => 'friends:of',
			'text' => elgg_echo('friends:of'),
			'href' => 'friendsof/' . $owner->username,
			'contexts' => array('friends')
		);
		elgg_register_menu_item('page', $params);
		
		elgg_register_menu_item('page', array(
			'name' => 'edit_avatar',
			'href' => "avatar/edit/{$owner->username}",
			'text' => elgg_echo('avatar:edit'),
			'contexts' => array('profile_edit'),
		));

		elgg_register_menu_item('page', array(
			'name' => 'edit_profile',
			'href' => "profile/{$owner->username}/edit",
			'text' => elgg_echo('profile:edit'),
			'contexts' => array('profile_edit'),
		));
	}

	// topbar
	if ($viewer) {
		elgg_register_menu_item('elastic_top', array(
			'name' => 'profile',
			'href' => $viewer->getURL(),
			'text' => elgg_view('output/img', array(
				'src' => $viewer->getIconURL('topbar'),
				'alt' => $viewer->name,
				'title' => elgg_echo('profile'),
				'class' => 'elgg-border-plain elgg-transition',
				'style' => "border-radius: 500px;",
			)),
			'priority' => 100,
			'link_class' => 'elgg-topbar-avatar',
			'section' => 'a_menu',
		));
		
	
		
		//$dropmenu[100] = 'profile';
				
		// friend requests
		$num_requests = count(elgg_get_entities_from_relationship(array(
			'type' => 'user',
			'relationship' => 'friendrequest',
			'relationship_guid' => elgg_get_logged_in_user_entity()->guid,
			'inverse_relationship' => true,
			'limit' => 0,
		)));
		if ($num_requests != 0) {
			elgg_register_menu_item('elastic_top', array(
				'name' => 'friends',
				'href' => "friends/{$viewer->username}",
				'text' => elgg_view_icon('users').'['.$num_requests.']',
				'title' => elgg_echo('friends'),
				'priority' => 300,
				'section' => 'a_menu',
			));
		} else {
			elgg_register_menu_item('elastic_top', array(
				'name' => 'friends',
				'href' => "friends/{$viewer->username}",
				'text' => elgg_view_icon('users'),
				'title' => elgg_echo('friends'),
				'priority' => 300,
				'section' => 'a_menu',
			));
		}
			
		elgg_register_menu_item('elastic_top', array(
			'name' => 'usersettings',
			'href' => "settings/user/{$viewer->username}",
			'text' => elgg_view_icon('settings') . elgg_echo('settings'),
			'priority' => 500,
			'section' => 'settings',
		));
		elgg_register_menu_item('elastic_drop', array(
			'name' => 'usersettings',
			'href' => "settings/user/{$viewer->username}",
			'text' => elgg_echo('settings'),
			'priority' => 500,
			'section' => 'settings',
		));	
			

		elgg_register_menu_item('elastic_top', array(
			'name' => 'logout',
			'href' => "action/logout",
			'text' => elgg_echo('logout'),
			'is_action' => TRUE,
			'priority' => 1000,
			'section' => 'settings',
		));
		elgg_register_menu_item('elastic_drop', array(
			'name' => 'logout',
			'href' => "action/logout",
			'text' => elgg_echo('logout'),
			'is_action' => TRUE,
			'priority' => 1000,
			'section' => 'settings',
		));
		
		
		elgg_register_menu_item('elastic_top', array(
			'name' => 'toggle',
			'href' => "#",
			'text' => elgg_echo('<div id="elastic-toggle-button"><a href="#" data-bind-action="toggle-content" data-bind-target="elastic-drop-menu-wrapper">Settings</a></div>'),
			'is_action' => TRUE,
			'priority' => 2000,
			'section' => 'a_menu-after',
		));
		
		
		/*
		elgg_register_menu_item('elastic_top', array(
			'name' => 'dashboard',
			'href' => 'dashboard',
			'text' => elgg_view_icon('home') . elgg_echo('dashboard'),
			'priority' => 450,
			'section' => 'a_menu',
		)); */
		
		messages_notify();
		
		if (elgg_is_admin_logged_in()) {
			elgg_register_menu_item('elastic_top', array(
				'name' => 'administration',
				'href' => 'admin',
				'text' => elgg_view_icon('settings') . elgg_echo('admin'),
				'priority' => 100,
				'section' => 'settings',
			));
			elgg_register_menu_item('elastic_drop', array(
				'name' => 'administration',
				'href' => 'admin',
				'text' => elgg_echo('admin'),
				'priority' => 100,
				'section' => 'settings',
			));
		}
		
	}
}

/**
 * Display notification of new messages in topbar
 */
function messages_notify() {
	
	// messages
	if (elgg_is_logged_in()) {
		$class = "elgg-icon elgg-icon-mail";
		$text = "<span class='$class'></span>";
		$drop_text = 'Messages';
		$tooltip = elgg_echo("messages");
		
		// get unread messages
		$num_messages = (int)messages_count_unread();
		if ($num_messages != 0) {
			$text .= "<span class=\"messages-new\">$num_messages</span>";
			$drop_text .= "<span class=\"messages-new\">$num_messages</span>";
			$tooltip .= " (" . elgg_echo("messages:unreadcount", array($num_messages)) . ")";
		}

		elgg_register_menu_item('elastic_top', array(
			'name' => 'messages',
			'href' => 'messages/inbox/' . elgg_get_logged_in_user_entity()->username,
			'text' => $text,
			'priority' => 600,
			'title' => $tooltip,
			'section' => 'a_menu',
		));
	}

}

function elastic_register_dropdown() {
	
}