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
			require_once('couche_metier/CL_date.php');

			$oMSG = new MSG();
			$oPCS_annonce = new PCS_annonce();
			$oPCS_contrat = new PCS_contrat();
			$oPCS_personne = new PCS_personne();
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
				$contrat[0]['DATE_DEBUT'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_DEBUT_contrat'], true, false, 'en', 'fr'), true, 'fr', false, false);
				$contrat[0]['DATE_FIN'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat[0]['DATE_FIN_contrat'], true, false, 'en', 'fr'), true, 'fr', false, false);
				$contrat[0]['PRIX'] = $contrat[0]['PRIX_contrat'];
				$contrat[0]['DESCRIPTION'] = str_replace(Array('<br />', '<br>'), '', $contrat[0]['DESCRIPTION_contrat']);

				
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