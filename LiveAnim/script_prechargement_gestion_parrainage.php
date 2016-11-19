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
		
	$parrains_temp = $oPCS_personne->fx_recuperer_tous_PARRAIN($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	$parrains = Array();
	
	# Pour tous ces parrains on récupère les informations personnes
	foreach($parrains_temp as $key => $parrain){
		$oMSG->setData('ID_PERSONNE', $parrain['PARRAIN']);
		
		$personne = $oPCS_personne->fx_recuperer_compte_min_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		$personne[0]['nb_filleuls'] = $parrain['nb_parrain'];
		
		# On sauvegarde la personne.
		$parrains[$key] = $personne[0];
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>