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
	<h2>Gestion des Mentions Légales:</h2><br />
	<br />
	<?php
	if(isset($_SESSION['gestion_mentions_legales']['message']) && $_SESSION['gestion_mentions_legales']['message_affiche'] == false){
		echo $_SESSION['gestion_mentions_legales']['message']."<br />";
		$_SESSION['gestion_mentions_legales']['message_affiche'] = true;
	}
	?>
	<fieldset class="padding_LR"><legend class="legend_basique">Télécharger les nouvelles Mentions Légales:</legend>
		<br />
		<form class="formulaire" enctype="multipart/form-data" name="modifier_mentions_legales" id="modifier_mentions_legales" method="post" action="script_modifier_mentions_legales">
			Téléchargez les nouvelles mentions légales: <span class="petit orange">(PDF obligé)</span><br />
			<input type="file" name="form_modifier_mentions_legales" id="form_modifier_mentions_legales" required /><br />
			<br />
			<center>
				<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Valider" />
			</center>
		</form>
		<br />
	</fieldset>
	
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>