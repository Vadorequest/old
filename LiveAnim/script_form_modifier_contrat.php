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
		require_once('couche_metier/PCS_contrat.php');
		require_once('couche_metier/PCS_personne.php');
		require_once('couche_metier/PCS_message.php');
		require_once('couche_metier/CL_date.php');
		
		$oMSG = new MSG();
		$oPCS_contrat = new PCS_contrat();
		$oPCS_personne = new PCS_personne();
		$oPCS_message = new PCS_message();
		$oCL_date = new CL_date();
		
		$nb_erreur = 0;
		$_SESSION['modifier_contrat']['message_affiche'] = false;
		$_SESSION['modifier_contrat']['message'] = "<br /><br />";
		
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
		
		# On récupère les données du formulaire.
		$ID_CONTRAT = (int)$_POST['form_ajout_modification_contrat_id_contrat'];
		$DATE_DEBUT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_contrat_date_debut']));
		$DATE_FIN = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_contrat_date_fin']));
		$PRIX = preg_replace($chaines_interdites, "", (float)ucfirst(trim($_POST['form_ajout_modification_contrat_prix'])));
		$DESCRIPTION = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_contrat_description']))));
		$STATUT_CONTRAT = "En attente";
		
		# On sauvegarde les données en session:
		$_SESSION['modifier_contrat']['DATE_DEBUT'] = $DATE_DEBUT;
		$_SESSION['modifier_contrat']['DATE_FIN'] = $DATE_FIN;
		$_SESSION['modifier_contrat']['PRIX'] = $PRIX;
		$_SESSION['modifier_contrat']['DESCRIPTION'] = $DESCRIPTION;
		
		# on vérifie que la personne ait le droit de modifier ce contrat. (Sélection sur le contrat avec vérification comme quoi il est bien destinataire)
		$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
		$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
		
		$nb_contrat = $oPCS_contrat->fx_compter_contrat_by_ID_CONTRAT_et_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

		if($nb_contrat[0]['nb_contrat'] != 1){
			# La personne n'a pas le droit de modifier ce contrat.
			$_SESSION['modifier_contrat'] = Array();
			unset($_SESSION['modifier_contrat']);
			
			$_SESSION['historique_contrat']['message_affiche'] = false;
			$_SESSION['historique_contrat']['message'] = "<center class='alert'>Vous n'avez pas les droits pour modifier ce contrat.</center><br />";
			header('Location: '.$oCL_page->getPage('historique_contrat')."?rq=courants");
			die();# On kill le processus en cours.
		}
		
		# On vérifie les dates.
		$DATE_DEBUT = str_replace('h', ':', $DATE_DEBUT).':00';# On rajoute les secondes.
		if(!$oCL_date->fx_verif_date($DATE_DEBUT, 'fr', true)){
			$nb_erreur++;
			$_SESSION['modifier_contrat']['message'].= "<span class='alert'>La date de début est invalide.</span><br />";
			unset($_SESSION['modifier_contrat']['DATE_DEBUT']);
		}
		$DATE_FIN = str_replace('h', ':', $DATE_FIN).':00';# On rajoute les secondes.
		if(!$oCL_date->fx_verif_date($DATE_FIN, 'fr', true)){
			$nb_erreur++;
			$_SESSION['modifier_contrat']['message'].= "<span class='alert'>La date de fin est invalide.</span><br />";
			unset($_SESSION['modifier_contrat']['DATE_FIN']);
		}
		
		# On vire l'éventuelle virgule du prix.
		$PRIX = str_replace(',', '.', $PRIX);
		
		# On vérifie que la date de début soit supérieure à la date de fin.
		if($oCL_date->fx_ajouter_date($DATE_DEBUT, true, true, 'fr') >= $oCL_date->fx_ajouter_date($DATE_FIN, true, true, 'fr')){
			# Date de début supérieure à la date de fin.
			$_SESSION['modifier_contrat']['message'].= "<span class='orange'>La date de début est supérieure à la date de fin.</span><br />";
			$nb_erreur++;
		}
		
		if($nb_erreur == 0){
			# On met en forme les dates (EN).
			$DATE_DEBUT = $oCL_date->fx_ajouter_date($DATE_DEBUT, true, false, 'fr', 'en');
			$DATE_FIN = $oCL_date->fx_ajouter_date($DATE_FIN, true, false, 'fr', 'en');
		
			# On récupère le destinataire. (On récupère la personne pour le contrat en cours qui n'est pas la personne en cours)
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			
			$destinataire = $oPCS_contrat->fx_recuperer_destinataire_by_ID_CONTRAT_et_nonID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			$oMSG->setData('ID_PERSONNE', $destinataire[0]['ID_PERSONNE']);
			
			$destinataire = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

			# On récupère l'expéditeur.
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			
			$expediteur = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			# On met à jour le contrat.
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
			$oMSG->setData('STATUT_CONTRAT', $STATUT_CONTRAT);
			$oMSG->setData('DESCRIPTION', $DESCRIPTION);
			$oMSG->setData('DATE_DEBUT', $DATE_DEBUT);
			$oMSG->setData('DATE_FIN', $DATE_FIN);
			$oMSG->setData('PRIX', $PRIX);
			$oMSG->setData('DESTINATAIRE', $destinataire[0]['ID_PERSONNE']);

			$oPCS_contrat->fx_maj_contrat($oMSG);
			
			# On envoi un mail au destinataire.
			$additional_headers = "From: noreply@liveanim.fr \r\n";
			$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
			$destinataires = $destinataire[0]['EMAIL'];
			$sujet = utf8_decode("LiveAnim [Modification du contrat N°".$ID_CONTRAT."]");
			
			$message = "------------------------------\n";
			$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
			$message.= "------------------------------\n\n";
			$message.= utf8_decode("Bonjour ".$destinataire[0]['PSEUDO'].", \n");
			$message.= utf8_decode("Le contrat N°".$ID_CONTRAT." a été modifié. \n\n");
			$message.= utf8_decode("Votre dernière proposition a été rejetée, vous pouvez prendre connaissance des modifications à ".$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT."\n");
			$message.= utf8_decode("Voici le commentaire qu'à mis votre interlocuteur: \n\n");
			$message.= utf8_decode($DESCRIPTION." \n\n\n");

			$message.= utf8_decode("------------------------------\n\n\n");
			$message.= utf8_decode("LiveAnim vous remercie de votre confiance et vous rappelle que vous pouvez nous contacter à support@liveanim.com !\n\n");
			$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
			
			mail($destinataires, $sujet, $message, $additional_headers);
			
			# On envoi un message au destinataire.
			$TITRE = "<span class='orange'>Refus de votre proposition</span> [Contrat N°".$ID_CONTRAT."]";
			$CONTENU = "La demande de contrat que vous avez fait à <span class='rose'>".$expediteur[0]['PSEUDO']."</span> vient d'être refusée.<br /><br />".
						$expediteur[0]['PSEUDO']." a modifié certains paramètres du contrat et vous l'a retourné.<br />".
						"Vous pouvez consulter les modifications dans votre historique des contrats ou en cliquant sur ce lien: ".
						"<a href='".$oCL_page->getPage('contrat')."?id_contrat=".$ID_CONTRAT."'>Voir le contrat</a><br /><br />".
						"<b>L'équipe LiveAnim.</b>";
						
			$oMSG->setData('TITRE', $TITRE);
			$oMSG->setData('CONTENU', $CONTENU);
			$oMSG->setData('DATE_ENVOI', date('Y-m-d H:i:s'));
			$oMSG->setData('EXPEDITEUR', $expediteur[0]['ID_PERSONNE']);
			$oMSG->setData('DESTINATAIRE', $destinataire[0]['ID_PERSONNE']);
			$oMSG->setData('TYPE_MESSAGE', 'Contrat');
			$oMSG->setData('VISIBLE', 1);
			
			$ID_MESSAGE = $oPCS_message->fx_creer_message($oMSG)->getData(1);
			
			$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
			$oMSG->setData('ID_PERSONNE', $destinataire[0]['ID_PERSONNE']);
			$oMSG->setData('STATUT_MESSAGE', 'Non lu');
			
			$oPCS_message->fx_lier_message($oMSG);			
			
			$_SESSION['modifier_contrat'] = Array();
			$_SESSION['contrat']['message_affiche'] = false;
			$_SESSION['contrat']['message'].= "<span class='valide'>Le contrat a été modifié, ".$destinataire[0]['PSEUDO']." a été prévenu des modifications.</span><br />";
			header('Location: '.$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT);
		}else{
			# Une erreur a eue lieu, au moins.
			$_SESSION['modifier_contrat']['message'].= "<span class='valide'>Le contrat n'a pas été modifié.</span><br />";
			header('Location: '.$oCL_page->getPage('modifier_contrat', 'absolu')."?id_contrat=".$ID_CONTRAT);
		}
		
	}else{
		# Pas de POST.
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>