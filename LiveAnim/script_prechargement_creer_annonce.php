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
		
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_annonce.php');		
	require_once('couche_metier/PCS_types.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_types = new PCS_types();
		
	# On récupère les types nécessaires:
	$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');# Vérifier la famille type.
	
	$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
	
	# On récupère les départements.
	$departements = $oPCS_annonce->fx_recuperer_tous_departements($oMSG)->getData(1)->fetchAll();

}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>