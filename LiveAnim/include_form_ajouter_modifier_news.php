<fieldset class="padding_LR"><legend class="legend_basique"><?php if($formulaire == "ajouter"){echo "Créez la news à publier:";}else if($formulaire == "modifier"){echo "Modifiez la news";} ?></legend><br />
		<br />
		<form enctype="multipart/form-data" class="formulaire" name="form_ajouter_modifier_news" id="form_ajouter_modifier_news" action="<?php if($formulaire == "ajouter"){echo "script_form_ajouter_news.php";}else if($formulaire == "modifier"){echo "script_form_modifier_news.php";} ?>" method="post">
			<?php
			if($formulaire == "modifier"){
			?>
				<input type="hidden" name="form_ajouter_modifier_news_id_nouveaute" value="<?php echo $nouveautee[0]['ID_NOUVEAUTE']; ?>" />
			<?php
			}
			?>
			<center class="alert">N'utilisez pas les balises h1, h2, h3 ou h4. Uniquement h5 et h6.</center><br />
			<br />
			<span class="alert">* </span><label for="form_ajouter_modifier_news_auteur">Auteur de la news: </label><br />
			<input type="text" name="form_ajouter_modifier_news_auteur" id="form_ajouter_modifier_news_auteur" value="<?php if($formulaire == "modifier"){echo $nouveautee[0]['AUTEUR'];}else if($formulaire == "ajouter" && isset($_SESSION['ajouter_news']['AUTEUR'])){echo $_SESSION['ajouter_news']['AUTEUR'];} ?>" required /><br />
			<br />
			<span class="alert">* </span><label for="form_ajouter_modifier_news_titre">Titre de la news: </label><br />
			<input type="text" name="form_ajouter_modifier_news_titre" id="form_ajouter_modifier_news_titre" value="<?php if($formulaire == "modifier"){echo $nouveautee[0]['TITRE'];}else if($formulaire == "ajouter" && isset($_SESSION['ajouter_news']['TITRE'])){echo $_SESSION['ajouter_news']['TITRE'];} ?>" required /><br />
			<br />
			<span class="alert">* </span><label for="form_ajouter_modifier_news_entete" title="L'entête sera ce qui sera affiché sur la page d'accueil afin de donner envie au lecteur de lire la news en entier. (100 caractères max)">Entête: <span class="petit noir">(Accepte le code HTML)</span></label><br />
			<textarea cols="80" rows="5" name="form_ajouter_modifier_news_entete" id="form_ajouter_modifier_news_entete" required ><?php if($formulaire == "modifier"){echo $nouveautee[0]['ENTETE'];}else if($formulaire == "ajouter" && isset($_SESSION['ajouter_news']['ENTETE'])){echo $_SESSION['ajouter_news']['ENTETE'];} ?></textarea><br />
			<br />
			<span class="alert">* </span><label for="form_ajouter_modifier_news_contenu">Contenu: <span class="petit noir">(Accepte le code HTML)</span></label><br />
			<textarea cols="80" rows="5" name="form_ajouter_modifier_news_contenu" id="form_ajouter_modifier_news_contenu" required ><?php if($formulaire == "modifier"){echo $nouveautee[0]['CONTENU'];}else if($formulaire == "ajouter" && isset($_SESSION['ajouter_news']['CONTENU'])){echo $_SESSION['ajouter_news']['CONTENU'];} ?></textarea><br />
			<br />
			<center>
				<fieldset class="padding_LR"><legend class="legend_basique"><label for="form_ajouter_modifier_news_url_photo">Url&nbsp;de&nbsp;la&nbsp;photo:&nbsp;</label><br /></legend><br />
					<textarea cols="80" rows="5" name="form_ajouter_modifier_news_url_photo" id="form_ajouter_modifier_news_url_photo" ><?php if($formulaire == "modifier"){echo $nouveautee[0]['URL_PHOTO'];}else if($formulaire == "ajouter" && isset($_SESSION['ajouter_news']['URL_PHOTO'])){echo $_SESSION['ajouter_news']['URL_PHOTO'];} ?></textarea><br />
					<br />
					<br /><b><u title="L'image téléchargée est prioritaire sur l'URL.">Ou:</u></b><br /><br />
					Télécharger une nouvelle photo: <br />
					<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
					<input type="file" name="form_ajouter_modifier_news_nouvelle_photo" /><br />
					<br />
				</fieldset>
			</center>
			<br />
			<?php
			if($formulaire == "modifier"){
			?>
			<span class="alert">* </span><label for="form_ajouter_modifier_news_date_creation">Date de publication: </label><br />
			<input type="text" name="form_ajouter_modifier_news_date_creation" id="form_ajouter_modifier_news_date_creation" value="<?php echo $nouveautee[0]['DATE_CREATION']; ?>" required /><br />
			<br />
			<?php
			}
			?>
			<label for="form_ajouter_modifier_news_visible" title="Indique si la news sera visible sur le site.">News visible: </label><input type="checkbox" name="form_ajouter_modifier_news_visible" id="form_ajouter_modifier_news_visible" <?php if($formulaire == "modifier"){if($nouveautee[0]['VISIBLE']){echo "checked='checked'";} }else if(isset($_SESSION['ajouter_news']['VISIBLE']) && $_SESSION['ajouter_news']['VISIBLE'] == true){echo "checked='checked'";}else if($formulaire == "ajouter" && !isset($_SESSION['ajouter_news']['VISIBLE'])){echo "checked='checked'";} ?>/><br />
			<br />
			<span class="fright"><span class="alert">* </span><span class="noir">: Champ obligatoire.&nbsp;</span></span><br />
			<center>
				<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Publier la news" title="" /><br />
			</center>
		</form>
	</fieldset>