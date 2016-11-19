<?php
	if(isset($_SESSION['ajouter_pack']['message']) && $_SESSION['ajouter_pack']['message_affiche'] == false){
		echo $_SESSION['ajouter_pack']['message'];
		$_SESSION['ajouter_pack']['message_affiche'] = true;
	}
?>
<form class="formulaire" action="script_form_ajouter_pack.php" method="post" name="form_pack" id="form_pack">
	<br />Nom du pack:<br />
	<input type="text" value="Live " name="form_pack_nom" id="form_pack_nom" required /><br />
	<br />
	Description du pack:<br />
	<textarea name="form_pack_description" id="form_pack_description" cols="80" rows="5" required></textarea><br />
	<br />
	Type de pack:<br />
	<select id="form_pack_type_pack" name="form_pack_type_pack" required>
		<?php
		foreach($types_packs as $key=>$type_pack){
		?>
			<option value="<?php echo $type_pack['ID_TYPES']; ?>" <?php if($type_pack['ID_TYPES'] == "Basique"){echo "selected='selected'";} ?>><?php echo $type_pack['ID_TYPES']; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Prix de base:<br />
	<input type="number"  name="form_pack_prix_base" id="form_pack_prix_base" min="3" max="300" step="0.1" required /><br />
	<br />
	Durée:<br />
	<select  name="form_pack_duree" id="form_pack_duree" />
		<?php
		for($i = 1;$i<13; $i++){
		?>
			<option value="<?php echo $i; ?>"><?php echo $i; ?> mois</option>
		<?php
		}
		?>
	</select><br />
	<br />
	Est-ce que ce pack bénéficie des réductions dûes au parrainage ?<br />
	<select  name="form_pack_soumis_reduction_parrainage" id="form_pack_soumis_reduction_parrainage"  required/>
		<option value="1" selected="selected">Oui</option>
		<option value="0">Non</option>
	</select><br />
	<br />
	Quel est le maximum de réduction auquel est soumis ce pack ?<br />
	<input type="text"  name="form_pack_gain_parrainage_max" id="form_pack_gain_parrainage_max" size="4" />&nbsp;<span class='petit' required>(En %)</span><br />
	<br />
	Si ce pack est acheté par un filleul, combien de réduction apporte-t-il à son parrain ?<br />
	<input type="text"  name="form_pack_reduction" id="form_pack_reduction" size="4" />&nbsp;<span class='petit' required>(En %)</span><br />
	<br />
	Activer le pack dès maintenant ?<br />
	<select  name="form_pack_visible" id="form_pack_visible" required />
		<option value="1" selected="selected">Activer</option>
		<option value="0">Désactiver</option>
	</select><br />
	<br />
	<hr />
	<br />
	<h5>Options du pack:</h5><br />
	<br />
	<span title="Plus ce nombre est élevé et plus le C.V a de chance d'apparaître en haut des listings. (On parle donc des C.V des Prestataires)"><u>Niveau de visibilité du C.V du prestataire:</u></span><br />
	<select  name="form_pack_cv_visibilite" id="form_pack_cv_visibilite" required />
		<?php
		for($i = 0;$i<11; $i++){
		?>
			<option value="<?php echo $i; ?>">Rang <?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	<span title="Plus ce nombre est élevé et plus les C.V vus afficheront d'informations. (On parle donc des C.V des Organisateurs)"><u>Niveau d'accessibilité des C.V des organisateurs:</u></span><br />
	<select  name="form_pack_cv_accessible" id="form_pack_cv_accessible" required />
		<?php
		for($i = 0;$i<11; $i++){
		?>
			<option value="<?php echo $i; ?>">Rang <?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Nombre d'annonces consultables par mois:<br />
	<input type="text"  name="form_pack_nb_fiches_visitables" id="form_pack_nb_fiches_visitables" size="5" required /><br />
	<br />
	Permet de faire un C.V vidéo:<br />
	<select  name="form_pack_cv_video_accessible" id="form_pack_cv_video_accessible" required />
		<option value="0">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	Envoi de MP en cas de désistement d'un autre prestataire dans les départements désirés:<br />
	<select  name="form_pack_alerte_non_disponibilite" id="form_pack_alerte_non_disponibilite" required />
		<option value="0">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	Nombre maximal de départements pour lesquels le prestataire sera prévenu en cas de désistement:<br /><span class="petit">(Va avec l'option précédente.)</span><br />
	<select  name="form_pack_nb_departements_alerte" id="form_pack_nb_departements_alerte" required />
		<?php
		for($i = 0;$i<11; $i++){
		?>
			<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Possibilité de parrainer:<br />
	<select  name="form_pack_parrainage_active" id="form_pack_parrainage_active" required />
		<option value="0">Non</option>
		<option value="1" selected='selected'>Oui</option>
	</select><br />
	<br />
	Possibilité de prévisualiser les fiches:<br />
	<select  name="form_pack_previsualisation_fiches" id="form_pack_previsualisation_fiches" required />
		<option value="0">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	Récupération des contrats sous format .pdf:<br />
	<select  name="form_pack_contrats_pdf" id="form_pack_contrats_pdf" required />
		<option value="0">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	<span title="Indique si le prestataire bénéficie d'un suivi de ses dépenses/gains via des statistiques détaillées."><u>Suivi du prestataire:</u></span><br />
	<select  name="form_pack_suivi" id="form_pack_suivi" required />
		<option value="0">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	Activer les pubs ?<br />
	<select  name="form_pack_pubs" id="form_pack_pubs" required />
		<option value="0" selected="selected">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	<br />
	<span class="fright alert petit">N.B: Tous les champs sont obligatoires.</span><br />
	<br />
	<center>
		<input type="image" src="images/valider.png" alt="Valider" name="btn_form_pack_valider" id="btn_form_pack_valider" />
	</center>
	</form>