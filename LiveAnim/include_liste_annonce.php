<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

// Cette page est en libre accès.

?>
	<img style="" alt="Historique achat"
 src="images/les-annonces.png">
<br />
<br />

<strong><u>Recherche d'annonce:</u></strong>&nbsp;&nbsp;&nbsp;<img id="img_plus_moins" onclick="fx_affiche('recherche_annonce', 'img_plus_moins');" src="<?php echo $oCL_page->getImage('petit_plus'); ?>" alt="Afficher/Cacher le formulaire de recherche" title="Afficher/Cacher le formulaire de recherche" /><br />
<div id="recherche_annonce">
	<br />
	<form class="formulaire" action="script_recherche_annonce.php" method="post" id="form_recherche_annonce">
		Je cherche une annonce qui commence entre le <input onblur="fx_verif_champ_date('date_debut', 'form_recherche_annonce_date_debut', 0);fx_verif_date_superieure('date_fin', 'form_recherche_annonce_date_debut', 'form_recherche_annonce_date_fin', 0);"" type="text" name="form_recherche_annonce_date_debut" id="form_recherche_annonce_date_debut" value="<?php if(isset($_SESSION['recherche_annonce']['DATE_DEBUT'])){echo $DATE_DEBUT_simple;}else{echo $now_court;} ?>" size="7" /> et le <input onblur="fx_verif_date_superieure('date_fin', 'form_recherche_annonce_date_debut', 'form_recherche_annonce_date_fin', 0);" type="text" name="form_recherche_annonce_date_fin" id="form_recherche_annonce_date_fin" value="<?php if(isset($_SESSION['recherche_annonce']['DATE_FIN'])){echo $DATE_FIN_simple;}else{echo "01-01-2020";} ?>" size="7" />.<br />
		<div id="date_debut"></div>
		<div id="date_fin"></div>
		<br />
		Type:&nbsp;<select name="form_recherche_annonce_type_annonce" id="form_recherche_annonce_type_annonce">
			<option value="*" selected="selected">Tous</option>
		<?php
		foreach($types_annonce as $key=>$type_annonce){
		?>
			<option value="<?php echo $type_annonce['ID_TYPES'] ?>" <?php if(isset($_SESSION['recherche_annonce']['TYPE_ANNONCE']) && $_SESSION['recherche_annonce']['TYPE_ANNONCE'] == $type_annonce['ID_TYPES']){echo "selected='selected'";} ?>><?php echo $type_annonce['ID_TYPES'] ?></option>
		<?php
		}
		?>
		</select><br />
		<br />
		Tarif minimal: <input type="text" name="form_recherche_annonce_budget" id="form_recherche_annonce_budget" value="<?php if(isset($_SESSION['recherche_annonce']['BUDGET'])){echo $_SESSION['recherche_annonce']['BUDGET'];}else{echo "0";} ?>" size="4" />&nbsp;€<br />
		<br />
		Code postal ou nom de la ville: <input type="text" name="form_recherche_annonce_cp_ville" id="form_recherche_annonce_cp_ville" value="<?php if(isset($_SESSION['recherche_annonce']['CP_VILLE'])){echo $_SESSION['recherche_annonce']['CP_VILLE'];}else{echo "";} ?>" /><br />
		<br />
		<center>
			<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Lancer la recherche" title="Lancer la recherche" />					
		</center>
	</form>
	<br />
	<br />
</div>
<br />
<?php
if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
	$path_parts = pathinfo($_SERVER['PHP_SELF']);
	$page = $path_parts["basename"];
	$page_actuelle = ($limite/$nb_result_affiches)+1;
	afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
}
?>
<fieldset class="padding_LR"><legend class="legend_basique">Annonces trouvées:</legend><br />
	<br />
	<?php
	if($nb_result[0]['nb_annonce'] == 0){
	?>
		<div class="orange">
		<center>Il n'y a pas d'annonce qui corresponde à vos critères de recherche.</center><br />
		</div>
	<?php
	}
	foreach($annonces as $key=>$annonce){
	?>
		<div class="padding_LR">
			<img class="fleft" width="150px" height="150px" alt="Image annonce" src="<?php echo $oCL_page->getImage('disco1'); ?>" />
			<div class="fleft" style="padding: 0 10px 0 10px;color:#F300FF">Annonce postée le <?php echo $annonce['DATE_ANNONCE'] ?>.</div><br />
			<b style="padding-left:5%"><u><a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce['ID_ANNONCE']; ?>"><?php echo $annonce['TITRE']; ?></a></u></b><br />
			<br />
			<div style="padding-left:30%;padding-right:10px;"><?php echo substr($annonce['DESCRIPTION'], 0, 100)." ..."; ?></div><br />
			<div style="padding-left:30%;padding-right:10px;">La représentation débute le <?php echo $annonce['DATE_DEBUT']; ?>, elle aura lieue à <?php echo $annonce['VILLE']." <span class='petit'>(".$annonce['CP'].").</span>"; ?></div>
			<div style="padding-left:80%;padding-right:10px;"><a  title="Les détails de l'annonce dépendront de votre pack si vous êtes un artiste. Les organisateurs n'ont accès qu'au strict minimum." href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce['ID_ANNONCE']; ?>" class="link3" <?php if(isset($_SESSION['pack']['NB_FICHES_VISITABLES']) && $_SESSION['pack']['NB_FICHES_VISITABLES'] < 100 && !in_array($annonce['ID_ANNONCE'], $_SESSION['compte']['annonces_visitées'])){?>onclick="return confirm('Vous pouvez actuellement visiter <?php echo $_SESSION['pack']['NB_FICHES_VISITABLES']; ?> annonces. Visiter l'annonce diminuera votre quota, souhaitez-vous continuer ?');"<?php }?>>Voir l'annonce</a></div><br/>
			<?php
			if(isset($_SESSION['pack']['PREVISUALISATION_FICHES']) && $_SESSION['pack']['PREVISUALISATION_FICHES'] == true){
			?>
				<fieldset class="padding_LR"><legend class="legend_basique">Informations&nbsp;supplémentaires:</legend><br />
					Il y a actuellement <?php echo $annonce['nb_contrat']; ?> contrats en cours pour cette annonce.<br />
					Le budget prévu par l'organisateur est de <b><?php echo $annonce['BUDGET']; ?>€</b>. Il y aura environ <b><?php echo $annonce['NB_CONVIVES']; ?> invités.</b><br />
					<br />
					
				</fieldset>
				<br />
				<hr />
			<?php
			}
			?>
			<br class="clear" />
		</div>
	<?php
	}
	?>
</fieldset>


<?php
if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
	$path_parts = pathinfo($_SERVER['PHP_SELF']);
	$page = $path_parts["basename"];
	$page_actuelle = ($limite/$nb_result_affiches)+1;
	afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
}
?>

<script type="text/javascript">
	initialiser_div('recherche_annonce');
</script>