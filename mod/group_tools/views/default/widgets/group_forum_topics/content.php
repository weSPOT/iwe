<?php
	$widget = $vars["entity"];
	$group = $widget->getOwnerEntity();

	$topic_count = sanitise_int($widget->topic_count, false);
	if(empty($topic_count)){
		$topic_count = 4;
	}

  // we get this here when saving widget preferences:
  $phase = (int) $vars['entity']->phase;

  if(!$phase) {
    $matches = null;
    preg_match("/tab\/(\d+)$/i", $_SERVER['REQUEST_URI'], $matches);
    $tab = $matches[1];
    if($tab) {
      $phase = get_entity($tab)->order;
    }
  }

  $activity_id = $vars["entity"]->activity_id;

  $options = array(
		'type' => 'object',
		'subtype' => 'groupforumtopic',
    'phase' => $phase,
    'activity_id' => $activity_id,
		'container_guid' => $group->getGUID(),
		'order_by' => 'e.last_action desc',
		'limit' => $topic_count,
		'full_view' => false,
		'pagination' => false,
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

	echo $content;

    $new_link = "";

    if (elgg_get_page_owner_entity()->canWriteToContainer())
    {
        $new_link = $phase ? elgg_view('output/url', array(
            'href' => "discussion/add/" . $group->getGUID() . "?phase=" . $phase . '&activity_id=' . $activity_id,
            'text' => elgg_echo('groups:addtopic'),
            'is_trusted' => true,
        )) : "Refresh to add topic";

        #$link = "<span>" . $new_link . "</span>";
    }

	if ($content) {
		$url = "discussion/owner/" . elgg_get_page_owner_entity()->getGUID();
		$more_link = elgg_view('output/url', array(
			'href' => $url . "?phase=" . $phase . '&activity_id=' . $activity_id,
			'text' => elgg_echo('widgets:discussion:more'),
			'is_trusted' => true,
		));
        if($new_link != "") { $new_link = ' | ' . "<span>" . $new_link . "</span>"; }
		echo "<span class=\"elgg-widget-more\">$more_link</span>" . $new_link;
	} else {
		echo elgg_echo('discussion:none');
        echo "<br/><br/>";
        echo "<div>" . $new_link . "</div>";;
	}
