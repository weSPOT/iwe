<?php 

	$francais = array(
	
		// general
		'group_tools:decline' => "Décliner",
		'group_tools:revoke' => "Retirer",
		'group_tools:add_users' => "Ajouter des utilisateurs",
		'group_tools:in' => "dans",
		'group_tools:remove' => "Supprimer",
		'group_tools:clear_selection' => "Effacer la sélection",
		'group_tools:all_members' => "Tous les membres",
		'group_tools:explain' => "Explication",
		
		'group_tools:default:access:group' => "Uniquement les membres du groupe",
		
		'group_tools:joinrequest:already' => "Supprimer la demande d'adhésion",
		'group_tools:joinrequest:already:tooltip' => "Vous avez déjà demandé à rejoindre ce groupe, cliquez ici si vous souhaitez supprimer votre demande d'adhésiont",
		
		// menu
		'group_tools:menu:mail' => "Envoyer un message aux membres",
		'group_tools:menu:invitations' => "Gérer les invitations",
		
    // export options
		'group_tools:export:format' => "Page format",
		'group_tools:export:format:a4' => "A4",
		'group_tools:export:format:letter' => "Letter",
		'group_tools:export:format:a3' => "A3",
		'group_tools:export:format:a5' => "A5",
		'group_tools:export:include_subpages' => "Include subpages",
		'group_tools:export:include_index' => "Include index",
    
		// plugin settings
		'group_tools:settings:invite:title' => "Options des invitations de groupe",
		'group_tools:settings:management:title' => "Options générales",
		'group_tools:settings:default_access:title' => "Accès par défaut des publications dans le groupe",
	
		'group_tools:settings:admin_create' => "Limiter la création de groupes aux administrateurs",
		'group_tools:settings:admin_create:description' => "Si vous indiquez 'Oui' aucun membre ne pourra créer de groupe par lui-même.",
		
		'group_tools:settings:admin_transfer' => "Autoriser le transfert d'administrateur du groupe",
		'group_tools:settings:admin_transfer:admin' => "Seulement par les administrateurs du site",
		'group_tools:settings:admin_transfer:owner' => "Par les administrateurs du site et par les animateurs de groupes",
		
		'group_tools:settings:multiple_admin' => "Autoriser plusieurs animateurs de groupes",
		
		'group_tools:settings:invite' => "Autoriser l'invitation de n'importe quel membre de la communauté (pas seulement les contacts)",
		'group_tools:settings:invite_email' => "autoriser l'invitation de membres par leur adresse mail",
		'group_tools:settings:invite_csv' => "autoriser l'invitation de membres en intégrant un fichier CSV",
	
		'group_tools:settings:mail' => "Autoriser les animateurs de groupes à envoyer des emails à tous leurs membres",
		
		'group_tools:settings:listing' => "Affichage des groupes par défaut",
		
		'group_tools:settings:default_access' => "Quel sera le niveau de publication par défaut dans les groupes ? (nous conseillons fortement de restreindre par défaut les publications au groupe lui-même)",
		'group_tools:settings:default_access:disclaimer' => "<b>ATTENTION:</b> Cette option implique que vous devez modifier le fichier /lib/access.php avec celui-ci <a href='https://github.com/Elgg/Elgg/pull/253' target='_blank'>https://github.com/Elgg/Elgg/pull/253</a> ",
		
		'group_tools:settings:search_index' => "Autoriser les groupes fermés à être indexés par les moteurs de recherche (pour les communautés ouvertes)",
		'group_tools:settings:auto_notification' => "Lorsqu'un membre rejoint un groupe, il sera automatiquement notifié de son activité",
		'group_tools:settings:auto_join' => "Adhésion automatiques aux groupes",
		'group_tools:settings:auto_join:description' => "Les nouveaux membres rejoindrons automatiquement les groupes suivants :",
		
		// group invite message
		'group_tools:groups:invite:body' => "Bonjour %s,

%s vous a invité à rejoindre le groupe '%s'. 
%s

Cliquez qur le lien ci-dessous pour voir vos invitations aux groupes :
%s",
	
		// group add message
		'group_tools:groups:invite:add:subject' => "Vous êtes membre du groupe %s",
		'group_tools:groups:invite:add:body' => "Bonjour %s,
	
%s vous a ajouté comme membre du groupe %s.
%s

Pour voir ce groupe, cliquez-ici
%s",
		// group invite by email
		'group_tools:groups:invite:email:subject' => "Vous avez été invité à rejoindre le groupe %s",
		'group_tools:groups:invite:email:body' => "Bonjour,

%s vous a invité à rejoindre le groupe %s sur %s.
%s

Si vous êtes déjà membre, ou après votre nouvelle inscription, cliquez sur ce lien pour rejoindre le groupe.
%s

Vous pouvez aussi allez sur Tous les groupes -> Invitations et entrer le code suivant:
%s",
		// group transfer notification
		'group_tools:notify:transfer:subject' => "L'administration du groupe %s vous a été assignée",
		'group_tools:notify:transfer:message' => "Bonjour %s,
		
%s vous a transmis l'administration du groupe %s. 

Pour visiter ce groupe, vous pouvez cliquer ce lien:
%s",
		
		// group edit tabbed
		'group_tools:group:edit:profile' => "Profil de groupe / outils",
		'group_tools:group:edit:other' => "Autres options",

		// admin transfer - form
		'group_tools:admin_transfer:title' => "Transférer l'appartenance de ce groupe",
		'group_tools:admin_transfer:transfer' => "Transférer l'appartenance à ",
		'group_tools:admin_transfer:myself' => "Moi-même",
		'group_tools:admin_transfer:submit' => "Transférer",
		'group_tools:admin_transfer:no_users' => "Aucun membre ou contact a qui transférer l'appartenance de ce groupe.",
		'group_tools:admin_transfer:confirm' => "Etes-vous certain/e de vouloir transférer l'appartenance de ce groupe?",
		
		// auto join form
		'group_tools:auto_join:title' => "Options d'auto-adhésion",
		'group_tools:auto_join:add' => "%sAjouter ce groupe%s dans les groupes à rejoindre à l'inscription.",
		'group_tools:auto_join:remove' => "%sSupprimer ce groupe%s des groupes à rejoindre à l'inscription.",
		'group_tools:auto_join:fix' => "Pour inscrire tous les membres du site dans ce groupe,  %scliquez ici%s.",
		
		// group admins
		'group_tools:multiple_admin:group_admins' => "Admins du groupe",
		'group_tools:multiple_admin:profile_actions:remove' => "Supprimer cet admin",
		'group_tools:multiple_admin:profile_actions:add' => "Ajouter cet admin",
	
		'group_tools:multiple_admin:group_tool_option' => "Autoriser les administrateurs du groupe a créer d'autres admistrateurs de groupe",

		// cleanup options
		'group_tools:cleanup:title' => "Options de la barre latérale du groupe",
		'group_tools:cleanup:description' => "Ces options n'ont pas d'effet sur les administrateurs.",
		'group_tools:cleanup:owner_block' => "Limiter l'affichage du bloc d'info du membre",
		'group_tools:cleanup:owner_block:explain' => "",
		'group_tools:cleanup:actions' => "Voulez vous autoriser les membres à rejoindre ce groupe",
		'group_tools:cleanup:actions:explain' => "oui = bouton rejoindre ce groupe. Non =  Demander à rejoindre",
		'group_tools:cleanup:menu' => "Cacher les outils du groupe",
		'group_tools:cleanup:menu:explain' => "Cache la barre d'outils, les membres ne pourront accéder au contenu que par les blocs d'infos (widgets).",
		'group_tools:cleanup:members' => "Cacher le bloc Membres du groupe",
		'group_tools:cleanup:members:explain' => "",
		'group_tools:cleanup:search' => "Cacher le champ de recherche dans le groupe",
		'group_tools:cleanup:search:explain' => "Vous pouvez choisir de cacher la recherche dans le groupe.",
		'group_tools:cleanup:featured' => "Afficher les groupes à la une dans la barre latérale ",
		'group_tools:cleanup:featured:explain' => "Vous pouvez choisir d'afficher les groupes à la une dans la barre latérale de votre groupe",
		'group_tools:cleanup:featured_sorting' => "Si oui, Ordre de tri des groupes à la une",
		'group_tools:cleanup:featured_sorting:time_created' => "Les plus récents",
		'group_tools:cleanup:featured_sorting:alphabetical' => "Alphabétique",

		// group default access
		'group_tools:default_access:title' => "Accès par défaut pour ce groupe",
		'group_tools:default_access:description' => "Il est recommandé de choisir ce groupe seulement pour conserver les informations à l'intérieur du groupe.",
		
		// group notification
		'group_tools:notifications:title' => "Notifications de groupe",
		'group_tools:notifications:description' => "Ce groupe a %s membres, %s ont activé les notifications pour ce groupe. Vous pouvez changer les notification pours vos membres.",
		'group_tools:notifications:disclaimer' => "Attention, cela peut prendre du temps sur les groupes importants.",
		'group_tools:notifications:enable' => "Activer les notifications pour tous les membre de ce groupe",
		'group_tools:notifications:disable' => "Désactiver les notifications pour tous les membre de ce groupe",
		
		// group profile widgets
		'group_tools:profile_widgets:title' => "Afficher les blocs d'infos du groupe aux non-membres de ce groupe",
		'group_tools:profile_widgets:description' => "Ce groupe est privé. Par défaut, les non-membres du groupe ne peuvent pas voir les infos. Vous pouvez changer cela (non conseillé).",
		'group_tools:profile_widgets:option' => "Autoriser les non-membres à voir les blocs d'infos sur votre page d'accueil de groupe:",
		
		// group mail
		'group_tools:mail:message:from' => "Du groupe",
		
		'group_tools:mail:title' => "Envoyer un message aux membres du groupe",
		'group_tools:mail:form:recipients' => "Nombre de destinataires",
		'group_tools:mail:form:members:selection' => "Sélectionner les membres individuellement",
		
		'group_tools:mail:form:title' => "Sujet",
		'group_tools:mail:form:description' => "Message",
	
		'group_tools:mail:form:js:members' => "Merci de sélectionner au moins un destinataire",
		'group_tools:mail:form:js:description' => "Merci de saisir un message",
		
		// group invite
		'group_tools:groups:invite:title' => "Inviter des membres dans ce groupe",
		'group_tools:groups:invite' => "Inviter des membres",
		
		'group_tools:group:invite:friends:select_all' => "Sélectionner tous mes contacts",
		'group_tools:group:invite:friends:deselect_all' => "Déselectionner tous mes contacts",
		
		'group_tools:group:invite:users' => "Trouver des membres",
		'group_tools:group:invite:users:description' => "Saisir le nom d'un membre et sélectionner-le dans la liste",
		'group_tools:group:invite:users:all' => "Inviter tous les membres dans ce groupe",
		
		'group_tools:group:invite:email' => "Via l'adresse email",
		'group_tools:group:invite:email:description' => "Saisir une adresse email valide et la sélectionner dans la liste",
		
		'group_tools:group:invite:csv' => "Using CSV upload",
		'group_tools:group:invite:csv:description' => "You can upload a CSV file with users to invite.<br />The format must be: displayname;e-mail address. There shouldn't be a header line.",
		
		'group_tools:group:invite:text' => "Message personnel (optionnel)",
		'group_tools:group:invite:add:confirm' => "Etes vous sûr de vouloir directement ajouter ces membres ?",
		
		'group_tools:group:invite:resend' => "Envoyer de nouveau l'invitation aux utilisateurs ayant déjà été invités",

		'group_tools:groups:invitation:code:title' => "Invitation de groupe via adresse email",
		'group_tools:groups:invitation:code:description' => "Si vous avez reçu une invitation à rejoindre le groupe par e-mail, vous pouvez entrer le code invitation ici.", 
	
		// group membership requests
		'group_tools:groups:membershipreq:requests' => "Utilisateurs voulant rejoindre le groupe",
		'group_tools:groups:membershipreq:invitations' => "Invitations envoyées",
		'group_tools:groups:membershipreq:invitations:none' => "Aucune invitation envoyée",
		'group_tools:groups:membershipreq:invitations:revoke:confirm' => "Voulez-vous vraiment annuler cette invitation ?",
	
		// group invitations
		'group_tools:group:invitations:request' => "Invitations en attente de validation",
		'group_tools:group:invitations:request:revoke:confirm' => "Etes-vous sûr/e de vouloir annuler votre demande d'adhésion ?",
		'group_tools:group:invitations:request:non_found' => "Il n'y a pas d'invitations en attente.",
	
		// group listing
		'group_tools:groups:sorting:alphabetical' => "Tri alphabétique",
		'group_tools:groups:sorting:open' => "Groupes ouverts",
		'group_tools:groups:sorting:closed' => "Groupes fermés",
	
		// actions
		'group_tools:action:error:input' => "Vous ne pouvez pas effectuer cette action",
		'group_tools:action:error:entities' => "The given GUIDs didn't result in the correct entities",
		'group_tools:action:error:entity' => "The given GUID didn't result in a correct entity",
		'group_tools:action:error:edit' => "You don't have access to the given entity",
		'group_tools:action:error:save' => "There was an error while saving the settings",
		'group_tools:action:success' => "The settings where saved successfully",
	
		// admin transfer - action
		'group_tools:action:admin_transfer:error:access' => "Vous ne pouvez pas transférer les droits d'administration de ce groupe",
		'group_tools:action:admin_transfer:error:self' => "Vous ne pouvez pas transférer les droits d'administration de ce groupe à vous même, vous en êtes déjà admin",
		'group_tools:action:admin_transfer:error:save' => "Erreur. Essayez à nouveau",
		'group_tools:action:admin_transfer:success' => "L'administration du groupe a été transférée à %s",
		
		// group admins - action
		'group_tools:action:toggle_admin:error:group' => "Erreur inconnue",
		'group_tools:action:toggle_admin:error:remove' => "Erreur inconnue",
		'group_tools:action:toggle_admin:error:add' => "Erreur inconnue",
		'group_tools:action:toggle_admin:success:remove' => "Ce membre n'est plus admin du groupe",
		'group_tools:action:toggle_admin:success:add' => "Ce membre est devenu admin du groupe",
		
		// group mail - action
		'group_tools:action:mail:success' => "Message envoyé",
	
		// group - invite - action
		'group_tools:action:invite:error:invite'=> "Aucun membre n'a été invité (%s déjà invités, %s déjà membres)",
		'group_tools:action:invite:error:add'=> "Aucun membre n'a été invité (%s déjà invités, %s déjà membres)",
		'group_tools:action:invite:success:invite'=> "Vous avez invité %s membres (%s déjà invités and %s déjà membres)",
		'group_tools:action:invite:success:add'=> "Vous avez invité %s membres (%s déjà invités and %s déjà membres)",
		
		// group - invite - accept e-mail
		'group_tools:action:groups:email_invitation:error:input' => "Entrez votre code invitation reçu par e-mail",
		'group_tools:action:groups:email_invitation:error:code' => "Ce code invitation n'est plus valide",
		'group_tools:action:groups:email_invitation:error:join' => "Erreur en rejoignant le groupe %s, Peut-être êtes vous déjà membre?",
		'group_tools:action:groups:email_invitation:success' => "Vous avez rejoint ce groupe",
		
		// group toggle auto join
		'group_tools:action:toggle_auto_join:error:save' => "Erreur d'enregistrement des paramètres",
		'group_tools:action:toggle_auto_join:success' => "Les nouveaux paramètres sont pris en compte",
		
		// group fix auto_join
		'group_tools:action:fix_auto_join:success' => "Nouvelles adhésions automatiques au groupe: %s nouveaux membres, %s déjà membres and %s erreurs",
		
		// group cleanup
		'group_tools:actions:cleanup:success' => "Les paramètres ont été pris en compte",
		
		// group default access
		'group_tools:actions:default_access:success' => "Le nouvel accès par défaut pour le groupe a été pris en compte",
		
		// group notifications
		'group_tools:action:notifications:error:toggle' => "Erreur",
		'group_tools:action:notifications:success:disable' => "Les notifications sont désactivées pour tous vos membres",
		'group_tools:action:notifications:success:enable' => "Les notifications sont activées pour tous vos membres",
	
		// Widgets
		// Group River Widget
		'widgets:group_river_widget:title' => "Activité du groupe",
	    'widgets:group_river_widget:description' => "Affiche les activités d'un groupe",

	    'widgets:group_river_widget:edit:num_display' => "Nb d'activités",
		'widgets:group_river_widget:edit:group' => "Choisissez un groupe",
		'widgets:group_river_widget:edit:no_groups' => "Vous devez être membre du groupe pour utliser ce bloc d'info",

		'widgets:group_river_widget:view:not_configured' => "Ce bloc d'info n'est pas encore configuré",

		'widgets:group_river_widget:view:more' => "Activité du groupe '%s'",
		'widgets:group_river_widget:view:noactivity' => "Pas d'activité pour le moment.",
		
		// Group Members
		'widgets:group_members:title' => "Membres du groupe",
  		'widgets:group_members:description' => "Affiche les membres de ce groupe",

  		'widgets:group_members:edit:num_display' => "Combien de membres à afficher",
  		'widgets:group_members:view:no_members' => "Aucun membre pour ce groupe",
  		
  		// Group Invitations
		'widgets:group_invitations:title' => "Invitations",
	  	'widgets:group_invitations:description' => "Affiche les invitations à des groupes",
	  	
	  	// Discussion
		"widgets:discussion:settings:group_only" => "N'afficher que les discussions des groupes auxquels vous appartenez",
  		'widgets:discussion:more' => "Voir plus de discussions",
  		"widgets:discussion:description" => "Affiche les dernières discussions de groupes",
  		
		// Forum topic widget
		'widgets:group_forum_topics:description' => "Affiche les dernières discussions dans les groupes",
		
		// index_groups
		'widgets:index_groups:description' => "Affiche les derniers groupes créés",
		'widgets:index_groups:show_members' => "Afficher le nombre de membres",
		'widgets:index_groups:featured' => "N'afficher que les groupes à la une",
		
		'widgets:index_group:filter:field' => "Filter les groupes selon un critère",
		'widgets:index_group:filter:value' => "avec le critère",
		'widgets:index_group:filter:no_filter' => "pas de filtre",
		
		// Featured Groups
		'widgets:featured_groups:description' => "Affiche une liste aléatoire des groupes à la une",
	  	'widgets:featured_groups:edit:show_random_group' => "Affiche une liste aléatoire des groupes qui ne sont pas à la une",
	  	
		// group_news widget
		"widgets:group_news:title" => "Info des groupes", 
		"widgets:group_news:description" => "Affiche les 5 derniers billets de blog de divers groupes", 
		"widgets:group_news:no_projects" => "Aucun groupe configuré", 
		"widgets:group_news:no_news" => "Pas de blog pour ce groupe", 
		"widgets:group_news:settings:project" => "Groupz", 
		"widgets:group_news:settings:no_project" => "Selectionnez un groupe",
		"widgets:group_news:settings:blog_count" => "Nb maxi de billets de blog à afficher",
		"widgets:group_news:settings:group_icon_size" => "Taille de l'icône de groupe",
		"widgets:group_news:settings:group_icon_size:small" => "Petite",
		"widgets:group_news:settings:group_icon_size:medium" => "Moyenne",
		
	);
	
	add_translation("fr", $francais);
  	