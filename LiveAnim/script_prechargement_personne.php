<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(isset($_GET['id_personne'])){
	$ID_PERSONNE = (int)$_GET['id_personne'];
	
	if($ID_PERSONNE > 0){
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_pack.php');
		require_once('couche_metier/PCS_contrat.php');
		require_once('couche_metier/PCS_personne.php');
		require_once('couche_metier/PCS_types.php');
		require_once('couche_metier/CL_date.php');
		require_once('couche_metier/CL_video.php');
		

		$oMSG = new MSG();
		$oPCS_pack = new PCS_pack();
		$oPCS_contrat = new PCS_contrat();
		$oPCS_personne = new PCS_personne();
		$oPCS_types = new PCS_types();
		$oCL_date = new CL_date();
		$oCL_video = new CL_video();
		
		
		# On récupère les informations du prestataire.
		$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
		
		$prestataire = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On vérifie qu'on ait bien récupéré un prestataire.
		if($prestataire[0]['TYPE_PERSONNE'] == "Prestataire"){
		
			# On récupère la note moyenne de chaque caractéristique pour toutes les prestations.
			# On récupère tous les types d'évaluation.
			$oMSG->setData("ID_FAMILLE_TYPES", 'Caractéristiques');
			
			$types_evaluation = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
			$oMSG->setData("ID_PERSONNE", $ID_PERSONNE);

			$evaluations = Array();
			# Pour chaque type d'évaluation on effectue la requete de sélection des types.
			foreach($types_evaluation as $key=>$type_evaluation){
				$oMSG->setData("TYPE_EVALUATION", $type_evaluation['ID_TYPES']);
				$evaluation = $oPCS_contrat->fx_recuperer_moy_evaluation_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				$caracteristique = $type_evaluation['ID_TYPES'];
				$evaluations[$caracteristique]['evaluation'] = $evaluation[0]['moy_evaluation'];
				# On vérifie que le résultat ne soit pas null.
				if($evaluations[$caracteristique]['evaluation'] === null){
					$evaluations[$caracteristique]['erreur'] = "<span class='petit'>N'a jamais été noté.</span>";
				}
			}
		
			# On met en forme les données.
			# On calcule l'age du prestataire.
			$annee_naissance = explode('-', $prestataire[0]['DATE_NAISSANCE']);
			$prestataire[0]['age'] = (((int)date('Y')) - $annee_naissance[0]);
			
			$prestataire[0]['DATE_NAISSANCE'] = $oCL_date->fx_ajouter_date($prestataire[0]['DATE_NAISSANCE'], false, false, 'en', 'fr');
			
			$prestataire[0]['DISTANCE_PRESTATION_MAX'] = str_replace('.', ',', $prestataire[0]['DISTANCE_PRESTATION_MAX']);
			
			# On récupère une url pour la vidéo valide.
			$prestataire[0]['CV_VIDEO'] = $oCL_video->fx_recuperer_tag($prestataire[0]['CV_VIDEO']);
					
			# On gère l'adresse.
			$adresse_complete = "";
			if(!empty($prestataire[0]['ADRESSE'])){
				$adresse_complete.= $prestataire[0]['ADRESSE'];
			}
			if(!empty($prestataire[0]['CP']) || !empty($prestataire[0]['VILLE'])){
				$adresse_complete.= ", ";
			}else{
				$adresse_complete.= " ";
			}
			if(!empty($prestataire[0]['CP'])){
				$adresse_complete.= $prestataire[0]['CP'];
			}
			$adresse_complete.= " ";
			if(!empty($prestataire[0]['VILLE'])){
				$adresse_complete.= $prestataire[0]['VILLE'];
			}
					
		
			# On vérifie les numéros de tel.
			if(strlen($prestataire[0]['TEL_FIXE']) < 10){
				$prestataire[0]['TEL_FIXE'] = "<span class='valide petit'>Ne souhaite pas afficher cette information.</span>";
			}
			if(strlen($prestataire[0]['TEL_PORTABLE']) < 10){
				$prestataire[0]['TEL_PORTABLE'] = "<span class='valide petit'>Ne souhaite pas afficher cette information.</span>";
			}
			
			# On vire les balises <br /> des textarea.
			$prestataire[0]['DESCRIPTION'] = str_replace('<br />', '', $prestataire[0]['DESCRIPTION']);
			$prestataire[0]['TARIFS'] = str_replace('<br />', '', $prestataire[0]['TARIFS']);
			$prestataire[0]['MATERIEL'] = str_replace('<br />', '', $prestataire[0]['MATERIEL']);
			
			# On extrait les rôles de l'utilisateur.
			$ROLES = explode(',', $prestataire[0]['ROLES']);
		
			# On récupère le pack actuel du prestataire visité.
			$oMSG->setData('ID_PERSONNE', $prestataire[0]['ID_PERSONNE']);
			$oMSG->setData('limit', 'LIMIT 0,1');
			
			$pack_prestataire = $oPCS_pack->fx_recuperer_pack_actif_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			# On vérifie que le pack soit bien valide.
			if(!empty($pack_prestataire[0]['ID_PACK'])){
				$pack_ok = true;			
			}else{
				$pack_ok = false;
			}
		
		
		}else{
			# L'id de la personne est incorrect.
			$_SESSION['liste_artiste']['message'] = "<span class='orange'>Le prestataire que vous avez recherché n'existe pas.</span>";
			$_SESSION['liste_artiste']['message_affiche'] = false;
			header('Location:'.$oCL_page->getPage('liste_artiste'));
		}
	}else{
		# L'id de la personne est incorrect.
		$_SESSION['liste_artiste']['message'] = "<span class='orange'>Le prestataire que vous avez recherché n'existe pas.</span>";
		$_SESSION['liste_artiste']['message_affiche'] = false;
		header('Location:'.$oCL_page->getPage('liste_artiste'));
	}
}else{
	# Pas d'id.
	header('Location:'.$oCL_page->getPage('liste_artiste'));
}
?>