<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_menuv_gestion_compte.php');

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
			
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');		
		require_once('couche_metier/PCS_contrat.php');		
		require_once('couche_metier/PCS_message.php');		
		require_once('couche_metier/PCS_personne.php');		
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		$oPCS_contrat = new PCS_contrat();
		$oPCS_message = new PCS_message();
		$oPCS_personne = new PCS_personne();
		
		$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
		
		if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
			# Le nombre d'annonces totales.
			$toutes_annonces = $oPCS_annonce->fx_compter_toutes_annonces_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			# Le nombre d'annonces en cours.
			$annonces_futures = $oPCS_annonce->fx_compter_annonces_futures_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			# On compte le nombre d'annonces goldlive.
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('GOLDLIVE', 1);
		
			$annonces_goldlive = $oPCS_annonce->fx_compter_annonces_by_GOLDLIVE_et_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
		}
		if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
			# Le nombre total de prestations effectuées.
			$oMSG->setData('STATUT_CONTRAT', 'Validé');
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('where', 'AND contrat.DATE_FIN < NOW()');
			
			$prestations_effectues = $oPCS_contrat->fx_compter_prestations_effectues($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			# Le nombre de prestations prévues.
			$oMSG->setData('STATUT_CONTRAT', 'Validé');
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('where', 'AND contrat.DATE_FIN > NOW()');
			
			$prestations_prevues = $oPCS_contrat->fx_compter_prestations_effectues($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
		}
		# Le nombre total de contrats.
		$tous_contrats = $oPCS_contrat->fx_compter_contrat_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
		# Le nombre de contrats en cours.
		$contrats_courants = $oPCS_contrat->fx_compter_contrats_courants_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();

		# Le nombre de messages non lus.
		$oMSG->setData('STATUT_MESSAGE', 'Non lu');
		$oMSG->setData('VISIBLE', 1);
		
		$messages_non_lus = $oPCS_message->fx_compter_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG)->getData(1)->fetchAll();
		
		# Le nombre de messages totaux visibles.
		$oMSG->setData('STATUT_MESSAGE', 'Supprimé');# Tous les messages qui n'auront pas de statut supprimé seront sélectionnés
		$oMSG->setData('VISIBLE', 1);
		$messages_totaux = $oPCS_message->fx_compter_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG)->getData(1)->fetchAll();
		
		# Le nombre de filleuls.
		$filleuls_totaux = $oPCS_personne->fx_compter_filleuls_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
		
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>