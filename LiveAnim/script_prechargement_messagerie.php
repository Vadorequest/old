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
	# On precharge le nombre de de message totaux. (Visibles).
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_message.php');
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/CL_date.php');

	$oMSG = new MSG();
	$oPCS_message = new PCS_message();
	$oPCS_personne = new PCS_personne();
	$oCL_date = new CL_date();

	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	$oMSG->setData('STATUT_MESSAGE', 'Supprimé');
	$oMSG->setData('VISIBLE', 1);
	
	$nb_result = $oPCS_message->fx_compter_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG)->getData(1)->fetchAll();
	
	# On définit le nombre de résultats par page.
	$nb_result_affiches = 20;
	$limite = (int)$_GET['limite'];
	
	
	# On charge tous les messages.
	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);

	$messages = $oPCS_message->fx_recuperer_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG)->getData(1)->fetchAll();
	
	# On met en forme les données.
	foreach($messages as $key=>$message){
		# On récupère le pseudo de l'expéditeur de chaque message:
		$oMSG->setData('ID_PERSONNE', $message['EXPEDITEUR']);
		
		$personne = $oPCS_personne->fx_recuperer_PSEUDO_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
		$messages[$key]['PSEUDO'] = $personne[0]['PSEUDO'];
		
		# On met en forme les dates.
		$messages[$key]['DATE_ENVOI'] = $oCL_date->fx_ajouter_date($message['DATE_ENVOI'], true, false, 'en', 'fr');
		$DATE_ENVOI_simple = split(' ', $messages[$key]['DATE_ENVOI']);
		$messages[$key]['DATE_ENVOI_simple'] = $DATE_ENVOI_simple[0];
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