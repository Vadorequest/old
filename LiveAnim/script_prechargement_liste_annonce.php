<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

// Cette page est en libre accès.

/*
*	Critères de recherche pour les annonces:
*	Type d'annonce
*	Budget
*	CP
*	Ville
*	Date de début
*	Date de fin
*/

# De base on récupère toutes les annonces visibles, validées et par ordre de goldlive et date de début.
require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_annonce.php');
require_once('couche_metier/PCS_contrat.php');
require_once('couche_metier/PCS_personne.php');
require_once('couche_metier/PCS_types.php');
require_once('couche_metier/CL_date.php');

$oMSG = new MSG();
$oPCS_annonce = new PCS_annonce();
$oPCS_contrat = new PCS_contrat();
$oPCS_personne = new PCS_personne();
$oPCS_types = new PCS_types();
$oCL_date = new CL_date();

// -------------- Préchargement du formulaire de recherche: ---------------------------

$now_court = date('d-m-Y');

$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');
$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);


// -------------- Gestion de la pagination et des requêtes de recherche: ---------------------------

# On définit le nombre de résultats par page.
if(isset($_SESSION['pack']) && $_SESSION['pack']['PREVISUALISATION_FICHES'] == 1){
	$nb_result_affiches = 10;
}else{
	$nb_result_affiches = 20;
}
$limite = (int)$_GET['limite'];

# Si aucune recherche n'a été faite alors on charge le truc de base.
if(!isset($_SESSION['recherche_annonce']['recherche_effectuée'])){
	$oMSG->setData('VISIBLE', 1);
	$oMSG->setData('STATUT', 'Validée');
	$oMSG->setData('criteres', 'AND annonce.DATE_DEBUT > NOW()');

	$nb_result = $oPCS_annonce->fx_compter_annonces_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);

	$annonces = $oPCS_annonce->fx_recuperer_annonces_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
}else{
	# Sinon on exécute la requête.
	$criteres = "AND annonce.DATE_DEBUT > '".$_SESSION['recherche_annonce']['DATE_DEBUT']."' AND annonce.DATE_FIN < '".$_SESSION['recherche_annonce']['DATE_FIN']."' ";
	
	# On rend nos deux dates de session (en) affichables (fr + !datetime)
	$DATE_DEBUT = $oCL_date->fx_ajouter_date($_SESSION['recherche_annonce']['DATE_DEBUT'], true, false, 'en', 'fr');
	$DATE_DEBUT_simple = explode(' ', $DATE_DEBUT);
	$DATE_DEBUT_simple = $DATE_DEBUT_simple[0];
	
	$DATE_FIN = $oCL_date->fx_ajouter_date($_SESSION['recherche_annonce']['DATE_FIN'], true, false, 'en', 'fr');
	$DATE_FIN_simple = explode(' ', $DATE_FIN);
	$DATE_FIN_simple = $DATE_FIN_simple[0];
	
	if($_SESSION['recherche_annonce']['TYPE_ANNONCE'] != "*"){
		$criteres.= "AND annonce.TYPE_ANNONCE='".$_SESSION['recherche_annonce']['TYPE_ANNONCE']."' ";
	}
	if($_SESSION['recherche_annonce']['BUDGET'] != 0){
		$criteres.= "AND annonce.BUDGET >='".$_SESSION['recherche_annonce']['BUDGET']."' ";
	}
	if(!empty($_SESSION['recherche_annonce']['CP_VILLE'])){
		$criteres.= "AND (annonce.VILLE LIKE '%".$_SESSION['recherche_annonce']['CP_VILLE']."%' OR annonce.CP LIKE '%".$_SESSION['recherche_annonce']['CP_VILLE']."%') ";
	}
	$oMSG->setData('VISIBLE', 1);
	$oMSG->setData('STATUT', 'Validée');
	$oMSG->setData('criteres', $criteres);
	
	$nb_result = $oPCS_annonce->fx_compter_annonces_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);

	$annonces = $oPCS_annonce->fx_recuperer_annonces_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
}
# On met en forme les données:
foreach($annonces as $key=>$annonce){
	# Les dates:
	$annonces[$key]['DATE_DEBUT'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($annonce['DATE_DEBUT'], true, false, 'en', 'fr'), true, 'fr');
	$annonces[$key]['DATE_FIN'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($annonce['DATE_FIN'], true, false, 'en', 'fr'), true, 'fr');
	$annonces[$key]['DATE_ANNONCE'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($annonce['DATE_ANNONCE'], true, false, 'en', 'fr'), true, 'fr');
	
	# Le nombre de contrat de chaque annonce.
	$oMSG->setData('ID_ANNONCE', $annonce['ID_ANNONCE']);
	$nb_contrat = $oPCS_contrat->fx_compter_contrat_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	$annonces[$key]['nb_contrat'] = $nb_contrat[0]['nb_contrat'];
}
# On recharge les annonces visitées depuis la BDD.
$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);

$annonces_visitees = $oPCS_personne->fx_recuperer_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();

$annonces_visitees = explode('/', $annonces_visitees[0]['ANNONCES_VISITEES']);

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

?>