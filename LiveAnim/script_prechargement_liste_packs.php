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
	# On charge tous les packs existants.
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_pack.php');
	
	$oMSG = new MSG();
	$oPCS_pack = new PCS_pack();
	
	$oMSG->setData(0, "");
	$oMSG->setData(1, "");
	
	$packs = $oPCS_pack->fx_recuperer_tous_packs($oMSG)->getData(1)->fetchAll();
	
}else{
# Si l'internaute n'est pas connecté et admin il gicle.
header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>