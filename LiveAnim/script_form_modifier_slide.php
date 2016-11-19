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
		$_SESSION['modifier_slide']['message_affiche'] = false;
		$_SESSION['modifier_slide']['message'] = "";
		
		# On récupère les variables du formulaire.
		$ID_SLIDE = $_POST['form_ajouter_modifier_slide_id_slide'];
		$TITRE = $_POST['form_ajouter_modifier_slide_titre'];
		$LIEN = $_POST['form_ajouter_modifier_slide_lien'];
		$CLASSE = $_POST['form_ajouter_modifier_slide_classe'];
		$ORDRE = $_POST['form_ajouter_modifier_slide_ordre'];
		$ACCES = implode(',', $_POST['form_ajouter_modifier_slide_access']);
		$VISIBLE = $_POST['form_ajouter_modifier_slide_visible'];

		if(!empty($_FILES) && $_FILES['form_ajouter_modifier_slide_url']['error'] == 0){
			require_once('couche_metier/CL_upload.php');			
			
			$oCL_upload = new CL_upload($_FILES['form_ajouter_modifier_slide_url'], "images/slides", array("png", "gif", "jpg", "jpeg"), 0777, array("image/jpeg", "image/jpeg", "image/png", "image/gif", "image/pjpg", "image/pjpeg"), 200, 200, 500000);
			$ext = explode('.', $_FILES['form_ajouter_modifier_slide_url']['name']);
			$extension = $ext[count($ext)-1];
			
			$new_filename = str_replace(Array('é', 'è', 'ê', 'à', 'â', 'ù', 'À', 'É', 'È', 'Ê' ,' ', '/', '\\'), Array('e', 'e', 'e', 'a', 'a', 'u', 'A', 'E', 'E', 'E' ,'_', '_', '_'), $TITRE);

			$tab_message = $oCL_upload->fx_upload($_FILES['form_ajouter_modifier_slide_url']['name'], $new_filename, true, false);
			
			if($tab_message['reussite'] == true){
				$_SESSION['modifier_slide']['message'].= "<center class='rose'>Téléchargement réussi.</center>";
				$_SESSION['modifier_slide']['message_affiche'] = false;
				$URL = "images/slides/".$new_filename.".".$extension;
				$modification_file = true;
			}else{
				$_SESSION['modifier_slide']['message'].= "<span class='alert'>".$tab_message['resultat']."</span>";
				$_SESSION['modifier_slide']['message_affiche'] = false;
				$nb_erreur++;
			}
			header('Location:'.$oCL_page->getPage('modifier_slide', 'absolu'));
		}else{
			$modification_file = false;
		}
		
		if($VISIBLE == "on"){
			$VISIBLE = 1;
		}else{
			$VISIBLE = 0;
		}
		
		if($nb_erreur == 0){
			# On crée le slide.
			$oMSG->setData('ID_SLIDE', $ID_SLIDE);
			$oMSG->setData('TITRE', $TITRE);
			if($modification_file){
				$oMSG->setData('URL', $URL);
			}
			$oMSG->setData('LIEN', $LIEN);
			$oMSG->setData('CLASSE', $CLASSE);
			$oMSG->setData('ORDRE', $ORDRE);
			$oMSG->setData('ACCES', $ACCES);
			$oMSG->setData('VISIBLE', $VISIBLE);
			
			if($modification_file){
				$oPCS_slide->fx_modifier_slide($oMSG);
			}else{
				$oPCS_slide->fx_modifier_slide_sauf_URL($oMSG);
			}
			
			$_SESSION['modifier_slide']['message'].= "<center class='valide'>Le slide a été modifié.</center><br />";
		}else{
			$_SESSION['modifier_slide']['message'].= "<span class='alert'>Une erreur est survenue, le slide n'a pas été modifié.</span><br />";
		}
		
		header('Location:'.$oCL_page->getPage('modifier_slide', 'absolu')."?id_slide=".$ID_SLIDE);
	}else{
		header('Location:'.$oCL_page->getPage('gestion_slides', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>