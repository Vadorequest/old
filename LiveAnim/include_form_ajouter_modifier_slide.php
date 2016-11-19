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
	<form class="formulaire" enctype="multipart/form-data" action="<?php if($formulaire == "modification"){echo "script_form_modifier_slide.php";}else{echo "script_form_ajouter_slide.php";} ?>" method="POST" name="form_ajouter_modifier_slide" id="form_ajouter_modifier_slide">
	<?php
	if($formulaire == "modification"){
	?>
		<input type="hidden" name="form_ajouter_modifier_slide_id_slide" id="form_ajouter_modifier_slide_id_slide" value="<?php echo $slide[0]['ID_SLIDE']; ?>" />
	<?php
	}
	?>
	<br />
	<label for="form_ajouter_modifier_slide_titre"><span class="alert">* </span>Titre:</label><br />
	<input onblur="fx_verif_champ_simple('div_titre', 'form_ajouter_modifier_slide_titre');" type="text" name="form_ajouter_modifier_slide_titre" id="form_ajouter_modifier_slide_titre" value="<?php if($formulaire == "modification"){echo $slide[0]['TITRE'];}else if($formulaire == "ajout" && isset($_SESSION['gestion_slides']['TITRE'])){echo $_SESSION['gestion_slides']['TITRE'];} ?>" size="20" required /><br />
	<div id="div_titre"></div>
	<br />
	<label for="form_ajouter_modifier_slide_url"><span class="alert">* </span>Téléchargez le slide:</label><br />
	<input type="file" name="form_ajouter_modifier_slide_url" id="form_ajouter_modifier_slide_url" <?php if($formulaire == "ajout"){echo "required";}?> /><br />
	<br />
	<label for="form_ajouter_modifier_slide_lien"><span class="alert">* </span>Lien vers lequel pointe le slide:</label><br />
	<textarea rows="5" cols="80" onblur="fx_verif_champ_simple('div_lien', 'form_ajouter_modifier_slide_lien');" name="form_ajouter_modifier_slide_lien" id="form_ajouter_modifier_slide_lien" required><?php if($formulaire == "modification"){echo $slide[0]['LIEN'];}else if($formulaire == "ajout" && isset($_SESSION['gestion_slides']['LIEN'])){echo $_SESSION['gestion_slides']['LIEN'];} ?></textarea><br />
	<br />
	<div id="div_lien"></div>
	<br />
	<label for="form_ajouter_modifier_slide_classe"><span class="alert">* </span>Définissez la classe CSS à appliquer:</label><br />
	<input onblur="fx_verif_champ_simple('div_classe', 'form_ajouter_modifier_slide_classe');" type="text" name="form_ajouter_modifier_slide_classe" id="form_ajouter_modifier_slide_classe" value="<?php if($formulaire == "modification"){echo $slide[0]['CLASSE'];}else if($formulaire == "ajout" && isset($_SESSION['gestion_slides']['CLASSE'])){echo $_SESSION['gestion_slides']['CLASSE'];}else{echo "slide";} ?>" size="4" required /><br />
	<div id="div_classe"></div>
	<br />
	<label for="form_ajouter_modifier_slide_ordre"><span class="alert">* </span>Définissez l'ordre d'apparition du slide: <br /><span class="petit">(Plus le chiffre est petit et plus le slide apparaîtra en premier)</span></label><br />
	<input onblur="fx_verif_champ_simple('div_ordre', 'form_ajouter_modifier_slide_ordre');" type="text" name="form_ajouter_modifier_slide_ordre" id="form_ajouter_modifier_slide_ordre" value="<?php if($formulaire == "modification"){echo $slide[0]['ORDRE'];}else if($formulaire == "ajout" && isset($_SESSION['gestion_slides']['ORDRE'])){echo $_SESSION['gestion_slides']['ORDRE'];}else{echo "0";} ?>" size="2" required /><br />
	<div id="div_ordre"></div>
	<br />
	<label for="form_ajouter_modifier_slide_access"><span class="alert">* </span>Définissez qui pourra voir le slide:</label><br />
	<select name="form_ajouter_modifier_slide_access[]" id="form_ajouter_modifier_slide_access" multiple required>
		<option value="Non connectés" <?php if($formulaire == "modification"){if(in_array('Non connectés', $slide[0]['ACCES'])){echo "selected='selected'";}}else if($formulaire == "ajout" && isset($_SESSION['gestion_slides']['ACCES'])){if(in_array('Non connectés', $_SESSION['gestion_slides']['ACCES'])){echo "selected='selected'";}} ?>>Non connectés</option>
		<option value="Prestataire" <?php if($formulaire == "modification"){if(in_array('Prestataire', $slide[0]['ACCES'])){echo "selected='selected'";}}else if($formulaire == "ajout" && isset($_SESSION['gestion_slides']['ACCES'])){if(in_array('Prestataire', $_SESSION['gestion_slides']['ACCES'])){echo "selected='selected'";}} ?>>Prestataire</option>
		<option value="Organisateur" <?php if($formulaire == "modification"){if(in_array('Organisateur', $slide[0]['ACCES'])){echo "selected='selected'";}}else if($formulaire == "ajout" && isset($_SESSION['gestion_slides']['ACCES'])){if(in_array('Organisateur', $_SESSION['gestion_slides']['ACCES'])){echo "selected='selected'";}} ?>>Organisateur</option>
		<option value="Admin" <?php if($formulaire == "modification"){if(in_array('Admin', $slide[0]['ACCES'])){echo "selected='selected'";}}else if($formulaire == "ajout" && isset($_SESSION['gestion_slides']['ACCES'])){if(in_array('Admin', $_SESSION['gestion_slides']['ACCES'])){echo "selected='selected'";}} ?>>Admin</option>
	</select>
	<br />	
	<?php
	if($formulaire == "modification"){
	?>
		<br />
		<label for="form_ajouter_modifier_slide_visible"><span class="alert">* </span>Slide visible:</label> <input type="checkbox" name="form_ajouter_modifier_slide_visible" id="form_ajouter_modifier_slide_visible" <?php if($slide[0]['VISIBLE']){echo "checked='checked'";}; ?> />
		<br />
	<?php
	}
	?>
	<center>
		<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Valider" title="Valider" />
	</center>
	</form>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>