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
	require_once('couche_metier/PCS_pack.php');
	require_once('couche_metier/PCS_annonce.php');
	require_once('couche_metier/PCS_nouveaute.php');
	require_once('couche_metier/PCS_pub.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oPCS_pack = new PCS_pack();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_nouveaute = new PCS_nouveaute();
	$oPCS_pub = new PCS_pub();
	

	# On récupère le nombre de comptes non activés.
	$oMSG->setData('CLE_ACTIVATION', "");
	
	$nb_comptes_inactifs = $oPCS_personne->fx_compter_comptes_non_actives($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On récupère le nombre de membres.
	$nb_comptes = $oPCS_personne->fx_compter_tous_membres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On récupère le nombre de comptes supprimés.
	$oMSG->setData('PERSONNE_SUPPRIMEE', 1);
	$oMSG->setData('VISIBLE', 0);
	
	$nb_comptes_supprimes = $oPCS_personne->fx_compter_comptes_by_PERSONNE_SUPPRIMEE_et_VISIBILITE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On récupère le nombre d'annonces en attentes.
	$oMSG->setData('VISIBLE', 0);
	
	$nb_annonces_en_attente = $oPCS_annonce->fx_compter_annonce_by_VISIBLE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	// $nb_annonces_en_attente[0]['nb_annonce']
	
	# On récupère le nombre d'annonces totales.
	$nb_annonces_totales = $oPCS_annonce->fx_compter_toutes_annonces($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On récupère le nombre de contrats en cours.
	
	# On récupère le nombre de contrats totaux.
	
	# On récupère le nombre packs existants.
	$nb_packs = $oPCS_pack->fx_compter_tous_packs($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	$oMSG->setData('VISIBLE', 0);
	$nb_packs_inactifs = $oPCS_pack->fx_compter_packs_by_VISIBLE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On récupère le nombre de news existantes.
	$oMSG->setData('VISIBLE', 1);
	$nb_news = $oPCS_nouveaute->fx_compter_toutes_nouveautees_by_VISIBLE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	$oMSG->setData('VISIBLE', 0);
	$nb_news_desactive = $oPCS_nouveaute->fx_compter_toutes_nouveautees_by_VISIBLE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On récupère le nombre de pubs existantes.
	$nb_pubs = $oPCS_pub->fx_compter_toutes_pubs($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

	
	
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>