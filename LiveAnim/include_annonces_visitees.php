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
<img style="" alt="Annonces débloquées"
 src="images/annonces-debloquees.png">
<br />
<br />
	Voici toutes les annonces que vous avez débloqué. Vous pouvez les consulter sans limite.<br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Liste&nbsp;des&nbsp;annonces&nbsp;que&nbsp;vous&nbsp;avez&nbsp;visité:&nbsp;</legend>
		<table width="100%">
			<?php
			if(is_array($_SESSION['compte']['annonces_visitées'])){
			?>
				<tr height="50" class="formulaire">
					<th>Titre de l'annonce&nbsp;:</th>
					<th>Date&nbsp;:</th>
				</tr>
			<?php 
				foreach($annonces as $key=>$annonce){
				?>
					<tr><th colspan="2"><hr /></th></tr>
					<tr>
						<th><a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce['ID_ANNONCE']; ?>"><?php echo $annonce['TITRE']; ?></a></th>
						<th><?php echo $annonce['DATE_ANNONCE']; ?></th>
					</tr>
				<?php
				}
			}else{
				echo "<tr><th colspan='2'><br /></th></tr><tr><th><span class='orange petit'>Vous n'avez pas encore débloqué d'annonce.</span></th></tr>";
			}
			?>
		</table>
		<br />
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>