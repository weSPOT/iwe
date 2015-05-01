<?php
/**
 * Elgg header logo
 */

$site = elgg_get_site_entity();
$site_name = $site->name;
$site_url = elgg_get_site_url();
?>

<div id="header-logo">
	<h1>
		<a class="elgg-heading-site" href="<?php echo $site_url; ?>">
			<?php echo $site_name; ?>
		</a>
	</h1>
</div>