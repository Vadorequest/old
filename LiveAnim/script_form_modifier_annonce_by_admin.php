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
* Script utilisé par la modification d'une annonce par un admin.
*/

# On vérifie que la personne soit connectée et admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
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
		
		# On récupère les données du formulaire.
		$ID_ANNONCE = (int)$_POST['form_ajout_modification_annonce_id_annonce'];
		$TITRE = ucfirst(trim($_POST['form_ajout_modification_annonce_titre']));
		$TYPE_ANNONCE = ucfirst(trim($_POST['form_ajout_modification_annonce_type_annonce']));
		$DATE_DEBUT = trim($_POST['form_ajout_modification_annonce_date_debut']);
		$DATE_FIN = trim($_POST['form_ajout_modification_annonce_date_fin']);
		$ARTISTES_RECHERCHES = nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_artistes_recherches'])));
		$BUDGET = floatval(str_replace(',', '.', trim($_POST['form_ajout_modification_annonce_budget'])));
		$NB_CONVIVES = (int)trim($_POST['form_ajout_modification_annonce_nb_convives']);
		$DESCRIPTION = nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_description'])));
		$ID_DEPARTEMENT = trim($_POST['form_ajout_modification_annonce_id_departement']);# Ne pas transformer en int car la corse est 2a/2b
		$ADRESSE = ucfirst(trim($_POST['form_ajout_modification_annonce_adresse']));
		$CP = (int)trim($_POST['form_ajout_modification_annonce_cp']);
		$VILLE = ucfirst(trim($_POST['form_ajout_modification_annonce_ville']));
		$STATUT = ucfirst(trim($_POST['form_ajout_modification_annonce_statut']));
		$REFUS = nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_refus'])));
	
			
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
		
		# On vérifie le statut de l'annonce et on en détermine sa visibilité.
		if($STATUT == "En attente"){
			$VISIBLE = 0;
		}else if($STATUT == "Validée"){
			$VISIBLE = 1;
		}else if($STATUT == "Refusée"){
			$VISIBLE = 0;
		}else{
			$nb_erreur++;
			$_SESSION['modifier_annonce']['message'].= "<span class='orange'>Le statut sélectionné est incorrect.</span><br />";
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
			$oMSG->setData('VISIBLE', $VISIBLE);
			$oMSG->setData('STATUT', $STATUT);
			
			$oPCS_annonce->fx_modifier_annonce_by_ID_ANNONCE($oMSG)->getData(1);
			
			#On envoi l'email.
			$additional_headers = "From: noreply@liveanim.fr \r\n";
			$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
			$destinataires = $_SESSION['compte']['EMAIL'];
			if($STATUT == "Validée"){
				$sujet = utf8_decode("LiveAnim [Annonce N°".$ID_ANNONCE." validée !]");
				$message = "------------------------------\n";
				$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
				$message.= "------------------------------\n\n";
				$message.= utf8_decode("Votre annonce N°".$ID_ANNONCE." a été validée par un administrateur de LiveAnim. \n\n");
				$message.= utf8_decode("Elle est désormais visible de tous les prestataires. \n");
				$message.= utf8_decode("N'oubliez pas que vous pouvez activer la fonctionnalité GoldLive pour toutes les annonces que vous créez, ce qui améliore leur visibilité et donc les chances de trouver des artistes ! \n");
			}else if($STATUT == "Refusée"){
				$sujet = utf8_decode("LiveAnim [Annonce N°".$ID_ANNONCE." refusée.]");
				$message = "------------------------------\n";
				$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
				$message.= "------------------------------\n\n";
				$message.= utf8_decode("Votre annonce N°".$ID_ANNONCE." a été refusée par notre service de modération pour la raison suivante: \n");
				$message.= utf8_decode($REFUS." \n\n");
				$message.= utf8_decode("Veuillez prendre vos dispositions afin que votre annonce soit acceptée en respectant nos règles, merci. \n");
				$message.= utf8_decode("Vous pouvez modifier votre annonce et la soumettre à nouveau. \n");
			}else if($STATUT == "En attente"){
				$sujet = utf8_decode("LiveAnim [Modification de l'annonce N°".$ID_ANNONCE.".]");
				$message = "------------------------------\n";
				$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
				$message.= "------------------------------\n\n";
				$message.= utf8_decode("Votre annonce N°".$ID_ANNONCE." a été modifiée par un administrateur. \n");
				$message.= utf8_decode("Vous pouvez voir les modifications dans votre page de gestion des annonces. \n");
				$message.= utf8_decode("Votre annonce est toujours en attente, elle devrait être validée sous peu, prenez tout de même connaissance des modifications apportées.	\n");
			}else{}# Pas d'autre possibilité, vérifié avant.
			
			$message.= utf8_decode("------------------------------\n\n\n");
			$message.= utf8_decode("LiveAnim vous remercie de votre confiance.\n\n");
			$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
			
			mail($destinataires, $sujet, $message, $additional_headers);
			
			
			$_SESSION['modifier_annonce']['message'].= "<span class='valide'>L'annonce a été modifiée avec succès. Un email a été envoyé à son possésseur pour le prévenir des modifications.</span><br />";
			header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu')."?id_annonce=".$ID_ANNONCE);
			
		}else{
			# Au moins une erreur a été detectée.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>L'annonce n'a pas été modifiée.</span><br />";
			header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu')."?id_annonce=".$ID_ANNONCE);
		}
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>