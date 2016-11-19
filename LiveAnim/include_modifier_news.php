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
?>
	<h2>Modification de la news <?php echo $nouveautee[0]['TITRE']; ?>:</h2><br />
	<br />
	
	<?php
	if(isset($_SESSION['modifier_news']['message']) && $_SESSION['modifier_news']['message_affiche'] == false){
		echo $_SESSION['modifier_news']['message'];
		$_SESSION['modifier_news']['message_affiche'];
	}
	?>
	
	<?php
	$formulaire = "modifier";
	require_once('include_form_ajouter_modifier_news.php');
	?>
	


<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>