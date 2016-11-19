<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(isset($_POST['form_recherche_annonce_date_debut'])){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_annonce.php');
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
	
	# On récupère les types.
	$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');
	$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
	
	
	$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
	$chaines_interdites2 = array("/'/", "/\"/");
		
	# On récupère les données du formulaire.
	$DATE_DEBUT = preg_replace($chaines_interdites, "", trim($_POST['form_recherche_annonce_date_debut']));
	$DATE_FIN = preg_replace($chaines_interdites, "", trim($_POST['form_recherche_annonce_date_fin']));
	$TYPE_ANNONCE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_recherche_annonce_type_annonce'])));
	$BUDGET = preg_replace($chaines_interdites, "", (int)trim($_POST['form_recherche_annonce_budget']));# On s'en fout des décimales...
	$CP_VILLE = preg_replace($chaines_interdites, "", trim($_POST['form_recherche_annonce_cp_ville']));
	$CP_VILLE = preg_replace($chaines_interdites2, "%", $CP_VILLE);# On fera une recherche plus globale avec le caractère %.
	
	# On sauvegarde les données en session pour les réutiliser dans le script_prechargement_liste_annonce.php.
	$_SESSION['recherche_annonce']['DATE_DEBUT'] = $DATE_DEBUT;
	$_SESSION['recherche_annonce']['DATE_FIN'] = $DATE_FIN;
	$_SESSION['recherche_annonce']['TYPE_ANNONCE'] = $TYPE_ANNONCE;
	$_SESSION['recherche_annonce']['BUDGET'] = $BUDGET;
	$_SESSION['recherche_annonce']['CP_VILLE'] = $CP_VILLE;
	$_SESSION['recherche_annonce']['recherche_effectuée'] = 1;
	
	
	# On vérifie les dates.
	if(!$oCL_date->fx_verif_date($DATE_DEBUT)){
		# DATE_DEBUT n'est pas une date.
		$_SESSION['recherche_annonce']['DATE_DEBUT'] = date('Y-m-d');
	}else{
		$_SESSION['recherche_annonce']['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($DATE_DEBUT, false, false, 'fr', 'en');
	}
	if(!empty($DATE_FIN)){
		if(!$oCL_date->fx_verif_date($DATE_FIN)){
			$_SESSION['recherche_annonce']['DATE_FIN'] = "2020-01-01";
		}else{
			$_SESSION['recherche_annonce']['DATE_FIN'] = $oCL_date->fx_ajouter_date($DATE_FIN, false, false, 'fr', 'en');
		}
	}else{
		$_SESSION['recherche_annonce']['DATE_FIN'] = "2020-01-01";
	}
	// Dans l'un ou l'autre cas, on effectue la recherche avec une date de fin très lointaine.

	# Vérification du types de l'annonce.
	if($TYPE_ANNONCE != "*"){# Si le mec a sélectionné un type spécifique on vérifie qu'il existe.
		$liste_types_annonce = array();
		foreach($types_annonce as $key=>$type_annonce){
			$liste_types_annonce[$key] = $type_annonce['ID_TYPES'];
		}
		if(!in_array($TYPE_ANNONCE, $liste_types_annonce)){
			# L'utilisateur a modifié le code source.
			$_SESSION['recherche_annonce']['TYPE_ANNONCE'] = '*';
		}
	}

	
	# On redirige vers la page de listage des annonces. 
	# Le script_prechargement_liste_annonce va automatiquement faire les requêtes en fonction des variables de session.
	header('Location:'.$oCL_page->getPage('liste_annonce').'#resultats_recherche');
	
}else{
	# L'utilisateur ne vient pas depuis le formulaire.
	header('Location:'.$oCL_page->getPage('liste_annonce'));
}
?>