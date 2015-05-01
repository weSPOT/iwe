<?php
/**
 * Duplicate a group
 */
 
$group_guid = (int) get_input("group_guid", 0);		
$group = get_entity($group_guid);

if (!$group || !($group instanceof ElggGroup)) {
	register_error(elgg_echo('group_tools:duplicate:notduplicated'));
	forward(REFERER);
}

elgg_set_ignore_access(true);

// create clone group
$new_group = clone $group;

// add name
if(get_input("new_name", 1))
	$new_group->name = get_input("new_name", 1);
else
	$new_group->name = $new_group->name." - copy";
		
// save clone group	
$new_group->save();

// fix group permissions
if($new_group->access_id > 2){
	$new_group->access_id = $new_group->group_acl;
	$new_group->save();
}

// add owner as member
$owner = new ElggUser;
$owner = get_entity($new_group->owner_guid);
$new_group->join($owner);

// add inquiry admins
if (elgg_is_active_plugin('group_operators')) {
	elgg_load_library('elgg:group_operators');
	$group_operators = get_group_operators($group);
	if($group_operators){
		foreach ($group_operators as $group_operator){
			$new_group->join($group_operator);
			add_entity_relationship($group_operator->guid, 'operator', $new_group->guid);
		}
	}
}

// add group icon
add_group_icon($group, $new_group);

// clone ARLearn game (needs to be done before cloning any ARLearn tasks)
$game = elgg_get_entities(array('type' => 'object', 'subtype' => 'arlearngame', 'owner_guid' => $group_guid));
if($game)
	new_arlearn_game($new_group->guid, $new_group->name);
$new_game = elgg_get_entities(array('type' => 'object', 'subtype' => 'arlearngame', 'owner_guid' => $new_group->guid));
$new_gameid = $new_game[0]->arlearn_gameid;

// delete tabs and widgets
$options = array(
	'type' => 'object',
	'subtype' => array('tabbed_profile', 'widget'),
	'container_guid' => $new_group->guid,
	'limit' => false,
);
$content = elgg_get_entities($options);
if($content){
	foreach ($content as $tab_or_widget)
		$tab_or_widget->delete();
}

// clone the group contents
$options = array(
	'type' => 'object',
	'container_guid' => $group_guid,
	'limit' => false,
);
$items = elgg_get_entities($options);
if ($items) {
	foreach ($items as $item){
	
		$subtype = get_subtype_from_id($item->subtype);
//		error_log($subtype);
		
		if(($subtype == 'widget') || ($subtype == 'answer') || ($subtype == 'page') || ($subtype == 'hypothesis') || ($subtype == 'notes') || ($subtype == 'conclusions') || ($subtype == 'reflection') || ($subtype == 'arlearntask') || ($subtype == 'arlearngame')) 
			continue; // skip these subtypes
	
		if (strstr($subtype, '_top'))
			$subtype = strstr($subtype, '_top', true); // lose the _top
		
		// clone the item	
		$new_item = clone $item;
		$new_item->container_guid = $new_group->guid;
		$new_item->save();
		$new_item->annotate($subtype, $new_item->description, $new_item->access_id);
		
		// clone any comments of the item
		if($item->countComments()){
			$comments = $item->getAnnotations('generic_comment', $limit = false);
			foreach ($comments as $comment)
				$new_item->annotate('generic_comment', $comment->value, $new_item->access_id, $comment->owner_guid);
		}
		
		// clone any sub-items
		if (strstr(get_subtype_from_id($item->subtype), '_top')){
			$options = array(
					'type' => 'object',
					'subtype' => $subtype,
					'container_guid' => $group_guid,
					'limit' => false,
				);
			$content = elgg_get_entities($options);
			if ($content) {
				foreach ($content as $sub_item){
					if($sub_item->parent_guid == $item->guid){ // if this is a sub-item to the current item
						// clone the sub-item and connect it to the new parent item
						$new_sub_item = clone $sub_item;
						$new_sub_item->container_guid = $new_group->guid;
						$new_sub_item->parent_guid = $new_item->guid;
						$new_sub_item->save();
						$new_sub_item->annotate($subtype, $new_sub_item->description, $new_sub_item->access_id);
					}
				}
			}
		}
		
		// annotate any responses to a forum topic
		if($subtype == 'groupforumtopic'){
			$options = array(
				'guid' => $item->guid,
				'annotation_name' => 'group_topic_post',
			);
			$content = elgg_get_annotations($options);
			if ($content) {
				foreach ($content as $reply){
					$new_item->annotate('group_topic_post', $reply->value, $new_item->access_id, $reply->owner_guid);
				}
			}
		}	
			
		// clone the widgets of a tab
		if($subtype == 'tabbed_profile'){
			// get all widgets of the group
			$options = array(
				'type' => 'object',
				'subtype' => 'widget',
				'container_guid' => $group_guid,
				'limit' => false,
			);
			$content = elgg_get_entities($options);
			if($content){
				foreach ($content as $widget){
					// check if the widget belongs to the tab
					if(check_entity_relationship($widget->guid, "widget_of_profile_tab", $item->guid)){
						// clone the widget	
						$new_widget = clone $widget;
						$new_widget->container_guid = $new_group->guid;
						$new_widget->owner_guid = $new_group->guid;
						$new_widget->save();
						$settings = get_all_private_settings($widget->guid);
						foreach ($settings as $key => $value)
							set_private_setting($new_widget->guid, $key, $value);
						add_entity_relationship($new_widget->guid, "widget_of_profile_tab", $new_item->guid);
					}	
				}
			}
		}
		
		// clone any ARLearn tasks
		if (get_subtype_from_id($item->subtype) == 'arlearntask_top'){
			elgg_load_library('elgg:wespot_arlearnservices');
			$teacherguid = get_loggedin_userid();
			$teacherprovider = elgg_get_plugin_user_setting('provider', $teacherguid, 'elgg_social_login');
			$teacheroauth = str_replace("{$teacherprovider}_", '', elgg_get_plugin_user_setting('uid', $teacherguid, 'elgg_social_login'));
			$usertoken = createARLearnUserToken($teacherprovider, $teacheroauth);
			$new_item->arlearn_id = null;
			$results = editARLearnTask($usertoken, $new_gameid, $new_item->title, $new_item->description, $new_item->task_type, $new_item->guid);
			if ($results) {
				$datareturned = json_decode($results);
				if (!isset($datareturned->error)) {
					$generalitemid = $datareturned->id;
					$new_item->arlearn_id = $generalitemid;
					$new_item->arlearn_gameid = $new_gameid;
				}
			}
			continue;
		}
				
		// clone any answers to the question
		if($subtype == 'question'){ 
			// get all answers from the group
			$options = array(
				'type' => 'object',
				'subtype' => 'answer',
				'container_guid' => $group_guid,
				'limit' => false,
			);
			$content = elgg_get_entities($options);
			if ($content) {
				foreach ($content as $answer){
					$question = get_entity($answer->question_guid);
					if($question->guid == $item->guid){ // if this is an answer to the current question
						// clone the answer and connect it to the question
						$new_answer = clone $answer;
						$new_answer->container_guid = $new_group->guid;
						$new_answer->question_guid = $new_item->guid;
						$new_answer->save();
						add_entity_relationship($new_item->getGUID(), "answer", $new_answer->getGUID());
					}
				}
			}
		}
			
		// copy mind map file
		if($subtype == 'mindmeistermap'){
			$map_path = elgg_get_data_path().'mindmeistermaps/'.$item->owner_guid.'/map'.$item->guid.$item->owner_guid.'.mind';
			$new_map_path = elgg_get_data_path().'mindmeistermaps/'.$new_item->owner_guid.'/map'.$new_item->guid.$new_item->owner_guid.'.mind';
			$new_item->map_filename = 'map'.$new_item->guid.$new_item->owner_guid.".mind";
			copy($map_path, $new_map_path);
		}
		
		// copy file
		if($subtype == 'file'){
			$prefix = "file/";
			$filename = $item->getFilenameOnFilestore();
			$filestorename = $item->getFilename();
			$filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
			$filepath = strstr($filename, $filestorename, true);
			$new_filestorename = elgg_strtolower(time().$item->originalfilename);
			$new_item->setFilename($prefix . $new_filestorename);
			copy($filename, $filepath.$new_filestorename);
		}
	}
}

elgg_set_ignore_access(false);
system_message(elgg_echo('group_tools:duplicate:duplicated'));
forward($new_group->getURL());


/**
 * Intialise a new game and run with ARLearn for the group given
 * @param $group_giud the unique id of the group
 * @param $group_name the name of the group
 * @return true if all goes well, else false;
 */
function new_arlearn_game($group_guid, $group_name) {

	if ($group_name == "") {
		return false;
	}

	elgg_load_library('elgg:wespot_arlearnservices');

	$added = false;

	$gamearray = elgg_get_entities(array('type' => 'object', 'subtype' => 'arlearngame', 'owner_guid' => $group_guid));
	debugWespotARLearn('GROUP GAME: '.print_r($gamearray, true));

	if ($gamearray === FALSE || count($gamearray) == 0) {
		$teacherguid = get_loggedin_userid();
		$teacherprovider = elgg_get_plugin_user_setting('provider', $teacherguid, 'elgg_social_login');
		$teacheroauth = str_replace("{$teacherprovider}_", '', elgg_get_plugin_user_setting('uid', $teacherguid, 'elgg_social_login'));
		$usertoken = createARLearnUserToken($teacherprovider, $teacheroauth);

		$teacher = get_entity(teacherguid);

		// check teacher known to ARLearn
		$exists = checkARLearnUserExists($teacherprovider, $teacheroauth);
		if (!$exists) {
			$results = createARLearnUser($teacherprovider, $teacheroauth, $teacher->email, $teacher->name);
			if ($results != false) {
				debugWespotARLearn('CHECK USER: '.print_r($results, true));
				$datareturned = json_decode($results);
				if (isset($datareturned->error)) {
					return false;
				}
			}
		}

		// register game on ARLEarn
		$results = createARLearnGame($usertoken, $group_name);
		if ($results != false) {
			debugWespotARLearn('ADD GAME: '.print_r($results, true));
			$datareturned = json_decode($results);
			if (!isset($datareturned->error)) {
				$gameid = $datareturned->gameId;

				// Register run on ARLEarn
				$results = createARLearnRun($usertoken, $gameid, $group_name);
				if ($results != false) {
					debugWespotARLearn('ADD WIDGET: '.print_r($results, true));

					$datareturned = json_decode($results);
					if (!isset($datareturned->error)) {
						$runid = $datareturned->runId;

						// get a list of students in the group and add all.
						$students = elgg_get_entities_from_relationship(array(
							'relationship' => 'member',
							'relationship_guid' => $group_guid,
							'inverse_relationship' => true,
							'type' => 'user',
						));

						if (isset($students) && count($students) > 0) {
							debugWespotARLearn('STUDENTS LIST: '.print_r($students, true));

							foreach($students as $student) {
								debugWespotARLearn('STUDENT: '.print_r($student, true));

								$studentid = $student->guid;
								$provider = elgg_get_plugin_user_setting('provider', $studentid, 'elgg_social_login');

								debugWespotARLearn('PROVIDER FOR STUDENT: '.print_r($provider, true));

								$oauth = str_replace("{$provider}_", '', elgg_get_plugin_user_setting('uid', $studentid, 'elgg_social_login'));

								if ( (isset($oauth) && $oauth != "") && (isset($provider) && $provider != -1)) {
									// check student exists.
									$exists = checkARLearnUserExists($provider, $oauth);
									$addtorun = false;
									if (!$exists) {
										$results = createARLearnUser($provider, $oauth, $student->email, $student->name);
										if ($results != false) {
											debugWespotARLearn('CREATE USER: '.print_r($results, true));
											$datareturned = json_decode($results);
											if (isset($datareturned->error)) {
												return false;
											} else {
												$addtorun = true;
											}
										}
									} else {
										$addtorun = true;
									}

									// adding student to run.
									if ($addtorun) {
										$results = addUserToRun($usertoken, $runid, $provider, $oauth);
										if ($results != false) {
											debugWespotARLearn('ADD STUDENT: '.print_r($results, true));
											$datareturned = json_decode($results);
											if (isset($datareturned->error)) {
												debugWespotARLearn('STUDENT ADDED: '.print_r($results, true));
												//return false;
											}
										} else {
											debugWespotARLearn('STUDENT NOT ADDED: '.print_r($results, true));
										}
									}
								}
							}
						} else {
							// If we get here it will be a new game not yet saved,
							// so just add the teacher as they will become the first student
							// Already checked if teacher exists
							// adding student to run.
							$results = addUserToRun($usertoken, $runid, $teacherprovider, $teacheroauth);
							if ($results != false) {
								debugWespotARLearn('ADD TEACHER AS STUDENT: '.print_r($results, true));
								$datareturned = json_decode($results);
								if (isset($datareturned->error)) {
									debugWespotARLearn('TEACHER ADDED ERROR: '.print_r($results, true));
									//return false;
								}
							} else {
								debugWespotARLearn('TEACHER NOT ADDED: '.print_r($results, true));
							}
						}

						$object = new ElggObject();
						$object->subtype = "arlearngame";

						//MB: LOGGED IN USER ACCESS ONLY - CHANGED TO PUBLIC FOR NOW
						//$object->access_id = 1; // LOGGED IN USERS
						$object->access_id = ACCESS_PUBLIC;

						$object->arlearn_gameid = $gameid;
						$object->arlearn_runid = $runid;
						$object->owner_guid = $group_guid;
						$object->container_guid = $group_guid;
						$object->save();

						$added = true;

						debugWespotARLearn('ADDING GAME TO GROUP: '.print_r($object, true));
					}
				}
			}
		}
	}

	return $added;
}

function add_group_icon($group, $new_group){

	$icon_sizes = elgg_get_config('icon_sizes');

	$prefix = "groups/" . $group->guid;
	$new_prefix = "groups/" . $new_group->guid;

	$filehandler = new ElggFile();
	$filehandler->owner_guid = $group->owner_guid;
	$filehandler->setFilename("groups/" . $group->guid . ".jpg");
	if ($filehandler->open("read")) {
		$contents = $filehandler->read($filehandler->size());
	}
	$filehandler->close();

	$new_filehandler = new ElggFile();
	$new_filehandler->owner_guid = $new_group->owner_guid;
	$new_filehandler->setFilename($new_prefix . ".jpg");
	$new_filehandler->open("write");
	$new_filehandler->write($contents);
	$new_filehandler->save();
	$new_filehandler->close();
	$new_filename = $new_filehandler->getFilenameOnFilestore();

	$sizes = array('tiny', 'small', 'medium', 'large');

	$thumbs = array();
	foreach ($sizes as $size) {
		$thumbs[$size] = get_resized_image_from_existing_file(
			$new_filename,
			$icon_sizes[$size]['w'],
			$icon_sizes[$size]['h'],
			$icon_sizes[$size]['square']
		);
	}

	if ($thumbs['tiny']) { // just checking if resize successful
		$thumb = new ElggFile();
		$thumb->owner_guid = $new_group->owner_guid;
		$thumb->setMimeType('image/jpeg');

		foreach ($sizes as $size) {
			$thumb->setFilename("{$new_prefix}{$size}.jpg");
			$thumb->open("write");
			$thumb->write($thumbs[$size]);
			$thumb->close();
		}
	}
	$new_group->icontime = time();
	create_metadata($new_group->guid, 'icontime', $new_group->icontime, 'integer', $new_group->owner_guid, ACCESS_PUBLIC);
}