<?php
if(!isset($_SESSION)){
	session_start();
}
error_reporting(-1);
# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
	
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_annonce.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oCL_date = new CL_date();

	# Si au moins une annonce a été visitée.
	if(is_array($_SESSION['compte']['annonces_visitées'])){
		# On récupère de la session les annonces visitées.
		$annonces_visitees = array_reverse($_SESSION['compte']['annonces_visitées']);
		$annonces = Array();
		
		# pour chacune des annonces visitées on la récupère de la bdd et on l'affiche.
		foreach($annonces_visitees as $annonce_visitee){
			$annonce_visitee = trim($annonce_visitee);
			if(!empty($annonce_visitee)){
				$oMSG->setData('ID_ANNONCE', $annonce_visitee);
				
				$annonce = $oPCS_annonce->fx_recuperer_annonce_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				# On met en forme les données.
				$annonce[0]['DATE_ANNONCE'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($annonce[0]['DATE_ANNONCE'], true, false, 'en', 'fr'), true, 'fr');
				$annonce[0]['DATE_DEBUT'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($annonce[0]['DATE_DEBUT'], true, false, 'en', 'fr'), true, 'fr');
				$annonce[0]['DATE_FIN'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($annonce[0]['DATE_FIN'], true, false, 'en', 'fr'), true, 'fr');
				
				
				# On sauvegarde l'annonce dans le tableau.
				$annonces[] = $annonce[0];
			}
		}
	}
	
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>