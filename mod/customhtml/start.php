<?php

	/**
	 * Elgg Custom Static Page plugin
	 *
	 * @package Elgg Custom Static Page
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Alex Falk, RiverVanRain
	 * @URL http://weborganizm.org/crewz/p/1983/personal-net
	 * @copyright (c) weborganiZm 2k13
	 */

function customhtml_init() {
global $CONFIG;
elgg_register_page_handler('screencasts','screencasts_page_handler');

elgg_register_page_handler('about','about_page_handler');

elgg_register_page_handler('contacts','contacts_page_handler');

elgg_register_page_handler('terms','terms_page_handler');

elgg_register_page_handler('with_captcha','with_captcha_page_handler');
				
// Add menu link
$item = new ElggMenuItem('screencasts', elgg_echo('help'), 'screencasts');
elgg_register_menu_item('site', $item);

$about = new ElggMenuItem('about', elgg_echo('about'), 'about');
elgg_register_menu_item('site', $about);

$contacts = new ElggMenuItem('contacts', elgg_echo('contacts'), 'contacts');
elgg_register_menu_item('site', $contacts);

$terms = new ElggMenuItem('terms', elgg_echo('terms'), 'terms');
elgg_register_menu_item('site', $terms);

$with_captcha = new ElggMenuItem('with_captcha', elgg_echo('with:captcha'), 'with_captcha');
elgg_register_menu_item('site', $with_captcha);

/**
 * Elgg captcha plugin
 *
 * @package ElggCaptcha
 */

    // Register page handler for captcha functionality
    elgg_register_page_handler('captcha','captcha_page_handler');

    // Extend CSS
    elgg_extend_view('css/elgg','captcha/css');

    // Number of background images
    $CONFIG->captcha_num_bg = 5;

    // Default length
    $CONFIG->captcha_length = 5;

    // Register a function that provides some default override actions
    elgg_register_plugin_hook_handler('actionlist', 'captcha', 'captcha_actionlist_hook');

    // Register captcha page as public page for walled-garden
    elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'captcha_public');

    // Register actions to intercept
    $actions = array();
    $actions = elgg_trigger_plugin_hook('actionlist', 'captcha', null, $actions);

    if (($actions) && (is_array($actions))) {
        foreach ($actions as $action) {
            elgg_register_plugin_hook_handler("action", $action, "captcha_verify_action_hook");
        }
    }
}

function screencasts_page_handler() {
	require_once dirname(__FILE__) . '/pages/help.php';
	return true;
}

function about_page_handler() {
	require_once dirname(__FILE__) . '/pages/about.php';
	return true;
}

function contacts_page_handler() {
	require_once dirname(__FILE__) . '/pages/contacts.php';
	return true;
}

function terms_page_handler() {
	require_once dirname(__FILE__) . '/pages/terms.php';
	return true;
}

function with_captcha_page_handler() {
	require_once dirname(__FILE__) . '/pages/with_captcha.php';
	return true;
}

/**
 * Elgg captcha plugin
 *
 * @package ElggCaptcha
 */
function captcha_public($hook, $handler, $return, $params) {
    $pages = array('captcha/.*');

    if (is_array($return))
        $pages = array_merge($pages, $return);

    return $pages;
}

function captcha_page_handler($page) {
    global $CONFIG;

    if (isset($page[0])) {
        set_input('captcha_token',$page[0]);
    }

    include($CONFIG->pluginspath . "customhtml/captcha.php");
}

/**
 * Generate a token to act as a seed value for the captcha algorithm.
 */
function captcha_generate_token() {
    return md5(generate_action_token(time()).rand()); // Use action token plus some random for uniqueness
}

/**
 * Generate a captcha based on the given seed value and length.
 *
 * @param string $seed_token
 * @return string
 */
function captcha_generate_captcha($seed_token) {
    global $CONFIG;

    /**
     * We generate a token out of the random seed value + some session data,
     * this means that solving via pr0n site or indian cube farm becomes
     * significantly more tricky (we hope).
     *
     * We also add the site secret, which is unavailable to the client and so should
     * make it very very hard to guess values before hand.
     *
     */
    return strtolower(substr(md5(generate_action_token(0) . $seed_token), 0, $CONFIG->captcha_length));
}

/**
 * Verify a captcha based on the input value entered by the user and the seed token passed.
 *
 * @param string $input_value
 * @param string $seed_token
 * @return bool
 */
function captcha_verify_captcha($input_value, $seed_token) {
    if (strcasecmp($input_value, captcha_generate_captcha($seed_token)) == 0) {
        return true;
    }

    return false;
}

/**
 * Listen to the action plugin hook and check the captcha.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function captcha_verify_action_hook($hook, $entity_type, $returnvalue, $params) {
    $token = get_input('captcha_token');
    $input = get_input('captcha_input');

    if (($token) && (captcha_verify_captcha($input, $token))) {
        return true;
    }

    register_error(elgg_echo('captcha:captchafail'));

    // forward to referrer or else action code sends to front page
    forward(REFERER);

    return false;
}

/**
 * This function returns an array of actions the captcha will expect a captcha for, other plugins may
 * add their own to this list thereby extending the use.
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 */
function captcha_actionlist_hook($hook, $entity_type, $returnvalue, $params) {
    if (!is_array($returnvalue)) {
        $returnvalue = array();
    }

    $returnvalue[] = 'register';
    $returnvalue[] = 'user/requestnewpassword';
	$returnvalue[] = 'with_captcha';

    return $returnvalue;
}
	
elgg_register_event_handler('init', 'system', 'customhtml_init');
?>