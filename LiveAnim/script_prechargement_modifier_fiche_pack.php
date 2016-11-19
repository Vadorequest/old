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
	require_once('couche_metier/PCS_pack.php');
	require_once('couche_metier/PCS_types.php');
	
	$oMSG = new MSG();
	$oPCS_pack = new PCS_pack();
	$oPCS_types = new PCS_types();
	
	$ID_PACK_ok = 0;
	
	# On récupère l'id_pack fournit et on va récupérer toutes ses infos.
	if(isset($_GET['id_pack']) && is_numeric($_GET['id_pack'])){
		
		$ID_PACK_ok = 1;# On valide le fait que l'ID_PACK a bien été réceptionné.
		
		$ID_PACK = (int)$_GET['id_pack'];
		
		# On récupère le pack en question.
		$oMSG->setData('ID_PACK', $ID_PACK);
		
		$pack = $oPCS_pack->fx_recuperer_pack_by_ID_PACK($oMSG)->getData(1)->fetchAll();
		
		# On récupère les types.
		$oMSG->setData('ID_FAMILLE_TYPES', 'Type de pack');
		
		$types_pack = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
		
	}else{
		$ID_PACK_ok = 0;# L'id_pack reçu est invalide.
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>