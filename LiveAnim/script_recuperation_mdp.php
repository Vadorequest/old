<?php
session_start();
require_once('couche_metier/CL_page.php');
$oCL_page = new CL_page();

$_SESSION['récupération'] = array();
$_SESSION['récupération']['message'] = "";
$_SESSION['récupération']['message_affiche'] = false;

$nb_erreur = 0;

if(isset($_POST['btn_form_recuperation_mdp_valider'])){
	# Si on reçoit bien nos données du formulaire de récupération de mot de passe.
	# On vérifie que les champs ne sont pas vides.
	if(empty($_POST['form_recuperation_mdp_pseudo']) || empty($_POST['form_recuperation_mdp_email'])){
		$_SESSION['récupération']['message'].= "<span class='alert'>Un des deux champs est vide.</span><br />";
		header('location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
		$nb_erreur++;
	}

	# On vérifie que le pseudo a une taille correcte.
	if(strlen($_POST['form_recuperation_mdp_pseudo']) < 4){
		$_SESSION['récupération']['message'].= "<span class='alert'>Le pseudo doit faire au moins 4 caractères.</span><br />";
		header('location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
		$nb_erreur++;
	}

	if(strlen($_POST['form_recuperation_mdp_pseudo']) > 20){
		$_SESSION['récupération']['message'].= "<span class='alert'>Le pseudo doit faire moins de 20 caractères.</span><br />";
		header('location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
		$nb_erreur++;
	}

	# On vérifie l'intégrité de l'email.
	if(!filter_var($_POST['form_recuperation_mdp_email'], FILTER_VALIDATE_EMAIL)){
		
		$_SESSION['récupération']['message'].= "<span class='alert'>L'email que vous avez rentré est invalide.</span><br />";
		header('Location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
		$nb_erreur++;
	}

	# Une fois les vérifications faites, on vérifie qu'il n'y a pas d'erreur.
	if($nb_erreur == 0){
	
		$PSEUDO = ucfirst(trim($_POST['form_recuperation_mdp_pseudo']));
		$EMAIL = trim($_POST['form_recuperation_mdp_email']);
		
		# On récupère toutes les personnes et leur MDP qui ont ce mail et ce pseudo (normalement, 1 max).
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_personne.php');
		
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		
		$oMSG->setData('PSEUDO', $PSEUDO);
		$oMSG->setData('EMAIL', $EMAIL);
		
		$Personne = $oPCS_personne->fx_recuperer_compte_by_PSEUDO_et_EMAIL($oMSG)->getData(1)->fetchAll();
		
		# On vérifie qu'on a bien un tableau non vide
		if(isset($Personne[0])){
			if(empty($Personne[0])){
				# Erreur: On a un tableau vide
				$_SESSION['récupération']['message'].= "<span class='alert'>Il n'y a pas de compte existant pour ce couple Pseudo/Mot de passe. Réessayez.</span><br />";
				header('Location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
				die();
			}

			}else{
			# Erreur: On a un tableau vide
			$_SESSION['récupération']['message'].= "<span class='alert'>Il n'y a pas de compte existant pour ce couple Pseudo/Mot de passe. Réessayez.</span><br />";
			header('Location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
			die();
		}

		# On récupère et décode le MDP.
		$MDP = utf8_decode($Personne[0]['MDP']);
		
		require_once('couche_metier/CL_cryptage.php');
		$oCL_cryptage= new CL_cryptage();
		
		# On décrypte le MDP
		$MDP = $oCL_cryptage->Cryptage($MDP, $PSEUDO);

		# On envoi le MDP par email à l'EMAIL fournit.
		
		$additional_headers = "From: noreply@wikibakoro.fr.cr/ \r\n";
		$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
		$destinataires = $EMAIL;
		$sujet = utf8_decode("[Wiki de Bakoro] Récupération de mot de passe");
		
		$message = "------------------------------\n";
		$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
		$message.= "------------------------------\n\n";
		$message.= utf8_decode("Bonjour ".$PSEUDO.", \n");
		$message.= utf8_decode("Vous recevez cet e-mail car vous avez fait une récupération de mot de passe sur nos services. \n");
		$message.= utf8_decode("Si ce n'est pas vous qui avez fait cette récupération sachez que le mot de passe n'a pas été modifié et vous est simplement renvoyé à cette adresse e-mail. \n");
		$message.= utf8_decode("L'adresse IP enregistrée lors de la demande de récupération est: ".$_SERVER["REMOTE_ADDR"]." \n\n");
		$message.= utf8_decode("Votre mot de passe: ".$MDP." \n\n------------------------------\n");
		$message.= utf8_decode("Nous vous souhaitons de nombreuses et agréables visites sur notre site !\n\n");
		$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
		
		if(mail($destinataires, $sujet, $message, $additional_headers)){
			$_SESSION['récupération']['message'] = "<span class='valide'>Un e-mail vous a été envoyé. Pensez à vérifier vos spams !</span><br />";
		}else{
			$_SESSION['récupération']['message'] = "<span class='alert'>Le serveur de mail ne fonctionne pas, veuillez contacter directement un administrateur pour qu'il règle votre problème ou bien attendre que le serveur de mail soit à nouveau fonctionnel. Nous nous excusons pour cet incident sur lequel nous n'avons aucune possibilité de gestion.</span><br />";
		}	
		header('Location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
	}
}else{
	header('location: '.$oCL_page->getPage('recuperation_mdp', 'absolu', 'absolu'));
}