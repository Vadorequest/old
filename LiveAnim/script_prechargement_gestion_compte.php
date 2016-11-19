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
error_reporting(-1);
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_pack.php');
	require_once('couche_metier/PCS_annonce.php');
	require_once('couche_metier/PCS_contrat.php');
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');

	$oMSG = new MSG();
	$oPCS_pack = new PCS_pack();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_contrat = new PCS_contrat();
	$oPCS_personne = new PCS_personne();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
				
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
		# On récupère la note moyenne de chaque caractéristique pour toutes les prestations.
		# On récupère tous les types d'évaluation.
		$oMSG->setData("ID_FAMILLE_TYPES", 'Caractéristiques');
		
		$types_evaluation = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
		// $oMSG->setData("ID_PERSONNE", $_SESSION['compte']['ID_PERSONNE']);
		$oMSG->setData("ID_PERSONNE", 23);

		$evaluations = Array();
		# Pour chaque type d'évaluation on effectue la requete de sélection des types.
		foreach($types_evaluation as $key=>$type_evaluation){
			$oMSG->setData("TYPE_EVALUATION", $type_evaluation['ID_TYPES']);
			$evaluation = $oPCS_contrat->fx_recuperer_moy_evaluation_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			$caracteristique = $type_evaluation['ID_TYPES'];
			$evaluations[$caracteristique]['evaluation'] = $evaluation[0]['moy_evaluation'];
			# On vérifie que le résultat ne soit pas null.
			if($evaluations[$caracteristique]['evaluation'] === null){
				$evaluations[$caracteristique]['erreur'] = "<span class='petit'>Vous n'avez jamais été noté sur cette caractéristique.</span>";
			}
		}
		
		if($_SESSION['pack']['SUIVI'] == true){
			# On récupère les gains totaux réalisés ainsi que les dépenses dans les packs et dans les annonces goldlive.
			# On commence par récupérer le prix des achats de tous les pacjs achetés
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			
			$cout_packs = $oPCS_pack->fx_calculer_couts_pack_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			# On récupère ensuite les gains qu'a effectué le prestataire.
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('criteres', 'AND contrat.DATE_FIN < NOW();');
			
			$gain_prestations_passees = $oPCS_contrat->fx_calculer_gains_contrats_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

			# On récupère ensuite les gains que va prochainement effectuer le prestataire mais qui ne sont pas surs à 100%.
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('criteres', 'AND contrat.DATE_FIN > NOW();');
			$gain_prestations_futures = $oPCS_contrat->fx_calculer_gains_contrats_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

		}
	}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur"){
		# On récupère le nombre de contrats en cours.
		
	}
	
	# On récupère la date de création du compte.
	$oMSG->setData("ID_PERSONNE", $_SESSION['compte']['ID_PERSONNE']);
	
	$date_creation_compte = $oPCS_personne->fx_recuperer_date_creation_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();

		# On met la date en FR.
		$date_creation_compte[0]['DATE_CONNEXION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($date_creation_compte[0]['DATE_CONNEXION'], true, false, 'en', 'fr'), true, 'fr');
	
	# On récupère les informations des dix dernières connexions.
	$oMSG->setData("ID_PERSONNE", $_SESSION['compte']['ID_PERSONNE']);
	$oMSG->setData("limit", "LIMIT 0,10");
	
	$dernieres_connexions = $oPCS_personne->fx_recuperer_dernieres_connexions_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	
		# On met les dates en FR.
		foreach($dernieres_connexions as $key=>$derniere_connexion){
			$dernieres_connexions[$key]['DATE_CONNEXION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($derniere_connexion['DATE_CONNEXION'], true, false, 'en', 'fr'), true, 'fr');
		}
				
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>