<?php
	$english = array(
		'friend_request' => "Demande de contact",
		'friend_request:menu' => "Demandes de contact",
		'friend_request:title' => "Demandes de contact",
	
		'friend_request:new' => "Nouvelle demande de contact",
		
		'friend_request:friend:add:pending' => "Demande de contact en attente",
		
		'friend_request:newfriend:subject' => "%s souhaite vous avoir en contact!",
		'friend_request:newfriend:body' => "Vous avez une nouvelle demande de contact de %s ! Vous pouvez l'accepter en suivant ce lien :

%s
",
		
		// Actions
		// Add request
		'friend_request:add:failure' => "Le système n'a pas pu effectuer votre demande de contact. Veuillez réessayer.",
		'friend_request:add:successful' => "La demande de contact a été envoyée à %s.",
		'friend_request:add:exists' => "Une demande de mise en contact à déjà été envoyée pour %s.",
		
		// Approve request
		'friend_request:approve' => "Accepter",
		'friend_request:approve:successful' => "%s fait maintenant parti de votre liste de contacts",
		'friend_request:approve:fail' => "Un problème est survenu lors de votre mise en contact avec %s. Veuillez réessayer",
	
		// Decline request
		'friend_request:decline' => "Annuler",
		'friend_request:decline:subject' => "%s a refusé votre demande de mise en contact",
		'friend_request:decline:message' => "Cher %s,

%s a refusé votre demande de mise en contact.",
		'friend_request:decline:success' => "La demande de mise en contact a bien été supprimée",
		'friend_request:decline:fail' => "Un problème est survenu lors de la suppression de la demande de mise en contact. Veuillez réessayer",
		
		// Revoke request
		'friend_request:revoke' => "Rejeter",
		'friend_request:revoke:success' => "La demande de mise en contact a bien été rejetée",
		'friend_request:revoke:fail' => "Un problème est survenu. Veuillez réessayer",
	
		// Views
		// Received
		'friend_request:received:title' => "Demandes de mises en contact reçues et non traitées :",
		'friend_request:received:none' => "Aucune demande en attente",
	
		// Sent
		'friend_request:sent:title' => "Vos demandes de mises en contact :",
		'friend_request:sent:none' => "Aucune demande en attente",
	);
					
	add_translation("fr", $english);
?>