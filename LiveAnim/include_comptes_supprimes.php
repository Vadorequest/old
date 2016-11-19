<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
?>
	<h2>Liste des comptes supprimés par les utilisateurs:</h2><br />
	<br />
	Tous les comptes suivants ont été supprimés par leur propriétaire - ou un tiers autre que notre administration -, connaître la raison de la suppression peut vous aider dans vos relations clients.<br />
	Vous pouvez contacter les membres en questions si vous pensez que la raison qu'ils ont donnée n'est pas valable dans le but de les faire changer d'avis.<br />
	Notez que les comptes ne sont supprimés que deux mois après leur date de suppression, ils peuvent donc récupérer toutes leurs informations sans perte de données.<br />
	<br />

	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr class="valide">
			<th width="20%" scope="col">Pseudo:</th>
			<th width="20%" scope="col">Infos:</th>
			<th width="20%" scope="col">Téléphone(s):</th>
			<th width="40%" scope="col">Raison:</th>
		</tr>
		<tr><th colspan="4"><hr /></th></tr>
		<?php
		while($compte_supprime = $comptes_supprimes->fetch(PDO::FETCH_ASSOC)){
		?>
			<tr>
				<th><span title="<?php echo "ID N°".$compte_supprime['ID_PERSONNE']; ?>"><a href="<?php echo $oCL_page->getPage('modifier_fiche_membre')."?id_personne=".$compte_supprime['ID_PERSONNE']; ?>"><?php echo $compte_supprime['PSEUDO']; ?></a></span></th>
				<th><?php echo $compte_supprime['CIVILITE']." ".$compte_supprime['NOM']." ".$compte_supprime['PRENOM']."<br />".$compte_supprime['EMAIL']; ?></th>
				<th><?php echo $compte_supprime['TEL_FIXE']."<br />".$compte_supprime['TEL_PORTABLE'] ?></th>
				<th><?php echo $compte_supprime['RAISON_SUPPRESSION']; ?></th>
			</tr>
			<tr><th colspan="4"><hr /></th></tr>
		<?php
		}
		?>
	</table>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>