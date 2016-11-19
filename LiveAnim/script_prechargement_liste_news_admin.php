<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_nouveaute.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_nouveaute = new PCS_nouveaute();
	$oCL_date = new CL_date();

	# On définit le nombre de résultats par page.
	$nb_result_affiches = 50;
	$limite = (int)$_GET['limite'];	
	
	# On compte le nombre news.
	$nb_result = $oPCS_nouveaute->fx_compter_toutes_nouveautees($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On précharge toutes les news du site sans distinction.
	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);
	
	$nouveautees = $oPCS_nouveaute->fx_selectionner_toutes_nouveautees($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On met en forme les données.
	foreach($nouveautees as $key=>$nouveautee){
		$nouveautees[$key]['DATE_CREATION_formatee'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($nouveautee['DATE_CREATION'], true, false, 'en', 'fr'), true, 'fr');
		$nouveautees[$key]['DATE_CREATION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($nouveautee['DATE_CREATION'], true, false, 'en', 'fr'), true, 'fr', true);
	}
	
	
	function afficher_pages($nb, $page, $total, $page_actuelle) {
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
					echo '<th width="20px"><a href = "'.$page.'?limite='.$limite.'">'.$numeroPages.'</a></th>'."\n";
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
						echo '<th width="20px"><a href = "'.$page.'?limite='.$limite.'">'.$numeroPages.'</a></th>'."\n";
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
					echo '<th width="20px"><a href = "'.$page.'?limite='.$limite.'">'.$numeroPages.'</a></th>'."\n";
				}
					$limite = $limite + $nb;
					$numeroPages++;
					$compteurPages++;
			}
        }
        echo '</tr></table>'."\n";
	}
	
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>