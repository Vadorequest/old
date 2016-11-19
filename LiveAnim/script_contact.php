<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(isset($_POST['form_contact_raison'])){

	$nb_erreur = 0;
	$_SESSION['contact']['message_affiche'] = false;
	$_SESSION['contact']['message'] = "";
	$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
	
	# On récupère les informations.
	if(!$_SESSION['compte']['connecté']){
		$pseudo = preg_replace($chaines_interdites, "", trim($_POST['form_contact_pseudo']));
		$email = preg_replace($chaines_interdites, "", trim($_POST['form_contact_email']));
	}else{
		$pseudo = $_SESSION['compte']['PSEUDO'];
		$email = $_SESSION['compte']['EMAIL'];
	}
	$raison = (int)$_POST['form_contact_raison'];
	$description = nl2br(preg_replace($chaines_interdites, "", trim($_POST['form_contact_descriptif'])));

	# On met en session les variables.
	$_SESSION['contact']['pseudo'] = $pseudo;
	$_SESSION['contact']['email'] = $email;
	$_SESSION['contact']['raison'] = $raison;
	$_SESSION['contact']['description'] = $description;
	
	
	# On vérifie que les variables ne soient pas vides.
	if(empty($raison) || empty($description) || empty($pseudo) || empty($email)){
		$nb_erreur++;
		$_SESSION['contact']['message'].= "<span class='orange'>Un des champs obligatoire est vide.</span><br />";
	}
	
	# SI le membre n'est pas connecté alors on  vérifie qu'il a rentré un email correct.
	if(!$_SESSION['compte']['connecté']){
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$nb_erreur++;
			$_SESSION['contact']['message'].= "<span class='alert'>Votre email est invalide.</span><br />";
		}
	}

	# On vérifie que la raison soit valide
	if($raison == 0){
		$nb_erreur++;
		$_SESSION['contact']['message'].= "<span class='orange'>La raison sélectionnée est invalide.</span><br />";	
	}
	
	# On vérifie que le membre ne nous envoie pas plein de mails.
	if($_SESSION['contact']['dernier_contact_envoye']){
		if(date('YmdHis') - $_SESSION['contact']['dernier_contact_envoye'] < 120){
			$nb_erreur++;
			$_SESSION['contact']['message'].= "<span class='orange'>Vous ne pouvez pas renvoyer d'email immédiatement, veuillez patienter quelques minutes.</span><br />";	
		}
	}
	
	if($nb_erreur == 0){
		switch($raison){
			case 1: $raison = "Problème connexion";
				break;
			case 2: $raison = "Compte banni";
				break;
			case 3: $raison = "Compte suspendu temporairement";
				break;
			case 4: $raison = "Bug";
				break;
			case 5: $raison = "Problème affichage";
				break;
			case 6: $raison = "Faille de sécurité";
				break;
			case 7: $raison = "Non précisé";
				break;
		}
		
		# On envoi l'email:		
		$additional_headers = "From: contact@liveanim.fr \r\n";
		$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
		$destinataires = "contact@liveanim.com";
		$sujet = utf8_decode("[".$pseudo."] ".$raison);
		
		$message = "------------------------------\n";
		$message.= utf8_decode("Mail envoyé depuis le formulaire de contact (".$oCL_page->getpage('contact', 'absolu').") \n");
		$message.= utf8_decode("Expéditeur: ".$pseudo." \n");
		$message.= utf8_decode("Email: ".$email." \n");
		$message.= utf8_decode("Sujet: ".$raison." \n");
		$message.= utf8_decode("Corps du message: \n ".$description." \n\n");
		$message.= utf8_decode("------------------------------\n\n\n");
		$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
		
		if(mail($destinataires, $sujet, $message, $additional_headers)){
			# Si le mail a été correctement envoyé:			
			$_SESSION['contact']['message'].= 
				"<span class='valide'>L'email a bien été envoyé. Nous vous remercions.</span><br /><br />";
		}else{
			$_SESSION['contact']['message'].= 
				"<span class='valide'>Pour une raison indépendante de notre volonté l'email n'a pas pu être envoyé.<br />
				Notre serveur mail semble être hors service pour le moment, veuillez réessayer.<br />
				<span class='petit'>Si l'opération échoue plus d'une fois c'est que notre hébergeur rencontre un problème, dans ce cas inutile de réessayer
				immédiatement, cela peut aller de quelques minutes à quelques heures avant que ce service ne redevienne opérationnel.<br /></span>
				Nous nous excusons pour la gêne occasionnée.</span><br /><br />";
		}
		
		# On supprime les infos sauvegardées du formulaire.
		unset($_SESSION['contact']['pseudo']);
		unset($_SESSION['contact']['email']);
		unset($_SESSION['contact']['raison']);
		unset($_SESSION['contact']['description']);
		
		$_SESSION['contact']['dernier_contact_envoye'] = date('YmdHis');
		header('Location:'.$oCL_page->getpage('contact'));
	}else{
		$_SESSION['contact']['message'].= "<span class='alert'>Une erreur est survenue, l'opération a été annulée.</span><br />";
		header('Location:'.$oCL_page->getpage('contact'));
	}
}else{
	# Pas de POST.
	header('Location:'.$oCL_page->getpage('accueil'));
}
?>