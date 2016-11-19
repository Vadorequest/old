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
	if(isset($_POST['form_noter_prestataire_id_contrat'])){
		$ID_CONTRAT = (int)$_POST['form_noter_prestataire_id_contrat'];
		if($ID_CONTRAT > 0){
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_contrat.php');
			require_once('couche_metier/PCS_personne.php');
			require_once('couche_metier/PCS_evaluation.php');
			require_once('couche_metier/PCS_types.php');
			require_once('couche_metier/PCS_message.php');

			$oMSG = new MSG();
			$oPCS_contrat = new PCS_contrat();
			$oPCS_personne = new PCS_personne();
			$oPCS_evaluation = new PCS_evaluation();
			$oPCS_message = new PCS_message();
			$oPCS_types = new PCS_types();
				
			# On vérifie que la personne ait les droits sur ce contrat.
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			
			$organisateur = $oPCS_contrat->fx_compter_contrat_by_ID_CONTRAT_et_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin" || ($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur" && $organisateur[0]['nb_contrat'] > 0)){

				# On récupère les types.
				$oMSG->setData('ID_FAMILLE_TYPES', 'Caractéristiques');
				
				$types = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
				$notes = Array();
				
				# Pour chaque type.
				foreach($types as $key=>$type){
					for($j = 0;$j < count($types); $j++){
						# On teste l'existence de la variable.
						if(isset($_POST[$type['ID_TYPES'].$j])){
							# On la sauvegarde dans le tableau des notes avec $notes['nom_du_type']['note_attribuée'];
							$notes[$type['ID_TYPES']] =  $_POST[$type['ID_TYPES'].$j];
							break;
						}else{
							# Si le type n'est pas présent dans les données fournies.
							$notes[$type['ID_TYPES']] = 0;
						}
					}
				}
				
				# Pour chaque case on effectue une requête de type INSERT ou UPDATE selon si l'information existe déjà.
				foreach($notes as $key=>$note){
					# On vérifie que la note soit correcte.
					if($notes[$key] < 0){
						$notes[$key] = 0;
					}else if($notes[$key] > 5){
						$notes[$key] = 5;
					}
					
					$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
					$oMSG->setData('TYPE_EVALUATION', $key);
					
					# On regarde si la note existe déjà.
					$nb_note_BDD = $oPCS_evaluation->fx_compter_evaluation_by_ID_CONTRAT_et_TYPE_EVALUATION($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
					
					if($nb_note_BDD[0]['nb_evaluation'] > 0){
						# Si la note existe déjà alors on la modifie.
						$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
						$oMSG->setData('EVALUATION', $notes[$key]);
						$oMSG->setData('COMMENTAIRE', "");
						$oMSG->setData('TYPE_EVALUATION', $key);
						
						$oPCS_evaluation->fx_maj_evaluation($oMSG);
						
					}else{
						# Si la note n'existe pas alors on la crée.
						$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
						$oMSG->setData('EVALUATION', $notes[$key]);
						$oMSG->setData('COMMENTAIRE', "");
						$oMSG->setData('TYPE_EVALUATION', $key);
						
						$oPCS_evaluation->fx_creer_evaluation($oMSG);
						
					}
				}// Fin du foreach
				
									
				# On modifie la date de notation.
				$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
				$oMSG->setData('DATE_EVALUATION', date('Y-m-d H:i:s'));
				
				$oPCS_contrat->fx_maj_DATE_EVALUATION_by_ID_CONTRAT($oMSG);
				
				# Si on a pas déjà eu une notation de la part de cette personne alors on prévient le prestataire (antispam).
				if(!isset($_SESSION['compte']['notation_prestataire'])){
					# On récupère le prestataire du contrat et on lui envoi un mail et un message pour l'avertir qu'il a été noté.
					$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
					$oMSG->setData('TYPE_PERSONNE', 'Organisateur');# Comme ça si un membre a un contrat avec un admin... peu probable mais bon.
					
					$prestataire = $oPCS_personne->fx_recuperer_personne_by_ID_CONTRAT_et_nonTYPE_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
					
					# On récupère l'organisateur du contrat.
					$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
					$oMSG->setData('TYPE_PERSONNE', 'Organisateur');
					
					$organisateur = $oPCS_personne->fx_recuperer_personne_by_ID_CONTRAT_et_TYPE_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
					
					# On envoi l'email:		
					$additional_headers = "From: noreply@liveanim.fr \r\n";
					$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
					$destinataires = $prestataire[0]['EMAIL'];# On est sensé n'avoir qu'un seul résultat.
					$sujet = utf8_decode("LiveAnim [Notation de votre prestation]");
					
					$message = "------------------------------\n";
					$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
					$message.= "------------------------------\n\n";
					$message.= utf8_decode("Bonjour ".$prestataire[0]['PSEUDO'].", \n");
					$message.= utf8_decode("Votre prestation concernant le contrat N°".$ID_CONTRAT." a été notée. \n\n");
					$message.= utf8_decode("Vous pouvez visualiser vos notes à cette adresse: ".$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT."\n");
					$message.= utf8_decode("Votre notation a été effectuée par ".$_SESSION['compte']['PSEUDO']." le ".date('d/m/Y H:i:s').". \n\n");
					$message.= utf8_decode("------------------------------\n\n\n");
					$message.= utf8_decode("LiveAnim vous remercie de votre confiance et espère que vos prestations se déroulent pour le mieux !\n\n");
					$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
					
					mail($destinataires, $sujet, $message, $additional_headers);
					
					# On envoi le message:
					$TITRE = "Vous avez été noté par ".$_SESSION['compte']['PSEUDO'];
					$CONTENU = "Bonjour ".$_SESSION['compte']['PSEUDO'].",<br /><br />";
					$CONTENU.= "Nous vous informons que vous avez été noté pour votre prestation avec l'organisateur ".$organisateur[0]['PSEUDO'].".<br />";
					$CONTENU.= "Voici vos notes:<br /><br />";
					foreach($notes as $key=>$note){
						$CONTENU.= "<b class='rose'>".$key."</b>: <b>".$note."</b>/5<br />";
					}
					$CONTENU.= "<br /><br />";
					$CONTENU.= "Vous pouvez consulter le contrat correspondant <a href='".$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT."'>ici</a>.";
					$CONTENU.= "En espérant que vous vous soyez bien amusé,<br />";
					$CONTENU.= "<b class='rose'>L'équipe LiveAnim</b>";
					$EXPEDITEUR = $organisateur[0]['ID_PERSONNE'];
					$DESTINATAIRE = $prestataire[0]['ID_PERSONNE'];
					
					$oMSG->setData('TITRE', $TITRE);
					$oMSG->setData('CONTENU', $CONTENU);
					$oMSG->setData('EXPEDITEUR', $EXPEDITEUR);
					$oMSG->setData('DESTINATAIRE', $DESTINATAIRE);
					$oMSG->setData('TYPE_MESSAGE', "Notation");
					$oMSG->setData('DATE_ENVOI', date('Y-m-d H:i:s'));
					$oMSG->setData('VISIBLE', 1);
					
					$ID_MESSAGE = $oPCS_message->fx_creer_message($oMSG)->getData(1);
					
					# On lie le message.
					$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
					$oMSG->setData('ID_PERSONNE', $DESTINATAIRE);
					$oMSG->setData('STATUT_MESSAGE', 'Non lu');
					
					$oPCS_message->fx_lier_message($oMSG);
				}#Fin du if de test date_notation_prestataire
				
				$_SESSION['compte']['notation_prestataire'] = true;# On conserve le fait que cette session a effectuée une notation.
				
				$_SESSION['contrat']['message'] = "<span class='valide'>Votre notation a été prise en compte. Le prestataire a été prévenu.<br />".
												  "Vous pouvez modifier vos notes si vous le désirez.</span><br /><br />";
				$_SESSION['contrat']['message_affiche'] = false;
				header('Location: '.$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT);
				
			}else{
				# Droits insuffisants.
				$_SESSION['contrat']['message'] = "<span class='alert'>Vous n'avez pas les droits nécessaires pour noter ce prestataire.</span>";
				$_SESSION['contrat']['message_affiche'] = false;
				header('Location: '.$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT);
			}
		}else{
			# ID_CONTRAT invalide.
			header('Location: '.$oCL_page->getPage('historique_contrat', 'absolu'));
		}
	}else{
		# Pas de POST.
		header('Location: '.$oCL_page->getPage('historique_contrat', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>