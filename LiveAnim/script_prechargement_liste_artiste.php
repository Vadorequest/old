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
* Critères de recherche pour les artistes:
* Rôles
* Statut professionnel
* Tarifs maximal 
*/


# De base on récupère tous les prestataires par ordre de type pack activé si on ne reçoit rien en GET
require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_personne.php');
require_once('couche_metier/PCS_types.php');
require_once('couche_metier/CL_date.php');

$oMSG = new MSG();
$oPCS_personne = new PCS_personne();
$oPCS_types = new PCS_types();
$oCL_date = new CL_date();

// -------------- Chargement des informations nécessaires au fonctionnement du formulaire de contact -----------
$oMSG->setData('ID_FAMILLE_TYPES', 'Role');

$types = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);


// -------------- Gestion de la pagination et des requêtes de recherche: ---------------------------

# On définit le nombre de résultats par page.
if(!isset($_SESSION['recherche_artiste']['nb_result_affiches'])){
	$nb_result_affiches = 25;
}
$limite = (int)$_GET['limite'];


if(isset($_GET['role'])){
	if($_GET['role'][strlen($_GET['role'])-1] == "s" || $_GET['role'][strlen($_GET['role'])-1] == "x"){
		$ROLE = substr($_GET['role'],0,-1);# On utilise un truc au pluriel, on vire le s ou le x de fin.
	}else{
		$ROLE = $_GET['role'];
	}
	# On sélectionne tous les prestataires qui ont ce rôle.
	$oMSG->setData('VISIBLE', 1);
	$oMSG->setData('TYPE_PERSONNE', 'Prestataire');
	$oMSG->setData('criteres', '');
	$oMSG->setData('sql_LIKE', "AND ROLES LIKE :ROLES ");
	$oMSG->setData('ROLES', "%".$ROLE."%");
	$oMSG->setData('ORDER_BY', 'ORDER BY pack.PRIX_BASE DESC ');

	$nb_result = $oPCS_personne->fx_compter_personne_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);

	$prestataires = $oPCS_personne->fx_recuperer_personne_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

}else if(!isset($_SESSION['recherche_artiste']['recherche_effectuée'])){
	# Si on a effectué aucune recherche alors on charge la requête de base.
	$oMSG->setData('VISIBLE', 1);
	$oMSG->setData('TYPE_PERSONNE', 'Prestataire');
	
	$oMSG->setData('criteres', "");
	$oMSG->setData('sql_LIKE', 'AND ROLES LIKE :ROLES ');
	$oMSG->setData('ROLES', '%%');
	$oMSG->setData('ORDER_BY', 'ORDER BY pack.PRIX_BASE DESC ');

	$nb_result = $oPCS_personne->fx_compter_personne_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);

	$prestataires = $oPCS_personne->fx_recuperer_personne_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

}else if($_SESSION['recherche_artiste']['recherche_effectuée']){
	# Si l'utilisateur a effectué une recherche alors la charge.
	$oMSG->setData('VISIBLE', 1);
	$oMSG->setData('TYPE_PERSONNE', 'Prestataire');
	$criteres = " ";
	if(!empty($_SESSION['recherche_artiste']['STATUT_PERSONNE'])){
		$criteres.= "AND personne.STATUT_PERSONNE='".$_SESSION['recherche_artiste']['STATUT_PERSONNE']."' ";
	}
	# On filtre aussi par départements si le champ n'est pas vide.
	if(!empty($_SESSION['recherche_artiste']['DEPARTEMENTS'])){
		$criteres.= ' AND DEPARTEMENTS IN ('.$_SESSION['recherche_artiste']['DEPARTEMENTS'].')';
	}
	$oMSG->setData('criteres', $criteres);
	$oMSG->setData('sql_LIKE', 'AND ROLES LIKE :ROLES ');
	$oMSG->setData('ROLES', '%'.$_SESSION['recherche_artiste']['ROLES'].'%');
	$oMSG->setData('ORDER_BY', 'ORDER BY pack.PRIX_BASE DESC ');

	$nb_result = $oPCS_personne->fx_compter_personne_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);

	$prestataires = $oPCS_personne->fx_recuperer_personne_par_criteres($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);

}

# On met en forme les données:
foreach($prestataires as $key=>$prestataire){
	$prestataires[$key]['ROLES'] = str_replace(',', '<br />', $prestataire['ROLES']);
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



?>