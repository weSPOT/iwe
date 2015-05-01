<?php
/**
 * videos English language file
 *	Author : Sarath C | Team Webgalli
 *	Team Webgalli | Elgg developers and consultants
 *	Mail : webgalli@gmail.com
 *	Web	: http://webgalli.com | http://plugingalaxy.com
 *	Skype : 'team.webgalli' or 'drsanupmoideen'
 *	@package Elgg-videos
 * 	Plugin info : Upload/ Embed videos. Save uploaded videos in youtube and save your bandwidth and server space
 *	Licence : GNU2
 *	Copyright : Team Webgalli 2011-2015
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'videos' => "Toutes les vidéos",
	'videos:add' => "Ajouter une vidéo",
	'videos:embed' => "Intégrer une video",
	'videos:embedurl' => "URL",
	'videos:edit' => "Modifier",
	'videos:owner' => "Vidéos de %s",
	'videos:friends' => "Vidéos de mes contacts",
	'videos:everyone' => "Toutes les videos",
	'videos:this:group' => "Vidéos de %s",
	'videos:morevideos' => "Plus de vidéos",
	'videos:more' => "Plus",
	'videos:with' => "Partager avec",
	'videos:new' => "Nouvelle vidéo",
	'videos:via' => "via vidéos",
	'videos:none' => 'Aucune vidéos existante',
	'videos:owner' => 'Mes vidéos',
	'videos:friends' => 'Vidéos de mes contacts',

	'videos:delete:confirm' => "Etes-vous sûre de vouloir supprimer cette vidéo ?",

	'videos:numbertodisplay' => 'Nombre de vidéos à afficher',

	'videos:shared' => "vidéos partagées",
	'videos:recent' => "vidéos récentes",

	'videos:river:created' => 'vidéo ajoutée %s',
	'videos:river:annotate' => 'un commentaire sur cette vidéo',
	'videos:river:item' => 'un élément',
	'river:commented:object:videos' => 'une vidéo',

	'river:create:object:videos' => '%s a ajouté une video %s',
	'river:comment:object:videos' => '%s a commenté une video %s',
	'videos:river:annotate' => 'un commentaire sur cette vidéo',
	'videos:river:item' => 'un élément',
	
	
	
	'item:object:videos' => 'Vidéos',

	'videos:group' => 'Vidéos',
	'videos:enablevideos' => 'Autoriser les vidéos de groupe',
	'videos:nogroup' => 'Ce groupe ne contient aucune vidéo',
	'videos:more' => 'Plus de vidéos',

	'videos:no_title' => 'Pas de titre',
	'videos:file' => 'Sélectionner le fichier vidéo à intégrer',

	/**
	 * Widget
	 */
	'videos:widget:description' => "Affichage de vos dernière vidéos.",

	/**
	 * Status messages
	 */

	'videos:save:success' => "La vidéo a bien été enregistrée.",
	'videos:delete:success' => "La vidéo a bien été supprimée.",

	/**
	 * Error messages
	 */

	'videos:save:failed' => "La vidéo n'a pas pu être enregistrée. Assurez-vous que vous avez bien saisi un titre et une description.",
	'videos:delete:failed' => "La vidéo n'a pas pu être supprimée. Veuillez réessayer.",
	'videos:noembedurl' => 'URL non renseignée',
	 /**
	  * Temporary loading of Cash's Video languages
	  */
	  'embedvideo:novideo' => 'Pas de vidéo',
	  'embedvideo:unrecognized' => 'Vidéo non reconnue',
	  'embedvideo:parseerror' => 'Erreur de chargement de la vidéo',
);

add_translation('en', $english);