<?php
elgg_load_library('elgg:wespot_arlearnservices');

$path = explode('/', $vars['path']);
array_shift($path);

$page_handler = array_shift($path);
$page_params_str = implode('/', $path);

ob_start();
elgg_set_viewtype('json');
page_handler($page_handler, $page_params_str);
elgg_set_viewtype('default');
$out = ob_get_contents();
ob_end_clean();

$json = json_decode($out);
foreach ($json as $child) foreach ($child as $grandchild) $json = $grandchild;
/* Removing duplicates
   This is unnecessary when #4504 is fixed. */
if (version_compare(get_version(true), '1.8.7', '<')) {
    $buggy = $json;
    $json = array();
    $guids = array();
    foreach ($buggy as $item) {
	$guids[] = $item->guid;
    }
    $guids = array_unique($guids);
    foreach (array_keys($guids) as $i) {
	$json[$i] = $buggy[$i];
    }
}

if (!is_array($json)) {
	exit();
}

$items = array();
foreach($json as $item) {
	$items[] = new ElggObject($item);
}


header('Content-type: text/plain');
echo elgg_view('page/components/list', array("items" => $items));
