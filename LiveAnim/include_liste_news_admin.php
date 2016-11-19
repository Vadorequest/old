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
	<h2>Liste des news:</h2><br />
	<br />
	
	<?php
	if(isset($_SESSION['liste_news_admin']['message']) && $_SESSION['liste_news_admin']['message_affiche'] == false){
		echo $_SESSION['liste_news_admin']['message'];
		$_SESSION['liste_news_admin']['message_affiche'] = true;
	}
	?>
	
	<?php
		if($nb_result[0]['nb_nouveautees'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_nouveautees'], $page_actuelle);
		}
	?>
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Liste&nbsp;des&nbsp;news&nbsp;:&nbsp;</legend>
		<br />
		<table width="100%">
			<tr class="valide">
				<th scope="col">News</th>
				<th scope="col">Auteur</th>
				<th scope="col">Titre</th>
				<th scope="col">Date de création</th>
				<th scope="col">Visible</th>
				<th scope="col">Voir</th>
			</tr>
			<?php
			foreach($nouveautees as $key=>$nouveautee){
			?>
			<tr><th colspan="6"><hr /><br /></th></tr>
			<tr>
				<th scope="row"><a href="<?php echo $oCL_page->getPage('modifier_news')."?id_news=".$nouveautee['ID_NOUVEAUTE']; ?>"><img class="petite_image_border" src="<?php echo $nouveautee['URL_PHOTO']; ?>" alt="<?php echo "Nouveautée N°".$nouveautee['ID_NOUVEAUTE']; ?>" title="<?php echo "Nouveautée N°".$nouveautee['ID_NOUVEAUTE']; ?>" /></a></th>
				<th class="rose"><?php echo $nouveautee['AUTEUR']; ?></th>
				<th><?php echo $nouveautee['TITRE']; ?></th>
				<th title="<?php echo $nouveautee['DATE_CREATION_formatee']; ?>"><?php echo str_replace(' ', '<br />', $nouveautee['DATE_CREATION']); ?></th>
				<th><?php if($nouveautee['VISIBLE']){echo "<span class='rose'>Oui</span>";}else{echo "<span class='alert'>Non</span>";} ?></th>
				<th><a href="<?php echo $oCL_page->getPage('modifier_news')."?id_news=".$nouveautee['ID_NOUVEAUTE']; ?>"><img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="Voir la news en détails" title="Voir la news en détails" /></a></th>
			</tr>
			<?php
			}
			?>
		</table>
		<br />
	</fieldset>
	<br />
	
	
	<?php
		if($nb_result[0]['nb_nouveautees'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_nouveautees'], $page_actuelle);
		}
	?>

<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>