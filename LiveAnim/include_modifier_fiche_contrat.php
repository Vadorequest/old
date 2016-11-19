<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
	if($id_contrat_ok){
?>
	<h2>Modification du contrat N°<?php echo $contrat[0]['ID_CONTRAT']; ?></h2><br />
	<br />
	
	<?php
	$formulaire = "modifier";
	require_once('include_form_ajouter_modifier_contrat.php');
	?>

<?php
	}else{
		if(isset($_SESSION['contrat']['message']) && $_SESSION['contrat']['message_affiche'] == false){
			echo $_SESSION['contrat']['message'];
			$_SESSION['contrat']['message_affiche'] = true;
		}
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>