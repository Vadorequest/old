<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

/*
* Script utilisé par la modification d'une annonce par un organisateur.
*/

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
	
	if(isset($_POST['form_ajout_modification_annonce_titre'])){	
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');		
		require_once('couche_metier/PCS_types.php');
		require_once('couche_metier/CL_date.php');
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		$oPCS_types = new PCS_types();
		$oCL_date = new CL_date();
		
		# On récupère les types nécessaires:
		$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');
		$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On récupère les départements:
		$departements = $oPCS_annonce->fx_recuperer_tous_departements($oMSG)->getData(1)->fetchAll();
	
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
		
		# On récupère les données du formulaire.
		$ID_ANNONCE = (int)$_POST['form_ajout_modification_annonce_id_annonce'];
		$ID_PERSONNE = $_SESSION['compte']['ID_PERSONNE'];
		$TITRE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_titre'])));
		$TYPE_ANNONCE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_type_annonce'])));
		$DATE_DEBUT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_date_debut']));
		$DATE_FIN = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_date_fin']));
		$ARTISTES_RECHERCHES = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_artistes_recherches']))));
		$BUDGET = preg_replace($chaines_interdites, "", floatval(str_replace(',', '.', trim($_POST['form_ajout_modification_annonce_budget']))));
		$NB_CONVIVES = preg_replace($chaines_interdites, "", (int)trim($_POST['form_ajout_modification_annonce_nb_convives']));
		$DESCRIPTION = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_description']))));
		$ID_DEPARTEMENT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_id_departement']));# Ne pas transformer en int car la corse est 2a/2b
		$ADRESSE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_adresse'])));
		$CP = preg_replace($chaines_interdites, "", (int)trim($_POST['form_ajout_modification_annonce_cp']));
		$VILLE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_ville'])));
	
			
		$GOLDLIVE = 0;# On ne gère pas la fonction GOLDLIVE pour le moment.
		
		# On prépare nos variables nécessaires pour les messages d'erreurs.
		$_SESSION['modifier_annonce']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
		$_SESSION['modifier_annonce']['message'] = "";# On initialise et on rajoutera par dessus.
		
		$nb_erreur = 0;
		
		# On vérifie l'intégrité des données:
		
		# On commence par vérifier qu'un des champs obligatoire ne soit pas vide.
		if(empty($TITRE) || empty($DATE_DEBUT) || empty($DATE_FIN) || empty($ARTISTES_RECHERCHES) || empty($DESCRIPTION) || empty($ADRESSE) || empty($CP) || empty($VILLE)){
			# Un des champs obligatoire est vide.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Un des champs obligatoire est vide.</span><br />";
			$nb_erreur++;
		}
		
		# On vérifie la taille des champs.
		if(strlen($TITRE) > 50){
			# Le titre est trop long.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le titre est trop long, 50 caractères maximum.</span><br />";
			$nb_erreur++;
		}
		
		if(strlen($TITRE) < 5){
			# Le titre est trop court.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le titre est trop court, 5 caractères minimum.</span><br />";
			$nb_erreur++;
		}
		
		# On vérifie les valeurs des listes déroulantes.
			# Vérification des types d'annonces.
			$liste_types_annonce = array();
			foreach($types_annonce as $key=>$type_annonce){
				$liste_types_annonce[$key] = $type_annonce['ID_TYPES'];
			}
			if(!in_array($TYPE_ANNONCE, $liste_types_annonce)){
				# L'utilisateur a modifié le code source, on l'envoi chier.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le type d'annonce est incorrect.</span><br />";
				$nb_erreur++;
			}
			
			# Vérification des départements.
			$liste_departements = array();
			foreach($departements as $key=>$departement){
				$liste_departements[$key] = $departement['ID_DEPARTEMENT'];
			}
			if(!in_array($ID_DEPARTEMENT, $liste_departements)){
				# L'utilisateur a modifié le code source, on l'envoi chier.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le département selectionné n'existe pas.</span><br />";
				$nb_erreur++;
			}
		
		# On vérifie les dates.
			# On vérifie la date de début.
			$DATE_DEBUT = str_replace('h', ':', $DATE_DEBUT).':00';# On rajoute les secondes.
			if(!$oCL_date->fx_verif_date($DATE_DEBUT, 'fr', true)){
				# La date de début est incorrecte.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>La date de début est incorrecte. <span class='petit'>(06/05/2011 20h56)</span></span><br />";
				$nb_erreur++;
			}
			
			# On vérifie la date de fin.
			$DATE_FIN = str_replace('h', ':', $DATE_FIN).':00';# On rajoute les secondes.
			if(!$oCL_date->fx_verif_date($DATE_FIN, 'fr', true)){
				# La date de début est incorrecte.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>La date de fin est incorrecte. <span class='petit'>(06/05/2011 20h56)</span></span><br />";
				$nb_erreur++;
			}
			
		# On vérifie le budget.
		if($BUDGET != 0){
			if(!is_numeric($BUDGET)){
				# La date de début est incorrecte.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le budget est de format incorrect. <span class='petit'>(53.5 ou 53,5 ou 53)</span></span><br />";
				$nb_erreur++;
			}
		}
		
		# On vérifie que la date de début soit supérieure à la date de fin.
		if($oCL_date->fx_ajouter_date($DATE_DEBUT, true, true, 'fr') >= $oCL_date->fx_ajouter_date($DATE_FIN, true, true, 'fr')){
				# Date de début supérieure à la date de fin.
				$_SESSION['modifier_annonce']['message'].= "<span class='orange'>La date de début est supérieure à la date de fin.</span><br />";
				$nb_erreur++;
		}
		
		# On charge l'annonce en cours afin de vérifier les droits de modification.
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		
		$annonce = $oPCS_annonce->fx_recuperer_annonce_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
		
		if($annonce[0]['ID_PERSONNE'] != $ID_PERSONNE){
			# La personne qui modifie n'est pas la même que celle qui possède l'annonce.
			$_SESSION['connexion']['message_affiche'] = false;# On redirige vers la page de l'annonce mais elle va elle même rediriger vers l'accueil.
			$_SESSION['connexion']['message'] = "<span class='alert'>Vous n'avez pas les droits nécessaires pour modifier cette annonce.</span><br />";
			$nb_erreur++;
		}
		
		if($annonce[0]['STATUT'] == "Validée"){
			# L'annonce a déjà été validée, on refuse les modifications
			$_SESSION['modifier_annonce']['message'] = "<span class='alert'>Vous ne pouvez pas modifier une annonce validée.</span><br />";
			$nb_erreur++;
		}
		
		if($nb_erreur == 0){
			# L'intégrité des données est vérifiée.
			
			# On convertit les dates en en.
			$DATE_DEBUT = $oCL_date->fx_ajouter_date($DATE_DEBUT, true, false, 'fr');
			$DATE_FIN = $oCL_date->fx_ajouter_date($DATE_FIN, true, false, 'fr');
			
			# On modifie l'annonce.
			$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
			$oMSG->setData('ID_DEPARTEMENT', $ID_DEPARTEMENT);
			$oMSG->setData('TITRE', $TITRE);
			$oMSG->setData('TYPE_ANNONCE', $TYPE_ANNONCE);
			$oMSG->setData('DATE_DEBUT', $DATE_DEBUT);
			$oMSG->setData('DATE_FIN', $DATE_FIN);
			$oMSG->setData('ARTISTES_RECHERCHES', $ARTISTES_RECHERCHES);
			$oMSG->setData('BUDGET', $BUDGET);
			$oMSG->setData('NB_CONVIVES', $NB_CONVIVES);
			$oMSG->setData('DESCRIPTION', $DESCRIPTION);
			$oMSG->setData('ADRESSE', $ADRESSE);
			$oMSG->setData('CP', $CP);
			$oMSG->setData('VILLE', $VILLE);
			$oMSG->setData('GOLDLIVE', $GOLDLIVE);
			$oMSG->setData('VISIBLE', $annonce[0]['VISIBLE']);
			$oMSG->setData('STATUT', $annonce[0]['STATUT']);
			
			$oPCS_annonce->fx_modifier_annonce_by_ID_ANNONCE($oMSG)->getData(1);
			
			
			
			$_SESSION['modifier_annonce']['message'].= "<span class='valide'>L'annonce a été modifiée avec succès. Vous serez prévenu par email lorsqu'elle sera validée par l'administration.</span><br />";
			header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu')."?id_annonce=".$ID_ANNONCE);
			
		}else{
			# Au moins une erreur a été detectée.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>L'annonce n'a pas été modifiée.</span><br />";
			//header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu')."?id_annonce=".$ID_ANNONCE);
			echo $ID_ANNONCE;
		}
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>