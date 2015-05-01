elgg.provide('elgg.extended_tinymce');

/**
 * Toggles the extended tinymce editor
 *
 * @param {Object} event
 * @return void
 */
elgg.extended_tinymce.toggleEditor = function(event) {
	event.preventDefault();

	var target = $(this).attr('href');
	var id = $(target).attr('id');
	var $link = $(this);

	tinyMCE.execCommand('mceToggleEditor', false, id);
	if ($link.html() == elgg.echo('extended_tinymce:remove')) {
		$link.html(elgg.echo('extended_tinymce:add'));
	} else {
		$link.html(elgg.echo('extended_tinymce:remove'));
	}
}

/**
 * TinyMCE initialization script
 *
 * You can find configuration information here:
 * http://tinymce.moxiecode.com/wiki.php/Configuration
 */
elgg.extended_tinymce.init = function() {

	$('.extended_tinymce-toggle-editor').live('click', elgg.extended_tinymce.toggleEditor);

	$('.elgg-input-longtext').parents('form').submit(function() {
		tinyMCE.triggerSave();
	});

	tinyMCE.init({
		selector: ".elgg-input-longtext",
		theme: "modern",
		skin : "lightgray",
		language : "<?php echo extended_tinymce_get_site_language(); ?>",
		relative_urls : false,
		remove_script_host : false,
		document_base_url : elgg.config.wwwroot,
		plugins: "advlist autolink autoresize autosave charmap code colorpicker emoticons fullscreen hr image insertdatetime link lists paste preview print searchreplace table textcolor textpattern wordcount",
		menubar: false,
		toolbar_items_size: "small",
		toolbar: [
			"newdocument preview fullscreen print | searchreplace | styleselect | fontselect | fontsizeselect",
			"undo redo | bullist numlist | outdent indent | bold italic underline | alignleft aligncenter alignright alignjustify | removeformat",
			"pastetext | insertdatetime | charmap | hr | table | forecolor backcolor | link unlink | image | emoticons | blockquote" + (elgg.is_admin_logged_in() ? " | code" : "")
		],
		width : "99%",
		browser_spellcheck : true,
		image_advtab: true,
		paste_data_images: false,
		autoresize_min_height: 200,
		autoresize_max_height: 450,
		insertdate_formats: ["%I:%M:%S %p", "%H:%M:%S", "%Y-%m-%d", "%d.%m.%Y"],
		content_css: elgg.config.wwwroot + 'mod/extended_tinymce/css/elgg_extended_tinymce.css'
	});
}

elgg.register_hook_handler('init', 'system', elgg.extended_tinymce.init);
