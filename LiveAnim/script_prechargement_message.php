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
	# On initialise notre variable qui détermine l'affichage du message demandé.
	$id_message_ok = 0;
	if(isset($_GET['id_message'])){
		$ID_MESSAGE = (int)$_GET['id_message'];
		# On vérifie que l'identifiant de l'annonce soit valide.
		if($ID_MESSAGE > 0){
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_personne.php');
			require_once('couche_metier/PCS_message.php');
			require_once('couche_metier/PCS_types.php');
			require_once('couche_metier/CL_date.php');

			$oMSG = new MSG();
			$oPCS_personne = new PCS_personne();
			$oPCS_message = new PCS_message();
			$oPCS_types = new PCS_types();
			$oCL_date = new CL_date();
			
			# On récupère le message.
			$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);

			$message = $oPCS_message->fx_recuperer_message_by_ID_MESSAGE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			# On vérifie que le message existe bien, si ce n'est pas le cas soit il n'existe pas soit la personne n'y a pas accès.
			if(!empty($message[0]['ID_MESSAGE'])){
				$id_message_ok = 1;
				$maintenant_en = date("Y-m-d H:i:s");
				$maintenant_fr = date("d-m-Y H:i:s");
				
				# On met en forme les données.
				$message[0]['DATE_LECTURE'] = $oCL_date->fx_ajouter_date($message[0]['DATE_LECTURE'], true, false, 'en', 'fr');
				$message[0]['DATE_REPONSE'] = $oCL_date->fx_ajouter_date($message[0]['DATE_REPONSE'], true, false, 'en', 'fr');
				$message[0]['DATE_ENVOI'] = $oCL_date->fx_ajouter_date($message[0]['DATE_ENVOI'], true, false, 'en', 'fr');
				
				$message[0]['DATE_LECTURE'] = str_replace(' ', ' à ', $message[0]['DATE_LECTURE']);
				$message[0]['DATE_REPONSE'] = str_replace(' ', ' à ', $message[0]['DATE_REPONSE']);
				$message[0]['DATE_ENVOI'] = str_replace(' ', ' à ', $message[0]['DATE_ENVOI']);
				
				# On charge les informations de l'expéditeur.
				$oMSG->setData('ID_PERSONNE', $message[0]['EXPEDITEUR']);

				$expediteur = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
				//$expediteur[0]['PSEUDO'];

				# Si le message n'avait pas été lu et qu'il est de type contrat alors on envoi un email à l'expéditeur.
				if($message[0]['STATUT_MESSAGE'] == "Non lu" && $message[0]['TYPE_MESSAGE'] == "Contrat"){
					
					# On modifie la date de lecture:
					$message[0]['DATE_LECTURE'] = $maintenant_fr;
					$message[0]['DATE_LECTURE'] = str_replace(' ', ' à ', $message[0]['DATE_LECTURE']);
					
					# On envoi un email à l'expéditeur afin de lui dire que son annonce a été lue.
					$additional_headers = "From: noreply@liveanim.fr \r\n";
					$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
					$destinataires = $expediteur[0]['EMAIL'];
					$sujet = utf8_decode("LiveAnim [Lecture de votre demande de contrat]");
					
					$message_mail = "------------------------------\n";
					$message_mail.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
					$message_mail.= "------------------------------\n\n";
					$message_mail.= utf8_decode("Bonjour ".$expediteur[0]['PSEUDO'].", \n");
					$message_mail.= utf8_decode("Nous vous informons que votre demande de contrat à ".$_SESSION['compte']['PSEUDO']." vient d'être lue. \n\n");
					$message_mail.= utf8_decode("------------------------------\n\n\n");
					$message_mail.= utf8_decode("LiveAnim vous remercie de votre confiance !\n\n");
					$message_mail.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
					
					mail($destinataires, $sujet, $message_mail, $additional_headers);
					
					# On indique que le message a été lu et sa date de lecture.
					$oMSG->setData('DATE_LECTURE', $maintenant_en);
					$oMSG->setData('STATUT_MESSAGE', 'Lu');
					$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
					$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
					
					$oPCS_message->fx_message_lu($oMSG);
					
				}else if($message[0]['STATUT_MESSAGE'] == "Non lu"){
					# On modifie la date de lecture:
					$message[0]['DATE_LECTURE'] = $maintenant_fr;
					$message[0]['DATE_LECTURE'] = str_replace(' ', ' à ', $message[0]['DATE_LECTURE']);
					
					# On indique que le message a été lu et sa date de lecture.
					$oMSG->setData('DATE_LECTURE', $maintenant_en);
					$oMSG->setData('STATUT_MESSAGE', 'Lu');
					$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
					$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
					
					$oPCS_message->fx_message_lu($oMSG);
				}
				
			}else{
				$_SESSION['message']['message_affiche'] = false;
				$_SESSION['message']['message'] = "<span class='alert'>Vous ne possédez pas l'autorisation de consulter ce message.</span><br />";
			}
		}else{
			$_SESSION['message']['message_affiche'] = false;
			$_SESSION['message']['message'] = "<span class='alert'>Le message personnel que vous souhaitez lire n'existe pas. (2)</span><br />";
		}
	}else{
		$_SESSION['message']['message_affiche'] = false;
		$_SESSION['message']['message'] = "<span class='alert'>Le message personnel que vous souhaitez lire n'existe pas. (1)</span><br />";
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>