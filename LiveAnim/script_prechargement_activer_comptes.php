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
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oCL_date = new CL_date();
	
	$oMSG->setData('CLE_ACTIVATION', "");
	
	$comptes_inactifs = $oPCS_personne->fx_recuperer_comptes_non_actives($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On traite les informations.
	foreach($comptes_inactifs as $key=>$compte_inactif){
	$comptes_inactifs[$key]['DATE_CONNEXION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($compte_inactif['DATE_CONNEXION'], true, false, 'en', 'fr'), true, 'fr');
	$comptes_inactifs[$key]['DATE_CONNEXION_simple'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($compte_inactif['DATE_CONNEXION'], true, false, 'en', 'fr'), true, 'fr', true, false, true);
	}
	
	function fx_recuperer_infos_by_ID_IP($ID_IP){
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		
		$oMSG->setData('ID_IP', $ID_IP);
		
		return $infos_ID_IP = $oPCS_personne->fx_recuperer_infos_by_ID_IP($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	}
	
	function fx_recuperer_infos_by_IP_COOKIE($IP_COOKIE){
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		
		$oMSG->setData('IP_COOKIE', $IP_COOKIE);
		
		return $infos_IP_COOKIE = $oPCS_personne->fx_recuperer_infos_by_IP_COOKIE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	}

}
?>