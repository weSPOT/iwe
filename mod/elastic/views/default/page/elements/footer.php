<?php
/**
 * Elgg footer
 * The standard HTML footer that displays across the site
 *
 * @package Elgg
 * @subpackage Core
 *
 */

/*
$powered_url = elgg_get_site_url() . "mod/elastic/graphics/wi.png";

$footer_bottom = elgg_view('output/url', array(
	'href' => 'http://www.webintelligence.ie',
	'text' => "<img src=\"$powered_url\" alt=\"Powered by Web Intelligence\"  />",
	'class' => 'bottom-logo',
	'is_trusted' => true,
));
*/

?>



<div id="elastic-footer-content" class="elastic-wrapper-inside">
	<div id="elastic-footer-left" class="elastic-content-wrapper">
		<div class="elastic-content">
			<?php echo elgg_view('page/elements/footer/footer_left'); ?> 
		</div>
	</div>
	<div id="elastic-footer-main" class="elastic-content-wrapper">
		<div class="elastic-content">
			<?php echo elgg_view('page/elements/footer/footer_main'); ?> 
		</div>
	</div>
	<div id="elastic-footer-right" class="elastic-content-wrapper">
		<div class="elastic-content">
			<?php echo elgg_view('page/elements/footer/footer_right'); ?> 
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<div class="elastic-wrapper-inside">
<?php echo $footer_bottom; ?> 
</div>

