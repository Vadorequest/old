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
	require_once('couche_metier/PCS_types.php');
	
	$oMSG = new MSG();
	$oPCS_types = new PCS_types();
	
	$oMSG->setData('ID_FAMILLE_TYPES', 'Type de pack');
	
	$types_packs = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>