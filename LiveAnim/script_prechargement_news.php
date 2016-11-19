<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(isset($_GET['id_news'])){
	if((int)$_GET['id_news'] > 0){
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_nouveaute.php');
		require_once('couche_metier/PCS_commentaire.php');
		require_once('couche_metier/CL_date.php');
		
		$oMSG = new MSG();
		$oPCS_nouveaute = new PCS_nouveaute();
		$oPCS_commentaire = new PCS_commentaire();
		$oCL_date = new CL_date();
		
		# On récupère la news à afficher.
		$oMSG->setData('ID_NOUVEAUTE', $_GET['id_news']);
		$oMSG->setData('VISIBLE', 1);

		$nouveautee = $oPCS_nouveaute->fx_selectionner_nouveautee_by_ID_NOUVEAUTE_and_VISIBLE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On vérifie qu'on ait bien récupéré un résultat.
		if(!empty($nouveautee[0]['ID_NOUVEAUTE'])){
			# On met en forme les données.
			$nouveautee[0]['DATE_CREATION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($nouveautee[0]['DATE_CREATION'], true, false, 'en', 'fr'), true, 'fr');
			$nouveautee[0]['CONTENU'] = str_replace(Array('\n'), Array('<br />'), $nouveautee[0]['CONTENU']);
			
			# -------------------------------- Gestion des commentaires de la news -------------------------------
			
			# On définit le nombre de résultats par page.
			$nb_result_affiches = 50;
			$limite = (int)$_GET['limite'];	
			
			
			$oMSG->setData('ID_NOUVEAUTE', $nouveautee[0]['ID_NOUVEAUTE']);
			$oMSG->setData('VISIBLE', 1);
			$oMSG->setData('debut_affichage', $limite);
			$oMSG->setData('nb_result_affiches', $nb_result_affiches);
			
			# On récupère le nombre de commentaires visibles pour cette news.
			$nb_result = $oPCS_commentaire->fx_compter_tous_commentaires_by_ID_NOUVEAUTE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

			# On récupère tous les commentaires de cette news.
			$commentaires = $oPCS_commentaire->fx_selectionner_commentaires_by_ID_NOUVEAUTE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
			# Mise en forme des données.
			foreach($commentaires as $key=>$commentaire){
				$commentaires[$key]['DATE_CREATION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($commentaire['DATE_CREATION'], true, false, 'en', 'fr'), true, 'fr');
				$commentaires[$key]['CONTENU'] = str_replace(Array('\n'), Array('<br />'), $commentaire['CONTENU']);
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
		
		}else{
			# id_news incorrect
			$_SESSION['news']['message_affiche'] = false;
			$_SESSION['news']['message'] = "<span class='orange'>La news que vous essayez de lire n'existe pas.</span><br /><br />";
			header('Location: '.$oCL_page->getPage('liste_news', 'absolu'));
		}
	}else{
		# id_news incorrect
		$_SESSION['news']['message_affiche'] = false;
		$_SESSION['news']['message'] = "<span class='orange'>La news que vous essayez de lire n'existe pas.</span><br /><br />";
		header('Location: '.$oCL_page->getPage('liste_news', 'absolu'));
	}
}else{
	# Pas d'id 
	header('Location: '.$oCL_page->getPage('liste_news', 'absolu'));
}

?>