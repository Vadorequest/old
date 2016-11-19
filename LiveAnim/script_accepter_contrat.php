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
	if(isset($_GET['id_contrat'])){
		$ID_CONTRAT = (int)$_GET['id_contrat'];
		if($ID_CONTRAT > 0){
			# On vérifie que la personne ai bien le droit de valider ce contrat donc soit le destinataire actuel du contrat.
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_contrat.php');
			require_once('couche_metier/PCS_personne.php');
			
			$oMSG = new MSG();
			$oPCS_contrat = new PCS_contrat();
			$oPCS_personne = new PCS_personne();
			
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
			$oMSG->setData('DESTINATAIRE', $_SESSION['compte']['ID_PERSONNE']);
			
			$nb_contrat = $oPCS_contrat->fx_compter_ID_CONTRAT_by_ID_CONTRAT_et_DESTINATAIRE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

			if($nb_contrat[0]['nb_contrat'] == 1){
				# La personne est bien le destinataire actuel de ce contrat.
				
				
				# On valide le contrat.
				$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
				$oMSG->setData('STATUT_CONTRAT', 'Validé');
				$oMSG->setData('URL_CONTRAT_PDF', '');# finalement je vais auto générer le contrat à chaque fois.
				
				$oPCS_contrat->fx_valider_contrat($oMSG);
				
				# Notre destinataire actuel prend le nom d'expéditeur, on le récupère.
				$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
				
				$expediteur = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				# On récupère le nouveau destinataire.
				$id_destinataire = $oPCS_contrat->fx_recuperer_destinataire_by_ID_CONTRAT_et_nonID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				$oMSG->setData('ID_PERSONNE', $id_destinataire[0]['ID_PERSONNE']);
				
				$destinataire = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				# On envoi l'email.
				$additional_headers = "From: noreply@liveanim.fr \r\n";
				$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
				$destinataires = $expediteur[0]['EMAIL'].', '.$destinataire[0]['EMAIL'];
				$sujet = utf8_decode("LiveAnim [Validation du contrat N°".$ID_CONTRAT." !]");
				
				$message = "------------------------------\n";
				$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
				$message.= "------------------------------\n\n";
				$message.= utf8_decode("Bonjour, \n");
				$message.= utf8_decode("Nous vous informons que le contrat ".$ID_CONTRAT." a été validé par ".$expediteur[0]['PSEUDO'].". \n\n");
				$message.= utf8_decode("Il n'est donc plus modifiable. Vous pouvez le consulter à cette adresse: ".$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT."\n\n");
				$message.= utf8_decode("Si vous avez un empèchement quelconque vous obligant à annuler ce contrat, vous pouvez l'annuler à cette adresse: ".$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT." \n");
				$message.= utf8_decode("Veuillez dans ce cas prévenir -par respect- votre interlocuteur: \n");
				$message.= utf8_decode("Email de ".$expediteur[0]['PSEUDO'].": ".$expediteur[0]['EMAIL']." \n");
				$message.= utf8_decode("Email de ".$destinataire[0]['PSEUDO'].": ".$destinataire[0]['EMAIL']." \n\n\n");
				$message.= utf8_decode("------------------------------\n\n\n");
				$message.= utf8_decode("LiveAnim vous remercie de votre confiance.\n\n");
				$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
				
				mail($destinataires, $sujet, $message, $additional_headers);
				
				$_SESSION['contrat']['message_affiche'] = false;
				$_SESSION['contrat']['message'] = "<span class='valide'>Le contrat a été validé, un mail vous a été envoyé.</span>";
				
				header('Location: '.$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT);
			}else{
				# La personne n'a pas le droit de valider ce contrat.
				$_SESSION['historique_contrat']['message_affiche'] = false;
				$_SESSION['historique_contrat']['message'] = "<span class='alert'>Vous n'avez pas les droits suffisants pour valider ce contrat.</span>";
				
				header('Location: '.$oCL_page->getPage('historique_contrat', 'absolu'));
			}
		}else{
			# ID_CONTRAT invalide.
			$_SESSION['historique_contrat']['message_affiche'] = false;
			$_SESSION['historique_contrat']['message'] = "<span class='alert'>Le contrat que vous essayez de valider n'existe pas.</span>";
			
			header('Location: '.$oCL_page->getPage('historique_contrat', 'absolu'));
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