<?php
require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_nouveaute.php');
require_once('couche_metier/PCS_commentaire.php');
require_once('couche_metier/CL_date.php');

$oMSG = new MSG();
$oPCS_nouveaute = new PCS_nouveaute();
$oPCS_commentaire = new PCS_commentaire();
$oCL_date = new CL_date();

$oMSG->setData('VISIBLE', 1);
$oMSG->setData('nb_result_affiches', 5);
$oMSG->setData('debut_affichage', 0);

$nouveautees_ = $oPCS_nouveaute->fx_selectionner_nouveautees_BY_VISIBLE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
# Appelé nouveautees_ afin de ne pas créer d'interférences avec d'autres scripts.

# On met en forme les données.
foreach($nouveautees_ as $key=>$nouveaute){
	$nouveautees_[$key]['DATE_CREATION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($nouveaute['DATE_CREATION'], true, false, 'en', 'fr'), true, 'fr'); 
}

?>
	 <img style="" alt="Les nouveautées"  src="/images/les-nouveautes.png"><br /><br />
<ul class="news-list">
	<?php
	foreach($nouveautees_ as $key=>$nouveaute){
		
		$oMSG->setData('VISIBLE', 1);
		$oMSG->setData('ID_NOUVEAUTE', $nouveaute['ID_NOUVEAUTE']);
		
		# On récupère le nombre de commentaires.
		$nb_commentaire = $oPCS_commentaire->fx_compter_tous_commentaires_by_ID_NOUVEAUTE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	?>
		<li>
			<img alt="Image de la nouveautée !" height="180px" width="180px" src="<?php if(!empty($nouveaute['URL_PHOTO'])){echo $nouveaute['URL_PHOTO'];}else{echo $oCL_page->getImage('news1');} ?>" />
			<b style="color:#e7a5f7"><u>Publiée le <?php echo $nouveaute['DATE_CREATION']; ?>&nbsp;:</u></b><br />
			<br />
			<h5><?php echo $nouveaute['TITRE']; ?></h5><br />
			<br />
			<h6><?php echo $nouveaute['ENTETE']; ?></h6><br />
			<br />
			<a href="<?php echo $oCL_page->getPage('news')."?id_news=".$nouveaute['ID_NOUVEAUTE']; ?>" class="link3">Voir la suite...</a><span class="comment"><a href="<?php echo $oCL_page->getPage('news')."?id_news=".$nouveaute['ID_NOUVEAUTE']; ?>"><?php if($nb_commentaire[0]['nb_commentaire'] > 0){echo $nb_commentaire[0]['nb_commentaire']." commentaires";} ?></a></span>
		</li>
	<?php
	}
	?>
</ul>
<a href="<?php echo $oCL_page->getPage('liste_news'); ?>" class="link1">Voir toutes les news</a>