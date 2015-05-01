<?php
/**
 * ARLearn Message board: delete message action
 *
 * @package Elgg wespot_msg
 */

action_gatekeeper();

$entity_guid = (int) get_input('entity_guid');
$entity = get_entity($entity_guid);

if ($entity && elgg_instanceof($entity, "object", "arlearn_msg") && $entity->canEdit() && $entity->delete()) {
	system_message(elgg_echo("wespot_msg:deleted"));
} else {
	system_message(elgg_echo("wespot_msg:notdeleted"));
}

forward(REFERER);
