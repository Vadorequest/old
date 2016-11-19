<form style="position:relative;" class="formulaire" id="form_ajout_modification_contrat" action="<?php if($formulaire == "creer"){echo "script_form_ajouter_contrat.php";}else if($formulaire == "modifier"){echo "script_form_modifier_contrat.php";} ?>" method="post">
	<input type="hidden" name="form_ajout_modification_contrat_id_annonce" id="form_ajout_modification_contrat_id_annonce" value="<?php echo $annonce[0]['ID_ANNONCE']; ?>" />
	<input type="hidden" name="form_ajout_modification_contrat_id_contrat" id="form_ajout_modification_contrat_id_contrat" value="<?php echo $contrat[0]['ID_CONTRAT']; ?>" />
	<br />
	<b><u><center><a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce[0]['ID_ANNONCE'];	?>"><?php echo $annonce[0]['TITRE']; ?></a></center></b></u><br />
	<center>
		<?php
		if($formulaire == "creer"){
		?>
			<span class="noir">( <?php echo $annonce[0]['TYPE_ANNONCE']; ?> )</span></center><br />
		<?php
		}else if($formulaire == "modifier"){
		?>
			<span class="noir">( <a href="<?php echo $oCL_page->getPage('contrat')."?id_contrat=".$contrat[0]['ID_CONTRAT'];	?>">Revenir au contrat</a> )</span></center><br />
		<?php
		}else{}
		?>
	<br />
	<span class="alert">*</span><label for="form_ajout_modification_contrat_date_debut">Date de début de l'évènement:</label><br />
	<input onblur="fx_verif_champ_date('date_debut', 'form_ajout_modification_contrat_date_debut', 1);" type="text" name="form_ajout_modification_contrat_date_debut" id="form_ajout_modification_contrat_date_debut" value="<?php if(isset($_SESSION['creer_contrat']['DATE_DEBUT'])){echo $_SESSION['creer_contrat']['DATE_DEBUT'];}else if(isset($_SESSION['modifier_contrat']['DATE_DEBUT'])){echo $_SESSION['modifier_contrat']['DATE_DEBUT'];}else if($formulaire == "modifier"){echo $contrat[0]['DATE_DEBUT'];}else{echo $annonce[0]['DATE_DEBUT'];} ?>" placeholder="Ex : 03/11/2011 17h30" required /><br />
	<div id="date_debut"></div>
	<br />
	<span class="alert">*</span><label for="form_ajout_modification_contrat_date_fin">Date de fin de l'évènement:</label><br />
	<input onblur="fx_verif_champ_date('date_fin', 'form_ajout_modification_contrat_date_fin', 1);" type="text" name="form_ajout_modification_contrat_date_fin" id="form_ajout_modification_contrat_date_fin" value="<?php if(isset($_SESSION['creer_contrat']['DATE_FIN'])){echo $_SESSION['creer_contrat']['DATE_FIN'];}else if(isset($_SESSION['modifier_contrat']['DATE_FIN'])){echo $_SESSION['modifier_contrat']['DATE_FIN'];}else if($formulaire == "modifier"){echo $contrat[0]['DATE_FIN'];}else{echo $annonce[0]['DATE_FIN'];} ?>" placeholder="Ex : 03/11/2011 23h30" required /><br />
	<div id="date_fin"></div>
	<br />
	<span class="alert">*</span><label for="form_ajout_modification_contrat_prix">Rémunération totale (HT):</label><br />
	<input onblur="fx_verif_champ_simple('prix', 'form_ajout_modification_contrat_prix');" type="number" name="form_ajout_modification_contrat_prix" id="form_ajout_modification_contrat_prix" value="<?php if(isset($_SESSION['creer_contrat']['BUDGET'])){echo $_SESSION['creer_contrat']['BUDGET'];}else if(isset($_SESSION['modifier_contrat']['PRIX'])){echo $_SESSION['modifier_contrat']['PRIX'];}else if($formulaire == "modifier"){echo $contrat[0]['PRIX'];}else{echo $annonce[0]['BUDGET'];} ?>" size="4" placeholder="Ex : 500" min="50" max="5000" step="10" required />€<br />
	<div id="prix"></div>
	<br />
	<span class="alert">*</span><label for="form_ajout_modification_contrat_description"><?php if($formulaire == "creer"){echo "Description du contrat:";}else if($formulaire == "modifier"){echo "Expliquez les raisons de vos modifications et les informations utiles:";} ?></label><br />
	<textarea onblur="fx_verif_champ_simple('description', 'form_ajout_modification_contrat_description');" rows="18" cols="85" id="form_ajout_modification_contrat_description" name="form_ajout_modification_contrat_description" required><?php if(isset($_SESSION['creer_contrat']['DESCRIPTION'])){echo $_SESSION['creer_contrat']['DESCRIPTION'];}else if(isset($_SESSION['modifier_contrat']['DESCRIPTION'])){echo $_SESSION['modifier_contrat']['DESCRIPTION'];}else if($formulaire == "modifier"){echo $contrat[0]['DESCRIPTION'];}else{ echo"Expliquez toutes les modifications que vous souhaitez comparé à l'annonce originale ainsi que tous les éléments que vous souhaitez donner à l'organisateur.";} ?></textarea><br />
	<div id="description"></div>
	<br />
	<span class="fright alert">* Champ obligatoire&nbsp;</span><br />
	<br />
	<center>
		<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Créer le contrat" title="Créer le contrat" />
	</center>
	<br />
</form>