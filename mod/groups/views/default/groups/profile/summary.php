<?php
/**
 * Group profile summary
 *
 * Icon and profile fields
 *
 * @uses $vars['group']
 */

if (!isset($vars['entity']) || !$vars['entity']) {
	echo elgg_echo('groups:notfound');
	return true;
}

$group = $vars['entity'];
$owner = $group->getOwnerEntity();

if (!$owner) {
	// not having an owner is very bad so we throw an exception
	$msg = elgg_echo('InvalidParameterException:IdNotExistForGUID', array('group owner', $group->guid));
	throw new InvalidParameterException($msg);
}

if ($group->summary_enable != "yes") {
?>

<script type="text/javascript">
	// Group hidden fields
	$(document).ready(function() {
	  $('.nav-toggle').click(function(){
		//get collapse content selector
		var collapse_content_selector = $(this).attr('href');					
		//make the collapse content to be shown or hide
		var toggle_switch = $(this);
		$(collapse_content_selector).toggle(function(){
		});
	  });
	});
</script>

<button href="#collapse1" class="nav-toggle elgg-button elgg-button-action"><?php echo ucfirst(elgg_echo("admin:plugins:label:moreinfo")); ?></button>

<div id="collapse1" style="display:none">
<?php
}
?>
	<div class="groups-profile clearfix elgg-image-block">
		<div class="elgg-image">
			<div class="groups-profile-icon">
				<?php
					// we don't force icons to be square so don't set width/height
					echo elgg_view_entity_icon($group, 'large', array(
						'href' => '',
						'width' => '',
						'height' => '',
					)); 
				?>
			</div>
			<div class="groups-stats">
				<p>
					<b><?php echo elgg_echo("groups:owner"); ?>: </b>
					<?php
						echo elgg_view('output/url', array(
							'text' => $owner->name,
							'value' => $owner->getURL(),
							'is_trusted' => true,
						));
					?>
				</p>
				<p>
				<?php
					echo elgg_echo('groups:members') . ": " . $group->getMembers(0, 0, TRUE);
				?>
				</p>
			</div>
		</div>

		<div class="groups-profile-fields elgg-body">
			<?php
				echo elgg_view('groups/profile/fields', $vars);
			?>
		</div>
	</div>
<?php
if ($group->summary_enable != "yes") {
?>
</div>
<?php
}
?>

