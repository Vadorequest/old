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
	<h2>Envoi de messages:</h2><br />
	<br />
	<?php
	if(isset($_SESSION['envoyer_message_admin']['message']) && $_SESSION['envoyer_message_admin']['message_affiche'] == false){
		echo $_SESSION['envoyer_message_admin']['message']."<br />";
		$_SESSION['envoyer_message_admin']['message_affiche'] = true;
	}
	?>
	<fieldset class="padding_LR"><legend class="legend_basique">Envoi de messages:</legend><br />
		<form class="formulaire" name="form_envoyer_message_admin" action="script_envoyer_message_admin.php" method="post">
			Vous souhaitez envoyer:<br />&nbsp;<select name="form_envoyer_message_admin_type_message" id="form_envoyer_message_admin_type_message">
													<option value="mail">Un e-mail</option>
													<option value="mp">Un message personnel via le site</option>
												</select><br />
			<br />
			à:<br />
			<select onchange="verif_choix('form_envoyer_message_admin_destinataires');" name="form_envoyer_message_admin_destinataires" id="form_envoyer_message_admin_destinataires" required>
				<option value="1" <?php if(isset($_SESSION['envoyer_message_admin']['destinataires']) && $_SESSION['envoyer_message_admin']['destinataires'] == 1){echo "selected='selected'";} ?>>Un membre en particulier</option>
				<option class="valide" value="2" <?php if(isset($_SESSION['envoyer_message_admin']['destinataires']) && $_SESSION['envoyer_message_admin']['destinataires'] == 2){echo "selected='selected'";} ?>>Tous les organisateurs</option>
				<option class="rose" value="3" <?php if(isset($_SESSION['envoyer_message_admin']['destinataires']) && $_SESSION['envoyer_message_admin']['destinataires'] == 3){echo "selected='selected'";} ?>>Tous les prestataires</option>
				<option class="alert" value="4" <?php if(isset($_SESSION['envoyer_message_admin']['destinataires']) && $_SESSION['envoyer_message_admin']['destinataires'] == 4){echo "selected='selected'";} ?>>Tous les admins</option>	
			</select><br />
			<div id="destinataire">
			<br />
			Sélectionnez le membre à qui envoyer le message:<br />
			<select name="form_envoyer_message_admin_destinataire" required>

				<optgroup label="Organisateurs">
				<?php
				foreach($organisateurs as $key=>$personne){
				?>
					<option class="valide" value="<?php echo $personne['ID_PERSONNE']; ?>" <?php if(isset($_SESSION['envoyer_message_admin']['DESTINATAIRE']) && $_SESSION['envoyer_message_admin']['DESTINATAIRE'] == $personne['ID_PERSONNE']){echo "selected='selected'";} ?>><?php echo $personne['PSEUDO']." (".$personne['CIVILITE'].". ".$personne['NOM']." ".$personne['PRENOM'].")"; ?></option>
				<?php
				}
				?>
				</optgroup>
				<optgroup label="Prestataires & Artistes">
				<?php
				foreach($prestataires as $key=>$personne){
				?>
					<option class="rose" value="<?php echo $personne['ID_PERSONNE']; ?>" <?php if(isset($_SESSION['envoyer_message_admin']['DESTINATAIRE']) && $_SESSION['envoyer_message_admin']['DESTINATAIRE'] == $personne['ID_PERSONNE']){echo "selected='selected'";} ?>><?php echo $personne['PSEUDO']." (".$personne['CIVILITE'].". ".$personne['NOM']." ".$personne['PRENOM'].")"; ?></option>
				<?php
				}
				?>
				</optgroup>
				<optgroup label="Administrateurs">
				<?php
				foreach($administrateurs as $key=>$personne){
				?>
					<option class="alert" value="<?php echo $personne['ID_PERSONNE']; ?>" <?php if(isset($_SESSION['envoyer_message_admin']['DESTINATAIRE']) && $_SESSION['envoyer_message_admin']['DESTINATAIRE'] == $personne['ID_PERSONNE']){echo "selected='selected'";} ?>><?php echo $personne['PSEUDO']." (".$personne['CIVILITE'].". ".$personne['NOM']." ".$personne['PRENOM'].")"; ?></option>
				<?php
				}
				?>
				</optgroup>
			</select>
			</div>
			<br />
			<label for="form_envoyer_message_admin_titre">Titre du message:</label><span class="petit orange">(Pas de HTML !)</span><br />
			<input type="text" name="form_envoyer_message_admin_titre" id="form_envoyer_message_admin_titre" value="<?php if(isset($_SESSION['envoyer_message_admin']['TITRE'])){echo $_SESSION['envoyer_message_admin']['TITRE'];} ?>" required /><br />
			<br />
			<label for="form_envoyer_message_admin_message">Ecrivez votre message:</label><span class="petit valide">(Html possible ! CSS en dur.)</span><br />
			<textarea onkeyup="fx_previsualiser('form_envoyer_message_admin_message', 'previsualisation_message');" rows="15" cols="80" name="form_envoyer_message_admin_message" id="form_envoyer_message_admin_message" required><?php if(isset($_SESSION['envoyer_message_admin']['MESSAGE'])){echo $_SESSION['envoyer_message_admin']['MESSAGE'];} ?></textarea><br />
			<br />
			<span class="petit noir">
			<u>Rappel de quelques balises:</u> Les [ sont à remplacer par des &lt;, idem pour les ]<br />
			<u>Balise style:</u> <br />[span style="color:red;font-size:15px;"]<span style="color:red;font-size:15px;">Coucou</span>[/span]<br />
			<br />
			<u>Balise image:</u> <br />[img src="http://mon_image.png" alt="Texte alternatif" title="Texte curseur" width="200px" height="150px" /]<br />
			<br />
			</span>
			<fieldset class="padding_LR noir"><legend class="legend_basique">Prévisualisation:&nbsp;&nbsp;</legend>
				<br />
				<div id="previsualisation_message"></div>
				<br />
			</fieldset><br />
			<br />
			<center>
				<input onclick="return confirm('Voulez-vous envoyer ce message ?');" type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Valider" />
			</center>
			<br />
		</form>
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>