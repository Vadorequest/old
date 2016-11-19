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
	require_once('couche_metier/PCS_pub.php');
	
	$oMSG = new MSG();
	$oPCS_pub = new PCS_pub();
	
	# on récupère toutes les pubs existantes par ordre de position.
	$pubs = $oPCS_pub->fx_recuperer_toutes_pubs($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($pubs as $key => $pub){
		$pubs[$key]['POSITION'] = constant("PCS_pub::POSITION_".$pub['POSITION']);
	}
	
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>