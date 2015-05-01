<?php
/**
 * Created by PhpStorm.
 * User: david
 * Get parsed json data for phases, skills and activities
 */

global $phases_data;
$file_path = file_get_contents(elgg_get_plugins_path() . 'profile_manager/data/skills.json');
$phases_data = json_decode($file_path, true);

function phase_name($phase) {
    global $phases_data;
    return $phases_data[$phase - 1]['name'];
}

function phase_count() {
    global $phases_data;
    return count($phases_data);
}

function get_activity_name($activity_id) {
    global $phases_data;

    foreach($phases_data as $phase) {
        foreach ($phase['tasks'] as $task) {
            if($activity_id == $task['activity_id']) {
                return $task['name'];
            }
        }
    }
}

function data_by_activity_ids() {
    global $phases_data;

    $data = Array();

    foreach($phases_data as $phase) {
        foreach ($phase['tasks'] as $task) {
            $task['phase'] = $phase['phase'];
            $data[$task['activity_id']] = $task;
        }
    }

    return $data;
}

function activity_for_api_form_task($task) {
    return Array('activity_id' => $task['activity_id'],
        'widget_type' => normalize_widget_type($task['widget']),
        'title' => $task['title'],
        'task' => $task['name'],
        'description' => $task['activity'],
        'phase' => $task['phase']
    );
}

function activities_for_api($activity_ids) {
    $data = data_by_activity_ids();

    $result = Array();

    foreach($activity_ids as $activity_id) {
        $task = $data[$activity_id];
        array_push($result, activity_for_api_form_task($task));
    }

    return $result;
}

function normalize_widget_type($plugin) {
    $mapper = array(
        'filerepo' => 'files',
        'wespot_mindmeister' => 'mindmaps',
        'answers' => 'questions',
        #'hypothesis' => 'hypothesis',
        #'pages' => 'pages',
        #'conclusions' => 'conclusions',
        #'notes' => 'notes',
        #'reflection' => 'reflection',
        'group_forum_topics' => 'discussion',
        'wespot_arlearn' => 'data_collection'
    );

    if($mapper[$plugin]) {
        return $mapper[$plugin];
    } else {
        return $plugin;
    }
}

function inquiry_supports_new_configuration_interface($inquiry) {
    if($inquiry == null) { return true; }
    $profiles = elgg_get_entities(array('types' => 'object', 'subtypes' => 'tabbed_profile', 'container_guid' => $inquiry->guid));
    $widget_counter = 0;
    foreach($profiles as $profile) {
        $widgets = elgg_get_entities_from_relationship(array(
            'relationship' => 'widget_of_profile_tab',
            'relationship_guid' => $profile->guid,
            'inverse_relationship' => TRUE,
            'limit' => 0
        ));
        foreach ($widgets as $w) {
            if ($w->activity_id) {
                return true;
            }
            $widget_counter++;
        }
    }
    return $widget_counter > 0 ? false : true; # if in new inquiry someone removes all the widgets, we still have to count it as a new inquiry!
}

function get_profile_widgets($profile) {
    return elgg_get_entities_from_relationship(array(
        'relationship' => 'widget_of_profile_tab',
        'relationship_guid' => $profile->guid,
        'inverse_relationship' => TRUE,
        'limit' => 0
    ));
}

function renumber_widgets($profile) {
    $widgets = Array();
    foreach(get_profile_widgets($profile) as $w) {
        array_push($widgets, $w);
    }
    usort($widgets, function($a, $b) {
        if ($a->order == $b->order) {
            return 0;
        }
        return ($a->order < $b->order) ? -1 : 1;
    });
    $count = 0;
    foreach($widgets as $w) {
        $count += 1;
        $w->order = 10 * ($count - 50);
        $w->save();
    }
}

function create_or_remove_widgets($inquiry, $activities) {
    global $phases_data;

    $profiles = elgg_get_entities(array('types' => 'object', 'subtypes' => 'tabbed_profile', 'container_guid' => $inquiry->guid));
    foreach($profiles as $profile) {
        $widgets = get_profile_widgets($profile);
        $phase = $phases_data[$profile->order - 1];

        // we only add or remove from selected phases... when phase gets unselected it keeps the widgets.. so that the state in configuration interface (activities)
        // is preserved as well as widget settings.. data would be preserved even if we removed and readded widgets
        if(in_array((string)$phase['phase'], str_split($inquiry->phases))) {

            $widgets_in_phase = array();
            foreach ($phase['tasks'] as $task) {
                if (strlen($task['widget']) > 0) {
                    array_push($widgets_in_phase, $task['activity_id']);
                }
            }

            $widget_created = false;

            foreach ($phase['tasks'] as $task) {
                if (strlen($task['widget']) > 0) {
                    $widget = null;
                    foreach ($widgets as $w) {
                        if ($w->activity_id == $task['activity_id']) {
                            $widget = $w;
                        }
                    }
                    $widget_should_exist = strpos($activities, $task['activity_id']) !== false;
                    #$widget_should_exist = strpos($activities, $task['activity_id']) !== false;
                    #$widget_should_exist = $task['activity_id'] == '9d701';
                    if ($widget_should_exist && !$widget) {
                        _create_widget($task['widget'], $task['order'], $task['column'], $inquiry->guid, $profile->guid, $task['activity_id'], $task['title']);
                        $widget_created = true;
                    }
                    if (!$widget_should_exist && $widget) {
                        remove_entity_relationship($widget->guid, 'widget_of_profile_tab', $profile->guid);
                        $widget->delete();
                    }
                }
            }

            if($widget_created) {
                renumber_widgets($profile); //avoid duplicate order numbers -> it might cause problems in Elgg
            }
        }
    }
}

// creates a widget
function _create_widget($handler, $order, $column, $group_guid, $profile_guid, $activity_id, $title) {
    $widget = new ElggWidget;
    $widget->owner_guid = $group_guid;
    $widget->container_guid = $group_guid;
    $widget->access_id = get_default_access();
    if(strlen($title) > 0) {
        $widget->title = $title;
    }
    $widget->save();
    $widget->handler = $handler;
    $widget->context = "groups";
    if($column == 2) {
        $widget->column = 1;
    } else
    {
        $widget->column = 2;
    }
    $widget->order = 10 * ($order - 50);
    $widget->activity_id = $activity_id;
    add_entity_relationship($widget->guid, "widget_of_profile_tab", $profile_guid);
}

function enabled_activities($inquiry_id, $_phase = null, $force_all = false) {
    global $phases_data;

    $activities = array();

    $phases = str_split(get_entity($inquiry_id)->phases);

    if($force_all) { // this is used in confuguration interface to get all selected activities meaning in currently deselected phases as well
        $phases = str_split('123456');
    }

    $profiles = elgg_get_entities(array('types' => 'object', 'subtypes' => 'tabbed_profile', 'container_guid' => $inquiry_id));
    foreach($profiles as $profile) {
        $phase = $phases_data[$profile->order - 1];

        $current_phase = (string)$phase['phase'];

        if((!$_phase && in_array($current_phase, $phases)) || ($_phase && in_array((string)$_phase, $phases) && (string)$_phase == $current_phase)) {

            $widgets = elgg_get_entities_from_relationship(array(
                'relationship' => 'widget_of_profile_tab',
                'relationship_guid' => $profile->guid,
                'inverse_relationship' => TRUE,
                'limit' => 0,
            ));

            foreach ($phase['tasks'] as $task) {
                if (strlen($task['widget']) > 0) {
                    $widget = null;
                    foreach ($widgets as $w) {
                        if ($w->activity_id == $task['activity_id']) {
                            $widget = $w;
                        }
                    }
                    if ($widget && (!$_phase || $_phase == $profile->order)) {
                        array_push($activities, $task['activity_id']);
                    }
                }
            }
        }
    }

    return $activities;
}

//get all skills that are possible to practice for a given phase
//excluding the things in brackets
function skills_for_phase($phase) {
    global $phases_data;
    $phase = $phase - 1;

    $skills = Array();

    foreach($phases_data[$phase]['tasks'] as $task) {
        foreach($task['skills'] as $skill) {
            array_push($skills, strip_brackets($skill));
        }
    }
    asort($skills);
    return array_unique($skills);
}

function strip_brackets($text) {
    $pos = strpos($text, '(');
    if($pos) {
        return chop(substr($text, 0, $pos));
    } else {
        return $text;
    }
}

function all_activities_ids() {
    $activities = Array();
    global $phases_data;
    foreach($phases_data as $phase) {
        foreach($phase['tasks'] as $task) {
            array_push($activities, $task['activity_id']);
        }
    }
    return $activities;
}

function phase_tasks($phase) {
    global $phases_data;
    return $phases_data[$phase - 1]['tasks'];
}
