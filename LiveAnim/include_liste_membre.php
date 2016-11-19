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
	<h2>Liste des membres:</h2><br />
	<br />
	Voici la liste de tous les membres du site. Vous pouvez obtenir l'ID d'un membre en laissant le curseur sur son Pseudo.<br />
	Vous pouvez obtenir des informations supplémentaires en laissant le curseur sur le Statut.<br />
	Enfin, vous pouvez accéder directement à la fiche détaillée du membre afin de modifier certaines de ses informations.<br />
	<br />
	<fieldset><legend class="legend_basique">Liste des membres.</legend><br />
	<br />

	<?php
		if($nb_result[0]['nb_personne'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_personne'], $page_actuelle);
		}
	?>
	<br />
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr class="valide">
			<th scope="col">Pseudo:</th>
			<th scope="col">Fonction:</th>
			<th scope="col">Statut:</th>
			<th scope="col">Voir la fiche:</th>
		</tr>
		<?php
		if($nb_result[0]['nb_personne'] == 0){
		?>
				<tr><th colspan="5"><hr /></th></tr>
				<tr><th colspan="5" class="orange" height="40px">Il n'y a pas de membre.</th></tr>
		<?php
		}
		?>
		<?php
		while($personne = $personnes->fetch(PDO::FETCH_ASSOC)){
			
			# On formate la date de manière à pouvoir effectuer des calculs dessus.
			$personne['DATE_BANNISSEMENT_formatee'] = new DateTime($personne['DATE_BANNISSEMENT']);
			$personne['DATE_BANNISSEMENT_formatee'] = $personne['DATE_BANNISSEMENT_formatee']->format("Ymd");
			if($personne['DATE_BANNISSEMENT_formatee'][0] == "-"){
				$personne['DATE_BANNISSEMENT_formatee'][0] = "";
			}
			
			# On met la date au format FR.
			$tab_date_suppression = explode("-", $personne['DATE_SUPPRESSION_REELLE']);# On récupère la date dans un tableau de trois cases ([Y][M][D])
			$personne['DATE_SUPPRESSION_REELLE'] = date("d-m-Y", mktime(0, 0, 0, $tab_date_suppression[1], $tab_date_suppression[2],  $tab_date_suppression[0]));
			
			$tab_date_bannissement = explode("-", $personne['DATE_BANNISSEMENT']);# On récupère la date dans un tableau de trois cases ([Y][M][D])
			$personne['DATE_BANNISSEMENT'] = date("d-m-Y", mktime(0, 0, 0, $tab_date_bannissement[1], $tab_date_bannissement[2],  $tab_date_bannissement[0]));
			
			$now = date("d-m-Y");
			$oNOW = new DateTime( $now );
			$now = $oNOW->format("Ymd");
		
			# On effectue le calcul du statut:
			if($personne['VISIBLE'] == true){
				$statut = "Normal";
				$title = "Compte actuellement actif, n'a jamais été banni.";
			}else if($personne['VISIBLE'] == false){
				if($personne['CLE_ACTIVATION'] != ""){
					$statut = "<span class='gris'>Compte non activé</span>";
					$title = "Le compte n'a jamais été activé via le mail d'activation.";
				}else if($personne['PERSONNE_SUPPRIMEE'] == true && ($personne['DATE_BANNISSEMENT_formatee'] <= $now)){
					$statut = "<span class='orange'>Compte supprimé par utilisateur.</span>";
					$title = str_replace("<br />", "\n", $personne['RAISON_SUPPRESSION'])."\n\nDate de suppression effective: ".$personne['DATE_SUPPRESSION_REELLE'];
				}else if($personne['PERSONNE_SUPPRIMEE'] == true && ($personne['DATE_BANNISSEMENT_formatee'] > $now)){
					$statut = "<span class='alert'>Compte supprimé par modérateur.</span>";
					$title = str_replace("<br />", "\n", $personne['RAISON_SUPPRESSION']);
				}else if($personne['PERSONNE_SUPPRIMEE'] == false && ($personne['DATE_BANNISSEMENT_formatee'] > $now)){
					$statut = "<span class='orange'>Compte banni temporairement.</span>";
					$title = str_replace("<br />", "\n", $personne['RAISON_SUPPRESSION'])."\n\nDate de remise en service du compte: ".$personne['DATE_BANNISSEMENT'];
				}else if($personne['PERSONNE_SUPPRIMEE'] == false && ($personne['DATE_BANNISSEMENT_formatee'] <= $now)){
					$statut = "<span class='valide'>Compte débanni le ".$personne['DATE_BANNISSEMENT'].".</span>";
					$title = str_replace("<br />", "\n", $personne['RAISON_SUPPRESSION']);
				}
			}
		?>
			<tr><th colspan="4"><hr /></th></tr>
			<tr>
				<th><span title="<?php echo "ID N°".$personne['ID_PERSONNE']; ?>"><?php echo $personne['PSEUDO']; ?></span></th>
				<th <?php if($personne['TYPE_PERSONNE'] == "Admin" ){echo "class='alert'";} ?>><?php echo $personne['TYPE_PERSONNE']; ?></th>
				<th><span title="<?php echo $title; ?>"><?php echo $statut; ?></span></th>
				<th><a href="<?php echo $oCL_page->getPage('modifier_fiche_membre')."?id_personne=".$personne['ID_PERSONNE']; ?>"><img src="images/voir.jpg" alt="Voir la fiche de <?php echo $personne['PSEUDO']; ?>." title="Voir la fiche de <?php echo $personne['PSEUDO']; ?>." /></a></th>
			</tr>
		<?php
		}
		?>
	</table>
	<br />
	<?php
		if($nb_result[0]['nb_personne'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_personne'], $page_actuelle);
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