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
	<h2>Liste des pubs:</h2><br />
	<br />
	<?php
	if(isset($_SESSION['liste_pubs_admin']['message']) && $_SESSION['liste_pubs_admin']['message_affiche'] == false){
		echo $_SESSION['liste_pubs_admin']['message'];
		$_SESSION['liste_pubs_admin']['message_affiche'] = true;
	}
	?>
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Liste des pubs:&nbsp;</legend><br />
		<br />
		<form action="script_supprimer_pubs.php" method="post" name="form_supprimer_pubs">
			<table width="100%">
				<tr class="formulaire">
					<th>Titre:</th>
					<th>Position:</th>
					<th>Voir:</th>
					<th>Supprimer:</th>
				</tr>
				<?php
				if(empty($pubs)){
					?>
					<tr><th colspan="4"><hr /><br /></th></tr>
					<tr><th class="orange petit" colspan="4">Il n'y a aucune pub à afficher.</th></tr>
					<?php
				}
				
				foreach($pubs as $key => $pub){
				?>
				<tr><th colspan="4"><hr /></th></tr>
				<tr>
					<th><?php echo $pub['TITRE']; ?></th>
					<th><?php echo $pub['POSITION']; ?></th>
					<th><a href="<?php echo $oCL_page->getPage('modifier_pub')."?id_pub=".$pub['ID_PUB']; ?>"><img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="Modifier la pub" title="Modifier la pub" /></a></th>
					<th><input type="checkbox" name="form_supprimer_pubs_id_pub[]" value="<?php echo $pub['ID_PUB']; ?>" /></th>
				</tr>
				<?php
				}
				?>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<?php
					# On affiche le bouton que s'il existe des pubs.
					if(!empty($pubs)){
					?>
						<th>
							<input height="20px" width="20px" onclick="return confirm('Souhaitez vous vraiment supprimer les pubs sélectionnées ?');" type="image" src="<?php echo $oCL_page->getImage('petite_croix'); ?>" alt="Supprimer les pubs sélectionnées" title="Supprimer les pubs sélectionnées" />
						</th>
					<?php
					}
					?>
			</table>
			<br />
		</form>
		<br />
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>