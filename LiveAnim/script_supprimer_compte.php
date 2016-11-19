<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
	if(isset($_POST['form_supprimer_compte_email'])){
	
		# On prépare nos variables.
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_personne.php');
		require_once('couche_metier/CL_cryptage.php');
		
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		$oCL_cryptage = new CL_cryptage();
		
		$_SESSION['supprimer_compte']['message'] = "";
		$_SESSION['supprimer_compte']['message_affiche'] = false;

		$nb_erreur = 0;
	
		# On vérifie qu'un des champs obligatoire ne soit pas vide.
		if(empty($_POST['form_supprimer_compte_email']) || empty($_POST['form_supprimer_compte_raison']) || empty($_POST['form_supprimer_compte_mdp'])){
			$nb_erreur++;
			$_SESSION['supprimer_compte']['message'].= "<span class='alert'>Un des champs obligatoires est vide.</span><br />";				
		}
		
		# On supprime les chaines interdites.
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/");
		
		$EMAIL = preg_replace ($chaines_interdites, "", trim($_POST['form_supprimer_compte_email']));
		$RAISON_SUPPRESSION = preg_replace ($chaines_interdites, "", trim(ucfirst($_POST['form_supprimer_compte_raison'])));
		$MDP = $_POST['form_supprimer_compte_mdp'];
		
		# On vérifie que la taille du mot de passe.
		if(strlen($MDP) < 4){
			$nb_erreur++;
			$_SESSION['supprimer_compte']['message'].= "<span class='alert'>Le mot de passe est trop court, 4 caractères minimum.</span><br />";
		}
		
		if(strlen($MDP) > 20){
			$nb_erreur++;
			$_SESSION['supprimer_compte']['message'].= "<span class='alert'>Le mot de passe est trop long, 20 caractères maximum.</span><br />";
		}
		
		# On vérifie que le format de l'adresse email soit valide.
		if(!filter_var($EMAIL, FILTER_VALIDATE_EMAIL)){
			$_SESSION['supprimer_compte']['message'].= "<span class='alert'>L'email que vous avez rentré est invalide.</span><br />";
			$nb_erreur++;
		}
		
		# S'il n'y a pas d'erreurs.
		if($nb_erreur == 0){
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('EMAIL', $EMAIL);
			$oMSG->setData('MDP', utf8_encode($oCL_cryptage->Cryptage($MDP, $_SESSION['compte']['PSEUDO'])));
			$oMSG->setData('RAISON_SUPPRESSION', $RAISON_SUPPRESSION);
			
			# On vérifie que le mot de passe et l'email et l'ID_PERSONNE correspondent bien.
			$nb_personne = $oPCS_personne->fx_compter_personne_by_ID_PERSONNE_et_EMAIL_et_MDP($oMSG)->getData(1)->fetchAll();

			if($nb_personne[0]['nb_personne'] == 1){
				if($_POST['form_supprimer_compte_infos_perso']){
					# On calcule la date d'aujourd'hui.
					$now = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"),  date("Y")));
				
					# S'il souhaite supprimer immédiatement ses informations personnelles.
					$oMSG->setData('NOM', '');
					$oMSG->setData('PRENOM', '');
					$oMSG->setData('URL_PHOTO_PRINCIPALE', '');
					$oMSG->setData('DATE_NAISSANCE', '0000-00-00');
					$oMSG->setData('CIVILITE', '');
					$oMSG->setData('VILLE', '');
					$oMSG->setData('ADRESSE', '');
					$oMSG->setData('CP', '');
					$oMSG->setData('TEL_FIXE', '');
					$oMSG->setData('TEL_PORTABLE', '');
					
					$oMSG->setData('VISIBLE', 0);
					$oMSG->setData('PERSONNE_SUPPRIMEE', 1);
					$oMSG->setData('DATE_BANNISSEMENT', '0000-00-00');
					$oMSG->setData('DATE_SUPPRESSION_REELLE', $now);
					$oMSG->setData('RAISON_SUPPRESSION', $RAISON_SUPPRESSION);
					
					$oPCS_personne->fx_supprimer_infos_perso_by_ID_PERSONNE($oMSG);
					$oPCS_personne->fx_bannir_personne($oMSG);
					
					$_SESSION = array();
					session_destroy();
					session_unset();
					session_start();
					
					# On s'apprête à afficher le message sur la page d'accueil.
					$_SESSION['connexion']['message_affiche'] = false;
					$_SESSION['connexion']['message'].= "<span class='valide'>Votre compte a été supprimé et vos informations personnelles supprimées. Votre avis a bien été pris en compte.</span><br />Vous avez été déconnecté.";
					header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
					
				}else{
					# On calcule la date d'aujourd'hui + 2 mois.
					$now = date("Y-m-d", mktime(0, 0, 0, date("m")+2, date("d"),  date("Y")));
				
					$oMSG->setData('VISIBLE', 0);
					$oMSG->setData('PERSONNE_SUPPRIMEE', 1);
					$oMSG->setData('DATE_BANNISSEMENT', '0000-00-00');
					$oMSG->setData('DATE_SUPPRESSION_REELLE', $now);
					$oMSG->setData('RAISON_SUPPRESSION', $RAISON_SUPPRESSION);
					
					$oPCS_personne->fx_bannir_personne($oMSG);
					
					$_SESSION = array();
					session_destroy();
					session_unset();
					session_start();
					
					# On s'apprête à afficher le message sur la page d'accueil.
					$_SESSION['connexion']['message_affiche'] = false;					
					$_SESSION['connexion']['message'].= "<span class='valide'>Votre compte a bien été désactivé. Vos informations personnelles seront supprimées d'ici 2 mois.<br/>Votre avis a bien été pris en compte.</span><br />Vous avez été déconnecté.";
					header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
				}
				
				
			
			}else{
				$_SESSION['supprimer_compte']['message'].= "<span class='alert'>Le mot de passe ou l'email sont incorrects.</span><br />";
				header('Location: '.$oCL_page->getPage('supprimer_compte', 'absolu'));
			}
		}else{
			$_SESSION['supprimer_compte']['message'].= "<span class='alert'>Le compte n'a pas été supprimé.</span><br />";
			header('Location: '.$oCL_page->getPage('supprimer_compte', 'absolu'));
		}		
	}else{
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>