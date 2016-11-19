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
	if(isset($_GET['id_annonce'])){
		# On récupère l'ID de l'annonce en GET et on le transforme en int.
		$ID_ANNONCE = (int)$_GET['id_annonce'];
		$ID_ANNONCE_ok = 0;

		# On vérifie que cette annonce existe. (Visibilité + statut + futures)
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');	
		require_once('couche_metier/CL_date.php');			
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		$oCL_date = new CL_date();
		
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		$oMSG->setData('VISIBLE', 1);
		$oMSG->setData('STATUT', 'Validée');
		
		$annonce = $oPCS_annonce->fx_recuperer_annonce_valide_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
	
		if(!empty($annonce[0]['ID_ANNONCE'])){
			# L'annonce existe bien et est valide, on va pouvoir faire un contrat dessus.
			$ID_ANNONCE_ok = 1;
			$formulaire = "creer";
			
			# On met en forme les dates:
			$annonce[0]['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_DEBUT'], true, false, 'en', 'fr');
			$annonce[0]['DATE_FIN'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_FIN'], true, false, 'en', 'fr');
			
			$annonce[0]['DATE_DEBUT'] = substr(str_replace(':', 'h', $annonce[0]['DATE_DEBUT']), 0, -3);
			$annonce[0]['DATE_FIN'] = substr(str_replace(':', 'h', $annonce[0]['DATE_FIN']), 0, -3);
			
		}else{
			# L'annonce n'existe pas ou alors elle n'est pas valide.
			$ID_ANNONCE_ok = 0;
			$_SESSION['creer_contrat']['message_affiche'] = false;
			$_SESSION['creer_contrat']['message'] = "<span class='orange'>L'annonce pour laquelle vous souhaitez créer un contrat n'est pas valide. <span class='petit'>(Date dépassée, annonce refusée, ...)</span></span><br />";
		}
	
	}else{
		# Pas de GET.
		$ID_ANNONCE_ok = 0;
		$_SESSION['creer_contrat']['message_affiche'] = false;
		$_SESSION['creer_contrat']['message'] = "<span class='alert'>L'annonce pour laquelle vous souhaitez créer un contrat n'existe pas.</span><br />";
	}
	
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>