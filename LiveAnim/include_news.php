<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

?>
	<h2>Lecture d'une news:</h2><br />
	<br />
	
	<?php
	if(isset($_SESSION['news']['message']) && $_SESSION['news']['message_affiche'] == false){
		echo $_SESSION['news']['message'];
		$_SESSION['news']['message_affiche'] = true;
	}
	?>
	<?php
	if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	?>
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Modération</legend><br />
		<br />
		<center>
			<a href="<?php echo $oCL_page->getPage('modifier_news')."?id_news=".$nouveautee[0]['ID_NOUVEAUTE']; ?>">Modifier la news.</a><br />
		</center>
		<br />
	</fieldset>
	<?php
	}
	?>
	<br />
	
	<fieldset class="padding_LR formulaire"><legend class="legend_basique"><?php echo str_replace(' ', '&nbsp;', $nouveautee[0]['TITRE']); ?>&nbsp;</legend><br />
	<div style="width:20%;" class="fright">
		<img class="border" height="100px" width="100%" src="<?php echo $nouveautee[0]['URL_PHOTO']; ?>" alt="Photo de la news" /><br />
	</div>
	<br />
	<div style="width:80%;">
		<span class="noir petit"><center><u>Auteur</u>: <?php echo $nouveautee[0]['AUTEUR']; ?><br />
		Publiée le <?php echo $nouveautee[0]['DATE_CREATION']; ?>.</center></span><br />
		<br />
	</div>
	<div>
	<span class="noir"><?php echo $nouveautee[0]['CONTENU']; ?></span>
	</div>
	<br />
	<?php
	if(isset($_SESSION['compte']) && $_SESSION['compte']['connecté'] == true){
	?>
		<hr />
		<center>
			<b><u>Poster un commentaire:</u></b>&nbsp;&nbsp;<img id="img_commentaire" onclick="fx_affiche('div_commentaire', 'img_commentaire');" src="<?php echo $oCL_page->getImage('petit_plus'); ?>" alt="Afficher/Cacher le commentaire" title="Afficher/Cacher le commentaire" /><br />
			<br />
			<div id="div_commentaire">
				<form action="script_poster_commentaire.php" name="form_poster_commentaire" method="post">
					<input type="hidden" name="form_poster_commentaire_id_nouveaute" value="<?php echo $nouveautee[0]['ID_NOUVEAUTE']; ?>" />
					<textarea autofocus="1" cols="80" rows="5" name="form_poster_commentaire_contenu"></textarea><br />
					<br />
					<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Poster mon commentaire" title="Poster mon commentaire" /><br />
				</form>
			</div>
		</center>
		<br />
	<?php
	}else{
		echo "<span class='orange petit'>Vous devez être connecté afin de poster un commentaire.</span><br />";
	}
	?>
	</fieldset>

	<script type="text/javascript">
		initialiser_div('div_commentaire');
	</script>
	<br />
	<a href="<?php echo $oCL_page->getPage('liste_news'); ?>">Voir toutes les news</a>
	<br />
	<?php
	# On s'occupe des commentaires de la news.
	?>
	
	<?php
		if($nb_result[0]['nb_commentaire'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_commentaire'], $page_actuelle);
		}
	?>
	<?php
	if(count($commentaires) > 0){
	?>
	<br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Commentaires</legend><br />
	<br />
		<?php
		foreach($commentaires as $key=>$commentaire){
		?>
			<div class="fleft">
				<b>Commentaire posté par <?php if(isset($_SESSION['compte']) && $_SESSION['compte']['connecté'] == true){echo "<span class='valide'>".$commentaire['PSEUDO']."</span>";}else{echo "le membre N°".$commentaire['ID_PERSONNE'];} ?> le <span class="rose"><?php echo $commentaire['DATE_CREATION']; ?></span>.</b><br />
				<br />
			</div>
			<?php
			# Modération du commentaire par administrateur.
			if(isset($_SESSION['compte'])&& $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
			?>
				<div class="fleft">
					<form action="script_cacher_commentaire.php" method="post" name="form_cacher_commentaire">
						<input type="hidden" name="form_cacher_commentaire_id_nouveaute" value="<?php echo $nouveautee[0]['ID_NOUVEAUTE'] ?>" />
						<input type="hidden" name="form_cacher_commentaire_id_commentaire" value="<?php echo $commentaire['ID_COMMENTAIRE'] ?>" />
						&nbsp;&nbsp;<input onclick="return confirm('Etes-vous sur de vouloir supprimer ce commentaire ?');" type="image" height="24px" width="24px" src="<?php echo $oCL_page->getImage('croix'); ?>" alt="Supprimer le commentaire" title="Supprimer le commentaire" />
					</form>
				</div>
			<?php
			}
			?>
			<br class="clear" />
			<?php echo $commentaire['CONTENU']; ?><br />
			<br />
			<hr />
		<?php
		}
		?>
		<br />
	</fieldset>
	<?php
	}else{
	?>
	<center class="petit noir">Il n'y a pas encore eu de commentaire pour cette news ! Soyez le premier à en postez une !</center>
	<?php
	}
	?>
	<?php
		if($nb_result[0]['nb_commentaire'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_commentaire'], $page_actuelle);
		}
	?>