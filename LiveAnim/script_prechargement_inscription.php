<?php
if(!isset($_SESSION)){
	session_start();
}
require_once('couche_metier/CL_page.php');
require_once('couche_metier/PCS_personne.php');
$oCL_page = new CL_page();
$oPCS_personne = new PCS_personne();

# On vérifie qu'on se trouve bien sur la page de l'inscription

require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_types.php');

$oMSG = new MSG();
$oPCS_types = new PCS_types();

# On récupère la liste des types de la famille 'Découverte du site'.
$oMSG->setData('ID_FAMILLE_TYPES', 'Découverte du site');

$connaissance_site = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();

function fx_activer_compte($email, $cle_activation){
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oCL_page = new CL_page();
	
	$oMSG->setData('EMAIL', $email);
	$oMSG->setData('CLE_ACTIVATION', $cle_activation);
	
	$nb_personne = $oPCS_personne->fx_compter_by_EMAIL_et_CLE_ACTIVATION($oMSG)->getData(1)->fetchAll();
	
	if($nb_personne[0]['nb_personne'] == 1){
		$oMSG->setData('CLE_ACTIVATION', "");# On supprime la clé d'activation afin que le compte ne puisse pas être réactivé.
		$oPCS_personne->fx_valider_compte($oMSG);
		
		# On envoi un email à la personne bannie.
		$additional_headers = "From: noreply@liveanim.com \r\n";
		$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
		$destinataires = $EMAIL;
		$sujet = utf8_decode("Alerte LiveAnim [Activation de votre compte]");
		
		$message = "------------------------------\n";
		$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
		$message.= "------------------------------\n\n";
		$message.= utf8_decode("Bonjour, \n");
		$message.= utf8_decode("Nous avons le plaisir de vous informer que votre compte a été activé sur notre site ".$oCL_page->getPage('accueil').". \n");
		$message.= utf8_decode("Vous pouvez donc dès à présent vous connecter et profiter ! \n");
		$message.= utf8_decode("Nous espérons que nos services vous satisferont, nous sommes à votre écoute pour d'éventuelles questions (Via notre page de contact). \n");
		$message.= utf8_decode("Nous vous souhaitons une agréable journée. \n");
		$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
		
		$_SESSION['bannir_membre']['message'] = "<span class='valide'>Le membre $PSEUDO a été banni $duree pour une durée de $duree_bannissement jour(s).<br />";

		
		if(mail($destinataires, $sujet, $message, $additional_headers)){
			# Si le mail a été correctement envoyé:		
			echo "La clé d'activation est valide. Compte activé. Un e-mail a été envoyé.";
		}else{
			echo "La clé d'activation est valide. Compte activé.";
		}
				
	}else{
		if(strlen($nb_personne[0]['CLE_ACTIVATION']) == 0){
			echo "Le compte a déjà été activé.";
		}else{
			echo "La clé d'activation est invalide.";
		}
	}

}

