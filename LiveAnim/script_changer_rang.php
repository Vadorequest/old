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

	if(isset($_POST['form_changer_rang_pseudo'])){
		# Si on reçoit les données du formulaire.
		$pseudo = ucfirst(trim($_POST['form_changer_rang_pseudo']));
		$type_personne = $_POST['form_changer_rang_type_personne'];
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_personne.php');
		
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		
		$oMSG->setData('PSEUDO', $pseudo);
		$oMSG->setData('TYPE_PERSONNE', $type_personne);
		
		# On vérifie que le membre existe bien.
		
		$nb_pseudo = $oPCS_personne->fx_compter_pseudo_by_PSEUDO($oMSG)->getData(1)->fetchAll();
		
		if($nb_pseudo[0]['nb_pseudo'] == 1){
			# Le membre existe, on vérifie le rang attribué.
			require_once('couche_metier/PCS_types.php');
			
			$oPCS_types = new PCS_types();
			
			$oMSG->setData('ID_TYPES', $type_personne);
			
			$nb_types = $oPCS_types->fx_compter_types_by_ID_TYPES($oMSG)->getData(1)->fetchAll();
			
			if($nb_types[0]['nb_types'] == 1){
				# Le type sélectionné existe, on valide.
				
				$oPCS_personne->fx_modifier_rang($oMSG);
		
				$_SESSION['administration']['message_affiche'] = false;
				$_SESSION['administration']['message'] = "<span class='valide'>Opération réussie. Le rang du membre a été modifié.</span><br />";
			}
			else{
				# Le rang n'existe pas.
				$_SESSION['administration']['message_affiche'] = false;
				$_SESSION['administration']['message'] = "<span class='alert'>Le nouveau rang spécifié n'existe pas.</span><br />";
			}
		}else{
		# Le membre n'existe pas.
			$_SESSION['administration']['message_affiche'] = false;
			$_SESSION['administration']['message'] = "<span class='alert'>Le membre n'existe pas.</span><br />";
		}
		header('Location: '.$oCL_page->getPage('changer_rang', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>