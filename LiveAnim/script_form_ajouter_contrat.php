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
	if(isset($_POST['form_ajout_modification_contrat_id_annonce'])){
	
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');		
		require_once('couche_metier/PCS_contrat.php');
		require_once('couche_metier/PCS_personne.php');
		require_once('couche_metier/PCS_message.php');
		require_once('couche_metier/CL_date.php');
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		$oPCS_contrat = new PCS_contrat();
		$oPCS_personne = new PCS_personne();
		$oPCS_message = new PCS_message();
		$oCL_date = new CL_date();
		
		$nb_erreur = 0;
		$_SESSION['creer_contrat']['message_affiche'] = false;
		$_SESSION['creer_contrat']['message'] = "<br /><br />";
		
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
		
		# On récupère les données du formulaire.
		$ID_ANNONCE = (int)$_POST['form_ajout_modification_contrat_id_annonce'];
		$DATE_DEBUT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_contrat_date_debut']));
		$DATE_FIN = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_contrat_date_fin']));
		$PRIX = preg_replace($chaines_interdites, "", (float)ucfirst(trim($_POST['form_ajout_modification_contrat_prix'])));
		$DESCRIPTION = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_contrat_description']))));
		$DATE_CONTRAT = date('Y-m-d H:i:s');
		$STATUT_CONTRAT = "En attente";
		
		# On sauvegarde les données en session:
		$_SESSION['creer_contrat']['DATE_DEBUT'] = $DATE_DEBUT;
		$_SESSION['creer_contrat']['DATE_FIN'] = $DATE_FIN;
		$_SESSION['creer_contrat']['PRIX'] = $PRIX;
		$_SESSION['creer_contrat']['DESCRIPTION'] = $DESCRIPTION;
		
		# On vérifie que l'ID_ANNONCE envoyé est correct:
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		$oMSG->setData('VISIBLE', 1);
		$oMSG->setData('STATUT', 'Validée');
		
		$annonce = $oPCS_annonce->fx_recuperer_annonce_valide_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
		
		if(empty($annonce[0]['ID_ANNONCE'])){
			$nb_erreur++;
			$_SESSION['creer_contrat']['message'].= "<span class='alert'>Cette annonce est invalide.</span><br />";
			unset($_SESSION['creer_contrat']);
		}
		
		# La personne qui a créée l'annonce est l'organisateur.
		$id_organisateur = $annonce[0]['ID_PERSONNE'];
		# La personne qui créée le contrat est le prestataire.
		$id_prestataire = $_SESSION['compte']['ID_PERSONNE'];
		
		# On vérifie les dates.
		$DATE_DEBUT = str_replace('h', ':', $DATE_DEBUT).':00';# On rajoute les secondes.
		if(!$oCL_date->fx_verif_date($DATE_DEBUT, 'fr', true)){
			$nb_erreur++;
			$_SESSION['creer_contrat']['message'].= "<span class='alert'>La date de début est invalide.</span><br />";
			unset($_SESSION['creer_contrat']['DATE_DEBUT']);
		}
		$DATE_FIN = str_replace('h', ':', $DATE_FIN).':00';# On rajoute les secondes.
		if(!$oCL_date->fx_verif_date($DATE_FIN, 'fr', true)){
			$nb_erreur++;
			$_SESSION['creer_contrat']['message'].= "<span class='alert'>La date de fin est invalide.</span><br />";
			unset($_SESSION['creer_contrat']['DATE_FIN']);
		}
		
		# On vire l'éventuelle virgule du prix.
		$PRIX = str_replace(',', '.', $PRIX);
		
		# On vérifie que la date de début soit supérieure à la date de fin.
		if($oCL_date->fx_ajouter_date($DATE_DEBUT, true, true, 'fr') >= $oCL_date->fx_ajouter_date($DATE_FIN, true, true, 'fr')){
				# Date de début supérieure à la date de fin.
				$_SESSION['creer_contrat']['message'].= "<span class='orange'>La date de début est supérieure à la date de fin.</span><br />";
				$nb_erreur++;
		}
		
		# On vérifie qu'il n'y ait pas déjà un contrat de créée.
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		$oMSG->setData('ID_PERSONNE', $id_prestataire);
		$oMSG->setData('conditions', "AND STATUT_CONTRAT <> 'Annulé';");
		
		$nb_contrat = $oPCS_contrat->fx_compter_contrat_by_ID_ANNONCE_et_ID_PERSONNE_et_condition($oMSG)->getData(1)->fetchAll();
		
		if($nb_contrat[0]['nb_contrat'] > 0){
			# Date de début supérieure à la date de fin.
			$_SESSION['creer_contrat']['message'].= "<span class='orange'>Vous avez déjà un contrat en cours avec cette personne.</span><br />";
			$nb_erreur++;
		}
		
		if($nb_erreur == 0){
			# On met en forme les données.
			$DATE_DEBUT = $oCL_date->fx_ajouter_date($DATE_DEBUT, true, false, 'fr', 'en');
			$DATE_FIN = $oCL_date->fx_ajouter_date($DATE_FIN, true, false, 'fr', 'en');
			
			# On crée le contrat.
			$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
			$oMSG->setData('DATE_DEBUT', $DATE_DEBUT);
			$oMSG->setData('DATE_FIN', $DATE_FIN);
			$oMSG->setData('PRIX', $PRIX);
			$oMSG->setData('DESCRIPTION', $DESCRIPTION);
			$oMSG->setData('DATE_CONTRAT', $DATE_CONTRAT);
			$oMSG->setData('STATUT_CONTRAT', $STATUT_CONTRAT);
			$oMSG->setData('DESTINATAIRE', $id_organisateur);
			
			$ID_CONTRAT = $oPCS_contrat->fx_creer_contrat($oMSG)->getData(1);
			
			# On lie le contrat aux deux personnes.
			$oMSG->setData('ID_PERSONNE', $id_organisateur);
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);

			$oPCS_contrat->fx_lier_contrat($oMSG);# On lie le contrat avec l'organisateur.
			
			$oMSG->setData('ID_PERSONNE', $id_prestataire);
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
			
			$oPCS_contrat->fx_lier_contrat($oMSG);# On lie le contrat avec le prestataire.
			
			# On récupère l'organisateur.
			$oMSG->setData('ID_PERSONNE', $id_organisateur);
			
			$organisateur = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			# On récupère le prestataire.
			$oMSG->setData('ID_PERSONNE', $id_prestataire);
			
			$prestataire = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			# On lui envoi un email comme quoi son annonce a reçue une demande de contrat.
			$additional_headers = "From: noreply@liveanim.fr \r\n";
			$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
			$destinataires = $organisateur[0]['EMAIL'];
			$sujet = utf8_decode("LiveAnim [Demande de contrat]");
			
			$message = "------------------------------\n";
			$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
			$message.= "------------------------------\n\n";
			$message.= utf8_decode("Bonjour ".$organisateur[0]['PSEUDO'].", \n");
			$message.= utf8_decode("Un membre vous propose un contrat concernant une annonce que vous avez faite. \n\n");
			$message.= utf8_decode("Veuillez vous connecter afin d'en prendre connaissance: \n");
			$message.= utf8_decode($oCL_page->getPage('accueil', 'absolu')." \n\n");
			$message.= utf8_decode("------------------------------\n\n\n");
			$message.= utf8_decode("LiveAnim vous remercie de votre confiance et vous rappelle qu'en cas de désaccord avec un artiste nous pouvons vous fournir le contrat que vous aurez validé avec l'artiste !\n\n");
			$message.= utf8_decode("Si vous souhaitez nous contacter vous pouvez nous envoyer un mail à support@liveanim.com \n\n");
			$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
			
			mail($destinataires, $sujet, $message, $additional_headers);
			
			# On crée un message 
			$CONTENU = "<b>Bonjour</b>, <br /><br />";
			$CONTENU.= "Une demande de contrat a été effectuée par l'artiste ".$prestataire[0]['PSEUDO'].".<br />";
			$CONTENU.= "Vous pouvez la visualiser ici: <a href='".$oCL_page->getPage('contrat', 'absolu').$ID_CONTRAT."'>Aller au contrat</a>.<br /><br />";
			$CONTENU.= "Nous vous rappellons que ce contrat est entre vous et l'artiste, LiveAnim n'est pas responsable des services offerts.<br />";
			$CONTENU.= "Toutefois nous mettons à votre disposition ce contrat afin d'avoir une pièce 'justificative' de votre accord.<br /> ";
			$CONTENU.= "En cas de problème vous pouvez nous contacter à: support@liveanim.com<br /> ";
			$CONTENU.= "Nous vous remercions d'utiliser nos services et espérons qu'ils vous apportent entière satisfaction.<br /><br />";
			$CONTENU.= "<span class='rose'>L'équipe LiveAnim.</span><br /> ";
			
			$oMSG->setData('TITRE', "Demande de contrat de ".$prestataire[0]['PSEUDO']);
			$oMSG->setData('CONTENU', $CONTENU);
			$oMSG->setData('DATE_ENVOI', $DATE_CONTRAT);
			$oMSG->setData('EXPEDITEUR', $prestataire[0]['ID_PERSONNE']);
			$oMSG->setData('DESTINATAIRE', $organisateur[0]['ID_PERSONNE']);
			$oMSG->setData('TYPE_MESSAGE', "Contrat");
			$oMSG->setData('VISIBLE', true);
			
			$ID_MESSAGE = $oPCS_message->fx_creer_message($oMSG)->getData(1);
			
			# On envoi le message à l'organisateur.
			$oMSG->setData('ID_PERSONNE', $organisateur[0]['ID_PERSONNE']);
			$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
			$oMSG->setData('STATUT_MESSAGE', 'Non lu');

			$oPCS_message->fx_lier_message($oMSG);
			
			$_SESSION['creer_contrat']['message'].= "<span class='valide'>Le contrat a bien été crée.<br />Vous serez prévenu par email lorsque l'organisateur y aura répondu.<br />Il a été prévenu de votre demande de contrat.</span><br />";
			header('Location:'.$oCL_page->getPage('creer_contrat', 'absolu')."?id_annonce=".$ID_ANNONCE);
		}else{
			# Une erreur a eue lieu.
			$_SESSION['creer_contrat']['message'].= "<span class='orange'>Le contrat n'a pas été crée.</span><br />";
			header('Location: '.$oCL_page->getPage('creer_contrat', 'absolu')."?id_annonce=".$ID_ANNONCE);
		}
	
	}else{
		# On ne reçoit pas les informations du POST.
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>