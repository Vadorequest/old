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
	if(isset($_POST['form_cacher_commentaire_id_nouveaute'])){
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_commentaire.php');
		
		$oMSG = new MSG();
		$oPCS_commentaire = new PCS_commentaire();
		
		$ID_NOUVEAUTE = (int)$_POST['form_cacher_commentaire_id_nouveaute'];
		$ID_COMMENTAIRE = (int)$_POST['form_cacher_commentaire_id_commentaire'];
		
		if($ID_NOUVEAUTE > 0 && $ID_COMMENTAIRE > 0){
			# On passe le commentaire en mode invisible.
			$oMSG->setData('ID_COMMENTAIRE', $ID_COMMENTAIRE);
			$oMSG->setData('ID_NOUVEAUTE', $ID_NOUVEAUTE);
			$oMSG->setData('VISIBLE', 0);
			
			$oPCS_commentaire->fx_cacher_commentaire($oMSG);
			
			$_SESSION['news']['message'] = "<span class='valide'>Le commentaire a été supprimé.</span>";
			$_SESSION['news']['message_affiche'] = false;
			header('Location: '.$oCL_page->getPage('news', 'absolu')."?id_news=".$ID_NOUVEAUTE);
		}else{
			# Ids incorrects
			header('Location: '.$oCL_page->getPage('liste_news', 'absolu'));
		}	
	}else{
		# Pas de POST.
		header('Location: '.$oCL_page->getPage('liste_news', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>