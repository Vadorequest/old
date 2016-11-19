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
<img style="" alt="Lien parrainage"
 src="images/liens-parrainage.png">
<br />
<br />
	Voici votre lien de parrainage, il vous suffit ce copier-coller le code fournit selon l'utilisation voulue afin que l'image apparaisse.<br />
	<?php
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
	?>
	À noter que plus votre pack est important plus le choix ci-dessous sera important. <br /><span class="petit">25/09/2011: Pour le moment aucune différence mais des ajouts selon les packs sont prévus à long terme.)</span><br />
	<?php
	}
	?>
	<br />
	<a href="<?php echo $lien ?>"><img src="<?php echo $image ?>" alt="Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !" title="Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !" /></a><br />
	<br />
	Code pour les sites webs (HTML):<br />
	<textarea cols="80" rows="5"><a href="<?php echo $lien ?>"><img src="<?php echo $image ?>" alt="Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !" title="Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !" /></a>
	</textarea><br />
	<br />
	<hr />
	<br />
	<a href="<?php echo $lien ?>">Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !</a><br />
	<br />
	Lien hypertexte simple:<br />
	<textarea cols="80" rows="5"><a href="<?php echo $lien ?>">Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !</a>
	</textarea><br />
	<br />

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>