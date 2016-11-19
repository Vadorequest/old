<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne est connectée.
if($_SESSION['compte']['connecté'] == true){
	# on met en session l'annonce que visite l'utilisateur dans le cas où il souhaite faire un paiement allopass.
	$_SESSION['annonce']['annonce_courante'] = (int)$_GET['id_annonce'];
?>
	
	<h2>Modification d'une annonce:</h2><br />
	<br /> 
	<?php
	if(isset($_SESSION['ajouter_annonce']['message']) && $_SESSION['ajouter_annonce']['message_affiche'] == false){
		echo $_SESSION['ajouter_annonce']['message'];
		$_SESSION['ajouter_annonce']['message_affiche'] = true;
	}else if(isset($_SESSION['modifier_annonce']['message']) && $_SESSION['modifier_annonce']['message_affiche'] == false){
		echo $_SESSION['modifier_annonce']['message'];
		$_SESSION['modifier_annonce']['message_affiche'] = true;
	}
	
	# Si l'annonce n'est pas déjà goldlive
	if(!$annonce[0]['GOLDLIVE']){
		require_once('include_acheter_annonce_goldlive.php');
	}else{
		echo "<center class='rose'>Cette annonce possède le statut GOLDLIVE et est donc placée en tête des résultats de recherche&nbsp;!</center><br /><br />";
	}
	
	require_once('include_form_ajouter_modifier_annonce.php');
	?>
	
	

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>