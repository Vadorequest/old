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
	if(isset($_GET['id_slide'])){
		$ID_SLIDE = (int)$_GET['id_slide'];
		if($ID_SLIDE > 0){
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_slide.php');
			
			$oMSG = new MSG();
			$oPCS_slide = new PCS_slide();
			
			$oMSG->setData('ID_SLIDE', $ID_SLIDE);
			$slide = $oPCS_slide->fx_recuperer_slide_by_ID_SLIDE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			# On vérifie que le slide existe.
			if(isset($slide[0]) && !empty($slide[0])){
				# On met en forme les données.
				$slide[0]['ACCES'] = explode(',', $slide[0]['ACCES']);
				$slide[0]['LIEN'] = trim($slide[0]['LIEN']);
			}else{
				$_SESSION['gestion_slides']['message'] = "<span class='alert'>Le slide que vous souhaitez modifier n'existe pas.</span><br />";
				$_SESSION['gestion_slides']['message_affiche'] = false;
				
				header('Location: '.$oCL_page->getPage('gestion_slides', 'absolu'));
			}
		}else{
			header('Location: '.$oCL_page->getPage('gestion_slides', 'absolu'));
		}
	}else{
		header('Location: '.$oCL_page->getPage('gestion_slides', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>