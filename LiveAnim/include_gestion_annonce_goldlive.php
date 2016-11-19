<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
?>
<img style="" alt="Mes annonces gold"
 src="images/mes-annonces-gold.png">
<br />
<br />
	<?php
	if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
		$path_parts = pathinfo($_SERVER['PHP_SELF']);
		$page = $path_parts["basename"];
		$page_actuelle = ($limite/$nb_result_affiches)+1;
		afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
	}
	?>
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Liste&nbsp;de&nbsp;vos&nbsp;annonces&nbsp;GoldLive:&nbsp;</legend><br />
		<br />
		<?php 
		if($nb_result[0]['nb_annonce'] == 0){
			echo "<span class='orange petit'>Aucune de vos annonce ne possède le statut GoldLive pour le moment.</span>";
		}
		
		foreach($annonces as $key=>$annonce){
		?>
			<div class="padding_LR">
			<img class="fleft" width="150px" height="120px" alt="Image annonce" src="<?php echo $oCL_page->getImage('disco1'); ?>" />
			<div class="fleft" style="padding: 0 10px 0 10px;color:#F300FF">Annonce postée le <?php echo $annonce['DATE_ANNONCE'] ?>.</div><br />
			<b style="padding-left:5%"><u><a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce['ID_ANNONCE']; ?>"><?php echo $annonce['TITRE']; ?></a></u></b><br />
			<br />
			<center class="noir">
				Le statut actuel de cette annonce est <span class="rose"><?php echo $annonce['STATUT']; ?>.</span>
			</center>
			<br class="clear" />
		</div>
		<br />
		<?php
		}
		?>
		<br />
	</fieldset>
	<br />
	<?php
	if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
		$path_parts = pathinfo($_SERVER['PHP_SELF']);
		$page = $path_parts["basename"];
		$page_actuelle = ($limite/$nb_result_affiches)+1;
		afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
	}
	?>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>