<?php
require_once elgg_get_plugins_path() . 'languagepacks/lib/elgg_language_packs/elgg_language_packs.php';

$release = get_version(true);

$file2download = trim(get_input('filename'));
if ( empty($file2download) ) {
	$file2download = 'elgg-languages.zip';
}
if ( substr($file2download, -4) != '.zip' ) {
	$file2download .= '.zip';
}

$filters = array();

$langstring = get_input('locales-selection');
if ( $langstring ) {
	$filters['langs'] = explode('|', $langstring);
} else {
	$filters['langs'] = null;
}
unset($langstring);

$projstring = get_input('cores-selection');
if ( $projstring ) {
    $filters['projs'] = explode('|', $projstring);
} else {
    $filters['projs'] = null;
}
unset($projstring);

$projstring = get_input('plugins-selection');
if ( $projstring ) {
    $filters['projs'] = array_merge((array)$filters['projs'], explode('|', $projstring));
}
unset($projstring);

$filters['ignore_en'] = (bool)get_input('ignore-en');
$filters['elgg_release'] = $release;
$filters['needs_manifest'] = true;

$newdir = elgglp_tempdir();
@mkdir("$newdir/languages", 0777, true);
@mkdir("$newdir/install/languages", 0777, true);
@mkdir("$newdir/mod", 0777, true);
$filters['dst_dir'] = $newdir;

$olddir = elgg_get_root_path();

$callback = 'elgglp_copy_languagemod';

// this adds the plugin version automatically and the Elgg version from $filters['elgg_release']
elgglp_create_languagepack_meta(null, $filters);

switch ( elgglp_recurse_language_pack($olddir, $filters, $callback) ) {
	case ELGGLP_ERR_STRUCTURE:
        elgg_deltree($newdir);
		register_error(elgg_echo('languagepacks:error:structure'));
		forward(REFERER);
	case ELGGLP_ERR_VERSION:
        elgg_deltree($newdir);
		register_error(elgg_echo('languagepacks:error:version'));
		forward(REFERER);
}

$zipfile = tempnam(sys_get_temp_dir(), 'zip');
elgglp_zip_folder($newdir, $zipfile);
header('Content-Type: application/zip');
header("Content-Disposition: attachment; filename=\"$file2download\"");
readfile($zipfile);
unlink($zipfile);
elgglp_deltree($newdir);
exit();
