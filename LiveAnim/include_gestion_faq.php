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
	<center> <img style="" alt="FAQ"
 src="images/gestion-faq.png"></center>
<br />
<br />

	<?php
	if(isset($_SESSION['gestion_faq']['message']) && $_SESSION['gestion_faq']['message_affiche'] == false){
		echo $_SESSION['gestion_faq']['message']."<br />";
		$_SESSION['gestion_faq']['message_affiche'] = true;
	}
	?>
	<br />
	Note: Les fichiers nécessitent une extension .php, mais le code à l'intérieur peut très bien être du HTML sans aucun code php.<br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Télécharger le menu horizontal de la FAQ: </legend>
		<br />
		<span class="noir petit">(Le menu de gauche qui présente les diverses parties)</span><br />
		<br />
		<form class="formulaire" enctype="multipart/form-data" name="modifier_menuh_faq" id="modifier_menuh_faq" method="post" action="script_modifier_menuh_faq.php">
			Téléchargez le nouveau menu horizontal de la FAQ: <span class="petit orange">(php obligé)</span><br />
			<input type="file" name="form_modifier_menuh_faq" id="form_modifier_menuh_faq" required /><br />
			<br />
			<center>
				<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Valider" />
			</center>
		</form>
		<br />
	</fieldset>
	<br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Télécharger le corps de la FAQ: </legend>
		<br />
		<span class="noir petit">(Le texte de droite qui explique tout)</span><br />
		<br />
		<form class="formulaire" enctype="multipart/form-data" name="modifier_include_faq" id="modifier_include_faq" method="post" action="script_modifier_include_faq.php">
			Téléchargez le nouveau corps de la FAQ: <span class="petit orange">(php obligé)</span><br />
			<input type="file" name="form_modifier_include_faq" id="form_modifier_include_faq" required /><br />
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