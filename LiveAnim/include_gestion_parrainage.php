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
	<h2>Gestion du parrainage:</h2><br />
	<br />
	
	<fieldset class="padding_LR"><legend class="legend_basique">Tous les parrains</legend>
		<br />
		<table>
			<tr class="formulaire">
				<th width="20%">Pseudo:</th>
				<th width="30%">Identité:</th>
				<th width="40%">Email:</th>
				<th width="10%">Nombre de filleuls:</th>
			</tr>
			<?php
			foreach($parrains as $key => $parrain){
			?>
				<tr><th colspan="4"><hr /></th></tr>
				<tr height="30px">
					<th><a href="<?php echo $oCL_page->getPage('personne')."?id_personne=".$parrain['ID_PERSONNE']; ?>"><?php echo $parrain['PSEUDO']; ?></a></th>
					<th><?php echo $parrain['CIVILITE'].". ".$parrain['NOM']." ".$parrain['PRENOM'] ; ?></th>
					<th><?php echo $parrain['EMAIL']; ?></th>
					<th><?php echo $parrain['nb_filleuls']; ?></th>
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