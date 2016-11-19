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
			require_once('couche_metier/PCS_evaluation.php');
			require_once('couche_metier/CL_date.php');
			
			$oMSG = new MSG();
			$oPCS_contrat = new PCS_contrat();
			$oPCS_personne = new PCS_personne();
			$oPCS_evaluation = new PCS_evaluation();
			$oCL_date = new CL_date();
			
			
			if($_SESSION['compte']['TYPE_PERSONNE'] != "Admin"){
				# On vérifie que la personne ait le droit de regard sur ce contrat.
				$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
				$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
				
				$nb_contrat = $oPCS_contrat->fx_compter_contrat_by_ID_CONTRAT_et_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				if($nb_contrat[0]['nb_contrat'] != 1){
				
					$_SESSION['historique_contrat']['message_affiche'] = false;
					$_SESSION['historique_contrat']['message'] = "<span class='alert'>Vous n'avez pas les droits nécessaires pour visionner ce contrat.</span><br />";
					header('Location: '.$oCL_page->getPage('historique_contrat', 'absolu'));
					die();
				}
				if(!$_SESSION['pack']['CONTRATS_PDF']){
					$_SESSION['contrat']['message_affiche'] = false;
					$_SESSION['contrat']['message'] = "<span class='alert'>Vous n'avez pas les droits nécessaires pour visionner ce contrat.</span><br />";
					header('Location: '.$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT);
					die();
				}
			}
			# La personne a le droit d'afficher le contrat, on le récupère.
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
			
			$contrat = $oPCS_contrat->fx_recuperer_contrat_by_ID_CONTRAT($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			# Si le contrat est validé.
			if($contrat[0]['STATUT_CONTRAT'] == 'Validé' || $contrat[0]['STATUT_CONTRAT'] == 'Annulé après validation'){

				# On met en forme les données.
				$contrat[0]['DESCRIPTION_annonce'] = utf8_decode($contrat[0]['DESCRIPTION_annonce']);
				$contrat[0]['DATE_CONTRAT'] = utf8_decode($oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_CONTRAT'], true, false, 'en', 'fr'), true, 'fr'));
				$contrat[0]['DATE_EVALUATION'] = ($oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_EVALUATION'], true, false, 'en', 'fr'), true, 'fr'));
				$contrat[0]['DATE_EVALUATION_formatee'] = utf8_decode($oCL_date->fx_ajouter_date($contrat[0]['DATE_EVALUATION'], true, true, 'fr', 'fr'));
				$contrat[0]['DATE_DEBUT_contrat'] = utf8_decode($oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_DEBUT_contrat'], true, false, 'en', 'fr'), true, 'fr'));
				$contrat[0]['DATE_FIN_contrat'] = utf8_decode($oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_FIN_contrat'], true, false, 'en', 'fr'), true, 'fr'));
				$contrat[0]['DATE_DEBUT_annonce'] = utf8_decode($oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_DEBUT_annonce'], true, false, 'en', 'fr'), true, 'fr'));
				$contrat[0]['DATE_FIN_annonce'] = utf8_decode($oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_FIN_annonce'], true, false, 'en', 'fr'), true, 'fr'));
				$contrat[0]['STATUT_CONTRAT'] = utf8_decode($contrat[0]['STATUT_CONTRAT']);
				$contrat[0]['PRIX_contrat'] = utf8_decode($contrat[0]['PRIX_contrat']." euros (HT)");
				$contrat[0]['PRIX_annonce'] = utf8_decode($contrat[0]['PRIX_annonce']." euros (HT)");
				$contrat[0]['TYPE_ANNONCE'] = utf8_decode($contrat[0]['TYPE_ANNONCE']);
				$contrat[0]['TITRE'] = utf8_decode($contrat[0]['TITRE']);
				$now = date('m-d-Y H:i:s');
				$now = utf8_decode($oCL_date->fx_formatter_heure($now, true, 'fr'));
				
				# On récupère la personne ayant validée le contrat.
				$oMSG->setData('ID_PERSONNE', $contrat[0]['DESTINATAIRE']);# Le destinataire est la dernière personne à avoir pu choisir de valider le contrat.
				
				$valideur = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
				
				$contrat[0]['DESCRIPTION_annonce'] = str_replace(Array('<br />', '<br>'), '\n', $contrat[0]['DESCRIPTION_annonce']);
				
				# On récupère les notes du prestataire pour ce contrat.
				$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
				
				$notes = $oPCS_evaluation->fx_recuperer_evaluations_by_ID_CONTRAT($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				# On le génère.
				require('ressources/pdf/CL_PDF.php');
				
				// Instanciation de la classe CL_PDF
				$pdf = new CL_PDF();
				
				$pdf->AliasNbPages();
				$pdf->AddPage();# Ajout d'une page (obligatoire)
				$pdf->SetFont('Times','BU',15);# Ajout d'un font (obligatoire)
				$pdf->MultiCell(0, 5, $contrat[0]['TITRE'].' :', 0, 'C');
				$pdf->Ln(10);
				$pdf->SetFont('Times','B',15);
				$html = "Le statut de ce contrat est actuellement <i><u>".$contrat[0]['STATUT_CONTRAT']."</u></i>.";
				$pdf->WriteHTML($html);
				$pdf->SetFont('Times','',11);
				$pdf->Cell(40, 5, "    (Le ".$now.")");
				$pdf->Ln(10);
				$pdf->SetFont('Times','',15);
				$html = '<u><b>Description de l\'annonce :</b></u>';
				$pdf->WriteHTML($html);
				$pdf->Ln(10);
				$pdf->MultiCell(0, 5, $contrat[0]['DESCRIPTION_annonce']);
				$pdf->Ln(20);
				# Création du tableau.
				$header_table = array('', "Demande d'origine :", utf8_decode('Offre acceptée :'));
				$datas_table = Array(
						Array(utf8_decode('Date de début :'), "Le ".$contrat[0]['DATE_DEBUT_annonce'], "Le ".$contrat[0]['DATE_DEBUT_contrat']), 
						Array('Date de fin :', "Le ".$contrat[0]['DATE_FIN_annonce'], "Le ".$contrat[0]['DATE_FIN_contrat']), 
						Array('Prix :', $contrat[0]['PRIX_annonce'], $contrat[0]['PRIX_contrat']), 
								);
				$pdf->FancyTable($header_table,$datas_table);
				$pdf->Ln(10);
				if($contrat[0]['DATE_EVALUATION_formatee'] > '20110101010101'){# Si la date d'évalutation est supérieure au premier janvier 2011.
					$html = utf8_decode("<u><b>Date d'évaluation du prestataire :</b></u> Le ".$contrat[0]['DATE_EVALUATION'].".");
					$pdf->WriteHTML($html);
					$html = $pdf->MultiCell(0, 8, '');
					
					# Pour chaque note, on affiche la note.
					foreach($notes as $key=>$note){
						$html = utf8_decode("<b>".$note['TYPE_EVALUATION']."</b>: ");
						$pdf->WriteHTML($html);
						$pdf->SetTextColor(235,40,82);
						$html = utf8_decode("<b>".$note['EVALUATION']."</b>");
						$pdf->WriteHTML($html);
						$pdf->SetTextColor(0);
						$html = utf8_decode("/5. \n");
						$pdf->WriteHTML($html);
					}
				}else{
					$html = utf8_decode("<b>Le prestataire n'a pas encore été noté.</b>");
					$pdf->WriteHTML($html);
				}
				$pdf->Ln(10);
				$html = utf8_decode("Le contrat a été crée le <b>").$contrat[0]['DATE_CONTRAT'].utf8_decode("</b>. Il fut validé par ").utf8_decode($valideur[0]['PSEUDO']).".";
				$pdf->WriteHTML($html);
				$pdf->Ln(30);
				$pdf->SetFont('Times','',11);
				$html = $pdf->MultiCell(0, 5, utf8_decode("Ce contrat a été autotmatiquement généré par nos services, il est susceptible à des modifications ultérieures car il prend en compte toute modification futures du présent contrat."));
				$pdf->SetFont('Times','',9);
				$html.= $pdf->MultiCell(0, 5, '(Annulation, notations du prestataire, etc.)');
				$html.= $pdf->MultiCell(0, 5, '');
				$pdf->SetFont('Times','',11);
				$html.= $pdf->MultiCell(0, 5, utf8_decode("Si vous rencontrez des problèmes, des erreurs ou si vous avez des questions nous vous invitons à nous contacter à support@liveanim.com, merci."));
				$html.= $pdf->MultiCell(0, 5, utf8_decode("LiveAnim reste à votre entière disposition pour tout renseignement complémentaires."));
				$pdf->WriteHTML($html);
				$pdf->Output();
				
				
			}else{
				# Contrat non validé.
				$_SESSION['contrat']['message_affiche'] = false;
				$_SESSION['contrat']['message'] = "<span class='orange'>Ce contrat n'a pas encoré été validé, vous ne pouvez pas le visualiser.</span><br />";
				header('Location: '.$oCL_page->getPage('contrat', 'absolu')."?id_contrat=".$ID_CONTRAT);
			}
		}else{
			# ID_CONTRAT invalide.
			header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
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