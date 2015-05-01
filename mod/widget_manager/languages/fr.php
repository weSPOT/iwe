<?php 

	$french = array(
		
		// special access level
		'LOGGED_OUT' => "Membres déconnectés",
		'access:admin_only' => "Admins seulement",
		
		// admin menu items
		'admin:widgets' => "Widgets",
		'admin:widgets:manage' => "Gérer",
		'admin:widgets:manage:index' => "Gérer l'index",
		'admin:statistics:widgets' => "Utilisation des blos d'infos",
		
		// widget edit wrapper
		'widget_manager:widgets:edit:custom_title' => "Donnez un titre à votre bloc d'infos",
		'widget_manager:widgets:edit:custom_url' => "Indiquez un lien sur ce titre (option)",
		'widget_manager:widgets:edit:hide_header' => "Cacher les entêtes",
		'widget_manager:widgets:edit:custom_class' => "Classe CSS à utiliser",
		'widget_manager:widgets:edit:disable_widget_content_style' => "Pas de style pour ce bloc d'infos",
			
		// group
		'widget_manager:groups:enable_widget_manager' => "Autoriser la gestion de blocs d'infos pour les groupes",
	
		// admin settings
		'widget_manager:settings:index' => "Index",
		'widget_manager:settings:group' => "Groupe",
		
		'widget_manager:settings:custom_index' => "Use Widget Manager custom index?",
		'widget_manager:settings:custom_index:non_loggedin' => "For non-loggedin users only",
		'widget_manager:settings:custom_index:loggedin' => "For loggedin users only",
		'widget_manager:settings:custom_index:all' => "For all users",
	
		'widget_manager:settings:widget_layout' => "Choose a widget layout",
		'widget_manager:settings:widget_layout:33|33|33' => "Default layout (33% per column)",
		'widget_manager:settings:widget_layout:50|25|25' => "Wide left column (50%, 25%, 25%)",
		'widget_manager:settings:widget_layout:25|50|25' => "Wide middle column (25%, 50%, 25%)",
		'widget_manager:settings:widget_layout:25|25|50' => "Wide right column (25%, 25%, 50%)",
		'widget_manager:settings:widget_layout:75|25' => "Two column (75%, 25%)",
		'widget_manager:settings:widget_layout:60|40' => "Two column (60%, 40%)",
		'widget_manager:settings:widget_layout:50|50' => "Two column (50%, 50%)",
		'widget_manager:settings:widget_layout:40|60' => "Two column (40%, 60%)",
		'widget_manager:settings:widget_layout:25|75' => "Two column (25%, 75%)",
		
		'widget_manager:settings:index_top_row' => "Show a top row on the index page",
		'widget_manager:settings:index_top_row:none' => "No top row",
		'widget_manager:settings:index_top_row:full_row' => "Full width row",
		'widget_manager:settings:index_top_row:two_column_left' => "Two column aligned left",
		
		'widget_manager:settings:disable_free_html_filter' => "Disable HTML filtering for Free HTML widgets on index (ADMIN ONLY)",
		
		'widget_manager:settings:group:enable' => "Autoriser les blocs d'infos de groupes",
		'widget_manager:settings:group:option_default_enabled' => "Widget management for groups default enabled",
		'widget_manager:settings:group:option_admin_only' => "Only administrator can enable group widgets",

		'widget_manager:settings:multi_dashboard' => "Multi Dashboard",
		'widget_manager:settings:multi_dashboard:enable' => "Enable multiple dashboards",

		// views
		// settings
		'widget_manager:forms:settings:no_widgets' => "Aucun bloc d'infos à gérer",
		'widget_manager:forms:settings:can_add' => "Peut être ajouté",
		'widget_manager:forms:settings:hide' => "Caché",

		// lightbox
		'widget_manager:button:add' => "Ajouter un bloc d'infos",
		'widget_manager:widgets:lightbox:title:dashboard' => "Ajouter des blocs d'infos à votre tableau de bord",
		'widget_manager:widgets:lightbox:title:profile' => "Ajouter des blocs d'infos à votre profil",
		'widget_manager:widgets:lightbox:title:index' => "Ajouter des blocs d'infos à l'index",
		'widget_manager:widgets:lightbox:title:groups' => "Ajouter des blocs d'infos au profil du groupe",
		'widget_manager:widgets:lightbox:title:admin' => "Ajouter des blocs d'infos à votre tableau de bord d'administration",
		
		// multi dashboard
		'widget_manager:multi_dashboard:add' => "Nouvel onglet",
		'widget_manager:multi_dashboard:extras' => "Ajouter un onglet dans le tableau de bord",
		
		// multi dashboard - edit
		'widget_manager:multi_dashboard:new' => "Créer un nouveau tableau de bord",
		'widget_manager:multi_dashboard:edit' => "Modifier le tableau de bord : %s",
		
		'widget_manager:multi_dashboard:types:title' => "Selectionnez un tableau de bord par défaut",
		'widget_manager:multi_dashboard:types:widgets' => "blocs d'infos",
		'widget_manager:multi_dashboard:types:iframe' => "iFrame",
		
		'widget_manager:multi_dashboard:num_columns:title' => "Nombre de colonnes",
		'widget_manager:multi_dashboard:iframe_url:title' => "URL de l'iFrame",
		'widget_manager:multi_dashboard:iframe_url:description' => "Note: assurez vous que l'URL commence bien par http:// ou https://. Tous les sites ne supportent pas les iFrames",
		'widget_manager:multi_dashboard:iframe_height:title' => "Hauteur de l'iFrame",
		
		'widget_manager:multi_dashboard:required' => "Les champs marqués d'une * sont obligatoires",
		
		// actions
		// manage
		'widget_manager:action:manage:error:context' => "Invalid context to save widget configuration",
		'widget_manager:action:manage:error:save_setting' => "Error while saving the setting %s for widget %s",
		'widget_manager:action:manage:success' => "Widget configuration saved successfully",
		
		// multi dashboard - edit
		'widget_manager:actions:multi_dashboard:edit:error:input' => "Invalid input, please submit a title",
		'widget_manager:actions:multi_dashboard:edit:success' => "Succesfully created/edited a dashboard",
		
		// multi dashboard - delete
		'widget_manager:actions:multi_dashboard:delete:error:delete' => "Unable to remove dashboard %s",
		'widget_manager:actions:multi_dashboard:delete:success' => "Dashboard %s succesfully removed",
		
		// multi dashboard - drop
		'widget_manager:actions:multi_dashboard:drop:success' => "The widget has successfully been moved the the new dashboard",
		
		// multi dashboard - reorder
		'widget_manager:actions:multi_dashboard:reorder:error:order' => "Please supply a new order",
		'widget_manager:actions:multi_dashboard:reorder:success' => "Dashboard reordered successfully",
		
		// widgets
		'widget_manager:widgets:edit:advanced' => "Avancé",
		'widget_manager:widgets:fix' => "Fixer ce bloc d'infos au profil/tableau de bord",
			
		// index_login
		'widget_manager:widgets:index_login:description' => "Show a login box",
		'widget_manager:widgets:index_login:welcome' => "<b>%s</b> welcome on the <b>%s</b> community",
		
		// index_members
		'widget_manager:widgets:index_members:name' => "Membres",
		'widget_manager:widgets:index_members:description' => "Monter les membres de votre communauté",
		'widget_manager:widgets:index_members:user_icon' => "Les membres doivent-ils avoir une icône de profil obligatoire ?",
		'widget_manager:widgets:index_members:no_result' => "Aucun membre trouvé",
		
		// index_memebers_online
		'widget_manager:widgets:index_members_online:name' => "Membres en ligne",
		'widget_manager:widgets:index_members_online:description' => "Afficher les membres en ligne actuellement",
		'widget_manager:widgets:index_members_online:member_count' => "Combien de membres à afficher ?",
		'widget_manager:widgets:index_members_online:user_icon' => "Les membres doivent-ils avoir une icône de profil obligatoire ?",
		'widget_manager:widgets:index_members_online:no_result' => "Aucun membre trouvé",
		
		// index_bookmarks
		'widget_manager:widgets:index_bookmarks:description' => "Afficher les derniers favoris",
		
		// index_activity
		'widget_manager:widgets:index_activity:description' => "Afficher les dernières activités",
	
		// image_slider
		'widget_manager:widgets:image_slider:name' => "Diaporama d'images",
		'widget_manager:widgets:image_slider:description' => "Afficher un  diaporama d'images",
		'widget_manager:widgets:image_slider:slider_type' => "Type de diaporama",
		'widget_manager:widgets:image_slider:slider_type:s3slider' => "s3Slider",
		'widget_manager:widgets:image_slider:slider_type:flexslider' => "FlexSlider",
		'widget_manager:widgets:image_slider:seconds_per_slide' => "Secondes par page",
		'widget_manager:widgets:image_slider:slider_height' => "Hauteur (pixels)",
		'widget_manager:widgets:image_slider:overlay_color' => "Overlay couleur (hex)",
		'widget_manager:widgets:image_slider:title' => "Diaporama",
		'widget_manager:widgets:image_slider:label:url' => "url de l'image",
		'widget_manager:widgets:image_slider:label:text' => "Texte",
		'widget_manager:widgets:image_slider:label:link' => "Lien",
		'widget_manager:widgets:image_slider:label:direction' => "Direction",
		'widget_manager:widgets:image_slider:direction:top' => "Haut",
		'widget_manager:widgets:image_slider:direction:right' => "Droit",
		'widget_manager:widgets:image_slider:direction:bottom' => "Bas",
		'widget_manager:widgets:image_slider:direction:left' => "Gauche",
	);
	add_translation("fr", $french);

	$twitter_search = array(
		// twitter_search
		'widgets:twitter_search:name' => "Twitter search",
		'widgets:twitter_search:description' => "Display a custom search from Twitter",
		
		'widgets:twitter_search:query' => "Search query",
		'widgets:twitter_search:query:help' => "try some advanced queries",
		'widgets:twitter_search:title' => "Widget title (optional)",
		'widgets:twitter_search:subtitle' => "Widget subtitle (optional)",
		'widgets:twitter_search:height' => "Widget height (pixels)",
		'widgets:twitter_search:background' => "Set a custom background color (HEX eq 4690d6)",
		'widgets:twitter_search:not_configured' => "This widget is not yet configured",
	);
	add_translation("fr", $twitter_search);
	
	$content_by_tag = array(
		// content_by_tag
		'widgets:content_by_tag:name' => "Mots clés",
		'widgets:content_by_tag:description' => "Trouver un contenu par mot clé",
		
		'widgets:content_by_tag:owner_guids' => "Who needs to write the items",
		'widgets:content_by_tag:group_only' => "Only show content from this group",
		'widgets:content_by_tag:entities' => "Which entities to show",
		'widgets:content_by_tag:tags' => "Mot(s) clé(s) (séparés par des virgules)",
		'widgets:content_by_tag:tags_option' => "Comment utiliser les mots clés",
		'widgets:content_by_tag:tags_option:and' => "ET",
		'widgets:content_by_tag:tags_option:or' => "OU",
		'widgets:content_by_tag:display_option' => "Comment lister le contenu",
		'widgets:content_by_tag:display_option:normal' => "Normal",
		'widgets:content_by_tag:display_option:simple' => "Simple",
		'widgets:content_by_tag:display_option:slim' => "Slim (single line)",
		'widgets:content_by_tag:highlight_first' => "Number of highlighted items (slim only)",
	);
	add_translation("fr", $content_by_tag);
	
	$rss = array(
		// RSS widget (based on SimplePie)
		'widgets:rss:title' => "Flux RSS",
		'widgets:rss:description' => "Show a RSS feed (based on SimplePie)",
		'widgets:rss:error:notset' => "No RSS Feed URL provided",
		
		'widgets:rss:settings:rss_count' => "Nombre d'entrées à afficher",
		'widgets:rss:settings:rssfeed' => "URL du flux RSS",
		'widgets:rss:settings:show_feed_title' => "Montrer le titre du flux",
		'widgets:rss:settings:excerpt' => "Montrer un résumé",
		'widgets:rss:settings:show_item_icon' => "montrer l'icône (si disponible)",
		'widgets:rss:settings:post_date' => "Montrer la date",
		'widgets:rss:settings:post_date:option:friendly' => "Affiche 'il y a...'",
		'widgets:rss:settings:post_date:option:date' => "Afficher la date",
	);
	add_translation("fr", $rss);
	
	$free_html = array(
		// Free HTML
		'widgets:free_html:title' => "HTML",
		'widgets:free_html:description' => "Votre propre contenu en HTML",
		'widgets:free_html:settings:html_content' => "Entrez votre contenu à afficher en HTML",
		'widgets:free_html:no_content' => "Ce bloc d'infos n'est pas configuré",
		
	);
	add_translation("fr", $free_html);
	
	$tagcloud = array(
		'widgets:tagcloud:description' => "Affiche un nuage de mots clés basé sur les contenus de la communauté, du groupe ou du membre",
		'widgets:tagcloud:no_data' => "Pas de mots clés à afficher pour le moment",
	);
	add_translation("fr", $tagcloud);
	
	$entity_statistics = array(
		// entity_statistics widget
		"widgets:entity_statistics:title" => "Statistics", 
		"widgets:entity_statistics:description" => "Shows site statistics", 
		"widgets:entity_statistics:settings:selected_entities" => "Select the entities you wish to show", 
	
	);
	add_translation("fr", $entity_statistics);
	
	$messages = array(
		// messages widget
		"widgets:messages:description" => "Affiche vos dernires messages internes", 
		"widgets:messages:not_logged_in" => "Vous devez être logué", 
		"widgets:messages:settings:only_unread" => "Ne voir que les messages non lus",
	);
	add_translation("fr", $messages);
	
	$favorites = array(
		// favorites widget
		"widgets:favorites:title" => "Mes contenus favoris", 
		"widgets:favorites:description" => "Affiche vos pages favorites dans la communauté",
		 
		"widgets:favorites:delete:success" => "Favoris supprimé", 
		"widgets:favorites:delete:failed" => "Failed to remove favorite", 
		"widgets:favorites:save:success" => "Favoris créé", 
		"widgets:favorites:save:failed" => "Failed to create favorite", 
		"widgets:favorites:toggle:missing_input" => "Entrée manquante pour cette action", 
		"widgets:favorites:content:more_info" => "Ajoutez vos contenus favoris en cliquant sur l'étoile qui se trouve dans le menu de droite.", 

		"widgets:favorites:menu:add" => "Ajouter cette page à votre contenu favoris", 
		"widgets:favorites:menu:remove" => "Supprimer cette page de vos contenus favoris", 
			
	);
	add_translation("fr", $favorites);
	