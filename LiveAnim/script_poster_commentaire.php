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
if($_SESSION['compte']['connecté'] == true){
	if(isset($_POST['form_poster_commentaire_contenu'])){
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_commentaire.php');
			
			$oMSG = new MSG();
			$oPCS_commentaire = new PCS_commentaire();
			
			$nb_error = 0;
			$_SESSION['news']['message_affiche'] = false;
			$_SESSION['news']['message'] = "";
			$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
			
			# On récupère les données.
			$CONTENU = nl2br(preg_replace($chaines_interdites, "", trim($_POST['form_poster_commentaire_contenu'])));
			$ID_NOUVEAUTE = (int)$_POST['form_poster_commentaire_id_nouveaute'];
			$ID_PERSONNE = $_SESSION['compte']['ID_PERSONNE'];
			$DATE_CREATION = date('Y-m-d H:i:s');
			$VISIBLE = 1;
			
			# On vérifie les données.
			if($ID_NOUVEAUTE == 0){
				$nb_error++;
				$_SESSION['news']['message'].= "<span class='orange'>Une erreur est survenue, votre commentaire n'a pas été publié.</span><br /><br />";
			}
			
			# On vérifie que le commentaire ne soit pas vide.
			if(empty($CONTENU)){
				$nb_error++;
				$_SESSION['news']['message'].= "<span class='orange'>Vous ne pouvez pas publier un commentaire vide !</span><br /><br />";
			}
			
			if($nb_error == 0){
				# On publie le commentaire.
				$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
				$oMSG->setData('ID_NOUVEAUTE', $ID_NOUVEAUTE);
				$oMSG->setData('CONTENU', $CONTENU);
				$oMSG->setData('DATE_CREATION', $DATE_CREATION);
				$oMSG->setData('VISIBLE', $VISIBLE);
				
				$id_commentaire = $oPCS_commentaire->fx_ajouter_commentaire($oMSG)->getData(1);

				# On vérifie qu'on ai bien inséré qqch.
				if($id_commentaire != 0){
					$_SESSION['news']['message'].= "<span class='valide'>Votre commentaire a été publié, nous vous remercions.</span><br /><br />";
				}else{
					$_SESSION['news']['message'].= "<span class='alert'>Une erreur est survenue, votre commentaire n'a pas été publié.</span><br /><br />";
				}
				header('Location: '.$oCL_page->getPage('news', 'absolu')."?id_news=".$ID_NOUVEAUTE);
				
			}else{
				header('Location: '.$oCL_page->getPage('news', 'absolu')."?id_news=".$ID_NOUVEAUTE);
			}
	}else{
		# Pas de post
		header('Location: '.$oCL_page->getPage('liste_news', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('liste_news', 'absolu'));
}
?>