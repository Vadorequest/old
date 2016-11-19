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
		if($ID_CONTRAT != 0){
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_annonce.php');
			require_once('couche_metier/PCS_contrat.php');
			require_once('couche_metier/PCS_personne.php');
			require_once('couche_metier/PCS_evaluation.php');
			require_once('couche_metier/PCS_types.php');
			require_once('couche_metier/CL_date.php');

			$oMSG = new MSG();
			$oPCS_annonce = new PCS_annonce();
			$oPCS_contrat = new PCS_contrat();
			$oPCS_personne = new PCS_personne();
			$oPCS_evaluation = new PCS_evaluation();
			$oPCS_types = new PCS_types();
			$oCL_date = new CL_date();
			
			# On vérifie que la personne ai le droit de consulter ce contrat.
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			
			$nb_contrat = $oPCS_contrat->fx_compter_contrat_by_ID_CONTRAT_et_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

			if($nb_contrat[0]['nb_contrat'] == 1){
				$id_contrat_ok = 1;
				
				# On récupère le contrat.
				$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
				
				$contrat = $oPCS_contrat->fx_recuperer_contrat_by_ID_CONTRAT($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				# On met en forme les données.
				$contrat[0]['DATE_CONTRAT'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_CONTRAT'], true, false, 'en', 'fr'), true, 'fr');
				$contrat[0]['DATE_EVALUATION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_EVALUATION'], true, false, 'en', 'fr'), true, 'fr');
				$contrat[0]['DATE_DEBUT_contrat'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_DEBUT_contrat'], true, false, 'en', 'fr'), true, 'fr');
				$contrat[0]['DATE_FIN_contrat'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_FIN_contrat'], true, false, 'en', 'fr'), true, 'fr');
				$contrat[0]['DATE_FIN_contrat_formatee'] = $oCL_date->fx_ajouter_date($contrat[0]['DATE_FIN_contrat'], true, true, 'fr', 'fr');
				$contrat[0]['DATE_DEBUT_annonce'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_DEBUT_annonce'], true, false, 'en', 'fr'), true, 'fr');
				$contrat[0]['DATE_FIN_annonce'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_FIN_annonce'], true, false, 'en', 'fr'), true, 'fr');
				$now_formatee = date('YmdHis');
				
				# On récupère les types.
				$oMSG->setData('ID_FAMILLE_TYPES', 'Caractéristiques');
				
				$types = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				# On récupère les notes.
				$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
				
				$evaluations = $oPCS_evaluation->fx_recuperer_evaluations_by_ID_CONTRAT($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				// Voir pourquoi aucun résultat.
								
			}else{
				# Le contrat n'appartient pas à la personne.
				$id_contrat_ok = 0;
				$_SESSION['contrat']['message_affiche'] = false;
				$_SESSION['contrat']['message'] = "<span class='alert'>Vous ne possédez pas le droit d'afficher ce contrat.</span><br />";
			}
		}
	}else{
		$id_contrat_ok = 0;
		$_SESSION['contrat']['message_affiche'] = false;
		$_SESSION['contrat']['message'] = "<span class='alert'>Le contrat que vous cherchez n'existe pas.</span><br />";
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>