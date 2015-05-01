<?php
/**
 * Email user validation plugin language pack.
 *
 * @package Elgg.Core.Plugin
 * @subpackage Elgguservalidationbyadmin
 */

$french = array(
	'admin:users:unvalidated' => 'Non validé',
	'admin:users:surname' => 'Nom du membre chargé de valider les nouveaux utilisateurs :',
	
	"email:account:created:success" => "Bienvenue sur %s!",
	"email:account:created:body" => "Bonjour %s,

Merci de votre inscription et bienvenue dans votre nouvel espace ! C’est le vôtre et celui des membres du réseau %s. 

L’objectif de cette plateforme se résume en 3 points :
•	Favoriser la collaboration entre tous 
•	Mutualiser, capitaliser et partager l’information
•	Valoriser les bonnes pratiques

Pour vous aider dans vos premiers clics, nous vous conseillons de :
•	Prendre quelques minutes pour compléter votre profil et y ajouter une photo. Une étape essentielle !
•	Partager vos idées dans le labo d’idées et voter :-)
•	Faire un tour dans le « Bon Coin »
•	Découvrir l’actualité CE

Important, vous trouverez des vidéos d’aide pour vous accompagner à la découverte progressive de ce nouvel espace.

N’hésitez pas à me contacter pour toute question !

Miecâlinement,

%s
",
	
	'email:validate:subject' => "%s demande une validation du compte pour %s!",
	'email:validate:body' => "Bonjour %s,

L'utilisateur %s demande une validation de son compte. 

Vous pouvez le valider en cliquant sur le lien suivant :

%s

Si vous ne pouvez pas cliquez sur le lien, copiez le dans la barre d'url de votre navigateur.

%s
",

	'user:validate:subject' => "Félicitation %s! Votre compte est activé",
	'user:validate:body' => "Bonjour %s,

Votre compte a bien été activé par l'administrateur du site. 

Vous pouvez maintenant vous connecter avec le nom d'utilisateur suivant : %s

Merci
%s
",

	'email:confirm:success' => "Le compte utilisateur est maintenant activé",
	'email:confirm:fail' => "Le compte utilisateur ne peut pas être activé...",

	'uservalidationbyadmin:registerok' => "Vous serez notifié une fois que l'admin validera votre compte",
	'uservalidationbyadmin:login:fail' => "Votre compte n'a pas été validé par l'administrateur du site.",

	'uservalidationbyadmin:admin:no_unvalidated_users' => 'Aucun utilisateur non activé.',

	'uservalidationbyadmin:admin:unvalidated' => 'Non validé',
	'uservalidationbyadmin:admin:user_created' => 'Registered %s',
	'uservalidationbyadmin:admin:resend_validation' => 'Renvoyer l\'email de validation',
	'uservalidationbyadmin:admin:validate' => 'Valider',
	'uservalidationbyadmin:admin:delete' => 'Supprimer',
	'uservalidationbyadmin:confirm_validate_user' => 'Valider %s ?',
	'uservalidationbyadmin:confirm_resend_validation' => 'Renvoyer un email de validation pour %s ?',
	'uservalidationbyadmin:confirm_delete' => 'Supprimer %s?',
	'uservalidationbyadmin:confirm_validate_checked' => 'Valider les utilisateurs sélectionnés ?',
	'uservalidationbyadmin:confirm_resend_validation_checked' => 'Renvoyer un email de validation pour les utilisateurs sélectionnés ?',
	'uservalidationbyadmin:confirm_delete_checked' => 'Supprimer les utilisateurs sélectionnés ?',
	'uservalidationbyadmin:check_all' => 'Tous',

	'uservalidationbyadmin:errors:unknown_users' => 'Utilisateurs inconnus',
	'uservalidationbyadmin:errors:could_not_validate_user' => 'Impossible de valider l\'utilisateur.',
	'uservalidationbyadmin:errors:could_not_validate_users' => 'Impossible de valider les utilisateurs sélectionnés.',
	'uservalidationbyadmin:errors:could_not_delete_user' => 'Impossible de supprimer l\'utilisateur.',
	'uservalidationbyadmin:errors:could_not_delete_users' => 'Impossible de supprimer les utilisateurs sélectionnés.',
	'uservalidationbyadmin:errors:could_not_resend_validation' => 'Impossible de renvoyer l\'email de validation.',
	'uservalidationbyadmin:errors:could_not_resend_validations' => 'Impossible de renvoyer un email de validation pour les utilisateurs sélectionnés',

	'uservalidationbyadmin:messages:validated_user' => 'Utilisateur validé.',
	'uservalidationbyadmin:messages:validated_users' => 'Tous les utilisateurs sélectionnés ont été validés.',
	'uservalidationbyadmin:messages:deleted_user' => 'Utilisateur supprimé.',
	'uservalidationbyadmin:messages:deleted_users' => 'Tous les utilisateurs sélectionnés ont été supprimés.',
	'uservalidationbyadmin:messages:resent_validation' => 'Validation request resent.',
	'uservalidationbyadmin:messages:resent_validations' => 'Validation requests resent to all checked users.'

);

add_translation("fr", $french);