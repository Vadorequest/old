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
	
	$oMSG->setData('ID_FAMILLE_TYPES', 'Role');
	$roles_singulier = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	$oMSG->setData('ID_FAMILLE_TYPES', 'Roles');
	$roles_pluriel = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On est sensé avoir autant de rôles singuliers que pluriels.
	$nb_roles = count($roles_singulier);
	
	# On regroupe les tableaux.
	$roles = Array();
	
	for($i = 0;$i<$nb_roles;$i++){
		$roles[$i]['singulier'] = $roles_singulier[$i]['ID_TYPES'];
		$roles[$i]['pluriel'] = $roles_pluriel[$i]['ID_TYPES'];
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>