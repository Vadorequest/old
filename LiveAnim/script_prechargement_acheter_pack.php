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
	require_once('couche_metier/PCS_pack.php');
	
	# On récupère tous les packs visibles.
	$oMSG = new MSG();
	$oPCS_pack = new PCS_pack();
	
	$oMSG->setData('VISIBLE', 1);
	
	$packs = $oPCS_pack->fx_recuperer_tous_packs_by_VISIBLE($oMSG)->getData(1)->fetchAll();
	
	# On calcule pour chaque pack le taux de réduction effectué.
	
	foreach($packs as $key=>$pack){
		if($pack['SOUMIS_REDUCTIONS_PARRAINAGE'] == true && $pack['VISIBLE'] == true){
			$PRIX = $pack['PRIX_BASE'];
			$MAX = $pack['GAIN_PARRAINAGE_MAX'];# Le maximum de réduction auquel est soumis le pack.
			$REDUCTION = $_SESSION['compte']['REDUCTION'];
			
			# Trois cas possibles, soit la réduction possédée est inférieure au MAX soit elle est égale soit elle est supérieure.
			if($REDUCTION >= $MAX){
				# Si la réduction possédée est supérieure ou égale au MAX de réduction possible.
				$REDUCTION = $MAX;# On met le taux de réduction au maximum.
			}
			
			# Si la réduction n'est pas négative ou nulle on l'effectue.
			if($REDUCTION > 0){
				$packs[$key]['nouvelle_reduction'] = $REDUCTION;# On stocke la réduction du pack.
				$packs[$key]['economie'] = round($PRIX*($REDUCTION/100), 2);# On calcule l'économie réalisée.
				$packs[$key]['nouveau_prix'] = round($PRIX-($PRIX*($REDUCTION/100)), 2);# Le nouveau prix est égal à l'ancien prix multiplié par la réduction.
				$packs[$key]['beneficie_reduction'] = true;
			}else{
				$packs[$key]['beneficie_reduction'] = false;
			}
		}		
	}
	
	$now = date("d-m-Y");
	$oNOW = new DateTime( $now );
	$now = $oNOW->format("Ymd");
	
	# MAJ du pack utilisé.
	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	$oMSG->setData('limit', 'LIMIT 0,1');
	
	
	$pack_personne = $oPCS_pack->fx_recuperer_pack_actif_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	
	# On vérifie que le pack est encore actif et on met en forme les dates.
	if(isset($pack_personne[0]['ID_PACK']) && !empty($pack_personne[0]['ID_PACK'])){
		# Il y a bien un pack d'activé, on va revérifier au cas où avec un traitement logiciel.
		
		$DATE_ACHAT = $pack_personne[0]['DATE_ACHAT'];
		$DATE_FIN = $pack_personne[0]['DATE_FIN'];
		
		$tab_date_achat = explode('-', $DATE_ACHAT);# On récupère la date dans un tableau de trois cases ([Y][M][D + H:i:s])	
		$tab_date_achat2 = explode(' ', $tab_date_achat[2]);# On récupère le reste de la date dans un tableau à deux cases ([D][H:i:s])
		$tab_date_achat3 = explode(':', $tab_date_achat2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
		
		$tab_date_fin = explode('-', $DATE_FIN);# On récupère la date dans un tableau de trois cases ([Y][M][D + H:i:s])
		$tab_date_fin2 = explode(' ', $tab_date_fin[2]);# On récupère le reste de la date dans un tableau à deux cases ([D][H:i:s])
		$tab_date_fin3 = explode(':', $tab_date_fin2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
		
		# On stocke la date de fin de validité sous son format affichable et calculé.
		$date_fin_validite = date("d-m-Y H:i:s", mktime($tab_date_fin3[0], $tab_date_fin3[1], $tab_date_fin3[2], $tab_date_fin[1], $tab_date_fin2[0],  $tab_date_fin[0]));
		$date_fin_validite_formatee = date("YmdHis", mktime($tab_date_fin3[0], $tab_date_fin3[1], $tab_date_fin3[2], $tab_date_fin[1], $tab_date_fin2[0],  $tab_date_fin[0]));
		
		$maintenant = $oNOW->format("YmdHis");
		
		if($date_fin_validite_formatee < $maintenant){
			# La date est dépassée, le pack n'est pas activé.
			$_SESSION['pack']['activé'] = false;
			$_SESSION['pack']['DATE_ACHAT'] = date("d-m-Y H:i:s", mktime($tab_date_achat3[0], $tab_date_achat3[1], $tab_date_achat3[2], $tab_date_achat[1], $tab_date_achat2[0],  $tab_date_achat[0]));
			$_SESSION['pack']['ID_PACK'] = $pack_personne[0]['ID_PACK'];
			$_SESSION['pack']['NOM'] = $pack_personne[0]['NOM'];
			$_SESSION['pack']['TYPE_PACK'] = $pack_personne[0]['TYPE_PACK'];
			$_SESSION['pack']['PRIX_BASE'] = $pack_personne[0]['PRIX_BASE'];
			$_SESSION['pack']['DUREE'] = $pack_personne[0]['DUREE'];
			$_SESSION['pack']['CV_VISIBILITE'] = 0;
			$_SESSION['pack']['CV_ACCESSIBLE'] = 0;
			$_SESSION['pack']['NB_FICHES_VISITABLES'] = 0;
			$_SESSION['pack']['CV_VIDEO_ACCESSIBLE'] = false;
			$_SESSION['pack']['ALERTE_NON_DISPONIBILITE'] = false;
			$_SESSION['pack']['NB_DEPARTEMENTS_ALERTE'] = 0;
			$_SESSION['pack']['PARRAINAGE_ACTIVE'] = false;
			$_SESSION['pack']['PREVISUALISATION_FICHES'] = false;
			$_SESSION['pack']['CONTRATS_PDF'] = false;
			$_SESSION['pack']['SUIVI'] = false;
			$_SESSION['pack']['PUBS'] = true;
			$_SESSION['pack']['date_fin_validite'] = $date_fin_validite;
			$_SESSION['pack']['date_fin_validite_formatee'] = $date_fin_validite_formatee;
		}else{
			# Le pack est encore valide, on l'active.
			$_SESSION['pack']['activé'] = true;
			$_SESSION['pack']['DATE_ACHAT'] = date("d-m-Y H:i:s", mktime($tab_date_achat3[0], $tab_date_achat3[1], $tab_date_achat3[2], $tab_date_achat[1], $tab_date_achat2[0],  $tab_date_achat[0]));
			$_SESSION['pack']['ID_PACK'] = $pack_personne[0]['ID_PACK'];
			$_SESSION['pack']['NOM'] = $pack_personne[0]['NOM'];
			$_SESSION['pack']['TYPE_PACK'] = $pack_personne[0]['TYPE_PACK'];
			$_SESSION['pack']['PRIX_BASE'] = $pack_personne[0]['PRIX_BASE'];
			$_SESSION['pack']['DUREE'] = $pack_personne[0]['DUREE'];
			$_SESSION['pack']['CV_VISIBILITE'] = $pack_personne[0]['CV_VISIBILITE'];
			$_SESSION['pack']['CV_ACCESSIBLE'] = $pack_personne[0]['CV_ACCESSIBLE'];
			$_SESSION['pack']['NB_FICHES_VISITABLES'] = $pack_personne[0]['NB_FICHES_VISITABLES'];# On ne charge pas ici le NB_FICHES_VISITABLES du pack mais celui 
																						 # de la table pack_personne, voir couche_metier/VIEW_pack.php.
			$_SESSION['pack']['CV_VIDEO_ACCESSIBLE'] = $pack_personne[0]['CV_VIDEO_ACCESSIBLE'];
			$_SESSION['pack']['ALERTE_NON_DISPONIBILITE'] = $pack_personne[0]['ALERTE_NON_DISPONIBILITE'];
			$_SESSION['pack']['NB_DEPARTEMENTS_ALERTE'] = $pack_personne[0]['NB_DEPARTEMENTS_ALERTE'];
			$_SESSION['pack']['PARRAINAGE_ACTIVE'] = $pack_personne[0]['PARRAINAGE_ACTIVE'];
			$_SESSION['pack']['PREVISUALISATION_FICHES'] = $pack_personne[0]['PREVISUALISATION_FICHES'];
			$_SESSION['pack']['CONTRATS_PDF'] = $pack_personne[0]['CONTRATS_PDF'];
			$_SESSION['pack']['SUIVI'] = $pack_personne[0]['SUIVI'];
			$_SESSION['pack']['PUBS'] = $pack_personne[0]['PUBS'];
			$_SESSION['pack']['date_fin_validite'] = $date_fin_validite;
			$_SESSION['pack']['date_fin_validite_formatee'] = $date_fin_validite_formatee;
		}
	}else{
		# Il n'y a pas de pack actif.
		$_SESSION['pack']['activé'] = false;
		$_SESSION['pack']['DATE_ACHAT'] = "";
		$_SESSION['pack']['ID_PACK'] = "";
		$_SESSION['pack']['NOM'] = "";
		$_SESSION['pack']['TYPE_PACK'] = "";
		$_SESSION['pack']['PRIX_BASE'] ="";
		$_SESSION['pack']['DUREE'] = "";
		$_SESSION['pack']['CV_VISIBILITE'] = 0;
		$_SESSION['pack']['CV_ACCESSIBLE'] = 0;
		$_SESSION['pack']['NB_FICHES_VISITABLES'] = 0;
		$_SESSION['pack']['CV_VIDEO_ACCESSIBLE'] = false;
		$_SESSION['pack']['ALERTE_NON_DISPONIBILITE'] = false;
		$_SESSION['pack']['NB_DEPARTEMENTS_ALERTE'] = 0;
		$_SESSION['pack']['PARRAINAGE_ACTIVE'] = false;
		$_SESSION['pack']['PREVISUALISATION_FICHES'] = false;
		$_SESSION['pack']['CONTRATS_PDF'] = false;
		$_SESSION['pack']['SUIVI'] = false;
		$_SESSION['pack']['PUBS'] = true;
		$_SESSION['pack']['date_fin_validite'] = "00-00-0000 00:00:00";
		$_SESSION['pack']['date_fin_validite_formatee'] = "00000000000000";
	}

}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>