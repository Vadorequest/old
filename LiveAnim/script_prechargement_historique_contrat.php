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
	require_once('couche_metier/PCS_contrat.php');	
	require_once('couche_metier/CL_date.php');			
	
	$oMSG = new MSG();
	$oPCS_contrat = new PCS_contrat();
	$oCL_date = new CL_date();

	# On définit le nombre de résultats par page.
	$nb_result_affiches = 20;
	$limite = (int)$_GET['limite'];
	
	# On fait la requête de sélection adaptée.
	if(isset($_GET['rq']) && $_GET['rq'] == "toutes"){
		# On compte le nombre de contrats de cette personne.
		$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
		
		$nb_result = $oPCS_contrat->fx_compter_contrat_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();

		# On récupère tous les contrats.
		$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
		$oMSG->setData('nb_result_affiches', $nb_result_affiches);
		$oMSG->setData('debut_affichage', $limite);
		
		$contrats = $oPCS_contrat->fx_recuperer_contrat_min_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	}else{# rq=courants ou rq='empty' ou pas de rq. Dans ce cas on ne récupère que les futurs contrats.
		# On compte le nombre de contrats de cette personne.
		$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
		
		$nb_result = $oPCS_contrat->fx_compter_contrats_courants_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();

		# On récupère tous les contrats.
		$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
		$oMSG->setData('nb_result_affiches', $nb_result_affiches);
		$oMSG->setData('debut_affichage', $limite);
		
		$contrats = $oPCS_contrat->fx_recuperer_contrats_courants_min_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	}
	# on met en forme les données.
	foreach($contrats as $key=>$contrat){
		# On met en forme les dates:
		$contrats[$key]['DATE_CONTRAT'] = $oCL_date->fx_ajouter_date($contrat['DATE_CONTRAT'], true, false, 'en', 'fr');
		$contrats[$key]['DATE_CONTRAT_simple'] = $oCL_date->fx_formatter_heure($contrats[$key]['DATE_CONTRAT'], true, 'fr', false, true, true);
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