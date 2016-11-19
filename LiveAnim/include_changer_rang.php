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
	<h2>Changer le rang:</h2><br />
	<br />
	<?php
		if(isset($_SESSION['administration']['message_affiche']) && $_SESSION['administration']['message_affiche'] == false){
			echo $_SESSION['administration']['message'];
			$_SESSION['administration']['message_affiche'] = true;
		}
	?>
	<br />
	<fieldset><legend class="legend_basique">Formulaire de modification du rang:</legend><br />
		<br />
		<form class="formulaire" action="script_changer_rang.php" method="post" id="" name="">
			Login/Pseudo du membre à modifier:<br />
			<input type="text" class="my_input" name="form_changer_rang_pseudo" id="form_changer_rang_pseudo" /><br />
			<br />
			
			Sélectionnez son nouveau rang:<br />
			<select class="my_input" name="form_changer_rang_type_personne" id="form_inscription_type_personne">
				<option value="Prestataire" <?php if(isset($_SESSION['changer_rang']['type_personne'])){ if($_SESSION['changer_rang']['type_personne'] == "Prestataire"){echo "selected='selected'";}} ?>>Prestataire / Artiste</option>
				<option value="Organisateur" <?php if(isset($_SESSION['changer_rang']['type_personne'])){ if($_SESSION['changer_rang']['type_personne'] == "Organisateur"){echo "selected='selected'";}} ?>>Organisateur de soirée</option>
				<option value="Admin" <?php if(isset($_SESSION['changer_rang']['type_personne'])){ if($_SESSION['changer_rang']['type_personne'] == "Admin"){echo "selected='selected'";}} ?>>Admin</option>
			</select><br />
			<br />
			<center>
				<input type="image" src="images/valider.png" id="btn_form_changer_rang_valider" name="btn_form_changer_rang_valider" />
			</center>
		</form>
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>