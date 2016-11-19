<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_liste_contrats_admin.php');

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
?>
	<?php
	if(isset($_GET['rq']) && $_GET['rq'] == 'en_cours'){
	?>
		<h2>Contrats en cours:</h2><br />
	<?php
	}else{
	?>
		<h2>Tous les contrats:</h2><br />
	<?php
	}
	?>
	
	<br />
	<fieldset><legend class="legend_basique">Contrats</legend><br />
	<br />
	<?php
		if($nb_result[0]['nb_contrat'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_contrat'], $page_actuelle);
		}
	?>
	<table width="100%">
		<tr class="formulaire">
			<th width="25%" scope="col">Contrat pour<br />l'annonce N°:</th>
			<th width="25%" scope="col">Date de création:</th>
			<th width="10%" scope="col">Prix:</th>
			<th width="20%" scope="col">Goldlive:</th>
			<th width="10%" scope="col">Statut:</th>
			<th width="10%" scope="col">Voir:</th>
		</tr>
		<?php
		if($nb_result[0]['nb_contrat'] == 0){
		?>
				<tr><th colspan="6"><hr /></th></tr>
				<?php
				if(isset($_GET['rq']) && $_GET['rq'] == 'en_cours'){
				?>
					<tr><th colspan="6" class="orange" height="40px">Il n'y a aucun contrat en attente.</th></tr>
				<?php
				}else{
				?>
					<tr><th colspan="6" class="orange" height="40px">Il n'y a aucun contrat existant.</th></tr>
				<?php
				}
				?>
		<?php
		}
		?>
		<?php
	    foreach($contrats as $key=>$contrat){
	    ?>
			<tr><td colspan="6"><hr /></td></tr>
			<tr>
				<th class="rose" scope="row"><a href="<?php echo $oCL_page->getPage('annonce').'?id_annonce='.$contrat['ID_ANNONCE']; ?>"><?php echo $contrat['TITRE']; ?></a></th>
				<th title="<?php echo $contrat['DATE_CONTRAT']?>"><?php echo $contrat['DATE_CONTRAT_simple']?></th>
				<th><?php echo $contrat['PRIX']; ?>€</th>
				<th><?php if($contrat['GOLDLIVE']){echo "<span class='rose'>Oui</span>";}else{echo "<span class='noir'>Non</span>";} ?></th>
				<th <?php if($contrat['STATUT_CONTRAT'] == "Validé"){echo "class='rose'";}else if($contrat['STATUT_CONTRAT'] == "Annulé"){echo "class='alert'";}else if($contrat['STATUT_CONTRAT'] == "Annulé après validation"){echo "class='orange'";} ?>><?php echo $contrat['STATUT_CONTRAT']; ?></th>
				<th><a href="<?php echo $oCL_page->getPage('contrat')."?id_contrat=".$contrat['ID_CONTRAT']; ?>"><img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="Voir le contrat" title="Voir le contrat" /></a></th>
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