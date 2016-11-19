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
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_contrat.php');
			require_once('couche_metier/PCS_personne.php');
			require_once('couche_metier/PCS_message.php');
			
			$oMSG = new MSG();
			$oPCS_contrat = new PCS_contrat();
			$oPCS_personne = new PCS_personne();
			$oPCS_message = new PCS_message();
			
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			
			$nb_contrat = $oPCS_contrat->fx_compter_contrat_by_ID_CONTRAT_et_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			if($nb_contrat[0]['nb_contrat'] == 1){
				# On récupère le contrat.
				$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
				
				$contrat = $oPCS_contrat->fx_recuperer_contrat_by_ID_CONTRAT($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				if($contrat[0]['STATUT_CONTRAT'] == "Validé"){
					# L'utilisateur annule un contrat déjà validé, on passe le statut à 'Annulé après validation'
					$oMSG->setData('STATUT_CONTRAT', 'Annulé après validation');
				}else if($contrat[0]['STATUT_CONTRAT'] == "En attente"){
					# Le contrat a été annulé alors qu'il avait pas été accepté auparavant, rien de spécial.
					$oMSG->setData('STATUT_CONTRAT', 'Annulé');
				}else{
					# Contrat n'est ni Validé ni en attente, soit il est refusé soit il est Annulé après validation, on arrête tout.
					$_SESSION['contrat']['message_affiche'] = false;
					$_SESSION['contrat']['message'] = "<span class='alert'>Ce contrat a déjà été traité, il est ".$contrat[0]['STATUT_CONTRAT'].".</span><br /><br />";
					header('Location: '.$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT);
					die();
				}
				$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
				
				$oPCS_contrat->fx_maj_STATUT_by_ID_CONTRAT($oMSG);
				
				# On récupère l'expediteur.
				$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
				
				$expediteur = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				# On récupère le destinataire.
				$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
				$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
				
				$destinataire = $oPCS_contrat->fx_recuperer_destinataire_by_ID_CONTRAT_et_nonID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				$oMSG->setData('ID_PERSONNE', $destinataire[0]['ID_PERSONNE']);
				
				$destinataire = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				# On envoi un email.
				$additional_headers = "From: noreply@liveanim.fr \r\n";
				$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
				$destinataires = $expediteur[0]['EMAIL'].', '.$destinataire[0]['EMAIL'];
				$sujet = utf8_decode("LiveAnim [Annulation du contrat N°".$ID_CONTRAT."]");
				
				$message = "------------------------------\n";
				$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
				$message.= "------------------------------\n\n";
				$message.= utf8_decode("Bonjour, \n");
				$message.= utf8_decode("Nous vous informons que le contrat ".$ID_CONTRAT." a été annulé définitivement par ".$expediteur[0]['PSEUDO'].". \n\n");
				$message.= utf8_decode("Il n'est donc plus modifiable. Vous pouvez le consulter à cette adresse: ".$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT."\n\n");
				$message.= utf8_decode("------------------------------\n\n\n");
				$message.= utf8_decode("LiveAnim vous remercie de votre confiance.\n\n");
				$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
				
				mail($destinataires, $sujet, $message, $additional_headers);
				
				
				# On envoi un message à tous les prestataire qui ont leur pack actuel qui accepte ALERTE_NON_DISPONIBILITE et dont le département de l'annonce figure dans NB_DEPARTEMENT_ALERTE
				# On récupère d'abord le département de l'annonce.
				$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
				
				$departement = $oPCS_contrat->fx_recuperer_departement_annonce_lors_annulation_contrat($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				

				# On récupère ensuite tous les prestataires dont le pack actuel valide l'alerte et dont le département de l'annonce figure dans la liste des départements.
				$oMSG->setData('ID_DEPARTEMENT', $departement[0]['ID_DEPARTEMENT']);
				
				$prestataires = $oPCS_personne->fx_recuperer_prestataires_lors_annulation_contrat($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

				# On vérifie qu'on ai bien eu des résultats.
				if(!empty($prestataires[0]['ID_PERSONNE'])){
					# On défini ici ce qui n'est pas variable. (Moins de ressources utilisées si c'est pas refait inutilement en boucle !)
					$oMSG->setData('TITRE', "<span class='alert'>[Alerte]</span><br /><span class='orange'>Annulation d'un contrat dans votre département !</span>");
					$oMSG->setData('DATE_ENVOI', date('Y-m-d H:i:s'));
					$oMSG->setData('EXPEDITEUR', 0);// Correspond à LiveAnim.
					$oMSG->setData('TYPE_MESSAGE', 'Alerte annulation');
					$oMSG->setData('VISIBLE', 1);
					$oMSG->setData('STATUT_MESSAGE', 'Non lu');
					
					# Pour chaque prestataire récupéré on envoi un message.
					foreach($prestataires as $key=>$prestataire){
						# Si le prestataire en cours est différent du prestataire ou de l'organisateur qui a annulé le contrat alors on continue.
						if($prestataire['ID_PERSONNE'] != $expediteur[0]['ID_PERSONNE'] && $prestataire['ID_PERSONNE'] != $destinataire[0]['ID_PERSONNE']){
							# On prépare les variables du message.
							if($expediteur[0]['TYPE_PERSONNE'] != "Prestataire"){
								$expediteur[0]['TYPE_PERSONNE_modifié'] = "l'annonceur";
							}else{
								$expediteur[0]['TYPE_PERSONNE_modifié'] = "le prestataire";
							}
						
							# On envoi un message.
							$contenu=	"Bonjour ".$prestataire['PSEUDO'].", <br />".
										"<br />".
										"Vous recevez ce message automatique car un contrat vient d'être annulé dans l'un des départements que vous surveillez.<br />".
										"Le statut du contrat qui vient d'être annulé était <b class='rose'>".$contrat[0]['STATUT_CONTRAT']."</b> avant son annulation.<br />";
							if($expediteur[0]['TYPE_PERSONNE'] == "Prestataire"){
								$contenu.=	"C'est <a href='".$oCL_page->getPage('personne')."?id_personne=".$_SESSION['compte']['ID_PERSONNE']."'>".$expediteur[0]['TYPE_PERSONNE_modifié']."</a> qui a annulé le contrat.<br />";
							}else{
								$contenu.=	"C'est ".$expediteur[0]['TYPE_PERSONNE_modifié']." qui a annulé le contrat.<br />";
							}
							$contenu.=	"Vous pouvez visualiser directement l'annonce: <a href='".$oCL_page->getPage('annonce', 'absolu')."?id_annonce=".$contrat[0]['ID_ANNONCE']."'>Voir l'annonce</a><br />".
										"<br />".
										"En espérant que cette information vous sera utile.<br />".
										"<b class='rose'>L'équipe LiveAnim</b>.";
										
							$oMSG->setData('CONTENU', $contenu);
							
							
							$oMSG->setData('DESTINATAIRE', $prestataire['ID_PERSONNE']);					

							$ID_MESSAGE = $oPCS_message->fx_creer_message($oMSG)->getData(1);
							
							# On lie le message à la personne
							$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);		
							$oMSG->setData('ID_PERSONNE', $prestataire['ID_PERSONNE']);		
							
							$oPCS_message->fx_lier_message($oMSG);
						}# Fin du if	
					}# Fin du foreach($prestataires as $key=>$prestataire)
				}# Fin du if(!empty($prestataires[0]['ID_PERSONNE']))
						
				$_SESSION['contrat']['message_affiche'] = false;
				$_SESSION['contrat']['message'] = "<span class='valide'>Le contrat a bien été annulé. Un email a été envoyé à vous et votre correspondant.</span><br /><br />";
				header('Location: '.$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT);
				
			}else{
				$_SESSION['historique_contrat']['message_affiche'] = false;
				$_SESSION['historique_contrat']['message'] = "<span class='alert'>Vous n'avez pas les droits nécessaires pour annuler ce contrat.</span><br /><br />";
				header('Location: '.$oCL_page->getPage('historique_contrat', 'absolu'));
			}
		}else{
			# Valeur de id_contrat incorrecte
			$_SESSION['historique_contrat']['message_affiche'] = false;
			$_SESSION['historique_contrat']['message'] = "<span class='alert'>Le contrat que vous essayez d'annuler n'existe pas.</span><br /><br />";
			header('Location: '.$oCL_page->getPage('historique_contrat', 'absolu'));
		}
	}else{
		# Pas de GET
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>