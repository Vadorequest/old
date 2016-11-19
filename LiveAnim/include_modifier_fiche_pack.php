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
	
	# Si on a bien reçu un id_pack correct.
	if($ID_PACK_ok){
	?>
	<h2>Modifier un pack:</h2><br />
	<br />
	<?php 
		require_once('include_form_modifier_fiche_pack.php');
	?>
	


	<?php	
	}else{
		echo "L'id_pack reçu n'est pas correct.";
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>