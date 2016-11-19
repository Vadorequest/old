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
	<h2>Statistiques globales:</h2><br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Revenus globaux:</legend>
		<br />
		<table>
			<tr class="formulaire">
				<th width="40%" height="30px">Entité:</th>
				<th width="40%">Revenu total:</th>
				<th width="20%">Nombre d'achats:</th>				
			</tr>
			<tr><th colspan="3"><hr /></th></tr>
			<tr>
				<th height="30px" class="formulaire">Annonces GoldLive:</th>
				<th>Entre <span class="valide"><?php echo $revenu_min_goldlive; ?>€</span> et <span class="valide"><?php echo $revenu_max_goldlive; ?>€</span>.</th>
				<th><?php echo $goldlives[0]['nb_annonce']; ?></th>
			</tr>
			<?php
			foreach($packs as $key=>$pack){
				if($pack['PRIX_BASE'] > 0){
			?>
					<tr><th colspan="3"><hr /></th></tr>
					<tr>
						<th height="30px" class="formulaire"><?php echo $pack['NOM']; ?>:</th>
						<th><?php echo $pack['gains']; ?>€</th>
						<th><?php echo $pack['nb_achats']; ?></th>
					</tr>
			<?php
				}
			}
			?>
			<tr><th colspan="3"><hr /></th></tr>
			<tr>
				<th height="30px" class="formulaire">Total:</th>
				<th>Entre <span class="valide"><?php echo $gain_total+$revenu_min_goldlive; ?>€</span> et <span class="valide"><?php echo $gain_total+$revenu_max_goldlive; ?>€</span></th>
				<th><?php echo $achats_totaux; ?> packs et <?php echo $goldlives[0]['nb_annonce']; ?> annonces goldlive.</th>
			</tr>			
		</table>
		<br />
	</fieldset>
	<br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Nombre de connectés:</legend>
		<br />
		<?php
		if($nb_connectes[0]['nb_personne'] > 1){
		?>
			Il y a actuellement <?php echo $nb_connectes[0]['nb_personne']; ?> membres connectés sur le site.<br />
		<?php
		}else{
		?>
			Il y a actuellement <?php echo $nb_connectes[0]['nb_personne']; ?> membre connecté sur le site.<br />
		<?php
		}
		?>
		<br />
	</fieldset>

<?php	
}else{
# Si l'internaute n'est pas connecté et admin il gicle.
header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>