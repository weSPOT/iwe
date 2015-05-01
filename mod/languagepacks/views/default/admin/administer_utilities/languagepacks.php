<?php
/**
 * Elgg Language Packs
 *
 * @package ElggLanguagePacks
 */

require_once elgg_get_plugins_path() . 'languagepacks/lib/elgg_language_packs/elgg_language_packs.php';

$toggle_link = elgg_view('output/url', array(
	'href' => '#language-pack-selection',
	'text' => elgg_echo('languagepacks:select_locales:link'),
	'rel' => 'toggle',
));
$form_class = 'elgg-module elgg-module-inline hidden';

$languages = elgglp_get_installed_translations();

$form_body = '<ul class="languagepacks-select-table">';
$form_body .= '<li>';
$form_body .= elgg_view("input/dropdown", array(
	'id' => 'select1_locales',
	'multiple' => true,
	'class' => 'languagepacks-select',
));
$form_body .= '</li><li>';
$form_body .= '<div class="languagepacks-buttons">';
$form_body .= '<p>' . elgg_view('input/button', array('id' => 'add-all', 'value' => elgg_echo('languagepacks:add:all'))) . '</p>';
$form_body .= '<p>' . elgg_view('input/button', array('id' => 'add', 'value' => elgg_echo('languagepacks:add:selected'))) . '</p>';
$form_body .= '<p>' . elgg_view('input/button', array('id' => 'remove', 'value' => elgg_echo('languagepacks:remove:selected'))) . '</p>';
$form_body .= '<p>' . elgg_view('input/button', array('id' => 'remove-all', 'value' => elgg_echo('languagepacks:remove:all'))) . '</p>';
$form_body .= '</div>';
$form_body .= '</li><li>';
$form_body .= elgg_view("input/dropdown", array(
	'id' => 'select2_locales',
	'options_values' => $languages,
	'multiple' => true,
	'class' => 'languagepacks-select',
));
$form_body .= '</li></ul>';

$release = get_version(true);
$plugins = elgglp_recurse_language_pack(
		elgg_get_root_path(),			// the elgg root directory where we are looking for language mods
		array('return_array' => true, 'elgg_release' => $release ),	// no filter, but return array of meta data and don't look for meta files
		null							// no callback
);

$cores = elgglp_core_plugins($release);
$core_options = array();
$plugin_options = array();
foreach ( $plugins as $value ) {
    if ( in_array($value['unique'], $cores) ) {
        $core_options[$value['unique']] = "$value[name] v$value[version]";
    } else {
        $plugin_options[$value['unique']] = "$value[name] v$value[version]";
    }
}
unset($plugins);
unset($cores);

// Core plugins

$toggle_link_1 = elgg_view('output/url', array(
    'href' => '#cores-pack-selection',
    'text' => elgg_echo('languagepacks:select_cores:link'),
    'rel' => 'toggle',
));
$form_class_1 = 'elgg-module elgg-module-inline hidden';

$form_body_1 = '<ul class="languagepacks-select-table">';
$form_body_1 .= '<li>';
$form_body_1 .= elgg_view("input/dropdown", array(
	'id' => 'select1_cores',
	'multiple' => true,
	'class' => 'languagepacks-select',
));
$form_body_1 .= '</li><li>';
$form_body_1 .= '<div class="languagepacks-buttons">';
$form_body_1 .= '<p>' . elgg_view('input/button', array('id' => 'add-all-1', 'value' => elgg_echo('languagepacks:add:all'))) . '</p>';
$form_body_1 .= '<p>' . elgg_view('input/button', array('id' => 'add-1', 'value' => elgg_echo('languagepacks:add:selected'))) . '</p>';
$form_body_1 .= '<p>' . elgg_view('input/button', array('id' => 'remove-1', 'value' => elgg_echo('languagepacks:remove:selected'))) . '</p>';
$form_body_1 .= '<p>' . elgg_view('input/button', array('id' => 'remove-all-1', 'value' => elgg_echo('languagepacks:remove:all'))) . '</p>';
$form_body_1 .= '</div>';
$form_body_1 .= '</li><li>';
$form_body_1 .= elgg_view("input/dropdown", array(
	'id' => 'select2_cores',
	'options_values' => $core_options,
	'multiple' => true,
	'class' => 'languagepacks-select',
));
$form_body_1 .= '</li></ul>';

// Extra plugins

$toggle_link_2 = elgg_view('output/url', array(
    'href' => '#plugins-pack-selection',
    'text' => elgg_echo('languagepacks:select_plugins:link'),
    'rel' => 'toggle',
));
$form_class_2 = 'elgg-module elgg-module-inline hidden';

$form_body_2 = '<ul class="languagepacks-select-table">';
$form_body_2 .= '<li>';
$form_body_2 .= elgg_view("input/dropdown", array(
    'id' => 'select1_plugins',
    'multiple' => true,
    'class' => 'languagepacks-select',
));
$form_body_2 .= '</li><li>';
$form_body_2 .= '<div class="languagepacks-buttons">';
$form_body_2 .= '<p>' . elgg_view('input/button', array('id' => 'add-all-2', 'value' => elgg_echo('languagepacks:add:all'))) . '</p>';
$form_body_2 .= '<p>' . elgg_view('input/button', array('id' => 'add-2', 'value' => elgg_echo('languagepacks:add:selected'))) . '</p>';
$form_body_2 .= '<p>' . elgg_view('input/button', array('id' => 'remove-2', 'value' => elgg_echo('languagepacks:remove:selected'))) . '</p>';
$form_body_2 .= '<p>' . elgg_view('input/button', array('id' => 'remove-all-2', 'value' => elgg_echo('languagepacks:remove:all'))) . '</p>';
$form_body_2 .= '</div>';
$form_body_2 .= '</li><li>';
$form_body_2 .= elgg_view("input/dropdown", array(
    'id' => 'select2_plugins',
    'options_values' => $plugin_options,
    'multiple' => true,
    'class' => 'languagepacks-select',
));
$form_body_2 .= '</li></ul>';
?>

<div class="mbm">
	<div>
		<?php echo $toggle_link; ?>
	</div>
	<div id="language-pack-selection" class="<?php echo $form_class; ?>">
		<div class="elgg-head">
			<h3><?php echo elgg_echo('languagepacks:select_locales:title'); ?></h3>
		</div>
		<div class="elgg-body">
			<?php echo $form_body ?>
		</div>
	</div>
</div>

<div class="mbm">
	<div>
		<?php echo $toggle_link_1; ?>
	</div>
	<div id="cores-pack-selection" class="<?php echo $form_class_1; ?>">
		<div class="elgg-head">
			<h3><?php echo elgg_echo('languagepacks:select_cores:title'); ?></h3>
		</div>
		<div class="elgg-body">
			<?php echo $form_body_1 ?>
		</div>
	</div>
</div>

<div class="mbm">
    <div>
        <?php echo $toggle_link_2; ?>
    </div>
    <div id="plugins-pack-selection" class="<?php echo $form_class_2; ?>">
        <div class="elgg-head">
            <h3><?php echo elgg_echo('languagepacks:select_plugins:title'); ?></h3>
        </div>
        <div class="elgg-body">
            <?php echo $form_body_2 ?>
        </div>
    </div>
</div>

<p><br/><?php echo elgg_echo('languagepacks:intro') ?><br/><br/></p>

<?php echo elgg_view_form('languagepacks/export', array('class' => 'object-selection', 'enctype' => 'multipart/form-data')); ?>

<p><br/><br/></p>

<?php echo elgg_view_form('languagepacks/import', array('class' => 'object-selection', 'enctype' => 'multipart/form-data')); ?>

<p><br/><br/></p>

<?php echo elgg_view_form('languagepacks/delete', array('class' => 'object-selection', 'enctype' => 'multipart/form-data')); ?>

<script>

$('#add').click(function() {
	return !$('#select1_locales option:selected').remove().appendTo('#select2_locales');
});
$('#remove').click(function() {
	return !$('#select2_locales option:selected').remove().appendTo('#select1_locales');
});
$('#add-all').click(function() {
	return !$('#select1_locales option').remove().appendTo('#select2_locales');
});
$('#remove-all').click(function() {
	return !$('#select2_locales option').remove().appendTo('#select1_locales');
});
$('#add-1').click(function() {
	return !$('#select1_cores option:selected').remove().appendTo('#select2_cores');
});
$('#remove-1').click(function() {
	return !$('#select2_cores option:selected').remove().appendTo('#select1_cores');
});
$('#add-all-1').click(function() {
	return !$('#select1_cores option').remove().appendTo('#select2_cores');
});
$('#remove-all-1').click(function() {
	return !$('#select2_cores option').remove().appendTo('#select1_cores');
});
$('#add-2').click(function() {
    return !$('#select1_plugins option:selected').remove().appendTo('#select2_plugins');
});
$('#remove-2').click(function() {
    return !$('#select2_plugins option:selected').remove().appendTo('#select1_plugins');
});
$('#add-all-2').click(function() {
    return !$('#select1_plugins option').remove().appendTo('#select2_plugins');
});
$('#remove-all-2').click(function() {
    return !$('#select2_plugins option').remove().appendTo('#select1_plugins');
});
$('form.object-selection').submit(function() {
	prepare_hidden_field('locales', this);
	prepare_hidden_field('cores', this);
    prepare_hidden_field('plugins', this);
});

function prepare_hidden_field(name, ctx) {
	hidden = $('input[name="' + name + '-selection"]', ctx);
	hidden.val('');
	if ( $('#select1_' + name + ' option').length > 0 && $('#select2_' + name + ' option').length > 0 ) {
		theval = $('#select2_' + name + ' option').each(function(index, option) {
			if ( hidden.val() ) {
				hidden.val(hidden.val() + "|" + option.value);
			} else {
				hidden.val(option.value);
			}
		});
	}
}

</script>
