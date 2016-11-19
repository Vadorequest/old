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
require_once('couche_metier/PCS_nouveaute.php');
require_once('couche_metier/PCS_commentaire.php');
require_once('couche_metier/CL_date.php');

$oMSG = new MSG();
$oPCS_nouveaute = new PCS_nouveaute();
$oPCS_commentaire = new PCS_commentaire();
$oCL_date = new CL_date();


# On définit le nombre de résultats par page.
$nb_result_affiches = 10;
$limite = (int)$_GET['limite'];	

# On récupère la news à afficher.
$oMSG->setData('VISIBLE', 1);
$oMSG->setData('debut_affichage', $limite);
$oMSG->setData('nb_result_affiches', $nb_result_affiches);

# On récupère le nombre de news visibles.
$nb_result = $oPCS_nouveaute->fx_compter_toutes_nouveautees_by_VISIBLE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

# On récupère toutes ces news.
$nouveautees = $oPCS_nouveaute->fx_selectionner_nouveautees_BY_VISIBLE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

# On met en forme les données.
foreach($nouveautees as $key=>$nouveautee){
	$nouveautees[$key]['DATE_CREATION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($nouveautee['DATE_CREATION'], true, false, 'en', 'fr'), true, 'fr');
	$nouveautees[$key]['CONTENU'] = str_replace(Array('\n'), Array('<br />'), $nouveautee['CONTENU']);
}

function afficher_pages($nb,$page,$total, $page_actuelle) {
	$nbpages=ceil($total/$nb);
	$numeroPages = 1;
	$compteurPages = 1;
	$limite  = 0;
	$troispointsdroits = 0;
	$troispointsgauche = 0;
	echo '<table border = "0" ><tr>'."\n";
	while($numeroPages <= $nbpages) {
		if($numeroPages > $page_actuelle+10){
			if($numeroPages == $nbpages){
				echo '<th width="20px"><a href = "'.$page.'?id_news='.$_GET['id_news'].'&limite='.$limite.'">'.$numeroPages.'</a></th>'."\n";
				$limite = $limite + $nb;
				$numeroPages++;
				$compteurPages++;
			}else{
				if($troispointsdroits == 0){
					echo '<th width="20px">...</th>'."\n";
					$troispointsdroits = 1;
				}
				$limite = $limite + $nb;
				$numeroPages++;
				$compteurPages++;
			}
		}else if($numeroPages < $page_actuelle-10){
				if($numeroPages == 1){
					echo '<th width="20px"><a href = "'.$page.'?id_news='.$_GET['id_news'].'&limite='.$limite.'">'.$numeroPages.'</a></th>'."\n";
				}else if($troispointsgauche == 0){
					echo '<th width="20px">...</th>'."\n";
					$troispointsgauche = 1;
				}
				$limite = $limite + $nb;
				$numeroPages++;
				$compteurPages++;
			
		}else{
			if($numeroPages == $page_actuelle){
				echo '<th width="20px" class="noir_fond">'.$numeroPages.'</th>'."\n";
			}else{
				echo '<th width="20px"><a href = "'.$page.'?id_news='.$_GET['id_news'].'&limite='.$limite.'">'.$numeroPages.'</a></th>'."\n";
			}
				$limite = $limite + $nb;
				$numeroPages++;
				$compteurPages++;
		}
	}
	echo '</tr></table>'."\n";
}
?>