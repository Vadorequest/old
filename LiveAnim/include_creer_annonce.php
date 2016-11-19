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
	<img style="" alt="Créer une annonce"
 src="images/creer-annonce.png">
<br />
<br />
	<span class="rose">Vous souhaitez créer une annonce car vous recherchez des artistes pour une soirée ?<br />
	Vous êtes sur la bonne page !<br /></span>
	<br />
	<span class="orange petit">L'équipe de LiveAnim vous rapelle qu'il est interdit de mentionner -de quelque manière que ce soit- vos coordonnées.<br />
	Tout ce qui doit être affiché le sera automatiquement. N'oubliez pas que chaque annonce devra être validée par l'administration avant d'être visible.</span><br />
	<br />
	
	<?php
		require_once("include_form_ajouter_modifier_annonce.php");
	?>

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>