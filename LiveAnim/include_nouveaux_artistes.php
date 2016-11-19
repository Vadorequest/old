<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once ('couche_metier/MSG.php');
require_once ('couche_metier/PCS_personne.php');

$oMSG = new MSG();
$oPCS_personne = new PCS_personne();

$oMSG->setData('TYPE_PERSONNE', 'Prestataire');
$oMSG->setData('nb_result_affiches', 6);
$oMSG->setData('debut_affichage', 0);

$nouveaux_artistes = $oPCS_personne->fx_recuperer_date_creation_compte($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC); 


?>
<div class="section">
	
	<img style="" alt="Nouveaux artistes"
 src="images/nouveauxartistes.png">
<br />
<br />

	<ul class="members-list">
		<?php
		foreach($nouveaux_artistes as $key=>$nouvel_artiste){
		?>
			<li><a href="<?php echo $oCL_page->getPage('personne')."?id_personne=".$nouvel_artiste['ID_PERSONNE']; ?>"><img class="image_border" alt="<?php if(isset($_SESSION['pack']) && $_SESSION['pack']['CV_ACCESSIBLE'] >= 2){echo $nouvel_artiste['PSEUDO'];}else{echo "Membre N°".$nouvel_artiste['ID_PERSONNE'];} ?>" title="<?php if(isset($_SESSION['pack']) && $_SESSION['pack']['CV_ACCESSIBLE'] >= 2){echo $nouvel_artiste['PSEUDO'];}else{echo "Membre N°".$nouvel_artiste['ID_PERSONNE'];} ?>" src="<?php if(!empty($nouvel_artiste['URL_PHOTO_PRINCIPALE'])){echo $nouvel_artiste['URL_PHOTO_PRINCIPALE'];}else{echo $oCL_page->getImage('avat_test1');} ?>" /><?php if(isset($_SESSION['pack']) && $_SESSION['pack']['CV_ACCESSIBLE'] >= 2){echo $nouvel_artiste['PSEUDO'];}else{echo "Membre N°".$nouvel_artiste['ID_PERSONNE'];} ?></a></li>
		<?php
		}
		?>
	</ul>
	<a href="<?php echo $oCL_page->getPage('liste_artiste'); ?>" class="link1">Voir tous les artistes ></a>
</div>