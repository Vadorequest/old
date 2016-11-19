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
	if(isset($_POST['form_ajouter_modifier_slide_titre'])){
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_slide.php');
		require_once('couche_metier/CL_upload.php');
		
		$oMSG = new MSG();
		$oPCS_slide = new PCS_slide();
		
		# On initialise nos variables.
		$nb_erreur = 0;
		$_SESSION['gestion_slides']['message_affiche'] = false;
		$_SESSION['gestion_slides']['message'] = "";
		
		# On récupère les variables du formulaire.
		$TITRE = $_POST['form_ajouter_modifier_slide_titre'];
		$LIEN = $_POST['form_ajouter_modifier_slide_lien'];
		$CLASSE = $_POST['form_ajouter_modifier_slide_classe'];
		$ORDRE = $_POST['form_ajouter_modifier_slide_ordre'];
		$ACCES = implode(',', $_POST['form_ajouter_modifier_slide_access']);
		
		# On sauvegarde en session nos variables.
		$_SESSION['gestion_slides']['TITRE'] = $TITRE;
		$_SESSION['gestion_slides']['CLASSE'] = $CLASSE;
		$_SESSION['gestion_slides']['ORDRE'] = $ORDRE;
		$_SESSION['gestion_slides']['ACCES'] = explode(',', $ACCES);

		if(!empty($_FILES) && $_FILES['form_ajouter_modifier_slide_url']['error'] == 0){
			require_once('couche_metier/CL_upload.php');			
			
			$oCL_upload = new CL_upload($_FILES['form_ajouter_modifier_slide_url'], "images/slides", array("png", "gif", "jpg", "jpeg"), 0777, array("image/jpeg", "image/jpeg", "image/png", "image/gif", "image/pjpg", "image/pjpeg"), 200, 200, 500000);
			$ext = explode('.', $_FILES['form_ajouter_modifier_slide_url']['name']);
			$extension = $ext[count($ext)-1];
			
			$new_filename = str_replace(Array('é', 'è', 'ê', 'à', 'â', 'ù', 'À', 'É', 'È', 'Ê' ,' ', '/', '\\'), Array('e', 'e', 'e', 'a', 'a', 'u', 'A', 'E', 'E', 'E' ,'_', '_', '_'), $TITRE);

			$tab_message = $oCL_upload->fx_upload($_FILES['form_ajouter_modifier_slide_url']['name'], $new_filename, true, false);
			
			if($tab_message['reussite'] == true){
				$_SESSION['gestion_slides']['message'].= "<center class='rose'>Téléchargement réussi.</center>";
				$_SESSION['gestion_slides']['message_affiche'] = false;
				$URL = "images/slides/".$new_filename.".".$extension;
			}else{
				$_SESSION['gestion_slides']['message'].= "<span class='alert'>".$tab_message['resultat']."</span>";
				$_SESSION['gestion_slides']['message_affiche'] = false;
				$nb_erreur++;
			}
			header('Location:'.$oCL_page->getPage('gestion_slides', 'absolu'));
		}else{
			$nb_erreur++;
		}
		
		if($nb_erreur == 0){
			# On crée le slide.
			$oMSG->setData('TITRE', $TITRE);
			$oMSG->setData('URL', $URL);
			$oMSG->setData('LIEN', $LIEN);
			$oMSG->setData('CLASSE', $CLASSE);
			$oMSG->setData('ORDRE', $ORDRE);
			$oMSG->setData('ACCES', $ACCES);
			$oMSG->setData('VISIBLE', 1);
			
			$oPCS_slide->fx_ajouter_slide($oMSG);
			
			# On vire les infos de la session.
			unset($_SESSION['gestion_slides']['TITRE']);
			unset($_SESSION['gestion_slides']['CLASSE']);
			unset($_SESSION['gestion_slides']['ORDRE']);
			unset($_SESSION['gestion_slides']['ACCES']);
			
			$_SESSION['gestion_slides']['message'].= "<center class='valide'>Le slide a été crée.</center><br />";
		}else{
			$_SESSION['gestion_slides']['message'].= "<span class='alert'>Une erreur est survenue, le slide n'a pas été crée.</span><br />";
		}
		
		header('Location:'.$oCL_page->getPage('gestion_slides', 'absolu'));
	}else{
		header('Location:'.$oCL_page->getPage('gestion_slides', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>