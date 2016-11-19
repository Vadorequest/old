<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_personne.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	
	# On récupère tous les prestataires.
	$oMSG->setData('TYPE_PERSONNE', 'Prestataire');
	$oMSG->setData('VISIBLE', 1);
	
	$prestataires = $oPCS_personne->fx_recuperer_toutes_identites_personnes_by_TYPE_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On récupère tous les organisateurs.
	$oMSG->setData('TYPE_PERSONNE', 'Organisateur');
	
	$organisateurs = $oPCS_personne->fx_recuperer_toutes_identites_personnes_by_TYPE_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On récupère tous les administrateurs.
	$oMSG->setData('TYPE_PERSONNE', 'Admin');
	
	$administrateurs = $oPCS_personne->fx_recuperer_toutes_identites_personnes_by_TYPE_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>