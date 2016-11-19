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
	# On précharge tous les packs de la personne, jointure externe gauche sur pack_personne.
	# On va compter le nombre de packs de la personne afin de générer les pages.
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/PCS_pack.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oPCS_pack = new PCS_pack();
	$oCL_date = new CL_date();
	
	$nb_packs_activables = 0;# On initialise cette variable qui va servir à compter le nombre de packs activables afin de déterminer l'affichage de l'activation des packs.
	$now_formatee = date("YmdHis");
	
	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	
	$nb_result = $oPCS_pack->fx_compter_tous_packs_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();# $nb_result contient le nombre de personne de la BDD.
	// $nb_result[0]['nb_pack']
	
	# On définit le nombre de résultats par page.
	$nb_result_affiches = 12;
	$limite = (int)$_GET['limite'];
	
	
	# On charge tous les packs
	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);

	$packs_personne = $oPCS_pack->fx_recuperer_packs_by_ID_PERSONNE_et_by_LIMIT($oMSG)->getData(1)->fetchAll();
	
	foreach($packs_personne as $key=>$pack_personne){
		# On convertit en date complète.
		$packs_personne[$key]['DATE_ACHAT'] = $oCL_date->fx_convertir_date($pack_personne['DATE_ACHAT'], true);
		$packs_personne[$key]['DATE_DEBUT'] = $oCL_date->fx_convertir_date($pack_personne['DATE_DEBUT'], true);
		$packs_personne[$key]['DATE_FIN'] = $oCL_date->fx_convertir_date($pack_personne['DATE_FIN'], true);
		
		# On récupère le "timestamp" de la date de début. 
		$packs_personne[$key]['DATE_DEBUT_formatee'] = $oCL_date->fx_convertir_date($pack_personne['DATE_DEBUT'], true, true);
		
		# On récupère la date d'achat au format EN:
		$packs_personne[$key]['DATE_ACHAT_en'] = $oCL_date->fx_convertir_date($pack_personne['DATE_ACHAT'], true, false, "en");

		# On convertit en date plus simple (m-d-Y).
		$ACHAT_simple = split(' ',$oCL_date->fx_convertir_date($pack_personne['DATE_ACHAT'], true));
		$DEBUT_simple = split(' ',$oCL_date->fx_convertir_date($pack_personne['DATE_DEBUT'], true));
		$FIN_simple = split(' ',$oCL_date->fx_convertir_date($pack_personne['DATE_FIN'], true));
		
		
		$packs_personne[$key]['DATE_ACHAT_simple'] = $ACHAT_simple[0];
		$packs_personne[$key]['DATE_DEBUT_simple'] = $DEBUT_simple[0];
		$packs_personne[$key]['DATE_FIN_simple'] = $FIN_simple[0];
		
		# On calcule le prix réel payé.
		$packs_personne[$key]['prix_reel'] = $pack_personne['PRIX_BASE'] - $pack_personne['PRIX_BASE'] * ($pack_personne['REDUCTION']/100);
		$packs_personne[$key]['reduction_reelle'] = $pack_personne['PRIX_BASE'] * ($pack_personne['REDUCTION']/100);
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