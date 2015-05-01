<?php
if ( get_current_language() != 'en' ) {
    register_error(elgg_echo('languagepacks:error:delete_lang'));
    forward(REFERER);
}

require_once elgg_get_plugins_path() . 'languagepacks/lib/elgg_language_packs/elgg_language_packs.php';

$release = get_version(true);

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

$filters['ignore_en'] = true;
$filters['elgg_release'] = $release;
$filters['needs_manifest'] = true;

$olddir = elgg_get_root_path();

$callback = 'elgglp_delete_languages';

switch ( elgglp_recurse_language_pack($olddir, $filters, $callback) ) {
	case ELGGLP_ERR_STRUCTURE:
		register_error(elgg_echo('languagepacks:error:structure'));
		forward(REFERER);
	case ELGGLP_ERR_VERSION:
		register_error(elgg_echo('languagepacks:error:version'));
		forward(REFERER);
    case ELGGLP_OK:
        // if the language does not exist anymore, remove JavaScript file
        $languages = elgglp_get_installed_translations();
        $jsdir = "$olddir/views/default/js/languages";
        $jsfiles = array_merge(glob("$jsdir/??.php"), glob("$jsdir/??[-_]??.php"));
        foreach ( $jsfiles as $jsfile ) {
            // basic name of the file is the locale name
            $lang = basename($jsfile, '.php');
            if ( $lang != 'en' && !$languages[$lang] && file_exists($jsfile) ) {
                @unlink($jsfile);
            }
        }
        // return result of deletion
        $ts = time();
        $token = generate_action_token($ts);
        $flush_link = elgg_get_site_url() . "action/admin/site/flush_cache?__elgg_ts=$ts&__elgg_token=$token";
        system_message(sprintf(elgg_echo('languagepacks:delete:success'), $flush_link));
}
