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
	if(isset($_POST['form_pack_nom'])){
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_pack.php');		
		require_once('couche_metier/PCS_types.php');
		
		$oMSG = new MSG();
		$oPCS_pack = new PCS_pack();
		$oPCS_types = new PCS_types();
		
		# On récupère les types nécessaires:
		$oMSG->setData('ID_FAMILLE_TYPES', 'Type de pack');
		$types_pack = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
		
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/");
	
		$ID_PACK = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_id_pack']);
		$NOM = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_pack_nom'])));
		$DESCRIPTION = nl2br(ucfirst(trim($_POST['form_pack_description'])));
		$TYPE_PACK = preg_replace($chaines_interdites, "", $_POST['form_pack_type_pack']);
		$PRIX_BASE = preg_replace($chaines_interdites, "", trim($_POST['form_pack_prix_base']));
		$DUREE = preg_replace($chaines_interdites, "", trim($_POST['form_pack_duree']));
		$SOUMIS_REDUCTIONS_PARRAINAGE = preg_replace($chaines_interdites, "", $_POST['form_pack_soumis_reduction_parrainage']);
		$GAIN_PARRAINAGE_MAX = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_gain_parrainage_max']);
		$REDUCTION = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_reduction']);
		$VISIBLE = preg_replace($chaines_interdites, "", $_POST['form_pack_visible']);

		$CV_VISIBILITE = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_cv_visibilite']);
		$CV_ACCESSIBLE = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_cv_accessible']);
		$NB_FICHES_VISITABLES = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_nb_fiches_visitables']);
		$CV_VIDEO_ACCESSIBLE = preg_replace($chaines_interdites, "", $_POST['form_pack_cv_video_accessible']);
		$ALERTE_NON_DISPONIBILITE = preg_replace($chaines_interdites, "", $_POST['form_pack_alerte_non_disponibilite']);
		$NB_DEPARTEMENTS_ALERTE = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_nb_departements_alerte']);
		$PARRAINAGE_ACTIVE = preg_replace($chaines_interdites, "", $_POST['form_pack_parrainage_active']);
		$PREVISUALISATION_FICHES = preg_replace($chaines_interdites, "", $_POST['form_pack_previsualisation_fiches']);
		$CONTRATS_PDF = preg_replace($chaines_interdites, "", $_POST['form_pack_contrats_pdf']);
		$SUIVI = preg_replace($chaines_interdites, "", $_POST['form_pack_suivi']);
		$PUBS = preg_replace($chaines_interdites, "", $_POST['form_pack_pubs']);
		
		# On prépare nos variables nécessaires pour les messages d'erreurs.
		$_SESSION['modifier_fiche_pack']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
		$_SESSION['modifier_fiche_pack']['message'] = "";# On initialise et on rajoutera par dessus.
		
		$nb_erreur = 0;
		
		# On vérifie l'intégrité des données:
		
		# On commence par vérifier que les champs obligatoire ne soient pas vides.
		if(empty($NOM) || empty($DESCRIPTION) || empty($NB_FICHES_VISITABLES)){
			$_SESSION['modifier_fiche_pack']['message'].= "<span class='alert'>Un de champs obligatoire est vide.</span><br />";
			$nb_erreur++;
		}

		# On vérifie que le type de pack soit correct.
		$type_pack_ok = 0;
		foreach($types_pack as $key=>$type_pack){
			if($type_pack['ID_TYPES'] == $TYPE_PACK){
				$type_pack_ok++;
			}
		}
		
		# Si ça vaut 0 alors c'est que le type n'existe pas en BDD donc modification formulaire. Si plus de 1 alors c'est qu'on a deux types identiques en BDD. (impossible)
		if($type_pack_ok != 1){
			$_SESSION['modifier_fiche_pack']['message'].= "<span class='alert'>Le type de pack sélectionné n'existe pas.</span><br />";
			$nb_erreur++;
		}
		
		# On vire les virgules probables.
		$PRIX_BASE = str_replace(",", ".", $PRIX_BASE);
		
		# Si malgré ça le prix ne correspond pas.
		if(!is_numeric($PRIX_BASE)){
			$_SESSION['modifier_fiche_pack']['message'].= "<span class='alert'>Le prix de base est incorrect. <span class='petit'>(Ex: 250/999.0/99,5)</span></span><br />";
			$nb_erreur++;
		}
		
		
		# S'il n'y a pas d'erreurs.
		if($nb_erreur == 0){
			$oMSG->setData('ID_PACK', $ID_PACK);
			$oMSG->setData('NOM', $NOM);
			$oMSG->setData('DESCRIPTION', $DESCRIPTION);
			$oMSG->setData('TYPE_PACK', $TYPE_PACK);
			$oMSG->setData('PRIX_BASE', $PRIX_BASE);
			$oMSG->setData('DUREE', $DUREE);
			$oMSG->setData('SOUMIS_REDUCTIONS_PARRAINAGE', $SOUMIS_REDUCTIONS_PARRAINAGE);
			$oMSG->setData('GAIN_PARRAINAGE_MAX', $GAIN_PARRAINAGE_MAX);
			$oMSG->setData('REDUCTION', $REDUCTION);
			$oMSG->setData('VISIBLE', $VISIBLE);
			
			$oMSG->setData('CV_VISIBILITE', $CV_VISIBILITE);
			$oMSG->setData('CV_ACCESSIBLE', $CV_ACCESSIBLE);
			$oMSG->setData('NB_FICHES_VISITABLES', $NB_FICHES_VISITABLES);
			$oMSG->setData('CV_VIDEO_ACCESSIBLE', $CV_VIDEO_ACCESSIBLE);
			$oMSG->setData('ALERTE_NON_DISPONIBILITE', $ALERTE_NON_DISPONIBILITE);
			$oMSG->setData('NB_DEPARTEMENTS_ALERTE', $NB_DEPARTEMENTS_ALERTE);
			$oMSG->setData('PARRAINAGE_ACTIVE', $PARRAINAGE_ACTIVE);
			$oMSG->setData('PREVISUALISATION_FICHES', $PREVISUALISATION_FICHES);
			$oMSG->setData('CONTRATS_PDF', $CONTRATS_PDF);
			$oMSG->setData('SUIVI', $SUIVI);
			$oMSG->setData('PUBS', $PUBS);
			
			$oPCS_pack->fx_modifier_pack($oMSG);
			
			$_SESSION['modifier_fiche_pack']['message'].= "<span class='valide'>Le pack a été modifié.</span><br />";
			header('Location: '.$oCL_page->getPage('modifier_fiche_pack')."?id_pack=".$ID_PACK);
			
		}else{
			$_SESSION['modifier_fiche_pack']['message'].= "<span class='alert'>Le pack n'a pas été modifié.</span><br />";
			header('Location: '.$oCL_page->getPage('modifier_fiche_pack')."?id_pack=".$ID_PACK);
		}
		
		
	}else{
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>