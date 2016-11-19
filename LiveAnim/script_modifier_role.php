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
	if(isset($_POST['new_ROLE'])){
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_types.php');
		
		$oMSG = new MSG();
		$oPCS_types = new PCS_types();
		
		$nb_erreur = 0;
		$reponse = "";
		$etat = false;
		
		$new_ROLE = ucfirst(trim($_POST['new_ROLE']));
		$new_ROLES = ucfirst(trim($_POST['new_ROLES']));
		$last_ROLE = ucfirst(trim($_POST['last_ROLE']));
		$last_ROLES = ucfirst(trim($_POST['last_ROLES']));
		
		# On vérifie que les champs ne soient pas vides.
		if(empty($new_ROLE) || empty($new_ROLES)){
			$nb_erreur++;
			$reponse.= "<span class='orange'>Un des champs est vide.</span><br />";
		}

		# On vérifie que les deux termes ne soient pas identiques.
		if($new_ROLE == $new_ROLES){
			$nb_erreur++;
			$reponse.= "<span class='orange'>Les deux champs ne doivent pas avoir la même valeur.</span><br />";
		}
		
		# On vérifie que le pluriel soit bien correspondant au singulier.
		if($new_ROLES != $new_ROLE."s" && $new_ROLES != $new_ROLE."x"){
			$nb_erreur++;
			$reponse.= "<span class='orange'>Le terme au pluriel ne correspond pas à celui au singulier. <span class='petit'>(Ex: Artiste -> Artistes)</span></span><br />";
		}

		# On vérifie que le ROLE ne soit pas déjà pris.
		$oMSG->setData('ID_TYPES', $new_ROLE);

		$nb_types = $oPCS_types->fx_compter_types_by_ID_TYPES($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		if($nb_types[0]['nb_types'] > 0){
			$nb_erreur++;
			$reponse.= "<span class='orange'>Le rôle (singulier) est déjà pris, veuillez en sélectionner un autre.</span><br />";
		}
		
		# On véfifie que le ROLES ne soit pas déjà pris.
		$oMSG->setData('ID_TYPES', $new_ROLES);
		
		$nb_types = $oPCS_types->fx_compter_types_by_ID_TYPES($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		if($nb_types[0]['nb_types'] > 0){
			$nb_erreur++;
			$reponse.= "<span class='orange'>Le rôle (pluriel) est déjà pris, veuillez en sélectionner un autre.</span><br />";
		}
		
		if($nb_erreur == 0){			
			# On insère le singulier.
			$oMSG->setData('new_ID_TYPES', $new_ROLE);
			$oMSG->setData('last_ID_TYPES', $last_ROLE);
			
			$oPCS_types->fx_modifier_ID_TYPES($oMSG);
			
			# On insère le pluriel.
			$oMSG->setData('new_ID_TYPES', $new_ROLES);
			$oMSG->setData('last_ID_TYPES', $last_ROLES);
			
			$oPCS_types->fx_modifier_ID_TYPES($oMSG);
			
			$reponse.="<span class='rose'>Le rôle a été modifié.</span><br />";
			$etat = true;
			echo $etat."|||".$reponse;
		}else{
			$reponse.= "<span class='alert'>Le rôle n'a pas été modifié.</span><br />";
			echo $etat."|||".$reponse;
		}		
	}else{
		$reponse.= "<span class='orange'>Erreur: Données invalides.</span><br />";
		echo $etat."|||".$reponse;
		header('Location: '.$oCL_page->getPage('liste_role_admin', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	echo "Accès interdit.";
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>
