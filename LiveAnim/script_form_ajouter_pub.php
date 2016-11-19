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
	if(isset($_POST['form_ajouter_modifier_pub_titre'])){
				
		# On initialise nos variables. 
		$nb_erreur = 0;
		$_SESSION['ajouter_pub']['message_affiche'] = false;
		$_SESSION['ajouter_pub']['message'] = "";
		
		$TITRE = trim($_POST['form_ajouter_modifier_pub_titre']);
		$CONTENU = trim($_POST['form_ajouter_modifier_pub_contenu']);
		$POSITION = (int)trim($_POST['form_ajouter_modifier_pub_position']);
		
		# On sauvegarde les variables en session.
		$_SESSION['ajouter_pub']['TITRE'] = $TITRE;
		$_SESSION['ajouter_pub']['CONTENU'] = $CONTENU;
		$_SESSION['ajouter_pub']['POSITION'] = $POSITION;
		
		if(empty($TITRE) || empty($CONTENU)){
			$nb_erreur++;
			$_SESSION['ajouter_pub']['message'].= "<span class='orange'>Un des champs est vide.</span><br />";
		}
		
		if($POSITION < 0){
			$nb_erreur++;
			$_SESSION['ajouter_pub']['message'].= "<span class='orange'>La position sélectionnée est invalide.</span><br />";
		}
		
		if($nb_erreur == 0){
			# On crée la pub.
			
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_pub.php');
			
			$oMSG = new MSG();
			$oPCS_pub = new PCS_pub();
		
			$oMSG->setData('TITRE', $TITRE);
			$oMSG->setData('CONTENU', $CONTENU);
			$oMSG->setData('POSITION', $POSITION);
			
			$oPCS_pub->fx_ajouter_pub($oMSG);
		
			
			# On détruit les variables de sessions inutiles.
			unset($_SESSION['ajouter_pub']['TITRE']);
			unset($_SESSION['ajouter_pub']['CONTENU']);
			unset($_SESSION['ajouter_pub']['POSITION']);			
			
			$_SESSION['ajouter_pub']['message'].= "<span class='valide'>La publicité a été ajoutée.</span><br />";
			header('Location:'.$oCL_page->getPage('ajouter_pub'));
		
		}else{
			$_SESSION['ajouter_pub']['message'].= "<span class='alert'>Une erreur est survenue, la publicité n'a pas été ajoutée.</span><br />";
			header('Location:'.$oCL_page->getPage('ajouter_pub'));
		}		
	}else{
		header('Location:'.$oCL_page->getPage('ajouter_pub'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>
