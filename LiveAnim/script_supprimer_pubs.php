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
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	if(isset($_POST['form_supprimer_pubs_id_pub']) && !empty($_POST['form_supprimer_pubs_id_pub'])){
		foreach($_POST['form_supprimer_pubs_id_pub'] as $ID_PUB){
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_pub.php');

			$oMSG = new MSG();
			$oPCS_pub = new PCS_pub();
			
			$_SESSION['liste_pubs_admin']['message'] = "";
			$_SESSION['liste_pubs_admin']['message_affiche'] = false;
			
			# On supprime la pub.
			$oMSG->setData('ID_PUB', $ID_PUB);
			
			$oPCS_pub->fx_supprimer_pub($oMSG);
			
			if(count($_POST['form_supprimer_pubs_id_pub']) > 1){
				$_SESSION['liste_pubs_admin']['message'].= "<center class='valide'>Les pubs ont été supprimées.</center><br />";
			}else{
				$_SESSION['liste_pubs_admin']['message'].= "<center class='valide'>La pub a été supprimée.</center><br />";
			}
			header('Location: '.$oCL_page->getPage('liste_pubs_admin', 'absolu'));
		}
	}else{
		# Pas de pub à supprimer.
		$_SESSION['liste_pubs_admin']['message'].= "<center class='alert'>Aucune pub n'a été supprimée.</center><br />";
		header('Location: '.$oCL_page->getPage('liste_pubs_admin', 'absolu'));
	}	
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>