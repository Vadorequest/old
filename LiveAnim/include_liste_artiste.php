<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

?>
	<img style="" alt="Les artistes"
 src="images/les-divers-artistes.png">
<br />
<br />
	
	<strong><u>Recherche d'artistes:</u></strong>&nbsp;&nbsp;&nbsp;<img id="img_plus_moins" onclick="fx_affiche('recherche_artiste', 'img_plus_moins');" src="<?php echo $oCL_page->getImage('petit_plus'); ?>" alt="Afficher/Cacher le formulaire de recherche" title="Afficher/Cacher le formulaire de recherche" /><br />
	<div id="recherche_artiste">
		<br />
		<form class="formulaire" action="script_recherche_artiste.php" method="post" id="form_recherche_artiste">
			Quels genre d'artiste recherchez vous ? <select name="form_recherche_artiste_role">
				<option value="">Tous</option>
				<?php
				foreach($types as $key=>$type){
				?>
					<option value="<?php echo $type['ID_TYPES']; ?>" <?php if($_SESSION['recherche_artiste']['ROLES'] == $type['ID_TYPES']){echo "selected='selected'";} ?>><?php echo $type['ID_TYPES']; ?></option>
				<?php
				}
				?>
			</select><br />
			<br />
			<label for="form_recherche_artiste_statut">Uniquement les artistes ayant un statut professionnel&nbsp;&nbsp;</label><input type="checkbox" name="form_recherche_artiste_statut" id="form_recherche_artiste_statut" <?php if($_SESSION['recherche_artiste']['STATUT_PERSONNE'] == "Pro"){echo "checked='checked'";} ?> /><br />
			<br />
			<label for="form_recherche_artiste_departements">Uniquement dans les départements suivants:</label><br />
			<span class="petit">(Rentrez les numéros des départements séparés par des virgules)</span><br />
			<input type="text" name="form_recherche_artiste_departements" id="form_recherche_artiste_departements" value="<?php if(isset($_SESSION['recherche_artiste']['DEPARTEMENTS'])){echo $_SESSION['recherche_artiste']['DEPARTEMENTS'];} ?>" /><br />
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
		if(isset($_SESSION['liste_artiste']['message']) && $_SESSION['liste_artiste']['message_affiche'] == false){
			echo $_SESSION['liste_artiste']['message'];
			$_SESSION['liste_artiste']['message_affiche'] = true;
		}
	?>
	
	
	<?php
	if($nb_result[0]['nb_personne'] > $nb_result_affiches){
		$path_parts = pathinfo($_SERVER['PHP_SELF']);
		$page = $path_parts["basename"];
		$page_actuelle = ($limite/$nb_result_affiches)+1;
		afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_personne'], $page_actuelle);
	}
	?>
	
	<?php
	if(isset($_GET['role'])){
	?>
		<b>Voici la liste des <span class="rose"><?php echo $_GET['role']; ?></span></b>:<br />
		<br />
	<?php
	}
	?>
	
	<table id="resultats_recherche">
		<tr>
			<td colspan="5">
				<br /><hr /><br />
			</td>
		</tr>	
	<?php
	foreach($prestataires as $key=>$prestataire){
	?>
		<tr>
			<td width="15%">
				<img class="image_border" src="<?php if(!empty($prestataire['URL_PHOTO_PRINCIPALE'])){echo $prestataire['URL_PHOTO_PRINCIPALE'];}else{echo $oCL_page->getImage("avat_test1");} ?>" alt="<?php if($_SESSION['pack']['CV_VISIBILITE'] >= 4){echo "Photo de: ".$prestataire['PSEUDO'];}else{} ?>" title="<?php if($_SESSION['pack']['CV_VISIBILITE'] >= 4){echo "Photo de: ".$prestataire['PSEUDO'];}else{echo "Photo du membre N°".$prestataire['ID_PERSONNE'];} ?>" />
			</td>
			<th width="25%">
				<?php
				if($_SESSION['pack']['CV_VISIBILITE'] >= 4){
					echo $prestataire['CIVILITE']."<br />".$prestataire['NOM']."<br />".$prestataire['PRENOM']."<br /><br /><span class='petit'>(".$prestataire['PSEUDO'].")</span>";
				}else{
					echo "Membre N°".$prestataire['ID_PERSONNE'];
				}
				?>
			</th>
			<td width="30%">
				<?php 
				if($prestataire['STATUT_PERSONNE'] == "Pro"){
				?>
					<span class="valide">Ce prestataire possède un statut professionnel.</span>
				<?php
				}else{
				?>
					Ce prestataire ne possède pas de statut professionnel.
				<?php
				}
				?>
			</td>
			<th width="25%">
				<?php echo $prestataire['ROLES']; ?>
			</th>
			<th width="5%">
				<a href="<?php echo $oCL_page->getPage('personne')."?id_personne=".$prestataire['ID_PERSONNE']; ?>"><img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="Voir la fiche <?php if($_SESSION['pack']['CV_VISIBILITE'] >= 4){ echo "de ".$prestataire['PSEUDO'];}else{ echo "du membre N°".$prestataire['ID_PERSONNE'];} ?>" title="Voir la fiche <?php if($_SESSION['pack']['CV_VISIBILITE'] >= 4){ echo "de ".$prestataire['PSEUDO'];}else{ echo "du membre N°".$prestataire['ID_PERSONNE'];} ?>" /></a>
			</th>
		</tr>
		<tr>
			<td colspan="5">
				<br /><hr /><br />
			</td>
		</tr>	
	<?php
	}
	?>
	</table>
	
	<?php
	if($nb_result[0]['nb_personne'] > $nb_result_affiches){
		$path_parts = pathinfo($_SERVER['PHP_SELF']);
		$page = $path_parts["basename"];
		$page_actuelle = ($limite/$nb_result_affiches)+1;
		afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_personne'], $page_actuelle);
	}
	?>
	<br />
	<center class="petit noir">La recherche a retournée un total de <?php echo $nb_result[0]['nb_personne']; ?> résultat(s).</center><br />
	
	<script type="text/javascript">
		initialiser_div('recherche_artiste');
	</script>
	