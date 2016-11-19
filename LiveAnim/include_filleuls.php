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
<img style="" alt="filleuls"
 src="images/mes-filleuls.png">
<br />
<br />
	Voici la liste de tous vos filleuls.<br />
	<?php
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
	?>
		Selon votre pack vous verrez apparaître plus ou moins d'informations, notamment le pourcentage total que chaque filleul vous a fait gagner et le pourcentage global.<br />
		<span class="petit">(Non effectué pour le moment.)</span><br />
	<?php
	}
	?>
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Liste de mes filleuls:</legend>
		<table width="100%" border="0" cellspacing="1" cellpadding="1"><br />
			<tr class="formulaire">
				<th width="30" scope="col">Pseudo:</th>
				<th width="40"scope="col">Identité:</th>
				<th width="30"scope="col">Statut:</th>
			</tr>
			<tr><th colspan="3"><hr /></th></tr>
			<?php
			foreach($filleuls as $key=>$filleul){
			?>
			<tr height="50px">
				<th class="rose"><?php if($filleul['TYPE_PERSONNE'] == "Prestataire"){?><a href="<?php echo $oCL_page->getPage('personne', 'absolu')."?id_personne=".$filleul['ID_PERSONNE']; ?>"><?php echo $filleul['PSEUDO'];?></a><?php }else{echo $filleul['PSEUDO'];} ?></th>
				<th><?php echo $filleul['CIVILITE']." ".$filleul['NOM']." ".$filleul['PRENOM']; ?></th>
				<th class="valide"><?php echo $filleul['TYPE_PERSONNE']; ?></th>
			</tr>
			<tr><th colspan="3"><hr /></th></tr>
			<?php
			}
			?>
		</table><br />
		<br />
	</fieldset>


<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>