<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_modifier_news.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	if(isset($_POST['form_ajouter_modifier_news_auteur'])){
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_nouveaute.php');
		require_once('couche_metier/CL_date.php');
		require_once('couche_metier/CL_upload.php');
		
		$oMSG = new MSG();
		$oPCS_nouveaute = new PCS_nouveaute();
		$oCL_date = new CL_date();
		
		# On gère les variables.
		$_SESSION['modifier_news']['message'] = "";
		$_SESSION['modifier_news']['message_affiche'] = false;
		$nb_erreur = 0;
		
		# On récupère les données.
		$ID_NOUVEAUTE = (int)trim($_POST['form_ajouter_modifier_news_id_nouveaute']);
		$AUTEUR = ucfirst(trim($_POST['form_ajouter_modifier_news_auteur']));
		$TITRE = ucfirst(trim($_POST['form_ajouter_modifier_news_titre']));
		$ENTETE = nl2br(ucfirst(trim($_POST['form_ajouter_modifier_news_entete'])));
		$CONTENU = nl2br(ucfirst(trim($_POST['form_ajouter_modifier_news_contenu'])));
		$URL_PHOTO = trim($_POST['form_ajouter_modifier_news_url_photo']);
		$DATE_CREATION = trim($_POST['form_ajouter_modifier_news_date_creation']);
		$VISIBLE = $_POST['form_ajouter_modifier_news_visible'];
				
		# On vérifie le booléen de visibilité.
		if($VISIBLE == "on"){
			$VISIBLE = 1;
		}else{
			$VISIBLE = 0;
		}
		
		$_SESSION['modifier_news']['AUTEUR'] = $AUTEUR;
		$_SESSION['modifier_news']['TITRE'] = $TITRE;
		$_SESSION['modifier_news']['ENTETE'] = $ENTETE;
		$_SESSION['modifier_news']['CONTENU'] = $CONTENU;
		$_SESSION['modifier_news']['URL_PHOTO'] = $URL_PHOTO;
		$_SESSION['modifier_news']['VISIBLE'] = $VISIBLE;
		
		# On vérifie les informations.
		# On vérifie qu'aucun champ obligatoire ne soit vide.
		if(empty($AUTEUR) || empty($TITRE) || empty($CONTENU)){
			$nb_erreur++;
			$_SESSION['modifier_news']['message'].= "<span class='orange'>Un des champs obligatoire est vide.</span><br /><br />";
		}
		
		# On vérifie l'auteur.
		if(strlen($AUTEUR) > 100){
			$nb_erreur++;
			$_SESSION['modifier_news']['message'].= "<span class='orange'>L'auteur ne peux pas avoir plus de 100 caractères.</span><br /><br />";
		}
		
		# On s'occupe de la photo de la news
		if(!empty($_FILES) && $_FILES['form_ajouter_modifier_news_nouvelle_photo']['error'] == 0){
			$oCL_upload = new CL_upload($_FILES['form_ajouter_modifier_news_nouvelle_photo'], "images/uploads/news", array("png", "gif", "jpg", "jpeg"), 0777, array("image/jpeg", "image/jpeg", "image/png", "image/gif", "image/pjpg", "image/pjpeg"), 200, 200, 100000);
			
			
			$interdit = Array(' ', 'é', 'è', 'É', 'È', 'à', 'À', 'ù', '/', '\\', '-', '(', ')', '[', ']', '!');
			$remplace = Array('_', 'e', 'e', 'E', 'E', 'a', 'A', 'u', '', '', '', '', '', '', '', '');
			$new_filename = str_replace($interdit, $remplace, $TITRE);
			
			$tab_message = $oCL_upload->fx_upload($_FILES['form_ajouter_modifier_news_nouvelle_photo']['name'], $new_filename);
			
			if($tab_message['reussite'] == true){
				$URL_PHOTO =  $oCL_page->getPage('accueil', 'absolu').$tab_message['resultat'];
			}else{
				$_SESSION['modifier_news']['message'].= $tab_message['resultat'];
				$URL_PHOTO = "";
				$echec_upload = true;
				# On empèche pas la modification de la news.
			}
		}
		
		# On vérifie la date.
		if(!$oCL_date->fx_verif_date($DATE_CREATION, 'en', true)){
			$nb_erreur++;
			$_SESSION['modifier_news']['message'].= "<span class='orange'>Le format de la date est incorrect, format anglais attendu.</span><br /><br />";
		}	
		
		
		if($nb_erreur == 0){
			# On prépare le message.
			$oMSG->setData('ID_NOUVEAUTE', $ID_NOUVEAUTE);
			$oMSG->setData('AUTEUR', $AUTEUR);
			$oMSG->setData('TITRE', $TITRE);
			$oMSG->setData('ENTETE', $ENTETE);
			$oMSG->setData('CONTENU', $CONTENU);
			$oMSG->setData('URL_PHOTO', $URL_PHOTO);
			$oMSG->setData('DATE_CREATION', $DATE_CREATION);
			$oMSG->setData('VISIBLE', $VISIBLE);
			
			$oPCS_nouveaute->fx_modifier_nouveautee($oMSG)->getData(1);
			

			$_SESSION['modifier_news']['message'].= "<span class='valide'>La news a bien été modifiée.</span><br /><br />";
			header('Location: '.$oCL_page->getPage('modifier_news', 'absolu')."?id_news=".$ID_NOUVEAUTE);
			
		}else{
			header('Location: '.$oCL_page->getPage('modifier_news', 'absolu')."?id_news=".$ID_NOUVEAUTE);
		}
	}else{
		# Si pas de POST.
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>
