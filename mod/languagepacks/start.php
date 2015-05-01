<?php
/**
 * Elgg Language Packs
 *
 * @package ElggLanguagePacks
 */

elgg_register_event_handler('init', 'system', 'languagepacks_init');

function languagepacks_init() {
	elgg_register_admin_menu_item('administer', 'languagepacks', 'administer_utilities');
	elgg_extend_view('css/admin', 'css/elements/languagepacks');
	$action_path = elgg_get_plugins_path() . 'languagepacks/actions/languagepacks';
	elgg_register_action('languagepacks/import', "$action_path/import.php", 'admin');
    elgg_register_action('languagepacks/export', "$action_path/export.php", 'admin');
    elgg_register_action('languagepacks/delete', "$action_path/delete.php", 'admin');
}

function elgglp_get_installed_translations() {
    // register translations for install module
    global $CONFIG;
    register_translations($CONFIG->path . "install/languages/");
    // rebuild $CONFIG->language_paths for every plugin
    $plugins = elgg_get_plugins('all');
    foreach ( $plugins as $plugin ) {
        // this only loads 'en' and current language
        $plugin->start(ELGG_PLUGIN_REGISTER_LANGUAGES);
    }
    // now load all languages in each path
    return get_installed_translations();
}
