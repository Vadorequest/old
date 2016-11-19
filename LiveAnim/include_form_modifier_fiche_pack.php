<?php
	if(isset($_SESSION['modifier_fiche_pack']['message']) && $_SESSION['modifier_fiche_pack']['message_affiche'] == false){
		echo $_SESSION['modifier_fiche_pack']['message'];
		$_SESSION['modifier_fiche_pack']['message_affiche'] = true;
	}
?>
<form class="formulaire" action="script_form_modifier_fiche_pack.php" method="post" name="form_pack" id="form_pack">
	<input type="hidden" name="form_pack_id_pack" id="form_pack_id_pack" value="<?php echo $pack[0]['ID_PACK']; ?>" />	
	<br />Nom du pack:<br />
	<input type="text" value="<?php echo $pack[0]['NOM']; ?>" name="form_pack_nom" id="form_pack_nom" placeholder="Ex : Pack Live Max" required /><br />
	<br />
	Description du pack:<br />
	<textarea name="form_pack_description" id="form_pack_description" cols="80" rows="5" required><?php echo $pack[0]['DESCRIPTION']; ?></textarea><br />
	<br />
	Type de pack:<br />
	<select id="form_pack_type_pack" name="form_pack_type_pack" required>
		<?php
		foreach($types_pack as $key=>$type_pack){
		?>
			<option value="<?php echo $type_pack['ID_TYPES']; ?>" <?php if($type_pack['ID_TYPES'] == $pack[0]['TYPE_PACK']){echo "selected='selected'";} ?>><?php echo $type_pack['ID_TYPES']; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Prix de base:<br />
	<input type="number"  name="form_pack_prix_base" id="form_pack_prix_base" value="<?php echo $pack[0]['PRIX_BASE']; ?>" min="3" max="300" step="0.1" required /><br />
	<br />
	Durée:<br />
	<select  name="form_pack_duree" id="form_pack_duree" required />
		<?php
		for($i = 1;$i<13; $i++){
		?>
			<option value="<?php echo $i; ?>" <?php if($i == $pack[0]['DUREE']){echo "selected='selected'";} ?>><?php echo $i; ?> mois</option>
		<?php
		}
		?>
	</select><br />
	<br />
	Est-ce que ce pack bénéficie des réductions dûes au parrainage ?<br />
	<select  name="form_pack_soumis_reduction_parrainage" id="form_pack_soumis_reduction_parrainage" required />
		<option value="1" <?php if($pack[0]['SOUMIS_REDUCTIONS_PARRAINAGE'] == true){echo "selected='selected'";} ?>>Oui</option>
		<option value="0" <?php if($pack[0]['SOUMIS_REDUCTIONS_PARRAINAGE'] == false){echo "selected='selected'";} ?>>Non</option>
	</select><br />
	<br />
	Quel est le maximum de réduction auquel est soumis ce pack ?<br />
	<input type="text"  name="form_pack_gain_parrainage_max" id="form_pack_gain_parrainage_max" size="4" value="<?php echo $pack[0]['GAIN_PARRAINAGE_MAX']; ?>" required />&nbsp;<span class='petit'>(En %)</span><br />
	<br />
	Si ce pack est acheté par un filleul, combien de réduction apporte-t-il à son parrain ?<br />
	<input type="text"  name="form_pack_reduction" id="form_pack_reduction" size="4" value="<?php echo $pack[0]['REDUCTION']; ?>" required />&nbsp;<span class='petit'>(En %)</span><br />
	<br />
	État du pack:<br />
	<select  name="form_pack_visible" id="form_pack_visible" required />
		<option value="1" <?php if($pack[0]['VISIBLE'] == true){echo "selected='selected'";} ?>>Activé</option>
		<option value="0" <?php if($pack[0]['VISIBLE'] == false){echo "selected='selected'";} ?>>Désactivé</option>
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
			<option value="<?php echo $i; ?>" <?php if($i == $pack[0]['CV_VISIBILITE']){echo "selected='selected'";} ?>>Rang <?php echo $i; ?></option>
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
			<option value="<?php echo $i; ?>" <?php if($i == $pack[0]['CV_ACCESSIBLE']){echo "selected='selected'";} ?>>Rang <?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Nombre d'annonces consultables par mois:<br />
	<input type="text"  name="form_pack_nb_fiches_visitables" id="form_pack_nb_fiches_visitables" size="5" value="<?php echo $pack[0]['NB_FICHES_VISITABLES']; ?>" required /><br />
	<br />
	Permet de faire un C.V vidéo:<br />
	<select  name="form_pack_cv_video_accessible" id="form_pack_cv_video_accessible" required />
		<option value="0" <?php if($pack[0]['CV_VIDEO_ACCESSIBLE'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['CV_VIDEO_ACCESSIBLE'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	Envoi de MP en cas de désistement d'un autre prestataire dans les départements désirés:<br />
	<select  name="form_pack_alerte_non_disponibilite" id="form_pack_alerte_non_disponibilite" required />
		<option value="0" <?php if($pack[0]['ALERTE_NON_DISPONIBILITE'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['ALERTE_NON_DISPONIBILITE'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	Nombre maximal de départements pour lesquels le prestataire sera prévenu en cas de désistement:<br /><span class="petit">(Va avec l'option précédente.)</span><br />
	<select  name="form_pack_nb_departements_alerte" id="form_pack_nb_departements_alerte" required />
		<?php
		for($i = 0;$i<11; $i++){
		?>
			<option value="<?php echo $i; ?>" <?php if($i == $pack[0]['NB_DEPARTEMENTS_ALERTE']){echo "selected='selected'";} ?>><?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Possibilité de parrainer:<br />
	<select  name="form_pack_parrainage_active" id="form_pack_parrainage_active" required />
		<option value="0" <?php if($pack[0]['PARRAINAGE_ACTIVE'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['PARRAINAGE_ACTIVE'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	Possibilité de prévisualiser les fiches:<br />
	<select  name="form_pack_previsualisation_fiches" id="form_pack_previsualisation_fiches" required />
		<option value="0" <?php if($pack[0]['PREVISUALISATION_FICHES'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['PREVISUALISATION_FICHES'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	Récupération des contrats sous format .pdf:<br />
	<select  name="form_pack_contrats_pdf" id="form_pack_contrats_pdf" required />
		<option value="0" <?php if($pack[0]['CONTRATS_PDF'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['CONTRATS_PDF'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	<span title="Indique si le prestataire bénéficie d'un suivi de ses dépenses/gains via des statistiques détaillées."><u>Suivi du prestataire:</u></span><br />
	<select  name="form_pack_suivi" id="form_pack_suivi" required />
		<option value="0" <?php if($pack[0]['SUIVI'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['SUIVI'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	Activer les pubs ?<br />
	<select  name="form_pack_pubs" id="form_pack_pubs" required />
		<option value="0" <?php if($pack[0]['PUBS'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['PUBS'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	<br />
	<span class="fright alert petit">N.B: Tous les champs sont obligatoires.</span><br />
	<br />
	<center>
		<input type="image" src="images/valider.png" alt="Valider" name="btn_form_pack_valider" id="btn_form_pack_valider" />
	</center>
</form>