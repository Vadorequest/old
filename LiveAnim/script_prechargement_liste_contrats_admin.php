<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_liste_contrats_admin.php');

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_annonce.php');
	require_once('couche_metier/PCS_contrat.php');
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/PCS_evaluation.php');
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');

	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_contrat = new PCS_contrat();
	$oPCS_personne = new PCS_personne();
	$oPCS_evaluation = new PCS_evaluation();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
	

	# On définit le nombre de résultats par page.
	$nb_result_affiches = 50;
	$limite = (int)$_GET['limite'];
	
	if(isset($_GET['rq']) && $_GET['rq'] == "en_cours"){
		$oMSG->setData('STATUT_CONTRAT', 'En cours');
	
		# on compte le nombre de résultats.
		$nb_result = $oPCS_contrat->fx_compter_tous_contrats_by_STATUT($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On récupère tous les contrats en cours.
		$oMSG->setData('nb_result_affiches', $nb_result_affiches);
		$oMSG->setData('debut_affichage', $limite);
		
		$contrats = $oPCS_contrat->fx_recuperer_tous_contrats_by_STATUT($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
	}else{
		# on compte le nombre de résultats.
		$nb_result = $oPCS_contrat->fx_compter_tous_contrats($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On récupère tous les contrats.
		$oMSG->setData('nb_result_affiches', $nb_result_affiches);
		$oMSG->setData('debut_affichage', $limite);
		
		$contrats = $oPCS_contrat->fx_recuperer_tous_contrats($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	}
	
	# On traite les données.
	foreach($contrats as $key=>$contrat){
		$contrats[$key]['DATE_CONTRAT_simple'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat['DATE_CONTRAT'], true, false, 'en', 'fr'), true, 'fr', true);
		$contrats[$key]['DATE_CONTRAT'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($contrat['DATE_CONTRAT'], true, false, 'en', 'fr'), true, 'fr');
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