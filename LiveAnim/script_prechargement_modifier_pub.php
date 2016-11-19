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
	if(isset($_GET['id_pub']) && (int)$_GET['id_pub'] > 0){
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_pub.php');
		
		$oMSG = new MSG();
		$oPCS_pub = new PCS_pub();
		
		# On récupère la pub.
		$oMSG->setData('ID_PUB', $_GET['id_pub']);
		
		$pub = $oPCS_pub->fx_recuperer_pub_by_ID_PUB($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		if(!isset($pub[0]['ID_PUB']) || empty($pub[0]['ID_PUB'])){
			# La pub recherchée n'existe pas.
			$_SESSION['liste_pubs_admin']['message_affiche'] = false;
			$_SESSION['liste_pubs_admin']['message'] = "<span class='alert'>Cette pub n'existe pas, vous avez été redirigé.</span><br />";
			
			header('Location: '.$oCL_page->getPage('liste_pubs_admin', 'absolu'));
		}
		
	}else{
		#$_GET['id_pub'] incorrect.
		$_SESSION['liste_pubs_admin']['message_affiche'] = false;
		$_SESSION['liste_pubs_admin']['message'] = "<span class='alert'>Cette pub n'existe pas, vous avez été redirigé.</span><br />";
		
		header('Location: '.$oCL_page->getPage('liste_pubs_admin', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>