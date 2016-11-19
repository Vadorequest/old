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
	if(isset($_POST['ROLE'])){
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_types.php');
		
		$oMSG = new MSG();
		$oPCS_types = new PCS_types();
		
		$nb_erreur = 0;
		$reponse = "";
		$etat = false;
		
		$ROLE = ucfirst(trim($_POST['ROLE']));
		$ROLES = ucfirst(trim($_POST['ROLES']));
		
		# On vérifie que les champs ne soient pas vides.
		if(empty($ROLE) || empty($ROLES)){
			$nb_erreur++;
			$reponse.= "<span class='orange'>Erreur: Le rôle que vous voulez supprimer est vide.</span><br />";
		}
		
		if($nb_erreur == 0){			
			# On insère le singulier.
			$oMSG->setData('ID_TYPES', $ROLE);
			
			$oPCS_types->fx_supprimer_ID_TYPES($oMSG);
			
			# On insère le pluriel.
			$oMSG->setData('ID_TYPES', $ROLES);
			
			$oPCS_types->fx_supprimer_ID_TYPES($oMSG);
			
			$reponse.="<span class='rose'>Le rôle a été supprimé.</span><br />";
			$etat = true;
			echo $etat."|||".$reponse;
		}else{
			$reponse.= "<span class='alert'>Le rôle n'a pas été supprimé.</span><br />";
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
