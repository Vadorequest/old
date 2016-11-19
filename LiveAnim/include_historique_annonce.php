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
	<img style="" alt="Historique des annonces"
 src="images/historique-annonces.png">
<br />
<br />
	<?php
	if(isset($_SESSION['historique_annonce']['message']) && $_SESSION['historique_annonce']['message_affiche'] == false){
		echo $_SESSION['historique_annonce']['message'];
		$_SESSION['historique_annonce']['message_affiche'] = true;
	}
	?>
	<?php
		if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
		}
	?>
	<table width="100%">
		<tr class="valide">
			<th scope="col">Titre:</th>
			<th scope="col">Date de création:</th>
			<th scope="col">Date de début:</th>
			<th scope="col">Type:</th>
			<th scope="col">Statut</th>
			<th scope="col">Voir</th>
		</tr>
		
		<?php
		if($nb_result[0]['nb_annonce'] == 0){
		?>
			<tr><td colspan="6"><hr /></td></tr>
			<tr><td colspan="6"><br /></td></tr>
			<tr><td colspan="6"><center class='orange'>Vous n'avez pas publié d'annonce.</center></td></tr>
		<?php
		}
		
		foreach($annonces as $key=>$annonce){
		?>
		<tr><td colspan="6"><hr /></td></tr>
		<tr height="50px">
			<th scope="row" class="rose"><?php echo $annonce['TITRE']; ?></th>
			<th scope="row"><?php echo $annonce['DATE_ANNONCE']; ?></th>
			<th scope="row" title="Fin: <?php echo $annonce['DATE_FIN']; ?>"><?php echo $annonce['DATE_DEBUT']; ?></th>
			<th scope="row"><?php echo $annonce['TYPE_ANNONCE']; ?></th>
			<th scope="row" <?php if($annonce['STATUT'] == "Validée"){echo "class='valide'";}else if($annonce['STATUT'] == "Refusée"){echo "class='alert'";} ?>><?php echo $annonce['STATUT']; ?></th>
			<th scope="row">
				<?php 
				if($annonce['STATUT'] != "Validée"){
				?>
					<a href="<?php echo $oCL_page->getPage('modifier_fiche_annonce')."?id_annonce=".$annonce['ID_ANNONCE'];?>">
				<?php
				}else{
				?>
					<a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce['ID_ANNONCE'];?>">
				<?php 
				}
				?>
					<img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="Voir l'annonce" title="Voir l'annonce" />
				</a>
			</th>
		</tr>
		<?php
		}# Fin du foreach.
		?>
	</table>
	
	
	<?php
		if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
		}
	?>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>