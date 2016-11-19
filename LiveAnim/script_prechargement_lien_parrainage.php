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
	$ID_PARRAIN = $_SESSION['compte']['ID_PERSONNE'];
	$lien = $oCL_page->getPage('inscription', 'absolu')."?parrain=".$ID_PARRAIN."#inscriptionh2";
	$image = $oCL_page->getPage('accueil', 'absolu').$oCL_page->getImage('special_parrainage');
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>