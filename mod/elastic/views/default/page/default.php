<?php
/**
 * Elgg pageshell
 * The standard HTML page shell that everything else fits into
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title']       The page title
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

if (!elgg_is_logged_in() && elgg_get_context() != 'login')
	// forward to login page
	forward ('/');

// backward compatability support for plugins that are not using the new approach
// of routing through admin. See reportedcontent plugin for a simple example.
if (elgg_get_context() == 'admin') {
	if (get_input('handler') != 'admin') {
		elgg_deprecated_notice("admin plugins should route through 'admin'.", 1.8);
	}
	elgg_admin_add_plugin_settings_menu();
	elgg_unregister_css('elgg');
	echo elgg_view('page/admin', $vars);
	return true;
}

// render content before head so that JavaScript and CSS can be loaded. See #4032
$topbar = elgg_view('page/elements/topbar', $vars);
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$body = elgg_view('page/elements/body', $vars);
$footer = elgg_view('page/elements/footer', $vars);

// add header different if front
if(elgg_get_context()=='front') {
	$header = elgg_view('page/elements/header_front', $vars);
	$header_css = 'livpast-page-header-front';
} else {
	$header = elgg_view('page/elements/header', $vars);
	$header_css = 'livpast-page-header';
}
$lemonbar = elgg_view('page/elements/topbar', $vars);

// Set the content type
header("Content-type: text/html; charset=UTF-8");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php echo elgg_view('page/elements/head', $vars); ?>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="viewport" content="width=device-width" />
</head>
<body>

	<noscript>
	 <strong>For full functionality of this site it is necessary to enable JavaScript.
	 Here are the <a href="http://www.enable-javascript.com/" target="_blank">
	 instructions how to enable JavaScript in your web browser</a>.
	 </strong>
	</noscript>

	<div class="elgg-page-messages">
		<?php echo $messages; ?>
	</div>
	
	<div id="elastic-site-wrapper">
		<div id="elastic-topbar">
			<div class="elastic-content-wrapper">
				<div class="elastic-content">
					<?php echo $topbar; ?>
				</div>
			</div>
		</div>
		
		<div id="elastic-header" class="elastic-wrapper">
			<div id="elastic-header-content" class="elastic-wrapper-inside">
				<div class="elastic-content">
				<?php echo $header; ?>
				</div>
				<!--
				<div id="elastic-main-menu-wrapper">
					<div class="elastic-menu-wrapper">
						<div id="elastic-main-menu">
							<ul class="elastic-menu">
								<li>Home</li>
								<li>First</li>
								<li>Second</li>
								<li>Third</li>
								<li>About</li>
								<li>Contact</li>
							</ul>
						</div>
					</div>
				</div> -->
			</div>
		</div>
		
		<div id="elastic-main-wrapper" class="elastic-wrapper">
			<?php echo $body; ?>
		</div>
		<div id="elastic-footer" class="elastic-wrapper">
			<?php echo $footer; ?>
		</div>		
		
	</div>
	
<!--	
<div class="elgg-page elgg-page-default">
	

	
	<div class="elgg-page-topba-fake">
		<div class="elgg-inner">
			<?php// echo $topbar; ?>
		</div>
	</div>
	
	<div class="<?php// echo $header_css; ?>">
		<div class="elgg-inner">
			<?php// echo $header; ?>
		</div>
		<div class="elgg-above-main"></div>
	</div>
	
	<div class="elgg-page-body">
		<div class="elgg-inner">
			<?php //echo $body; ?>
		</div>
	</div>
	<div class="elgg-page-footer">
		<div class="elgg-inner">
			<?php// echo $footer; ?>
		</div>
	</div>
</div> -->
<?php echo elgg_view('page/elements/foot'); ?>
</body>
</html>