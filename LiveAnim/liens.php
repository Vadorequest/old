<?php
header('Content-Type: text/html; charset=utf-8');

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_annonce.php');
require_once('couche_metier/PCS_personne.php');

$oMSG = new MSG();
$oPCS_annonce = new PCS_annonce();
$oPCS_personne = new PCS_personne();

# On récupère toutes les annonces.
$oMSG->setData('nb_result_affiches', '');
$oMSG->setData('debut_affichage', '');

$annonces = $oPCS_annonce->fx_recuperer_toutes_annonces_min($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
echo "<h2>Annonces:</h2>";
foreach($annonces as $key => $annonce){
	?>
	<a href="<?php echo $oCL_page->getPage('annonce', 'absolu')."?id_annonce=".$annonce['ID_ANNONCE']; ?>"><?php echo $annonce['TITRE']; ?></a><br />
	<?php
}

# On récupère tous les artistes.
$oMSG->setData('TYPE_PERSONNE', 'Prestataire');
$oMSG->setData('VISIBLE', 1);

$personnes = $oPCS_personne->fx_recuperer_toutes_identites_personnes_by_TYPE_PERSONNE($oMSG)->GetData(1)->fetchAll(PDO::FETCH_ASSOC);
echo "<h2>Artistes:</h2>";
foreach($personnes as $key => $personne){
	?>
	<a href="<?php echo $oCL_page->getPage('personne', 'absolu')."?id_personne=".$personne['ID_PERSONNE']; ?>"><?php echo $personne['CIVILITE'].". ".$personne['NOM']." ".$personne['PRENOM']; ?></a><br />
	<?php
}
?>