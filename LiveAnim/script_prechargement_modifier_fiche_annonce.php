<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}
/////////////////////////////////////////// Comparer avec le script admin.
# On vérifie que la personne est connectée.
if($_SESSION['compte']['connecté'] == true){

	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_annonce.php');		
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
		
	# On récupère les types nécessaires:
	$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');# Vérifier la famille type.
	
	$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
	
	# On récupère les départements.
	$departements = $oPCS_annonce->fx_recuperer_tous_departements($oMSG)->getData(1)->fetchAll();
	
	# Récupérer l'id_annonce en GET et récupérer l'annonce associée.
	if(isset($_GET['id_annonce']) && is_numeric($_GET['id_annonce'])){
		$ID_ANNONCE = $_GET['id_annonce'];
		
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		
		$annonce = $oPCS_annonce->fx_recuperer_annonce_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
		# On vérifie que l'annonce appartient bien à cette personne.
		if($annonce[0]['ID_PERSONNE'] == $_SESSION['compte']['ID_PERSONNE']){
			# On vérifie que l'annonce n'ait pas déjà été validée.
			if($annonce[0]['STATUT'] != "Validée"){
				# On met en forme les dates.
				$annonce[0]['DATE_ANNONCE'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_ANNONCE'], true, false, 'en', 'fr');
				$annonce[0]['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_DEBUT'], true, false, 'en', 'fr');
				$annonce[0]['DATE_FIN'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_FIN'], true, false, 'en', 'fr');
				
				# On élimine les secondes et on convertit plus lisiblement
				$annonce[0]['DATE_DEBUT'] = substr(str_replace(':', 'h', $annonce[0]['DATE_DEBUT']), 0, -3);
				$annonce[0]['DATE_FIN'] = substr(str_replace(':', 'h', $annonce[0]['DATE_FIN']), 0, -3);
			
				# On met en forme les textarea:
				$annonce[0]['ARTISTES_RECHERCHES'] = str_replace(array('<br>', '<br />'), '', $annonce[0]['ARTISTES_RECHERCHES']);
				$annonce[0]['DESCRIPTION'] = str_replace(array('<br>', '<br />'), '', $annonce[0]['DESCRIPTION']);
			}else{
				$_SESSION['historique_annonce']['message_affiche'] = false;
				$_SESSION['historique_annonce']['message'] = "<span class='orange'>Vous ne pouvez pas modifier une annonce ayant été publiée.</span><br />";
				header('Location:'.$oCL_page->getPage('historique_annonce'));
			}
		}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
			# On redirige l'administrateur vers la page de modification des annonces pour les administrateurs.
			$_SESSION['modifier_annonce']['message'] = "<span class='rose'>Vous avez été redirigé vers l'interface administrateur.</span>";
			$_SESSION['modifier_annonce']['message_affiche'] = false;
			header('Location:'.$oCL_page->getPage('modifier_fiche_annonce_by_admin')."?id_annonce=".$annonce[0]['ID_ANNONCE']);
		}else{
			# L'utilisateur veut modifier une annonce qui ne lui appartient pas.
			header('Location:'.$oCL_page->getPage('gestion_compte'));
		}
		
	}
	
	# On récupère les types de statut.
	$oMSG->setData('ID_FAMILLE_TYPES', "Statut de l'annonce");# Vérifier la famille type
	
	$statuts = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();

}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>