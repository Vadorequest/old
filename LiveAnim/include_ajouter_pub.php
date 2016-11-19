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
	<h2>Ajouter une pub:</h2><br />
	<br />
	<?php
	if(isset($_SESSION['ajouter_pub']['message']) && $_SESSION['ajouter_pub']['message_affiche'] == false){
		echo $_SESSION['ajouter_pub']['message'];
		$_SESSION['ajouter_pub']['message_affiche'] = true;
	}
	?>
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Ajouter une pub:&nbsp;</legend><br />
		<br />
		<?php
		$formulaire = "Ajout";
		require_once('include_form_ajouter_modifier_pub.php');
		?>
		<br />
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>