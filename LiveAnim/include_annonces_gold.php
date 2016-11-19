<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_annonce.php');
require_once('couche_metier/PCS_contrat.php');
require_once('couche_metier/PCS_personne.php');
require_once('couche_metier/PCS_types.php');
require_once('couche_metier/CL_date.php');

$oMSG = new MSG();
$oPCS_annonce = new PCS_annonce();
$oPCS_contrat = new PCS_contrat();
$oPCS_personne = new PCS_personne();
$oPCS_types = new PCS_types();
$oCL_date = new CL_date();

$oMSG->setData('VISIBLE', 1);
$oMSG->setData('STATUT', 'Validée');
$oMSG->setData('criteres', 'AND annonce.DATE_DEBUT > NOW()');

$oMSG->setData('nb_result_affiches', 5);
$oMSG->setData('debut_affichage', 0);

$annonces_ = $oPCS_annonce->fx_recuperer_annonces_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

# On met en forme les données:
foreach($annonces_ as $key=>$annonce){
	# Les dates:
	$annonces_[$key]['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($annonce['DATE_DEBUT'], true, false, 'en', 'fr');
	$annonces_[$key]['DATE_DEBUT_simple'] = split(' ',$oCL_date->fx_convertir_date($annonce['DATE_DEBUT'], true));
	$annonces_[$key]['DATE_DEBUT_simple'] = $annonces_[$key]['DATE_DEBUT_simple'][0];
	
	$annonces_[$key]['DATE_FIN'] = $oCL_date->fx_ajouter_date($annonce['DATE_FIN'], true, false, 'en', 'fr');
	$annonces_[$key]['DATE_FIN_simple'] = split(' ',$oCL_date->fx_convertir_date($annonce['DATE_FIN'], true));
	$annonces_[$key]['DATE_FIN_simple'] = $annonces_[$key]['DATE_FIN_simple'][0];
	
	$annonces_[$key]['DATE_ANNONCE'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($annonce['DATE_FIN'], true, false, 'en', 'fr'), true, 'fr');
	
}

?>
<img style="" alt="Annonces Gold"  src="/images/annonces-gold.png"><br /><br />
	<?php
	foreach($annonces_ as $key=>$annonce){
	?>
		<div class="padding_LR">
			<img class="fleft" width="150px" height="120px" alt="Image annonce" src="<?php echo $oCL_page->getImage('disco1'); ?>" />
			<div class="fleft" style="padding: 0 10px 0 10px;color:#F300FF">Annonce postée le <?php echo $annonce['DATE_ANNONCE'] ?>.</div><br />
			<b style="padding-left:5%"><u><a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce['ID_ANNONCE']; ?>"><?php echo $annonce['TITRE']; ?></a></u></b><br />
			<br />
			<div style="padding-left:30%;padding-right:10px;"><?php echo substr($annonce['DESCRIPTION'], 0, 100)." ..."; ?></div><br />
			<div style="padding-left:80%;padding-right:10px;"><a  title="Les détails de l'annonce dépendront de votre pack si vous êtes un artiste. Les organisateurs n'ont accès qu'au strict minimum." href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce['ID_ANNONCE']; ?>" class="link3">Voir l'annonce</a></div><br/>
			<br class="clear" />
		</div>
	<?php
	}
	?>