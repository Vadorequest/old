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
	<h2>Paiement annulé !</h2><br />
	<p>
		<br />
		Vous avez annulé le paiement en cours. L'opération a été annulée et votre compte n'a pas été débité.<br />
		<br />
		Sachez que le paiement est géré par PayPal et donc entièrement sécurisé:<br />
		<p>
			Le service que propose PayPal est de payer en ligne sans communiquer ses données financières, en s’identifiant simplement avec son adresse électronique et un mot de passe.<br />
			Il n'est pas nécessaire d'alimenter son compte PayPal à l'avance.<br />
			La source d'approvisionnement que vous avez choisie (carte de paiement ou compte bancaire) est automatiquement débitée au moment de la transaction.<br />
			Les destinataires sont avertis par courriel dès que vous leur envoyez de l’argent.<br />
			PayPal peut également permettre de transférer des fonds d’un compte vers un autre internaute à condition que le destinataire ait un compte PayPal.<br />
			Ce service est gratuit si vous choisissez un compte bancaire comme source d'approvisionnement, ou s'il y a de l'argent disponible sur votre compte PayPal.<br />
		</p>
	Pour plus d'informations, nous vous invitons à <a href="https://cms.paypal.com/fr/cgi-bin/marketingweb?cmd=_render-content&content_ID=marketing_fr/particuliers_acheter&nav=0.1">visiter leur site officiel.</a>
		
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>