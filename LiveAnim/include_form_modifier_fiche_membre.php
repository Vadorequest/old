<?php
if(isset($_SESSION['modification_fiche_membre']['message']) && $_SESSION['modification_fiche_membre']['message_affiche'] == false){
	echo "<center>".$_SESSION['modification_fiche_membre']['message']."</center><br /><br />";
	$_SESSION['modification_fiche_membre']['message_affiche'] = true;
}
?>


<form class="formulaire" action="script_form_modifier_fiche_membre.php" method="post" name="form_fiche_membre" id="form_fiche_membre" enctype="multipart/form-data">
	<center><h5><?php echo $personne[0]['PSEUDO']; ?>, <?php echo $personne[0]['TYPE_PERSONNE']; ?>.</h5><br /></center>
	<?php
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	?>
	<center><span class='petit'>(ID N°<?php echo $personne[0]['ID_PERSONNE']; ?>)</span></center><br />
	<br />
	<?php
	}
	?>
	<hr />
	<input type="hidden" name="form_fiche_membre_id_personne" id="form_fiche_membre_id_personne" value="<?php echo $personne[0]['ID_PERSONNE']; ?>" />
	<input type="hidden" name="form_fiche_membre_pseudo" id="form_fiche_membre_pseudo" value="<?php echo $personne[0]['PSEUDO']; ?>" />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Informations personnelles:</legend><br />
	<br />
	<?php 
		if(!empty($personne[0]['URL_PHOTO_PRINCIPALE'])){
		?>
			<span class="fright image_border">	
				<img src="<?php echo $personne[0]['URL_PHOTO_PRINCIPALE']; ?>" title="<?php echo $personne[0]['PSEUDO']; ?>" alt="<?php echo $personne[0]['PSEUDO']; ?>" width="93" height="117" />
			</span>
		<?php
		} 
	?>
	<span class="alert">*</span><label for="form_fiche_membre_nom">Nom: </label><br /><input onblur="fx_verif_champ_simple('nom', 'form_fiche_membre_nom');" type="text" name="form_fiche_membre_nom" id="form_fiche_membre_nom" value="<?php echo $personne[0]['NOM']; ?>" size="60" required /><br />
	<div id="nom"></div>
	<br />
	<span class="alert">*</span><label for="form_fiche_membre_prenom">Prénom: </label><br /><input onblur="fx_verif_champ_simple('prenom', 'form_fiche_membre_prenom');" type="text" name="form_fiche_membre_prenom" id="form_fiche_membre_prenom" value="<?php echo $personne[0]['PRENOM']; ?>" size="60" required /><br />
	<div id="prenom"></div>
	<br />
	<span class="alert">*</span>Civilité: 	<br /><select name="form_fiche_membre_civilite" id="form_fiche_membre_civilite">
					<option value="Mr" <?php if($personne[0]['CIVILITE'] == "Mr"){echo "selected='selected'";} ?>>Monsieur</option>
					<option value="Mme" <?php if($personne[0]['CIVILITE'] == "Mme"){echo "selected='selected'";} ?>>Madame</option>
					<option value="Mlle" <?php if($personne[0]['CIVILITE'] == "Mlle"){echo "selected='selected'";} ?>>Mademoiselle</option>
				</select><br />
	<br />
	<span class="alert">*</span><label for="form_fiche_membre_date_naissance">Né(e) le: </label><br /><input onblur="fx_verif_champ_date('date_naissance', 'form_fiche_membre_date_naissance', 0);" type="text" name="form_fiche_membre_date_naissance" id="form_fiche_membre_date_naissance" value="<?php echo $personne[0]['DATE_NAISSANCE']; ?>" required /><br />
	<div id="date_naissance"></div>
	<?php 
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	?>
	<br />
	<center>
		<label for="form_fiche_membre_url_photo_principale">URL de la photo: </label><br /><textarea name="form_fiche_membre_url_photo_principale" id="form_fiche_membre_url_photo_principale" cols="80" rows="5"><?php echo $personne[0]['URL_PHOTO_PRINCIPALE']; ?></textarea><br />
		<br /><b><u  title="L'image téléchargée est prioritaire sur l'URL. Vous pouvez modifier votre avatar en mettant l'url de la nouvelle image ou bien en la téléchargant depuis votre ordinateur.">Ou:</u></b><br /><br />
		Télécharger une nouvelle photo: <span class="petit noir">(2 Mo maximum)</span><br />
		<input type="file" name="form_fiche_membre_nouvelle_photo_principale" /><br />
	</center>
	<?php
	}else{
		# Si c'est un Organisateur, pas de photo !
	}
	?>
	<br />
	<span class="alert">*</span><label for="form_fiche_membre_email">Email: </label><br /><input onblur="fx_verif_champ_email('email', 'form_fiche_membre_email');" type="email" name="form_fiche_membre_email" id="form_fiche_membre_email" value="<?php echo $personne[0]['EMAIL']; ?>" size="40" required /><br />
	<div id="email"></div>
	<br />
	<label for="form_fiche_membre_tel_fixe">Téléphone: </label><br /><input placeholder="Ex : 04.12.68.97.22" type="tel" name="form_fiche_membre_tel_fixe" id="form_fiche_membre_tel_fixe" value="<?php echo $personne[0]['TEL_FIXE']; ?>" /><br />
	<br />
	<label for="form_fiche_membre_tel_portable">Portable: </label><br /><input placeholder="Ex : 06 51 78 96 32" type="tel" name="form_fiche_membre_tel_portable" id="form_fiche_membre_tel_portable" value="<?php echo $personne[0]['TEL_PORTABLE']; ?>" /><br />
	<br />
	</fieldset>
	<br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Informations relatives à l'adresse:</legend><br />
	<label for="form_fiche_membre_adresse">Adresse </label><span class="petit">(Rue, chemin, ...)</span>: <br /><input type="text" name="form_fiche_membre_adresse" id="form_fiche_membre_adresse" value="<?php echo $personne[0]['ADRESSE']; ?>" size="60" /><br />
	<br />
	<label for="form_fiche_membre_cp">Code postal: </label><br /><input type="text" name="form_fiche_membre_cp" id="form_fiche_membre_cp" value="<?php if($personne[0]['CP'] != 0){echo $personne[0]['CP'];} ?>" size="10" /><br />
	<br />
	<label for="form_fiche_membre_ville">Ville: </label><br /><input type="text" name="form_fiche_membre_ville" id="form_fiche_membre_ville" value="<?php echo $personne[0]['VILLE']; ?>" size="60" /><br />
	<br />
	</fieldset>
	<br />
	<br />
	
	<fieldset class="padding_LR"><legend class="legend_basique">Informations complémentaires:</legend><br />
	Parrain: 
	<?php  
	if($personne[0]['PARRAIN'] != "Aucun"){
		?><a class='noir' href="?id_personne=<?php echo $parrain[0]['ID_PERSONNE']; ?>"><?php echo $parrain[0]['CIVILITE']." ".$parrain[0]['NOM']." ".$parrain[0]['PRENOM'].". (".$parrain[0]['PSEUDO'].")" ?></a><br />
		
	<?php
	}else{
		echo "<span class='noir'>Aucun parrain.</span><br />";
	}
	
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	?>
		<br />
		<label for="form_fiche_membre_reduction">Total de réduction possédé: </label><br /><input type="text" name="form_fiche_membre_reduction" id="form_fiche_membre_reduction" value="<?php echo $personne[0]['REDUCTION']; ?>" size="2" />&nbsp;<span class='petit'>(En %)</span><br />
		<br />
	<?php
	}else{
	?>
		<br />
		Total de réduction possédé: <span class="noir"><?php echo $personne[0]['REDUCTION']; ?>%</span><br />
		<br />
	<?php
	}
	?>
	<br />
	<span class="alert">*</span>Accepte les newsletter: 
		<select name="form_fiche_membre_newsletter" id="form_fiche_membre_newsletter">
			<option value="1" <?php if($personne[0]['NEWSLETTER'] == true){echo "selected='selected'";} ?>>Oui</option>
			<option value="0" <?php if($personne[0]['NEWSLETTER'] == false){echo "selected='selected'";} ?>>Non</option>
		</select><br />
	<span class="alert">*</span>Accepte les offres de nos annonceurs: 
		<select name="form_fiche_membre_offres_annonceurs" id="form_fiche_membre_offres_annonceurs">
			<option value="1" <?php if($personne[0]['OFFRES_ANNONCEURS'] == true){echo "selected='selected'";} ?>>Oui</option>
			<option value="0" <?php if($personne[0]['OFFRES_ANNONCEURS'] == false){echo "selected='selected'";} ?>>Non</option>
		</select><br />
	A connu le site grâce à: <span class="noir"><?php echo $personne[0]['CONNAISSANCE_SITE']; ?></span><br />
	<br />
	<br />
	</fieldset>
	<br />
	<?php  
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin" || $_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
	?>
		<br />
		<fieldset class="padding_LR"><legend class="legend_basique">Informations spécifiques:</legend><br />
		<label for="form_fiche_membre_description">Description: </label><br /><textarea name="form_fiche_membre_description" id="form_fiche_membre_description" cols="80" rows="5"><?php echo $personne[0]['DESCRIPTION']; ?></textarea><br />
		<br />
		Statut: <br />
			<select name="form_fiche_membre_statut" id="form_fiche_membre_statut">
				<?php
				foreach($statuts as $key=>$statut){
				?>
					<option value="<?php echo $statut['ID_TYPES']; ?>" <?php if($statut['ID_TYPES'] == $personne[0]['STATUT_PERSONNE']){echo "selected='selected'";} ?>><?php echo $statut['ID_TYPES']; ?></option>
				<?php
				}
				?>
			</select><br />
		<br />
		<label for="form_fiche_membre_roles">Qelles sont vos qualifications ? <span class="petit noir">(Plusieurs choix possibles via la touche ctrl)</span> </label><br />
		<select name="form_fiche_membre_roles[]" id="form_fiche_membre_roles" class="multiselect" multiple="multiple">
			<?php
			foreach($roles as $key=>$role){
			?>
				<option value="<?php echo $role['ID_TYPES']; ?>" 
					<?php
					# On regarde si l'utilisateur a un rôle.
					if(!empty($ROLES[0])){
						# On regarde chaque ROLE et on voit s'il est égal avec le role en cours.
						foreach($ROLES as $KEY=>$ROLE){
							if($ROLE == $role['ID_TYPES']){
								echo "selected='selected'";
							}
						}
					}else{
						# Sinon alors on lui attribue le premier de la liste.
						if($role['ID_TYPES'] == "Animateur"){
							echo "selected='selected'";
						}
					}
					?>
					><?php echo $role['ID_TYPES']; ?></option>
			<?php
			}
			?>
		</select>
		<br />
		<br />
		<?php
		# Si admin ou si le nombre de départements que le pack permet d'alerter est non nul.
		if($_SESSION['compte']['TYPE_PERSONNE'] == 'Admin' || $_SESSION['pack']['NB_DEPARTEMENTS_ALERTE'] > 0){
		?>
		<label for="form_fiche_membre_departements">Départements surveillés: <?php if($_SESSION['compte']['TYPE_PERSONNE'] == 'Prestataire'){?><br /><span class="petit noir">(Votre pack actuel vous permet de surveiller <?php echo $_SESSION['pack']['NB_DEPARTEMENTS_ALERTE']; ?> départements, séparez les par une virgule)</span><?php } ?></label><br /><input type="text" name="form_fiche_membre_departements" id="form_fiche_membre_departements" value="<?php echo $personne[0]['DEPARTEMENTS']; ?>" size="60" placeholder="Ex : 11, 13, 54, 77" /><br />
		<br />
		<?php
		}
		?>						
		<label for="form_fiche_membre_siret">N° de SIRET: </label><br /><input onblur="fx_verif_champ_siret('siret', 'form_fiche_membre_siret');" type="text" name="form_fiche_membre_siret" id="form_fiche_membre_siret" value="<?php echo $personne[0]['SIRET']; ?>" size="60" placeholder="Ex : 254 365 125 98751" /><br />
		<div id="siret"></div>
		<br />
		<label for="form_fiche_membre_tarifs">Informations concernant vos tarifs: </label><br /><textarea name="form_fiche_membre_tarifs" id="form_fiche_membre_tarifs" cols="80" rows="5"><?php echo $personne[0]['TARIFS']; ?></textarea><br />
		<br />
		<label for="form_fiche_membre_distance_prestation_max">Distance maximale pour une prestation: </label><br /><input type="text" name="form_fiche_membre_distance_prestation_max" id="form_fiche_membre_distance_prestation_max" value="<?php echo $personne[0]['DISTANCE_PRESTATION_MAX']; ?>" size="6" placeholder="Ex : 120" /> Km.<br />
		<br />Vidéo: <br />
		<?php 
		if(!empty($personne[0]['CV_VIDEO'])){
		?>
			<object type="application/x-shockwave-flash" width="100%" height="355" data="<?php echo $personne[0]['CV_VIDEO']; ?>">
				<param name="movie" value="<?php echo $personne[0]['CV_VIDEO']; ?>">
				<param name="wmode" value="transparent">
			</object>
		<?php
		}else{
			echo "<span class='noir'>Aucune vidéo.</span>";
		}
		?>
		<br />
		<br />
		<label for="form_fiche_membre_cv_video">URL de la vidéo: <span class="petit orange">(Youtube uniquement pour le moment)</span></label><br /><textarea name="form_fiche_membre_cv_video" id="form_fiche_membre_cv_video" cols="80" rows="5"><?php echo $personne[0]['CV_VIDEO']; ?></textarea><br />
		<br />
		<label for="form_fiche_membre_materiel">Descriptif du matériel: </label><br />
		<textarea name="form_fiche_membre_materiel" id="form_fiche_membre_materiel" cols="80" rows="5"><?php echo $personne[0]['MATERIEL']; ?></textarea><br />
		<br />
	<?php
	}# Fin du if de test du TYPE_PERSONNE.
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	?>
		<br />
		<fieldset class="padding_LR"><legend class="legend_basique">Informations de modération:</legend><br />
		Compte activé: <span class="noir"><?php if($personne[0]['CLE_ACTIVATION'] == ""){echo "Oui.";}else{echo "Non.";} ?></span><br />
		Compte banni: <span class="noir"><?php if($personne[0]['VISIBLE'] == false && $personne[0]['PERSONNE_SUPPRIMEE'] == true && $personne[0]['DATE_BANNISSEMENT'] >= date("Y-m-d")){echo "Oui.";}else{echo "Non.";} ?></span><br />
		Compte supprimé: <span class="noir"><?php if($personne[0]['VISIBLE'] == false && $personne[0]['PERSONNE_SUPPRIMEE'] == true && $personne[0]['DATE_BANNISSEMENT'] < date("Y-m-d")){echo "Oui.";}else{echo "Non.";} ?></span><br />
		Raison de la suppression/bannissement: <br />
		<span class="noir"><?php echo $personne[0]['RAISON_SUPPRESSION']; ?><br /></span><br />
		</fieldset>
		<br />
	<?php
	}
	?>
	<br />
	<span class="fright alert">*&nbsp;Informations obligatoires.</span><br />
	<br />
	<center>
		<?php
		if($_SESSION['compte']['TYPE_PERSONNE'] != "Admin"){
		?>
			<label for="form_fiche_membre_mdp">Veuillez rentrer votre mot de passe actuel avant de valider.</label><br />
			<input type="password" name="form_fiche_membre_mdp" id="form_fiche_membre_mdp" /><br />
			<br />
		<?php
		}
		?>
		<input type="image" src="images/valider.png" alt="Valider" />
	</center>
	</fieldset>
</form>
