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
	<h2>Modification du slide N°<?php echo $slide[0]['ID_SLIDE']; ?></h2><br />
	<br />
	<?php
	if(isset($_SESSION['modifier_slide']['message']) && $_SESSION['modifier_slide']['message_affiche'] == false){
		echo $_SESSION['modifier_slide']['message']."<br />";
		$_SESSION['modifier_slide']['message_affiche'] = true;
	}
	?>
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Modification du slide:</legend>
		<br />
		<?php 
			$formulaire = "modification";
			require_once('include_form_ajouter_modifier_slide.php'); 
		?>
		<br />
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>