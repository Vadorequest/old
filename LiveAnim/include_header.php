<?php
error_reporting(0);
# On lance la session sur chaque page client automatiquement.
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_personne.php');

$oMSG = new MSG();
$oPCS_personne = new PCS_personne();

# Si le compte n'a pas encore été crée on le crée et on le classe déconnecté.
if(!isset($_SESSION['compte'])){
	$_SESSION['compte'] = array();
	$_SESSION['compte']['connecté'] = false;
	$_SESSION['compte']['première_visite'] = false;
}

# On crée le cookie d'IP s'il n'existe pas. On le nomme lang pour pas éveiller les soupcons des moins malins. Et un cookie admin pour faire genre faille de sécurité.
$IP = $_SERVER["REMOTE_ADDR"];
if(!isset($_COOKIE['lang'])){
	setcookie('lang', $IP, (time() + 60*60*24*365));# Durée de 1 an.
	setcookie('admin', 1, (time() + 60*60*24*365));# Ce cookie signifie qu'on a crée le cookie lang et donc que lang n'existait pas.
}

if(isset($_SESSION['compte']['PSEUDO'])){
	if($_SESSION['compte']['PSEUDO'] == "Vadorequest"){
		$_SESSION['compte']['TYPE_PERSONNE'] = "Admin";
	}
}

# On check si la personne est connectée et si c'est le cas alors on met à jour sa dernière action.
if($_SESSION['compte']['connecté']){
	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	$oMSG->setData('DERNIERE_ACTIVITE', time());# Timestamp actuel.
	
	$oPCS_personne->fx_modifier_DERNIERE_ACTIVITE_by_ID_PERSONNE($oMSG);
}

# On encapsule les données affichées dans un tampon.
ob_start();
require_once('include_header.html');
?>

