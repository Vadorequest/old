<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(isset($_POST['form_recherche_artiste_role'])){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_annonce.php');
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
	
	# On récupère les types.
	$oMSG->setData('ID_FAMILLE_TYPES', 'Statut professionnel');
	$types_statut = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
	
	
	$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
	$chaines_interdites2 = array("/'/", "/\"/");
		
	# On récupère les données du formulaire.
	$ROLES = preg_replace($chaines_interdites, "", trim($_POST['form_recherche_artiste_role']));
	$STATUT_PERSONNE = preg_replace($chaines_interdites, "", trim($_POST['form_recherche_artiste_statut']));
	$DEPARTEMENTS = explode(',', preg_replace($chaines_interdites, "", trim($_POST['form_recherche_artiste_departements'])));
	$NOTE = preg_replace($chaines_interdites, "", (int)trim($_POST['form_recherche_artiste_note']));
	
	# On traite la checkbox.
	if($STATUT_PERSONNE == "on" || $STATUT_PERSONNE == "On"){
		$STATUT_PERSONNE = "Pro";
	}else{
		$STATUT_PERSONNE = "";# A tester pour voir si ça retourne tout le monde ou pas :/
	}
	
	$departements_valides = "";
	$nb_departements = count($DEPARTEMENTS);
	# On vérifie que tous les départements soient valides.
	foreach($DEPARTEMENTS as $key=>$departement){
		if((int)$departement > 0){
			$departements_valides.= $departement;
		}
		if($key != $nb_departements-1){
			$departements_valides.= ",";
		}
	}

	# On vérifie la note.
	if($NOTE <= 0 || $NOTE > 5){
		# Si la note est invalide alors on lui attribue la valeur 3 (Par défaut)
		$NOTE = 3;
	}
	
	# On sauvegarde les données en session pour les réutiliser dans le script_prechargement_liste_artiste.php.
	$_SESSION['recherche_artiste']['ROLES'] = $ROLES;
	$_SESSION['recherche_artiste']['STATUT_PERSONNE'] = $STATUT_PERSONNE;
	$_SESSION['recherche_artiste']['DEPARTEMENTS'] = $departements_valides;
	$_SESSION['recherche_artiste']['NOTE'] = $NOTE;
	$_SESSION['recherche_artiste']['recherche_effectuée'] = true;

	

	# Vérification du types du statut de la personne.
	if($STATUT_PERSONNE != ""){# Si le mec a sélectionné un type spécifique on vérifie qu'il existe.
		$liste_statut_personne = array();
		foreach($types_statut as $key=>$type_statut){
			$liste_statut_personne[$key] = $type_statut['ID_TYPES'];
		}
		if(!in_array($STATUT_PERSONNE, $liste_statut_personne)){
			# L'utilisateur a modifié le code source.
			$_SESSION['recherche_artiste']['STATUT_PERSONNE'] = '';# on recherche tous les types si le type n'est pas compris.
		}
	}
	
	# On redirige vers la page de listage des artistes. 
	# Le script_prechargement_liste_artiste va automatiquement faire les requêtes en fonction des variables de session.
	header('Location:'.$oCL_page->getPage('liste_artiste').'#resultats_recherche');
	
}else{
	# L'utilisateur ne vient pas depuis le formulaire.
	header('Location:'.$oCL_page->getPage('liste_artiste'));
}
?>