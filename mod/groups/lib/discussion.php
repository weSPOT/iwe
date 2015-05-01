<?php
/**
 * Discussion function library
 */

/**
 * List all discussion topics
 */
function discussion_handle_all_page() {

	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('discussion'));

	$content = elgg_list_entities(array(
		'type' => 'object',
		'subtype' => 'groupforumtopic',
		'order_by' => 'e.last_action desc',
		'limit' => 20,
		'full_view' => false,
	));

	$params = array(
		'content' => $content,
		'title' => elgg_echo('discussion:latest'),
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * List discussion topics in a group
 *
 * @param int $guid Group entity GUID
 */
function discussion_handle_list_page($guid) {

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid);
	if (!$group) {
		register_error(elgg_echo('group:notfound'));
		forward();
	}
	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($group->name . ' ' . elgg_echo('item:object:groupforumtopic'));

  $phase = $_GET['phase'];
  $activity_id = $_GET['activity_id'];

    if($phase) {
        elgg_register_title_button(null, 'add', 'phase=' . $phase . '&activity_id=' . $activity_id);
    }

	group_gatekeeper();

	$title = $group->name . ' ' . elgg_echo('item:object:groupforumtopic');

	$options = array(
		'type' => 'object',
		'subtype' => 'groupforumtopic',
		'limit' => 20,
		'order_by' => 'e.last_action desc',
		'container_guid' => $guid,
		'full_view' => false,
	);

    $get_content = function ($options) use ($phase, $activity_id) {
      $filter = function($element) use ($phase, $activity_id) { return ($element->phase == $phase || (!$element->phase && $phase == 5)) && ($element->activity_id == $activity_id || !$activity_id); };
      if($options['count']) { # because of how elgg_list_entities works
          $options['count'] = FALSE;
          return count(array_filter(elgg_get_entities($options), $filter));
      } else {
          return array_filter(elgg_get_entities($options), $filter);
      }
    };

	$content = elgg_list_entities($options, $get_content, 'elgg_view_entity_list', true);
	if (!$content) {
		$content = elgg_echo('discussion:none');
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Edit or add a discussion topic
 *
 * @param string $type 'add' or 'edit'
 * @param int    $guid GUID of group or topic
 */
function discussion_handle_edit_page($type, $guid) {
	gatekeeper();

	if ($type == 'add') {
		$group = get_entity($guid);
		if (!$group) {
			register_error(elgg_echo('group:notfound'));
			forward();
		}

		// make sure user has permissions to add a topic to container
		if (!$group->canWriteToContainer(0, 'object', 'groupforumtopic')) {
			register_error(elgg_echo('groups:permissions:error'));
			forward($group->getURL());
		}

		$title = elgg_echo('groups:addtopic');

		elgg_push_breadcrumb($group->name . ' ' . elgg_echo('item:object:groupforumtopic'), "discussion/owner/$group->guid");
		elgg_push_breadcrumb($title);

		$body_vars = discussion_prepare_form_vars();
		$content = elgg_view_form('discussion/save', array(), $body_vars);
	} else {
		$topic = get_entity($guid);
		if (!$topic || !$topic->canEdit()) {
			register_error(elgg_echo('discussion:topic:notfound'));
			forward();
		}
		$group = $topic->getContainerEntity();
		if (!$group) {
			register_error(elgg_echo('group:notfound'));
			forward();
		}

		$title = elgg_echo('groups:edittopic');

		elgg_push_breadcrumb($group->name . ' ' . elgg_echo('item:object:groupforumtopic'), "discussion/owner/$group->guid");
		elgg_push_breadcrumb($topic->title, $topic->getURL());
		elgg_push_breadcrumb($title);

		$body_vars = discussion_prepare_form_vars($topic);
		$content = elgg_view_form('discussion/save', array(), $body_vars);
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * View a discussion topic
 *
 * @param int $guid GUID of topic
 */
function discussion_handle_view_page($guid) {
	// We now have RSS on topics
	global $autofeed;
	$autofeed = true;

	$topic = get_entity($guid);
	if (!$topic) {
		register_error(elgg_echo('noaccess'));
		$_SESSION['last_forward_from'] = current_page_url();
		forward('');
	}

	$group = $topic->getContainerEntity();
	if (!$group) {
		register_error(elgg_echo('group:notfound'));
		forward();
	}

	elgg_set_page_owner_guid($group->getGUID());

    elgg_register_title_button(null, 'add', 'phase=' . $topic->phase . '&activity_id=' . $topic->activity_id);

	group_gatekeeper();

	elgg_push_breadcrumb($group->name . ' ' . elgg_echo('item:object:groupforumtopic'), "discussion/owner/$group->guid");
	elgg_push_breadcrumb($topic->title);

	$content = elgg_view_entity($topic, array('full_view' => true));
	if ($topic->status == 'closed') {
		$content .= elgg_view('discussion/replies', array(
			'entity' => $topic,
			'show_add_form' => false,
		));
		$content .= elgg_view('discussion/closed');
	} elseif ($group->canWriteToContainer(0, 'object', 'groupforumtopic') || elgg_is_admin_logged_in()) {
		$content .= elgg_view('discussion/replies', array(
			'entity' => $topic,
			'show_add_form' => true,
		));
	} else {
		$content .= elgg_view('discussion/replies', array(
			'entity' => $topic,
			'show_add_form' => false,
		));
	}

	$params = array(
		'content' => $content,
		'title' => $topic->title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($topic->title, $body);
}

/**
 * Prepare discussion topic form variables
 *
 * @param ElggObject $topic Topic object if editing
 * @return array
 */
function discussion_prepare_form_vars($topic = NULL) {
	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'status' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $topic,
	);

	if ($topic) {
		foreach (array_keys($values) as $field) {
			if (isset($topic->$field)) {
				$values[$field] = $topic->$field;
			}
		}
	}

	if (elgg_is_sticky_form('topic')) {
		$sticky_values = elgg_get_sticky_values('topic');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('topic');

	return $values;
}
