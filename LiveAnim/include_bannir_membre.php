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

	if(isset($_GET['id_personne'])){
		$ID_PERSONNE = (int)$_GET['id_personne'];
	}
?>
		<h2>Bannir un membre:</h2>
		<br />
		<br />
		Vous pouvez effectuer un bannissement temporaire ou définitif du membre sélectionné. <br />
		<span class='petit'>(Date de fin de bannissement proche de 2020 si définitif.)</span><br />
		<br />
		
		<fieldset><legend class="legend_basique">Formulaire de bannissement d'un membre.</legend>
			<form class="formulaire" action="script_bannissement_membre.php" method="post" id="form_bannissement" name="form_bannissement"><br />
				<?php
					if(isset($_SESSION['bannir_membre']['message']) && $_SESSION['bannir_membre']['message_affiche'] == false){
						echo $_SESSION['bannir_membre']['message'];
						$_SESSION['bannir_membre']['message_affiche'] = true;
					}
				?>
				
				<br />
				Choisissez le membre à modérer:<br />
				<select id="form_bannissement_id_personne" name="form_bannissement_id_personne">
					<?php
					var_dump($membres);
					foreach($membres as $key=>$membre){
					?>
						<option value="<?php echo $membre['ID_PERSONNE']; ?>" <?php if(isset($ID_PERSONNE) && $ID_PERSONNE == $membre['ID_PERSONNE']){echo "selected='selected'";} ?>><?php echo $membre['PSEUDO']; ?></option>
					<?php
					}
					?>
				</select>
				<br />
				<br />
				
				<label for="form_bannissement_personne_supprimee">Ban définitif:&nbsp;</label><input type="checkbox" name="form_bannissement_personne_supprimee" id="form_bannissement_personne_supprimee" />
				<br />
				<br />
				
				<label for="form_bannissement_duree">Durée: <span class="petit">(En jours)</span></label><br />
				<input type="text" name="form_bannissement_duree" id="form_bannissement_duree" size="5" /><span class="petit">&nbsp;(Inutile si ban définitif)</span>
				<br />
				<br />
				
				
				
				<label for="form_bannissement_raison">Raison du bannissement:</label><br />
				<textarea id="form_bannissement_raison" name="form_bannissement_raison" cols="80" rows="8">Vous avez été banni par notre service de modération pour la raison suivante: </textarea><br />
				<br />
				
				<center>
					<a><img src="images/previsualiser.jpg" alt="Prévisualiser" onclick="fx_previsualiser('form_bannissement_raison', 'preview');" /></a>
				</center>
				<br />
					<p id="preview">
					
					</p>
				<center>
					<input type="image" id="btn_form_bannissement_valider" name="btn_form_bannissement_valider" src="images/valider2.png" />
				</center>
			</form>
		</fieldset>
		

<?php	
}
?>