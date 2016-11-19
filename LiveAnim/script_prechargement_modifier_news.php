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
	if(isset($_GET['id_news'])){
		$ID_NOUVEAUTE = (int)$_GET['id_news'];
		if($ID_NOUVEAUTE > 0){
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_nouveaute.php');
			require_once('couche_metier/CL_date.php');
			
			$oMSG = new MSG();
			$oPCS_nouveaute = new PCS_nouveaute();
			$oCL_date = new CL_date();
			
			# On va récupérer la nouveautée.
			$oMSG->setData('ID_NOUVEAUTE', $ID_NOUVEAUTE);
			
			$nouveautee = $oPCS_nouveaute->fx_selectionner_nouveautee_by_ID_NOUVEAUTE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
						
		}else{
			# id_news incorrect.
			header('Location: '.$oCL_page->getPage('liste_news', 'absolu'));
		}
	}else{
		# Pas d'id_news.
		header('Location: '.$oCL_page->getPage('liste_news', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>