<?php
require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_annonce.php');
require_once('couche_metier/PCS_contrat.php');

$oMSG = new MSG();
$oPCS_annonce = new PCS_annonce();
$oPCS_contrat = new PCS_contrat();

$oMSG->setData('VISIBLE', 1);
$oMSG->setData('STATUT', 'Validée');
$oMSG->setData('criteres', 'AND annonce.DATE_DEBUT > NOW()');
$oMSG->setData('nb_result_affiches', 10);
$oMSG->setData('debut_affichage', 0);

$annonces_ = $oPCS_annonce->fx_recuperer_annonces_par_criteres($oMSG)->getData(1)->fetchAll();
// Je l'appelle $annonces_ car sinon cela crée des interférences avec les autres scripts.

?>
	<img style="" alt="Derniers projets"
 src="images/dernierprojet.png"> 
<br />
<br />
<ul class="list1">
	<?php
	foreach($annonces_ as $key=>$annonce_){
	?>
		<li><a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce_['ID_ANNONCE']; ?>"><?php echo $annonce_['TITRE']; ?></a></li>
	<?php
	}
	?>
</ul>
<a href="<?php echo $oCL_page->getPage('liste_annonce'); ?>">Voir toutes les annonces ></a><br />
<br />
