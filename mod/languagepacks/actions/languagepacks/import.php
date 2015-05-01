<?php
require_once elgg_get_plugins_path() . 'languagepacks/lib/elgg_language_packs/elgg_language_packs.php';

$file2upload = $_FILES['upload']['tmp_name'];
if ( !$file2upload ) {
	register_error(elgg_echo('languagepacks:error:upload'));
	forward(REFERER);
}

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

$filters['overwrite'] = (bool)get_input('overwrite');

$filters['ignore_en'] = (bool)get_input('ignore-en');

$filters['dst_dir'] = elgg_get_root_path();

$filters['needs_meta'] = true;

$filters['elgg_release'] = array_keys(elgglp_core_plugins());

$callback = 'elgglp_copy_languagemod';

$zip = new ZipArchive;
$res = $zip->open($file2upload);
if ( $res === true ) {
	$newdir = elgglp_tempdir();
	$zip->extractTo($newdir);
	$zip->close();
	unlink($file2upload);
	switch ( elgglp_recurse_language_pack($newdir, $filters, $callback) ) {
		case ELGGLP_ERR_STRUCTURE:
            elgglp_deltree($newdir);
			register_error(elgg_echo('languagepacks:error:structure'));
			forward(REFERER);
		case ELGGLP_ERR_VERSION:
            elgglp_deltree($newdir);
			register_error(elgg_echo('languagepacks:error:version'));
			forward(REFERER);
		case ELGGLP_OK:
            elgglp_deltree($newdir);
            $ts = time();
            $token = generate_action_token($ts);
            $flush_link = elgg_get_site_url() . "action/admin/site/flush_cache?__elgg_ts=$ts&__elgg_token=$token";
			system_message(sprintf(elgg_echo('languagepacks:import:success'), $flush_link));
	}
}
