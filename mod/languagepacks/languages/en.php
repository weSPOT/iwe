<?php
/**
 * Elgg Language Packs language file
 *
 * @package ElggLanguagePacks
 */

$loc = array(
	/// The title for the utilities page in the admin console
	'admin:administer_utilities:languagepacks' => 'Language packs',
	/// Appears in the import page and explains the features of the page
	'languagepacks:intro' => 'You can import a language pack, export any currently installed language mods, or delete unwanted language files from your Elgg installation',
	/// Selection of locales to import or export (toggling link)
	'languagepacks:select_locales:link' => 'Language Selection',
	/// Selection of core and bundled plugins to import or export (toggling link)
	'languagepacks:select_cores:link' => 'Core and Bundled Plugins',
    /// Selection of plugins to import or export (toggling link)
    'languagepacks:select_plugins:link' => 'Extra Plugins',
	/// Selection of locales to import or export (select element label)
	'languagepacks:select_locales:title' => 'Select the languages to import, export, or delete',
    /// Selection of core and bundled plugins (select element label)
    'languagepacks:select_cores:title' => 'Select the core and bundled plugins to import, export, or delete',
	/// Selection of extra plugins (select element label)
    'languagepacks:select_plugins:title' => 'Select the third party plugins to import, export, or delete',
	/// Button between the two drop-down boxes for the selection of languages to export
	'languagepacks:add:all' => 'Add All',
	/// Button between the two drop-down boxes for the selection of languages to export
	'languagepacks:add:selected' => 'Add Selected',
	/// Button between the two drop-down boxes for the selection of languages to export
	'languagepacks:remove:all' => 'Remove All',
	/// Button between the two drop-down boxes for the selection of languages to export
	'languagepacks:remove:selected' => 'Remove Selected',
	'languagepacks:export:title' => 'Create a language pack from your Elgg installation',
	'languagepacks:export:filename' => 'Choose a filename to save your pack under (leave blank for <i>elgg-languages.zip</i>)',
	/// Label for the ignore-english check box (follows the check box)
	'languagepacks:export:ignore_en' => 'Do not export original English language files regardless of locale selection',
	/// Title for the Export submit button (keep it short)
	'languagepacks:export:button' => 'Export',
	'languagepacks:import:title' => 'Import a language pack into your Elgg installation',
	'languagepacks:import:filename' => 'Select the Zip archive of the language pack to import',
	/// Label for the overwrite check box (follows the check box)
	'languagepacks:import:overwrite' => 'Overwrite existing language files with those in package',
	/// Label for the ignore-english check box (follows the check box)
	'languagepacks:import:ignore_en' => 'Always ignore original English language files in package',
	/// Title for the Import submit button (keep it short)
	'languagepacks:import:button' => 'Import',
	/// Status message shown in system message area at the top of the page
	'languagepacks:import:success' => 'Language pack import completed successfully. <a href="%s">Click here</a> to flush the caches and reload languages',
    /// Status message shown in system message area at the top of the page
    'languagepacks:delete:success' => 'Language pack deletion completed successfully. <a href="%s">Click here</a> to flush the caches and reload languages',
	/// Error message shown in system message area at the top of the page
	'languagepacks:error:upload' => 'Could not upload your file',
	/// Error message shown in system message area at the top of the page
	'languagepacks:error:structure' => 'Invalid language pack structure',
	/// Error message shown in system message area at the top of the page
	'languagepacks:error:version' => 'Invalid language pack version - does not match current Elgg installation',
    'languagepacks:delete:title' => 'Delete language mods from your Elgg installation',
    /// Title for the Delete submit button (keep it short)
    'languagepacks:delete:button' => 'Delete',
    /// The explanatory text for the delete form, quite verbose and spans several lines
    'languagepacks:delete:explain' => 'Click the button below to physically delete the language files that match your selections from the file system.<br>
    WARNING: This will permanently delete the files. You can reinstall them at any time if you have made a backup copy with the export button above.<br>
    NOTE: The English language files will NOT be deleted ever, regardless of your selections.',
    'languagepacks:error:delete_lang' => 'In order to safely delete language files from the system, you must ensure that you are running the delete action with English as your current language',
    /// Extra language code with country for language list
    'pt-br' => 'Brazilian Portuguese',
    /// Extra language code with country for language list
    'zh-tw' => 'Chinese (Taiwan)',
    /// Extra language code with country for language list
    'en-gb' => 'British English',
    /// Extra language code with country for language list
    'en-us' => 'US English',
);

add_translation('en', $loc);
