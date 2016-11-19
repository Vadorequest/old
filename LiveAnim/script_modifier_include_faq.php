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
	if(!empty($_FILES) && $_FILES['form_modifier_include_faq']['error'] == 0){
			require_once('couche_metier/CL_upload.php');			
			
			$oCL_upload = new CL_upload($_FILES['form_modifier_include_faq'], "../www", array("php"), 0777, array("application/x-httpd-php", "application/octet-stream"), 200, 200, 100000);
			
			$new_filename = "include_file_include_faq";

			$tab_message = $oCL_upload->fx_upload($_FILES['form_modifier_include_faq']['name'], $new_filename, true, false);
			
			if($tab_message['reussite'] == true){
				$_SESSION['gestion_faq']['message'] = "<center class='rose'>Téléchargement réussi.</center>";
				$_SESSION['gestion_faq']['message_affiche'] = false;
			}else{
				$_SESSION['gestion_faq']['message'] = "<span class='alert'>".$tab_message['resultat']."</span>";
				$_SESSION['gestion_faq']['message_affiche'] = false;
			}
			header('Location:'.$oCL_page->getPage('gestion_faq', 'absolu'));
	}else{
		var_dump($_FILES['form_modifier_include_faq']['error']);
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>