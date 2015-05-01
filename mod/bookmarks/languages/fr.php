<?php
/**
 * Bookmarks English language file
 */

$french = array(

	/**
	 * Menu items and titles
	 */
	'bookmarks' => "Liens favoris",
	'bookmarks:add' => "Ajouter un lien",
	'bookmarks:edit' => "Modifier le lien",
	'bookmarks:owner' => "Les favoris de %s",
	'bookmarks:friends' => "Favoris des contacts",
	'bookmarks:everyone' => "Tous les favoris du site",
	'bookmarks:this' => "Mettre en favoris cette page",
	'bookmarks:this:group' => "Mettre en favoris dans %s",
	'bookmarks:bookmarklet' => "Récupérer le bookmarklet",
	'bookmarks:bookmarklet:group' => "Récupérer le bookmarklet du groupe",
	'bookmarks:inbox' => "Boîte de réception des favoris",
	'bookmarks:morebookmarks' => '+ de favoris',
	'bookmarks:more' => "Plus de liens favoris",
	'bookmarks:with' => "Partager avec",
	'bookmarks:new' => "Un nouveau lien",
	'bookmarks:via' => "via les signets",
	'bookmarks:address' => "Adresse de la ressource à ajouter à vos favoris",
	'bookmarks:none' => "Aucuns favoris",
	'bookmarks:notification' =>
'%s a partagé un nouveau lien :

%s - %s
%s

Voir et commenter ce lien:
%s
',
	'bookmarks:delete:confirm' => "Etes-vous sûr(e) de vouloir supprimer cette ressource ?",

	'bookmarks:numbertodisplay' => "Nombre de favoris à afficher",

	'bookmarks:shared' => "a partagé un lien",
	'bookmarks:visit' => "Voir la ressource",
	'bookmarks:recent' => "Favoris récents",

	'bookmarks:river:created' => "%s mis en favoris",
	'bookmarks:river:annotate' => "a posté un commentaire sur ce favori",
	'bookmarks:river:item' => "un favori",
	'river:commented:object:bookmarks' => "un favori",
	'river:create:object:bookmarks' => '%s a partagé le lien %s',
	'river:comment:object:bookmarks' => '%s a commenté le lien %s',
	'bookmarks:river:annotate' => 'a commenté ce lien',
	'bookmarks:river:item' => 'un lien',
	'item:object:bookmarks' => "Eléments mis en favoris",

	'bookmarks:group' => "Liens favoris",
	'bookmarks:enablebookmarks' => "Activer les liens favoris du groupe",
	'bookmarks:nogroup' => "Ce groupe n'a pas encore de favoris",
	'bookmarks:more' => "Plus de favoris",

	'bookmarks:no_title' => "Pas de titre",
	
	/* Key bookmarks */
	"bookmarks:settings:key" => "Activer les favoris clés du site", 
	"bookmarks:filter:key" => "Les liens favoris importants", 
	"bookmarks:filter:friend" => "Les liens favoris de mes contacts",
	"bookmarks:filter:mine" => "Mes liens favoris",
	"bookmarks:filter:all" => "Tous les liens favoris",
	"bookmarks:forms:isakey" => "Ce lien est un lien favori important",
	
	/**
	 * Widget and bookmarklet
	 */
	'bookmarks:widget:description' => "Ce widget affiche vos derniers favoris.",

	'bookmarks:bookmarklet:description' =>
			"Le bookmarklet vous permez de partager ce que vous trouvez sur le web avec vos contact, ou pour vous-même. Pour l'utiliser, glissez simplement le boutton ci-dessous dans votre barre de liens de votre navigateur.",

	'bookmarks:bookmarklet:descriptionie' =>
			"Si vous utilisez Internet Explorer, faites un clic droit sur le boutton et ajouter le dans vos favoris, puis votre barre de liens.",

	'bookmarks:bookmarklet:description:conclusion' =>
			"Vous pouvez mettre en favoris n'importe quelle page en cliquant sur le bookmarklet.",

	/**
	 * Status messages
	 */

	'bookmarks:save:success' => "Votre élément a bien été mis en favoris.",
	'bookmarks:delete:success' => "Votre favori a bien été supprimé.",

	/**
	 * Error messages
	 */

	'bookmarks:save:failed' => "Votre élément n'a pu être correctement mis en favori. Vérifiez que le titre et le lien soient correct et réessayez.",
	'bookmarks:delete:failed' => "Votre favori n'a pu être supprimé. Merci de réessayer.",
	'bookmarks:save:invalid' => "L'adresse de votre lien est invalide et ne peut être sauvegardée.",

);

add_translation("fr", $french);
