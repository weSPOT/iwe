<?php
/**
 * WeSpot specific Library of functions primarily to communicate with the MindMeister services
 *
 * Also has the debug function in here as it was convenient for use and library includes.
 */

/** Turn debugging message on and off */
global $debug_wespot_mindmeister;
$debug_wespot_mindmeister = true;

/** The url for the MindMeister service calls */
global $serviceRootMindMeister;
$serviceRootMindMeister = elgg_get_plugin_setting('mindmeister_url', 'wespot_mindmeister');

/** The MindMeister App Key required when making certain service calls to MindMeister */
global $weSpotElggMindMeisterKey;
$weSpotElggMindMeisterKey = elgg_get_plugin_setting('mindmeister_apikey', 'wespot_mindmeister');

/** The MindMeister App Secret required when making certain service calls to MindMeister */
global $weSpotElggMindMeisterSecret;
$weSpotElggMindMeisterSecret = elgg_get_plugin_setting('mindmeister_apisecret', 'wespot_mindmeister');

/** The data folder to store maps in **/
global $weSpotElggMindMeisterDataFolder;
$weSpotElggMindMeisterDataFolder = elgg_get_data_path().'mindmeistermaps/';


/**
 * If debugging is turned on, output the given message to the PHP error log.
 */
function debugWespotMindMeister($message) {
	global $debug_wespot_mindmeister;

	if ($debug_wespot_mindmeister) {
		error_log($message);
	}
}

/**
For Reference - MindMeiseter parameters that can be sent to a external/show call:
api_key - your developer api key
file[id] - unique integer identifier for this file
file[name] - file name (including the extension)
file[allow_export] - flag that enables/disables export and print functionality on the map view
file[view_only] - setting this flag to true will cause the map to be displayed in view only mode
file[hide_close_button] - setting this flag to true will hide the close button on the map view
file[hide_sidebar] - setting this flag to true will show the sidebar in its minimized state
file[use_url_params] - setting this flag to true will use the exact 'newcopy_url' or 'overwrite_url' instead of using parameters inside the post data
file[indexable_text] - setting this flag to true will save back a .txt file containing the indexable text of the mind map
file[download_url] - if you have the file stored on your server, and do not wish to post it using multipart, we will use this url to fetch and load it in the editor
file[content] - the file, as multipart/form-data
Note: if neither of the above two options are present or valid, a new map will be opened in the editor
file[save_action] - when the user presses the save button we will post the resulting file to one of the 2 urls described below. Parameter takes value 'o' for overwrite and 's' for save as new copy.
file[newcopy_url] - URL where we will post the resulting map when the user clicks the Save button
file[overwrite_url] - same as above
file[success_url] - URL where we will redirect the user after he clicks the Close buttons
external_user_name - The name of the user editing the map (first name and last name space separated)
*/


/**
 * Create a new MindMeister map file locally for the filename given, by copying the template file.
 *
 * @param $filename the file name of the local MindMeister map on this server (.mind file)
 * @param $userguid the guid for the user who is creating this new MindMap (used for the folder name in the data area)
 */
function createNewMindMeisterMap($filename, $userguid) {
	global $weSpotElggMindMeisterDataFolder;

	$mindmapdir = $weSpotElggMindMeisterDataFolder;
	if (!file_exists($mindmapdir)) {
		mkdir($mindmapdir);
	}
	$filepath = $mindmapdir.$userguid."/";
	if (!file_exists($filepath)) {
		mkdir($filepath);
	}
	//copy template file to data area.
	if (!file_exists($mindmapdir.$filename)) {
		copy(elgg_get_plugins_path().'wespot_mindmeister/lib/template.mind', $filepath.$filename);
	}
}

/**
 * Get the url for opening a MindMeister map for editing.
 *
 * @param $mindmap_guid the guid of the map to delete.
 * @param $successURL the url to pass to MindMeister for it to call if the close button is pushed.
 * (Currently the close button is turned off, so this is not used)
 * @param $filename the file name of the local MindMeister map on this server (.mind file)
 * @param $username the user name to send to MindMeister to display in the map.
 * @return the url to be added to the MindMeister IFrame src.
 */
function editMindMeisterMapURL($mindmap_guid, $successURL, $filename, $username) {
	global $serviceRootMindMeister, $weSpotElggMindMeisterKey, $weSpotElggMindMeisterSecret;

	$api_sig = md5($weSpotElggMindMeisterSecret.$filename.$mindmap_guid);

	$overwriteurl = elgg_get_site_url().'services/api/wespot_mindmeister/xml?method=wespot_mindmeister.savemap&filename='.$filename.'&guid='.$mindmap_guid.'&sig='.$api_sig;
	$downloadurl = elgg_get_site_url().'services/api/wespot_mindmeister/xml?method=wespot_mindmeister.loadmap&filename='.$filename.'&guid='.$mindmap_guid.'&sig='.$api_sig;

	$callurl = $serviceRootMindMeister.'external/show?';
	$callurl.= 'api_key='.$weSpotElggMindMeisterKey.'&';

	// Put this 'success_url' back if the close button is added back.
	//$callurl.= 'file[success_url]='.urlencode($successURL).'&';
	$callurl.= 'file[hide_close_button]=true&';

	$callurl.= 'file[download_url]='.urlencode($downloadurl).'&';
	$callurl.= 'file[overwrite_url]='.urlencode($overwriteurl).'&';
	$callurl.= 'file[save_action]=o&';
	$callurl.= 'file[id]='.$mindmap_guid.'&';
	$callurl.= 'file[allow_export]=on&';
	$callurl.= 'file[use_url_params]=true&';
	$callurl.= 'file[hide_sidebar]=true&';
	$callurl.= 'external_user_name='.urlencode($username).'&';
	$callurl.= 'file[name]='.urlencode($filename);

	return $callurl;
}

/**
 * Get the url for opening a MindMeister map for viewing only.
 *
 * @param $mindmap_guid the guid of the map to delete.
 * @param $successURL the url to pass to MindMeister for it to call if the close button is pushed.
 * (Currently the close button is turned off, so this is not used)
 * @param $filename the file name of the local MindMeister map on this server (.mind file)
 * @param $username the user name to send to MindMeister to display in the map.
 * @return the url to be added to the MindMeister IFrame src.
 */
function viewMindMeisterMapURL($mindmap_guid, $successURL, $filename, $username) {
	global $serviceRootMindMeister, $weSpotElggMindMeisterKey, $weSpotElggMindMeisterSecret;

	$api_sig = md5($weSpotElggMindMeisterSecret.$filename.$mindmap_guid);

	$downloadurl = elgg_get_site_url().'services/api/wespot_mindmeister/xml?method=wespot_mindmeister.loadmap&filename='.$filename.'&guid='.$mindmap_guid.'&sig='.$api_sig;

	$callurl = $serviceRootMindMeister.'external/show?';
	$callurl.= 'api_key='.$weSpotElggMindMeisterKey.'&';

	// Put this 'success_url' back if the close button is added back.
	//$callurl.= 'file[success_url]='.urlencode($successURL).'&';
	$callurl.= 'file[hide_close_button]=true&';

	$callurl.= 'file[download_url]='.urlencode($downloadurl).'&';
	$callurl.= 'file[id]='.$mindmap_guid.'&';
	$callurl.= 'file[allow_export]=off&';
	$callurl.= 'file[hide_sidebar]=true&';
	$callurl.= 'file[name]='.urlencode($filename).'&';
	$callurl.= 'external_user_name='.$username.'&';
	$callurl.= 'file[view_only]=true';

	return $callurl;
}

/**
 * Delete the MindMiester map for the given guid from the data area.
 *
 * @param $mindmap_guid the guid of the map to delete.
 * @return true if it successeded, else false;
 */
function deleteMindMeisterMap($mindmap_guid) {
	global $weSpotElggMindMeisterDataFolder;

	$mindmap = get_entity($mindmap_guid);

	//delete file from server.
	$filepath = $weSpotElggMindMeisterDataFolder.$mindmap->owner_guid."/".$mindmap->map_filename;
	debugWespotMindMeister($filepath);
	if (is_readable($filepath)) {
		if (unlink($filepath)) {
			return true;
		}
	}

	return false;
}

/**
 * check if a mindmeister file of the given name exists on the server.
 *
 * @param $filename the file name of the local MindMeister map on this server to check (.mind file)
 * @param $userguid the guid for the user who is creating this new MindMap (used for the folder name in the data area)
 * @return true if the file exsits else false;
 */
function doesMindMeisterFileExist($filename, $userguid) {
	global $weSpotElggMindMeisterDataFolder;

	if (file_exists($weSpotElggMindMeisterDataFolder.$userguid."/".$filename)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Called by MindMeister to load a MindMeister map.
 *
 * @param $filename, the filename of the map to load.
 * @param $mindmap_guid, the guid of the map whose file to load.
 * @param $sig, the signature to check the passed data against.
 */
function loadMindMeisterMap($filename, $mindmap_guid, $sig) {
	global $serviceRootMindMeister, $weSpotElggMindMeisterKey, $weSpotElggMindMeisterSecret, $weSpotElggMindMeisterDataFolder;

	$mindmapdir = $weSpotElggMindMeisterDataFolder;
	$mindmap = get_entity($mindmap_guid);

	if ( $sig === md5($weSpotElggMindMeisterSecret.$filename.$mindmap_guid) ) {

		if (!$mindmap || $mindmap->map_filename != $filename) {
			register_error(elgg_echo('wespot_mindmeister:load:failure'));
		} else {
			$path = $mindmapdir.$mindmap->owner_guid."/".$filename;

			if (file_exists($path)) {
				$mm_type="application/mindmeister";

				debugWespotMindMeister('About to SEND: '.$path);

				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-Type:application/mindmeister");
				header("Content-Length: " .(string)(filesize($path)) );
				header('Content-Disposition: attachment; filename="'.basename($path).'"');
				header("Content-Transfer-Encoding: binary\n");
				ob_clean();
				flush();
				readfile($path); // outputs the content of the file
				exit();
			} else {
				register_error(elgg_echo('wespot_mindmeister:load:filemissing'));
			}
		}
	} else {
		register_error(elgg_echo('wespot_mindmeister:sig:failure'));
	}
}

/**
 * Called by MindMeister to save a MindMeister map.
 *
 * @param $filename, the filename of the map to save.
 * @param $mindmap_guid, the guid of the map whose file to save
 * @param $sig, the signature to check the passed data against.
 */
function saveMindMeisterMap($filename, $mindmap_guid, $sig) {
	global $serviceRootMindMeister, $weSpotElggMindMeisterKey, $weSpotElggMindMeisterSecret, $weSpotElggMindMeisterDataFolder;

	debugWespotMindMeister("SAVING: ".$filename);

	$uploaddir = $weSpotElggMindMeisterDataFolder;
	if ($sig === md5($weSpotElggMindMeisterSecret.$filename.$mindmap_guid) ) {

		$mindmap = get_entity($mindmap_guid);

		// !$mindmap->canEdit() //failing so removed for now.

		if (!isset($mindmap) || $mindmap->map_filename != $filename) {
			register_error(elgg_echo('wespot_mindmeister:save:failure'));
		} else {
			debugWespotMindMeister("SAVING 1");

			foreach($_FILES as $name => $value) {

				$error    = $value['error'];
				$size     = $value['size'];
				$tmp_name     = $value['tmp_name'];
				$passedfilename     = $value['name'];

				switch ($error) {
					case UPLOAD_ERR_OK:
						debugWespotMindMeister('There is no error, the file uploaded with success');
						break;
					case UPLOAD_ERR_INI_SIZE:
						debugWespotMindMeister('The uploaded file exceeds the upload_max_filesize directive in php.ini');
						break;
					case UPLOAD_ERR_PARTIAL:
						debugWespotMindMeister('The uploaded file was only partially uploaded');
						break;
					case UPLOAD_ERR_NO_FILE:
						debugWespotMindMeister('No file was uploaded');
						break;
					case UPLOAD_ERR_CANT_WRITE:
						debugWespotMindMeister('Failed to write file to disk.');
						break;
					case UPLOAD_ERR_EXTENSION:
						debugWespotMindMeister('File upload stopped by extension.'); //Introduced in PHP 5.2.0
						break;
					default:
						debugWespotMindMeister('File save - Unknown error.');
						break;
				}

				if (is_uploaded_file($tmp_name)) {

					$path_parts = pathinfo($passedfilename);
					$estensione = $path_parts['extension'];
					if($estensione != 'mind') {
						register_error(elgg_echo('wespot_mindmeister:filetype:failure'));
					} else {
						$uploadfile = $uploaddir.$mindmap->owner_guid."/".basename($passedfilename);

						debugWespotMindMeister('About to SAVE:'.$uploadfile);

						if (move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)) {
							system_message(elgg_echo('wespot_mindmeister:save:success'));
						} else {
							register_error(elgg_echo('wespot_mindmeister:move:failure'));
						}
					}
				} else {
					register_error(elgg_echo('wespot_mindmeister:upload:failure'));
				}
			}
		}
	} else {
		register_error(elgg_echo('wespot_mindmeister:sig:failure'));
	}
}