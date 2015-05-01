<?php
/**
 * Elgg one-column layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 * @uses $vars['class']   Additional class to apply to layout
 */

$class = 'elastic-wrapper-inside clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

// navigation defaults to breadcrumbs
$nav = elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));

?>
<div id="elastic-main-content" class="<?php echo $class; ?>">

		<div class="elastic-content">
		<?php
			echo $nav;

			if (isset($vars['title'])) {
				echo elgg_view_title($vars['title']);
			}

			echo $vars['content'];

			// @deprecated 1.8
			if (isset($vars['area1'])) {
				echo $vars['area1'];
			}
		?>
			<div class="clearfix"></div>

	</div>
</div>