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
	<img style="" alt="Historique achat"
 src="images/historique-achat.png">
<br />
<br />
	Voici l'historique de tous vos achats de packs du plus récent au plus ancien.<br />
	<br />
	Des informations supplémentaires apparaissent lorsque vous laissez le curseur sur certains éléments. (Réduction apportée (en €), dates exactes, ..)<br />
	<br />
	
	<fieldset><legend class="legend_basique">Liste de mes packs achetés.</legend><br />
	<br />

	<?php
		if($nb_result[0]['nb_pack'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_pack'], $page_actuelle);
		}
	?>
	<br />
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr class="valide">
			<th width="10%" scope="col">Pack</th>
			<th width="5%" scope="col">Durée <br /><span class='petit'>(mois)</span></th>
			<th width="10%" scope="col">Prix <br />initial</th>
			<th width="10%" scope="col">Réduction<br /></th>
			<th width="10%" scope="col">Prix <br />payé</th>
			<th width="20%" scope="col">Date <br />d'achat</th>
			<th width="15%" scope="col">Date <br />d'activation</th>
			<th width="20%" scope="col">Fin de <br />validité</th>
		</tr>
		<tr><th colspan="9"><hr /></th></tr>
		<?php
			if(empty($pack_personne)){
				echo "<tr><th colspan='9' class='orange petit'><br />Vous n'avez jamais effectué d'achat de pack.</th></tr>";
			}
			foreach($packs_personne as $key=>$pack_personne){
			# On compte le nombre de packs activables.
			if($pack_personne['DATE_DEBUT_formatee'] > $now_formatee){
				$nb_packs_activables++;# Cette variable servira à afficher le fieldset d'activation des packs.
			}
			
		?>
		<tr <?php if($_SESSION['pack']['DATE_ACHAT'] == $pack_personne['DATE_ACHAT']){echo "class='selectionne'";} ?> height="50px" title="Ce pack vous a couté la somme de <?php echo $pack_personne['prix_reel'] ?>€, vous avez bénéficié de <?php echo $pack_personne['REDUCTION'] ?>% de réduction soit <?php echo $pack_personne['reduction_reelle'] ?>€.">
			<th scope="col" class="rose"><?php echo $pack_personne['NOM'] ?></th>
			<th scope="col"><?php echo $pack_personne['DUREE'] ?></th>
			<th scope="col"><?php echo $pack_personne['PRIX_BASE'] ?>€</th>
			<th scope="col"><?php echo $pack_personne['REDUCTION'] ?>%</th>
			<th title="Soit une réduction de <?php echo $pack_personne['reduction_reelle'] ?>€." scope="col"><?php echo $pack_personne['prix_reel'] ?>€</th>
			<th title="<?php echo $pack_personne['DATE_ACHAT'] ?>" scope="col" class="rose"><?php echo $pack_personne['DATE_ACHAT_simple'] ?></th>
			<th title="<?php echo $pack_personne['DATE_DEBUT'] ?>" scope="col" class="valide"><?php echo $pack_personne['DATE_DEBUT_simple'] ?></th>
			<th title="<?php echo $pack_personne['DATE_FIN'] ?>" scope="col" class="orange"><?php echo $pack_personne['DATE_FIN_simple'] ?></th>
		</tr>
		<tr><th colspan="9"><hr /></th></tr>
		<?php
		}
		?>
		</table>
		<br />
		<?php
		if($nb_result[0]['nb_pack'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_pack'], $page_actuelle);
		}
		?>
	<br />
	</fieldset><br />
	<br />
	<br />
	<fieldset id="achat_pack" class="padding_LR"><legend class="legend_basique">Activer un pack:</legend><br />
		<?php
			if(isset($_SESSION['historique_achat_pack']['message']) && $_SESSION['historique_achat_pack']['message_affiche'] == false){
				echo $_SESSION['historique_achat_pack']['message'];
				$_SESSION['historique_achat_pack']['message_affiche'] = true;
			}
		?>
		<br />
		Vous pouvez activer ici le pack sélectionné.<br />
		Cette opération peut-être utile si vous souhaitez, par exemple, activer un pack Live Max alors que vous êtes sous un pack Live Small/Medium.<br />
		<br />
		Cette opération <span class="orange">mettra fin à votre pack actuel</span>, le temps qu'il reste sur votre pack actuel <span class="orange">sera perdu et non remboursable.</span><br />
		<br />
		<span class="orange">Cette opération étant irréversible <span class="petit">(à moins de contacter le service client)</span> veuillez faire attention à ce que vous faites.</span><br />
		<br />
		<?php
		if($nb_packs_activables != 0){
		?>
		<form name="form_activer_pack" id="form_activer_pack" method="post" action="script_historique_achat_pack_activer_pack.php">
			<select name="form_activer_pack_date_achat">
				<?php
				foreach($packs_personne as $key=>$pack_personne){
					if($now_formatee < $pack_personne['DATE_DEBUT_formatee']){
					?>
						<option value="<?php echo $pack_personne['DATE_ACHAT_en'] ?>">Pack <?php echo $pack_personne['NOM'] ?>, acheté le <?php echo $pack_personne['DATE_ACHAT'] ?>. (à <?php echo $pack_personne['prix_reel'] ?>€)</option>
					<?php
					}#Fin du if
				}#Fin du foreach
				?>
			</select>
			&nbsp;&nbsp;<input type="image" src="images/ok.gif" alt="Activer le pack" title="Activer le pack" onclick="return confirm('Voulez vous vraiment activer ce pack maintenant ?\nCette opération mettra définitivement fin à votre pack actuel et activera le pack sélectionné.\n');" />
			<br />
		</form>
		<?php
		}else{
			echo "<br /><center><span class='orange'>Il n'y a pas de pack d'activable pour le moment.</span></center><br />";
		}
		?>
		<br />
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>