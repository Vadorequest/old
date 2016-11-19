<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_annonce.php');
	require_once('couche_metier/PCS_pack.php');
	require_once('couche_metier/PCS_personne.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_pack = new PCS_pack();
	$oPCS_personne = new PCS_personne();
	
	# On récupère le nombre d'achats d'annonces GoldLive.
	$goldlives = $oPCS_annonce->fx_compter_tous_goldlive($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	$revenu_min_goldlive = 0.8 * $goldlives[0]['nb_annonce'];
	$revenu_max_goldlive = 1.6 * $goldlives[0]['nb_annonce'];
	
	# On récupère tous les packs existants.
	$packs = $oPCS_pack->fx_recuperer_tous_packs($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
	# On initialise notre gain total.
	$gain_total = 0;
	
	# Le nombre d'achats totaux correspond au nombre d'achats de packs.
	$achats_totaux = 0;
	
	# Pour chaque pack payant on récupère le nombre d'achats de ce pack.
	foreach($packs as $key=>$pack){
		if($pack['PRIX_BASE'] > 0){
			$oMSG->setData('ID_PACK', $pack['ID_PACK']);
		
			$nb_achats = $oPCS_pack->fx_compter_nb_packs_achetes_by_ID_PACK($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			$packs[$key]['nb_achats'] = $nb_achats[0]['nb_pack'];
			
			# On mutliplie par le prix du pack pour obtenir le revenu total de ce pack.
			$packs[$key]['gains'] = ($nb_achats[0]['nb_pack'])*($pack['PRIX_BASE']);
			
			# On ajoute le gain du pack au gain total.
			$gain_total+= $packs[$key]['gains'];
			$achats_totaux+= $packs[$key]['nb_achats'];
		}
	}
	
	# On récupère le nombre de connectés.
	$oMSG->setData('DERNIERE_ACTIVITE', (time() - 300));
	$nb_connectes = $oPCS_personne->fx_compter_connectes($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	
}else{
# Si l'internaute n'est pas connecté et admin il gicle.
header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>