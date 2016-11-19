<?php
/*
	Formulaire d'ajout d'une annonce pour Organisateur.	
*/
/*
	Nous utilisons ['modifier_annonce'] que ce soit un admin ou non qui utilise la fiche.
	Ce formulaire regroupe la création, la modification par utilisateur et la modification par admin d'une annonce.
*/
?>
<script type="text/javascript" src="js/annonce.js"></script>
<br />
	<fieldset class="padding_LR"><legend class="legend_basique"><?php if(isset($annonce)){echo "Modification d'une annonce";}else{echo "Création d'une annonce";} ?></legend><br />
		<br />
		<form action="<?php if(isset($annonce) && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){echo "script_form_modifier_annonce_by_admin.php";}else if(isset($annonce)){echo "script_form_modifier_annonce.php";}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur"){echo "script_form_ajouter_annonce.php";} ?>" method="post" name="form_ajout_modification_annonce" id="form_ajout_modification_annonce" class="formulaire">
			<input type="hidden" name="form_ajout_modification_annonce_id_annonce" value="<?php echo $annonce[0]['ID_ANNONCE']; ?>" />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_titre">Titre de l'annonce:</label><br />
			<input onkeyup="fx_previsualiser_annonce();" onblur="fx_verif_champ_simple('titre', 'form_ajout_modification_annonce_titre');" type="text" name="form_ajout_modification_annonce_titre" id="form_ajout_modification_annonce_titre" size="40" value="<?php if(isset($annonce)){echo $annonce[0]['TITRE'];}else if(isset($_SESSION['ajouter_annonce']['TITRE'])){echo $_SESSION['ajouter_annonce']['TITRE'];} ?>" autofocus placeholder="Ex : Anniversaire de mon cousin" required  /><br />
			<div id="titre"></div>
			<br />
			<?php
			if(isset($annonce)){
			?>
				Date de création de l'annonce: <span class='noir'><?php echo $annonce[0]['DATE_ANNONCE']; ?>.</span><br />
				<br />
			<?php
			}
			?>
			<span class="alert">*</span><label for="form_ajout_modification_annonce_type_annonce">Type d'annonce:</label><br />
			<select onchange="fx_previsualiser_annonce();" name="form_ajout_modification_annonce_type_annonce" id="form_ajout_modification_annonce_type_annonce">
				<?php
				foreach($types_annonce as $key=>$type_annonce){
				?>
					<option value="<?php echo $type_annonce['ID_TYPES']; ?>" <?php if(isset($annonce[0]['TYPE_ANNONCE']) && $annonce[0]['TYPE_ANNONCE'] == $type_annonce['ID_TYPES']){echo "selected='selected'";}else if(isset($_SESSION['ajouter_annonce']['TYPE_ANNONCE']) && $_SESSION['ajouter_annonce']['TYPE_ANNONCE'] == $type_annonce['ID_TYPES']){echo "selected='selected'";} ?>><?php echo $type_annonce['ID_TYPES']; ?></option>
				<?php
				}
				?>
			</select><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_date_debut">Date de début: <span class="petit" title="Date au format jour/mois/année.">(Ex: 06/05/2011 20h56)</span></label><br />
			<input onkeyup="fx_previsualiser_annonce();" onblur="fx_verif_champ_date('date_debut', 'form_ajout_modification_annonce_date_debut', 1);"  type="text" name="form_ajout_modification_annonce_date_debut" id="form_ajout_modification_annonce_date_debut" size="40" value="<?php if(isset($annonce)){echo $annonce[0]['DATE_DEBUT'];}else if(isset($_SESSION['ajouter_annonce']['DATE_DEBUT'])){echo $_SESSION['ajouter_annonce']['DATE_DEBUT'];} ?>" placeholder="Ex : 03/11/2011 18h00" required  /><br />
			<div id="date_debut"></div>
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_date_fin">Date de fin: <span class="petit" title="Date au format jour/mois/année.">(Ex: 06/05/2011 20h56)</span></label><br />
			<input onkeyup="fx_previsualiser_annonce();" onblur="fx_verif_champ_date('date_fin', 'form_ajout_modification_annonce_date_fin', 1);"  type="text" name="form_ajout_modification_annonce_date_fin" id="form_ajout_modification_annonce_date_fin" size="40" value="<?php if(isset($annonce)){echo $annonce[0]['DATE_FIN'];}else if(isset($_SESSION['ajouter_annonce']['DATE_FIN'])){echo $_SESSION['ajouter_annonce']['DATE_FIN'];} ?>" placeholder="Ex : 03/11/2011 23h30" required  /><br />
			<div id="date_fin"></div>
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_artistes_recherches">Description des artistes recherchés:</label><br />
			<textarea onkeyup="fx_previsualiser_annonce();" onblur="fx_verif_champ_simple('artistes_recherches', 'form_ajout_modification_annonce_artistes_recherches');"  cols="80" rows="5" name="form_ajout_modification_annonce_artistes_recherches" id="form_ajout_modification_annonce_artistes_recherches" required><?php if(isset($annonce)){echo $annonce[0]['ARTISTES_RECHERCHES'];}else if(isset($_SESSION['ajouter_annonce']['ARTISTES_RECHERCHES'])){echo $_SESSION['ajouter_annonce']['ARTISTES_RECHERCHES'];}else{echo "Listez les artistes dont vous avez besoin par ordre de priorité. N'hésitez pas à détailler.";} ?></textarea><br />
			<div id="artistes_recherches"></div>
			<br />
			<label for="form_ajout_modification_annonce_budget">Budget prévu: <span class="petit" title="Tous prix exprimés sur le site le sont exclusivement en Euros (€).">(€)</span></label><br />
			<input onkeyup="fx_previsualiser_annonce();" type="text" name="form_ajout_modification_annonce_budget" id="form_ajout_modification_annonce_budget" size="10" value="<?php if(isset($annonce)){echo $annonce[0]['BUDGET'];}else if(isset($_SESSION['ajouter_annonce']['BUDGET'])){echo $_SESSION['ajouter_annonce']['BUDGET'];} ?>"  placeholder="Ex : 350€" /><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_nb_convives">Nombre d'invités: <span class="petit" title="Si vous ignorez le nombre d'invités, essayez d'estimer une fourchette. Si vous n'en savez rien, mettez 0.">(Mettez 0 si inconnu)</span></label><br />
			<input onkeyup="fx_previsualiser_annonce();" onblur="fx_verif_champ_simple('nb_convives', 'form_ajout_modification_annonce_nb_convives');"  type="number" name="form_ajout_modification_annonce_nb_convives" id="form_ajout_modification_annonce_nb_convives" size="5" value="<?php if(isset($annonce)){echo $annonce[0]['NB_CONVIVES'];}else if(isset($_SESSION['ajouter_annonce']['NB_CONVIVES'])){echo $_SESSION['ajouter_annonce']['NB_CONVIVES'];} ?>" placeholder="Ex : 25" min="0" max="3000" step="1" required /><br />
			<div id="nb_convives"></div>
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_description" title="Décrivez votre annonce de facon à ce qu'elle intéresse les artistes qui la liront ! Plus une annonce est intéressante et plus elle a de chance d'attirer du monde.">Description de l'annonce:</label><br />
			<textarea onkeyup="fx_previsualiser_annonce();" onblur="fx_verif_champ_simple('description', 'form_ajout_modification_annonce_description');"  cols="80" rows="5" name="form_ajout_modification_annonce_description" id="form_ajout_modification_annonce_description" required ><?php if(isset($annonce)){echo $annonce[0]['DESCRIPTION'];}else if(isset($_SESSION['ajouter_annonce']['DESCRIPTION'])){echo $_SESSION['ajouter_annonce']['DESCRIPTION'];}else{echo "Expliquez tout ce qui pourrait motiver les artistes qui liront cette annonce !";} ?></textarea><br />
			<div id="description"></div>
			<br />			
			<span class="alert">*</span><label for="form_ajout_modification_annonce_id_departement">Département :</label><br />
			<select onchange="fx_previsualiser_annonce();" name="form_ajout_modification_annonce_id_departement" id="form_ajout_modification_annonce_id_departement" required >
				<?php
				foreach($departements as $key=>$departement){
				# à finir 
				?>
					<option value="<?php echo $departement['ID_DEPARTEMENT']; ?>" <?php if(isset($annonce) && $annonce[0]['ID_DEPARTEMENT'] == $departement['ID_DEPARTEMENT']){echo "selected='selected'";}else if(isset($_SESSION['ajouter_annonce']['ID_DEPARTEMENT']) && $_SESSION['ajouter_annonce']['ID_DEPARTEMENT'] == $departement['ID_DEPARTEMENT']){echo "selected='selected'";} ?>><?php echo $departement['ID_DEPARTEMENT'].") ".$departement['NOM']; ?></option>
				<?php
				}
				?>
			</select><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_adresse">Adresse:</label><br />
			<input onkeyup="fx_previsualiser_annonce();" onblur="fx_verif_champ_simple('adresse', 'form_ajout_modification_annonce_adresse');"  type="text" name="form_ajout_modification_annonce_adresse" id="form_ajout_modification_annonce_adresse" size="40" value="<?php if(isset($annonce)){echo $annonce[0]['ADRESSE'];}else if(isset($_SESSION['ajouter_annonce']['ADRESSE'])){echo $_SESSION['ajouter_annonce']['ADRESSE'];} ?>" required  /><br />
			<div id="adresse"></div>
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_cp">Code postal:</label><br />
			<input onkeyup="fx_previsualiser_annonce();" onblur="fx_verif_champ_simple('cp', 'form_ajout_modification_annonce_cp');"  type="text" name="form_ajout_modification_annonce_cp" id="form_ajout_modification_annonce_cp" size="5" value="<?php if(isset($annonce)){echo $annonce[0]['CP'];}else if(isset($_SESSION['ajouter_annonce']['CP'])){echo $_SESSION['ajouter_annonce']['CP'];} ?>" required  /><br />
			<div id="cp"></div>
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_ville">Ville:</label><br />
			<input onkeyup="fx_previsualiser_annonce();" onblur="fx_verif_champ_simple('ville', 'form_ajout_modification_annonce_ville');"  type="text" name="form_ajout_modification_annonce_ville" id="form_ajout_modification_annonce_ville" size="40" value="<?php if(isset($annonce)){echo $annonce[0]['VILLE'];}else if(isset($_SESSION['ajouter_annonce']['VILLE'])){echo $_SESSION['ajouter_annonce']['VILLE'];} ?>" required  /><br />
			<div id="ville"></div>
			<br />
			<br />
			<span class="fright alert">* Champ obligatoire&nbsp;</span><br />
			<br />
			<center>
				<br />
				<!-- Div qui afficheront les informations prévisualisées. -->
				<div class="noir" id="previsualisation_annonce">
					<fieldset class="padding_LR"><legend class="legend_basique">Prévisualisation&nbsp;de&nbsp;l'annonce:&nbsp;</legend><br />
						<div id="previsualiser_titre" class="rose">
							
						</div>
						<div id="previsualiser_type_annonce" class="rose">
							
						</div>
						<div id="previsualiser_dates">
							
						</div>
						<div id="previsualiser_budget_nb_personne" class="rose">
							
						</div>
						<div id="previsualiser_description">
							
						</div>
						<div id="previsualiser_artistes_recherches">
							
						</div>
						<br />
					</fieldset>
				</div>
				<br />
				<?php
				if(isset($annonce) && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				?>
				Sélectionnez le nouveau statut de l'annonce:<br />
				<select name="form_ajout_modification_annonce_statut" id="form_ajout_modification_annonce_statut" onchange="fx_afficher('p_raison_refus', 'form_ajout_modification_annonce_statut', 'form_ajout_modification_annonce_refus');" >
					<?php
					foreach($statuts as $key=>$statut){
					?>
					<option value="<?php echo $statut['ID_TYPES']; ?>"  <?php if($statut['ID_TYPES'] == $annonce[0]['STATUT']){echo "selected='selected'";} ?>><?php echo $statut['ID_TYPES']; ?></option>
					<?php
					}# Fin du foreach d'affichage des statuts.
					?>
				</select>
				<br />
				<p id="p_raison_refus">
					Expliquez rapidement la raison de votre refus:<br />
					<textarea cols="80" rows="5" name="form_ajout_modification_annonce_refus" id="form_ajout_modification_annonce_refus" ></textarea>
				</p>
				<?php
				}# Fin du if du type_personne.
				?>
				<input type="image" src="images/valider.png" alt="Créer l'annonce." name="Créer l'annonce." /><br />
			</center>
		</form>
	</fieldset>
	
	<?php
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	?>
		<?php
		if($annonce[0]['STATUT'] != "Refusée"){
		?>
			<script type="text/javascript">
				fx_cacher('p_raison_refus', 'form_ajout_modification_annonce_refus');
			</script>
		<?php
		}
		?>
	<?php
	}
	?>