<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}
/*
*	On récupère toutes les annonces de la personne.
*	On récupèrera en direct les détails des contrats de chaque annonce.
*/

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
	
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/PCS_annonce.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oPCS_annonce = new PCS_annonce();
	$oCL_date = new CL_date();
	
	# On définit le nombre de résultats par page.
	$nb_result_affiches = 5;# 5 Seulement car on affiche aussi les contrats.
	$limite = (int)$_GET['limite'];
	
	# On compte le nombre d'annonces pour la personne.
	$oMSG->setData("ID_PERSONNE", $_SESSION['compte']['ID_PERSONNE']);
	$oMSG->setData("nb_result_affiches", $nb_result_affiches);
	$oMSG->setData("debut_affichage", $limite);
		
	
	
	# On fait la requête de sélection adaptée.
	if(isset($_GET['rq']) && $_GET['rq'] == "toutes"){
		
		$nb_result = $oPCS_annonce->fx_compter_toutes_annonces_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();		

			
		# On récupère toutes les annonces de la personne.
		$oMSG->setData("ID_PERSONNE", $_SESSION['compte']['ID_PERSONNE']);
		
		$annonces = $oPCS_annonce->fx_recuperer_toutes_annonces_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();		
	}else{# rq= future ou rq='empty' ou pas de rq. Dans ce cas on ne récupère que les futures annonces.
		
		$nb_result = $oPCS_annonce->fx_compter_annonces_futures_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();		

		
		# On récupère les futures annonces de la personne.
		$oMSG->setData("ID_PERSONNE", $_SESSION['compte']['ID_PERSONNE']);
		
		$annonces = $oPCS_annonce->fx_recuperer_toutes_annonces_futures_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
		//$nb_annonce[0]['nb_annonce'] = COUNT(ID_ANNONCE)
	}
	
	foreach($annonces as $key=>$annonce){
		# On met en forme les dates.
		$annonces[$key]['DATE_ANNONCE'] = $oCL_date->fx_ajouter_date($annonce['DATE_ANNONCE'], true, false, 'en', 'fr');
		$annonces[$key]['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($annonce['DATE_DEBUT'], true, false, 'en', 'fr');
		$annonces[$key]['DATE_FIN'] = $oCL_date->fx_ajouter_date($annonce['DATE_FIN'], true, false, 'en', 'fr');
		
		# On élimine les secondes et on convertit plus lisiblement
		$annonces[$key]['DATE_DEBUT'] = substr(str_replace(':', 'h', $annonce['DATE_DEBUT']), 0, -3);
		$annonces[$key]['DATE_ANNONCE'] = substr(str_replace(':', 'h', $annonce['DATE_ANNONCE']), 0, -3);
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
					echo '<th width="20px"><a href = "'.$page.'?limite='.$limite.'&rq='.$_GET['rq'].'">'.$numeroPages.'</a></th>'."\n";
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
						echo '<th width="20px"><a href = "'.$page.'?limite='.$limite.'&rq='.$_GET['rq'].'">'.$numeroPages.'</a></th>'."\n";
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
					echo '<th width="20px"><a href = "'.$page.'?limite='.$limite.'&rq='.$_GET['rq'].'">'.$numeroPages.'</a></th>'."\n";
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