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
	<h2>Paiement réussi !</h2><br />
	<br />
	<p>
	<span class="valide">Votre achat a bien été réalisé, vous pouvez dès à présent activer votre nouveau pack si vous le souhaitez.</span><br />
	Vous pouvez visionner toutes les informations concernant votre compte dans votre profil s'il a été activé.<br />
	Si vous avez préféré attendre la fin de validité de votre compte actuel, sachez que votre nouveau compte s'activera automatiquement dès que celui-ci sera fini.<br />
	</p><br />
	
	

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>