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
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_annonce.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oCL_date = new CL_date();
	
	# On définit le nombre de résultats par page.
	$nb_result_affiches = 10;
	$limite = (int)$_GET['limite'];
	
	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);	
	$oMSG->setData('GOLDLIVE', 1);
	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);
	
	# On compte le nombre d'annonces goldlive que possède ce prestataire.
	$nb_result = $oPCS_annonce->fx_compter_annonces_by_GOLDLIVE_et_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On récupère les annonces.
	$annonces = $oPCS_annonce->fx_recuperer_annonces_by_GOLDLIVE_et_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On met en forme les données.
	foreach($annonces as $key=>$annonce){
		$annonces[$key]['DATE_ANNONCE'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($annonce['DATE_ANNONCE'], true, false, 'en', 'fr'), true, 'fr');
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
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>