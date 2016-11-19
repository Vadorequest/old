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
	<h2>Voir les packs:</h2><br />
	<br />
	<br />
	<fieldset><legend class="legend_basique">Liste de tous les packs existants:</legend>
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr class="formulaire">
			<th width="20%" scope="col">Nom:</th>
			<th width="15%" scope="col">Prix:</th>
			<th width="20%" scope="col">Type:</th>
			<th width="15%" scope="col">Durée:</th>
			<th width="10%" scope="col">Activé:</th>
			<th width="20%" scope="col">Fiche détaillée:</th>
		</tr>
		<?php
		foreach($packs as $key=>$pack){
		?>
		<tr><th colspan="6"><hr /></th></tr>
		<tr>
			<th scope="col"><span title="<?php echo "ID N°".$pack['ID_PACK']; ?>"><?php echo $pack['NOM']; ?></th>
			<th scope="col"><?php echo $pack['PRIX_BASE']; ?></th>
			<th scope="col"><?php echo $pack['TYPE_PACK']; ?></th>
			<th scope="col"><?php echo $pack['DUREE']." mois"; ?></th>
			<th scope="col"><?php if($pack['VISIBLE']){echo "<span class='valide'>Oui</span>";}else{echo "<span class='orange'>Non</span>";} ?></th>
			<th scope="col"><a href="<?php echo $oCL_page->getPage('modifier_fiche_pack')."?id_pack=".$pack['ID_PACK']; ?>"><img src="images/voir.jpg" alt="Consulter la fiche détaillée" title="Consulter la fiche détaillée" /></a></th>
		</tr>
		
		<?php
		}
		?>
	</table>
	</fieldset>
	
	
<?php
}else{
# Si l'internaute n'est pas connecté et admin il gicle.
header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>