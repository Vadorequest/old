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
	<h2>Liste des annonces non activées:</h2><br />
	<br />
	<fieldset><legend class="legend_basique">Liste des annonces</legend><br />
	<br />
	<?php
		if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
		}
	?>
	<table width="100%">
		<tr class="formulaire">
			<th width="25%" scope="col">Titre:</th>
			<th width="25%" scope="col">Créateur:</th>
			<th width="10%" scope="col">Type:</th>
			<th width="20%" scope="col">Date de création:</th>
			<th width="10%" scope="col">Statut:</th>
			<th width="10%" scope="col">Voir:</th>
		</tr>
		<?php
		if($nb_result[0]['nb_annonce'] == 0){
		?>
				<tr><th colspan="6"><hr /></th></tr>
				<tr><th colspan="6" class="orange" height="40px">Il n'y a aucune annonce en attente.</th></tr>
		<?php
		}
		?>
		<?php
		foreach($annonces as $key=>$annonce){
		?>
			<tr><td colspan="6"><hr /></td></tr>
			<tr>
				<th class="rose" scope="row"><?php echo $annonce['TITRE']; ?></th>
				<th title="Personne N°<?php echo $annonce['ID_PERSONNE']; ?>"><a href="<?php echo $oCL_page->getPage('modifier_fiche_membre', 'absolu')."?id_personne=".$annonce['ID_PERSONNE']; ?>"><?php echo $annonce['PSEUDO']; ?></a></th>
				<th><?php echo $annonce['TYPE_ANNONCE']; ?></th>
				<th><?php echo $annonce['DATE_ANNONCE']; ?></th>
				<th <?php if($annonce['STATUT'] == "En cours"){echo "class='valide'";}else if($annonce['STATUT'] == "Refusée"){echo "class='alert'";} ?>><?php echo $annonce['STATUT']; ?></th>
				<th><a href="<?php echo $oCL_page->getPage('modifier_fiche_annonce_by_admin')."?id_annonce=".$annonce['ID_ANNONCE']; ?>"><img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="Voir l'annonce de <?php echo $annonce['PSEUDO']; ?> (N°<?php echo $annonce['ID_ANNONCE']; ?>)" title="Voir l'annonce de <?php echo $annonce['PSEUDO']; ?> (N°<?php echo $annonce['ID_ANNONCE']; ?>)" /></a></th>
			</tr>
		<?php
		}
		?>
		
	</table>
	<br />
</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>