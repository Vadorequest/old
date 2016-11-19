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

	if(isset($achat_annonce_goldlive_ok) && $achat_annonce_goldlive_ok == 1){
		# Le paiement par Allopass a fonctionné.
	?>
		<h2>Achat réussi !</h2><br />
		<br />
		Succès de l'opération, votre annonce est à présent <span class="rose">GoldLive</span> et est donc dès maintenant en tête des résultats de recherches !<br />
		<br />
		<center><a href="<?php echo $oCL_page->getPage('gestion_annonce_goldlive'); ?>">Voir mes annonces GoldLive</a></center><br />
	<?php
	}else if(isset($_GET['RECALL']) && $_GET['reussite'] == 0){
		# L'achat via allopass a échoué. Code érroné.
	?>
		<h2>L'opération a échouée !</h2><br />
		<br />
		Le code Allopass que vous avez rentré est erronné, l'annonce n'a pas été mise à jour.<br />
		<br />
		<center><a href="<?php echo $oCL_page->getPage('historique_annonce')."?rq=toutes"; ?>">Retour à la liste de mes annonces</a></center><br />		
	<?php
	}else if(isset($_GET['reussite']) && $_GET['reussite'] == 1){
		# L'achat via paypal ou allopass a fonctionné.
	?>
		<h2>Achat réussi !</h2><br />
		<br />
		Succès de l'opération, votre annonce est à présent <span class="rose">GoldLive</span> et est donc dès maintenant en tête des résultats de recherches !<br />
		<br />
		<center><a href="<?php echo $oCL_page->getPage('gestion_annonce_goldlive'); ?>">Voir mes annonces GoldLive</a></center><br />
	<?php
	}else{
		# L'achat via paypal a été arrêté.
	?>
		<h2>Opération annulée !</h2><br />
		<br />
		L'opération a été annulée à votre demande, votre compte n'a pas été débité et l'annonce ne possède le statut <span class="rose">GoldLive</span>.<br />
		Elle est donc moins visible que toutes les annonces <span class="rose">GoldLive !</span><br />
		<br />
		<center><a href="<?php echo $oCL_page->getPage('historique_annonce')."?rq=toutes"; ?>">Retour à la liste de mes annonces</a></center><br />		
	<?php
	}

}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>