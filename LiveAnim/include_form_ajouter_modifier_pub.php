<form class="formulaire" name="form_ajouter_modifier_pub" id="form_ajouter_modifier_modifier_pub" method="post" action="<?php if($formulaire == "Ajout"){echo "script_form_ajouter_pub.php";}else if($formulaire == "Modification"){echo "script_form_modifier_pub.php";} ?>">
	<?php
	if($formulaire == "Modification"){
	?>
		<input type="hidden" name="form_ajouter_modifier_pub_id_pub" value="<?php echo $pub[0]['ID_PUB']; ?>" />
	<?php
	}
	?>
	<label for="form_ajouter_modifier_pub_titre">Titre:</label><br />
	<input onblur="fx_verif_champ_simple('titre', 'form_ajouter_modifier_pub_titre')" type="text" name="form_ajouter_modifier_pub_titre" id="form_ajouter_modifier_pub_titre" value="<?php if(isset($_SESSION['ajouter_pub']['TITRE'])){echo $_SESSION['ajouter_pub']['TITRE'];}else if($formulaire == "Modification"){echo $pub[0]['TITRE'];} ?>" required /><br />
	<div id="titre"></div>
	<br />
	<label for="form_ajouter_modifier_pub_contenu">Contenu:</label> <span class="petit">(Code HTML/CSS/JS accepté)</span><br />
	<textarea onblur="fx_verif_champ_simple('contenu', 'form_ajouter_modifier_pub_contenu')" rows="5" cols="80" name="form_ajouter_modifier_pub_contenu" id="form_ajouter_modifier_pub_contenu" required ><?php if(isset($_SESSION['ajouter_pub']['CONTENU'])){echo $_SESSION['ajouter_pub']['CONTENU'];}else if($formulaire == "Modification"){echo $pub[0]['CONTENU'];} ?></textarea><br />
	<div id="contenu"></div>
	<br />
	<label for="form_ajouter_modifier_pub_position">Position:</label><br />
	<select name="form_ajouter_modifier_pub_position" id="form_ajouter_modifier_pub_position" required >
		<option value="1" <?php if(isset($_SESSION['ajouter_pub']['POSITION']) && $_SESSION['ajouter_pub']['POSITION'] == 1){echo "selected='selected'";}else if($formulaire == "Modification" && $pub[0]['POSITION'] == 1){echo "selected='selected'";} ?>>Sous le slider</option>
		<option value="2" <?php if(isset($_SESSION['ajouter_pub']['POSITION']) && $_SESSION['ajouter_pub']['POSITION'] == 2){echo "selected='selected'";}else if($formulaire == "Modification" && $pub[0]['POSITION'] == 2){echo "selected='selected'";} ?>>Tout en bas</option>
	</select><br />
	<br />
	<br />
	<center>
		<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Valider" title="Valider" />
	</center>
	<br />
</form>