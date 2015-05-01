<?php
/**
 * ARLearn Message Board
 * This plugin allows groups to attach a message board to their profile for other users
 * to post comments.
 *
 * @package MessageBoard
 */

/**
 * MessageBoard initialisation
 */
function wespot_msg_init()
{ 
    elgg_register_library('elgg:wespot_arlearn', elgg_get_plugins_path() . 'wespot_arlearn/lib/wespot_arlearn.php');
    elgg_register_library('elgg:wespot_msg', elgg_get_plugins_path() . 'wespot_msg/lib/wespot_msg.php');
    elgg_register_library('elgg:wespot_arlearnservices', elgg_get_plugins_path() . 'wespot_arlearn/lib/arlearnservices.php');
    elgg_register_library('elgg:wespot_arlearnmsgservices', elgg_get_plugins_path() . 'wespot_msg/lib/arlearnmsgservices.php');

    elgg_register_js('google_channel', '//talkgadget.google.com/talkgadget/channel.js');

    elgg_extend_view('css/elgg', 'wespot_msg/css');

    $wespot_msg_js = elgg_get_simplecache_url('js', 'wespot_msg/wespot_msg');
    elgg_register_simplecache_view('js/wespot_msg/wespot_msg');
    elgg_register_js('elgg.wespot_msg', $wespot_msg_js);

    $wespot_msg_channel_js = elgg_get_simplecache_url('js', 'wespot_msg/wespot_msg_channel');
    elgg_register_simplecache_view('js/wespot_msg/wespot_msg_channel');
    elgg_register_js('elgg.wespot_msg.channel', $wespot_msg_channel_js);


    elgg_register_ajax_view('infinite_scroll/list');

    $infinite_scroll_js = elgg_get_simplecache_url('js', 'infinite_scroll/infinite_scroll');
    elgg_register_simplecache_view('js/infinite_scroll/infinite_scroll');
    elgg_register_js('elgg.wespot_msg.infinite_scroll', $infinite_scroll_js);


    // Register javascript needed for automatic pagination
    $js_url = 'mod/wespot_msg/vendors/jquery-waypoints/waypoints.min.js';
    elgg_register_js('jquery-waypoints', $js_url);

    $js_url = 'mod/wespot_msg/vendors/jquery-viewport/jquery.viewport.mini.js';
    elgg_register_js('jquery-viewport', $js_url);

    $automatic_pagination_js = elgg_get_simplecache_url('js', 'infinite_scroll/automatic_pagination');
    elgg_register_simplecache_view('js/infinite_scroll/automatic_pagination');
    elgg_register_js('elgg.wespot_msg.infinite_scroll.automatic_pagination', $automatic_pagination_js);

    elgg_register_page_handler('wespot_msg', 'wespot_msg_page_handler');
    
    elgg_register_widget_type('wespot_msg', elgg_echo("wespot_msg:board"), elgg_echo("wespot_msg:desc"), "groups");
    
    $action_path = dirname(__FILE__) . '/actions';
    elgg_register_action("wespot_msg/add", "$action_path/add.php");
    elgg_register_action("wespot_msg/delete", "$action_path/delete.php");
    elgg_register_action("wespot_msg/ajax/get", "$action_path/ajax/get.php");
    elgg_register_action("wespot_msg/refreshtoken", "$action_path/refreshtoken.php");

    elgg_register_plugin_hook_handler('register', 'menu:entity', 'wespot_msg_menu_setup', 500);
    
    //By default, Elgg's security system enforces four different rules for non-admins:
    //View permissions. You can only see content you have permission to see.
    //Write permissions. You can only change content you own.
    //Owner permissions. You can only create new content owned by you and cannot transfer the ownership of your existing content to anyone else.
    //Container permissions. You can only place content in your own user entity or groups for which you are a member.
    //The owner and container permissions can be over-ridden by the container_permissions_check plugin hook.
    elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'wespot_msg_container_permission_check');

    // hook on ARLearn Message Board' widget delete action 
    // and delete locally stored messages
    register_plugin_hook("action", "widgets/delete", "wespot_msg_delete_widget_hook");
}

/**
 * ARLean Message Board dispatcher for flat message board.
 *
 * Group messageboard:                wespot_msg/group/<guid>/all
 *
 * @param array $page Array of page elements
 * @return bool
 */
function wespot_msg_page_handler($page)
{ 
    elgg_load_library('elgg:wespot_arlearn');
    elgg_load_library('elgg:wespot_msg');
    
    $pages = dirname(__FILE__) . '/pages/wespot_msg';

    //elgg_load_library('elgg:wespot_arlearnservices');

    switch ($page[0]) {
        
        case 'group':
            group_gatekeeper();
            $owner_guid = elgg_extract(1, $page);
	    elgg_set_page_owner_guid($owner_guid); //nasko
            set_input('page_owner_guid', $owner_guid);
            $threadId = elgg_extract(2, $page);
            set_input('threadId', $threadId);
            include "$pages/group.php";
            break;
        
        default:
            return false;
    }
    return true;
}

/**
 * Add messageboard post
 *
 * @param ElggUser $user User posting the message
 * @param ElggGroup $group Group who owns the message board
 * @param stdClass $message The posted message
 * @param int $access_id Access level
 * @return bool
 */
function wespot_msg_add_message($user, $group, $message, $access_id = ACCESS_PUBLIC)
{
    if (!isset($message) || empty($message->messageId) || empty($message->threadId) || empty($message->body))
        return false;
    
    $obj                  = new ElggObject();
    $obj->subtype         = 'arlearn_msg';
    $obj->owner_guid      = $user->getGUID();
    $obj->container_guid  = $group->getGUID();
    $obj->write_access_id = ACCESS_PRIVATE; //$access_id;
    $obj->access_id       = ACCESS_PUBLIC;
    $obj->messageId       = $message->messageId;
    $obj->threadId        = $message->threadId;
    $obj->body            = $message->body;
    $obj->post_date       = $message->date;
	
	elgg_set_ignore_access(true);
    $result = $obj->save();
	elgg_set_ignore_access(false);
    
    if (!$result) {
        return false;
    }
    
    //add_to_river('river/object/arlearn_msg/create', 'create', $user->guid, $group->guid, $access_id, ($obj->post_date / 1000), $result);
    
    return $result;
}

function wespot_msg_add_thread($user, $group, $thread, $access_id = ACCESS_PUBLIC)
{
    if (!isset($thread) || empty($thread->threadId) || empty($thread->name)) {
        return false;
    }

    $obj                  = new ElggObject();
    $obj->subtype         = 'arlearn_thread';
    $obj->owner_guid      = $user->getGUID();
    $obj->container_guid  = $group->getGUID();
    $obj->write_access_id = ACCESS_PRIVATE; //$access_id;
    $obj->access_id       = ACCESS_PUBLIC;
    $obj->threadId        = $thread->threadId;
    $obj->name            = $thread->name;
    $obj->lastModificationDate       = $thread->lastModificationDate;
    $obj->deleted = $thread->deleted;

	elgg_set_ignore_access(true);
    $result = $obj->save();
	elgg_set_ignore_access(false);

    if (!$result) {
        return false;
    }

    //add_to_river('river/object/arlearn_thread/create', 'create', $user->guid, $group->guid, $access_id, ($obj->post_date / 1000), $result);

    return $result;
}


/**
 * Add delete links for Message Board replies
 */
function wespot_msg_menu_setup($hook, $type, $return, $params)
{ 
    if (!empty($params) && is_array($params)) {
        
        $entity = elgg_extract("entity", $params);
        if (!empty($entity) && elgg_instanceof($entity, "object", "arlearn_msg")) {
            
            foreach ($return as $index => $item) {
                if ($item->getName() == 'access') {
                    unset($return[$index]);
                }
            }
            
            // AS FOR NOW ARLearn Message API delete is not implemented below code is skiped for now
            /**
            if ($entity->canEdit()) {
            $url = elgg_http_add_url_query_elements('action/wespot_msg/delete', array(
            'entity_guid' => $entity->getGUID(),
            ));
            
            $options = array(
            'name' => 'delete',
            'href' => $url,
            'text' => "<span class=\"elgg-icon elgg-icon-delete\"></span>",
            'confirm' => elgg_echo('deleteconfirm'),
            'encode_text' => false
            );
            $return[] = ElggMenuItem::factory($options);
            }
            */
            
            // likes button
            $options  = array(
                'name' => 'likes',
                'text' => elgg_view('likes/button', array(
                    'entity' => $entity
                )),
                'href' => false,
                'priority' => 1000
            );
            $return[] = ElggMenuItem::factory($options);
            
            // likes count
            $count = elgg_view('likes/count', array(
                'entity' => $entity
            ));
            if ($count) {
                $options  = array(
                    'name' => 'likes_count',
                    'text' => $count,
                    'href' => false,
                    'priority' => 1001
                );
                $return[] = ElggMenuItem::factory($options);
            }
        }
    }
    
    return $return;
}

function wespot_msg_delete_group_message_board($group_guid, $threadId)
{  
    //@todo user ElggBatch
    //@link  http://community.elgg.org/discussion/view/1096269/clearing-out-old-plugin-entityobject-types
    $container = get_entity((int) $group_guid);
    
    elgg_set_page_owner_guid($group_guid);
    if (!($container instanceof ElggGroup) && $container->canEdit()) {
        return false;
    }

    $messages = elgg_get_entities_from_metadata(array(
        'type' => 'object',
        'subtype' => 'arlearn_msg',
        'container_guid' => $group_guid, // this works for group widgets
        'metadata_names' => array('threadId'),
        'metadata_values' => array($threadId),        
        'limit' => 0
    ));
    foreach ($messages as $message) {
            $message->delete();
    }
    
    $threads = elgg_get_entities_from_metadata(array(
        'type' => 'object',
        'subtype' => 'arlearn_thread',
        'container_guid' => $group_guid, // this works for group widgets
        'metadata_names' => array('threadId'),
        'metadata_values' => array($threadId),        
        'limit' => 0
    ));
    foreach ($threads as $thread) {
            $thread->delete();
    }    

    return true;
}

/**
 * Delete messages from Elgg when the ARLearn widget is deleted
 */
function wespot_msg_delete_widget_hook($hook, $entity_type, $returnvalue, $params)
{
    $widget_guid = get_input('widget_guid');
    $entity      = get_entity($widget_guid);
    if (elgg_instanceof($entity, 'object', 'widget')) {
        $returnvalue = wespot_msg_delete_group_message_board($entity->container_guid, $entity->threadId);
    }
    return $returnvalue;
}

/**
 * Extend container permissions checking to extend can_write_to_container for write users.
 *
 */
function wespot_msg_container_permission_check($hook, $entity_type, $returnvalue, $params)
{
    if (elgg_get_context() == "wespot_msg") {
      if (elgg_get_page_owner_guid()) {
        if (can_write_to_container(elgg_get_logged_in_user_guid(), elgg_get_page_owner_guid())) 
	  return true;
      }
    }
    
    return null;
}

elgg_register_event_handler('init', 'system', 'wespot_msg_init');
