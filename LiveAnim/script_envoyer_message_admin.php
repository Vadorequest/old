<?php
if(!isset($_SESSION)){
	session_start();
}
error_reporting(-1);
# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	if(isset($_POST['form_envoyer_message_admin_destinataires'])){
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_personne.php');
		require_once('couche_metier/PCS_message.php');
		
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		$oPCS_message = new PCS_message();
		
		# On définit nos variables.
		$nb_erreur = 0;
		
		$_SESSION['envoyer_message_admin']['message_affiche'] = false;
		$_SESSION['envoyer_message_admin']['message'] = "";
		
		# On récupère les champs.
		$destinataires = $_POST['form_envoyer_message_admin_destinataires'];
		$DESTINATAIRE[0]['ID_PERSONNE'] = $_POST['form_envoyer_message_admin_destinataire'];# On le met dans un tableau pour faire un futur foreach.
		$TITRE = trim($_POST['form_envoyer_message_admin_titre']);
		$MESSAGE = nl2br(trim($_POST['form_envoyer_message_admin_message']));
		
		# On les stocke en session.
		$_SESSION['envoyer_message_admin']['destinataires'] = $destinataires;
		$_SESSION['envoyer_message_admin']['DESTINATAIRE'] = $DESTINATAIRE[0]['ID_PERSONNE'];
		$_SESSION['envoyer_message_admin']['TITRE'] = $TITRE;
		$_SESSION['envoyer_message_admin']['MESSAGE'] = $MESSAGE;
		
		# On vérifie l'intégrité des données.
		if(empty($destinataires) || empty($TITRE) || empty($MESSAGE)){
			$nb_erreur++;
			$_SESSION['envoyer_message_admin']['message'].= "<span class='alert'>Un des champs obligatoire est vide.</span><br />";
		}
		
		switch($destinataires){
			case 1:
				# Un membre en particulier
				# On vérifie que l'ID transmis est correct.
				if((int)$DESTINATAIRE[0]['ID_PERSONNE'] == 0){
					$nb_erreur++;
					$_SESSION['envoyer_message_admin']['message'].= "<span class='alert'>Vous n'avez pas sélectionné de destinataire valide.</span><br />";
				}else{
					# On récupère l'email de la personne.
					$oMSG->setData('ID_PERSONNE', $DESTINATAIRE[0]['ID_PERSONNE']);
					
					$DESTINATAIRE = $oPCS_personne->fx_recuperer_compte_min_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				}
				break;
				
			case 2:
				# Tous les organisateurs

				$oMSG->setData('TYPE_PERSONNE', 'Organisateur');
				$oMSG->setData('VISIBLE', 1);
				
				$DESTINATAIRE = $oPCS_personne->fx_recuperer_toutes_identites_personnes_by_TYPE_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				break;
				
			case 3:
				# Tous les prestataires

				$oMSG->setData('TYPE_PERSONNE', 'Prestataire');
				$oMSG->setData('VISIBLE', 1);
				
				$DESTINATAIRE = $oPCS_personne->fx_recuperer_toutes_identites_personnes_by_TYPE_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				break;
				
			case 4:
				# Tous les admins
				# On récupère tous les organisateurs de la BDD.
				$oMSG->setData('TYPE_PERSONNE', 'Admin');
				$oMSG->setData('VISIBLE', 1);
				
				$DESTINATAIRE = $oPCS_personne->fx_recuperer_toutes_identites_personnes_by_TYPE_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				break;
		}# Fin Switch.
		
		# S'il n'y a pas d'erreur.
		if($nb_erreur == 0){
			
			if($_POST['form_envoyer_message_admin_type_message'] == "mail"){
				# Si le message doit être envoyé par email:
				$additional_headers = "From: contact@liveanim.fr \r\n";
				$additional_headers.= "MIME-Version: 1.0 \n";
				$additional_headers.= "Content-Type: text/html; charset=\"ISO-8859-1\" \n";
				$additional_headers.= "<html><head><title>".$TITRE."</title><meta http-equiv='Content-Type' Content='text/html; charset=iso-8859-1'></head> \n";
				$sujet = utf8_decode($TITRE);
				$message = "<body>";
				$message.= utf8_decode($MESSAGE);
				$message.= "</body></html>";
				
			
				# Pour chaque destinataire on envoit le message.
				foreach($DESTINATAIRE as $key => $destinataire){
					$destinataires = $destinataire['EMAIL'];

					if(mail($destinataires, $sujet, $message, $additional_headers)){
						$mail_envoye = true;
					}else{
						$mail_envoye = false;
						break;
					}
				}
				
				if($mail_envoye){
					$_SESSION['envoyer_message_admin']['message'].= "<span class='rose'>Le mail a bien été envoyé.</span><br />";
				}else{
					$_SESSION['envoyer_message_admin']['message'].= "<span class='alert'>Le mail n'a pas pu être envoyé.</span><br />";
				}
			}else if($_POST['form_envoyer_message_admin_type_message'] == "mp"){
				# Sinon si il doit être envoyé par MP via le site:
				# On crée le message.
				$oMSG->setData('TITRE', $TITRE);
				$oMSG->setData('CONTENU', $MESSAGE);
				$oMSG->setData('DATE_ENVOI', date('Y-m-d H:i:s'));
				$oMSG->setData('EXPEDITEUR', 0);
				$oMSG->setData('DESTINATAIRE', 0);
				$oMSG->setData('TYPE_MESSAGE', 'Message général');
				$oMSG->setData('VISIBLE', 1);
				
				$ID_MESSAGE = $oPCS_message->fx_creer_message($oMSG)->getData(1);
				
				$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
				$oMSG->setData('STATUT_MESSAGE', 'Non lu');
				
				# Pour chaque destinataire on envoit le message.
				foreach($DESTINATAIRE as $key => $destinataire){
					$oMSG->setData('ID_PERSONNE', $destinataire['ID_PERSONNE']);
				
					$oPCS_message->fx_lier_message($oMSG)->getData(1);
				}
				
				
			
				# On valide et on redirige.
				$_SESSION['envoyer_message_admin']['message'].= "<span class='rose'>Le message a bien été envoyé.</span><br />";
			}
			
			header('Location: '.$oCL_page->getPage('envoyer_message_admin', 'absolu'));
			
		}else{
			header('Location: '.$oCL_page->getPage('envoyer_message_admin', 'absolu'));
		}
	}else{
		header('Location: '.$oCL_page->getPage('envoyer_message_admin', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>