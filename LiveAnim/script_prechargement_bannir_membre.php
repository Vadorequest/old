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
	
	$oMSG->setData(0, '');
	$oMSG->setData(1, '');
	
	$membres = $oPCS_personne->fx_recuperer_tous_membres($oMSG)->getData(1)->fetchAll();

}

?>