<?php
/**
 * Elgg widget add action
 *
 * @package Elgg.Core
 * @subpackage Widgets.Management
 */

$owner_guid = get_input('owner_guid');
$handler = get_input('handler');
$context = get_input('context');
$show_access = (bool)get_input('show_access', true);
$column = get_input('column', 1);
$default_widgets = get_input('default_widgets', 0);

elgg_push_context($context);
if ($default_widgets) {
	elgg_push_context('default_widgets');
}
elgg_push_context('widgets');

if (!empty($owner_guid)) {
	$owner = get_entity($owner_guid);
	if ($owner && $owner->canEdit()) {
		$guid = elgg_create_widget($owner->getGUID(), $handler, $context);
		if ($guid) {
			$widget = get_entity($guid);

			// position the widget
			$widget->move($column, 0);

			# assign special activity_id (prefixed with '--') for widgets added via "Add inquiry components button"
			$widget->activity_id = '--' . $widget->guid;
			// $multiple_allowed = Array('filerepo', 'answers', 'hypothesis', 'pages', 'conclusions', 'notes', 'reflection', 'group_forum_topics');
			// if(in_array($handler, $current_handlers)) {
			//   $widget->activity_id = '--' . $widget->guid;
			// } else {
			//   $widget->activity_id = '--' . md5($handler); # so that data is not lost for these widgets
			// }

			$widgets = elgg_get_entities(array(
				'types' => array('object'),
				'owner_guid' => $owner_guid,
				'subtype' => 'widget',
				'limit' => 9999
			));

			$max_order = 0;

			foreach($widgets as $w) {
				if($w->order > $max_order) { $max_order = $w->order; }
			}

      		$widget->order = $max_order + 1; # put after the widgets created via configuration interface
			$widget->save();

			// send widget html for insertion
			echo elgg_view_entity($widget, array('show_access' => $show_access));

			system_message(elgg_echo('widgets:add:success'));
			forward(REFERER);
		}
	}
}

register_error(elgg_echo('widgets:add:failure'));
forward(REFERER);
