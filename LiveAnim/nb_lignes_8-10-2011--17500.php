nb lignes le 08/10/2011

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
	require_once('couche_metier/PCS_personne.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	
	$oMSG->setData(0, '');
	$oMSG->setData(1, '');
	
	$membres = $oPCS_personne->fx_recuperer_tous_membres($oMSG)->getData(1)->fetchAll();

}

?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){

	# On va charger tous les comptes qui ont été supprimés par les utilisateurs.
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_personne.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	
	$oMSG->setData('VISIBLE', 0);
	$oMSG->setData('PERSONNE_SUPPRIMEE', 1);

	$comptes_supprimes = $oPCS_personne->fx_recuperer_tous_comptes_supprimes($oMSG)->getData(1);

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	require_once('couche_metier/PCS_annonce.php');		
	require_once('couche_metier/PCS_types.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_types = new PCS_types();
		
	# On récupère les types nécessaires:
	$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');# Vérifier la famille type.
	
	$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
	
	# On récupère les départements.
	$departements = $oPCS_annonce->fx_recuperer_tous_departements($oMSG)->getData(1)->fetchAll();

}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	if(isset($_GET['id_annonce'])){
		# On récupère l'ID de l'annonce en GET et on le transforme en int.
		$ID_ANNONCE = (int)$_GET['id_annonce'];
		$ID_ANNONCE_ok = 0;

		# On vérifie que cette annonce existe. (Visibilité + statut + futures)
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');	
		require_once('couche_metier/CL_date.php');			
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		$oCL_date = new CL_date();
		
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		$oMSG->setData('VISIBLE', 1);
		$oMSG->setData('STATUT', 'Validée');
		
		$annonce = $oPCS_annonce->fx_recuperer_annonce_valide_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
	
		if(!empty($annonce[0]['ID_ANNONCE'])){
			# L'annonce existe bien et est valide, on va pouvoir faire un contrat dessus.
			$ID_ANNONCE_ok = 1;
			$formulaire = "creer";
			
			# On met en forme les dates:
			$annonce[0]['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_DEBUT'], true, false, 'en', 'fr');
			$annonce[0]['DATE_FIN'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_FIN'], true, false, 'en', 'fr');
			
			$annonce[0]['DATE_DEBUT'] = substr(str_replace(':', 'h', $annonce[0]['DATE_DEBUT']), 0, -3);
			$annonce[0]['DATE_FIN'] = substr(str_replace(':', 'h', $annonce[0]['DATE_FIN']), 0, -3);
			
		}else{
			# L'annonce n'existe pas ou alors elle n'est pas valide.
			$ID_ANNONCE_ok = 0;
			$_SESSION['creer_contrat']['message_affiche'] = false;
			$_SESSION['creer_contrat']['message'] = "<span class='orange'>L'annonce pour laquelle vous souhaitez créer un contrat n'est pas valide. <span class='petit'>(Date dépassée, annonce refusée, ...)</span></span><br />";
		}
	
	}else{
		# Pas de GET.
		$ID_ANNONCE_ok = 0;
		$_SESSION['creer_contrat']['message_affiche'] = false;
		$_SESSION['creer_contrat']['message'] = "<span class='alert'>L'annonce pour laquelle vous souhaitez créer un contrat n'existe pas.</span><br />";
	}
	
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	require_once('couche_metier/PCS_personne.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	
	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	
	$filleuls = $oPCS_personne->fx_recuperer_filleuls_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	
	# On précharge tous les filleuls de la personne.
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/CL_date.php');

	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oCL_date = new CL_date();
	
	# On récupère le nombre de messages non lus.
	
	# On récupère le nombre de messages non lus en rapport avec un contrat.
	
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
		# On récupère la note moyenne de chaque caractéristique pour toutes les prestations.
		
		if($_SESSION['pack']['SUIVI'] == true){
			# On récupère les gains totaux réalisés ainsi que les dépenses dans les packs.
			
		}
	}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur"){
		# On récupère le nombre d'annonces en cours.
		
	}
	
	# On récupère la date de création du compte.
	$oMSG->setData(0, "");
	$oMSG->setData(1, "");
	
	$date_creation_compte = $oPCS_personne->fx_recuperer_date_creation_compte($oMSG)->getData(1)->fetchAll();

		# On met la date en FR.
		$date_creation_compte[0]['DATE_CONNEXION'] = $oCL_date->fx_convertir_date($date_creation_compte[0]['DATE_CONNEXION'], true);
	
	# On récupère les informations des dix dernières connexions.
	$oMSG->setData("ID_PERSONNE", $_SESSION['compte']['ID_PERSONNE']);
	$oMSG->setData("limit", "LIMIT 0,10");
	
	$dernieres_connexions = $oPCS_personne->fx_recuperer_dernieres_connexions_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	
		# On met les dates en FR.
		foreach($dernieres_connexions as $key=>$derniere_connexion){
			$dernieres_connexions[$key]['DATE_CONNEXION'] = $oCL_date->fx_convertir_date($derniere_connexion['DATE_CONNEXION'], true);
		}
				
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?><?php
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
	require_once('couche_metier/PCS_contrat.php');	
	require_once('couche_metier/CL_date.php');			
	
	$oMSG = new MSG();
	$oPCS_contrat = new PCS_contrat();
	$oCL_date = new CL_date();

	# On compte le nombre de contrats de cette personne.
	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	
	$nb_result = $oPCS_contrat->fx_compter_contrat_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	
	# On définit le nombre de résultats par page.
	$nb_result_affiches = 20;
	$limite = (int)$_GET['limite'];

	# On récupère tous les contrats.
	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);
	
	$contrats = $oPCS_contrat->fx_recuperer_contrat_min_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	
	# on met en forme les données.
	foreach($contrats as $key=>$contrat){
		# On met en forme les dates:
		$contrats[$key]['DATE_CONTRAT'] = $oCL_date->fx_ajouter_date($contrat['DATE_CONTRAT'], true, false, 'en', 'fr');
		$contrats[$key]['DATE_CONTRAT_simple'] = $oCL_date->fx_formatter_heure($contrats[$key]['DATE_CONTRAT'], true, 'fr', false, true, true);
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
?><?php
if(!isset($_SESSION)){
	session_start();
}
require_once('couche_metier/CL_page.php');
require_once('couche_metier/PCS_personne.php');
$oCL_page = new CL_page();
$oPCS_personne = new PCS_personne();

# On vérifie qu'on se trouve bien sur la page de l'inscription

require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_types.php');

$oMSG = new MSG();
$oPCS_types = new PCS_types();

# On récupère la liste des types de la famille 'Découverte du site'.
$oMSG->setData('ID_FAMILLE_TYPES', 'Découverte du site');

$connaissance_site = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();

function fx_activer_compte($email, $cle_activation){
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oCL_page = new CL_page();
	
	$oMSG->setData('EMAIL', $email);
	$oMSG->setData('CLE_ACTIVATION', $cle_activation);
	
	$nb_personne = $oPCS_personne->fx_compter_by_EMAIL_et_CLE_ACTIVATION($oMSG)->getData(1)->fetchAll();
	
	if($nb_personne[0]['nb_personne'] == 1){
		$oMSG->setData('CLE_ACTIVATION', "");# On supprime la clé d'activation afin que le compte ne puisse pas être réactivé.
		$oPCS_personne->fx_valider_compte($oMSG);
		
		# On envoi un email à la personne bannie.
		$additional_headers = "From: noreply@liveanim.com \r\n";
		$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
		$destinataires = $EMAIL;
		$sujet = utf8_decode("Alerte LiveAnim [Activation de votre compte]");
		
		$message = "------------------------------\n";
		$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
		$message.= "------------------------------\n\n";
		$message.= utf8_decode("Bonjour, \n");
		$message.= utf8_decode("Nous avons le plaisir de vous informer que votre compte a été activé sur notre site ".$oCL_page->getPage('accueil').". \n");
		$message.= utf8_decode("Vous pouvez donc dès à présent vous connecter et profiter ! \n");
		$message.= utf8_decode("Nous espérons que nos services vous satisferont, nous sommes à votre écoute pour d'éventuelles questions (Via notre page de contact). \n");
		$message.= utf8_decode("Nous vous souhaitons une agréable journée. \n");
		$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
		
		$_SESSION['bannir_membre']['message'] = "<span class='valide'>Le membre $PSEUDO a été banni $duree pour une durée de $duree_bannissement jour(s).<br />";

		
		if(mail($destinataires, $sujet, $message, $additional_headers)){
			# Si le mail a été correctement envoyé:		
			echo "La clé d'activation est valide. Compte activé. Un e-mail a été envoyé.";
		}else{
			echo "La clé d'activation est valide. Compte activé.";
		}
				
	}else{
		if(strlen($nb_personne[0]['CLE_ACTIVATION']) == 0){
			echo "Le compte a déjà été activé.";
		}else{
			echo "La clé d'activation est invalide.";
		}
	}

}

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
	$ID_PARRAIN = $_SESSION['compte']['ID_PERSONNE'];
	$lien = $oCL_page->getPage('inscription', 'absolu')."?parrain=".$ID_PARRAIN."#inscriptionh2";
	$image = $oCL_page->getPage('accueil', 'absolu')."images/parrainage1.png";
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
require_once('couche_metier/PCS_types.php');
require_once('couche_metier/CL_date.php');

$oMSG = new MSG();
$oPCS_annonce = new PCS_annonce();
$oPCS_contrat = new PCS_contrat();
$oPCS_types = new PCS_types();
$oCL_date = new CL_date();

// -------------- Préchargement du formulaire de recherche: ---------------------------

$now_court = date('d-m-Y');

$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');
$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();


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

	$nb_result = $oPCS_annonce->fx_compter_annonces_par_criteres($oMSG)->getData(1)->fetchAll();

	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);

	$annonces = $oPCS_annonce->fx_recuperer_annonces_par_criteres($oMSG)->getData(1)->fetchAll();
}else{
	# Sinon on exécute la requête.
	$criteres = "AND annonce.DATE_DEBUT > '".$_SESSION['recherche_annonce']['DATE_DEBUT']."' AND annonce.DATE_FIN < '".$_SESSION['recherche_annonce']['DATE_FIN']."' ";
	
	# On rend nos deux dates de session (en) affichables (fr + !datetime)
	$DATE_DEBUT = $oCL_date->fx_ajouter_date($_SESSION['recherche_annonce']['DATE_DEBUT'], true, false, 'en', 'fr');
	$DATE_DEBUT_simple = split(' ', $DATE_DEBUT);
	$DATE_DEBUT_simple = $DATE_DEBUT_simple[0];
	
	$DATE_FIN = $oCL_date->fx_ajouter_date($_SESSION['recherche_annonce']['DATE_FIN'], true, false, 'en', 'fr');
	$DATE_FIN_simple = split(' ', $DATE_FIN);
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
	
	$nb_result = $oPCS_annonce->fx_compter_annonces_par_criteres($oMSG)->getData(1)->fetchAll();

	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);

	$annonces = $oPCS_annonce->fx_recuperer_annonces_par_criteres($oMSG)->getData(1)->fetchAll();
}
# On met en forme les données:
foreach($annonces as $key=>$annonce){
	# Les dates:
	$annonces[$key]['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($annonce['DATE_DEBUT'], true, false, 'en', 'fr');
	$annonces[$key]['DATE_DEBUT_simple'] = split(' ',$oCL_date->fx_convertir_date($annonce['DATE_DEBUT'], true));
	$annonces[$key]['DATE_DEBUT_simple'] = $annonces[$key]['DATE_DEBUT_simple'][0];
	
	$annonces[$key]['DATE_FIN'] = $oCL_date->fx_ajouter_date($annonce['DATE_FIN'], true, false, 'en', 'fr');
	$annonces[$key]['DATE_FIN_simple'] = split(' ',$oCL_date->fx_convertir_date($annonce['DATE_FIN'], true));
	$annonces[$key]['DATE_FIN_simple'] = $annonces[$key]['DATE_FIN_simple'][0];
	
	# Le nombre de contrat de chaque annonce.
	$oMSG->setData('ID_ANNONCE', $annonce['ID_ANNONCE']);
	$nb_contrat = $oPCS_contrat->fx_compter_contrat_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
	$annonces[$key]['nb_contrat'] = $nb_contrat[0]['nb_contrat'];
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

?><?php
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
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oCL_date = new CL_date();
	
	# on compte le nombre de résultats.
	$oMSG->setData('VISIBLE', 0);
	
	$nb_result = $oPCS_annonce->fx_compter_annonce_by_VISIBLE($oMSG)->getData(1)->fetchAll();
	// $nb_result[0]['nb_annonce']
	
	# On définit le nombre de résultats par page.
	$nb_result_affiches = 50;
	$limite = (int)$_GET['limite'];
	
	# On récupère toutes les annonces avec le statut visible=0 selon la limite.
	$oMSG->setData('VISIBLE', 0);
	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);
	
	$annonces = $oPCS_annonce->fx_recuperer_min_annonce_by_VISIBLE($oMSG)->getData(1)->fetchAll();
	
	# On met au format la date de création de l'annonce.
	foreach($annonces as $key=>$annonce){
		$annonces[$key]['DATE_ANNONCE'] = $oCL_date->fx_ajouter_date($annonce['DATE_ANNONCE'], true, false, 'en', 'fr');
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
?><?php
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
	# On va compter le nombre de membres afin de générer les pages.
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_personne.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	
	$nb_result = $oPCS_personne->fx_compter_tous_membres($oMSG)->getData(1)->fetchAll();# $nb_result contient le nombre de personne de la BDD.
	
	
	# On définit le nombre de résultats par page.
	$nb_result_affiches = 100;
	$limite = (int)$_GET['limite'];
	
	
	# On charge toutes les personnes
	$oMSG->setData('nb_result_affiches', $nb_result_affiches);
	$oMSG->setData('debut_affichage', $limite);

	$personnes = $oPCS_personne->fx_recuperer_membres_by_LIMIT($oMSG)->getData(1);
	
	
	
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
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>
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
	# On charge tous les packs existants.
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_pack.php');
	
	$oMSG = new MSG();
	$oPCS_pack = new PCS_pack();
	
	$oMSG->setData(0, "");
	$oMSG->setData(1, "");
	
	$packs = $oPCS_pack->fx_recuperer_tous_packs($oMSG)->getData(1)->fetchAll();
	
}else{
# Si l'internaute n'est pas connecté et admin il gicle.
header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/PCS_pack.php');
	require_once('couche_metier/PCS_annonce.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oPCS_pack = new PCS_pack();
	$oPCS_annonce = new PCS_annonce();
	

	# On récupère le nombre de comptes non activés.
	$oMSG->setData('CLE_ACTIVATION', "");
	
	$nb_comptes_inactifs = $oPCS_personne->fx_compter_comptes_non_actives($oMSG)->getData(1)->fetchAll();
	
	# On récupère le nombre de membres.
	$nb_comptes = $oPCS_personne->fx_compter_tous_membres($oMSG)->getData(1)->fetchAll();
	
	# On récupère le nombre de comptes supprimés.
	$oMSG->setData('PERSONNE_SUPPRIMEE', 1);
	$oMSG->setData('VISIBLE', 0);
	
	$nb_comptes_supprimes = $oPCS_personne->fx_compter_comptes_by_PERSONNE_SUPPRIMEE_et_VISIBILITE($oMSG)->getData(1)->fetchAll();
	
	# On récupère le nombre d'annonces en attentes.
	$oMSG->setData('VISIBLE', 0);
	
	$nb_annonces_en_attente = $oPCS_annonce->fx_compter_annonce_by_VISIBLE($oMSG)->getData(1)->fetchAll();
	// $nb_annonces_en_attente[0]['nb_annonce']
	
	# On récupère le nombre d'annonces totales.
	
	# On récupère le nombre de contrats en cours.
	
	# On récupère le nombre de contrats totaux.
	
	# On récupère le nombre packs existants.
	$nb_packs = $oPCS_pack->fx_compter_tous_packs($oMSG)->getData(1)->fetchAll();
	
	$oMSG->setData('VISIBLE', 0);
	$nb_packs_inactifs = $oPCS_pack->fx_compter_packs_by_VISIBLE($oMSG)->getData(1)->fetchAll();
	
	# On récupère le nombre de messages non lus de l'administration. --> secondaire
	

	
	
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_menuv_gestion_compte.php');

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
			
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');		
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		
		$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
		
		if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur"){
			# Le nombre d'annonces totales.
			$toutes_annonces = $oPCS_annonce->fx_compter_toutes_annonces_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			# Le nombre d'annonces en cours.
			$annonces_futures = $oPCS_annonce->fx_compter_annonces_futures_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
		}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
			# Le nombre total de prestations.
			
			# Le nombre de prestations prévues.
			
		}
		# Le nombre total de contrats.
		
		# Le nombre de contrats en cours.
		

		# Le nombre de messages non lus.

}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	# On initialise notre variable qui détermine l'affichage du message demandé.
	$id_message_ok = 0;
	if(isset($_GET['id_message'])){
		$ID_MESSAGE = (int)$_GET['id_message'];
		# On vérifie que l'identifiant de l'annonce soit valide.
		if($ID_MESSAGE > 0){
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_personne.php');
			require_once('couche_metier/PCS_message.php');
			require_once('couche_metier/PCS_types.php');
			require_once('couche_metier/CL_date.php');

			$oMSG = new MSG();
			$oPCS_personne = new PCS_personne();
			$oPCS_message = new PCS_message();
			$oPCS_types = new PCS_types();
			$oCL_date = new CL_date();
			
			# On récupère le message.
			$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);

			$message = $oPCS_message->fx_recuperer_message_by_ID_MESSAGE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			
			# On vérifie que le message existe bien, si ce n'est pas le cas soit il n'existe pas soit la personne n'y a pas accès.
			if(!empty($message[0]['ID_MESSAGE'])){
				$id_message_ok = 1;
				$maintenant_en = date("Y-m-d H:i:s");
				$maintenant_fr = date("d-m-Y H:i:s");
				
				# On met en forme les données.
				$message[0]['DATE_LECTURE'] = $oCL_date->fx_ajouter_date($message[0]['DATE_LECTURE'], true, false, 'en', 'fr');
				$message[0]['DATE_REPONSE'] = $oCL_date->fx_ajouter_date($message[0]['DATE_REPONSE'], true, false, 'en', 'fr');
				$message[0]['DATE_ENVOI'] = $oCL_date->fx_ajouter_date($message[0]['DATE_ENVOI'], true, false, 'en', 'fr');
				
				$message[0]['DATE_LECTURE'] = str_replace(' ', ' à ', $message[0]['DATE_LECTURE']);
				$message[0]['DATE_REPONSE'] = str_replace(' ', ' à ', $message[0]['DATE_REPONSE']);
				$message[0]['DATE_ENVOI'] = str_replace(' ', ' à ', $message[0]['DATE_ENVOI']);
				
				# On charge les informations de l'expéditeur.
				$oMSG->setData('ID_PERSONNE', $message[0]['EXPEDITEUR']);

				$expediteur = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
				//$expediteur[0]['PSEUDO'];

				# Si le message n'avait pas été lu et qu'il est de type contrat alors on envoi un email à l'expéditeur.
				if($message[0]['STATUT_MESSAGE'] == "Non lu" && $message[0]['TYPE_MESSAGE'] == "Contrat"){
					
					# On modifie la date de lecture:
					$message[0]['DATE_LECTURE'] = $maintenant_fr;
					$message[0]['DATE_LECTURE'] = str_replace(' ', ' à ', $message[0]['DATE_LECTURE']);
					
					# On envoi un email à l'expéditeur afin de lui dire que son annonce a été lue.
					$additional_headers = "From: noreply@liveanim.fr \r\n";
					$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
					$destinataires = $expediteur[0]['EMAIL'];
					$sujet = utf8_decode("LiveAnim [Lecture de votre demande de contrat]");
					
					$message_mail = "------------------------------\n";
					$message_mail.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
					$message_mail.= "------------------------------\n\n";
					$message_mail.= utf8_decode("Bonjour ".$expediteur[0]['PSEUDO'].", \n");
					$message_mail.= utf8_decode("Nous vous informons que votre demande de contrat à ".$_SESSION['compte']['PSEUDO']." vient d'être lue. \n\n");
					$message_mail.= utf8_decode("------------------------------\n\n\n");
					$message_mail.= utf8_decode("LiveAnim vous remercie de votre confiance !\n\n");
					$message_mail.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
					
					mail($destinataires, $sujet, $message_mail, $additional_headers);
					
					# On indique que le message a été lu et sa date de lecture.
					$oMSG->setData('DATE_LECTURE', $maintenant_en);
					$oMSG->setData('STATUT_MESSAGE', 'Lu');
					$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
					
					$oPCS_message->fx_message_lu($oMSG);
					
				}else if($message[0]['STATUT_MESSAGE'] == "Non lu"){
					# On modifie la date de lecture:
					$message[0]['DATE_LECTURE'] = $maintenant_fr;
					$message[0]['DATE_LECTURE'] = str_replace(' ', ' à ', $message[0]['DATE_LECTURE']);
					
					# On indique que le message a été lu et sa date de lecture.
					$oMSG->setData('DATE_LECTURE', $maintenant_en);
					$oMSG->setData('STATUT_MESSAGE', 'Lu');
					$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
					
					$oPCS_message->fx_message_lu($oMSG);
				}
				
			}else{
				$_SESSION['message']['message_affiche'] = false;
				$_SESSION['message']['message'] = "<span class='alert'>Vous ne possédez pas l'autorisation de consulter ce message.</span><br />";
			}
		}else{
			$_SESSION['message']['message_affiche'] = false;
			$_SESSION['message']['message'] = "<span class='alert'>Le message personnel que vous souhaitez lire n'existe pas. (2)</span><br />";
		}
	}else{
		$_SESSION['message']['message_affiche'] = false;
		$_SESSION['message']['message'] = "<span class='alert'>Le message personnel que vous souhaitez lire n'existe pas. (1)</span><br />";
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}
/////////////////////////////////////////// Comparer avec le script admin.
# On vérifie que la personne est connectée.
if($_SESSION['compte']['connecté'] == true){

	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_annonce.php');		
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
		
	# On récupère les types nécessaires:
	$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');# Vérifier la famille type.
	
	$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
	
	# On récupère les départements.
	$departements = $oPCS_annonce->fx_recuperer_tous_departements($oMSG)->getData(1)->fetchAll();
	
	# Récupérer l'id_annonce en GET et récupérer l'annonce associée.
	if(isset($_GET['id_annonce']) && is_numeric($_GET['id_annonce'])){
		$ID_ANNONCE = $_GET['id_annonce'];
		
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		
		$annonce = $oPCS_annonce->fx_recuperer_annonce_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
		# On vérifie que l'annonce appartient bien à cette personne.
		if($annonce[0]['ID_PERSONNE'] == $_SESSION['compte']['ID_PERSONNE']){
			# On vérifie que l'annonce n'ait pas déjà été validée.
			if($annonce[0]['STATUT'] != "Validée"){
				# On met en forme les dates.
				$annonce[0]['DATE_ANNONCE'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_ANNONCE'], true, false, 'en', 'fr');
				$annonce[0]['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_DEBUT'], true, false, 'en', 'fr');
				$annonce[0]['DATE_FIN'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_FIN'], true, false, 'en', 'fr');
				
				# On élimine les secondes et on convertit plus lisiblement
				$annonce[0]['DATE_DEBUT'] = substr(str_replace(':', 'h', $annonce[0]['DATE_DEBUT']), 0, -3);
				$annonce[0]['DATE_FIN'] = substr(str_replace(':', 'h', $annonce[0]['DATE_FIN']), 0, -3);
			
				# On met en forme les textarea:
				$annonce[0]['ARTISTES_RECHERCHES'] = str_replace(array('<br>', '<br />'), '', $annonce[0]['ARTISTES_RECHERCHES']);
				$annonce[0]['DESCRIPTION'] = str_replace(array('<br>', '<br />'), '', $annonce[0]['DESCRIPTION']);
			}else{
				$_SESSION['historique_annonce']['message_affiche'] = false;
				$_SESSION['historique_annonce']['message'] = "<span class='orange'>Vous ne pouvez pas modifier une annonce ayant été publiée.</span><br />";
				header('Location:'.$oCL_page->getPage('historique_annonce'));
			}
		}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
			# On redirige l'administrateur vers la page de modification des annonces pour les administrateurs.
			$_SESSION['modifier_annonce']['message'] = "<span class='rose'>Vous avez été redirigé vers l'interface administrateur.</span>";
			$_SESSION['modifier_annonce']['message_affiche'] = false;
			header('Location:'.$oCL_page->getPage('modifier_fiche_annonce_by_admin')."?id_annonce=".$annonce[0]['ID_ANNONCE']);
		}else{
			# L'utilisateur veut modifier une annonce qui ne lui appartient pas.
			header('Location:'.$oCL_page->getPage('gestion_compte'));
		}
		
	}
	
	# On récupère les types de statut.
	$oMSG->setData('ID_FAMILLE_TYPES', "Statut de l'annonce");# Vérifier la famille type
	
	$statuts = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();

}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
		
	# On récupère les types nécessaires:
	$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');# Vérifier la famille type.
	
	$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
	
	# On récupère les départements.
	$departements = $oPCS_annonce->fx_recuperer_tous_departements($oMSG)->getData(1)->fetchAll();
	
	# Récupérer l'id_annonce en GET et récupérer l'annonce associée.
	if(isset($_GET['id_annonce']) && is_numeric($_GET['id_annonce'])){
		$ID_ANNONCE = $_GET['id_annonce'];
		
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		
		$annonce = $oPCS_annonce->fx_recuperer_annonce_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
		
		# On met en forme les dates.
		$annonce[0]['DATE_ANNONCE'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_ANNONCE'], true, false, 'en', 'fr');
		$annonce[0]['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_DEBUT'], true, false, 'en', 'fr');
		$annonce[0]['DATE_FIN'] = $oCL_date->fx_ajouter_date($annonce[0]['DATE_FIN'], true, false, 'en', 'fr');
		
		# On élimine les secondes et on convertit plus lisiblement
		$annonce[0]['DATE_DEBUT'] = substr(str_replace(':', 'h', $annonce[0]['DATE_DEBUT']), 0, -3);
		$annonce[0]['DATE_FIN'] = substr(str_replace(':', 'h', $annonce[0]['DATE_FIN']), 0, -3);
		
		# On met en forme les textarea:
		$annonce[0]['ARTISTES_RECHERCHES'] = str_replace(array('<br>', '<br />'), '', $annonce[0]['ARTISTES_RECHERCHES']);
		$annonce[0]['DESCRIPTION'] = str_replace(array('<br>', '<br />'), '', $annonce[0]['DESCRIPTION']);
	}
	
	# On récupère les types de statut.
	$oMSG->setData('ID_FAMILLE_TYPES', "Statut de l'annonce");# Vérifier la famille type
	
	$statuts = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
	
	$ID_PERSONNE_ok = 0;
	
	# On récupère l'id_personne fournit et on va récupérer toutes ses infos personnelles.
	if(isset($_GET['id_personne']) && is_numeric($_GET['id_personne'])){
		
		$ID_PERSONNE_ok = 1;# On valide le fait que l'ID_PERSONNE a bien été réceptionné.
		
		$ID_PERSONNE = (int)$_GET['id_personne'];
		
		$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
		
		$personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
		
		# Ensuite on récupère ses IPs.
		
		$ip_personne = $oPCS_personne->fx_recuperer_toutes_ip_by_ID_PERSONNE($oMSG)->getData(1);
		
		# On récupère le parrain.
		$oMSG->setData('ID_PERSONNE', $personne[0]['PARRAIN']);
		$parrain = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
		
		# On récupère les statuts.
		$oMSG->setData('ID_FAMILLE_TYPES', 'Statut professionnel');
		$statuts = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
		
		# On gère l'url de la vidéo.
		require_once('couche_metier/CL_video.php');
		$oCL_video = new CL_video();
		
		$personne[0]['CV_VIDEO'] = $oCL_video->fx_recuperer_tag($personne[0]['CV_VIDEO']);
		
		# On met en forme la date qui vient de la BDD.
		$personne[0]['DATE_NAISSANCE'] = $oCL_date->fx_convertir_date($personne[0]['DATE_NAISSANCE']);
		
		# On vire les balises <br /> des textarea.
	$personne[0]['DESCRIPTION'] = str_replace('<br />', '', $personne[0]['DESCRIPTION']);
	$personne[0]['TARIFS'] = str_replace('<br />', '', $personne[0]['TARIFS']);
	$personne[0]['MATERIEL'] = str_replace('<br />', '', $personne[0]['MATERIEL']);
	}
	
	

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	require_once('couche_metier/PCS_pack.php');
	require_once('couche_metier/PCS_types.php');
	
	$oMSG = new MSG();
	$oPCS_pack = new PCS_pack();
	$oPCS_types = new PCS_types();
	
	$ID_PACK_ok = 0;
	
	# On récupère l'id_pack fournit et on va récupérer toutes ses infos.
	if(isset($_GET['id_pack']) && is_numeric($_GET['id_pack'])){
		
		$ID_PACK_ok = 1;# On valide le fait que l'ID_PACK a bien été réceptionné.
		
		$ID_PACK = (int)$_GET['id_pack'];
		
		# On récupère le pack en question.
		$oMSG->setData('ID_PACK', $ID_PACK);
		
		$pack = $oPCS_pack->fx_recuperer_pack_by_ID_PACK($oMSG)->getData(1)->fetchAll();
		
		# On récupère les types.
		$oMSG->setData('ID_FAMILLE_TYPES', 'Type de pack');
		
		$types_pack = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
		
	}else{
		$ID_PACK_ok = 0;# L'id_pack reçu est invalide.
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');

	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
	
	$ID_PERSONNE_ok = 1;
	
	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	
	$personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	
	# Ensuite on récupère ses IPs.
		
	$ip_personne = $oPCS_personne->fx_recuperer_toutes_ip_by_ID_PERSONNE($oMSG)->getData(1);
	
	# On récupère le parrain.
	$oMSG->setData('ID_PERSONNE', $personne[0]['PARRAIN']);
	$parrain = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	
	# On récupère les statuts.
	$oMSG->setData('ID_FAMILLE_TYPES', 'Statut professionnel');
	$statuts = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
	
	# On gère l'url de la vidéo.
	require_once('couche_metier/CL_video.php');
	$oCL_video = new CL_video();
	
	$personne[0]['CV_VIDEO'] = $oCL_video->fx_recuperer_tag($personne[0]['CV_VIDEO']);
	
	# On met en forme la date qui vient de la BDD.
	$personne[0]['DATE_NAISSANCE'] = $oCL_date->fx_convertir_date($personne[0]['DATE_NAISSANCE']);
	
	# On vire les balises <br /> des textarea.
	$personne[0]['DESCRIPTION'] = str_replace('<br />', '', $personne[0]['DESCRIPTION']);
	$personne[0]['TARIFS'] = str_replace('<br />', '', $personne[0]['TARIFS']);
	$personne[0]['MATERIEL'] = str_replace('<br />', '', $personne[0]['MATERIEL']);
			
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(isset($_POST['form_recherche_annonce_date_debut'])){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_annonce.php');
	require_once('couche_metier/PCS_types.php');
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_annonce = new PCS_annonce();
	$oPCS_types = new PCS_types();
	$oCL_date = new CL_date();
	
	# On récupère les types.
	$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');
	$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
	
	
	$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
	$chaines_interdites2 = array("/'/", "/\"/");
		
	# On récupère les données du formulaire.
	$DATE_DEBUT = preg_replace($chaines_interdites, "", trim($_POST['form_recherche_annonce_date_debut']));
	$DATE_FIN = preg_replace($chaines_interdites, "", trim($_POST['form_recherche_annonce_date_fin']));
	$TYPE_ANNONCE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_recherche_annonce_type_annonce'])));
	$BUDGET = preg_replace($chaines_interdites, "", (int)trim($_POST['form_recherche_annonce_budget']));# On s'en fout des décimales...
	$CP_VILLE = preg_replace($chaines_interdites, "", trim($_POST['form_recherche_annonce_cp_ville']));
	$CP_VILLE = preg_replace($chaines_interdites2, "%", $CP_VILLE);# On fera une recherche plus globale avec le caractère %.
	
	# On sauvegarde les données en session pour les réutiliser dans le script_prechargement_liste_annonce.php.
	$_SESSION['recherche_annonce']['DATE_DEBUT'] = $DATE_DEBUT;
	$_SESSION['recherche_annonce']['DATE_FIN'] = $DATE_FIN;
	$_SESSION['recherche_annonce']['TYPE_ANNONCE'] = $TYPE_ANNONCE;
	$_SESSION['recherche_annonce']['BUDGET'] = $BUDGET;
	$_SESSION['recherche_annonce']['CP_VILLE'] = $CP_VILLE;
	$_SESSION['recherche_annonce']['recherche_effectuée'] = 1;
	
	
	# On vérifie les dates.
	if(!$oCL_date->fx_verif_date($DATE_DEBUT)){
		# DATE_DEBUT n'est pas une date.
		$_SESSION['recherche_annonce']['DATE_DEBUT'] = date('Y-m-d');
	}else{
		$_SESSION['recherche_annonce']['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($DATE_DEBUT, false, false, 'fr', 'en');
	}
	if(!empty($DATE_FIN)){
		if(!$oCL_date->fx_verif_date($DATE_FIN)){
			$_SESSION['recherche_annonce']['DATE_FIN'] = "2020-01-01";
		}else{
			$_SESSION['recherche_annonce']['DATE_FIN'] = $oCL_date->fx_ajouter_date($DATE_FIN, false, false, 'fr', 'en');
		}
	}else{
		$_SESSION['recherche_annonce']['DATE_FIN'] = "2020-01-01";
	}
	// Dans l'un ou l'autre cas, on effectue la recherche avec une date de fin très lointaine.

	# Vérification du types de l'annonce.
	if($TYPE_ANNONCE != "*"){# Si le mec a sélectionné un type spécifique on vérifie qu'il existe.
		$liste_types_annonce = array();
		foreach($types_annonce as $key=>$type_annonce){
			$liste_types_annonce[$key] = $type_annonce['ID_TYPES'];
		}
		if(!in_array($TYPE_ANNONCE, $liste_types_annonce)){
			# L'utilisateur a modifié le code source.
			$_SESSION['recherche_annonce']['TYPE_ANNONCE'] = '*';
		}
	}

	
	# On redirige vers la page de listage des annonces. 
	# Le script_prechargement_liste_annonce va automatiquement faire les requêtes en fonction des variables de session.
	header('Location:'.$oCL_page->getPage('liste_annonce').'#resultats_recherche');
	
}else{
	# L'utilisateur ne vient pas depuis le formulaire.
	header('Location:'.$oCL_page->getPage('liste_annonce'));
}
?><?php
session_start();
require_once('couche_metier/CL_page.php');
$oCL_page = new CL_page();

$_SESSION['récupération'] = array();
$_SESSION['récupération']['message'] = "";
$_SESSION['récupération']['message_affiche'] = false;

$nb_erreur = 0;

if(isset($_POST['btn_form_recuperation_mdp_valider'])){
	# Si on reçoit bien nos données du formulaire de récupération de mot de passe.
	# On vérifie que les champs ne sont pas vides.
	if(empty($_POST['form_recuperation_mdp_pseudo']) || empty($_POST['form_recuperation_mdp_email'])){
		$_SESSION['récupération']['message'].= "<span class='alert'>Un des deux champs est vide.</span><br />";
		header('location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
		$nb_erreur++;
	}

	# On vérifie que le pseudo a une taille correcte.
	if(strlen($_POST['form_recuperation_mdp_pseudo']) < 4){
		$_SESSION['récupération']['message'].= "<span class='alert'>Le pseudo doit faire au moins 4 caractères.</span><br />";
		header('location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
		$nb_erreur++;
	}

	if(strlen($_POST['form_recuperation_mdp_pseudo']) > 20){
		$_SESSION['récupération']['message'].= "<span class='alert'>Le pseudo doit faire moins de 20 caractères.</span><br />";
		header('location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
		$nb_erreur++;
	}

	# On vérifie l'intégrité de l'email.
	if(!filter_var($_POST['form_recuperation_mdp_email'], FILTER_VALIDATE_EMAIL)){
		
		$_SESSION['récupération']['message'].= "<span class='alert'>L'email que vous avez rentré est invalide.</span><br />";
		header('Location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
		$nb_erreur++;
	}

	# Une fois les vérifications faites, on vérifie qu'il n'y a pas d'erreur.
	if($nb_erreur == 0){
	
		$PSEUDO = ucfirst(trim($_POST['form_recuperation_mdp_pseudo']));
		$EMAIL = trim($_POST['form_recuperation_mdp_email']);
		
		# On récupère toutes les personnes et leur MDP qui ont ce mail et ce pseudo (normalement, 1 max).
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_personne.php');
		
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		
		$oMSG->setData('PSEUDO', $PSEUDO);
		$oMSG->setData('EMAIL', $EMAIL);
		
		$Personne = $oPCS_personne->fx_recuperer_compte_by_PSEUDO_et_EMAIL($oMSG)->getData(1)->fetchAll();
		
		# On vérifie qu'on a bien un tableau non vide
		if(isset($Personne[0])){
			if(empty($Personne[0])){
				# Erreur: On a un tableau vide
				$_SESSION['récupération']['message'].= "<span class='alert'>Il n'y a pas de compte existant pour ce couple Pseudo/Mot de passe. Réessayez.</span><br />";
				header('Location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
				die();
			}

			}else{
			# Erreur: On a un tableau vide
			$_SESSION['récupération']['message'].= "<span class='alert'>Il n'y a pas de compte existant pour ce couple Pseudo/Mot de passe. Réessayez.</span><br />";
			header('Location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
			die();
		}

		# On récupère et décode le MDP.
		$MDP = utf8_decode($Personne[0]['MDP']);
		
		require_once('couche_metier/CL_cryptage.php');
		$oCL_cryptage= new CL_cryptage();
		
		# On décrypte le MDP
		$MDP = $oCL_cryptage->Cryptage($MDP, $PSEUDO);

		# On envoi le MDP par email à l'EMAIL fournit.
		
		$additional_headers = "From: noreply@wikibakoro.fr.cr/ \r\n";
		$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
		$destinataires = $EMAIL;
		$sujet = utf8_decode("[Wiki de Bakoro] Récupération de mot de passe");
		
		$message = "------------------------------\n";
		$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
		$message.= "------------------------------\n\n";
		$message.= utf8_decode("Bonjour ".$PSEUDO.", \n");
		$message.= utf8_decode("Vous recevez cet e-mail car vous avez fait une récupération de mot de passe sur nos services. \n");
		$message.= utf8_decode("Si ce n'est pas vous qui avez fait cette récupération sachez que le mot de passe n'a pas été modifié et vous est simplement renvoyé à cette adresse e-mail. \n");
		$message.= utf8_decode("L'adresse IP enregistrée lors de la demande de récupération est: ".$_SERVER["REMOTE_ADDR"]." \n\n");
		$message.= utf8_decode("Votre mot de passe: ".$MDP." \n\n------------------------------\n");
		$message.= utf8_decode("Nous vous souhaitons de nombreuses et agréables visites sur notre site !\n\n");
		$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
		
		if(mail($destinataires, $sujet, $message, $additional_headers)){
			$_SESSION['récupération']['message'] = "<span class='valide'>Un e-mail vous a été envoyé. Pensez à vérifier vos spams !</span><br />";
		}else{
			$_SESSION['récupération']['message'] = "<span class='alert'>Le serveur de mail ne fonctionne pas, veuillez contacter directement un administrateur pour qu'il règle votre problème ou bien attendre que le serveur de mail soit à nouveau fonctionnel. Nous nous excusons pour cet incident sur lequel nous n'avons aucune possibilité de gestion.</span><br />";
		}	
		header('Location: '.$oCL_page->getPage('recuperation_mdp', 'absolu'));
	}
}else{
	header('location: '.$oCL_page->getPage('recuperation_mdp', 'absolu', 'absolu'));
}<?php
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
	if(isset($_POST['form_supprimer_compte_email'])){
	
		# On prépare nos variables.
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_personne.php');
		require_once('couche_metier/CL_cryptage.php');
		
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		$oCL_cryptage = new CL_cryptage();
		
		$_SESSION['supprimer_compte']['message'] = "";
		$_SESSION['supprimer_compte']['message_affiche'] = false;

		$nb_erreur = 0;
	
		# On vérifie qu'un des champs obligatoire ne soit pas vide.
		if(empty($_POST['form_supprimer_compte_email']) || empty($_POST['form_supprimer_compte_raison']) || empty($_POST['form_supprimer_compte_mdp'])){
			$nb_erreur++;
			$_SESSION['supprimer_compte']['message'].= "<span class='alert'>Un des champs obligatoires est vide.</span><br />";				
		}
		
		# On supprime les chaines interdites.
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/");
		
		$EMAIL = preg_replace ($chaines_interdites, "", trim($_POST['form_supprimer_compte_email']));
		$RAISON_SUPPRESSION = preg_replace ($chaines_interdites, "", trim(ucfirst($_POST['form_supprimer_compte_raison'])));
		$MDP = $_POST['form_supprimer_compte_mdp'];
		
		# On vérifie que la taille du mot de passe.
		if(strlen($MDP) < 4){
			$nb_erreur++;
			$_SESSION['supprimer_compte']['message'].= "<span class='alert'>Le mot de passe est trop court, 4 caractères minimum.</span><br />";
		}
		
		if(strlen($MDP) > 20){
			$nb_erreur++;
			$_SESSION['supprimer_compte']['message'].= "<span class='alert'>Le mot de passe est trop long, 20 caractères maximum.</span><br />";
		}
		
		# On vérifie que le format de l'adresse email soit valide.
		if(!filter_var($EMAIL, FILTER_VALIDATE_EMAIL)){
			$_SESSION['supprimer_compte']['message'].= "<span class='alert'>L'email que vous avez rentré est invalide.</span><br />";
			$nb_erreur++;
		}
		
		# S'il n'y a pas d'erreurs.
		if($nb_erreur == 0){
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('EMAIL', $EMAIL);
			$oMSG->setData('MDP', utf8_encode($oCL_cryptage->Cryptage($MDP, $_SESSION['compte']['PSEUDO'])));
			$oMSG->setData('RAISON_SUPPRESSION', $RAISON_SUPPRESSION);
			
			# On vérifie que le mot de passe et l'email et l'ID_PERSONNE correspondent bien.
			$nb_personne = $oPCS_personne->fx_compter_personne_by_ID_PERSONNE_et_EMAIL_et_MDP($oMSG)->getData(1)->fetchAll();

			if($nb_personne[0]['nb_personne'] == 1){
				if($_POST['form_supprimer_compte_infos_perso']){
					# On calcule la date d'aujourd'hui.
					$now = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"),  date("Y")));
				
					# S'il souhaite supprimer immédiatement ses informations personnelles.
					$oMSG->setData('NOM', '');
					$oMSG->setData('PRENOM', '');
					$oMSG->setData('URL_PHOTO_PRINCIPALE', '');
					$oMSG->setData('DATE_NAISSANCE', '0000-00-00');
					$oMSG->setData('CIVILITE', '');
					$oMSG->setData('VILLE', '');
					$oMSG->setData('ADRESSE', '');
					$oMSG->setData('CP', '');
					$oMSG->setData('TEL_FIXE', '');
					$oMSG->setData('TEL_PORTABLE', '');
					
					$oMSG->setData('VISIBLE', 0);
					$oMSG->setData('PERSONNE_SUPPRIMEE', 1);
					$oMSG->setData('DATE_BANNISSEMENT', '0000-00-00');
					$oMSG->setData('DATE_SUPPRESSION_REELLE', $now);
					$oMSG->setData('RAISON_SUPPRESSION', $RAISON_SUPPRESSION);
					
					$oPCS_personne->fx_supprimer_infos_perso_by_ID_PERSONNE($oMSG);
					$oPCS_personne->fx_bannir_personne($oMSG);
					
					$_SESSION = array();
					session_destroy();
					session_unset();
					session_start();
					
					# On s'apprête à afficher le message sur la page d'accueil.
					$_SESSION['connexion']['message_affiche'] = false;
					$_SESSION['connexion']['message'].= "<span class='valide'>Votre compte a été supprimé et vos informations personnelles supprimées. Votre avis a bien été pris en compte.</span><br />Vous avez été déconnecté.";
					header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
					
				}else{
					# On calcule la date d'aujourd'hui + 2 mois.
					$now = date("Y-m-d", mktime(0, 0, 0, date("m")+2, date("d"),  date("Y")));
				
					$oMSG->setData('VISIBLE', 0);
					$oMSG->setData('PERSONNE_SUPPRIMEE', 1);
					$oMSG->setData('DATE_BANNISSEMENT', '0000-00-00');
					$oMSG->setData('DATE_SUPPRESSION_REELLE', $now);
					$oMSG->setData('RAISON_SUPPRESSION', $RAISON_SUPPRESSION);
					
					$oPCS_personne->fx_bannir_personne($oMSG);
					
					$_SESSION = array();
					session_destroy();
					session_unset();
					session_start();
					
					# On s'apprête à afficher le message sur la page d'accueil.
					$_SESSION['connexion']['message_affiche'] = false;					
					$_SESSION['connexion']['message'].= "<span class='valide'>Votre compte a bien été désactivé. Vos informations personnelles seront supprimées d'ici 2 mois.<br/>Votre avis a bien été pris en compte.</span><br />Vous avez été déconnecté.";
					header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
				}
				
				
			
			}else{
				$_SESSION['supprimer_compte']['message'].= "<span class='alert'>Le mot de passe ou l'email sont incorrects.</span><br />";
				header('Location: '.$oCL_page->getPage('supprimer_compte', 'absolu'));
			}
		}else{
			$_SESSION['supprimer_compte']['message'].= "<span class='alert'>Le compte n'a pas été supprimé.</span><br />";
			header('Location: '.$oCL_page->getPage('supprimer_compte', 'absolu'));
		}		
	}else{
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Supprimer mon compte</title>
</head>
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
		
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "supprimer_compte";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_supprimer_compte.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Achat de pack annulé</title>
</head>
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
		
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "achat_pack_annule";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_achat_pack_annule.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Achat pack réussi</title>
</head>
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
	
	require_once('script_prechargement_achat_pack_ok.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "achat_pack_ok";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_achat_pack_ok.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Acheter un pack</title>
</head>
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
	
	require_once('script_prechargement_acheter_pack.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "acheter_pack";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>
	<script type="text/javascript" src="<?php echo $oCL_page->getPage('acheter_pack.js'); ?>">
	
	</script>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_acheter_pack.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Activer un compte</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_activer_comptes.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "activer_comptes";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_activer_comptes.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Administration</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "administration";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>
<head>
<title>Administration</title>
</head>
	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_administration.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Ajouter un pack</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_ajouter_pack.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "ajouter_pack";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_ajouter_pack.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Annonce N°<?php echo $_GET['id_annonce']; ?></title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=fr"></script>
<script type="text/javascript" src="js/GMap.js"></script>
</head>
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
		
	require_once('script_prechargement_annonce.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "annonce";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_dernieres_annonces.php
						*/
							require_once('include_panel_GMap.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_annonce.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Bannir un membre</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_bannir_membre.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "bannir_membre";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_bannir_membre.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Changer le rang d'un utilisateur</title>
</head>
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
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "changer_rang";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_changer_rang.php');
						?>	
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Les comptes supprimés par les utilisateurs</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_comptes_supprimes.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "comptes_supprimes";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_comptes_supprimes.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Créer une annonce</title>
</head>
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
		
	require_once('script_prechargement_creer_annonce.php');
		
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "creer_annonce";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_creer_annonce.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Créer un contrat</title>
</head>
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
	
	require_once('script_prechargement_creer_contrat.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "creer_contrat";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>
<script type="text/javascript" src="js/creer_contrat.js"></script>
	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_creer_contrat.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Mes filleuls</title>
</head>
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
		
	require_once('script_prechargement_filleuls.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "filleuls";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_filleuls.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Gestion du compte</title>
</head>
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
	
	require_once('script_prechargement_gestion_compte.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "gestion_compte";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_gestion_compte.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Historique de mes achats de pack</title>
</head>
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
	
	require_once('script_prechargement_historique_achat_pack.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "historique_achat_pack";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>
<script type="text/javascript" src="<?php echo $oCL_page->getPage('activer_pack.js'); ?>" ></script>
	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_historique_achat_pack.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Historique de mes annonces</title>
</head>
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
		
	require_once('script_prechargement_historique_annonce.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "historique_annonce";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_historique_annonce.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Ma messagerie</title>
</head>
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
		
	require_once('script_prechargement_historique_contrat.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "historique_contrat";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_historique_contrat.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>
	<h2>Paiement annulé !</h2><br />
	<p>
		<br />
		Vous avez annulé le paiement en cours. L'opération a été annulée et votre compte n'a pas été débité.<br />
		<br />
		Sachez que le paiement est géré par PayPal et donc entièrement sécurisé:<br />
		<p>
			Le service que propose PayPal est de payer en ligne sans communiquer ses données financières, en s’identifiant simplement avec son adresse électronique et un mot de passe.<br />
			Il n'est pas nécessaire d'alimenter son compte PayPal à l'avance.<br />
			La source d'approvisionnement que vous avez choisie (carte de paiement ou compte bancaire) est automatiquement débitée au moment de la transaction.<br />
			Les destinataires sont avertis par courriel dès que vous leur envoyez de l’argent.<br />
			PayPal peut également permettre de transférer des fonds d’un compte vers un autre internaute à condition que le destinataire ait un compte PayPal.<br />
			Ce service est gratuit si vous choisissez un compte bancaire comme source d'approvisionnement, ou s'il y a de l'argent disponible sur votre compte PayPal.<br />
		</p>
	Pour plus d'informations, nous vous invitons à <a href="https://cms.paypal.com/fr/cgi-bin/marketingweb?cmd=_render-content&content_ID=marketing_fr/particuliers_acheter&nav=0.1">visiter leur site officiel.</a>
		
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>
	<h2>Paiement réussi !</h2><br />
	<br />
	<p>
	<span class="valide">Votre achat a bien été réalisé, vous pouvez dès à présent activer votre nouveau pack si vous le souhaitez.</span><br />
	Vous pouvez visionner toutes les informations concernant votre compte dans votre profil s'il a été activé.<br />
	Si vous avez préféré attendre la fin de validité de votre compte actuel, sachez que votre nouveau compte s'activera automatiquement dès que celui-ci sera fini.<br />
	</p><br />
	
	

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>
	<h2>Acheter un pack:</h2><br />
	<br />
	Voici tous nos packs, vous pouvez en choisir un nouveau selon votre besoin.<br />
	Si vous avez déjà un pack d'activé sachez que vous concerverez votre pack actuel d'activé si le pack acheté est moins intéressant que l'actuel.<br />
	Par contre si le pack acheté est plus intéressant que l'actuel vous l'activerez automatiquement.<br />
	<br />
	Dans le but de vous laisser gérer avec le plus de facilités possibles vos achats, vous pouvez forcer l'activation/désactivation de votre pack actuel, selon votre choix.<br />
	Vous restez donc dans les deux cas le maître de la situation !<br />
	<br />
	<span title="A moins de faire appel à notre SAV afin qu'ils modifient manuellement votre erreur." class="petit orange">/!\ <u>Attention</u>: Une fois le paiement validé le choix de l'activation/non activation du pack acheté est <i>définitif</i>, donc n'hésitez pas à annuler le paiement avant de le valider si vous avez fait une erreur.</span>
	<br />
	<br />
	Si vous demandez une activation immédiate de votre nouveau pack, le délai de l'actuel sera terminé. Vous ne récupèrerez pas le temps non utilisé entre les deux packs.<br />
	A contraire, si le pack acheté s'active à la fin de votre pack actuel alors vous ne perdrez rien.<br />
	<br />
	<?php
	foreach($packs as $key=>$pack){
	?>
		<fieldset <?php if($pack['ID_PACK'] == $_SESSION['pack']['ID_PACK']){ echo " class='selectionne'";} ?> id="pack"><legend class="legend_basique"><?php echo $pack['NOM'] ?></legend><br />
			<?php if($pack['ID_PACK'] == $_SESSION['pack']['ID_PACK'] && $_SESSION['pack']['activé'] == true){ echo "<span class='petit gras'>[Pack Activé jusqu'au ".$_SESSION['pack']['date_fin_validite']."]</span>";} ?>
			<center class="valide"><br />
				<?php echo $pack['DESCRIPTION'] ?><br />
			</center>
			<br />
			<center>
			Le prix de ce pack est de <b><?php echo $pack['PRIX_BASE'] ?>€/mois</b>. Il dure <b><?php echo $pack['DUREE'] ?> mois</b>.<br />
			<br />
			</center>
			<?php
			if($pack['SOUMIS_REDUCTIONS_PARRAINAGE']){
				?>
				<span class="cool gras">Le prix de ce pack peut être réduit grâce à vos éventuelles réductions d'un maximum de <?php echo $pack['GAIN_PARRAINAGE_MAX'] ?>%.</span>
				<?php
			}else{
				?>
				<span class="bad souligne">Le prix de ce pack ne peut pas être réduit via d'éventuelles réductions.</span>
				<?php
			}
			?>
			<br />
			<br />
			<span class="souligne">Si l'un de vos filleuls achète ce pack vous bénéficirez automatiquement d'une réduction de <?php echo $pack['REDUCTION'] ?>% en plus.</span> <span class='petit'>(Cumulable)</span><br />
			<br />
			<center class="souligne gras">Les avantages de ce pack:</center><br />
			<ul>
				<?php
				if($pack['CV_VISIBILITE'] == 10){
					?>
					<li></li>
					<?php
					}else if($pack['CV_VISIBILITE'] > 8){
					?>
					<li></li>
					<?php
					}else if($pack['CV_VISIBILITE'] > 6){
					?>
					<li></li>
					<?php
					}else if($pack['CV_VISIBILITE'] > 4){
					?>
					<li class="cool">La visibilité de votre C.V bénéficiera d'un gros coup de pouce de notre part.</li>
					<?php
					}else if($pack['CV_VISIBILITE'] > 2){
					?>
					<li class="bad gras">La visibilité de votre C.V bénéficiera d'un petit coup de pouce.</li>
					<?php
					}else if($pack['CV_VISIBILITE'] > 0){
					?>
					<li class="bad">La visibilité de votre C.V sera légèrement améliorée.</li>
					<?php
					}else{
					?>
					<li class="alert">Votre C.V sera dans les derniers affichés.</li>
					<?php
				}
				?>

				<?php
				if($pack['CV_ACCESSIBLE'] == 10){
					?>
					<li class="cool gras">Votre C.V affichera le maximum d'informations aux Organisateur le consultant.</li>
					<?php
					}else if($pack['CV_ACCESSIBLE'] > 8){
					?>
					<li></li>
					<?php
					}else if($pack['CV_ACCESSIBLE'] > 6){
					?>
					<li></li>
					<?php
					}else if($pack['CV_ACCESSIBLE'] > 4){
					?>
					<li class="cool">Quiconque consultera votre C.V trouvera probablement toutes les informations dont il a besoin.</li>
					<?php
					}else if($pack['CV_ACCESSIBLE'] > 2){
					?>
					<li class="bad gras">Votre C.V permettra d'afficher tout un tas d'informations intéressantes.</li>
					<?php
					}else if($pack['CV_ACCESSIBLE'] > 0){
					?>
					<li class="bad">Votre C.V affichera quelques informations utiles aux Organisateur le consultant.</li>
					<?php
					}else{
					?>
					<li class="alert">Votre C.V affichera le minimum d'informations aux Organisateur le consultant.</li>
					<?php
				}
				?>
				<?php
				if($pack['NB_FICHES_VISITABLES'] > 1000){
				?>
					<li class="cool gras souligne">Vous pourrez visiter un nombre illimité d'annonces&nbsp;!</li>
				<?php
				}else{
				?>
					<li class="gras">Vous pourrez visiter jusqu'à <?php echo $pack['NB_FICHES_VISITABLES'] ?> annonces.</li>
				<?php
				}
				if($pack['CV_VIDEO_ACCESSIBLE']){
				?>
					<li class="cool">Les personnes consultant votre C.V pourront voir votre C.V vidéo.</li>
				<?php
				}else{
				?>
					<li class="bad">Les personnes consultant votre C.V ne pourront pas voir votre C.V vidéo.</li>
				<?php
				}
				
				if($pack['ALERTE_NON_DISPONIBILITE']){
				?>
					<li class="cool">Lors d'une annulation de contrat de la part d'un autre artiste habitant dans les départements que vous surveillez, vous serez automatiquement prévenu.</li>
					<li class="cool">Vous pourrez surveiller jusqu'à <?php echo $pack['NB_DEPARTEMENTS_ALERTE'] ?> départements.</li>
				<?php
				}else{
				?>
					<li class="bad">Vous ne bénéficierez pas du service d'alerte en cas d'annulation de contrat par un autre artiste dans les départements que vous surveillez.</li>
				<?php
				}
				
				if($pack['PARRAINAGE_ACTIVE']){
				?>
					<li class="cool">Vous pourrez parrainer vos amis afin de bénéficier de réductions à chaque fois qu'ils achèteront un pack.</li>
				<?php
				}else{
				?>
					<li class="bad">Vous ne pourrez pas parrainer vos amis et donc n'aurez accès qu'à très peu de réductions possibles.</li>
				<?php
				}
				
				if($pack['PREVISUALISATION_FICHES']){
				?>
					<li class="cool">Vous pourrez prévisualiser les annonces ce qui vous permettra de connaître certaines informations sans pour autant diminuer votre quota d'annonces visitables.</li>
				<?php
				}else{
				?>
					<li class="bad">Vous ne pourrez pas prévisualiser les annonces et de ce fait devrez les consulter afin de prendre connaissance de la moindre information ce qui diminuera votre quota d'annonces visitables.</li>
				<?php
				}
				
				if($pack['CONTRATS_PDF']){
				?>
					<li class="cool">Vous pourrez récupérer tous vos contrats sous format .pdf tout simplement.</li>
				<?php
				}else{
				?>
					<li class="bad">Vous ne pourrez pas récupérer vos contrats sous format .pdf.</li>
				<?php
				}
				
				if($pack['SUIVI']){
				?>
					<li class="cool">Vous bénéficierez d'un suivi automatisé afin d'avoir une vue globale des vos dépenses et gains.</li>
				<?php
				}else{
				?>
					<li class="bad">Vous ne bénéficirez pas d'un suivi automatique et n'aurez pas accès à vos statistiques.</li>
				<?php
				}
				
				if($pack['PUBS']){
				?>
					<li class="bad">Toutes les publicités seront activées.</li>
				<?php
				}else{
				?>
					<li class="cool">Aucune publicité ne viendra vous déranger durant votre navigation sur le site</li>
				<?php
				}
				?>
			</ul>
			<br />
			<?php
			/*
			* 	action --> Dirige vers paypal. /!\ Ne pas oublier de virer le sandbox pour le vrai script.
			*	amount: Somme à payer.
			*	currency_code: Type de monnaie.
			*	shipping: Frais de port.
			*	tax: Taxe.
			*	return: Page sur laquelle est redirigé l'utilisateur à la fin du paiement. (Bravo vous avez réussi !)
			* 	cancel_return: Page sur laquelle est redirigé l'utilisateur si le paiement est annulé.
			*	notify_url: Notification instantanée de Paiement (IPN). /!\ Hyper important.
			*	cmd: Le type de commande, ne pas modifier.
			*	business: Nom du compte qui va recevoir l'argent.
			*	item_name: Le nom de l'item vendu.
			*	no_note: 
			*	lc:	
			*	bn:
			*	custom: Toutes nos propres variables --> id_personne, id_pack
			*/
			?>
			<center>
				<?php
				if($pack['SOUMIS_REDUCTIONS_PARRAINAGE'] == true && $pack['VISIBLE'] == true && $pack['beneficie_reduction'] == true){
				?>
				Vous bénéficiez de la réduction suivante sur ce pack:<br />
				Total de réduction sur le pack: <b class="valide"><?php echo $pack['nouvelle_reduction']; ?>%.</b><br />
				Prix <span class="petit">(Avec réduction)</span>: <b class="cool"><?php echo $pack['nouveau_prix']; ?>€</b><br />
				<br />
				Soit une réduction <b class="cool">de <?php echo $pack['economie']; ?>€ !</b><br />
				<?php
				}
				?>
				<label for="activer_pack_maintenant">Activer le pack dès maintenant&nbsp;</label><input onclick="maj_formulaire_paiement('custom', '<?php echo $_SESSION['compte']['ID_PERSONNE']; ?>', '<?php echo $pack['ID_PACK']; ?>');" type="checkbox" name="activer_pack_maintenant" id="activer_pack_maintenant" <?php if($_SESSION['pack']['PRIX_BASE'] < $pack['PRIX_BASE']){echo "checked='checked'";} ?> /><br />
					<br />
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input name="amount" type="hidden" value="<?php if(isset($pack['nouveau_prix']) && !empty($pack['nouveau_prix'])){echo $pack['nouveau_prix'];}else{echo $pack['PRIX_BASE'];} ?>" />
					<input name="currency_code" type="hidden" value="EUR" />
					<input name="shipping" type="hidden" value="0.00" />
					<input name="tax" type="hidden" value="0.00" />
					<input name="return" type="hidden" value="<?php echo $oCL_page->getPage('achat_pack_ok', 'absolu'); ?>" />
					<input name="cancel_return" type="hidden" value="<?php echo $oCL_page->getPage('achat_pack_annule', 'absolu'); ?>" />
					<input name="notify_url" type="hidden" value="<?php echo $oCL_page->getPage('IPN', 'absolu'); ?>" />
					<input name="cmd" type="hidden" value="_xclick" />
					<input name="business" type="hidden" value="<?php echo $oCL_page->getConfig('compte_credite'); ?>" />
					<input name="item_name" type="hidden" value="<?php echo $pack['NOM']; ?>" />
					<input name="no_note" type="hidden" value="1" />
					<input name="lc" type="hidden" value="FR" />
					<input name="bn" type="hidden" value="PP-BuyNowBF" />
					<input id="custom" name="custom" type="hidden" value="id_personne=<?php echo $_SESSION['compte']['ID_PERSONNE']; ?>&id_pack=<?php echo $pack['ID_PACK']; ?>&duree=1&activer_pack_maintenant=0" />
					<br />
					<input type="image" src="<?php echo $oCL_page->getImage('paypal_boutons'); ?>" title="Acheter le forfait <?php echo $pack['NOM']; ?> (Via Paypal)" alt="Acheter le forfait <?php echo $pack['NOM']; ?> (Via Paypal)">
				</form>
				<span class="petit cool gras">Vous serez redirigé vers PayPal pour cette transaction, vous pouvez payer <u>via tous les moyens de paiement acceptés par PayPal</u>.<br />
				La transaction bancaire est <u>assurée</u> par <u>PayPal</u> et est <u>sécurisée</u>.</span>
			</center>
			<br />
		</fieldset>
		<br />
		<br />
	<?php
	}# Fin du foreach des packs.
	?>
	

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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

	?>
	<h2>Page d'activation des comptes:</h2>

	Voici une liste de tous les comptes qui ont été crées sur une IP déjà utilisée.<br />
	En général il y aura autant d'IP que d'IP cookie. Si jamais il y a plus d'IP cookie que d'IP alors c'est que le membre change d'IP (box) sans vider ses cookies.<br />
	Dans ce cas, si l'IP est différente de celle du cookie alors la ligne devient rouge.<br />
	<br />
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
	  <tr class="valide">
			<th width="15%" scope="col">Pseudo</th>
			<th width="15%" scope="col">IP</th>
			<th width="15%" scope="col">IP <span class="petit">(cookie)</span></th>
			<th width="30%" scope="col">Date de création</th>
			<th width="5%" scope="col">Activer</th>
			<th width="5%" scope="col">Bannir</th>
	    </tr>
	<?php
	foreach($comptes_inactifs as $key=> $compte_inactif){
		if($compte_inactif['VALIDE'] == true){
			echo "<span class='alert'>";# Couleur d'alerte si un compte possède encore une clé d'activation en étant valide.
		}
	?>
		<tr><td colspan="6"><hr /></td></tr>
		<tr <?php if($compte_inactif['ID_IP'] != $compte_inactif['IP_COOKIE']){echo "class='alert'";}else{echo "class='valide'";} ?>>
			<th><span title="ID_PERSONNE: N°<?php echo $compte_inactif['ID_PERSONNE']; ?>"><a href="<?php echo $oCL_page->getPage('modifier_fiche_membre')."?id_personne=".$compte_inactif['ID_PERSONNE']; ?>"><?php echo $compte_inactif['PSEUDO']; ?></a></span></th>
			<th><?php echo $compte_inactif['ID_IP']; ?></th>
			<th><?php echo $compte_inactif['IP_COOKIE']; ?></th>
			<th><?php echo $compte_inactif['DATE_CONNEXION']; ?></th>
			<th>
				<a href="<?php echo $oCL_page->getPage('inscription', 'absolu')."?email=".$compte_inactif['EMAIL']."&cle_activation=".$compte_inactif['CLE_ACTIVATION']; ?>"><img src="images/ok.gif" alt="" title="" /></a>
			</th>
			<th><a href="<?php echo $oCL_page->getPage('bannir_membre'); ?>?id_personne=<?php echo $compte_inactif['ID_PERSONNE']; ?>"><img src="images/supprimer_personne_petit.png" alt="Bannir <?php echo $compte_inactif['PSEUDO']; ?>" title="Bannir <?php echo $compte_inactif['PSEUDO']; ?>" /></a></th>
		</tr>
		<tr><td colspan="6">Liste des IP du compte <?php echo $compte_inactif['PSEUDO']; ?>: <span class="valide">(IP simple)</span></td></tr>
		<?php
		$infos_ID_IP = fx_recuperer_infos_by_ID_IP($compte_inactif['ID_IP']);
		$infos_IP_COOKIE = fx_recuperer_infos_by_IP_COOKIE($compte_inactif['IP_COOKIE']);
		foreach($infos_ID_IP as $key2=>$info_ID_IP){
			if($info_ID_IP['ID_PERSONNE'] != $compte_inactif['ID_PERSONNE']){
		?>
				<tr <?php if($info_ID_IP['ID_IP'] != $info_ID_IP['IP_COOKIE']){echo "class='alert'";} ?>>
					<th><span title="ID_PERSONNE: N°<?php echo $info_ID_IP['ID_PERSONNE']; ?>"><?php echo $info_ID_IP['PSEUDO']; ?></span></th>
					<th><?php echo $info_ID_IP['ID_IP']; ?></th>
					<th><?php echo $info_ID_IP['IP_COOKIE']; ?></th>
					<th><?php echo $info_ID_IP['DATE_CONNEXION']; ?></th>
					<th><?php if($info_ID_IP['VISIBLE'] == true){echo "Activé";}else{echo "Inactif";} ?></th>
					<?php if($info_ID_IP['ID_IP'] != $info_ID_IP['IP_COOKIE']){echo "<th>/!\</th>";} ?>
				</tr>
		<?php
			}
		}# Fin du foreach infos_ID_IP
		?>
		<tr><td colspan="6">Liste des IP du compte <?php echo $compte_inactif['PSEUDO']; ?>: <span class="alert">(IP par cookie)</span></td></tr>
		<?php
		foreach($infos_IP_COOKIE as $key2=>$info_IP_COOKIE){
			if($info_IP_COOKIE['ID_PERSONNE'] != $compte_inactif['ID_PERSONNE']){
		?>
				<tr <?php if($info_IP_COOKIE['ID_IP'] != $info_IP_COOKIE['IP_COOKIE']){echo "class='alert'";} ?>>
					<th><span title="ID_PERSONNE: N°<?php echo $info_IP_COOKIE['ID_PERSONNE']; ?>"><?php echo $info_IP_COOKIE['PSEUDO']; ?></span></th>
					<th><?php echo $info_IP_COOKIE['ID_IP']; ?></th>
					<th><?php echo $info_IP_COOKIE['IP_COOKIE']; ?></th>
					<th><?php echo $info_IP_COOKIE['DATE_CONNEXION']; ?></th>
					<th><?php if($info_IP_COOKIE['VISIBLE'] == true){echo "Activé";}else{echo "Inactif";} ?></th>
					<?php if($info_IP_COOKIE['ID_IP'] != $info_IP_COOKIE['IP_COOKIE']){echo "<th>/!\</th>";} ?>
				</tr>
		<?php
			}
		}# Fin du foreach infos_IP_COOKIE
		if($compte_inactif['VALIDE'] == true){
			echo "</span>";
		}
	}# Fin du foreach
		?>
	</table>









<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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

?>
<h2>Administration:</h2>

Vous êtes dans l'interface d'administration de LiveAnim.<br />
Les menus à gauches vous proposent les diverses parties que vous pouvez administrer.<br />





<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>
	<h2>Créer un nouveau Pack:</h2><br />
	<br />
	<?php
		require_once('include_form_ajouter_pack.php');
	?>
	
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>	
	<h2><?php echo $annonce_courante[0]['TITRE'] ?></h2><br />
	<br />
	<?php
	if(isset($_SESSION['annonce']['message']) && $_SESSION['annonce']['message_affiche'] == false){
		echo $_SESSION['annonce']['message'];
		$_SESSION['annonce']['message_affiche'] = true;
	}
	?>
	<?php
	if($id_annonce_ok){# On affiche le contenu de l'annonce.
	
	?>

		<fieldset class="padding_LR"><legend class="legend_basique">Informations relative à l'annonce</legend><br />
			<br />
			<center>
				<span class="rose">Cette annonce a été créée le <b><?php echo $annonce_courante[0]['DATE_ANNONCE']; ?></b>.</span><br />
				<br />
				<span class="rose"><u>Évènement:</u></span> <b><?php echo $annonce_courante[0]['TYPE_ANNONCE'] ?></b>.<br />
				<br />
				La représentation débute le <b><?php echo $annonce_courante[0]['DATE_DEBUT'] ?></b> et se termine le <b><?php echo $annonce_courante[0]['DATE_FIN'] ?></b>.<br />
				<br />
				<span class="rose">Le budget initial prévu est de <b><?php echo $annonce_courante[0]['BUDGET'] ?>€.</b></span><br />
				<?php
				if($annonce_courante[0]['NB_CONVIVES'] != 0){
				?>
					<span class="rose">La représentation se fera devant <b><?php echo $annonce_courante[0]['NB_CONVIVES'] ?> personnes.</b></span><br />
				<?php
				}else{
				?>
					<span class="petit">(Le nombre d'invité n'a pas été précisé)</span><br />
				<?php
				}
				?>
				<br />
			</center>
			<div class="justify">
				<span class="rose"><u>Description:</u></span><br />
				<?php echo $annonce_courante[0]['DESCRIPTION'] ?><br />
				<br />
				<span class="rose"><u>Artistes recherchés:</u></span><br />
				<?php echo $annonce_courante[0]['ARTISTES_RECHERCHES'] ?><br />
			</div>
			<br />
			<?php
			if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin" || $_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
			?>
				<center>
					Cette annonce vous intéresse ? Faites un <b>contrat</b> !<br />
					<a href="<?php echo $oCL_page->getPage('creer_contrat')."?id_annonce=".$annonce_courante[0]['ID_ANNONCE']; ?>">Faire un contrat</a><br />
					<br />
				</center>
			<?php
			}
			?>
		</fieldset>
		<br />
		<br />
		<fieldset class="padding_LR"><legend class="legend_basique">Informations relatives à l'organisateur:</legend><br />
		<br />
		<b>Organisateur: <u><a title="Cliquez pour accéder à la fiche personnelle de <?php echo $annonce_courante[0]['PSEUDO']; ?>." href="<?php echo $oCL_page->getPage('personne')."?id_personne=".$annonce_courante[0]['ID_PERSONNE']; ?>"><?php echo $annonce_courante[0]['PSEUDO']; ?></a></u></b><br />
		<br />
		
		</fieldset>
		<br />
		<br />
		<fieldset style="" class="padding_LR"><legend class="legend_basique">Informations supplémentaires:</legend><br />
		<br />
		<b><u>Trajet entre vous et le lieu de prestation:</u></b><br />
		<br />
		<input type="hidden" name="origin" id="origin" value="<?php echo $personne_courante[0]['ADRESSE'].", ".$personne_courante[0]['CP']." ".$personne_courante[0]['VILLE']; ?>" />
		<input type="hidden" name="destination" id="destination" value="<?php echo $annonce_courante[0]['CP']." ".$annonce_courante[0]['VILLE']; ?>" />
		<div id="map" style="position:relative;height:400px;width:100%;">
		
		</div>
		<br />
		<div id="map_infos">
			<br />
			<span class="rose">La carte officielle avec d'autres options.<span class="petit"> (impression, chemin le moins cher, etc...)</span>:</span><br />
			<center>
				<a href="http://maps.google.fr/maps?f=d&source=s_d&saddr=<?php echo $personne_courante[0]['ADRESSE']; ?>,+<?php echo $personne_courante[0]['VILLE']; ?>&daddr=<?php echo $annonce_courante[0]['VILLE']; ?>">Carte officielle !</a>
			</center>
			<br />
		</div>
		<div id="map_erreur">
		
		</div>
		<script type="text/javascript">
			initialiser_GMap();
			calculate();
			verifier_donnees();
		</script>
		</fieldset>
	
	<?php
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><h3>Annonces GOLD</h3>
<ul class="news-list">
	<li class="last">
		<img alt="" src="images/2page-img3.jpg" />
		<h6><a href="#">Recherche DJ sur Paris</a></h6>
		Bonjour, je recherche un DJ qualifié sur paris pour une soirée entre amis... <a href="#" class="link3">suite</a><br/><br />
	</li>
	<li class="last">
		<img alt="" src="images/2page-img3.jpg" />
		<h6><a href="#">Recherche DJ sur Paris</a></h6>
		Bonjour, je recherche un DJ qualifié sur paris pour une soirée entre amis... <a href="#" class="link3">suite</a><br/><br />
	</li>
	<li class="last">
		<img alt="" src="images/2page-img3.jpg" />
		<h6><a href="#">Recherche DJ sur Paris</a></h6>
		Bonjour, je recherche un DJ qualifié sur paris pour une soirée entre amis... <a href="#" class="link3">suite</a><br/><br />
	</li>
	<li class="last">
		<img alt="" src="images/2page-img3.jpg" />
		<h6><a href="#">Recherche DJ sur Paris</a></h6>
		Bonjour, je recherche un DJ qualifié sur paris pour une soirée entre amis... <a href="#" class="link3">suite</a><br/><br />
	</li>
	<li class="last">
		<img alt="" src="images/2page-img3.jpg" />
		<h6><a href="#">Recherche DJ sur Paris</a></h6>
		Bonjour, je recherche un DJ qualifié sur paris pour une soirée entre amis... <a href="#" class="link3">suite</a><br/><br />
	</li>
</ul><div class="section">
	<h2>LES ARTISTES PREMIUM</h2>
	  <!-- newsSlider begin -->
	 <div id="newsSlider">
		<div class="container">
		   <div class="slides">
			  <div class="slide">
			  
				<ul class="topics">
					<li>
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 1 398</h5> 
					   <p>CHANTEUR</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
					<li class="alt">
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 6 398</h5> 
					   <p>DJ</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
					<li>
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 5 398</h5> 
					   <p>MUSICIEN</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
					<li class="alt">
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 3 398</h5> 
					   <p>MAGICIEN</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
					<li>
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 3 398</h5> 
					   <p>PRESENTATEUR</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
					<li class="alt">
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 2 398</h5> 
					   <p>DANSEUR</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
				
				</ul>

			  </div>
			  <!-- Div 2 des prestataires, faut autogénérer des div, ça va être d'un comique ça encore... -->
			  <div class="slide">
				 <ul class="topics">
					<li>
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 21 398</h5> 
					   <p>CHANTEUR</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
					<li class="alt">
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 26 398</h5> 
					   <p>DJ</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
					<li>
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 25 398</h5> 
					   <p>MUSICIEN</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
					<li class="alt">
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 23 398</h5> 
					   <p>MAGICIEN</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
					<li>
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 23 398</h5> 
					   <p>PRESENTATEUR</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
					<li class="alt">
					   <a href="#"><img alt="" src="images/1page-img1.jpg" /></a>
					   <h5>Membre 22 398</h5> 
					   <p>DANSEUR</p>
					   <p><a href="#" class="link2">Voir son CV</a></p>
					   <span>Inscrit le XX/XX/XXXX</span>
					</li>
				 </ul>
			  </div>
		   </div>
		</div>
		<a href="#" class="previous"></a><a href="#" class="next"></a>
	 </div>
 </div><?php
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

	if(isset($_GET['id_personne'])){
		$ID_PERSONNE = (int)$_GET['id_personne'];
	}
?>
		<h2>Bannir un membre:</h2>
		<br />
		<br />
		Vous pouvez effectuer un bannissement temporaire ou définitif du membre sélectionné. <br />
		<span class='petit'>(Date de fin de bannissement proche de 2020 si définitif.)</span><br />
		<br />
		
		<fieldset><legend class="legend_basique">Formulaire de bannissement d'un membre.</legend>
			<form class="formulaire" action="script_bannissement_membre.php" method="post" id="form_bannissement" name="form_bannissement"><br />
				<?php
					if(isset($_SESSION['bannir_membre']['message']) && $_SESSION['bannir_membre']['message_affiche'] == false){
						echo $_SESSION['bannir_membre']['message'];
						$_SESSION['bannir_membre']['message_affiche'] = true;
					}
				?>
				
				<br />
				Choisissez le membre à modérer:<br />
				<select id="form_bannissement_id_personne" name="form_bannissement_id_personne">
					<?php
					var_dump($membres);
					foreach($membres as $key=>$membre){
					?>
						<option value="<?php echo $membre['ID_PERSONNE']; ?>" <?php if(isset($ID_PERSONNE) && $ID_PERSONNE == $membre['ID_PERSONNE']){echo "selected='selected'";} ?>><?php echo $membre['PSEUDO']; ?></option>
					<?php
					}
					?>
				</select>
				<br />
				<br />
				
				<label for="form_bannissement_personne_supprimee">Ban définitif:&nbsp;</label><input type="checkbox" name="form_bannissement_personne_supprimee" id="form_bannissement_personne_supprimee" />
				<br />
				<br />
				
				<label for="form_bannissement_duree">Durée: <span class="petit">(En jours)</span></label><br />
				<input type="text" name="form_bannissement_duree" id="form_bannissement_duree" size="5" /><span class="petit">&nbsp;(Inutile si ban définitif)</span>
				<br />
				<br />
				
				
				
				<label for="form_bannissement_raison">Raison du bannissement:</label><br />
				<textarea id="form_bannissement_raison" name="form_bannissement_raison" cols="80" rows="8">Vous avez été banni par notre service de modération pour la raison suivante: </textarea><br />
				<br />
				
				<center>
					<a><img src="images/previsualiser.jpg" alt="Prévisualiser" onclick="fx_previsualiser('form_bannissement_raison', 'preview');" /></a>
				</center>
				<br />
					<p id="preview">
					
					</p>
				<center>
					<input type="image" id="btn_form_bannissement_valider" name="btn_form_bannissement_valider" src="images/valider2.png" />
				</center>
			</form>
		</fieldset>
		

<?php	
}
?><?php
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
?>
	<h2>Changer le rang:</h2><br />
	<br />
	<?php
		if(isset($_SESSION['administration']['message_affiche']) && $_SESSION['administration']['message_affiche'] == false){
			echo $_SESSION['administration']['message'];
			$_SESSION['administration']['message_affiche'] = true;
		}
	?>
	<br />
	<fieldset><legend class="legend_basique">Formulaire de modification du rang:</legend><br />
		<br />
		<form class="formulaire" action="script_changer_rang.php" method="post" id="" name="">
			Login/Pseudo du membre à modifier:<br />
			<input type="text" class="my_input" name="form_changer_rang_pseudo" id="form_changer_rang_pseudo" /><br />
			<br />
			
			Sélectionnez son nouveau rang:<br />
			<select class="my_input" name="form_changer_rang_type_personne" id="form_inscription_type_personne">
				<option value="Prestataire" <?php if(isset($_SESSION['changer_rang']['type_personne'])){ if($_SESSION['changer_rang']['type_personne'] == "Prestataire"){echo "selected='selected'";}} ?>>Prestataire / Artiste</option>
				<option value="Organisateur" <?php if(isset($_SESSION['changer_rang']['type_personne'])){ if($_SESSION['changer_rang']['type_personne'] == "Organisateur"){echo "selected='selected'";}} ?>>Organisateur de soirée</option>
				<option value="Admin" <?php if(isset($_SESSION['changer_rang']['type_personne'])){ if($_SESSION['changer_rang']['type_personne'] == "Admin"){echo "selected='selected'";}} ?>>Admin</option>
			</select><br />
			<br />
			<center>
				<input type="image" src="images/valider.png" id="btn_form_changer_rang_valider" name="btn_form_changer_rang_valider" />
			</center>
		</form>
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
?>
	<h2>Liste des comptes supprimés par les utilisateurs:</h2><br />
	<br />
	Tous les comptes suivants ont été supprimés par leur propriétaire - ou un tiers autre que notre administration -, connaître la raison de la suppression peut vous aider dans vos relations clients.<br />
	Vous pouvez contacter les membres en questions si vous pensez que la raison qu'ils ont donnée n'est pas valable dans le but de les faire changer d'avis.<br />
	Notez que les comptes ne sont supprimés que deux mois après leur date de suppression, ils peuvent donc récupérer toutes leurs informations sans perte de données.<br />
	<br />

	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr class="valide">
			<th width="20%" scope="col">Pseudo:</th>
			<th width="20%" scope="col">Infos:</th>
			<th width="20%" scope="col">Contact:</th>
			<th width="40%" scope="col">Raison:</th>
		</tr>
		<tr><th colspan="4"><hr /></th></tr>
		<?php
		while($compte_supprime = $comptes_supprimes->fetch(PDO::FETCH_ASSOC)){
		?>
			<tr>
				<th><span title="<?php echo "ID N°".$compte_supprime['ID_PERSONNE']; ?>"><a href="<?php echo $oCL_page->getPage('modifier_fiche_membre')."?id_personne=".$compte_supprime['ID_PERSONNE']; ?>"><?php echo $compte_supprime['PSEUDO']; ?></a></span></th>
				<th><?php echo $compte_supprime['CIVILITE']." ".$compte_supprime['NOM']." ".$compte_supprime['NOM']."<br />".$compte_supprime['EMAIL']; ?></th>
				<th><?php echo $compte_supprime['TEL_FIXE']."<br />".$compte_supprime['TEL_PORTABLE'] ?></th>
				<th><?php echo $compte_supprime['RAISON_SUPPRESSION']; ?></th>
			</tr>
			<tr><th colspan="4"><hr /></th></tr>
		<?php
		}
		?>
	</table>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><div class="adv-zzz">
	<?php
	if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin" || $_SESSION['compte']['PSEUDO'] == "Vadorequest"){
		echo "<h4><a href='".$oCL_page->getPage('administration')."'>Administration</a></h4>";	
	}
	if($_SESSION['compte']['connecté'] == true){
	?>
		
		Salut <?php echo $_SESSION['compte']['PSEUDO']; ?><br />
		<br />
		<h4><form action="<?php echo $oCL_page->getPage("gestion_compte"); ?>" method="post"><input type="image" src="images/mon_compte.gif" alt="Accéder à mon compte" title="Accéder à mon compte" /></form></h4>
		
		<a class="fright" href="<?php echo $oCL_page->getPage("deconnexion.php"); ?>" >Déconnexion</a>
	<?php
		}else if($_SESSION['compte']['connecté'] == false){
	?>
	<form action="script_connexion.php" method="post" id="form_connexion" name="form_connexion">
		Vous êtes un artiste ou un organisateur ? <br />Nous faisons tout à moindre coût&nbsp;!<br /><br />
	    <table border="0" cellspacing="1" cellpadding="3">
	    <tr>
			<td align="left" width="45%">Pseudo :</td>
			<td>
				<input type="text" name="form_connexion_pseudo" id="form_connexion_pseudo" size="15" maxlength="20" />
			</td>
	    </tr>
	    <tr>
			<td align="left">Mot de passe :</td>
			<td>
				<input type="password" name="form_connexion_mdp" id="form_connexion_mdp" size="15" />
			</td>
	    </tr>
	    <tr>
			<td>&nbsp;</td>
			<td align="right"><span class='petit'><a href="<?php echo $oCL_page->getPage('recuperation_mdp'); ?>">Récupération de mot de passe</a></span></td>
		</tr>
	   <tr align="center">
			<td colspan="2">
				<input type="image" src="images/valider.png" id="btn_form_connexion_valider" name="btn_form_connexion_valider" value="Envoyer" />
		    </td>
	    </tr>
	    </table>
	<?php
		}
	?>
	</form>
</div>
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
?>
	<h2>Créer une nouvelle annonce:</h2><br />
	<br />
	<span class="rose">Vous souhaitez créer une annonce car vous recherchez des artistes pour une soirée ?<br />
	Vous êtes sur la bonne page !<br /></span>
	<br />
	<span class="orange petit">L'équipe de LiveAnim vous rapelle qu'il est interdit de mentionner -de quelque manière que ce soit- vos coordonnées.<br />
	Tout ce qui doit être affiché le sera automatiquement. N'oubliez pas que chaque annonce devra être validée par l'administration avant d'être visible.</span><br />
	<br />
	
	<?php
		require_once("include_form_ajouter_modifier_annonce.php");
	?>

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>
	<h2>Créer un contrat pour l'annonce <a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce[0]['ID_ANNONCE']; ?>"><?php echo $annonce[0]['TITRE']; ?></a></h2><br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Création d'un contrat</legend>
		Vous vous apprêtez à créer un contrat concernant l'annonce N°<?php echo $annonce[0]['ID_ANNONCE']; ?>.<br />
		&nbsp;&nbsp;&nbsp;<img id="img_plus_moins" onclick="fx_affiche('informations_contrat', 'img_plus_moins');" src="<?php echo $oCL_page->getImage('plus'); ?>" alt="Afficher/Cacher l'aide" title="Afficher/Cacher l'aide" /><br />
		
		<div class="justify" id="informations_contrat"><br />
			<br />
			Notre système d'édition de contrat est simple.<br />
			<br />
			Vous créez un contrat concernant une annonce qui vous intéresse et pour laquelle vous proposez vos services.<br />
			<br />
			Vous pouvez modifier certains champs, notamment les dates ainsi que votre rémunération.<br />
			<span class="petit">(Prenez bien en compte que dans le cas de grosses festivités, le budget indiqué peut être le budget total prévu pour tous las artistes demandés !)</span>
			<br />
			L'organisateur est aussitôt prévenu par e-mail, le contrat lui est envoyé par la messagerie du site.<br />
			<br />
			Il peut alors accepter, refuser ou annuler le contrat. <br />
			L'acceptation peut se faire par les deux personnes mais uniquement si les valeurs (dates, rémunération) fournies restent inchangées.<br />
			L'annulation d'un contrat est définitif. <br />
			Le refus passe le statut du contrat en attente de l'avis de l'autre personne (Validation, refus ou annulation).<br />
			<br />
			Une fois un contrat validé, il est toujours possible de l'annuler mais plus d'en modifier les clauses.<br />
			<br />
			Si vous avez une indisponibilité quelconque qui fait que vous ne pourrez pas respecter cet accord veuillez l'annuler le plus rapidement possible afin que l'autre partie puisse prendre ces dispositions.<br />
			<br />
			<p class="orange">
				Si des frais ont été engagés avant la prise de connaissance de l'annulation alors les deux parties doivent s'arranger entre elles.<br />
				Nous mettons à votre disposition tous les contrats afin que si litige il y a entre les deux parties vous possédiez une pièce justificative.<br />
			</p>
		</div>
		<?php
		require_once('include_form_ajouter_modifier_contrat.php');
		?>
	</fieldset>
	<script type="text/javascript">
		initialiser_contrat("informations_contrat");
	</script>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_annonce.php');
require_once('couche_metier/PCS_contrat.php');

$oMSG = new MSG();
$oPCS_annonce = new PCS_annonce();
$oPCS_contrat = new PCS_contrat();

$oMSG->setData('VISIBLE', 1);
$oMSG->setData('STATUT', 'Validée');
$oMSG->setData('criteres', 'AND annonce.DATE_DEBUT > NOW()');
$oMSG->setData('nb_result_affiches', 10);
$oMSG->setData('debut_affichage', 0);

$annonces_ = $oPCS_annonce->fx_recuperer_annonces_par_criteres($oMSG)->getData(1)->fetchAll();
// Je l'appelle $annonces_ car sinon cela crée des interférences avec les autres scripts.
?>
<h3>Derni&Egrave;res annonces</h3>
<ul class="list1">
	<?php
	if(isset($_SESSION['compte']) && $_SESSION['compte']['connecté'] == true){
		foreach($annonces_ as $key=>$annonce_){
		?>
			<li><a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce_['ID_ANNONCE']; ?>"><?php echo $annonce_['TITRE']; ?></a></li>
		<?php
		}
	}else{
	?>
		<span class="orange">Vous devez être connecté pour voir les annonces détaillées.</span><br />
		<br />
	<?php
	}
	?>
</ul>
<a href="<?php echo $oCL_page->getPage('liste_annonce'); ?>">Voir toutes les annonces ></a><br />
<br />
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
?>
	<h2>Liste de mes filleuls:</h2><br />
	<br />
	Voici la liste de tous vos filleuls.<br />
	<?php
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
	?>
		Selon votre pack vous verrez apparaître plus ou moins d'informations, notamment le pourcentage total que chaque filleul vous a fait gagner et le pourcentage global.<br />
		<span class="petit">(Non effectué pour le moment.)</span><br />
	<?php
	}
	?>
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Liste de mes filleuls:</legend>
		<table width="100%" border="0" cellspacing="1" cellpadding="1"><br />
			<tr class="formulaire">
				<th width="30" scope="col">Pseudo:</th>
				<th width="40"scope="col">Identité:</th>
				<th width="30"scope="col">Statut:</th>
			</tr>
			<tr><th colspan="3"><hr /></th></tr>
			<?php
			foreach($filleuls as $key=>$filleul){
			?>
			<tr height="50px">
				<th class="rose"><?php echo $filleul['PSEUDO']; ?></th>
				<th><?php echo $filleul['CIVILITE']." ".$filleul['NOM']." ".$filleul['PRENOM']; ?></th>
				<th class="valide"><?php echo $filleul['TYPE_PERSONNE']; ?></th>
			</tr>
			<tr><th colspan="3"><hr /></th></tr>
			<?php
			}
			?>
		</table><br />
		<br />
	</fieldset>


<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>	<div id="footer">
		<br />
		<div class="fleft">LiveAnim.com &copy; 2011 | Site crée par <a title="Alias Vadorequest. (Développeur)" href="http://vadorequest.ovh.org">Ambroise Dhenain</a> avec l'aide de <a title="Alias Tyrion. (Graphiste/Designer)" href="#footer">Timothée Dhenain</a> | <a href="#">Mentions légales</a></div>
		<ul class="fright">
			<li>Suivez nous :
				<a href="#"><img alt="" src="images/icon1.gif" /></a>
				<a href="#"><img alt="" src="images/icon2.gif" /></a>
				<a href="#"><img alt="" src="images/icon3.gif" /></a>
				<a href="#"><img alt="" src="images/icon4.gif" /></a>
				<a href="#"><img alt="" src="images/icon5.gif" /></a>
				<a href="#"><img alt="" src="images/icon6.gif" /></a>
			</li>
		</ul>
	</div>
<?php
# On affiche le tout
ob_end_flush();
?>
<?php
/*
	Formulaire d'ajout d'une annonce pour Organisateur.	
*/
if(isset($_SESSION['ajouter_annonce']['message']) && $_SESSION['ajouter_annonce']['message_affiche'] == false){
	echo $_SESSION['ajouter_annonce']['message'];
	$_SESSION['ajouter_annonce']['message_affiche'] = true;
}else if(isset($_SESSION['modifier_annonce']['message']) && $_SESSION['modifier_annonce']['message_affiche'] == false){
	echo $_SESSION['modifier_annonce']['message'];
	$_SESSION['modifier_annonce']['message_affiche'] = true;
}
/*
	Nous utilisons ['modifier_annonce'] que ce soit un admin ou non qui utilise la fiche.
	Ce formulaire regroupe la création, la modification par utilisateur et la modification par admin d'une annonce.
*/
?>
<br />
	<fieldset><legend class="legend_basique"><?php if(isset($annonce)){echo "Modification d'une annonce";}else{echo "Création d'une annonce";} ?></legend><br />
		<br />
		<form action="<?php if(isset($annonce) && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){echo "script_form_modifier_annonce_by_admin.php";}else if(isset($annonce)){echo "script_form_modifier_annonce.php";}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur"){echo "script_form_ajouter_annonce.php";} ?>" method="post" name="form_ajout_modification_annonce" id="form_ajout_modification_annonce" class="formulaire">
			<input type="hidden" name="form_ajout_modification_annonce_id_annonce" value="<?php echo $annonce[0]['ID_ANNONCE']; ?>" />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_titre">Titre de l'annonce:</label><br />
			<input type="text" name="form_ajout_modification_annonce_titre" id="form_ajout_modification_annonce_titre" size="40" value="<?php if(isset($annonce)){echo $annonce[0]['TITRE'];}else if(isset($_SESSION['ajouter_annonce']['TITRE'])){echo $_SESSION['ajouter_annonce']['TITRE'];} ?>" /><br />
			<br />
			<?php
			if(isset($annonce)){
			?>
				Date de création de l'annonce: <span class='noir'><?php echo $annonce[0]['DATE_ANNONCE']; ?>.</span><br />
				<br />
			<?php
			}
			?>
			<span class="alert">*</span><label for="form_ajout_modification_annonce_type_annonce">Type d'annonce:</label><br />
			<select name="form_ajout_modification_annonce_type_annonce" id="form_ajout_modification_annonce_type_annonce">
				<?php
				foreach($types_annonce as $key=>$type_annonce){
				?>
					<option value="<?php echo $type_annonce['ID_TYPES']; ?>" <?php if(isset($annonce[0]['TYPE_ANNONCE']) && $annonce[0]['TYPE_ANNONCE'] == $type_annonce['ID_TYPES']){echo "selected='selected'";}else if(isset($_SESSION['ajouter_annonce']['TYPE_ANNONCE']) && $_SESSION['ajouter_annonce']['TYPE_ANNONCE'] == $type_annonce['ID_TYPES']){echo "selected='selected'";} ?>><?php echo $type_annonce['ID_TYPES']; ?></option>
				<?php
				}
				?>
			</select><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_date_debut">Date de début: <span class="petit" title="Date au format jour/mois/année.">(Ex: 06/05/2011 20h56)</span></label><br />
			<input type="text" name="form_ajout_modification_annonce_date_debut" id="form_ajout_modification_annonce_date_debut" size="40" value="<?php if(isset($annonce)){echo $annonce[0]['DATE_DEBUT'];}else if(isset($_SESSION['ajouter_annonce']['DATE_DEBUT'])){echo $_SESSION['ajouter_annonce']['DATE_DEBUT'];} ?>" /><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_date_fin">Date de fin: <span class="petit" title="Date au format jour/mois/année.">(Ex: 06/05/2011 20h56)</span></label><br />
			<input type="text" name="form_ajout_modification_annonce_date_fin" id="form_ajout_modification_annonce_date_fin" size="40" value="<?php if(isset($annonce)){echo $annonce[0]['DATE_FIN'];}else if(isset($_SESSION['ajouter_annonce']['DATE_FIN'])){echo $_SESSION['ajouter_annonce']['DATE_FIN'];} ?>" /><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_artistes_recherches">Description des artistes recherchés:</label><br />
			<textarea cols="80" rows="5" name="form_ajout_modification_annonce_artistes_recherches" id="form_ajout_modification_annonce_artistes_recherches"><?php if(isset($annonce)){echo $annonce[0]['ARTISTES_RECHERCHES'];}else if(isset($_SESSION['ajouter_annonce']['ARTISTES_RECHERCHES'])){echo $_SESSION['ajouter_annonce']['ARTISTES_RECHERCHES'];}else{echo "Listez les artistes dont vous avez besoin par ordre de priorité. N'hésitez pas à détailler.";} ?></textarea><br />
			<br />
			<label for="form_ajout_modification_annonce_budget">Budget prévu: <span class="petit" title="Tous prix exprimés sur le site le sont exclusivement en Euros (€).">(€)</span></label><br />
			<input type="text" name="form_ajout_modification_annonce_budget" id="form_ajout_modification_annonce_budget" size="10" value="<?php if(isset($annonce)){echo $annonce[0]['BUDGET'];}else if(isset($_SESSION['ajouter_annonce']['BUDGET'])){echo $_SESSION['ajouter_annonce']['BUDGET'];} ?>" /><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_nb_convives">Nombre d'invités: <span class="petit" title="Si vous ignorez le nombre d'invités, essayez d'estimer une fourchette. Si vous n'en savez rien, mettez 0.">(Mettez 0 si inconnu)</span></label><br />
			<input type="text" name="form_ajout_modification_annonce_nb_convives" id="form_ajout_modification_annonce_nb_convives" size="5" value="<?php if(isset($annonce)){echo $annonce[0]['NB_CONVIVES'];}else if(isset($_SESSION['ajouter_annonce']['NB_CONVIVES'])){echo $_SESSION['ajouter_annonce']['NB_CONVIVES'];} ?>" /><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_description" title="Décrivez votre annonce de facon à ce qu'elle intéresse les artistes qui la liront ! Plus une annonce est intéressante et plus elle a de chance d'attirer du monde.">Description de l'annonce:</label><br />
			<textarea cols="80" rows="5" name="form_ajout_modification_annonce_description" id="form_ajout_modification_annonce_description"><?php if(isset($annonce)){echo $annonce[0]['DESCRIPTION'];}else if(isset($_SESSION['ajouter_annonce']['DESCRIPTION'])){echo $_SESSION['ajouter_annonce']['DESCRIPTION'];}else{echo "Expliquez tout ce qui pourrait motiver les artistes qui liront cette annonce !";} ?></textarea><br />
			<br />			
			<span class="alert">*</span><label for="form_ajout_modification_annonce_id_departement">Département :</label><br />
			<select name="form_ajout_modification_annonce_id_departement" id="form_ajout_modification_annonce_id_departement">
				<?php
				foreach($departements as $key=>$departement){
				# à finir 
				?>
					<option value="<?php echo $departement['ID_DEPARTEMENT']; ?>" <?php if(isset($annonce) && $annonce[0]['ID_DEPARTEMENT'] == $departement['ID_DEPARTEMENT']){echo "selected='selected'";}else if(isset($_SESSION['ajouter_annonce']['ID_DEPARTEMENT']) && $_SESSION['ajouter_annonce']['ID_DEPARTEMENT'] == $departement['ID_DEPARTEMENT']){echo "selected='selected'";} ?>><?php echo $departement['ID_DEPARTEMENT'].") ".$departement['NOM']; ?></option>
				<?php
				}
				?>
			</select><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_adresse">Adresse:</label><br />
			<input type="text" name="form_ajout_modification_annonce_adresse" id="form_ajout_modification_annonce_adresse" size="40" value="<?php if(isset($annonce)){echo $annonce[0]['ADRESSE'];}else if(isset($_SESSION['ajouter_annonce']['ADRESSE'])){echo $_SESSION['ajouter_annonce']['ADRESSE'];} ?>" /><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_cp">Code postal:</label><br />
			<input type="text" name="form_ajout_modification_annonce_cp" id="form_ajout_modification_annonce_cp" size="5" value="<?php if(isset($annonce)){echo $annonce[0]['CP'];}else if(isset($_SESSION['ajouter_annonce']['CP'])){echo $_SESSION['ajouter_annonce']['CP'];} ?>" /><br />
			<br />
			<span class="alert">*</span><label for="form_ajout_modification_annonce_ville">Ville:</label><br />
			<input type="text" name="form_ajout_modification_annonce_ville" id="form_ajout_modification_annonce_ville" size="40" value="<?php if(isset($annonce)){echo $annonce[0]['VILLE'];}else if(isset($_SESSION['ajouter_annonce']['VILLE'])){echo $_SESSION['ajouter_annonce']['VILLE'];} ?>" /><br />
			<br />
			<br />
			<span class="fright alert">* Champ obligatoire&nbsp;</span><br />
			<br />
			<center>
				<input type="image" src="images/previsualiser.jpg" alt="Prévisualiser votre annonce." name="Prévisualiser votre annonce." /><br />
				<br />
				<br />
				<?php
				if(isset($annonce) && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				?>
				Sélectionnez le nouveau statut de l'annonce:<br />
				<select name="form_ajout_modification_annonce_statut" id="form_ajout_modification_annonce_statut" onchange="fx_afficher('p_raison_refus', 'form_ajout_modification_annonce_statut', 'form_ajout_modification_annonce_refus');" >
					<?php
					foreach($statuts as $key=>$statut){
					?>
					<option value="<?php echo $statut['ID_TYPES']; ?>"  <?php if($statut['ID_TYPES'] == "Validée"){echo "selected='selected'";} ?>><?php echo $statut['ID_TYPES']; ?></option>
					<?php
					}# Fin du foreach d'affichage des statuts.
					?>
				</select>
				<br />
				<p id="p_raison_refus">
					Expliquez rapidement la raison de votre refus:<br />
					<textarea cols="80" rows="5" name="form_ajout_modification_annonce_refus" id="form_ajout_modification_annonce_refus"></textarea>
				</p>
				<?php
				}# Fin du if du type_personne.
				?>
				<input type="image" src="images/valider.png" alt="Créer l'annonce." name="Créer l'annonce." /><br />
			</center>
		</form>
	</fieldset>
	<script type="text/javascript">
		fx_cacher('p_raison_refus', 'form_ajout_modification_annonce_refus');
	</script>
<?php
if(isset($_SESSION['creer_contrat']['message']) && $_SESSION['creer_contrat']['message_affiche'] == false){
	echo $_SESSION['creer_contrat']['message'];
	$_SESSION['creer_contrat']['message_affiche'] = true;
}

?>
<form style="position:relative;" class="formulaire" id="form_ajout_modification_contrat" action="<?php if($formulaire == "creer"){echo "script_form_ajouter_contrat.php";}else if($formulaire == "modifier"){echo "script_form_modifier_contrat.php";} ?>" method="post">
	<input type="hidden" name="form_ajout_modification_contrat_id_annonce" id="form_ajout_modification_contrat_id_annonce" value="<?php echo $annonce[0]['ID_ANNONCE']; ?>" />
	<br />
	<b><u><center><?php echo $annonce[0]['TITRE']; ?></center></b></u><br />
	<center><span class="noir">(<a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce[0]['ID_ANNONCE'];	?>"><?php echo $annonce[0]['TYPE_ANNONCE'] ?></a>)</span></center><br />
	<br />
	<label for="form_ajout_modification_contrat_date_debut">Date de début de l'évènement:</label><br />
	<input type="text" name="form_ajout_modification_contrat_date_debut" id="form_ajout_modification_contrat_date_debut" value="<?php if(isset($_SESSION['creer_contrat']['DATE_DEBUT'])){echo $_SESSION['creer_contrat']['DATE_DEBUT'];}else if($formulaire == "modifier"){}else{echo $annonce[0]['DATE_DEBUT'];} ?>" /><br />
	<br />
	<label for="form_ajout_modification_contrat_date_fin">Date de fin de l'évènement:</label><br />
	<input type="text" name="form_ajout_modification_contrat_date_fin" id="form_ajout_modification_contrat_date_fin" value="<?php if(isset($_SESSION['creer_contrat']['DATE_FIN'])){echo $_SESSION['creer_contrat']['DATE_FIN'];}else if($formulaire == "modifier"){}else{echo $annonce[0]['DATE_FIN'];} ?>" /><br />
	<br />
	<label for="form_ajout_modification_contrat_prix">Rémunération totale (HT):</label><br />
	<input type="text" name="form_ajout_modification_contrat_prix" id="form_ajout_modification_contrat_prix" value="<?php if(isset($_SESSION['creer_contrat']['BUDGET'])){echo $_SESSION['creer_contrat']['BUDGET'];}else if($formulaire == "modifier"){}else{echo $annonce[0]['BUDGET'];} ?>" size="4" />€<br />
	<br />
	<label for="form_ajout_modification_contrat_description">Description du contrat:</label><br />
	<textarea rows="18" cols="85" id="form_ajout_modification_contrat_description" name="form_ajout_modification_contrat_description"><?php if(isset($_SESSION['creer_contrat']['DESCRIPTION'])){echo $_SESSION['creer_contrat']['DESCRIPTION'];}else if($formulaire == "modifier"){}else{ echo"Expliquez toutes les modifications que vous souhaitez comparé à l'annonce originale ainsi que tous les éléments que vous souhaitez donner à l'organisateur.";} ?></textarea><br />
	<br />
	<center>
		<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Créer le contrat" title="Créer le contrat" />
	</center>
</form><?php
	if(isset($_SESSION['ajouter_pack']['message']) && $_SESSION['ajouter_pack']['message_affiche'] == false){
		echo $_SESSION['ajouter_pack']['message'];
		$_SESSION['ajouter_pack']['message_affiche'] = true;
	}
?>
<form class="formulaire" action="script_form_ajouter_pack.php" method="post" name="form_pack" id="form_pack">
	<br />Nom du pack:<br />
	<input type="text" value="Live " name="form_pack_nom" id="form_pack_nom" /><br />
	<br />
	Description du pack:<br />
	<textarea name="form_pack_description" id="form_pack_description" cols="80" rows="5"></textarea><br />
	<br />
	Type de pack:<br />
	<select id="form_pack_type_pack" name="form_pack_type_pack">
		<?php
		foreach($types_packs as $key=>$type_pack){
		?>
			<option value="<?php echo $type_pack['ID_TYPES']; ?>" <?php if($type_pack['ID_TYPES'] == "Basique"){echo "selected='selected'";} ?>><?php echo $type_pack['ID_TYPES']; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Prix de base:<br />
	<input type="text"  name="form_pack_prix_base" id="form_pack_prix_base" /><br />
	<br />
	Durée:<br />
	<select  name="form_pack_duree" id="form_pack_duree" />
		<?php
		for($i = 1;$i<13; $i++){
		?>
			<option value="<?php echo $i; ?>"><?php echo $i; ?> mois</option>
		<?php
		}
		?>
	</select><br />
	<br />
	Est-ce que ce pack bénéficie des réductions dûes au parrainage ?<br />
	<select  name="form_pack_soumis_reduction_parrainage" id="form_pack_soumis_reduction_parrainage" />
		<option value="1" selected="selected">Oui</option>
		<option value="0">Non</option>
	</select><br />
	<br />
	Quel est le maximum de réduction auquel est soumis ce pack ?<br />
	<input type="text"  name="form_pack_gain_parrainage_max" id="form_pack_gain_parrainage_max" size="4" />&nbsp;<span class='petit'>(En %)</span><br />
	<br />
	Si ce pack est acheté par un filleul, combien de réduction apporte-t-il à son parrain ?<br />
	<input type="text"  name="form_pack_reduction" id="form_pack_reduction" size="4" />&nbsp;<span class='petit'>(En %)</span><br />
	<br />
	Activer le pack dès maintenant ?<br />
	<select  name="form_pack_visible" id="form_pack_visible" />
		<option value="1" selected="selected">Activer</option>
		<option value="0">Désactiver</option>
	</select><br />
	<br />
	<hr />
	<br />
	<h5>Options du pack:</h5><br />
	<br />
	<span title="Plus ce nombre est élevé et plus le C.V a de chance d'apparaître en haut des listings. (On parle donc des C.V des Prestataires)"><u>Niveau de visibilité du C.V du prestataire:</u></span><br />
	<select  name="form_pack_cv_visibilite" id="form_pack_cv_visibilite" />
		<?php
		for($i = 0;$i<11; $i++){
		?>
			<option value="<?php echo $i; ?>">Rang <?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	<span title="Plus ce nombre est élevé et plus les C.V vus afficheront d'informations. (On parle donc des C.V des Organisateurs)"><u>Niveau d'accessibilité des C.V des organisateurs:</u></span><br />
	<select  name="form_pack_cv_accessible" id="form_pack_cv_accessible" />
		<?php
		for($i = 0;$i<11; $i++){
		?>
			<option value="<?php echo $i; ?>">Rang <?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Nombre d'annonces consultables par mois:<br />
	<input type="text"  name="form_pack_nb_fiches_visitables" id="form_pack_nb_fiches_visitables" size="5" /><br />
	<br />
	Permet de faire un C.V vidéo:<br />
	<select  name="form_pack_cv_video_accessible" id="form_pack_cv_video_accessible" />
		<option value="0">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	Envoi de MP en cas de désistement d'un autre prestataire dans les départements désirés:<br />
	<select  name="form_pack_alerte_non_disponibilite" id="form_pack_alerte_non_disponibilite" />
		<option value="0">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	Nombre maximal de départements pour lesquels le prestataire sera prévenu en cas de désistement:<br /><span class="petit">(Va avec l'option précédente.)</span><br />
	<select  name="form_pack_nb_departements_alerte" id="form_pack_nb_departements_alerte" />
		<?php
		for($i = 0;$i<11; $i++){
		?>
			<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Possibilité de parrainer:<br />
	<select  name="form_pack_parrainage_active" id="form_pack_parrainage_active" />
		<option value="0">Non</option>
		<option value="1" selected='selected'>Oui</option>
	</select><br />
	<br />
	Possibilité de prévisualiser les fiches:<br />
	<select  name="form_pack_previsualisation_fiches" id="form_pack_previsualisation_fiches" />
		<option value="0">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	Récupération des contrats sous format .pdf:<br />
	<select  name="form_pack_contrats_pdf" id="form_pack_contrats_pdf" />
		<option value="0">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	<span title="Indique si le prestataire bénéficie d'un suivi de ses dépenses/gains via des statistiques détaillées."><u>Suivi du prestataire:</u></span><br />
	<select  name="form_pack_suivi" id="form_pack_suivi" />
		<option value="0">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	Activer les pubs ?<br />
	<select  name="form_pack_pubs" id="form_pack_pubs" />
		<option value="0" selected="selected">Non</option>
		<option value="1">Oui</option>
	</select><br />
	<br />
	<br />
	<span class="fright alert petit">N.B: Tous les champs sont obligatoires.</span><br />
	<br />
	<center>
		<input type="image" src="images/valider.png" alt="Valider" name="btn_form_pack_valider" id="btn_form_pack_valider" />
	</center>
	</form><?php
# Il faut envoyer les bonnes données au script.
# Le plus important et qu'il ne faut pas qu'un membre puisse modifier le profil d'un autre, pas de $_GET donc.
# Gérer le préchargement des informations via l'ID_PERSONNE dans la session de cette manière on est tranquille.

if(isset($_SESSION['modification_fiche_membre']['message']) && $_SESSION['modification_fiche_membre']['message_affiche'] == false){
	echo "<center>".$_SESSION['modification_fiche_membre']['message']."</center><br /><br />";
	$_SESSION['modification_fiche_membre']['message_affiche'] = true;
}
?>
<form class="formulaire" action="script_form_modifier_fiche_membre.php" method="post" name="form_fiche_membre" id="form_fiche_membre" enctype="multipart/form-data">
					<center><h5><?php echo $personne[0]['PSEUDO']; ?>, <?php echo $personne[0]['TYPE_PERSONNE']; ?>.</h5><br /></center>
					<?php
					if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
					?>
					<center><span class='petit'>(ID N°<?php echo $personne[0]['ID_PERSONNE']; ?>)</span></center><br />
					<br />
					<?php
					}
					?>
					<hr />
					<input type="hidden" name="form_fiche_membre_id_personne" id="form_fiche_membre_id_personne" value="<?php echo $personne[0]['ID_PERSONNE']; ?>" />
					<input type="hidden" name="form_fiche_membre_pseudo" id="form_fiche_membre_pseudo" value="<?php echo $personne[0]['PSEUDO']; ?>" />
					<br />
					<h6>Informations personnelles:</h6><br />
					<br />
					<?php 
						if(!empty($personne[0]['URL_PHOTO_PRINCIPALE'])){
						?>
							<span class="fright">	
								<img src="<?php echo $personne[0]['URL_PHOTO_PRINCIPALE']; ?>" title="<?php echo $personne[0]['PSEUDO']; ?>" alt="<?php echo $personne[0]['PSEUDO']; ?>" width="93" height="117" />
							</span>
						<?php
						} 
					?>
					<span class="alert">*</span><label for="form_fiche_membre_nom">Nom: </label><br /><input type="text" name="form_fiche_membre_nom" id="form_fiche_membre_nom" value="<?php echo $personne[0]['NOM']; ?>" size="60" /><br />
					<br />
					<span class="alert">*</span><label for="form_fiche_membre_prenom">Prénom: </label><br /><input type="text" name="form_fiche_membre_prenom" id="form_fiche_membre_prenom" value="<?php echo $personne[0]['PRENOM']; ?>" size="60" /><br />
					<br />
					<span class="alert">*</span>Civilité: 	<br /><select name="form_fiche_membre_civilite" id="form_fiche_membre_civilite">
									<option value="Mr" <?php if($personne[0]['CIVILITE'] == "Mr"){echo "selected='selected'";} ?>>Monsieur</option>
									<option value="Mme" <?php if($personne[0]['CIVILITE'] == "Mme"){echo "selected='selected'";} ?>>Madame</option>
									<option value="Mlle" <?php if($personne[0]['CIVILITE'] == "Mlle"){echo "selected='selected'";} ?>>Mademoiselle</option>
								</select><br />
					<br />
					<span class="alert">*</span><label for="form_fiche_membre_date_naissance">Né(e) le: </label><br /><input type="text" name="form_fiche_membre_date_naissance" id="form_fiche_membre_date_naissance" value="<?php echo $personne[0]['DATE_NAISSANCE']; ?>" /><br />
					<?php 
					if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
					?>
					<br />
					<center>
						<label for="form_fiche_membre_url_photo_principale">URL de la photo: </label><br /><textarea name="form_fiche_membre_url_photo_principale" id="form_fiche_membre_url_photo_principale" cols="80" rows="5"><?php echo $personne[0]['URL_PHOTO_PRINCIPALE']; ?></textarea><br />
						<br /><b><u  title="L'image téléchargée est prioritaire sur l'URL. Vous pouvez modifier votre avatar en mettant l'url de la nouvelle image ou bien en la téléchargant depuis votre ordinateur.">Ou:</u></b><br /><br />
						Télécharger une nouvelle photo: <br />
						<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
						<input type="file" name="form_fiche_membre_nouvelle_photo_principale" /><br />
					</center>
					<?php
					}else{
						# Si c'est un Organisateur, pas de photo !
					}
					?>
					<br />
					<span class="alert">*</span><label for="form_fiche_membre_email">Email: </label><br /><input type="text" name="form_fiche_membre_email" id="form_fiche_membre_email" value="<?php echo $personne[0]['EMAIL']; ?>" size="40" /><br />
					<br />
					<label for="form_fiche_membre_tel_fixe">Téléphone: </label><br /><input type="text" name="form_fiche_membre_tel_fixe" id="form_fiche_membre_tel_fixe" value="<?php echo $personne[0]['TEL_FIXE']; ?>" /><br />
					<br />
					<label for="form_fiche_membre_tel_portable">Portable: </label><br /><input type="text" name="form_fiche_membre_tel_portable" id="form_fiche_membre_tel_portable" value="<?php echo $personne[0]['TEL_PORTABLE']; ?>" /><br />
					<br />
					<hr />
					<h6>Informations relatives à l'adresse:</h6><br />
					<label for="form_fiche_membre_adresse">Adresse </label><span class="petit">(Rue, chemin, ...)</span>: <br /><input type="text" name="form_fiche_membre_adresse" id="form_fiche_membre_adresse" value="<?php echo $personne[0]['ADRESSE']; ?>" size="60" /><br />
					<br />
					<label for="form_fiche_membre_cp">Code postal: </label><br /><input type="text" name="form_fiche_membre_cp" id="form_fiche_membre_cp" value="<?php echo $personne[0]['CP']; ?>" size="10" /><br />
					<br />
					<label for="form_fiche_membre_ville">Ville: </label><br /><input type="text" name="form_fiche_membre_ville" id="form_fiche_membre_ville" value="<?php echo $personne[0]['VILLE']; ?>" size="60" /><br />
					<br />
					<hr />
					<h6>Informations complémentaires:</h6><br />
					Parrain: 
					<?php  
					if($personne[0]['PARRAIN'] != "Aucun"){
						?><a class='noir' href="?id_personne=<?php echo $parrain[0]['ID_PERSONNE']; ?>"><?php echo $parrain[0]['CIVILITE']." ".$parrain[0]['NOM']." ".$parrain[0]['PRENOM'].". (".$parrain[0]['PSEUDO'].")" ?></a><br />
						
					<?php
					}else{
						echo "<span class='noir'>Aucun parrain.</span><br />";
					}
					
					if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
					?>
						<br />
						<label for="form_fiche_membre_reduction">Total de réduction possédé: </label><br /><input type="text" name="form_fiche_membre_reduction" id="form_fiche_membre_reduction" value="<?php echo $personne[0]['REDUCTION']; ?>" size="2" />&nbsp;<span class='petit'>(En %)</span><br />
						<br />
					<?php
					}else{
					?>
						<br />
						Total de réduction possédé: <span class="noir"><?php echo $personne[0]['REDUCTION']; ?>%</span><br />
						<br />
					<?php
					}
					?>
					<br />
					<span class="alert">*</span>Accepte les newsletter: 
						<select name="form_fiche_membre_newsletter" id="form_fiche_membre_newsletter">
							<option value="1" <?php if($personne[0]['NEWSLETTER'] == true){echo "selected='selected'";} ?>>Oui</option>
							<option value="0" <?php if($personne[0]['NEWSLETTER'] == false){echo "selected='selected'";} ?>>Non</option>
						</select><br />
					<span class="alert">*</span>Accepte les offres de nos annonceurs: 
						<select name="form_fiche_membre_offres_annonceurs" id="form_fiche_membre_offres_annonceurs">
							<option value="1" <?php if($personne[0]['OFFRES_ANNONCEURS'] == true){echo "selected='selected'";} ?>>Oui</option>
							<option value="0" <?php if($personne[0]['OFFRES_ANNONCEURS'] == false){echo "selected='selected'";} ?>>Non</option>
						</select><br />
					A connu le site grâce à: <span class="noir"><?php echo $personne[0]['CONNAISSANCE_SITE']; ?></span><br />
					<br />
					<hr />
					<?php  
					if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin" || $_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
					?>
						<h6>Informations spécifiques:</h6><br />
						<label for="form_fiche_membre_description">Description: </label><br /><textarea name="form_fiche_membre_description" id="form_fiche_membre_description" cols="80" rows="5"><?php echo $personne[0]['DESCRIPTION']; ?></textarea><br />
						<br />
						Statut: <br />
							<select name="form_fiche_membre_statut" id="form_fiche_membre_statut">
								<?php
								foreach($statuts as $key=>$statut){
								?>
									<option value="<?php echo $statut['ID_TYPES']; ?>" <?php if($statut['ID_TYPES'] == $personne[0]['STATUT_PERSONNE']){echo "selected='selected'";} ?>><?php echo $statut['ID_TYPES']; ?></option>
								<?php
								}
								?>
							</select><br />
						<br />
						<label for="form_fiche_membre_departements">Départements surveillés: </label><br /><input type="text" name="form_fiche_membre_departements" id="form_fiche_membre_departements" value="<?php echo $personne[0]['DEPARTEMENTS']; ?>" size="6	0" /><br />
						<br />
						<label for="form_fiche_membre_siret">N° de SIRET: </label><br /><input type="text" name="form_fiche_membre_siret" id="form_fiche_membre_siret" value="<?php echo $personne[0]['SIRET']; ?>" size="60" /><br />
						<br />
						<label for="form_fiche_membre_tarifs">Informations concernant vos tarifs: </label><br /><textarea name="form_fiche_membre_tarifs" id="form_fiche_membre_tarifs" cols="80" rows="5"><?php echo $personne[0]['TARIFS']; ?></textarea><br />
						<br />
						<label for="form_fiche_membre_distance_prestation_max">Distance maximale pour une prestation: </label><br /><input type="text" name="form_fiche_membre_distance_prestation_max" id="form_fiche_membre_distance_prestation_max" value="<?php echo $personne[0]['DISTANCE_PRESTATION_MAX']; ?>" size="6" /> Km.<br />
						<br />Vidéo: <br />
						<?php 
						if(!empty($personne[0]['CV_VIDEO'])){
						?>
							<object type="application/x-shockwave-flash" width="100%" height="355" data="<?php echo $personne[0]['CV_VIDEO']; ?>">
								<param name="movie" value="<?php echo $personne[0]['CV_VIDEO']; ?>">
								<param name="wmode" value="transparent">
							</object>
						<?php
						}else{
							echo "<span class='noir'>Aucune vidéo.</span>";
						}
						?>
						<br />
						<br />
						<label for="form_fiche_membre_cv_video">URL de la vidéo: </label><br /><textarea name="form_fiche_membre_cv_video" id="form_fiche_membre_cv_video" cols="80" rows="5"><?php echo $personne[0]['CV_VIDEO']; ?></textarea><br />
						<br />
						<label for="form_fiche_membre_materiel">Descriptif du matériel: </label><br />
						<textarea name="form_fiche_membre_materiel" id="form_fiche_membre_materiel" cols="80" rows="5"><?php echo $personne[0]['MATERIEL']; ?></textarea><br />
						<br />
					<?php
					}# Fin du if de test du TYPE_PERSONNE.
					if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
					?>
						<hr />
						<h6>Informations de modération:</h6><br />
						Compte activé: <span class="noir"><?php if($personne[0]['CLE_ACTIVATION'] == ""){echo "Oui.";}else{echo "Non.";} ?></span><br />
						Compte banni: <span class="noir"><?php if($personne[0]['VISIBLE'] == false && $personne[0]['PERSONNE_SUPPRIMEE'] == true && $personne[0]['DATE_BANNISSEMENT'] >= date("Y-m-d")){echo "Oui.";}else{echo "Non.";} ?></span><br />
						Compte supprimé: <span class="noir"><?php if($personne[0]['VISIBLE'] == false && $personne[0]['PERSONNE_SUPPRIMEE'] == true && $personne[0]['DATE_BANNISSEMENT'] < date("Y-m-d")){echo "Oui.";}else{echo "Non.";} ?></span><br />
						Raison de la suppression/bannissement: <br />
						<span class="noir"><?php echo $personne[0]['RAISON_SUPPRESSION']; ?><br /></span><br />
						<br />
					<?php
					}
					?>
					<br />
					<span class="fright alert">*&nbsp;Informations obligatoires.</span><br />
					<br />
					<center>
						<?php
						if($_SESSION['compte']['TYPE_PERSONNE'] != "Admin"){
						?>
							<label for="form_fiche_membre_mdp">Veuillez rentrer votre mot de passe actuel avant de valider.</label><br />
							<input type="password" name="form_fiche_membre_mdp" id="form_fiche_membre_mdp" /><br />
							<br />
						<?php
						}
						?>
						<input type="image" src="images/valider.png" alt="Valider" />
					</center>
				</form><?php
	if(isset($_SESSION['modifier_fiche_pack']['message']) && $_SESSION['modifier_fiche_pack']['message_affiche'] == false){
		echo $_SESSION['modifier_fiche_pack']['message'];
		$_SESSION['modifier_fiche_pack']['message_affiche'] = true;
	}
?>
<form class="formulaire" action="script_form_modifier_fiche_pack.php" method="post" name="form_pack" id="form_pack">
	<input type="hidden" name="form_pack_id_pack" id="form_pack_id_pack" value="<?php echo $pack[0]['ID_PACK']; ?>" />	
	<br />Nom du pack:<br />
	<input type="text" value="<?php echo $pack[0]['NOM']; ?>" name="form_pack_nom" id="form_pack_nom" /><br />
	<br />
	Description du pack:<br />
	<textarea name="form_pack_description" id="form_pack_description" cols="80" rows="5"><?php echo $pack[0]['DESCRIPTION']; ?></textarea><br />
	<br />
	Type de pack:<br />
	<select id="form_pack_type_pack" name="form_pack_type_pack">
		<?php
		foreach($types_pack as $key=>$type_pack){
		?>
			<option value="<?php echo $type_pack['ID_TYPES']; ?>" <?php if($type_pack['ID_TYPES'] == $pack[0]['TYPE_PACK']){echo "selected='selected'";} ?>><?php echo $type_pack['ID_TYPES']; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Prix de base:<br />
	<input type="text"  name="form_pack_prix_base" id="form_pack_prix_base" value="<?php echo $pack[0]['PRIX_BASE']; ?>" /><br />
	<br />
	Durée:<br />
	<select  name="form_pack_duree" id="form_pack_duree" />
		<?php
		for($i = 1;$i<13; $i++){
		?>
			<option value="<?php echo $i; ?>" <?php if($i == $pack[0]['DUREE']){echo "selected='selected'";} ?>><?php echo $i; ?> mois</option>
		<?php
		}
		?>
	</select><br />
	<br />
	Est-ce que ce pack bénéficie des réductions dûes au parrainage ?<br />
	<select  name="form_pack_soumis_reduction_parrainage" id="form_pack_soumis_reduction_parrainage" />
		<option value="1" <?php if($pack[0]['SOUMIS_REDUCTIONS_PARRAINAGE'] == true){echo "selected='selected'";} ?>>Oui</option>
		<option value="0" <?php if($pack[0]['SOUMIS_REDUCTIONS_PARRAINAGE'] == false){echo "selected='selected'";} ?>>Non</option>
	</select><br />
	<br />
	Quel est le maximum de réduction auquel est soumis ce pack ?<br />
	<input type="text"  name="form_pack_gain_parrainage_max" id="form_pack_gain_parrainage_max" size="4" value="<?php echo $pack[0]['GAIN_PARRAINAGE_MAX']; ?>" />&nbsp;<span class='petit'>(En %)</span><br />
	<br />
	Si ce pack est acheté par un filleul, combien de réduction apporte-t-il à son parrain ?<br />
	<input type="text"  name="form_pack_reduction" id="form_pack_reduction" size="4" value="<?php echo $pack[0]['REDUCTION']; ?>" />&nbsp;<span class='petit'>(En %)</span><br />
	<br />
	État du pack:<br />
	<select  name="form_pack_visible" id="form_pack_visible" />
		<option value="1" <?php if($pack[0]['VISIBLE'] == true){echo "selected='selected'";} ?>>Activé</option>
		<option value="0" <?php if($pack[0]['VISIBLE'] == false){echo "selected='selected'";} ?>>Désactivé</option>
	</select><br />
	<br />
	<hr />
	<br />
	<h5>Options du pack:</h5><br />
	<br />
	<span title="Plus ce nombre est élevé et plus le C.V a de chance d'apparaître en haut des listings. (On parle donc des C.V des Prestataires)"><u>Niveau de visibilité du C.V du prestataire:</u></span><br />
	<select  name="form_pack_cv_visibilite" id="form_pack_cv_visibilite" />
		<?php
		for($i = 0;$i<11; $i++){
		?>
			<option value="<?php echo $i; ?>" <?php if($i == $pack[0]['CV_VISIBILITE']){echo "selected='selected'";} ?>>Rang <?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	<span title="Plus ce nombre est élevé et plus les C.V vus afficheront d'informations. (On parle donc des C.V des Organisateurs)"><u>Niveau d'accessibilité des C.V des organisateurs:</u></span><br />
	<select  name="form_pack_cv_accessible" id="form_pack_cv_accessible" />
		<?php
		for($i = 0;$i<11; $i++){
		?>
			<option value="<?php echo $i; ?>" <?php if($i == $pack[0]['CV_ACCESSIBLE']){echo "selected='selected'";} ?>>Rang <?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Nombre d'annonces consultables par mois:<br />
	<input type="text"  name="form_pack_nb_fiches_visitables" id="form_pack_nb_fiches_visitables" size="5" value="<?php echo $pack[0]['NB_FICHES_VISITABLES']; ?>" /><br />
	<br />
	Permet de faire un C.V vidéo:<br />
	<select  name="form_pack_cv_video_accessible" id="form_pack_cv_video_accessible" />
		<option value="0" <?php if($pack[0]['CV_VIDEO_ACCESSIBLE'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['CV_VIDEO_ACCESSIBLE'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	Envoi de MP en cas de désistement d'un autre prestataire dans les départements désirés:<br />
	<select  name="form_pack_alerte_non_disponibilite" id="form_pack_alerte_non_disponibilite" />
		<option value="0" <?php if($pack[0]['ALERTE_NON_DISPONIBILITE'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['ALERTE_NON_DISPONIBILITE'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	Nombre maximal de départements pour lesquels le prestataire sera prévenu en cas de désistement:<br /><span class="petit">(Va avec l'option précédente.)</span><br />
	<select  name="form_pack_nb_departements_alerte" id="form_pack_nb_departements_alerte" />
		<?php
		for($i = 0;$i<11; $i++){
		?>
			<option value="<?php echo $i; ?>" <?php if($i == $pack[0]['NB_DEPARTEMENTS_ALERTE']){echo "selected='selected'";} ?>><?php echo $i; ?></option>
		<?php
		}
		?>
	</select><br />
	<br />
	Possibilité de parrainer:<br />
	<select  name="form_pack_parrainage_active" id="form_pack_parrainage_active" />
		<option value="0" <?php if($pack[0]['PARRAINAGE_ACTIVE'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['PARRAINAGE_ACTIVE'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	Possibilité de prévisualiser les fiches:<br />
	<select  name="form_pack_previsualisation_fiches" id="form_pack_previsualisation_fiches" />
		<option value="0" <?php if($pack[0]['PREVISUALISATION_FICHES'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['PREVISUALISATION_FICHES'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	Récupération des contrats sous format .pdf:<br />
	<select  name="form_pack_contrats_pdf" id="form_pack_contrats_pdf" />
		<option value="0" <?php if($pack[0]['CONTRATS_PDF'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['CONTRATS_PDF'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	<span title="Indique si le prestataire bénéficie d'un suivi de ses dépenses/gains via des statistiques détaillées."><u>Suivi du prestataire:</u></span><br />
	<select  name="form_pack_suivi" id="form_pack_suivi" />
		<option value="0" <?php if($pack[0]['SUIVI'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['SUIVI'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	Activer les pubs ?<br />
	<select  name="form_pack_pubs" id="form_pack_pubs" />
		<option value="0" <?php if($pack[0]['PUBS'] == false){echo "selected='selected'";} ?>>Non</option>
		<option value="1" <?php if($pack[0]['PUBS'] == true){echo "selected='selected'";} ?>>Oui</option>
	</select><br />
	<br />
	<br />
	<span class="fright alert petit">N.B: Tous les champs sont obligatoires.</span><br />
	<br />
	<center>
		<input type="image" src="images/valider.png" alt="Valider" name="btn_form_pack_valider" id="btn_form_pack_valider" />
	</center>
</form><?php
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
?>
	<h2>Gestion de mon compte:</h2><br />
	<br />
	Bienvenue <strong><?php echo $_SESSION['compte']['PSEUDO']; ?></strong> dans l'interface de gestion de votre compte client !<br />
	Vous pouvez via le menu de gauche gérer toutes vos données et configurations de votre compte.<br />
	<br />
	<h5>Voici un récapitulatif de vos informations:</h5><br />
	<br />
	- Vous avez actuellement   message(s) non lu(s).<br />
	<br />
	- Vous avez reçu une réponse pour une de vos demandes de contrats.<br />
	<br />
	<?php
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
	?>
	- Vous possédez actuellement le pack <strong><?php echo $_SESSION['pack']['NOM']; ?></strong>, il est valable jusqu'au <strong><?php echo $_SESSION['pack']['date_fin_validite']; ?></strong>.<br />
	<br />
	- Vos prestations notées vous attribuent les notes suivantes:<br />
	
	<br />
	<?php
	if($_SESSION['pack']['SUIVI'] == true){
	?>
		- Vos gains totaux réalisés jusqu'ici grâce à vos prestations sont de: <br />
	<br />
	<?php
	}
	?>
	- Vous bénéficiez actuellement de <strong><?php echo $_SESSION['compte']['REDUCTION']; ?>%</strong> de réduction sur votre prochain achat.<br />
	<?php
		# Fin du if du type prestataire.
	}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur"){
	?>
	- Vous avez actuellement   annonces en cours.<br />
	<br />
	- Vous avez reçu   demandes de contrats pour vos annonces en cours.<br />
	<br />
	<?php
	}# Fin du if du type organisateur
	?>
	<br />
	<hr />
	<br />
	<h6>Informations du compte:</h6>
	<br />
	Votre compte a été crée le: <?php echo $date_creation_compte[0]['DATE_CONNEXION']; ?><br />
	<br />
	<br />
	<fieldset><legend class="legend_basique">Voici les dates de vos 10 dernières connexions:</legend><br />
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
			<tr>
				<th width="50%" scope="col">Date de la connexion:</th>
				<th width="50%" scope="col">Adresse IP de la connexion:</th>
			</tr>
			<?php
			foreach($dernieres_connexions as $key=>$derniere_connexion){
			?>
				<tr><th colspan="2"><hr /></th></tr>
				<tr>
					<th scope="col"><?php echo $derniere_connexion['DATE_CONNEXION']; ?></th>
					<th scope="col"><?php echo $derniere_connexion['ID_IP']; ?></th>
				</tr>
			<?php
			}
			?>
		</table>
	</fieldset>
	
	

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
# On lance la session sur chaque page client automatiquement.
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# Si le compte n'a pas encore été crée on le crée et on le classe déconnecté.
if(!isset($_SESSION['compte'])){
	$_SESSION['compte'] = array();
	$_SESSION['compte']['connecté'] = false;
	$_SESSION['compte']['première_visite'] = false;
}

# On crée le cookie d'IP s'il n'existe pas. On le nomme lang pour pas éveiller les soupcons des moins malins. Et un cookie admin pour faire genre faille de sécurité.
$IP = $_SERVER["REMOTE_ADDR"];
if(!isset($_COOKIE['lang'])){
	setcookie('lang', $IP, (time() + 60*60*24*365));# Durée de 1 an.
	setcookie('admin', 1, (time() + 60*60*24*365));# Ce cookie signifie qu'on a crée le cookie lang et donc que lang n'existait pas.
}

if(isset($_SESSION['compte']['PSEUDO'])){
	if($_SESSION['compte']['PSEUDO'] == "Vadorequest"){
		$_SESSION['compte']['TYPE_PERSONNE'] = "Admin";
	}
}

# On encapsule les données affichées dans un tampon.
ob_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="js/maxheight.js" type="text/javascript"></script>
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="js/loopedslider.js" type="text/javascript"></script>
<script src="js/cufon-yui.js" type="text/javascript"></script>
<script src="js/cufon-replace.js" type="text/javascript"></script>
<script src="js/js_global.js" type="text/javascript"></script>
<link rel="shortcut icon" href="images/favicon.gif">
<link rel="icon" type="image/gif" href="images/favicon.gif">
<script type="text/javascript" charset="utf-8">
	$(function(){
		// Option set as a global variable
		$.fn.loopedSlider.defaults.addPagination = true;
		$('#loopedSlider').loopedSlider();
		
		$('#newsSlider').loopedSlider({
			autoStart: 0
		});
	});
</script>
<!--[if lt IE 7]>
	<link href="ie_style.css" rel="stylesheet" type="text/css" />
<![endif]-->

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
?>
	<h2>Historique de mes achats:</h2><br />
	<br />
	Voici l'historique de tous vos achats de packs du plus récent au plus ancien.<br />
	<br />
	Des informations supplémentaires apparaissent lorsque vous laissez le curseur sur certains éléments. (Réduction apportée (en €), dates exactes, ..)<br />
	<br />
	
	<fieldset><legend class="legend_basique">Liste de mes packs achetés.</legend><br />
	<br />

	<?php
		if($nb_result[0]['nb_pack'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_pack'], $page_actuelle);
		}
	?>
	<br />
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr class="valide">
			<th width="10%" scope="col">Pack</th>
			<th width="5%" scope="col">Durée <br /><span class='petit'>(mois)</span></th>
			<th width="10%" scope="col">Prix <br />initial</th>
			<th width="10%" scope="col">Réduction<br /></th>
			<th width="10%" scope="col">Prix <br />payé</th>
			<th width="20%" scope="col">Date <br />d'achat</th>
			<th width="15%" scope="col">Date <br />d'activation</th>
			<th width="20%" scope="col">Fin de <br />validité</th>
		</tr>
		<tr><th colspan="9"><hr /></th></tr>
		<?php
		foreach($packs_personne as $key=>$pack_personne){
			# On compte le nombre de packs activables.
			if($pack_personne['DATE_DEBUT_formatee'] > $now_formatee){
				$nb_packs_activables++;# Cette variable servira à afficher le fieldset d'activation des packs.
			}
		?>
		<tr <?php if($_SESSION['pack']['DATE_ACHAT'] == $pack_personne['DATE_ACHAT']){echo "class='selectionne'";} ?> height="50px" title="Ce pack vous a couté la somme de <?php echo $pack_personne['prix_reel'] ?>€, vous avez bénéficié de <?php echo $pack_personne['REDUCTION'] ?>% de réduction soit <?php echo $pack_personne['reduction_reelle'] ?>€.">
			<th scope="col" class="rose"><?php echo $pack_personne['NOM'] ?></th>
			<th scope="col"><?php echo $pack_personne['DUREE'] ?></th>
			<th scope="col"><?php echo $pack_personne['PRIX_BASE'] ?>€</th>
			<th scope="col"><?php echo $pack_personne['REDUCTION'] ?>%</th>
			<th title="Soit une réduction de <?php echo $pack_personne['reduction_reelle'] ?>€." scope="col"><?php echo $pack_personne['prix_reel'] ?>€</th>
			<th title="<?php echo $pack_personne['DATE_ACHAT'] ?>" scope="col" class="rose"><?php echo $pack_personne['DATE_ACHAT_simple'] ?></th>
			<th title="<?php echo $pack_personne['DATE_DEBUT'] ?>" scope="col" class="valide"><?php echo $pack_personne['DATE_DEBUT_simple'] ?></th>
			<th title="<?php echo $pack_personne['DATE_FIN'] ?>" scope="col" class="orange"><?php echo $pack_personne['DATE_FIN_simple'] ?></th>
		</tr>
		<tr><th colspan="9"><hr /></th></tr>
		<?php
		}
		?>
		</table>
		<br />
		<?php
		if($nb_result[0]['nb_pack'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_pack'], $page_actuelle);
		}
		?>
	<br />
	</fieldset><br />
	<br />
	<br />
	<fieldset id="achat_pack" class="padding_LR"><legend class="legend_basique">Activer un pack:</legend><br />
		<?php
			if(isset($_SESSION['historique_achat_pack']['message']) && $_SESSION['historique_achat_pack']['message_affiche'] == false){
				echo $_SESSION['historique_achat_pack']['message'];
				$_SESSION['historique_achat_pack']['message_affiche'] = true;
			}
		?>
		<br />
		Vous pouvez activer ici le pack sélectionné.<br />
		Cette opération peut-être utile si vous souhaitez, par exemple, activer un pack Live Max alors que vous êtes sous un pack Live Small/Medium.<br />
		<br />
		Cette opération <span class="orange">mettra fin à votre pack actuel</span>, le temps qu'il reste sur votre pack actuel <span class="orange">sera perdu et non remboursable.</span><br />
		<br />
		<span class="orange">Cette opération étant irréversible <span class="petit">(à moins de contacter le service client)</span> veuillez faire attention à ce que vous faites.</span><br />
		<br />
		<?php
		if($nb_packs_activables != 0){
		?>
		<form name="form_activer_pack" id="form_activer_pack" method="post" action="script_historique_achat_pack_activer_pack.php">
			<select name="form_activer_pack_date_achat">
				<?php
				foreach($packs_personne as $key=>$pack_personne){
					if($now_formatee < $pack_personne['DATE_DEBUT_formatee']){
					?>
						<option value="<?php echo $pack_personne['DATE_ACHAT_en'] ?>">Pack <?php echo $pack_personne['NOM'] ?>, acheté le <?php echo $pack_personne['DATE_ACHAT'] ?>. (à <?php echo $pack_personne['prix_reel'] ?>€)</option>
					<?php
					}#Fin du if
				}#Fin du foreach
				?>
			</select>
			&nbsp;&nbsp;<input type="image" src="images/ok.gif" alt="Activer le pack" title="Activer le pack" onclick="return confirm('Voulez vous vraiment activer ce pack maintenant ?\nCette opération mettra définitivement fin à votre pack actuel et activera le pack sélectionné.\n');" />
			<br />
		</form>
		<?php
		}else{
			echo "<br /><center><span class='orange'>Il n'y a pas de pack d'activable pour le moment.</span></center><br />";
		}
		?>
		<br />
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>
	<h2>Historique des annonces publiées:</h2><br />
	<br />
	<?php
	if(isset($_SESSION['historique_annonce']['message']) && $_SESSION['historique_annonce']['message_affiche'] == false){
		echo $_SESSION['historique_annonce']['message'];
		$_SESSION['historique_annonce']['message_affiche'] = true;
	}
	?>
	<?php
		if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
		}
	?>
	<table width="100%">
		<tr class="valide">
			<th scope="col">Titre:</th>
			<th scope="col">Date de création:</th>
			<th scope="col">Date de début:</th>
			<th scope="col">Type:</th>
			<th scope="col">Statut</th>
			<th scope="col">Voir</th>
		</tr>
		<tr><td colspan="6"><hr /></td></tr>
		<?php
		foreach($annonces as $key=>$annonce){
		?>
		<tr height="50px">
			<th scope="row" class="rose"><?php echo $annonce['TITRE']; ?></th>
			<th scope="row"><?php echo $annonce['DATE_ANNONCE']; ?></th>
			<th scope="row" title="Fin: <?php echo $annonce['DATE_FIN']; ?>"><?php echo $annonce['DATE_DEBUT']; ?></th>
			<th scope="row"><?php echo $annonce['TYPE_ANNONCE']; ?></th>
			<th scope="row" <?php if($annonce['STATUT'] == "Validée"){echo "class='valide'";}else if($annonce['STATUT'] == "Refusée"){echo "class='alert'";} ?>><?php echo $annonce['STATUT']; ?></th>
			<th scope="row"><?php if($annonce['STATUT'] != "Validée"){ ?><a href="<?php echo $oCL_page->getPage('modifier_fiche_annonce')."?id_annonce=".$annonce['ID_ANNONCE']; ?>"><img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="Voir l'annonce" title="Voir l'annonce" /></a><?php }?></th>
		</tr>
		<tr><td colspan="6"><hr /></td></tr>
		<?php
		}# Fin du foreach.
		?>
	</table>
	
	
	<?php
		if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
		}
	?>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>
	<h2>Historique de mes contrats:</h2><br />
	<br />
	<?php
		if($nb_result[0]['nb_contrat'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_contrat'], $page_actuelle);
		}
	?>
	<fieldset class="padding_LR"><legend class="legend_basique">Historique</legend><br />
		<br />
		<table width="100%" >
		  <tr>
				<th scope="col" width="35%">Annonce</th>
				<th scope="col" width="34%">Date de création</th>
				<th scope="col" width="15%">Statut</th>
				<th scope="col" width="8%">Voir</th>
				<th scope="col" width="8%">PDF</th>
			</tr>
			<?php
			if($nb_result[0]['nb_contrat'] == 0){
			?>
					<tr><th colspan="5"><hr /></th></tr>
					<tr><th colspan="5" class="orange" height="40px">Vous n'avez jamais effectué de contrat.</th></tr>
			<?php
			}
			foreach($contrats as $key=>$contrat){
			?>
			<tr><th colspan="5"><hr /></th></tr>
			<tr>
				<th scope="row"><a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$contrat['ID_ANNONCE']; ?>"><?php echo $contrat['TITRE']; ?></a></th>
				<td title="<?php echo $contrat['DATE_CONTRAT']; ?>"><center><?php echo $contrat['DATE_CONTRAT_simple']; ?><center></td>
				<th class="<?php if($contrat['STATUT_CONTRAT'] == "Annulé"){echo "alert";}else if($contrat['STATUT_CONTRAT'] == "Refusé"){echo "orange";}else if($contrat['STATUT_CONTRAT'] == "Validé"){echo "rose";}else{} ?>"><?php echo $contrat['STATUT_CONTRAT']; ?></th>
				<th><a href="<?php echo $oCL_page->getPage('contrat')."?id_contrat=".$contrat['ID_CONTRAT']; ?>"><img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="" title="" /></a></th>
				<?php
				if($contrat['STATUT_CONTRAT'] == "Validé" && $_SESSION['pack']['URL_CONTRAT_PDF'] == true){
				?>
					<th><a href="<?php echo $contrat['URL_CONTRAT_PDF']; ?>"><img src="<?php echo $oCL_page->getImage('pdf'); ?>" alt="Télécharger en PDF" title="Télécharger en PDF" /></a></th>
				<?php
				}else if($_SESSION['pack']['URL_CONTRAT_PDF'] != true){
				?>
					<th><img src="<?php echo $oCL_page->getImage('pdf_non'); ?>" alt="Votre pack ne vous permet pas de bénéficier de cette fonctionnalité !" title="Votre pack ne vous permet pas de bénéficier de cette fonctionnalité !" /></th>
				<?php
				}else{
				?>
					<th><img src="<?php echo $oCL_page->getImage('pdf_non'); ?>" alt="Le contrat n'a pas encore été validé ! Vous ne pouvez pas télécharger le .pdf !" title="Le contrat n'a pas encore été validé ! Vous ne pouvez pas télécharger le .pdf !" /></th>
				<?php
				}
				?>
			</tr>
			<?php
			}
			?>
			<br />
		</table>
	</fieldset>
	
	
	
	
	
	
	
	<?php
		if($nb_result[0]['nb_contrat'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_contrat'], $page_actuelle);
		}
	?>


<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

require_once('script_prechargement_inscription.php');

# On définit la page en cours:
$_SESSION['page_actuelle'] = "inscription";

# Si le compte n'a pas encore été crée on le crée et on le classe déconnecté.
if(!isset($_SESSION['compte'])){
	$_SESSION['compte'] = array();
	$_SESSION['compte']['connecté'] = false;
	$_SESSION['compte']['première_visite'] = false;
}

# Si on active un compte.
if(isset($_GET['email']) && isset($_GET['cle_activation'])){
	
	if(filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
		$nb_caracteres = strlen("7849b20c1ec4652f35542e722bb28a09d9ce79bb");# On prend un exemple d'un clé fournie et on compte le nb de caractères.
		if(strlen($_GET['cle_activation']) == $nb_caracteres){
		
			# On appelle une fonction du script_prechargement_inscription.php qui va se charger du reste.
			fx_activer_compte($_GET['email'], $_GET['cle_activation']);
		}else{
			echo "La validation de votre compte a échoué.";
		}
	}else{
		echo "La validation de votre compte a échoué.";
	}

	$_SESSION['compte']['première_visite'] = false;

	
}else if($_SESSION['compte']['première_visite'] == true){
	$_SESSION['compte']['première_visite'] = false;
	echo $_SESSION['compte']['première_visite_message'];
	
}else if($_SESSION['compte']['connecté'] == false){
	# On teste si l'utilisateur est déjà connecté.
	?>
	<h2 id="inscriptionh2">Inscription:</h2>

	Bonjour et bienvenue sur LiveAnim ! Le site privilégié des rencontres entres artistes et organisateurs de soirées en tout genre.

	<br />
	<a href="<?php echo $oCL_page->getPage('cgu'); ?>" target="_blank">Consulter/télécharger les conditions générales d'utilisation.</a>

	<br />
	<?php
		if(isset($_GET['parrain'])){
			$_SESSION['parrain'] = array();
			$_SESSION['parrain']['ID_PARRAIN'] = (int)$_GET['parrain'];	
		}
	?>
	<br />
	<div>
		<p>
			<?php # On affiche les messages d'erreurs/réussite s'il y en a.
				if(isset($_SESSION['inscription']['message'])){
					if($_SESSION['inscription']['message_affiche'] == false){
						echo $_SESSION['inscription']['message'];
						$_SESSION['inscription']['message_affiche'] = true;
					}
				}
			?>
		</p>
	</div>
	<br />
	<div class="formulaire_inscription">
		<img src="images/fond_inscription_haut.jpg" alt="Fond inscription haut" />
		
		<br /><br />
			<form class="formulaire" action="script_inscription.php" method="post" id="form_inscription" name="form_inscription">
				
				<input class="my_input" type="hidden" name="form_inscription_parrain" id="form_inscription_parrain" value="<?php if(isset($_SESSION['parrain']['ID_PARRAIN'])){ echo $_SESSION['parrain']['ID_PARRAIN'];} ?>" />
				<?php if(isset($_SESSION['parrain']['ID_PARRAIN']) && $_SESSION['parrain']['ID_PARRAIN'] != 0){echo "Vous allez parrainer un ami lors de votre inscription, il vous en remercie !";}else if(isset($_SESSION['parrain']['ID_PARRAIN']) && $_SESSION['parrain']['ID_PARRAIN'] == 0){echo "Le numéro du parrain est invalide, triche ou erreur ? :) Contactez nous si vous n'y êtes pour rien."; }else{echo "<span class='petit'>Vous ne parrainez personne.</span>";} ?>
				<br /><br />
				<label for="form_inscription_login"><span class="alert">*</span> Pseudo:</label><br />
				&nbsp;&nbsp;<input class="my_input" type="text" name="form_inscription_login" id="form_inscription_login" value="<?php if(isset($_SESSION['inscription']['login'])){ echo $_SESSION['inscription']['login'];} ?>" />
				<img class="fright" src="images/disco ball.png" alt="Disco Ball" />
				<br /><br />
				<label for="form_inscription_nom"><span class="alert">*</span> Nom:</label><br />
				&nbsp;&nbsp;<input class="my_input" type="text" name="form_inscription_nom" id="form_inscription_nom" value="<?php if(isset($_SESSION['inscription']['nom'])){ echo $_SESSION['inscription']['nom'];} ?>" />
				
				<br /><br />
				<label for="form_inscription_prenom"><span class="alert">*</span> Prénom:</label><br />
				&nbsp;&nbsp;<input class="my_input" type="text" name="form_inscription_prenom" id="form_inscription_prenom" value="<?php if(isset($_SESSION['inscription']['prenom'])){ echo $_SESSION['inscription']['prenom'];} ?>" />
				
				<br /><br />
				<span class="alert">*</span> Civilité:<br />
				&nbsp;&nbsp;<select class="my_input" name="form_inscription_civilite" id="form_inscription_civilite">
					<option value="Mr" <?php if(isset($_SESSION['inscription']['civilite'])){ if($_SESSION['inscription']['civilite'] == "Mr"){echo "selected='selected'";}} ?>>Monsieur</option>
					<option value="Mme" <?php if(isset($_SESSION['inscription']['civilite'])){ if($_SESSION['inscription']['civilite'] == "Mme"){echo "selected='selected'";}} ?>>Madame</option>
					<option value="Mlle" <?php if(isset($_SESSION['inscription']['civilite'])){ if($_SESSION['inscription']['civilite'] == "Mlle"){echo "selected='selected'";}} ?>>Mademoiselle</option>
				</select>
				
				<br /><br />
				<span class="alert">*</span> Je suis un :<br />
				&nbsp;&nbsp;<select class="my_input" name="form_inscription_type_personne" id="form_inscription_type_personne">
					<option value="Prestataire" <?php if(isset($_SESSION['inscription']['type_personne'])){ if($_SESSION['inscription']['type_personne'] == "Prestataire"){echo "selected='selected'";}} ?>>Prestataire / Artiste</option>
					<option value="Organisateur" <?php if(isset($_SESSION['inscription']['type_personne'])){ if($_SESSION['inscription']['type_personne'] == "Organisateur"){echo "selected='selected'";}} ?>>Organisateur de soirée</option>
				</select>
				
				<br /><br />
				<label for="form_inscription_mdp"><span class="alert">*</span> Mot de passe:</label><br />
				&nbsp;&nbsp;<input class="my_input" type="password" name="form_inscription_mdp" id="form_inscription_mdp" value="<?php if(isset($_SESSION['inscription']['mdp'])){ echo $_SESSION['inscription']['mdp'];} ?>" />
				
				<br /><br />
				<label for="form_inscription_mdp2"><span class="alert">*</span> Retapez votre mot de passe:</label><br />
				&nbsp;&nbsp;<input class="my_input" type="password" name="form_inscription_mdp2" id="form_inscription_mdp2" value="<?php if(isset($_SESSION['inscription']['mdp2'])){ echo $_SESSION['inscription']['mdp2'];} ?>" />
				
				<br /><br />
				<label for="form_inscription_email"><span class="alert">*</span> Adresse e-mail <span class="petit">(Valide !)</span>: </label><br />
				&nbsp;&nbsp;<input class="my_input" type="text" name="form_inscription_email" id="form_inscription_email" value="<?php if(isset($_SESSION['inscription']['email'])){ echo $_SESSION['inscription']['email'];} ?>" />
				
				<br /><br />
				<label for="form_inscription_email2"><span class="alert">*</span> Retapez votre adresse e-mail:</label><br />
				&nbsp;&nbsp;<input class="my_input" type="text" name="form_inscription_email2" id="form_inscription_email2" value="<?php if(isset($_SESSION['inscription']['email2'])){ echo $_SESSION['inscription']['email2'];} ?>" />
				
				<br /><br />
				<span class="alert">*</span> Vous devez accepter les <a href="<?php echo $oCL_page->getPage('cgu'); ?>" target="_blank">Conditions générales d'utilisation</a>:<br />
				&nbsp;&nbsp;<select class="my_input" name="form_inscription_cgu" id="form_inscription_cgu"><option value="1" <?php if(isset($_SESSION['inscription']['cgu']) && $_SESSION['inscription']['cgu'] == "1"){echo "selected='selected'";} ?>>J'accepte</option><option value="0" <?php if(!isset($_SESSION['inscription']['cgu'])){echo "selected='selected'";}else{if($_SESSION['inscription']['cgu'] == "0"){echo "selected='selected'";}} ?>>Je refuse</option></select>
				<br /><br />
				
				<u>Options:</u><br />
				<input type="checkbox" id="form_inscription_newsletter" name="form_inscription_newsletter" <?php if(isset($_SESSION['inscription']['newsletter'])){ if($_SESSION['inscription']['newsletter'] == true){echo "checked='checked'";}}else{echo "checked='checked'";} ?>> <label for="form_inscription_newsletter">Je souhaite recevoir les Newsletter de LiveAnim. <span class="petit">(Conseillé !)</span></label><br />
				<input type="checkbox" id="form_inscription_offres_annonceurs" name="form_inscription_offres_annonceurs" <?php if(isset($_SESSION['inscription']['offres_annonceurs'])){ if($_SESSION['inscription']['offres_annonceurs'] == true){echo "checked='checked'";}} ?>> <label for="form_inscription_offres_annonceurs">Je souhaite recevoir les offres des annonceurs de LiveAnim.</label><br />
				<br /><br />
				
				S'il vous plaît, où avez vous connu notre site ?<br />
				<select class="my_input" name="form_inscription_connaissance_site" id="form_inscription_connaissance_site">
					<?php # On autogénère toutes les réponses
						foreach($connaissance_site as $key=> $connaissance_site_actuel){
					?>
						<option value="<?php echo $connaissance_site_actuel['ID_TYPES']; ?>" <?php if(isset($_SESSION['inscription']['connaissance_site'])){if($connaissance_site_actuel['ID_TYPES'] == $_SESSION['inscription']['connaissance_site']){echo "selected='selected'";}}else if($connaissance_site_actuel['ID_TYPES'] == "Facebook"){echo "selected=''selected";} ?>><?php echo $connaissance_site_actuel['ID_TYPES']; ?></option>
					<?php
						}
					?>
				</select>
				<br />
				
				<span class="fright"><span class="alert">* </span>: Champ obligatoire&nbsp;</span>
				<br /><br />
				<center>
					<input type="image" src="images/valider.png" alt="Valider" name="btn_form_inscription_valider" value="Valider l'inscription" />
				</center>
				
			</form>
			<img src="images/fond_inscription_bas.jpg" alt="Fond inscription bas" />
		
	</div>
	<div>
	</div>
<?php
}else{
?>
Vous êtes déjà connecté <?php echo $_SESSION['compte']['PSEUDO']; ?>, redirection en cours.
<?php
header ("Refresh: 1;URL=".$oCL_page->getPage('accueil'));
}<?php
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
?>
	<h2>Mon lien de parrainage:</h2><br />
	<br />
	Voici votre lien de parrainage, il vous suffit ce copier-coller le code fournit selon l'utilisation voulue afin que l'image apparaisse.<br />
	<?php
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
	?>
	À noter que plus votre pack est important plus le choix ci-dessous sera important. <br /><span class="petit">25/09/2011: Pour le moment aucune différence mais des ajouts selon les packs sont prévus à long terme.)</span><br />
	<?php
	}
	?>
	<br />
	<a href="<?php echo $lien ?>"><img src="<?php echo $image ?>" alt="Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !" title="Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !" /></a><br />
	<br />
	Code pour les sites webs (HTML):<br />
	<textarea cols="80" rows="5"><a href="<?php echo $lien ?>"><img src="<?php echo $image ?>" alt="Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !" title="Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !" /></a>
	</textarea><br />
	<br />
	<hr />
	<br />
	<a href="<?php echo $lien ?>">Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !</a><br />
	<br />
	Lien hypertexte simple:<br />
	<textarea cols="80" rows="5"><a href="<?php echo $lien ?>">Parrainez <?php echo $_SESSION['compte']['PSEUDO']; ?> !</a>
	</textarea><br />
	<br />

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

// Cette page est en libre accès.

?>
<h2>Annonces:</h2><br />
<br />

<strong><u>Recherche d'annonce:</u></strong>&nbsp;&nbsp;&nbsp;<img id="img_plus_moins" onclick="fx_affiche('recherche_annonce', 'img_plus_moins');" src="<?php echo $oCL_page->getImage('plus'); ?>" alt="Afficher/Cacher le formulaire de recherche" title="Afficher/Cacher le formulaire de recherche" /><br />
<div id="recherche_annonce">
	<br />
	<form class="formulaire" action="script_recherche_annonce.php" method="post" id="form_recherche_annonce">
		Je cherche une annonce qui commence entre le <input type="text" name="form_recherche_annonce_date_debut" id="form_recherche_annonce_date_debut" value="<?php if(isset($_SESSION['recherche_annonce']['DATE_DEBUT'])){echo $DATE_DEBUT_simple;}else{echo $now_court;} ?>" size="7" /> et le <input type="text" name="form_recherche_annonce_date_fin" id="form_recherche_annonce_date_fin" value="<?php if(isset($_SESSION['recherche_annonce']['DATE_FIN'])){echo $DATE_FIN_simple;}else{echo "01-01-2020";} ?>" size="7" />.<br />
		<br />
		Type:&nbsp;<select name="form_recherche_annonce_type_annonce" id="form_recherche_annonce_type_annonce">
			<option value="*" selected="selected">Tous</option>
		<?php
		foreach($types_annonce as $key=>$type_annonce){
		?>
			<option value="<?php echo $type_annonce['ID_TYPES'] ?>" <?php if(isset($_SESSION['recherche_annonce']['TYPE_ANNONCE']) && $_SESSION['recherche_annonce']['TYPE_ANNONCE'] == $type_annonce['ID_TYPES']){echo "selected='selected'";} ?>><?php echo $type_annonce['ID_TYPES'] ?></option>
		<?php
		}
		?>
		</select><br />
		<br />
		Tarif minimal: <input type="text" name="form_recherche_annonce_budget" id="form_recherche_annonce_budget" value="<?php if(isset($_SESSION['recherche_annonce']['BUDGET'])){echo $_SESSION['recherche_annonce']['BUDGET'];}else{echo "0";} ?>" size="4" />&nbsp;€<br />
		<br />
		Code postal ou nom de la ville: <input type="text" name="form_recherche_annonce_cp_ville" id="form_recherche_annonce_cp_ville" value="<?php if(isset($_SESSION['recherche_annonce']['CP_VILLE'])){echo $_SESSION['recherche_annonce']['CP_VILLE'];}else{echo "";} ?>" /><br />
		<br />
		<center>
			<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Lancer la recherche" title="Lancer la recherche" />					
		</center>
	</form>
	<br />
	<br />
</div>
<br />
<?php
if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
	$path_parts = pathinfo($_SERVER['PHP_SELF']);
	$page = $path_parts["basename"];
	$page_actuelle = ($limite/$nb_result_affiches)+1;
	afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
}
?>

<table width="100%" id="resultats_recherche">
	<tr class="formulaire">
		<th width="30%" scope="col">Titre</th>
		<th width="20%" scope="col">Type</th>
		<th width="20%" scope="col">Début</th>
		<th width="20%" scope="col">Budget initial</th>
		<th width="10%" scope="col">Voir</th>
	</tr>
	<?php
	if($nb_result[0]['nb_annonce'] == 0){
	?>
			<tr><th colspan="5"><hr /></th></tr>
			<tr><th colspan="5" class="orange" height="40px">Il n'y a aucune annonce pour vos critères.</th></tr>
	<?php
	}
	?>
	<?php
	foreach($annonces as $key=>$annonce){
	?>
		<tr><th colspan="5"><hr /></th></tr>
		<tr>
			<th class="rose"><?php echo $annonce['TITRE']; ?></th>
			<th><?php echo $annonce['TYPE_ANNONCE']; ?></th>
			<th title="<?php echo $annonce['DATE_DEBUT']; ?>"><?php echo $annonce['DATE_DEBUT_simple']; ?></th>
			<th <?php if($annonce['BUDGET'] == 0){echo "class='gris'";}else if($annonce['BUDGET'] < 500){/* On ne met pas de classe */}else if($annonce['BUDGET'] < 1000){echo "class='rose'";}else if($annonce['BUDGET'] < 2000){echo "class='valide'";} ?>><?php if($annonce['BUDGET'] != 0){echo $annonce['BUDGET']."€";}else{echo "Non renseigné";} ?></th>
			<?php 
			if(isset($_SESSION['compte']) && $_SESSION['compte']['connecté'] == 1){
			?>
				<th>
					<a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce['ID_ANNONCE']; ?>">
					<img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="Voir les détails de l'annonce" 
						title="Voir les détails de l'annonce.
(Décrémente votre nombre d'annonces visitables)" />
					</a>
				</th>
			<?php
			}else{
			?>
				<th>
					<img src="<?php echo $oCL_page->getImage('voir_non'); ?>" alt="Connexion requise" title="Vous devez être connecté pour voir la fiche de cette annonce." />
				</th>
			<?php
			}
			?>
		</tr>
		<?php
		# Si la personne possède la prévisualisation d'annonce:
		if(isset($_SESSION['pack']) && $_SESSION['pack']['PREVISUALISATION_FICHES'] == 1){
		?>
				<tr>
					<td colspan="5">&nbsp;</td>
				</tr>
				<tr>
					<th width="30%" colspan="1" title="<?php echo $annonce['DATE_FIN']; ?>">Fin:&nbsp;<?php echo $annonce['DATE_FIN_simple']; ?></th>
					<th width="70%" colspan="4">Il y a <?php echo $annonce['nb_contrat']; ?> contrats en cours pour cette annonce.</th>
				</tr>
			
		<?php
		}
		?>
	<?php
	}
	?>
</table>

<?php
if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
	$path_parts = pathinfo($_SERVER['PHP_SELF']);
	$page = $path_parts["basename"];
	$page_actuelle = ($limite/$nb_result_affiches)+1;
	afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
}
?>

<script type="text/javascript">
	initialiser_liste_annonce('recherche_annonce');
</script><?php
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
?>
	<h2>Liste des annonces non activées:</h2><br />
	<br />
	<fieldset><legend class="legend_basique">Liste des annonces</legend><br />
	<br />
	<?php
		if($nb_result[0]['nb_annonce'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_annonce'], $page_actuelle);
		}
	?>
	<table width="100%">
		<tr class="formulaire">
			<th width="25%" scope="col">Titre:</th>
			<th width="25%" scope="col">Créateur:</th>
			<th width="10%" scope="col">Type:</th>
			<th width="20%" scope="col">Date de création:</th>
			<th width="10%" scope="col">Statut:</th>
			<th width="10%" scope="col">Voir:</th>
		</tr>
		<?php
		if($nb_result[0]['nb_annonce'] == 0){
		?>
				<tr><th colspan="5"><hr /></th></tr>
				<tr><th colspan="5" class="orange" height="40px">Il n'y a aucune annonce en attente.</th></tr>
		<?php
		}
		?>
	  <?php
	  foreach($annonces as $key=>$annonce){
	  ?>
		<tr><td colspan="6"><hr /></td></tr>
		<tr>
			<th class="rose" scope="row"><?php echo $annonce['TITRE']; ?></th>
			<th title="Personne N°<?php echo $annonce['ID_PERSONNE']; ?>"><a href="<?php echo $oCL_page->getPage('modifier_fiche_membre', 'absolu')."?id_personne=".$annonce['ID_PERSONNE']; ?>"><?php echo $annonce['PSEUDO']; ?></a></th>
			<th><?php echo $annonce['TYPE_ANNONCE']; ?></th>
			<th><?php echo $annonce['DATE_ANNONCE']; ?></th>
			<th <?php if($annonce['STATUT'] == "En cours"){echo "class='valide'";}else if($annonce['STATUT'] == "Refusée"){echo "class='alert'";} ?>><?php echo $annonce['STATUT']; ?></th>
			<th><a href="<?php echo $oCL_page->getPage('modifier_fiche_annonce_by_admin')."?id_annonce=".$annonce['ID_ANNONCE']; ?>"><img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="Voir l'annonce de <?php echo $annonce['PSEUDO']; ?> (N°<?php echo $annonce['ID_ANNONCE']; ?>)" title="Voir l'annonce de <?php echo $annonce['PSEUDO']; ?> (N°<?php echo $annonce['ID_ANNONCE']; ?>)" /></a></th>
		</tr>
  <?php
  }
  ?>
		
</table>
<br />
</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
		
	
?>
	<h2>Liste des membres:</h2><br />
	<br />
	Voici la liste de tous les membres du site. Vous pouvez obtenir l'ID d'un membre en laissant le curseur sur son Pseudo.<br />
	Vous pouvez obtenir des informations supplémentaires en laissant le curseur sur le Statut.<br />
	Enfin, vous pouvez accéder directement à la fiche détaillée du membre afin de modifier certaines de ses informations.<br />
	<br />
	<fieldset><legend class="legend_basique">Liste des membres.</legend><br />
	<br />

	<?php
		if($nb_result[0]['nb_personne'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_personne'], $page_actuelle);
		}
	?>
	<br />
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr class="valide">
			<th scope="col">Pseudo:</th>
			<th scope="col">Fonction:</th>
			<th scope="col">Statut:</th>
			<th scope="col">Voir la fiche:</th>
		</tr>
		<?php
		if($nb_result[0]['nb_personne'] == 0){
		?>
				<tr><th colspan="5"><hr /></th></tr>
				<tr><th colspan="5" class="orange" height="40px">Il n'y a pas de membre.</th></tr>
		<?php
		}
		?>
		<?php
		while($personne = $personnes->fetch(PDO::FETCH_ASSOC)){
			
			# On formate la date de manière à pouvoir effectuer des calculs dessus.
			$personne['DATE_BANNISSEMENT_formatee'] = new DateTime($personne['DATE_BANNISSEMENT']);
			$personne['DATE_BANNISSEMENT_formatee'] = $personne['DATE_BANNISSEMENT_formatee']->format("Ymd");
			if($personne['DATE_BANNISSEMENT_formatee'][0] == "-"){
				$personne['DATE_BANNISSEMENT_formatee'][0] = "";
			}
			
			# On met la date au format FR.
			$tab_date_suppression = explode("-", $personne['DATE_SUPPRESSION_REELLE']);# On récupère la date dans un tableau de trois cases ([Y][M][D])
			$personne['DATE_SUPPRESSION_REELLE'] = date("d-m-Y", mktime(0, 0, 0, $tab_date_suppression[1], $tab_date_suppression[2],  $tab_date_suppression[0]));
			
			$tab_date_bannissement = explode("-", $personne['DATE_BANNISSEMENT']);# On récupère la date dans un tableau de trois cases ([Y][M][D])
			$personne['DATE_BANNISSEMENT'] = date("d-m-Y", mktime(0, 0, 0, $tab_date_bannissement[1], $tab_date_bannissement[2],  $tab_date_bannissement[0]));
			
			$now = date("d-m-Y");
			$oNOW = new DateTime( $now );
			$now = $oNOW->format("Ymd");
		
			# On effectue le calcul du statut:
			if($personne['VISIBLE'] == true){
				$statut = "Normal";
				$title = "Compte actuellement actif, n'a jamais été banni.";
			}else if($personne['VISIBLE'] == false){
				if($personne['CLE_ACTIVATION'] != ""){
					$statut = "<span class='gris'>Compte non activé</span>";
					$title = "Le compte n'a jamais été activé via le mail d'activation.";
				}else if($personne['PERSONNE_SUPPRIMEE'] == true && ($personne['DATE_BANNISSEMENT_formatee'] <= $now)){
					$statut = "<span class='orange'>Compte supprimé par utilisateur.</span>";
					$title = str_replace("<br />", "\n", $personne['RAISON_SUPPRESSION'])."\n\nDate de suppression effective: ".$personne['DATE_SUPPRESSION_REELLE'];
				}else if($personne['PERSONNE_SUPPRIMEE'] == true && ($personne['DATE_BANNISSEMENT_formatee'] > $now)){
					$statut = "<span class='alert'>Compte supprimé par modérateur.</span>";
					$title = str_replace("<br />", "\n", $personne['RAISON_SUPPRESSION']);
				}else if($personne['PERSONNE_SUPPRIMEE'] == false && ($personne['DATE_BANNISSEMENT_formatee'] > $now)){
					$statut = "<span class='orange'>Compte banni temporairement.</span>";
					$title = str_replace("<br />", "\n", $personne['RAISON_SUPPRESSION'])."\n\nDate de remise en service du compte: ".$personne['DATE_BANNISSEMENT'];
				}else if($personne['PERSONNE_SUPPRIMEE'] == false && ($personne['DATE_BANNISSEMENT_formatee'] <= $now)){
					$statut = "<span class='valide'>Compte débanni le ".$personne['DATE_BANNISSEMENT'].".</span>";
					$title = str_replace("<br />", "\n", $personne['RAISON_SUPPRESSION']);
				}
			}
		?>
			<tr><th colspan="4"><hr /></th></tr>
			<tr>
				<th><span title="<?php echo "ID N°".$personne['ID_PERSONNE']; ?>"><?php echo $personne['PSEUDO']; ?></span></th>
				<th <?php if($personne['TYPE_PERSONNE'] == "Admin" ){echo "class='alert'";} ?>><?php echo $personne['TYPE_PERSONNE']; ?></th>
				<th><span title="<?php echo $title; ?>"><?php echo $statut; ?></span></th>
				<th><a href="<?php echo $oCL_page->getPage('modifier_fiche_membre')."?id_personne=".$personne['ID_PERSONNE']; ?>"><img src="images/voir.jpg" alt="Voir la fiche de <?php echo $personne['PSEUDO']; ?>." title="Voir la fiche de <?php echo $personne['PSEUDO']; ?>." /></a></th>
			</tr>
		<?php
		}
		?>
	</table>
	<br />
	<?php
		if($nb_result[0]['nb_personne'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_personne'], $page_actuelle);
		}
	?>
	<br />
	</fieldset>



<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>
	<h2>Voir les packs:</h2><br />
	<br />
	<br />
	<fieldset><legend class="legend_basique">Liste de tous les packs existants:</legend>
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr class="formulaire">
			<th width="20%" scope="col">Nom:</th>
			<th width="15%" scope="col">Prix:</th>
			<th width="20%" scope="col">Type:</th>
			<th width="15%" scope="col">Durée:</th>
			<th width="10%" scope="col">Activé:</th>
			<th width="20%" scope="col">Fiche détaillée:</th>
		</tr>
		<?php
		foreach($packs as $key=>$pack){
		?>
		<tr><th colspan="6"><hr /></th></tr>
		<tr>
			<th scope="col"><span title="<?php echo "ID N°".$pack['ID_PACK']; ?>"><?php echo $pack['NOM']; ?></th>
			<th scope="col"><?php echo $pack['PRIX_BASE']; ?></th>
			<th scope="col"><?php echo $pack['TYPE_PACK']; ?></th>
			<th scope="col"><?php echo $pack['DUREE']." mois"; ?></th>
			<th scope="col"><?php if($pack['VISIBLE']){echo "<span class='valide'>Oui</span>";}else{echo "<span class='orange'>Non</span>";} ?></th>
			<th scope="col"><a href="<?php echo $oCL_page->getPage('modifier_fiche_pack')."?id_pack=".$pack['ID_PACK']; ?>"><img src="images/voir.jpg" alt="Consulter la fiche détaillée" title="Consulter la fiche détaillée" /></a></th>
		</tr>
		
		<?php
		}
		?>
	</table>
	</fieldset>
	
	
<?php
}else{
# Si l'internaute n'est pas connecté et admin il gicle.
header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
# On charge le nombre de messages non lus. 
if($_SESSION['compte']['connecté'] == true){
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_message.php');

	$oMSG = new MSG();
	$oPCS_message = new PCS_message();

	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	$oMSG->setData('STATUT_MESSAGE', 'Non lu');
	$oMSG->setData('VISIBLE', 1);
	
	$nb_messages_non_lus = $oPCS_message->fx_compter_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG)->getData(1)->fetchAll();
	$nb_messages_non_lus = $nb_messages_non_lus[0]['nb_message'];
}
?>
<ul class="nav">

	<li><a href="index.php" <?php if($_SESSION['page_actuelle'] == "accueil"){echo "class='current'";} ?>>Acceuil</a></li>
	<?php
	if($_SESSION['compte']['connecté'] == false){
	?>
		<li><a href="inscription.php#inscriptionh2" <?php if($_SESSION['page_actuelle'] == "inscription"){echo "class='current'";} ?>>Inscription</a></li>
	<?php
	}
	?>
	<li><a href="<?php echo $oCL_page->getPage('liste_annonce'); ?>" <?php if($_SESSION['page_actuelle'] == "annonce"){echo "class='current'";} ?>>Annonces</a></li>

	<li><a href="#" <?php if($_SESSION['page_actuelle'] == "FAQ"){echo "class='current'";} ?>>FAQ</a></li>

	<li><a href="#" <?php if($_SESSION['page_actuelle'] == "contact"){echo "class='current'";} ?>>Contact</a></li>
	<?php
	if($_SESSION['compte']['connecté'] == true){
	?>
		<li><a href="<?php echo $oCL_page->getPage('messagerie'); ?>" class='<?php if($nb_messages_non_lus > 0){echo "alert";} if($_SESSION['page_actuelle'] == "ma_messagerie"){echo "current";} ?>'>Messagerie <?php if($nb_messages_non_lus > 0){echo "<span class='petit orange'>(".$nb_messages_non_lus.")</span>";} ?></a></li>
	<?php
	}
	?>
</ul>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_menuv_admin.php');

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
?>
<ul>
	<li><h5>Gestion des Membres:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('activer_comptes'); ?>">Activer les comptes non activés.</a>&nbsp;<span title="Il y a <?php echo $nb_comptes_inactifs[0]['nb_comptes']; ?> comptes en attente de modération." class="orange">[<?php echo $nb_comptes_inactifs[0]['nb_comptes']; ?>]</span></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_membre'); ?>">Voir la liste des membres.</a>&nbsp;<span title="Il y a <?php echo $nb_comptes[0]['nb_personne']; ?> utilisateurs au total." class="orange">[<?php echo $nb_comptes[0]['nb_personne']; ?>]</span></li>
	<li><a href="<?php echo $oCL_page->getPage('comptes_supprimes'); ?>">Voir les comptes supprimés.&nbsp;<span class="petit">(Par l'utilisateur)</span></a>&nbsp;<span title="Il y a <?php echo $nb_comptes_supprimes[0]['nb_comptes_supprimes']; ?> comptes supprimés par leur utilisateur." class="orange">[<?php echo $nb_comptes_supprimes[0]['nb_comptes_supprimes']; ?>]</span></li>
	<li>&nbsp;</li>
	<li><a href="<?php echo $oCL_page->getPage('bannir_membre'); ?>">Bannir un membre</a></li>
	<li><a href="#">Voir les IPs.</a></li>
	<li><a href="<?php echo $oCL_page->getPage('changer_rang'); ?>"><span class='alert'>/!\</span> Changer le rang. <span class='alert'>/!\</span></a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Annonces:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_annonces_en_attente'); ?>">Voir toutes les annonces en attente de validation.</a>&nbsp;<span title="Il y a <?php echo $nb_annonces_en_attente[0]['nb_annonce']; ?> annonces en attente." class="orange">[<?php echo $nb_annonces_en_attente[0]['nb_annonce']; ?>]</span></li>
	<li><a href="#">Voir toutes les annonces.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Contrats:</h5></li>
	<li><a href="#">Voir tous les contrats récents/en cours.</a></li>
	<li><a href="#">Voir tous les contrats.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Packs:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_packs'); ?>">Voir tous les packs.</a>&nbsp;<span title="Il y a <?php echo $nb_packs[0]['nb_packs']; ?> packs existants dont <?php echo $nb_packs_inactifs[0]['nb_packs']; ?> pack(s) désactivé(s)." class="orange">[<?php echo $nb_packs[0]['nb_packs']; ?>]</span></li>
	<li><a href="<?php echo $oCL_page->getPage('ajouter_pack'); ?>">Ajouter un pack.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Messages:</h5></li>
	<li><a href="#">Lire les messages pour l'administration.</a></li>
	<li><a href="#">Envoyer un message.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Pubs:</h5></li>
	<li><a href="#">Voir les pubs actuelles.</a></li>
	<li><a href="#">Ajouter une publicité.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion du Parrainage:</h5></li>
	<li><a href="#">Voir les meilleurs parrains.</a></li>
	<li><a href="#">Modifier la page Parrainage.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion globale:</h5></li>
	<li><a href="#">Voir les statistiques du site. <span class="petit">(C.A/connectés/...)</span></a></li>
	<li><a href="#">Modifier la page de la FAQ.</a></li>
	<li><a href="#">Modifier le règlement. <span class="petit">(.pdf)</span></a></li>
	<li><a href="#">Modifier les slides. <span class="petit">(Accueil)</span></a></li>
	<li><a href="#">Faire gagner la tombola.</a></li>
	<li><a href="#"></a></li>
	<li><a href="#"></a></li>
</ul>





<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_menuv_gestion_compte.php');

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
?>
	<h3><a href="<?php echo $oCL_page->getPage('gestion_compte'); ?>">Mon compte:</a></h3><br />
	<br />
	<ul>
		<li><h5>Mes informations personnelles</h5></li>
		<li><a title="Vous pouvez y modifier l'intégralité de vos informations personnelles hormis votre mot de passe." href="<?php echo $oCL_page->getPage('modifier_fiche_perso'); ?>">Modifier mes informations personnelles.</a></li>
		<li><a title="Vous pouvez y modifier votre mot de passe." href="<?php echo $oCL_page->getPage('modifier_mdp'); ?>">Modifier mon mot de passe.</a></li>
		<li>&nbsp;</li>
		<li><a title="Vous pouvez y supprimer votre compte. (Aucun remboursement n'aura lieu.)" href="<?php echo $oCL_page->getPage('supprimer_compte'); ?>">Supprimer mon compte.</a></li>
	</ul>
	<br /><hr /><br />
	<?php
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	?>
		<ul>
			<li><h5>Mes Packs:</h5></li>
			<li><a title="Consultez tous vous achats de packs." href="<?php echo $oCL_page->getPage('historique_achat_pack'); ?>">Historique de mes achats.</a></li>
			<li><a title="Venez découvrir nos Packs !" href="<?php echo $oCL_page->getPage('acheter_pack'); ?>">Acheter un Pack.</a></li>
		</ul>
		<br /><hr /><br />
	<?php
	}
	?>
	<?php
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	?>
	<ul>
		<li><h5>Mes Annonces:</h5></li>
		<li><a href="<?php echo $oCL_page->getPage('historique_annonce')."?rq=toutes"; ?>">Historiques de mes annonces.</a>&nbsp;<span class="orange" title="Vous avez fait <?php echo $toutes_annonces[0]['nb_annonce']; ?> annonces jusqu'à maintenant.">[<?php echo $toutes_annonces[0]['nb_annonce']; ?>]</span></li>
		<li><a href="<?php echo $oCL_page->getPage('historique_annonce')."?rq=futures"; ?>">Mes annonces en cours.</a>&nbsp;<span class="orange" title='Vous avez <?php echo $annonces_futures[0]['nb_annonce']; ?> annonces en cours.
(Annonces dont la date de début est future)'>[<?php echo $annonces_futures[0]['nb_annonce']; ?>]</span></li>
		<li><a href="<?php echo $oCL_page->getPage('creer_annonce'); ?>">Créer une annonce.</a></li>
	</ul>
	<br /><hr /><br />
	<?php
	}
	?>
	<ul>
		<li><h5>Mes Contrats:</h5></li>
		<li><a href="<?php echo $oCL_page->getPage('historique_contrat'); ?>">Historiques de mes contrats.</a></li>
		<li><a href="<?php echo $oCL_page->getPage(''); ?>">Mes contrats en cours.</a></li>
	</ul>
	<br /><hr /><br />
	<?php
	if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	?>
	<ul>
		<li><h5>Mes Prestations:</h5></li>
		<li><a href="<?php echo $oCL_page->getPage(''); ?>">Historique de mes prestations.</a></li>
		<li><a href="<?php echo $oCL_page->getPage(''); ?>">Mes prestations prévues.</a></li>
	</ul>
	<br /><hr /><br />
	<?php
	}
	?>
	<ul>
		<li><h5>Ma Messagerie:</h5></li>
		<li><a href="<?php echo $oCL_page->getPage(''); ?>">Mes messages reçus.</a></li>
	</ul>
	<br /><hr /><br />
	<?php
	if($_SESSION['pack']['PARRAINAGE_ACTIVE']){
	?>
	<ul>
		<li><h5>Parrainage:</h5></li>
		<li><a href="<?php echo $oCL_page->getPage('filleuls'); ?>">Mes filleuls.</a></li>
		<li><a href="<?php echo $oCL_page->getPage('lien_parrainage'); ?>">Obtenir mon lien de parrainage.</a></li>
	</ul>
	<br /><hr /><br />
	<?php
	}
	?>



<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	if($id_message_ok){
?>
	<h2><?php echo $message[0]['TITRE'] ?></h2><br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique"></legend>
		<br />
		<b class='valide'>Date de réception:</b> <?php echo $message[0]['DATE_ENVOI'] ?><br />
		<br />
		<b class='valide'>Date de lecture:</b> <?php echo $message[0]['DATE_LECTURE'] ?><br />
		<br />
		<b class='valide'>Expéditeur:</b> <a href="<?php echo $oCL_page->getPage('')."?id_personne=".$message[0]['EXPEDITEUR']; ?>"><?php echo $expediteur[0]['PSEUDO'] ?></a>
	<br />
	<br />
	<fieldset class="padding_LR">
	<center><b><u class='valide'>Corps du message:</u></b><br /></center><br />
	<br />
	<?php echo $message[0]['CONTENU'] ?><br />
	<br />
	</fieldset>
	<br />
	<center>
		<a href="<?php echo $oCL_page->getPage('messagerie'); ?>">Ma messagerie</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $oCL_page->getPage('supprimer_message'); ?>"><img src="<?php echo $oCL_page->getImage('supprimer_personne_petit'); ?>" alt="Supprimer le message" title="Supprimer le message" /></a>
	</center>
	</fieldset>

<?php
	}else{
		if($_SESSION['message']['message_affiche'] == false){
			echo $_SESSION['message']['message'];
			$_SESSION['message']['message_affiche'] = true;
		}
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>

	<h2>Ma messagerie:</h2><br />
	<br />
	
	<?php
		if($nb_result[0]['nb_message'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_message'], $page_actuelle);
		}
	?>
	
	<table width="100%">
		<tr class="formulaire">
			<th width="40%" scope="col">Titre</th>
			<th width="20%" scope="col">Expéditeur</th>
			<th width="20%" scope="col">Date de <br />réception</th>
			<th width="15%" scope="col">&Eacute;tat</th>
			<th  width="5%"scope="col">&nbsp;</th>
		</tr>
		<?php
		foreach($messages as $key=>$message){
		?>
		<tr><th colspan="5"><hr /></th></tr>
		<tr height="60px">
			<th title="Lire le message" scope="row"><a href="<?php echo $oCL_page->getPage('message')."?id_message=".$message['ID_MESSAGE']; ?>"><?php echo $message['TITRE']; ?></a></th>
			<th><?php echo $message['PSEUDO']; ?></th>
			<th title="<?php echo $message['DATE_ENVOI']; ?>"><?php echo $message['DATE_ENVOI_simple']; ?></th>
			<th class="<?php if($message['STATUT_MESSAGE'] == "Non lu"){echo "orange";}else if($message['STATUT_MESSAGE'] == "Répondu"){echo "valide";} ?>"><?php echo $message['STATUT_MESSAGE']; ?></th>
			<th><input type="checkbox" name="<?php echo $message['ID_MESSAGE']; ?>" id="" /></th>
		</tr>
		<?php
		}
		?>
	</table>
	<br />
	<?php
		if($nb_result[0]['nb_message'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_message'], $page_actuelle);
		}
	?>

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne est connectée.
if($_SESSION['compte']['connecté'] == true){
?>

	<h2>Modification d'une annonce:</h2><br />
	<br />
	<?php
	require_once('include_form_ajouter_modifier_annonce.php');
	?>


<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>

	<h2>Modification d'une annonce:</h2><br />
	<br />
	<?php
	require_once('include_form_ajouter_modifier_annonce.php');
	?>


<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
?>
	<h2>Modification de la fiche personnelle d'un membre:</h2><br />
	<br />

<?php
	if($ID_PERSONNE_ok == 1){
	# L'id_personne transmis est correct, on affiche les données récupérées si elles existent.
	
		if(!empty($personne[0]['ID_PERSONNE'])){
			# Si l'ID_PERSONNE de la personne fournie en GET n'est pas vide c'est que cette personne existe.
			if($personne[0]['TYPE_PERSONNE'] != "Admin"){
				echo "<span class='orange'>/!\ Le code HTML sera automatiquement supprimé des données. /!\</span><br /><br />";
				require_once('include_form_modifier_fiche_membre.php');
			?>
				
				<br />
				<br />
				<?php
				if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				?>
					<hr />
					<center><h6>Récapitulatif des IP de connection:</h6></center><br />
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<tr>
							<th scope="col">Date de connexion:</th>
							<th scope="col">IP:</th>
							<th scope="col">Cookie:</th>
							<th scope="col">Cookie détruit:</th>
						</tr>
						<tr><th colspan="4"><hr /></th></tr>
						<?php
						while($ip = $ip_personne->fetch(PDO::FETCH_ASSOC)){
						?>
						<tr <?php if($ip['IP_COOKIE'] != $ip['ID_IP']){echo "class='alert'";} ?>>
							<th><?php echo $ip['DATE_CONNEXION']; ?></th>
							<th><?php echo $ip['ID_IP']; ?></th>
							<th><?php echo $ip['IP_COOKIE']; ?></th>
							<th><?php if($ip['COOKIE_DETRUIT'] == true){echo "<span class='orange'>Oui</span>";}else{echo "Non";} ?></th>
						</tr>
						<tr><th colspan="4"><hr /></th></tr>
						<?php
						}# Fin du while() d'affichage des IPs.
						?>
					</table>
			<?php
				}
			#$ip_personne
			}else{
				echo "<span class='orange'>Vous ne pouvez pas modifier les informations d'un administrateur.</span>";
			}
		}else{
			echo "<span class='alert'>Erreur: La requête n'a retourné aucun résultat. Il n'y a pas de membre possédant cet ID.</span>";
		}
	}else{
		echo "<span class='alert'>Erreur: L'id_personne transmit est incorrect.</span>";
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	
	# Si on a bien reçu un id_pack correct.
	if($ID_PACK_ok){
	?>
	<h2>Modifier un pack:</h2><br />
	<br />
	<?php 
		require_once('include_form_modifier_fiche_pack.php');
	?>
	


	<?php	
	}else{
		echo "L'id_pack reçu n'est pas correct.";
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>
	<h2>Mes informations personnelles:</h2><br />
	<br />
	<?php
	require_once('include_form_modifier_fiche_membre.php');
	?>
	
<?php				
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
?>

	<h2>Modification du mot de passe:</h2><br />
	<br />
	<?php
		if(isset($_SESSION['modifier_mdp']['message']) && $_SESSION['modifier_mdp']['message_affiche'] == false){
			echo $_SESSION['modifier_mdp']['message'];
			$_SESSION['modifier_mdp']['message_affiche'] = true;
		}
	?>
	<br />
	<form class="formulaire" action="script_modifier_mdp.php" method="post" name="form_modifier_mdp" id="form_modifier_mdp" >
		Rentrez votre mot de passe actuel:<br />
		<input type="password" name="form_modifier_mdp_ancien_mdp" id="form_modifier_mdp_ancien_mdp" /><br />
		<br />
		Rentrez votre nouveau mot de passe:<br />
		<input type="password" name="form_modifier_mdp_nouveau_mdp" id="form_modifier_mdp_nouveau_mdp" /><br />
		<br />
		Répétez votre nouveau mot de passe:<br />
		<input type="password" name="form_modifier_mdp_nouveau_mdp_bis" id="form_modifier_mdp_nouveau_mdp_bis" /><br />
		<br />
		<span class="alert fright">* Tous les champs sont obligatoires.</span><br />
		<br />
		<center>
			<input type="image" src="images/valider.png" alt="Valider" title="Valider" name="btn_form_modifier_mdp_valider" id="btn_form_modifier_mdp_valider" />
		</center>
	</form>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><h2>LES NOUVEAUTEES</h2>
<ul class="news-list">
	<li>
		<img alt="" src="images/1page-img12.jpg" />
		<h4>Dimanche 28 Aout 2011, 12h35 <br />
		<a href="#">Sortie officielle du site LIVEANIM.COM</a></h4>
		<p>Ici présentation de la sortie officielle de liveanim.com. Ici présentation de la sortie officielle de liveanim.com.Ici présentation de la sortie officielle de liveanim.com.Ici présentation de la sortie officielle de liveanim.com.</p>
		<a href="#" class="link3">Voire la suite...</a><span>25 commentaires</span>
	</li>
	<li>
		<img alt="" src="images/1page-img12.jpg" />
		<h4>Dimanche 28 Aout 2011, 12h35 <br />
		<a href="#">Sortie officielle du site LIVEANIM.COM</a></h4>
		<p>Ici présentation de la sortie officielle de liveanim.com. Ici présentation de la sortie officielle de liveanim.com.Ici présentation de la sortie officielle de liveanim.com.Ici présentation de la sortie officielle de liveanim.com.</p>
		<a href="#" class="link3">Voire la suite...</a><span>25 commentaires</span>
	</li>
	<li>
		<img alt="" src="images/1page-img12.jpg" />
		<h4>Dimanche 28 Aout 2011, 12h35 <br />
		<a href="#">Sortie officielle du site LIVEANIM.COM</a></h4>
		<p>Ici présentation de la sortie officielle de liveanim.com. Ici présentation de la sortie officielle de liveanim.com.Ici présentation de la sortie officielle de liveanim.com.Ici présentation de la sortie officielle de liveanim.com.</p>
		<a href="#" class="link3">Voire la suite...</a><span>25 commentaires</span>
	</li>
	<li>
		<img alt="" src="images/1page-img12.jpg" />
		<h4>Dimanche 28 Aout 2011, 12h35 <br />
		<a href="#">Sortie officielle du site LIVEANIM.COM</a></h4>
		<p>Ici présentation de la sortie officielle de liveanim.com. Ici présentation de la sortie officielle de liveanim.com.Ici présentation de la sortie officielle de liveanim.com.Ici présentation de la sortie officielle de liveanim.com.</p>
		<a href="#" class="link3">Voire la suite...</a><span>25 commentaires</span>
	</li>
</ul>
<a href="#" class="link1">Voire toutes les news</a><div class="section">
	<h3>Nouveaux Artistes</h3>
	<ul class="members-list">
		<li><a href="#"><img alt="" src="images/1page-img5.jpg" />Michel P.</a></li>
		<li><a href="#"><img alt="" src="images/1page-img5.jpg" />Michel P.</a></li>
		<li><a href="#"><img alt="" src="images/1page-img5.jpg" />Michel P.</a></li>
		<li><a href="#"><img alt="" src="images/1page-img5.jpg" />Michel P.</a></li>
		<li><a href="#"><img alt="" src="images/1page-img5.jpg" />Michel P.</a></li>
		<li><a href="#"><img alt="" src="images/1page-img5.jpg" />Michel P.</a></li>
	</ul>
	<a href="#" class="link1">Voir tous les artistes ></a>
</div><div class="padding_LR">
	<div id="panel" style="position:relative;">
	<!-- Div contenant les informations du trajet. -->
	</div>
</div><h3>PARTENAIRES</h3>
<center>
	<a href="http://www.penduquiz.com/inscription_gratuite_jeux_flash.php?parrain=membre1925" title="Jouer gratuitement au quiz et au pendu"><img src="http://www.penduquiz.com/bannieres/bann250250.gif" alt="Pendu Quiz" border="0"/></a> <br/><br/>
</center><?php
if(!isset($_SESSION)){
	session_start();
}
?>
<center>	
	<?php
	if(isset($_SESSION['pack']['activé']) && $_SESSION['pack']['PUBS'] != false){
	?>
		<br/>  
		<a href="http://www.penduquiz.com/inscription_gratuite_jeux_flash.php?parrain=membre1925" title="Jouer gratuitement au quiz et au pendu"><img src="http://www.penduquiz.com/bannieres/bann728901.gif" alt="Pendu Quiz" border="0"/></a>
	<?php
	}
	?>
</center><?php
if(!isset($_SESSION)){
	session_start();
}
?>
<br/> 
<center>	
	<?php
	if(isset($_SESSION['pack']['activé']) && $_SESSION['pack']['PUBS'] != false){
	?>
		<a href="http://www.penduquiz.com/inscription_gratuite_jeux_flash.php?parrain=membre1925" title="Jouer gratuitement au quiz et au pendu"><img src="http://www.penduquiz.com/bannieres/bann46860.gif" alt="Pendu Quiz" border="0"/></a><br/><br/>
	<?php
	}
	?>
</center><?php
$_SESSION['page_actuelle'] = "recuperation_mdp";

require_once('couche_metier/CL_page.php');
$oCL_page = new CL_page();

if(isset($_SESSION['compte']['connecté']) && $_SESSION['compte']['connecté'] == true){
	 ?>
	 Vous êtes déjà connecté. Vous ne pouvez pas effectuer une récupération de mot de passe en étant connecté.
	 
	 <?php
	 header ("Refresh: 1;URL=".$oCL_page->getPage('accueil'));
	
}else{
?>
<h2>Récupération de mot de passe:</h2>

<h4><u>Comment ça marche ?</u></h4>
Le principe de récupération de votre mot de passe est simple.<br />
Vous devez entrer votre pseudo et votre adresse e-mail dans les cases indiquées.<br />
Le système vérifiera ces informations et si elles sont exactes alors un e-mail vous sera envoyé avec votre mot de passe.<br /><br />

<?php
if(isset($_SESSION['récupération']['message'])){
	if($_SESSION['récupération']['message_affiche'] == false){
		echo $_SESSION['récupération']['message'];
		$_SESSION['récupération']['message_affiche'] = true;
	}
}

?>
<br />
<fieldset class="formulaire"><legend class="legend_basique">Formulaire de récupération de votre mot de passe:</legend>
	<form action="script_recuperation_mdp.php" method="post" name="form_recuperation_mdp" id="form_recuperation_mdp">
		<br />
		<span class="alert">*</span><label for="form_recuperation_mdp_pseudo">Pseudo:</label><br />
		<input type="text" name="form_recuperation_mdp_pseudo" id="form_recuperation_mdp_pseudo" class="my_input" /><br  />
		<br />
		
		<span class="alert">*</span><label for="form_recuperation_mdp_email">E-mail:</label><br />
		<input type="text" name="form_recuperation_mdp_email" id="form_recuperation_mdp_email" class="my_input" /><br  />
		<br />
		
		<center>
			<input type="image" src="images/valider.jpg" name="btn_form_recuperation_mdp_valider" id="btn_form_recuperation_mdp_valider" class="art-button" value="Valider" />
		</center>
		</form>
</fieldset>
<?php
}
?><?php
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
?>
	<div id="loopedSlider">	
		<div class="container">
			<div class="slides">
				<div class="slide"><a href="<?php echo $oCL_page->getPage('administration'); ?>"><img alt="Admin ON !" src="images/slide_administration.gif" /></a><strong>ADMINISTRATION</strong></div>
				<div class="slide"><a href="<?php echo $oCL_page->getPage('administration'); ?>"><img alt="Admin ON !" src="images/slide_administration.gif" /></a><strong>ADMINISTRATION</strong></div>
				<div class="slide"><a href="<?php echo $oCL_page->getPage('administration'); ?>"><img alt="Admin ON !" src="images/slide_administration.gif" /></a><strong>ADMINISTRATION</strong></div>
				<div class="slide"><a href="<?php echo $oCL_page->getPage('administration'); ?>"><img alt="Admin ON !" src="images/slide_administration.gif" /></a><strong>ADMINISTRATION</strong></div>
				<div class="slide"><a href="<?php echo $oCL_page->getPage('administration'); ?>"><img alt="Admin ON !" src="images/slide_administration.gif" /></a><strong>ADMINISTRATION</strong></div>
				
			</div>
		</div>
	</div>
<?php
}else{
?>
	<div id="loopedSlider">	
		<div class="container">
			<div class="slides">
				<div class="slide"><a href="#"><img alt="" src="images/slide1.jpg" /></a><strong>TEST SLIDER</strong></div>
				<div class="slide"><a href="#"><img alt="" src="images/slide1.jpg" /></a><strong>TEST SLIDER</strong></div>
				<div class="slide"><a href="#"><img alt="" src="images/slide1.jpg" /></a><strong>TEST SLIDER</strong></div>
				<div class="slide"><a href="#"><img alt="" src="images/slide1.jpg" /></a><strong>TEST SLIDER</strong></div>
				<div class="slide"><a href="#"><img alt="" src="images/slide1.jpg" /></a><strong>TEST SLIDER</strong></div>
			</div>
		</div>
	</div>
<?php
}
?><?php
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
?>
	<h2>Supprimer mon compte:</h2><br />
	<br />
	<?php
	if(isset($_SESSION['supprimer_compte']['message']) && $_SESSION['supprimer_compte']['message_affiche'] == false){
		echo $_SESSION['supprimer_compte']['message'];
		$_SESSION['supprimer_compte']['message_affiche'] = true;
	}	
	?>
	Voici le formulaire de suppression du compte.<br />
	Afin de satisfaire toujours au mieux notre clientèle nous vous demandons d'indiquer la raison de la suppression.<br />
	Cette information sera étudiée afin de savoir si des améliorations sont nécessaires et envisageables.<br />
	<br />
	À noter que votre compte ne sera pas immédiatement supprimé. Nous conservons l'intégralité de vos informations pour une durée de deux mois.<br />
	Vos informations -quelles qu'elles soient- ne seront pas divulguées à des tiers ni utilisées autrement que dans un but d'amélioration de nos services et de statistiques.<br />
	<br />
	Si vous souhaitez toutefois supprimer définitivement vos informations <u title='Seules les informations suivantes seront immédiatement supprimées. Nom, prénom, date de naissance, photo, civilité, email, ville, adresse, code postal, téléphones.'>personnelles</u> vous pouvez cocher la case correspondante et elles seront supprimées immédiatement.<br />
	<br />
	<br />
	<fieldset><legend class="legend_basique">Suppression du compte:</legend><br />
		<br />
		<form class="formulaire" method="post" action="script_supprimer_compte.php" name="form_supprimer_compte" id="form_supprimer_compte" >
			Entrez votre adresse e-mail:<br />
			<input type="text" name="form_supprimer_compte_email" id="form_supprimer_compte_email" /><br />
			<br />
			Expliquez les raisons qui vous poussent à supprimer votre compte:<br />
			<textarea name="form_supprimer_compte_raison" id="form_supprimer_compte_raison" cols="90" rows="10" ></textarea><br />
			<br />
			Entrez votre mot de passe:<br />
			<input type="password" name="form_supprimer_compte_mdp" id="form_supprimer_compte_mdp" /><br />
			<br />
			Je souhaite supprimer immédiatement mes informations <u title='Seules les informations suivantes seront immédiatement supprimées. Nom, prénom, date de naissance, photo, civilité, email, ville, adresse, code postal, téléphones.'>personnelles</u>.&nbsp;<select name="form_supprimer_compte_infos_perso" id="form_supprimer_compte_infos_perso"><option value="0" selected="selected">Non</option><option value="1">Oui</select><br />
			<br />
			<span class="fright alert">Tous les champs sont obligatoires.&nbsp;&nbsp;</span><br />
			<br />
			<center>
				<input type="image" src="images/valider.png" alt="Supprimer mon compte" title="Supprimer mon compte" name="btn_form_supprimer_compte_valider" id="btn_form_supprimer_compte_valider" />
			</center>
		</form>
	</fieldset>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><div class="section">
	<h3>Tous les Artistes</h3>
	<ul class="adv-menu">
		<li><a href="#"><img alt="" src="images/imgmenu.png" /> DJ's</a></li>
		<li><a href="#"><img alt="" src="images/imgmenu.png" /> Chanteurs</a></li>
		<li><a href="#"><img alt="" src="images/imgmenu.png" /> Animateurs</a></li>
		<li><a href="#"><img alt="" src="images/imgmenu.png" /> Groupes</a></li>
		<li><a href="#"><img alt="" src="images/imgmenu.png" /> Danseurs</a></li>
		<li><a href="#"><img alt="" src="images/imgmenu.png" /> Magiciens</a></li>
		<li><a href="#"><img alt="" src="images/imgmenu.png" /> Clown</a></li>
		<li><a href="#"><img alt="" src="images/imgmenu.png" /> Humouristes</a></li>
		<li><a href="#"><img alt="" src="images/imgmenu.png" /> Présentateurs</a></li>
		<li><a href="#"><img alt="" src="images/imgmenu.png" /> Divers Artistes</a></li>
	</ul>
</div><?php
ob_start();
?>
<title>Accueil</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}
# On définit la page courante:
$_SESSION['page_actuelle'] = "accueil";

# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
require_once('include_header.php');
?>

<body id="page1" onload="new ElementMaxHeight();">
   <div id="main">
      <!-- header -->
      <div id="header">
      	<div class="wrapper">
         	<div class="col-1">				
				<?php
					# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
					require_once('include_connexion.php');
				?>
				<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
            </div>
            <div class="col-2">
            	<?php
					/* Partie qui peut prendre les include: 
							include_menu_principal.php
							include_slider.php
					*/
					require_once('include_menu_principal.php');
					require_once('include_slider.php');
				?>
            </div>
        </div>
    </div>
    <div id="content">
      	<div class="wrapper">
         	<div class="aside">
            	<div class="indent">
					<?php
					/* Partie qui peut prendre les include: 
							include_annonces_gold.php
							include_artiste.php
							include_partenaire.php
							include_nouveaux_artistes.php
							include_dernieres_annonces.php
					*/
						require_once('include_types_artistes.php');
						require_once('include_partenaire.php');
						require_once('include_nouveaux_artistes.php');
						require_once('include_dernieres_annonces.php');
					?>					
			   </div>
            </div>
			<?php
				require_once('include_pub_haut.php');
			?>	
            <div class="mainContent maxheight">
            	<div class="indent">
					<?php
						/* Partie qui peut prendre les include: 
								include_artistes_premium.php
								include_nouveautees.php
						*/
						if(isset($_SESSION['connexion']['message']) && $_SESSION['connexion']['message_affiche'] == false){
							echo $_SESSION['connexion']['message'];
							$_SESSION['connexion']['message_affiche'] = true;
						}else{
							require_once('include_artistes_premium.php');
							require_once('include_nouveautees.php');
						}
					?>	
					
						
					</div>
				</div>
			</div>
		</div>
	    <?php
			/* Partie qui peut prendre les include: 
					include_pub_bas.php
					include_footer.php
			*/
			require_once('include_pub_bas.php');
			require_once('include_footer.php');
		?>
   </div>
   <script type="text/javascript"> Cufon.now(); </script>
</body>
</html><?php
ob_start();
?>
<title>Inscription</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On définit la page courante:
$_SESSION['page_actuelle'] = "inscription";

# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
require_once('include_header.php');
?>

<body id="page1" onload="new ElementMaxHeight();">
   <div id="main">
      <!-- header -->
      <div id="header">
      	<div class="wrapper">
         	<div class="col-1">				
				<?php
					# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
					require_once('include_connexion.php');
				?>
				<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
            </div>
            <div class="col-2">
            	<?php
					/* Partie qui peut prendre les include: 
							include_menu_principal.php
							include_slider.php
					*/
					require_once('include_menu_principal.php');
					require_once('include_slider.php');
				?>
            </div>
        </div>
    </div>
    <div id="content">
      	<div class="wrapper">
         	<div class="aside">
            	<div class="indent">
					<?php
					/* Partie qui peut prendre les include: 
							include_annonces_gold.php
							include_artiste.php
							include_partenaire.php
							include_nouveaux_artistes.php
							include_dernieres_annonces.php
					*/
						require_once('include_annonces_gold.php');
						require_once('include_dernieres_annonces.php');
						require_once('include_partenaire.php');
					?>					
			   </div>
            </div>
			<?php
				require_once('include_pub_haut.php');
			?>	
            <div class="mainContent maxheight">
            	<div class="indent">
					<?php
						/* Partie qui peut prendre les include: 
								include_artistes_premium.php
								include_nouveautees.php
						*/
						require_once('include_inscription.php');
					?>	
					
						
					</div>
				</div>
			</div>
		</div>
	    <?php
			/* Partie qui peut prendre les include: 
					include_pub_bas.php
					include_footer.php
			*/
			require_once('include_pub_bas.php');
			require_once('include_footer.php');
		?>
   </div>
   <script type="text/javascript"> Cufon.now(); </script>
</body>
</html><?php
ob_start();
?>
<title>Mon lien de parrainage</title>
</head>
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
		
	require_once('script_prechargement_lien_parrainage.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "lien_parrainage";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_lien_parrainage.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Liste des annonces</title>
</head>
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
		
require_once('script_prechargement_liste_annonce.php');

# On définit la page courante:
$_SESSION['page_actuelle'] = "liste_annonce";

# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
require_once('include_header.php');
?>
<body id="page1" onload="new ElementMaxHeight();">
	<script type="text/javascript" src="js/liste_annonce.js"></script>
   <div id="main">
	  <!-- header -->
	  <div id="header">
		<div class="wrapper">
			<div class="col-1">				
				<?php
					# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
					require_once('include_connexion.php');
				?>
				<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
			</div>
			<div class="col-2">
				<?php
					/* Partie qui peut prendre les include: 
							include_menu_principal.php
							include_slider.php
					*/
					require_once('include_menu_principal.php');
					require_once('include_slider.php');
				?>
			</div>
		</div>
	</div>
	<div id="content">
		<div class="wrapper">
			<div class="aside">
				<div class="indent">
					<?php
					/* Partie qui peut prendre les include: 
							include_annonces_gold.php
							include_artistes_premium.php
							include_partenaire.php
							include_nouveaux_artistes.php
							include_dernieres_annonces.php
					*/
						require_once('include_dernieres_annonces.php');
						require_once('include_annonces_gold.php');
						require_once('include_nouveaux_artistes.php');
						
					?>					
			   </div>
			</div>
			<div class="mainContent maxheight">
				<div class="indent">
					<?php
						/* Partie qui peut prendre les include: 
								include_artistes_premium.php
								include_nouveautees.php
						*/
						require_once('include_liste_annonce.php');
					?>	
					
						
					</div>
				</div>
			</div>
		</div>
		<?php
			/* Partie qui peut prendre les include: 
					include_pub_bas.php
					include_footer.php
			*/
			require_once('include_pub_bas.php');
			require_once('include_footer.php');
		?>
   </div>
   <script type="text/javascript"> Cufon.now(); </script>
</body>
</html>
<?php
ob_start();
?>
<title>Admin: Annonces en attentes</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_liste_annonces_en_attente.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "liste_annonces_en_attente";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_liste_annonces_en_attente.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Liste des membres</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_liste_membre.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "liste_membre";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_liste_membre.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Liste des packs</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_liste_packs.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "liste_packs";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_liste_packs.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Maintenance en cours</title>
</head>
<?php
require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							
						?>	
						<h3>Maintenance:</h3>
						Le site est actuellement en maintenance, il est totalement indisponible pour toute la durée de la maintenance.<br />
						Cette maintenance peut-être due à diverses raisons, mise à jour du site, réparation ou simplement une sauvegarde des données.<br />
						<br />
						Nous vous prions de patienter et nous excusons de ce désagrément.<br />
						<br />
						L'équipe LiveAnim.
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
			?>
	   </div>
	</body>
	</html>
<?php
ob_start();
?>
<title>Lecture d'un message</title>
</head>
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
		
	require_once('script_prechargement_message.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "message";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_message.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Ma messagerie</title>
</head>
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
		
	require_once('script_prechargement_messagerie.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "messagerie";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_messagerie.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Modification d'une annonce</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_modifier_fiche_annonce.php');

# On vérifie que la personne est connectée .
if($_SESSION['compte']['connecté'] == true){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "modifier_fiche_annonce";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_modifier_fiche_annonce.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Modification d'une annonce</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_modifier_fiche_annonce_by_admin.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "modifier_fiche_annonce_by_admin";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>
<script type="text/javascript" src="js/modifier_annonce_by_admin.js"></script>
	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_modifier_fiche_annonce_by_admin.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Modification d'un utilisateur</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_modifier_fiche_membre.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "modifier_fiche_membre";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_modifier_fiche_membre.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Admin: Modification d'un pack</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_modifier_fiche_pack.php');

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "modifier_fiche_pack";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_modifier_fiche_pack.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Modification de mon profil</title>
</head>
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
	
	require_once('script_prechargement_modifier_fiche_perso.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "modifier_fiche_perso";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_modifier_fiche_perso.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Modification de mon mot de passe</title>
</head>
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
		
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "modifier_mdp";

	# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
	require_once('include_header.php');
?>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_gestion_compte.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_modifier_mdp.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_pub_bas.php');
				require_once('include_footer.php');
			?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
ob_start();
?>
<title>Récupération de mot de passe</title>
</head>
<?php
if(!isset($_SESSION)){
	session_start();
}
# On définit la page courante:
$_SESSION['page_actuelle'] = "recuperation_mdp";

# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
require_once('include_header.php');
?>

<body id="page1" onload="new ElementMaxHeight();">
   <div id="main">
      <!-- header -->
      <div id="header">
      	<div class="wrapper">
         	<div class="col-1">	
				<?php
					# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
					require_once('include_connexion.php');
				?>			
				<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
            </div>
            <div class="col-2">
            	<?php
					/* Partie qui peut prendre les include: 
							include_menu_principal.php
							include_slider.php
					*/
					require_once('include_menu_principal.php');
					require_once('include_slider.php');
				?>
            </div>
        </div>
    </div>
    <div id="content">
      	<div class="wrapper">
         	<div class="aside">
            	<div class="indent">
					<?php
					/* Partie qui peut prendre les include: 
							include_annonces_gold.php
							include_artiste.php
							include_partenaire.php
							include_nouveaux_artistes.php
							include_dernieres_annonces.php
					*/
						require_once('include_types_artistes.php');
						require_once('include_partenaire.php');
						require_once('include_nouveaux_artistes.php');
						require_once('include_dernieres_annonces.php');
					?>					
			   </div>
            </div>
			<?php
				require_once('include_pub_haut.php');
			?>	
            <div class="mainContent maxheight">
            	<div class="indent">
					<?php
						/* Partie qui peut prendre les include: 
								include_artistes_premium.php
								include_nouveautees.php
						*/
						require_once('include_recuperation_mdp.php');
					?>	
					
						
					</div>
				</div>
			</div>
		</div>
	    <?php
			/* Partie qui peut prendre les include: 
					include_pub_bas.php
					include_footer.php
			*/
			require_once('include_pub_bas.php');
			require_once('include_footer.php');
		?>
   </div>
   <script type="text/javascript"> Cufon.now(); </script>
</body>
</html><?php 
# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}
if(isset($_POST['payment_status'])){
	//permet de traiter le retour ipn de paypal
	$email_account = $oCL_page->getConfig('compte_credite');
	$req = 'cmd=_notify-validate';

	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$req .= "&$key=$value";
	}

	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
	$item_name = $_POST['item_name'];
	$item_number = $_POST['item_number'];
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	$transaction_subject = $_POST['transaction_subject'];
	parse_str($_POST['custom'],$custom);

	if (!$fp) {
		file_put_contents('aalog.txt', print_r('Vient pas de la bonne adresse', true));
	} else {
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
		if (strcmp ($res, "VERIFIED") == 0) {
			// vérifier que payment_status a la valeur Completed
			if ( $payment_status == "Completed") {
				   if ( $email_account == $receiver_email) {
						# On récupère nos données et on les met dans le tableau associatif $datas.
						$tab_datas = explode('&', $transaction_subject);
						$datas = Array();
						
						foreach($tab_datas as $key=>$data){
							$tab_datas[$key] = explode('=', $data);
							$datas[$tab_datas[$key][0]] = $tab_datas[$key][1];
						}
						
						# On charge les informations de notre client via id_personne.
						require_once('couche_metier/MSG.php');
						require_once('couche_metier/PCS_personne.php');
						require_once('couche_metier/PCS_pack.php');
						require_once('couche_metier/CL_date.php');

						$oMSG = new MSG();
						$oPCS_personne = new PCS_personne();
						$oPCS_pack = new PCS_pack();
						$oCL_date = new CL_date();
						
						# On crée nos variables 
						$nb_error = 0;
						$message = "";
						
						$oMSG->setData('ID_PERSONNE', $datas['id_personne']);
						
						$personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
						
						# On charge les informations du pack via id_pack.
						$oMSG->setData('ID_PACK', $datas['id_pack']);
						
						$pack = $oPCS_pack->fx_recuperer_pack_by_ID_PACK($oMSG)->getData(1)->fetchAll();
						
						
						
						# On calcule la réduction auquel est soumis le pack.
						if($pack[0]['VISIBLE'] == true){
						
							$PRIX = $pack[0]['PRIX_BASE'];
							$MAX = $pack[0]['GAIN_PARRAINAGE_MAX'];# Le maximum de réduction auquel est soumis le pack.
							$REDUCTION = $personne[0]['REDUCTION'];
							
							if($pack[0]['SOUMIS_REDUCTIONS_PARRAINAGE'] == true){
								# Le pack est soumis à des réductions.
								
								# Trois cas possibles, soit la réduction possédée est inférieure au MAX soit elle est égale soit elle est supérieure.
								if($REDUCTION >= $MAX){
									# Si la réduction possédée est supérieure ou égale au MAX de réduction possible.
									$REDUCTION = $MAX;# On met le taux de réduction au maximum.
								}
								
								# Si la réduction n'est pas négative ou nulle on l'effectue.
								if($REDUCTION > 0){
									$pack[0]['nouvelle_reduction'] = $REDUCTION;# On stocke la réduction du pack.
									$pack[0]['economie'] = round($PRIX*($REDUCTION/100), 2);# On calcule l'économie réalisée.
									$pack[0]['nouveau_prix'] = round($PRIX-($PRIX*($REDUCTION/100)), 2);# Le nouveau prix est égal à l'ancien prix multiplié par la réduction.
									$pack[0]['beneficie_reduction'] = true;
								}else{
									$pack[0]['nouveau_prix'] = $PRIX;
									$pack[0]['beneficie_reduction'] = false;
								}
							}else{
								# le pack n'est pas soumis à des réductions.
								$pack[0]['beneficie_reduction'] = false;
								$pack[0]['nouveau_prix'] = $PRIX;
							}
						}else{
							$nb_error++;
							$message.= "&error".$nb_error."=Pack non soumis aux réductions parrainage ou non visible.";
						}
						
						if($pack[0]['beneficie_reduction']){
							# Si la personne bénéficie d'une quelconque réduction.
							$message.= "&beneficie_reduction=1";
							
						}else{
							# Si la personne ne bénéficie pas de réduction.
							$message.= "&beneficie_reduction=0";
						}
						
						# On vérifie que le prix appliqué est bien conforme au prix calculé.
						if($pack[0]['nouveau_prix'] != $payment_amount){
							# Le prix payé est différent de celui calculé.
							$nb_error++;
							$message.= "&error".$nb_error."=Prix payé et prix calculé différents.";
						}
					
						
						# On vérifie la monnaie utilisée.
						if($_POST['mc_currency'] != "EUR"){
							# La monnaie utilisée est différente de l'euro.
							$nb_error++;
							$message.= "&error".$nb_error."=Monnaie utilisée différente de l'euro. (".$_POST['mc_currency'].")";
						}
					
						
						$now = date('Y-m-d H:i:s');
						$now_concat = date("Y-m-d_H")."h".date("i")."mn".date("s");
						$DATAS_PAYPAL = "item_name=".$item_name."&item_number=".$item_number."&payment_status=".$payment_status."&payment_amount=".$payment_amount.
						"&payment_currency=".$payment_currency."&txn_id=".$txn_id."&receiver_email=".$receiver_email."&payer_email=".$payer_email.
						"&payment_date=".$_POST['payment_date']."&pending_reason=".$_POST['pending_reason'].
						"&mc_currency=".$_POST['mc_currency']."&custom=".$transaction_subject."&nb_error=".$nb_error.$message;
						
						if($nb_error == 0){
							# On valide l'achat.
							
							# On calcule la nouvelle réduction.
							if($pack[0]['beneficie_reduction']){
								# On doit modifier la réduction car il en a bénéficié.
								$pack_reduction = $pack[0]['nouvelle_reduction'];
								$personne[0]['nouvelle_reduction'] = $personne[0]['REDUCTION'] - $REDUCTION;
								$a_beneficie_reduction = true;
							}else{
								# Inutile de modifier la réduction.
								$pack_reduction = 0;
								$personne[0]['nouvelle_reduction'] = $REDUCTION;
								$a_beneficie_reduction = false;
							}
							# On calcule la date de fin de validité.
							
							# On récupère le pack dont la date de fin est supérieure à maintenant. 
							$oMSG->setData('limit', 'LIMIT 0,1');
		
							$pack_personne = $oPCS_pack->fx_recuperer_dernier_pack_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
							
							# On met à jour la réduction de la personne si elle en a bénéficiée.
							if($pack[0]['beneficie_reduction'] == true && $a_beneficie_reduction == true){
								# Le pack permet une réduction et la nouvelle réduction est différente de la réduction possédée au début par la personne.
								$oMSG->setData('REDUCTION', $personne[0]['nouvelle_reduction']);
								$oMSG->setData('ID_PERSONNE', $personne[0]['ID_PERSONNE']);
								
								$oPCS_personne->fx_modifier_REDUCTION_by_ID_PERSONNE($oMSG);
							}
							
							# On vérifie si le membre a un parrain.
							if(isset($personne[0]['PARRAIN']) && !empty($personne[0]['PARRAIN']) && $personne[0]['PARRAIN'] != "Aucun" && $personne[0]['PARRAIN'] != 0){
								$oMSG->setData('ID_PERSONNE', $personne[0]['PARRAIN']);
								$oMSG->setData('REDUCTION', $pack[0]['REDUCTION']);# On attribue au parrain la réduction du pack (en plus de ce qu'il possède déjà)
								
								$oPCS_personne->fx_modifier_REDUCTION_by_ID_PARRAIN($oMSG);
							}
							
							
							if($datas['activer_pack_maintenant']){
								# On active le pack maintenant							
								if(isset($pack_personne[0]['ID_PACK']) && !empty($pack_personne[0]['ID_PACK'])){
									# Le pack existe bel et bien. On modifie le pack actuel.
									$oMSG->setData('ID_PERSONNE', $personne[0]['ID_PERSONNE']);
									$oMSG->setData('ID_PACK', $pack_personne[0]['ID_PACK']);
									$oMSG->setData('DATE_ACHAT', $pack_personne[0]['DATE_ACHAT']);
									$oMSG->setData('DATE_FIN', $now);
									
									$oPCS_pack->fx_modifier_DATE_FIN_by_IDs($oMSG);

									# On calcule la nouvelle date de début et de fin.
									$DATE_DEBUT = $now;
									$DATE_FIN = $oCL_date->fx_convertir_date($DATE_DEBUT, true, false, "en", $pack[0]['DUREE']);

								}else{
									# le pack n'existe pas, on calcule la nouvelle date de début et de fin.
									$DATE_DEBUT = $now;
									$DATE_FIN = $oCL_date->fx_convertir_date($DATE_DEBUT, true, false, "en", $pack[0]['DUREE']);
								}							
							
							}else{
								# On active le pack après le pack actuel.
								# On attribue au nouveau pack pour date de début la date de fin du pack actuel et pour date de fin la date de début plus la durée du pack.
								# On active le pack maintenant							
								if(isset($pack_personne[0]['ID_PACK']) && !empty($pack_personne[0]['ID_PACK'])){
									# Le pack existe bel et bien. On calcule la nouvelle date de début et de fin.
									$DATE_DEBUT = $pack_personne[0]['DATE_FIN'];
									$DATE_FIN = $oCL_date->fx_convertir_date($DATE_DEBUT, true, false, "en", $pack[0]['DUREE']);

								}else{
									# le pack n'existe pas, on calcule la nouvelle date de début et de fin.
									$DATE_DEBUT = $now;
									$DATE_FIN = $oCL_date->fx_convertir_date($DATE_DEBUT, true, false, "en", $pack[0]['DUREE']);
								}					
								
							}
							
							# On attribue le pack à la personne.
							$oMSG->setData('ID_PACK', $pack[0]['ID_PACK']);
							$oMSG->setData('ID_PERSONNE', $personne[0]['ID_PERSONNE']);
							$oMSG->setData('DATE_ACHAT', $now);
							$oMSG->setData('DATE_DEBUT', $DATE_DEBUT);
							$oMSG->setData('DATE_FIN', $DATE_FIN);
							$oMSG->setData('REDUCTION', $pack_reduction);
							$oMSG->setData('NB_FICHES_VISITABLES', $pack[0]['NB_FICHES_VISITABLES']);
							$oMSG->setData('DATAS_PAYPAL', $DATAS_PAYPAL);
							
							$oPCS_pack->fx_lier_pack_personne($oMSG);
							
							file_put_contents($oCL_page->getPage('paiement_bancaire_pack')."OK_".$personne[0]['ID_PERSONNE']."_".$personne[0]['EMAIL']."_".$now_concat."_POST.txt", print_r($_POST, true));
							file_put_contents($oCL_page->getPage('paiement_bancaire_pack')."OK_".$personne[0]['ID_PERSONNE']."_".$personne[0]['EMAIL']."_".$now_concat."_oMSG.txt", print_r($oMSG->getDatas(), true));
							
							# On envoi un email.
							$additional_headers = "From: noreply@liveanim.fr \r\n";
							$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
							$destinataires = $personne[0]['EMAIL'];
							$sujet = utf8_decode("LiveAnim [Achat du Pack ".$pack[0]['NOM']."]");
							
							$message = "------------------------------\n";
							$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
							$message.= "------------------------------\n\n";
							$message.= utf8_decode("Bonjour ".$personne[0]['PSEUDO'].", \n");
							$message.= utf8_decode("Votre achat du Pack ".$pack[0]['NOM']." a bien été effectué. \n\n");
							$message.= utf8_decode("Le montant initial du Pack est de ".$pack[0]['PRIX_BASE']." euros. \n");
							if($pack[0]['beneficie_reduction']){
								$message.= utf8_decode("Vous êtes bénéficiaire d'une réduction de ".$REDUCTION."% du prix initial. \n");
								$message.= utf8_decode("Ce qui vous fait un total à payer de ".$pack[0]['nouveau_prix']." euros soit une réduction effective de ".$pack[0]['economie']." euros. \n\n");
							}else{
								$message.= utf8_decode("Vous ne bénéficiez d'aucune réduction ce qui vous fait un total à payer de ".$pack[0]['nouveau_prix'].". \n\n");
							}
							$message.= utf8_decode("La durée de votre Pack est de ".$pack[0]['DUREE']." mois. \n");
							if($datas['activer_pack_maintenant']){
								$message.= utf8_decode("Vous avez choisi d'activer immédiatement votre pack. Il est donc dès à présent activé. \n");
							}else{
								$message.= utf8_decode("Vous avez choisi d'activer ce pack après votre pack actuel, si vous possédiez un pack d'activé alors il a été fait selon votre demande.\n Le pack que vous venez d'acheter s'activera automatiquement après que l'actuel ait expiré. \n");
							}
							$message.= utf8_decode("Votre compte a été débité de la somme correspondante. \n");
							$message.= utf8_decode("La totalité de la transaction bancaire a été gérée par PayPal. \n\n");
							$message.= utf8_decode("S'il y a un problème quelconque concernant le paiement veuillez contacter notre équipe, nous ferons notre possible pour vous donner entière satisfaction. \n");
							$message.= utf8_decode("------------------------------\n\n\n");
							$message.= utf8_decode("LiveAnim vous remercie de votre confiance et espère que votre nouveau produit vous satisferat ! \n\n");
							$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
							
							mail($destinataires, $sujet, $message, $additional_headers);
							
						}else{
							# On invalide l'achat.
							# On sauvegarde les erreurs en BDD ainsi que la tentative de paiement.
							$oMSG->setData('ID_PACK', $pack[0]['ID_PACK']);
							$oMSG->setData('ID_PERSONNE', $personne[0]['ID_PERSONNE']);
							$oMSG->setData('DATE_ACHAT', $now);
							$oMSG->setData('DATE_DEBUT', $now);
							$oMSG->setData('DATE_FIN', $now);
							$oMSG->setData('REDUCTION', 0);
							$oMSG->setData('NB_FICHES_VISITABLES', 0);
							$oMSG->setData('DATAS_PAYPAL', "ERROR: ".$DATAS_PAYPAL);
							
							$oPCS_pack->fx_lier_pack_personne($oMSG);
							
							file_put_contents($oCL_page->getPage('paiement_bancaire_pack')."ERROR_".$personne[0]['ID_PERSONNE']."_".$personne[0]['EMAIL']."_".$now_concat."_POST.txt", print_r($_POST, true));
							file_put_contents($oCL_page->getPage('paiement_bancaire_pack')."ERROR_".$personne[0]['ID_PERSONNE']."_".$personne[0]['EMAIL']."_".$now_concat."_oMSG.txt", print_r($oMSG->getDatas(), true));
							
							# On envoi un email.
							$additional_headers = "From: noreply@liveanim.fr \r\n";
							$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
							$destinataires = $personne[0]['EMAIL'];
							$sujet = utf8_decode("LiveAnim [Echec de l'achat du Pack".$pack[0]['NOM']."]");
							
							$message = "------------------------------\n";
							$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
							$message.= "------------------------------\n\n";
							$message.= utf8_decode("Bonjour ".$personne[0]['PSEUDO'].", \n");
							$message.= utf8_decode("Votre achat du Pack ".$pack[0]['NOM']." a échoué. \n\n");
							$message.= utf8_decode("Votre compte a été débité de ".$payment_amount." ".$_POST['mc_currency'].". \n\n");
							$message.= utf8_decode("Si l'erreur ne vient pas de vous (Tentative de modification du fonctionnement normal du système de paiement) nous vous invitons à nous contacter. \n");
							$message.= utf8_decode("(Inutile de préciser que dans le cas où vous auriez tenté d'altérer le fonctionnement normal de notre système de paiement il est inutile de faire une réclamation) \n\n");
							$message.= utf8_decode("------------------------------\n\n\n");
							$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
							
							mail($destinataires, $sujet, $message, $additional_headers);
							
						}

					}else{
					}
			}else {
					// Statut de paiement: Echec
					
					# On récupère nos données et on les met dans le tableau associatif $datas.
					$tab_datas = explode('&', $transaction_subject);
					$datas = Array();
					
					foreach($tab_datas as $key=>$data){
						$tab_datas[$key] = explode('=', $data);
						$datas[$tab_datas[$key][0]] = $tab_datas[$key][1];
					}
						
					require_once('couche_metier/MSG.php');
					require_once('couche_metier/PCS_personne.php');

					$oMSG = new MSG();
					$oPCS_personne = new PCS_personne();
				
					
					$oMSG->setData('ID_PERSONNE', $datas['id_personne']);
					
					$personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
					
					# On envoi un email.
					$additional_headers = "From: postmaster@liveanim.fr \r\n";
					$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
					$destinataires = $personne[0]['EMAIL'];
					$sujet = utf8_decode("LiveAnim [Echec d'achat via PayPal]");
					
					$message = "------------------------------\n";
					$message.= utf8_decode("Vous pouvez répondre à cet e-mail. \n");
					$message.= "------------------------------\n\n";
					$message.= utf8_decode("Bonjour ".$personne[0]['PSEUDO'].", \n");
					$message.= utf8_decode("Vous avez fait une tentative d'achat d'un de nos packs. \n\n");
					$message.= utf8_decode("Cette tentative a échouée pour une raison qu'il nous est impossible à certifier exactement. \n\n");
					$message.= utf8_decode("Votre compte PayPal n'a normalement pas été débité. Si c'est le cas merci de nous retourner cet email ainsi qu'un descriptif de vos actions concernant le paiement. \n");
					$message.= utf8_decode("Il est possible que la cause de l'erreur soit la monnaie utilisée mais rien ne l'indique avec certitude. Nous attendons des € (EUR) et vous avez payé en ".$_POST['mc_currency'].". \n\n");
					$message.= utf8_decode("Si l'erreur vient de la monnaie que vous avez utilisé et que vous avez été débité nous vous rembourseront si vous nous fournissez les pièces justificatives de l'achat. (Ce mail et la facture PayPal) \n\n");
					$message.= utf8_decode("------------------------------\n\n\n");
					$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
					
					mail($destinataires, $sujet, $message, $additional_headers);
			}
			exit();
	   }else if (strcmp ($res, "INVALID") == 0) {
			// Transaction invalide
		}
	}
	fclose ($fp);
	}	
}else{
	# Si $_POST['payment_status'] n'existe pas:
	session_start();
	$_SESSION['connexion']['message'] = "<span class='orange'>Vous avez tenté d'accéder à une page de manière non autorisée, vous avez été redirigé.</span><br /> ";
	$_SESSION['connexion']['message_affiche'] = false;
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

$_SESSION['bannir_membre']['message_affiche'] = false;

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	if(isset($_POST['form_bannissement_id_personne'])){
		$ID_PERSONNE = (int)$_POST['form_bannissement_id_personne'];
		$duree_bannissement = (int)$_POST['form_bannissement_duree'];
		$RAISON_SUPPRESSION = nl2br($_POST['form_bannissement_raison']);
		$PERSONNE_SUPPRIMEE = $_POST['form_bannissement_personne_supprimee'];
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_personne.php');
		
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		
		$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
		
		# On récupère les informations de la personne notamment son email car on va en avoir besoin.
		$personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
		
		$EMAIL = $personne[0]['EMAIL'];
		$PSEUDO = $personne[0]['PSEUDO'];
		
		# On voit si le ban est définitif ou temporaire.
		if($PERSONNE_SUPPRIMEE == "on"){
			$PERSONNE_SUPPRIMEE = 1;
			$duree_bannissement = 3000;
		}else{
			$PERSONNE_SUPPRIMEE = 0;
			
			# On va vérifier la valeur de la duree_bannissement.
			if($duree_bannissement == 0){
				$duree_bannissement = 3000;
			}
		}
		# On calcule la date de bannissement.
		$DATE_BANNISSEMENT = date("Y/m/d", mktime(0, 0, 0, date("m"), date("d")+$duree_bannissement,  date("Y")));
		
		# On écrit le message final.
		$oMSG->setData('EMAIL', $EMAIL);
		$oMSG->setData('DATE_BANNISSEMENT', $DATE_BANNISSEMENT);
		$oMSG->setData('DATE_SUPPRESSION_REELLE', date("Y-m-d"));
		$oMSG->setData('RAISON_SUPPRESSION', $RAISON_SUPPRESSION);
		$oMSG->setData('VISIBLE', 0);
		$oMSG->setData('PERSONNE_SUPPRIMEE', $PERSONNE_SUPPRIMEE);
		
		# On banni la personne.
		$oPCS_personne->fx_bannir_personne($oMSG);
			
		
		# On crée le message.
		if($PERSONNE_SUPPRIMEE == 1){
			$duree = "définitivement";
		}else{
			$duree = "temporairement";
		}
		
		# On envoi un email à la personne bannie.
		$additional_headers = "From: noreply@liveanim.fr \r\n";
		$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
		$destinataires = $EMAIL;
		$sujet = utf8_decode("Alerte LiveAnim [Bannissement de votre compte]");
		
		$message = "------------------------------\n";
		$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
		$message.= "------------------------------\n\n";
		$message.= utf8_decode("Bonjour ".$PSEUDO.", \n");
		$message.= utf8_decode("Notre service de modération vient de vous bannir ".$duree.". \n");
		if($PERSONNE_SUPPRIMEE == 1){
		$message.= utf8_decode("Le bannissement est définitif: \n\n");
		$message.= utf8_decode(str_replace("<br />", "\n", $RAISON_SUPPRESSION)." \n\n");
		$message.= utf8_decode("Si vous pensez avoir été victime d'une erreur, nous vous prions de nous contacter.\n Votre compte est bloqué et inaccessible mais vos données ne seront pas supprimées avant deux mois. \n");
		$message.= utf8_decode("Au revoir. \n");
		}else{
		$message.= utf8_decode("La durée du bannissement est de ".$duree_bannissement." jour(s): \n\n");
		$message.= utf8_decode(str_replace("<br />", "", $RAISON_SUPPRESSION)." \n\n");
		$message.= utf8_decode("Si vous pensez avoir été victime d'une erreur, nous vous prions de nous contacter.\n Votre compte est bloqué et inaccessible mais vos données ne seront pas supprimées avant deux mois. \n");
		$message.= utf8_decode("Votre compte sera automatiquement réactivé le ".$DATE_BANNISSEMENT.". \n");
		$message.= utf8_decode("LiveAnim vous encourage à lire les règles en attendant cette date.\nAu revoir. \n");
		}
		$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
		
		$_SESSION['bannir_membre']['message'] = "<span class='valide'>Le membre $PSEUDO a été banni $duree pour une durée de $duree_bannissement jour(s).<br />";

		
		if(mail($destinataires, $sujet, $message, $additional_headers)){
			# Si le mail a été correctement envoyé:		
			$_SESSION['bannir_membre']['message'].= "Un email lui a été envoyé.</span><br />";
		}else{
			$_SESSION['bannir_membre']['message'].= "Le serveur mail a eu un problème et aucun email n'a pu partir afin de prévenir l'utilisateur.</span><br />";
		}
		
		
		header('Location: '.$oCL_page->getPage('bannir_membre'));
		
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}<?php
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

	if(isset($_POST['form_changer_rang_pseudo'])){
		# Si on reçoit les données du formulaire.
		$pseudo = ucfirst(trim($_POST['form_changer_rang_pseudo']));
		$type_personne = $_POST['form_changer_rang_type_personne'];
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_personne.php');
		
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		
		$oMSG->setData('PSEUDO', $pseudo);
		$oMSG->setData('TYPE_PERSONNE', $type_personne);
		
		# On vérifie que le membre existe bien.
		
		$nb_pseudo = $oPCS_personne->fx_compter_pseudo_by_PSEUDO($oMSG)->getData(1)->fetchAll();
		
		if($nb_pseudo[0]['nb_pseudo'] == 1){
			# Le membre existe, on vérifie le rang attribué.
			require_once('couche_metier/PCS_types.php');
			
			$oPCS_types = new PCS_types();
			
			$oMSG->setData('ID_TYPES', $type_personne);
			
			$nb_types = $oPCS_types->fx_compter_types_by_ID_TYPES($oMSG)->getData(1)->fetchAll();
			
			if($nb_types[0]['nb_types'] == 1){
				# Le type sélectionné existe, on valide.
				
				$oPCS_personne->fx_modifier_rang($oMSG);
		
				$_SESSION['administration']['message_affiche'] = false;
				$_SESSION['administration']['message'] = "<span class='valide'>Opération réussie. Le rang du membre a été modifié.</span><br />";
			}
			else{
				# Le rang n'existe pas.
				$_SESSION['administration']['message_affiche'] = false;
				$_SESSION['administration']['message'] = "<span class='alert'>Le nouveau rang spécifié n'existe pas.</span><br />";
			}
		}else{
		# Le membre n'existe pas.
			$_SESSION['administration']['message_affiche'] = false;
			$_SESSION['administration']['message'] = "<span class='alert'>Le membre n'existe pas.</span><br />";
		}
		header('Location: '.$oCL_page->getPage('changer_rang', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
session_start();

require_once('couche_metier/CL_page.php');
$oCL_page = new CL_page();

if(isset($_POST['form_connexion_pseudo'])){
	$PSEUDO = ucfirst(trim($_POST['form_connexion_pseudo']));
	$MDP = $_POST['form_connexion_mdp'];
	
	$_SESSION['connexion'] = array();
	$_SESSION['connexion']['message_affiche'] = false;
	
	if(!isset($_SESSION['connexion']['nb_tentative'])){
		
		# On autorise 5 tentative par session.
		
		$_SESSION['connexion']['nb_tentative'] = 0;
	}
	# Si le nombre de tentative est atteint, on envoit chier l'abruti.
	if($_SESSION['connexion']['nb_tentative'] >= 5){
		
		$_SESSION['connexion']['message'] = "<span class='alert'>Nombre maximal de tentatives de connexion atteint.</span><br />Si vous avez oublié votre mot de passe, vous pouvez faire une <a href='".$oCL_page->getPage('recuperation_mdp')."'>Récupération de mot de passe</a> !";
											
		header('Location: '.$oCL_page->getPage('accueil'));
		die();
	}
	
	# On vérifie que le login existe (pour aider l'utilisateur, fuck les hackeurs).
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/MSG.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	
	$oMSG->setData('PSEUDO', $PSEUDO);
	
	$nb_pseudo = $oPCS_personne->fx_compter_pseudo_by_PSEUDO($oMSG)->getData(1)->fetchAll();
	if($nb_pseudo[0]['nb_pseudo'] != 1){
		
		$_SESSION['connexion']['nb_tentative']++;
		$_SESSION['connexion']['message'] = "<span class='alert'>L'identifiant que vous avez rentré n'existe pas.</span><br />";
		header('Location: '.$oCL_page->getPage('accueil'));
		die();
	}
	
	# On va crypter le mot de passe fournit.

	require_once('couche_metier/CL_cryptage.php');
	$oCL_cryptage = new CL_cryptage();
	
	$MDP = $oCL_cryptage->Cryptage($MDP, $PSEUDO);
	
	$oMSG->setData('MDP', utf8_encode($MDP));
	
	# On vérifie que le login et le mot de passe renvoient bien un seul résultat.
	$nb_pseudo = $oPCS_personne->fx_compter_pseudo_by_PSEUDO_et_MDP($oMSG)->getData(1)->fetchAll();
	
	if($nb_pseudo[0]['nb_pseudo'] != 1){
		# S'il n'y a pas de résultats alors on augmente le nombre de tentatives et on arrête tout.
		$_SESSION['connexion']['nb_tentative']++;
		$_SESSION['connexion']['message'] = "<span class='alert'>L'identifiant et le mot de passe que vous avez rentré ne correspondent pas.</span><br />";
		header('Location: '.$oCL_page->getPage('accueil'));
		die();
	}
	
	# On récupère les données du compte possédant ce PSEUDO pour ce MDP:
	$Personne = $oPCS_personne->fx_recuperer_compte_by_PSEUDO($oMSG)->getData(1)->fetchAll();
	
	# On formate la date de manière à pouvoir effectuer des calculs dessus.
	$Personne[0]['DATE_SUPPRESSION_REELLE_formatee'] = new DateTime($Personne[0]['DATE_SUPPRESSION_REELLE']);
	$Personne[0]['DATE_SUPPRESSION_REELLE_formatee'] = $Personne[0]['DATE_SUPPRESSION_REELLE_formatee']->format("Ymd");
	if($Personne[0]['DATE_SUPPRESSION_REELLE_formatee'][0] == "-"){
		$Personne[0]['DATE_SUPPRESSION_REELLE_formatee'][0] = "";
	}
	
	# On met la date au format FR.
	$tab_date_suppression = explode("-", $Personne[0]['DATE_SUPPRESSION_REELLE']);# On récupère la date dans un tableau de trois cases ([Y][M][D])
	$Personne[0]['DATE_SUPPRESSION_REELLE'] = date("d-m-Y", mktime(0, 0, 0, $tab_date_suppression[1], $tab_date_suppression[2],  $tab_date_suppression[0]));
	
	
	# On formate la date de manière à pouvoir effectuer des calculs dessus.
	$Personne[0]['DATE_BANNISSEMENT_formatee'] = new DateTime($Personne[0]['DATE_BANNISSEMENT']);
	$Personne[0]['DATE_BANNISSEMENT_formatee'] = $Personne[0]['DATE_BANNISSEMENT_formatee']->format("Ymd");
	if($Personne[0]['DATE_BANNISSEMENT_formatee'][0] == "-"){
		$Personne[0]['DATE_BANNISSEMENT_formatee'][0] = "";
	}
	
	# On met la date au format FR.
	$tab_date_bannissement = explode("-", $Personne[0]['DATE_BANNISSEMENT']);# On récupère la date dans un tableau de trois cases ([Y][M][D])
	$Personne[0]['DATE_BANNISSEMENT'] = date("d-m-Y", mktime(0, 0, 0, $tab_date_bannissement[1], $tab_date_bannissement[2],  $tab_date_bannissement[0]));
	
	
	# On vérifie que l'utilisateur ait bien le droit de se connecter.

	$nb_erreur = 0;
	$now = date("d-m-Y");
	$oNOW = new DateTime( $now );
	$now = $oNOW->format("Ymd");
	
	// echo $Personne[0]['DATE_SUPPRESSION_REELLE_formatee']."<br />".$Personne[0]['DATE_BANNISSEMENT_formatee']."<br />".$this_day."<br />".$now."<br />";
	
	if(strlen($Personne[0]['CLE_ACTIVATION']) != 0){
		# Le compte n'a pas été activé.
		$nb_erreur++;
		$_SESSION['connexion']['message'] = "<span class='alert'>Votre compte n'a pas été activé. Activez le grâce à l'e-mail reçu lors de votre inscription.</span><br />";
		header('Location: '.$oCL_page->getPage('accueil'));
	}
	
	if($Personne[0]['VISIBLE'] == false){
		if($Personne[0]['PERSONNE_SUPPRIMEE'] == true && ($Personne[0]['DATE_BANNISSEMENT_formatee'] <= $now)){
			# Le compte a été supprimé par l'utilisateur ou un tiers.
			$nb_erreur++;			
			$_SESSION['connexion']['message'] = "<span class='alert'>Votre compte a été supprimé.<br />En voici la raison:</span><br /><br />".
											   $Personne[0]['RAISON_SUPPRESSION']."<br /><br />".
											   "Si c'est une personne tierce qui a supprimée votre compte et que vous".
											   " souhaitez le récupérer, vous pouvez contacter notre service.<br /><br />La suppression réelle de votre compte".
											   " aura lieue aux alentours du: ".$Personne[0]['DATE_SUPPRESSION_REELLE'].".<br /><br />Jusqu'à cette date nous conservons vos informations".
											   " dans un but de statistiques. Elles ne seront pas dévoilées à des services tiers.<br />".
											   "Si toutefois vous souhaitez que nous les supprimions immédiatement alors envoyez nous un mail avec votre pseudo ".
											   " nous vous contacterons pour vérifier votre identité puis nous supprimerons définitivement toutes vos informations.<br />";
			header('Location: '.$oCL_page->getPage('accueil'));
			
		}else if($Personne[0]['PERSONNE_SUPPRIMEE'] == true && ($Personne[0]['DATE_BANNISSEMENT_formatee'] > $now)){
			# Le compte a été supprimé par l'équipe d'administration.
			$nb_erreur++;			
			$_SESSION['connexion']['message'] = "<span class='alert'>Votre compte a été supprimé par notre équipe.<br />En voici la raison:</span><br /><br />".
											   $Personne[0]['RAISON_SUPPRESSION']."<br /><br />";
			header('Location: '.$oCL_page->getPage('accueil'));
		
		}else if($Personne[0]['PERSONNE_SUPPRIMEE'] == false && ($Personne[0]['DATE_BANNISSEMENT_formatee'] > $now)){
			# Le compte a banni temporairement.
			$nb_erreur++;	
			$_SESSION['connexion']['message'] = "<span class='alert'>Votre compte a été banni temporairement par l'équipe de modération.<br />En voici la raison:</span><br /><br />".
											   $Personne[0]['RAISON_SUPPRESSION']."<br /><br />".
											   "Si vous pensez être victime d'une erreur, veuillez nous contacter. Abstenez vous dans le cas contraire.<br />".
											   "Votre compte sera automatiquement réactivé le:".$Personne[0]['DATE_BANNISSEMENT'].".";
			header('Location: '.$oCL_page->getPage('accueil'));
		}else if($Personne[0]['PERSONNE_SUPPRIMEE'] == false && ($Personne[0]['DATE_BANNISSEMENT_formatee'] <= $now)){
			# Le compte a été banni mais est à nouveau utilisable.
		
		}else{
			# Cas oublié ?
			$nb_erreur++;
			$_SESSION['connexion']['message'] = "<span class='alert'>Votre compte n'est pas accessible. Cela peut-être dû à une erreur, veuillez nous contacter.</span><br />";
			header('Location: '.$oCL_page->getPage('accueil'));
		}
	}
	
	
	
	if($nb_erreur == 0){
		# On récupère l'IP de l'utilisateur et on la stocke.
		$IP = $_SERVER["REMOTE_ADDR"];
			
		if($Personne[0]['TYPE_PERSONNE'] == "Prestataire"){
			$_SESSION['compte'] = array();
			$_SESSION['compte']['connecté'] = true;
			$_SESSION['compte']['première_visite'] = false;
			$_SESSION['compte']['date_connexion'] = $oNOW->format("YmdHis");
			$_SESSION['compte']['ID_PERSONNE'] = $Personne[0]['ID_PERSONNE'];
			$_SESSION['compte']['PSEUDO'] = $Personne[0]['PSEUDO'];
			$_SESSION['compte']['NOM'] = $Personne[0]['NOM'];
			$_SESSION['compte']['PRENOM'] = $Personne[0]['PRENOM'];
			$_SESSION['compte']['CIVILITE'] = $Personne[0]['CIVILITE'];
			$_SESSION['compte']['EMAIL'] = $Personne[0]['EMAIL'];
			$_SESSION['compte']['TYPE_PERSONNE'] = $Personne[0]['TYPE_PERSONNE'];
			$_SESSION['compte']['PARRAIN'] = $Personne[0]['PARRAIN'];
			$_SESSION['compte']['REDUCTION'] = $Personne[0]['REDUCTION'];
			$_SESSION['compte']['annonces_visitées'] = $Personne[0]['ANNONCES_VISITEES'];
			
			# On met en forme les annonces_visitées de manière à en faire un tableau.
			if(!empty($_SESSION['compte']['annonces_visitées'])){
				$_SESSION['compte']['annonces_visitées'] = explode('/', $_SESSION['compte']['annonces_visitées']);
			}else{
				$_SESSION['compte']['annonces_visitées'] = array();
			}
			
			# On récupère le pack actif de la personne.
			require_once('couche_metier/PCS_pack.php');
			$oPCS_pack = new PCS_pack();
					
			$oMSG->setData('ID_PERSONNE', $Personne[0]['ID_PERSONNE']);
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
					$_SESSION['pack']['URL_CONTRAT_PDF'] = false;
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
					$_SESSION['pack']['URL_CONTRAT_PDF'] = $pack_personne[0]['URL_CONTRAT_PDF'];
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
				$_SESSION['pack']['URL_CONTRAT_PDF'] = false;
				$_SESSION['pack']['SUIVI'] = false;
				$_SESSION['pack']['PUBS'] = true;
				$_SESSION['pack']['date_fin_validite'] = "00-00-0000 00:00:00";
				$_SESSION['pack']['date_fin_validite_formatee'] = "00000000000000";
			}
		}else if($Personne[0]['TYPE_PERSONNE'] == "Organisateur"){
			$_SESSION['compte'] = array();
			$_SESSION['compte']['connecté'] = true;
			$_SESSION['compte']['première_visite'] = false;
			$_SESSION['compte']['date_connexion'] = $oNOW->format("YmdHis");
			$_SESSION['compte']['ID_PERSONNE'] = $Personne[0]['ID_PERSONNE'];
			$_SESSION['compte']['PSEUDO'] = $Personne[0]['PSEUDO'];
			$_SESSION['compte']['NOM'] = $Personne[0]['NOM'];
			$_SESSION['compte']['PRENOM'] = $Personne[0]['PRENOM'];
			$_SESSION['compte']['CIVILITE'] = $Personne[0]['CIVILITE'];
			$_SESSION['compte']['EMAIL'] = $Personne[0]['EMAIL'];
			$_SESSION['compte']['TYPE_PERSONNE'] = $Personne[0]['TYPE_PERSONNE'];
			$_SESSION['compte']['PARRAIN'] = $Personne[0]['PARRAIN'];
			
			# On permet à un Organisateur de parrainer.
			$_SESSION['pack']['PARRAINAGE_ACTIVE'] = true;
			$_SESSION['pack']['URL_CONTRAT_PDF'] = true;
			$_SESSION['pack']['PUBS'] = true;
			
		
		}else{# $Personne[0]['TYPE_PERSONNE'] = "Admin", forcément.
			$_SESSION['compte'] = array();
			$_SESSION['compte']['connecté'] = true;
			$_SESSION['compte']['première_visite'] = false;
			$_SESSION['compte']['date_connexion'] = $oNOW->format("YmdHis");
			$_SESSION['compte']['ID_PERSONNE'] = $Personne[0]['ID_PERSONNE'];
			$_SESSION['compte']['PSEUDO'] = $Personne[0]['PSEUDO'];
			$_SESSION['compte']['NOM'] = $Personne[0]['NOM'];
			$_SESSION['compte']['PRENOM'] = $Personne[0]['PRENOM'];
			$_SESSION['compte']['CIVILITE'] = $Personne[0]['CIVILITE'];
			$_SESSION['compte']['EMAIL'] = $Personne[0]['EMAIL'];
			$_SESSION['compte']['TYPE_PERSONNE'] = $Personne[0]['TYPE_PERSONNE'];
			$_SESSION['compte']['PARRAIN'] = $Personne[0]['PARRAIN'];
			
			# On permet à un Admin de tout faire.
			$_SESSION['pack']['activé'] = false;
			$_SESSION['pack']['DATE_ACHAT'] = date('d-m-Y H:i:s');# On dit que la date de l'achat correspond à maintenant.
			$_SESSION['pack']['ID_PACK'] = 5; # Pack Live MAX
			$_SESSION['pack']['NOM'] = "Pack Admin"; # On explique que c'est pas vraiment le live max. ^^
			$_SESSION['pack']['TYPE_PACK'] = "Admin";
			$_SESSION['pack']['PRIX_BASE'] = 0;
			$_SESSION['pack']['DUREE'] = 12;
			$_SESSION['pack']['CV_VISIBILITE'] = 10;
			$_SESSION['pack']['CV_ACCESSIBLE'] = 10;
			$_SESSION['pack']['NB_FICHES_VISITABLES'] = 1000000;
			$_SESSION['pack']['CV_VIDEO_ACCESSIBLE'] = true;
			$_SESSION['pack']['ALERTE_NON_DISPONIBILITE'] = true;
			$_SESSION['pack']['NB_DEPARTEMENTS_ALERTE'] = 5;
			$_SESSION['pack']['PARRAINAGE_ACTIVE'] = true;
			$_SESSION['pack']['PREVISUALISATION_FICHES'] = true;
			$_SESSION['pack']['URL_CONTRAT_PDF'] = true;
			$_SESSION['pack']['SUIVI'] = true;
			$_SESSION['pack']['PUBS'] = false;
			$_SESSION['pack']['date_fin_validite'] = "25-12-2100 00:00:00";
			$_SESSION['pack']['date_fin_validite_formatee'] = "25122100000000";
			
		}# Fin du gros if sur TYPE_PERSONNE.
		
		# On crée l'IP.
		$oPCS_personne->fx_creer_IP($oMSG);# Si elle existe déjà ça marchera pas mais on s'en fout.
		
		# On lie l'IP.
		$now = date('Y-m-d H:i:s');
		$oMSG->setData('ID_PERSONNE', $Personne[0]['ID_PERSONNE']);
		$oMSG->setData('ID_IP', $IP);
		$oMSG->setData('IP_COOKIE', $_COOKIE['lang']);# lang => IP, on l'appelle langue pour tromper les tricheurs idiots.
		$oMSG->setData('COOKIE_DETRUIT', $_COOKIE['admin']);# admin => sert à vérifier le premier cookie, on sait si lang a été détruit ou pas.
		$oMSG->setData('DATE_CONNEXION', $now);
				
		$oPCS_personne->fx_lier_IP_et_PERSONNE($oMSG);# On sauvegarde le tout.
		
		setcookie('lang', $IP, (time() + 60*60*24*365));# On met à jour l'IP du cookie.
		setcookie('admin', 0, (time() + 60*60*24*365));# Ce cookie signifie qu'on a crée le cookie lang et donc que lang n'existait pas.
		
		unset($Personne);# On détruit les infos pour pas qu'elles soient accessibles (ça sert à qqch ?^^)
		$_SESSION['connexion']['message'] = "<span class='valide'>Bonjour ".$_SESSION['compte']['PSEUDO'].".<br />Vous êtes à présent connecté !</span><br />";
		
		if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
			$_SESSION['connexion']['message'].= "<span class='valide'>Les informations concernant votre pack ont été chargées.<br /></span><br />";
			if($_SESSION['pack']['activé'] == true){
				$_SESSION['connexion']['message'].= "<span class='valide'>Vous disposez du pack <strong>".$_SESSION['pack']['NOM']."</strong> jusqu'au "
				.$_SESSION['pack']['date_fin_validite']."<br />Vous pouvez encore visualiser ".$_SESSION['pack']['NB_FICHES_VISITABLES']." fiches d'annonces détaillées.</span>";
			}else{
				$_SESSION['connexion']['message'].= "<span class='orange'>La date de validité de votre pack est dépassée. Veuillez vous en procurer un autre pour profiter au maximum de nos services !<br /></span>";
			}
		}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
			$_SESSION['connexion']['message'].= "<span class='formulaire'>Vous êtes connecté en tant qu'administrateur.<br /></span><br />";
		}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur"){
			$_SESSION['connexion']['message'].= "<span class='orange'>Nous vous rappellons que les annonces que vous créerez devront d'abord être validées par un administrateur avant d'être publiées.<br /></span><br />";
			$_SESSION['connexion']['message'].= "Nous vous souhaitons une très agréable visite sur LiveAnim.com !<br />";
		}else{
			$_SESSION['connexion']['message'].= "<span class='alert'>Une erreur s'est produite durant votre connexion, vous avez été deconnecté. [Erreur: Type de la personne non reconnu lors de la connexion]</alert><br />".
			"<span class='orange'>Veuillez contacter un administrateur si le problème se reproduit en indiquant le message d'erreur. Merci.</span><br />";

		}
		header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle']));
	}

}else{
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
<?php
session_start();

require_once('couche_metier/CL_page.php');
$oCL_page = new CL_page();

$_SESSION = array();
session_destroy();
session_unset();
session_start();
$_SESSION['connexion']['message'] = "<span class='valide'>Vous avez été déconnecté avec succès.</span><br />";
$_SESSION['connexion']['message_affiche'] = false;
header('Location: '.$oCL_page->getPage('accueil'));
?>
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
* Script utilisé par la création d'une annonce.
*/

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
	
	if(isset($_POST['form_ajout_modification_annonce_titre'])){	
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');		
		require_once('couche_metier/PCS_types.php');
		require_once('couche_metier/CL_date.php');
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		$oPCS_types = new PCS_types();
		$oCL_date = new CL_date();
		
		# On récupère les types nécessaires:
		$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');
		$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On récupère les départements:
		$departements = $oPCS_annonce->fx_recuperer_tous_departements($oMSG)->getData(1)->fetchAll();
	
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
		
		# On récupère les données du formulaire.
		$TITRE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_titre'])));
		$TYPE_ANNONCE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_type_annonce'])));
		$DATE_DEBUT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_date_debut']));
		$DATE_FIN = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_date_fin']));
		$ARTISTES_RECHERCHES = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_artistes_recherches']))));
		$BUDGET = preg_replace($chaines_interdites, "", floatval(str_replace(',', '.', trim($_POST['form_ajout_modification_annonce_budget']))));
		$NB_CONVIVES = preg_replace($chaines_interdites, "", (int)trim($_POST['form_ajout_modification_annonce_nb_convives']));
		$DESCRIPTION = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_description']))));
		$ID_DEPARTEMENT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_id_departement']));# Ne pas transformer en int car la corse est 2a/2b
		$ADRESSE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_adresse'])));
		$CP = preg_replace($chaines_interdites, "", (int)trim($_POST['form_ajout_modification_annonce_cp']));
		$VILLE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_ville'])));
			
		$ID_PERSONNE = $_SESSION['compte']['ID_PERSONNE'];
		$DATE_ANNONCE = date("Y-m-d H:i:s");
		$VISIBLE = 0; # Une annonce est non visible de base.
		$GOLDLIVE = 0;# Une annonce ne bénéficie pas du statut GOLDLIVE de base.
		$STATUT = "En cours";
		
		# On prépare nos variables nécessaires pour les messages d'erreurs.
		$_SESSION['ajouter_annonce']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
		$_SESSION['ajouter_annonce']['message'] = "";# On initialise et on rajoutera par dessus.
		
		$nb_erreur = 0;
		
		# On sauvegarde en session les champs.
		$_SESSION['ajouter_annonce']['TITRE'] = $TITRE;
		$_SESSION['ajouter_annonce']['TYPE_ANNONCE'] = $TYPE_ANNONCE;
		$_SESSION['ajouter_annonce']['DATE_DEBUT'] = $DATE_DEBUT;
		$_SESSION['ajouter_annonce']['DATE_FIN'] = $DATE_FIN;
		$_SESSION['ajouter_annonce']['ARTISTES_RECHERCHES'] = $ARTISTES_RECHERCHES;
		$_SESSION['ajouter_annonce']['BUDGET'] = $BUDGET;
		$_SESSION['ajouter_annonce']['NB_CONVIVES'] = $NB_CONVIVES;
		$_SESSION['ajouter_annonce']['DESCRIPTION'] = $DESCRIPTION;
		$_SESSION['ajouter_annonce']['ID_DEPARTEMENT'] = $ID_DEPARTEMENT;
		$_SESSION['ajouter_annonce']['ADRESSE'] = $ADRESSE;
		$_SESSION['ajouter_annonce']['CP'] = $CP;
		$_SESSION['ajouter_annonce']['VILLE'] = $VILLE;
		
		# On vérifie l'intégrité des données:
		
		# On commence par vérifier qu'un des champs obligatoire ne soit pas vide.
		if(empty($TITRE) || empty($DATE_DEBUT) || empty($DATE_FIN) || empty($ARTISTES_RECHERCHES) || empty($DESCRIPTION) || empty($ADRESSE) || empty($CP) || empty($VILLE)){
			# Un des champs obligatoire est vide.
			$_SESSION['ajouter_annonce']['message'].= "<span class='alert'>Un des champs obligatoire est vide.</span><br />";
			$nb_erreur++;
		}
		
		# On vérifie la taille des champs.
		if(strlen($TITRE) > 50){
			# Le titre est trop long.
			$_SESSION['ajouter_annonce']['TITRE'] = "";
			$_SESSION['ajouter_annonce']['message'].= "<span class='alert'>Le titre est trop long, 50 caractères maximum.</span><br />";
			$nb_erreur++;
		}
		
		if(strlen($TITRE) < 5){
			# Le titre est trop court.
			$_SESSION['ajouter_annonce']['TITRE'] = "";
			$_SESSION['ajouter_annonce']['message'].= "<span class='alert'>Le titre est trop court, 5 caractères minimum.</span><br />";
			$nb_erreur++;
		}
		
		# On vérifie les valeurs des listes déroulantes.
			# Vérification des types d'annonces.
			$liste_types_annonce = array();
			foreach($types_annonce as $key=>$type_annonce){
				$liste_types_annonce[$key] = $type_annonce['ID_TYPES'];
			}
			if(!in_array($TYPE_ANNONCE, $liste_types_annonce)){
				# L'utilisateur a modifié le code source, on l'envoi chier.
				$_SESSION['ajouter_annonce']['TYPE_ANNONCE'] = "";
				$_SESSION['ajouter_annonce']['message'].= "<span class='alert'>Le type d'annonce est incorrect.</span><br />";
				$nb_erreur++;
			}
			
			# Vérification des départements.
			$liste_departements = array();
			foreach($departements as $key=>$departement){
				$liste_departements[$key] = $departement['ID_DEPARTEMENT'];
			}
			if(!in_array($ID_DEPARTEMENT, $liste_departements)){
				# L'utilisateur a modifié le code source, on l'envoi chier.
				$_SESSION['ajouter_annonce']['ID_DEPARTEMENT'] = "";
				$_SESSION['ajouter_annonce']['message'].= "<span class='alert'>Le département selectionné n'existe pas.</span><br />";
				$nb_erreur++;
			}
		
		# On vérifie les dates.
			# On vérifie la date de début.
			$DATE_DEBUT = str_replace('h', ':', $DATE_DEBUT).':00';# On rajoute les secondes.
			if(!$oCL_date->fx_verif_date($DATE_DEBUT, 'fr', true)){
				# La date de début est incorrecte.
				$_SESSION['ajouter_annonce']['DATE_DEBUT'] = "";
				$_SESSION['ajouter_annonce']['message'].= "<span class='alert'>La date de début est incorrecte. <span class='petit'>(06/05/2011 20h56)</span></span><br />";
				$nb_erreur++;
			}
			
			# On vérifie la date de fin.
			$DATE_FIN = str_replace('h', ':', $DATE_FIN).':00';# On rajoute les secondes.
			if(!$oCL_date->fx_verif_date($DATE_FIN, 'fr', true)){
				# La date de début est incorrecte.
				$_SESSION['ajouter_annonce']['DATE_FIN'] = "";
				$_SESSION['ajouter_annonce']['message'].= "<span class='alert'>La date de fin est incorrecte. <span class='petit'>(06/05/2011 20h56)</span></span><br />";
				$nb_erreur++;
			}
			
		# On vérifie le budget.
		if($BUDGET != 0){
			if(!is_numeric($BUDGET)){
				# La date de début est incorrecte.
				$_SESSION['ajouter_annonce']['BUDGET'] = "0.00";
				$_SESSION['ajouter_annonce']['message'].= "<span class='alert'>Le budget est de format incorrect. <span class='petit'>(53.5 ou 53,5 ou 53)</span></span><br />";
				$nb_erreur++;
			}
		}
		
		# On vérifie que la date de début soit supérieure à la date de fin.
		if($oCL_date->fx_ajouter_date($DATE_DEBUT, true, true, 'fr') >= $oCL_date->fx_ajouter_date($DATE_FIN, true, true, 'fr')){
				# Date de début supérieure à la date de fin.
				$_SESSION['ajouter_annonce']['message'].= "<span class='orange'>La date de début est supérieure à la date de fin.</span><br />";
				$nb_erreur++;
		}
		
		
		if($nb_erreur == 0){
			# L'intégrité des données est vérifiée.
			
			# On convertit les dates en en.
			$DATE_DEBUT = $oCL_date->fx_ajouter_date($DATE_DEBUT, true, false, 'fr');
			$DATE_FIN = $oCL_date->fx_ajouter_date($DATE_FIN, true, false, 'fr');
			
			# On crée l'annonce, en mode invisible.
			$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
			$oMSG->setData('ID_DEPARTEMENT', $ID_DEPARTEMENT);
			$oMSG->setData('TITRE', $TITRE);
			$oMSG->setData('TYPE_ANNONCE', $TYPE_ANNONCE);
			$oMSG->setData('DATE_ANNONCE', $DATE_ANNONCE);
			$oMSG->setData('DATE_DEBUT', $DATE_DEBUT);
			$oMSG->setData('DATE_FIN', $DATE_FIN);
			$oMSG->setData('ARTISTES_RECHERCHES', $ARTISTES_RECHERCHES);
			$oMSG->setData('BUDGET', $BUDGET);
			$oMSG->setData('NB_CONVIVES', $NB_CONVIVES);
			$oMSG->setData('DESCRIPTION', $DESCRIPTION);
			$oMSG->setData('ADRESSE', $ADRESSE);
			$oMSG->setData('CP', $CP);
			$oMSG->setData('VILLE', $VILLE);
			$oMSG->setData('GOLDLIVE', $GOLDLIVE);
			$oMSG->setData('VISIBLE', $VISIBLE);
			$oMSG->setData('STATUT', $STATUT);
			
			$ID_ANNONCE = $oPCS_annonce->fx_creer_annonce($oMSG)->getData(1);# On récupère l'ID au passage.
			
			#On envoi l'email.
			$additional_headers = "From: noreply@liveanim.fr \r\n";
			$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
			$destinataires = $_SESSION['compte']['EMAIL'];
			$sujet = utf8_decode("LiveAnim [Création de votre annonce]");
			
			$message = "------------------------------\n";
			$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
			$message.= "------------------------------\n\n";
			$message.= utf8_decode("Bonjour ".$_SESSION['compte']['PSEUDO'].", \n");
			$message.= utf8_decode("La création de votre annonce a été effectuée. Elle possède l'identifiant ".$ID_ANNONCE.". \n\n");
			$message.= utf8_decode("Vous serez prévenu automatiquement par email lorsqu'elle aura été validée par nos services. \n");
			$message.= utf8_decode("Nous essayons de traiter votre annonce le plus rapidement possible. \n");
			$message.= utf8_decode("------------------------------\n\n\n");
			$message.= utf8_decode("LiveAnim vous remercie de votre confiance et vous souhaite un très bon surf sur notre site !\n\n");
			$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
			
			mail($destinataires, $sujet, $message, $additional_headers);
			
			# On supprime les variables de session.
			unset($_SESSION['ajouter_annonce']);
			
			$_SESSION['ajouter_annonce']['message'].= "<span class='valide'>L'annonce que vous avez publié a été créée avec succès. Un email vous a été envoyé.<br /> Vous serez prévenu par email lorsqu'elle sera validée par l'administration.</span><br />";
			header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu'));
			
		}else{
			# Au moins une erreur a été detectée.
			$_SESSION['ajouter_annonce']['message'].= "<span class='alert'>L'annonce n'a pas été crée.</span><br />";
			header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu'));
		}
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	if(isset($_POST['form_ajout_modification_contrat_id_annonce'])){
	
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');		
		require_once('couche_metier/PCS_contrat.php');
		require_once('couche_metier/PCS_personne.php');
		require_once('couche_metier/PCS_message.php');
		require_once('couche_metier/CL_date.php');
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		$oPCS_contrat = new PCS_contrat();
		$oPCS_personne = new PCS_personne();
		$oPCS_message = new PCS_message();
		$oCL_date = new CL_date();
		
		$nb_erreur = 0;
		$_SESSION['creer_contrat']['message_affiche'] = false;
		$_SESSION['creer_contrat']['message'] = "<br /><br />";
		
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
		
		# On récupère les données du formulaire.
		$ID_ANNONCE = (int)$_POST['form_ajout_modification_contrat_id_annonce'];
		$DATE_DEBUT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_contrat_date_debut']));
		$DATE_FIN = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_contrat_date_fin']));
		$PRIX = preg_replace($chaines_interdites, "", (float)ucfirst(trim($_POST['form_ajout_modification_contrat_prix'])));
		$DESCRIPTION = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_contrat_description']))));
		$DATE_CONTRAT = date('Y-m-d H:i:s');
		$STATUT_CONTRAT = "En attente";
		
		# On sauvegarde les données en session:
		$_SESSION['creer_contrat']['DATE_DEBUT'] = $DATE_DEBUT;
		$_SESSION['creer_contrat']['DATE_FIN'] = $DATE_FIN;
		$_SESSION['creer_contrat']['PRIX'] = $PRIX;
		$_SESSION['creer_contrat']['DESCRIPTION'] = $DESCRIPTION;
		
		# On vérifie que l'ID_ANNONCE envoyé est correct:
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		$oMSG->setData('VISIBLE', 1);
		$oMSG->setData('STATUT', 'Validée');
		
		$annonce = $oPCS_annonce->fx_recuperer_annonce_valide_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
		
		if(empty($annonce[0]['ID_ANNONCE'])){
			$nb_erreur++;
			$_SESSION['creer_contrat']['message'].= "<span class='alert'>Cette annonce est invalide.</span><br />";
			unset($_SESSION['creer_contrat']);
		}
		
		# La personne qui a créée l'annonce est l'organisateur.
		$id_organisateur = $annonce[0]['ID_PERSONNE'];
		# La personne qui créée le contrat est le prestataire.
		$id_prestataire = $_SESSION['compte']['ID_PERSONNE'];
		
		# On vérifie les dates.
		$DATE_DEBUT = str_replace('h', ':', $DATE_DEBUT).':00';# On rajoute les secondes.
		if(!$oCL_date->fx_verif_date($DATE_DEBUT, 'fr', true)){
			$nb_erreur++;
			$_SESSION['creer_contrat']['message'].= "<span class='alert'>La date de début est invalide.</span><br />";
			unset($_SESSION['creer_contrat']['DATE_DEBUT']);
		}
		$DATE_FIN = str_replace('h', ':', $DATE_FIN).':00';# On rajoute les secondes.
		if(!$oCL_date->fx_verif_date($DATE_FIN, 'fr', true)){
			$nb_erreur++;
			$_SESSION['creer_contrat']['message'].= "<span class='alert'>La date de fin est invalide.</span><br />";
			unset($_SESSION['creer_contrat']['DATE_FIN']);
		}
		
		# On vire l'éventuelle virgule du prix.
		$PRIX = str_replace(',', '.', $PRIX);
		
		# On vérifie que la date de début soit supérieure à la date de fin.
		if($oCL_date->fx_ajouter_date($DATE_DEBUT, true, true, 'fr') >= $oCL_date->fx_ajouter_date($DATE_FIN, true, true, 'fr')){
				# Date de début supérieure à la date de fin.
				$_SESSION['creer_contrat']['message'].= "<span class='orange'>La date de début est supérieure à la date de fin.</span><br />";
				$nb_erreur++;
		}
		
		# On vérifie qu'il n'y ait pas déjà un contrat de créée.
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		$oMSG->setData('ID_PERSONNE', $id_prestataire);
		$oMSG->setData('conditions', "AND STATUT_CONTRAT <> 'Annulé';");
		
		$nb_contrat = $oPCS_contrat->fx_compter_contrat_by_ID_ANNONCE_et_ID_PERSONNE_et_condition($oMSG)->getData(1)->fetchAll();
		
		if($nb_contrat[0]['nb_contrat'] > 0){
			# Date de début supérieure à la date de fin.
			$_SESSION['creer_contrat']['message'].= "<span class='orange'>Vous avez déjà un contrat en cours avec cette personne.</span><br />";
			$nb_erreur++;
		}
		
		if($nb_erreur == 0){
			# On met en forme les données.
			$DATE_DEBUT = $oCL_date->fx_ajouter_date($DATE_DEBUT, true, false, 'fr', 'en');
			$DATE_FIN = $oCL_date->fx_ajouter_date($DATE_FIN, true, false, 'fr', 'en');
			
			# On crée le contrat.
			$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
			$oMSG->setData('DATE_DEBUT', $DATE_DEBUT);
			$oMSG->setData('DATE_FIN', $DATE_FIN);
			$oMSG->setData('PRIX', $PRIX);
			$oMSG->setData('DESCRIPTION', $DESCRIPTION);
			$oMSG->setData('DATE_CONTRAT', $DATE_CONTRAT);
			$oMSG->setData('STATUT_CONTRAT', $STATUT_CONTRAT);
			
			$ID_CONTRAT = $oPCS_contrat->fx_creer_contrat($oMSG)->getData(1);
			
			# On lie le contrat aux deux personnes.
			$oMSG->setData('ID_PERSONNE', $id_organisateur);
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);

			$oPCS_contrat->fx_lier_contrat($oMSG);# On lie le contrat avec l'organisateur.
			
			$oMSG->setData('ID_PERSONNE', $id_prestataire);
			$oMSG->setData('ID_CONTRAT', $ID_CONTRAT);
			
			$oPCS_contrat->fx_lier_contrat($oMSG);# On lie le contrat avec le prestataire.
			
			# On récupère l'organisateur.
			$oMSG->setData('ID_PERSONNE', $id_organisateur);
			
			$organisateur = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			# On récupère le prestataire.
			$oMSG->setData('ID_PERSONNE', $id_prestataire);
			
			$prestataire = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			# On lui envoi un email comme quoi son annonce a reçue une demande de contrat.
			$additional_headers = "From: noreply@liveanim.fr \r\n";
			$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
			$destinataires = $organisateur[0]['EMAIL'];
			$sujet = utf8_decode("LiveAnim [Demande de contrat]");
			
			$message = "------------------------------\n";
			$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
			$message.= "------------------------------\n\n";
			$message.= utf8_decode("Bonjour ".$organisateur[0]['PSEUDO'].", \n");
			$message.= utf8_decode("Un membre vous propose un contrat concernant une annonce que vous avez faite. \n\n");
			$message.= utf8_decode("Veuillez vous connecter afin d'en prendre connaissance: \n");
			$message.= utf8_decode($oCL_page->getPage('accueil', 'absolu')." \n\n");
			$message.= utf8_decode("------------------------------\n\n\n");
			$message.= utf8_decode("LiveAnim vous remercie de votre confiance et vous rappelle qu'en cas de désaccord avec un artiste nous pouvons vous fournir le contrat que vous aurez validé avec l'artiste !\n\n");
			$message.= utf8_decode("Si vous souhaitez nous contacter vous pouvez nous envoyer un mail à support@liveanim.com \n\n");
			$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
			
			mail($destinataires, $sujet, $message, $additional_headers);
			
			# On crée un message 
			$CONTENU = "<b>Bonjour</b>, <br /><br />";
			$CONTENU.= "Une demande de contrat a été effectuée par l'artiste ".$prestataire[0]['PSEUDO'].".<br />";
			$CONTENU.= "Vous pouvez la visualiser ici: <a href='".$oCL_page->getPage('')."'>Aller au contrat</a>.<br /><br />";
			$CONTENU.= "Nous vous rappellons que ce contrat est entre vous et l'artiste, LiveAnim n'est pas responsable des services offerts.<br />";
			$CONTENU.= "Toutefois nous mettons à votre disposition ce contrat afin d'avoir une pièce 'justificative' de votre accord.<br /> ";
			$CONTENU.= "En cas de problème vous pouvez nous contacter à: support@liveanim.com<br /> ";
			$CONTENU.= "Nous vous remercions d'utiliser nos services et espérons qu'ils vous apportent entière satisfaction.<br /><br />";
			$CONTENU.= "<span class='rose'>L'équipe LiveAnim.</span><br /> ";
			
			$oMSG->setData('TITRE', "Demande de contrat de ".$prestataire[0]['PSEUDO']);
			$oMSG->setData('CONTENU', $CONTENU);
			$oMSG->setData('DATE_ENVOI', $DATE_CONTRAT);
			$oMSG->setData('EXPEDITEUR', $prestataire[0]['ID_PERSONNE']);
			$oMSG->setData('DESTINATAIRE', $organisateur[0]['ID_PERSONNE']);
			$oMSG->setData('TYPE_MESSAGE', "Contrat");
			$oMSG->setData('VISIBLE', true);
			
			$ID_MESSAGE = $oPCS_message->fx_creer_message($oMSG)->getData(1);
			
			# On envoi le message à l'organisateur.
			$oMSG->setData('ID_PERSONNE', $organisateur[0]['ID_PERSONNE']);
			$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
			$oMSG->setData('STATUT_MESSAGE', 'Non lu');

			$oPCS_message->fx_lier_message($oMSG);
			
			$_SESSION['creer_contrat']['message'].= "<span class='valide'>Le contrat a bien été crée.<br />Vous serez prévenu par email lorsque l'organisateur y aura répondu.<br />Il a été prévenu de votre demande de contrat.</span><br />";
			header('Location:'.$oCL_page->getPage('creer_contrat', 'absolu')."?id_annonce=".$ID_ANNONCE);
		}else{
			# Une erreur a eue lieu.
			$_SESSION['creer_contrat']['message'].= "<span class='orange'>Le contrat n'a pas été crée.</span><br />";
			header('Location: '.$oCL_page->getPage('creer_contrat', 'absolu')."?id_annonce=".$ID_ANNONCE);
		}
	
	}else{
		# On ne reçoit pas les informations du POST.
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	if(isset($_POST['form_pack_nom'])){
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_pack.php');		
		require_once('couche_metier/PCS_types.php');
		
		$oMSG = new MSG();
		$oPCS_pack = new PCS_pack();
		$oPCS_types = new PCS_types();
		
		# On récupère les types nécessaires:
		$oMSG->setData('ID_FAMILLE_TYPES', 'Type de pack');
		$types_pack = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
	
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/");
	
		$NOM = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_pack_nom'])));
		$DESCRIPTION = nl2br(preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_pack_description']))));
		$TYPE_PACK = preg_replace($chaines_interdites, "", $_POST['form_pack_type_pack']);
		$PRIX_BASE = preg_replace($chaines_interdites, "", trim($_POST['form_pack_prix_base']));
		$DUREE = preg_replace($chaines_interdites, "", trim($_POST['form_pack_duree']));
		$SOUMIS_REDUCTIONS_PARRAINAGE = preg_replace($chaines_interdites, "", $_POST['form_pack_soumis_reduction_parrainage']);
		$GAIN_PARRAINAGE_MAX = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_gain_parrainage_max']);
		$REDUCTION = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_reduction']);
		$VISIBLE = preg_replace($chaines_interdites, "", $_POST['form_pack_visible']);

		$CV_VISIBILITE = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_cv_visibilite']);
		$CV_ACCESSIBLE = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_cv_accessible']);
		$NB_FICHES_VISITABLES = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_nb_fiches_visitables']);
		$CV_VIDEO_ACCESSIBLE = preg_replace($chaines_interdites, "", $_POST['form_pack_cv_video_accessible']);
		$ALERTE_NON_DISPONIBILITE = preg_replace($chaines_interdites, "", $_POST['form_pack_alerte_non_disponibilite']);
		$NB_DEPARTEMENTS_ALERTE = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_nb_departements_alerte']);
		$PARRAINAGE_ACTIVE = preg_replace($chaines_interdites, "", $_POST['form_pack_parrainage_active']);
		$PREVISUALISATION_FICHES = preg_replace($chaines_interdites, "", $_POST['form_pack_previsualisation_fiches']);
		$CONTRATS_PDF = preg_replace($chaines_interdites, "", $_POST['form_pack_contrats_pdf']);
		$SUIVI = preg_replace($chaines_interdites, "", $_POST['form_pack_suivi']);
		$PUBS = preg_replace($chaines_interdites, "", $_POST['form_pack_pubs']);
		
		# On prépare nos variables nécessaires pour les messages d'erreurs.
		$_SESSION['ajouter_pack']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
		$_SESSION['ajouter_pack']['message'] = "";# On initialise et on rajoutera par dessus.
		
		$nb_erreur = 0;
		
		# On vérifie l'intégrité des données:
		
		# On commence par vérifier que les champs obligatoire ne soient pas vides.
		if(empty($NOM) || empty($DESCRIPTION) || empty($NB_FICHES_VISITABLES)){
			$_SESSION['ajouter_pack']['message'].= "<span class='alert'>Un de champs obligatoire est vide.</span><br />";
			$nb_erreur++;
		}

		# On vérifie que le type de pack soit correct.
		$type_pack_ok = 0;
		foreach($types_pack as $key=>$type_pack){
			if($type_pack['ID_TYPES'] == $TYPE_PACK){
				$type_pack_ok++;
			}
		}
		
		# Si ça vaut 0 alors c'est que le type n'existe pas en BDD donc modification formulaire. Si plus de 1 alors c'est qu'on a deux types identiques en BDD. (impossible)
		if($type_pack_ok != 1){
			$_SESSION['ajouter_pack']['message'].= "<span class='alert'>Le type de pack sélectionné n'existe pas.</span><br />";
			$nb_erreur++;
		}
		
		# On vire les virgules probables.
		$PRIX_BASE = str_replace(",", ".", $PRIX_BASE);
		
		# Si malgré ça le prix ne correspond pas.
		if(!is_numeric($PRIX_BASE)){
			$_SESSION['ajouter_pack']['message'].= "<span class='alert'>Le prix de base est incorrect. <span class='petit'>(Ex: 250/999.0/99,5)</span></span><br />";
			$nb_erreur++;
		}
		
		# S'il n'y a pas d'erreurs.
		if($nb_erreur == 0){
			$oMSG->setData('NOM', $NOM);
			$oMSG->setData('DESCRIPTION', $DESCRIPTION);
			$oMSG->setData('TYPE_PACK', $TYPE_PACK);
			$oMSG->setData('PRIX_BASE', $PRIX_BASE);
			$oMSG->setData('DUREE', $DUREE);
			$oMSG->setData('SOUMIS_REDUCTIONS_PARRAINAGE', $SOUMIS_REDUCTIONS_PARRAINAGE);
			$oMSG->setData('GAIN_PARRAINAGE_MAX', $GAIN_PARRAINAGE_MAX);
			$oMSG->setData('REDUCTION', $REDUCTION);
			$oMSG->setData('VISIBLE', $VISIBLE);
			
			$oMSG->setData('CV_VISIBILITE', $CV_VISIBILITE);
			$oMSG->setData('CV_ACCESSIBLE', $CV_ACCESSIBLE);
			$oMSG->setData('NB_FICHES_VISITABLES', $NB_FICHES_VISITABLES);
			$oMSG->setData('CV_VIDEO_ACCESSIBLE', $CV_VIDEO_ACCESSIBLE);
			$oMSG->setData('ALERTE_NON_DISPONIBILITE', $ALERTE_NON_DISPONIBILITE);
			$oMSG->setData('NB_DEPARTEMENTS_ALERTE', $NB_DEPARTEMENTS_ALERTE);
			$oMSG->setData('PARRAINAGE_ACTIVE', $PARRAINAGE_ACTIVE);
			$oMSG->setData('PREVISUALISATION_FICHES', $PREVISUALISATION_FICHES);
			$oMSG->setData('CONTRATS_PDF', $CONTRATS_PDF);
			$oMSG->setData('SUIVI', $SUIVI);
			$oMSG->setData('PUBS', $PUBS);
			
			$oPCS_pack->fx_creer_pack($oMSG);
			
			$_SESSION['ajouter_pack']['message'].= "<span class='valide'>Le pack a été ajouté.</span><br />";
			header('Location: '.$oCL_page->getPage('ajouter_pack'));
			
		}else{
			$_SESSION['ajouter_pack']['message'].= "<span class='alert'>Le pack n'a pas été crée.</span><br />";
			header('Location: '.$oCL_page->getPage('ajouter_pack'));
		}
		
		
	}else{
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}
	

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

/*
* Script utilisé par la modification d'une annonce par un organisateur.
*/

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
	
	if(isset($_POST['form_ajout_modification_annonce_titre'])){	
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');		
		require_once('couche_metier/PCS_types.php');
		require_once('couche_metier/CL_date.php');
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		$oPCS_types = new PCS_types();
		$oCL_date = new CL_date();
		
		# On récupère les types nécessaires:
		$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');
		$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On récupère les départements:
		$departements = $oPCS_annonce->fx_recuperer_tous_departements($oMSG)->getData(1)->fetchAll();
	
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
		
		# On récupère les données du formulaire.
		$ID_ANNONCE = (int)$_POST['form_ajout_modification_annonce_id_annonce'];
		$ID_PERSONNE = $_SESSION['compte']['ID_PERSONNE'];
		$TITRE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_titre'])));
		$TYPE_ANNONCE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_type_annonce'])));
		$DATE_DEBUT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_date_debut']));
		$DATE_FIN = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_date_fin']));
		$ARTISTES_RECHERCHES = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_artistes_recherches']))));
		$BUDGET = preg_replace($chaines_interdites, "", floatval(str_replace(',', '.', trim($_POST['form_ajout_modification_annonce_budget']))));
		$NB_CONVIVES = preg_replace($chaines_interdites, "", (int)trim($_POST['form_ajout_modification_annonce_nb_convives']));
		$DESCRIPTION = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_description']))));
		$ID_DEPARTEMENT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_id_departement']));# Ne pas transformer en int car la corse est 2a/2b
		$ADRESSE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_adresse'])));
		$CP = preg_replace($chaines_interdites, "", (int)trim($_POST['form_ajout_modification_annonce_cp']));
		$VILLE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_ville'])));
	
			
		$GOLDLIVE = 0;# On ne gère pas la fonction GOLDLIVE pour le moment.
		
		# On prépare nos variables nécessaires pour les messages d'erreurs.
		$_SESSION['modifier_annonce']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
		$_SESSION['modifier_annonce']['message'] = "";# On initialise et on rajoutera par dessus.
		
		$nb_erreur = 0;
		
		# On vérifie l'intégrité des données:
		
		# On commence par vérifier qu'un des champs obligatoire ne soit pas vide.
		if(empty($TITRE) || empty($DATE_DEBUT) || empty($DATE_FIN) || empty($ARTISTES_RECHERCHES) || empty($DESCRIPTION) || empty($ADRESSE) || empty($CP) || empty($VILLE)){
			# Un des champs obligatoire est vide.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Un des champs obligatoire est vide.</span><br />";
			$nb_erreur++;
		}
		
		# On vérifie la taille des champs.
		if(strlen($TITRE) > 50){
			# Le titre est trop long.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le titre est trop long, 50 caractères maximum.</span><br />";
			$nb_erreur++;
		}
		
		if(strlen($TITRE) < 5){
			# Le titre est trop court.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le titre est trop court, 5 caractères minimum.</span><br />";
			$nb_erreur++;
		}
		
		# On vérifie les valeurs des listes déroulantes.
			# Vérification des types d'annonces.
			$liste_types_annonce = array();
			foreach($types_annonce as $key=>$type_annonce){
				$liste_types_annonce[$key] = $type_annonce['ID_TYPES'];
			}
			if(!in_array($TYPE_ANNONCE, $liste_types_annonce)){
				# L'utilisateur a modifié le code source, on l'envoi chier.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le type d'annonce est incorrect.</span><br />";
				$nb_erreur++;
			}
			
			# Vérification des départements.
			$liste_departements = array();
			foreach($departements as $key=>$departement){
				$liste_departements[$key] = $departement['ID_DEPARTEMENT'];
			}
			if(!in_array($ID_DEPARTEMENT, $liste_departements)){
				# L'utilisateur a modifié le code source, on l'envoi chier.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le département selectionné n'existe pas.</span><br />";
				$nb_erreur++;
			}
		
		# On vérifie les dates.
			# On vérifie la date de début.
			$DATE_DEBUT = str_replace('h', ':', $DATE_DEBUT).':00';# On rajoute les secondes.
			if(!$oCL_date->fx_verif_date($DATE_DEBUT, 'fr', true)){
				# La date de début est incorrecte.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>La date de début est incorrecte. <span class='petit'>(06/05/2011 20h56)</span></span><br />";
				$nb_erreur++;
			}
			
			# On vérifie la date de fin.
			$DATE_FIN = str_replace('h', ':', $DATE_FIN).':00';# On rajoute les secondes.
			if(!$oCL_date->fx_verif_date($DATE_FIN, 'fr', true)){
				# La date de début est incorrecte.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>La date de fin est incorrecte. <span class='petit'>(06/05/2011 20h56)</span></span><br />";
				$nb_erreur++;
			}
			
		# On vérifie le budget.
		if($BUDGET != 0){
			if(!is_numeric($BUDGET)){
				# La date de début est incorrecte.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le budget est de format incorrect. <span class='petit'>(53.5 ou 53,5 ou 53)</span></span><br />";
				$nb_erreur++;
			}
		}
		
		# On vérifie que la date de début soit supérieure à la date de fin.
		if($oCL_date->fx_ajouter_date($DATE_DEBUT, true, true, 'fr') >= $oCL_date->fx_ajouter_date($DATE_FIN, true, true, 'fr')){
				# Date de début supérieure à la date de fin.
				$_SESSION['modifier_annonce']['message'].= "<span class='orange'>La date de début est supérieure à la date de fin.</span><br />";
				$nb_erreur++;
		}
		
		# On charge l'annonce en cours afin de vérifier les droits de modification.
		$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
		
		$annonce = $oPCS_annonce->fx_recuperer_annonce_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
		
		if($annonce[0]['ID_PERSONNE'] != $ID_PERSONNE){
			# La personne qui modifie n'est pas la même que celle qui possède l'annonce.
			$_SESSION['connexion']['message_affiche'] = false;# On redirige vers la page de l'annonce mais elle va elle même rediriger vers l'accueil.
			$_SESSION['connexion']['message'] = "<span class='alert'>Vous n'avez pas les droits nécessaires pour modifier cette annonce.</span><br />";
			$nb_erreur++;
		}
		
		if($annonce[0]['STATUT'] == "Validée"){
			# L'annonce a déjà été validée, on refuse les modifications
			$_SESSION['modifier_annonce']['message'] = "<span class='alert'>Vous ne pouvez pas modifier une annonce validée.</span><br />";
			$nb_erreur++;
		}
		
		if($nb_erreur == 0){
			# L'intégrité des données est vérifiée.
			
			# On convertit les dates en en.
			$DATE_DEBUT = $oCL_date->fx_ajouter_date($DATE_DEBUT, true, false, 'fr');
			$DATE_FIN = $oCL_date->fx_ajouter_date($DATE_FIN, true, false, 'fr');
			
			# On modifie l'annonce.
			$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
			$oMSG->setData('ID_DEPARTEMENT', $ID_DEPARTEMENT);
			$oMSG->setData('TITRE', $TITRE);
			$oMSG->setData('TYPE_ANNONCE', $TYPE_ANNONCE);
			$oMSG->setData('DATE_DEBUT', $DATE_DEBUT);
			$oMSG->setData('DATE_FIN', $DATE_FIN);
			$oMSG->setData('ARTISTES_RECHERCHES', $ARTISTES_RECHERCHES);
			$oMSG->setData('BUDGET', $BUDGET);
			$oMSG->setData('NB_CONVIVES', $NB_CONVIVES);
			$oMSG->setData('DESCRIPTION', $DESCRIPTION);
			$oMSG->setData('ADRESSE', $ADRESSE);
			$oMSG->setData('CP', $CP);
			$oMSG->setData('VILLE', $VILLE);
			$oMSG->setData('GOLDLIVE', $GOLDLIVE);
			$oMSG->setData('VISIBLE', $annonce[0]['VISIBLE']);
			$oMSG->setData('STATUT', $annonce[0]['STATUT']);
			
			$oPCS_annonce->fx_modifier_annonce_by_ID_ANNONCE($oMSG)->getData(1);
			
			
			
			$_SESSION['modifier_annonce']['message'].= "<span class='valide'>L'annonce a été modifiée avec succès. Vous serez prévenu par email lorsqu'elle sera validée par l'administration.</span><br />";
			header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu')."?id_annonce=".$ID_ANNONCE);
			
		}else{
			# Au moins une erreur a été detectée.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>L'annonce n'a pas été modifiée.</span><br />";
			//header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu')."?id_annonce=".$ID_ANNONCE);
			echo $ID_ANNONCE;
		}
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

/*
* Script utilisé par la modification d'une annonce par un admin.
*/

# On vérifie que la personne soit connectée et admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
	
	if(isset($_POST['form_ajout_modification_annonce_titre'])){	
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');		
		require_once('couche_metier/PCS_types.php');
		require_once('couche_metier/CL_date.php');
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		$oPCS_types = new PCS_types();
		$oCL_date = new CL_date();
		
		# On récupère les types nécessaires:
		$oMSG->setData('ID_FAMILLE_TYPES', 'Type de soirée');
		$types_annonce = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
		
		# On récupère les départements:
		$departements = $oPCS_annonce->fx_recuperer_tous_departements($oMSG)->getData(1)->fetchAll();
	
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
		
		# On récupère les données du formulaire.
		$ID_ANNONCE = (int)$_POST['form_ajout_modification_annonce_id_annonce'];
		$TITRE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_titre'])));
		$TYPE_ANNONCE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_type_annonce'])));
		$DATE_DEBUT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_date_debut']));
		$DATE_FIN = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_date_fin']));
		$ARTISTES_RECHERCHES = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_artistes_recherches']))));
		$BUDGET = preg_replace($chaines_interdites, "", floatval(str_replace(',', '.', trim($_POST['form_ajout_modification_annonce_budget']))));
		$NB_CONVIVES = preg_replace($chaines_interdites, "", (int)trim($_POST['form_ajout_modification_annonce_nb_convives']));
		$DESCRIPTION = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_description']))));
		$ID_DEPARTEMENT = preg_replace($chaines_interdites, "", trim($_POST['form_ajout_modification_annonce_id_departement']));# Ne pas transformer en int car la corse est 2a/2b
		$ADRESSE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_adresse'])));
		$CP = preg_replace($chaines_interdites, "", (int)trim($_POST['form_ajout_modification_annonce_cp']));
		$VILLE = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_ville'])));
		$STATUT = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_ajout_modification_annonce_statut'])));
		$REFUS = preg_replace($chaines_interdites, "", nl2br(ucfirst(trim($_POST['form_ajout_modification_annonce_refus']))));
	
			
		$GOLDLIVE = 0;# On ne gère pas la fonction GOLDLIVE pour le moment.
		
		# On prépare nos variables nécessaires pour les messages d'erreurs.
		$_SESSION['modifier_annonce']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
		$_SESSION['modifier_annonce']['message'] = "";# On initialise et on rajoutera par dessus.
		
		$nb_erreur = 0;
		
		# On vérifie l'intégrité des données:
		
		# On commence par vérifier qu'un des champs obligatoire ne soit pas vide.
		if(empty($TITRE) || empty($DATE_DEBUT) || empty($DATE_FIN) || empty($ARTISTES_RECHERCHES) || empty($DESCRIPTION) || empty($ADRESSE) || empty($CP) || empty($VILLE)){
			# Un des champs obligatoire est vide.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Un des champs obligatoire est vide.</span><br />";
			$nb_erreur++;
		}
		
		# On vérifie la taille des champs.
		if(strlen($TITRE) > 50){
			# Le titre est trop long.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le titre est trop long, 50 caractères maximum.</span><br />";
			$nb_erreur++;
		}
		
		if(strlen($TITRE) < 5){
			# Le titre est trop court.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le titre est trop court, 5 caractères minimum.</span><br />";
			$nb_erreur++;
		}
		
		# On vérifie les valeurs des listes déroulantes.
			# Vérification des types d'annonces.
			$liste_types_annonce = array();
			foreach($types_annonce as $key=>$type_annonce){
				$liste_types_annonce[$key] = $type_annonce['ID_TYPES'];
			}
			if(!in_array($TYPE_ANNONCE, $liste_types_annonce)){
				# L'utilisateur a modifié le code source, on l'envoi chier.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le type d'annonce est incorrect.</span><br />";
				$nb_erreur++;
			}
			
			# Vérification des départements.
			$liste_departements = array();
			foreach($departements as $key=>$departement){
				$liste_departements[$key] = $departement['ID_DEPARTEMENT'];
			}
			if(!in_array($ID_DEPARTEMENT, $liste_departements)){
				# L'utilisateur a modifié le code source, on l'envoi chier.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le département selectionné n'existe pas.</span><br />";
				$nb_erreur++;
			}
		
		# On vérifie les dates.
			# On vérifie la date de début.
			$DATE_DEBUT = str_replace('h', ':', $DATE_DEBUT).':00';# On rajoute les secondes.
			if(!$oCL_date->fx_verif_date($DATE_DEBUT, 'fr', true)){
				# La date de début est incorrecte.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>La date de début est incorrecte. <span class='petit'>(06/05/2011 20h56)</span></span><br />";
				$nb_erreur++;
			}
			
			# On vérifie la date de fin.
			$DATE_FIN = str_replace('h', ':', $DATE_FIN).':00';# On rajoute les secondes.
			if(!$oCL_date->fx_verif_date($DATE_FIN, 'fr', true)){
				# La date de début est incorrecte.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>La date de fin est incorrecte. <span class='petit'>(06/05/2011 20h56)</span></span><br />";
				$nb_erreur++;
			}
			
		# On vérifie le budget.
		if($BUDGET != 0){
			if(!is_numeric($BUDGET)){
				# La date de début est incorrecte.
				$_SESSION['modifier_annonce']['message'].= "<span class='alert'>Le budget est de format incorrect. <span class='petit'>(53.5 ou 53,5 ou 53)</span></span><br />";
				$nb_erreur++;
			}
		}
		
		# On vérifie que la date de début soit supérieure à la date de fin.
		if($oCL_date->fx_ajouter_date($DATE_DEBUT, true, true, 'fr') >= $oCL_date->fx_ajouter_date($DATE_FIN, true, true, 'fr')){
				# Date de début supérieure à la date de fin.
				$_SESSION['modifier_annonce']['message'].= "<span class='orange'>La date de début est supérieure à la date de fin.</span><br />";
				$nb_erreur++;
		}
		
		# On vérifie le statut de l'annonce et on en détermine sa visibilité.
		if($STATUT == "En attente"){
			$VISIBLE = 0;
		}else if($STATUT == "Validée"){
			$VISIBLE = 1;
		}else if($STATUT == "Refusée"){
			$VISIBLE = 0;
		}else{
			$nb_erreur++;
			$_SESSION['modifier_annonce']['message'].= "<span class='orange'>Le statut sélectionné est incorrect.</span><br />";
		}		
		
		if($nb_erreur == 0){
			# L'intégrité des données est vérifiée.
			
			# On convertit les dates en en.
			$DATE_DEBUT = $oCL_date->fx_ajouter_date($DATE_DEBUT, true, false, 'fr');
			$DATE_FIN = $oCL_date->fx_ajouter_date($DATE_FIN, true, false, 'fr');
			
			# On modifie l'annonce.
			$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
			$oMSG->setData('ID_DEPARTEMENT', $ID_DEPARTEMENT);
			$oMSG->setData('TITRE', $TITRE);
			$oMSG->setData('TYPE_ANNONCE', $TYPE_ANNONCE);
			$oMSG->setData('DATE_DEBUT', $DATE_DEBUT);
			$oMSG->setData('DATE_FIN', $DATE_FIN);
			$oMSG->setData('ARTISTES_RECHERCHES', $ARTISTES_RECHERCHES);
			$oMSG->setData('BUDGET', $BUDGET);
			$oMSG->setData('NB_CONVIVES', $NB_CONVIVES);
			$oMSG->setData('DESCRIPTION', $DESCRIPTION);
			$oMSG->setData('ADRESSE', $ADRESSE);
			$oMSG->setData('CP', $CP);
			$oMSG->setData('VILLE', $VILLE);
			$oMSG->setData('GOLDLIVE', $GOLDLIVE);
			$oMSG->setData('VISIBLE', $VISIBLE);
			$oMSG->setData('STATUT', $STATUT);
			
			$oPCS_annonce->fx_modifier_annonce_by_ID_ANNONCE($oMSG)->getData(1);
			
			#On envoi l'email.
			$additional_headers = "From: noreply@liveanim.fr \r\n";
			$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
			$destinataires = $_SESSION['compte']['EMAIL'];
			if($STATUT == "Validée"){
				$sujet = utf8_decode("LiveAnim [Annonce N°".$ID_ANNONCE." validée !]");
				$message = "------------------------------\n";
				$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
				$message.= "------------------------------\n\n";
				$message.= utf8_decode("Votre annonce N°".$ID_ANNONCE." a été validée par un administrateur de LiveAnim. \n\n");
				$message.= utf8_decode("Elle est désormais visible de tous les prestataires. \n");
				$message.= utf8_decode("N'oubliez pas que vous pouvez activer la fonctionnalité GoldLive pour toutes les annonces que vous créez, ce qui améliore leur visibilité et donc les chances de trouver des artistes ! \n");
			}else if($STATUT == "Refusée"){
				$sujet = utf8_decode("LiveAnim [Annonce N°".$ID_ANNONCE." refusée.]");
				$message = "------------------------------\n";
				$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
				$message.= "------------------------------\n\n";
				$message.= utf8_decode("Votre annonce N°".$ID_ANNONCE." a été refusée par notre service de modération pour la raison suivante: \n");
				$message.= utf8_decode($REFUS." \n\n");
				$message.= utf8_decode("Veuillez prendre vos dispositions afin que votre annonce soit acceptée en respectant nos règles, merci. \n");
				$message.= utf8_decode("Vous pouvez modifier votre annonce et la soumettre à nouveau. \n");
			}else if($STATUT == "En attente"){
				$sujet = utf8_decode("LiveAnim [Modification de l'annonce N°".$ID_ANNONCE.".]");
				$message = "------------------------------\n";
				$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
				$message.= "------------------------------\n\n";
				$message.= utf8_decode("Votre annonce N°".$ID_ANNONCE." a été modifiée par un administrateur. \n");
				$message.= utf8_decode("Vous pouvez voir les modifications dans votre page de gestion des annonces. \n");
				$message.= utf8_decode("Votre annonce est toujours en attente, elle devrait être validée sous peu, prenez tout de même connaissance des modifications apportées.	\n");
			}else{}# Pas d'autre possibilité, vérifié avant.
			
			$message.= utf8_decode("------------------------------\n\n\n");
			$message.= utf8_decode("LiveAnim vous remercie de votre confiance.\n\n");
			$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
			
			mail($destinataires, $sujet, $message, $additional_headers);
			
			
			$_SESSION['modifier_annonce']['message'].= "<span class='valide'>L'annonce a été modifiée avec succès. Un email a été envoyé à son possésseur pour le prévenir des modifications.</span><br />";
			header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu')."?id_annonce=".$ID_ANNONCE);
			
		}else{
			# Au moins une erreur a été detectée.
			$_SESSION['modifier_annonce']['message'].= "<span class='alert'>L'annonce n'a pas été modifiée.</span><br />";
			header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu')."?id_annonce=".$ID_ANNONCE);
		}
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

	/*
	* Attention: 
	* Lorsque l'on parle de $_SESSION['compte']['TYPE_PERSONNE'] on parle à la fois de la personne connectée et de la 
	* personne modifiée. En effet, si le type est "Admin" alors il a tous les droits de modifications.
	* Si le type est Prestataire alors il peut modifier ses infos de prestations.
	* Si le type est Organisateur alors il ne peut modifier que ses infos perso.
	*
	* Amélioration possible pour une plus grande facilité d'évolution:
	* 	- Modifier le script appelé selon le TYPE_PERSONNE en SESSION.
	* 	- Vérifier que le script a le droit d'être appelé par ce TYPE_PERSONNE.
	*	- Faire les modifications selon les infos BDD ou SESSION selon le script appelé.
	*/

# On vérifie que la personne est connectée.
if($_SESSION['compte']['connecté'] == true){
	if(isset($_POST['form_fiche_membre_nom'])){
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_personne.php');		
		require_once('couche_metier/PCS_types.php');
		require_once('couche_metier/CL_date.php');
		require_once('couche_metier/CL_upload.php');
		require_once('couche_metier/CL_cryptage.php');
		
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		$oPCS_types = new PCS_types();
		$oCL_date = new CL_date();
		$oCL_cryptage = new CL_cryptage();
		
		# Si ce n'est pas un admin on vérifie que le mot de passe fournit correspond bien à l'ID_PERSONNE fournie (/!\ Modification de l'id possible /!\)
		if($_SESSION['compte']['TYPE_PERSONNE'] != "Admin"){
			
			$oMSG->setData('ID_PERSONNE', (int)$_POST['form_fiche_membre_id_personne']);
			$oMSG->setData('MDP', utf8_encode($oCL_cryptage->Cryptage($_POST['form_fiche_membre_mdp'], $_POST['form_fiche_membre_pseudo'])));
			
			$nb_personne = $oPCS_personne->fx_compter_personne_by_ID_PERSONNE_et_MDP($oMSG)->getData(1)->fetchAll();

			if($nb_personne[0]['nb_personne'] == 1){
				$id_personne_ok = true;
			}else{
				$id_personne_ok = false;
				$_SESSION['modification_fiche_membre']['message'] = "<span class='alert'>Le mot de passe saisi est incorrect. Aucune modification n'a été effectuée.</span><br />";
				$_SESSION['modification_fiche_membre']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
			}

		}else{
			$id_personne_ok = true;
		}
		
		if($id_personne_ok){
			
			# On récupère les types nécessaires:
				$oMSG->setData('ID_FAMILLE_TYPES', 'Civilité');
				$civilites = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
				
				$oMSG->setData('ID_FAMILLE_TYPES', 'Statut professionnel');
				$statuts_personne = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
				
			# On récupère la réduction du personnage.
			$oMSG->setData('ID_PERSONNE', (int)$_POST['form_fiche_membre_id_personne']);
			
			$reduction = $oPCS_personne->fx_recuperer_REDUCTION_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			
			# On supprime les chaines interdites.
			$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
		
			$ID_PERSONNE = preg_replace ($chaines_interdites, "", (int)$_POST['form_fiche_membre_id_personne']);
			$NOM = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_nom'])));
			$PRENOM = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_prenom'])));
			$CIVILITE = preg_replace ($chaines_interdites, "", $_POST['form_fiche_membre_civilite']);
			$DATE_NAISSANCE = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_date_naissance']));
			$URL_PHOTO_PRINCIPALE = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_url_photo_principale']));
			$EMAIL = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_email']));
			$TEL_FIXE = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_tel_fixe']));
			$TEL_PORTABLE = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_tel_portable']));
			if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				# Si c'est un admin qui modifie la fiche alors on autorise la modification de la réduction.
				$REDUCTION = preg_replace ($chaines_interdites, "", (int)trim($_POST['form_fiche_membre_reduction']));
			}else{
				$REDUCTION = $reduction[0]['REDUCTION'];
			}
			$ADRESSE = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_adresse'])));
			$CP = preg_replace ($chaines_interdites, "", trim((int)$_POST['form_fiche_membre_cp']));
			$VILLE = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_ville'])));
			$NEWSLETTER = preg_replace ($chaines_interdites, "", $_POST['form_fiche_membre_newsletter']);
			$OFFRES_ANNONCEURS = preg_replace ($chaines_interdites, "", $_POST['form_fiche_membre_offres_annonceurs']);
			
			# Si c'est un prestataire ou un admin qui modifie la fiche alors on prend en compte les informations suivantes.
			if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				$DESCRIPTION = nl2br(preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_description']))));
				$STATUT_PERSONNE = preg_replace ($chaines_interdites, "", $_POST['form_fiche_membre_statut']);
				$DEPARTEMENTS = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_departements']));
				$SIRET = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_siret']));
				$TARIFS = nl2br(preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_tarifs'])));
				$DISTANCE_PRESTATION_MAX = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_distance_prestation_max']));
				$CV_VIDEO = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_cv_video']));
				$MATERIEL = nl2br(preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_materiel']))));
			}
			
			# On prépare nos variables nécessaires pour les messages d'erreurs.
			$_SESSION['modification_fiche_membre']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
			$_SESSION['modification_fiche_membre']['message'] = "";# On initialise et on rajoutera par dessus.
			
			$nb_erreur = 0;
			
			# On vérifie l'intégrité des données:
			
			# On commence par vérifier que les champs obligatoire ne soient pas vides.
			if(empty($NOM) || empty($PRENOM) || empty($CIVILITE) || empty($EMAIL)){
				$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>Un des champs obligatoire est vide.<span class='petit'>(Nom, prénom, civilité et email)<span></span><br />";
				$nb_erreur++;
			}
			
			# On vérifie que la civilité soit correcte.
			$civilite_ok = 0;
			foreach($civilites as $key=>$civilite){
				if($civilite['ID_TYPES'] == $CIVILITE){
					$civilite_ok++;
				}
			}
			
			# Si ça vaut 0 alors c'est que le type n'existe pas en BDD donc modification formulaire. Si plus de 1 alors c'est qu'on a deux types identiques en BDD. (impossible)
			if($civilite_ok != 1){
				$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>La civilité sélectionnée n'existe pas.</span><br />";
				$nb_erreur++;
			}
			
			# On vérifie le format de date, on attend un format FR et on veut le transformer en EN.
			if($oCL_date->fx_verif_date($DATE_NAISSANCE, "fr")){
				$DATE_NAISSANCE = $oCL_date->fx_convertir_date($DATE_NAISSANCE);
			}else{
				if($oCL_date->fx_verif_date($DATE_NAISSANCE, "en")){
					# La date est déjà au format en, on ne fait rien.
					
				}else{
					# La date n'est ni au format en, ni fr.
					$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>Date incorrecte. <span class='petit'>Format: 00/00/0000 ou 0000/00/00.</span></span><br />";
					$nb_erreur++;
				}
			}
			
			# On s'occupe de l'image. (photo)
			if(!empty($_FILES) && $_FILES['form_fiche_membre_nouvelle_photo_principale']['error'] == 0){
					$oCL_upload = new CL_upload($_FILES['form_fiche_membre_nouvelle_photo_principale'], "images/uploads/membres", array("png", "gif", "jpg", "jpeg"), 0777, array("image/jpeg", "image/jpeg", "image/png", "image/gif", "image/pjpg", "image/pjpeg"), 200, 200, 30000);
					
					$new_filename = $ID_PERSONNE."_".date("Y-m-d_H-i-s");
					$ext = explode('.', $_FILES['form_fiche_membre_nouvelle_photo_principale']['name']);
					$extension = $ext[count($ext)-1];
					
					$tab_message = $oCL_upload->fx_upload($_FILES['form_fiche_membre_nouvelle_photo_principale']['name'], $new_filename);
					
					if($tab_message['reussite'] == true){
						$URL_PHOTO_PRINCIPALE =  $oCL_page->getPage('accueil', 'absolu').$tab_message['resultat'];
					}else{
						$_SESSION['modification_fiche_membre']['message'].= $tab_message['resultat'];
						$URL_PHOTO_PRINCIPALE = "";
						$echec_upload = true;
						# On empèche pas la modification de la fiche.
					}
			}# On ne fait rien de plus concernant le téléchargement. 
			# Si il y a un téléchargement il change l'url de la photo, il reste prioritaire.
			
			# On teste si l'adresse e-mail est à un format valide.
			if(!filter_var($EMAIL, FILTER_VALIDATE_EMAIL)){
				
				$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>L'email que vous avez rentré est invalide.</span><br />";
				$nb_erreur++;
			}
			
			# Si c'est un prestataire ou un admin qui modifie la fiche alors on prend en compte les informations suivantes.
			if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				
				# au cas où il y ai eu un autre séparateur que la virgule.
				$DEPARTEMENTS = str_replace(array(";", ".", "/", "_", "-"), ",", $DEPARTEMENTS);
				
				# On vire les virgules probables.
				$DISTANCE_PRESTATION_MAX = str_replace(",", ".", $DISTANCE_PRESTATION_MAX);
				
				# Si malgré ça la distance ne correspond pas.
				if(!is_numeric($DISTANCE_PRESTATION_MAX)){
					$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>La distance de prestation maximale est incorrecte. <span class='petit'>(Ex: 250/999.0/99,5)</span></span><br />";
					$nb_erreur++;
				}
				
				
				# On vérifie que le statut soit correct.
				$statut_personne_ok = 0;
				foreach($statuts_personne as $key=>$statut_personne){
					if($statut_personne['ID_TYPES'] == $STATUT_PERSONNE){
						$statut_personne_ok++;
					}
				}
				
				# Si ça vaut 0 alors c'est que le type n'existe pas en BDD donc modification formulaire. Si plus de 1 alors c'est qu'on a deux types identiques en BDD. (impossible)
				if($statut_personne_ok != 1){
					$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>Le statut sélectionné n'existe pas.</span><br />";
					$nb_erreur++;
				}
				
			}
			
			# on récupère les infos de la personne.
			$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
					
			$personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			# On vérifie l'email fournit par rapport à l'email de en BDD.
			if($EMAIL != $personne[0]['EMAIL']){
				# L'email a été modifié, on doit vérifier s'il n'a pas déjà été pris.
				$oMSG->setData('EMAIL', $EMAIL);
				
				$nb_email = $oPCS_personne->fx_compter_email_by_EMAIL($oMSG)->getData(1)->fetchAll();
				
				if($nb_email[0]['nb_email'] != 0){
					# L'email existe déjà.
					$_SESSION['modification_fiche_membre']['message'].= "<span class='orange'>L'email saisi est déjà utilisé.</span><br />";
					$nb_erreur++;
				}
			}
			
			
			# On regarde les erreurs.
			if($nb_erreur == 0){
				# On écrit le message.
				$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
				$oMSG->setData('NOM', $NOM);
				$oMSG->setData('PRENOM', $PRENOM);
				$oMSG->setData('CIVILITE', $CIVILITE);
				$oMSG->setData('DATE_NAISSANCE', $DATE_NAISSANCE);
				$oMSG->setData('URL_PHOTO_PRINCIPALE', $URL_PHOTO_PRINCIPALE);
				$oMSG->setData('EMAIL', $EMAIL);
				$oMSG->setData('TEL_FIXE', $TEL_FIXE);
				$oMSG->setData('TEL_PORTABLE', $TEL_PORTABLE);
				$oMSG->setData('REDUCTION', $REDUCTION);
				$oMSG->setData('ADRESSE', $ADRESSE);
				$oMSG->setData('CP', $CP);
				$oMSG->setData('VILLE', $VILLE);
				$oMSG->setData('NEWSLETTER', $NEWSLETTER);
				$oMSG->setData('OFFRES_ANNONCEURS', $OFFRES_ANNONCEURS);
				
				# Si c'est un prestataire ou un admin qui modifie la fiche alors on prend en compte les informations suivantes.
				if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
					$oMSG->setData('DESCRIPTION', $DESCRIPTION);
					$oMSG->setData('STATUT_PERSONNE', $STATUT_PERSONNE);
					$oMSG->setData('DEPARTEMENTS', $DEPARTEMENTS);
					$oMSG->setData('SIRET', $SIRET);
					$oMSG->setData('TARIFS', $TARIFS);
					$oMSG->setData('DISTANCE_PRESTATION_MAX', $DISTANCE_PRESTATION_MAX);
					$oMSG->setData('CV_VIDEO', $CV_VIDEO);
					$oMSG->setData('MATERIEL', $MATERIEL);				
				}
				
				# On spécifie qui est-ce qui modifie la fiche:
				$oMSG->setData('TYPE_PERSONNE', $_SESSION['compte']['TYPE_PERSONNE']);		

				$oPCS_personne->fx_maj_fiche_personnelle_selon_TYPE_PERSONNE($oMSG);

				if($echec_upload){
					$_SESSION['modification_fiche_membre']['message'].= "<span class='orange'>Le téléchargement de l'image a échoué.<br />Les autres informations ont été correctement enregistrées.</span><br />";
				}else{
					$_SESSION['modification_fiche_membre']['message'].= "<span class='valide'>Les modifications ont bien été effectuées.</span><br />";
				}
				
				# On modifie les informations de session si ce n'est pas un admin.
				if($_SESSION['compte']['TYPE_PERSONNE'] != "Admin"){
					
					$_SESSION['compte']['PSEUDO'] = $personne[0]['PSEUDO'];
					$_SESSION['compte']['NOM'] = $personne[0]['NOM'];
					$_SESSION['compte']['PRENOM'] = $personne[0]['PRENOM'];
					$_SESSION['compte']['CIVILITE'] = $personne[0]['CIVILITE'];
					$_SESSION['compte']['EMAIL'] = $personne[0]['EMAIL'];
				}
				# On redirige.
				if($_SESSION['page_actuelle'] == "modifier_fiche_membre"){
					header('Location: '.$oCL_page->getPage('modifier_fiche_membre', 'absolu')."?id_personne=".$ID_PERSONNE);
				}else{
					header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu'));
				}
				
			}else{
				$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>Aucune modification n'a été effectuée.</span><br />";

				if($_SESSION['page_actuelle'] == "modifier_fiche_membre"){
					header('Location: '.$oCL_page->getPage('modifier_fiche_membre', 'absolu')."?id_personne=".$ID_PERSONNE);
				}else{
					header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu'));
				}
				
			}
		# Fin de la vérification de la validité de l'ID_PERSONNE par rapport au mdp.
		}else{
			header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu'));
		}
	}
}else{
	# Si l'internaute n'est pas connecté.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	if(isset($_POST['form_pack_nom'])){
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_pack.php');		
		require_once('couche_metier/PCS_types.php');
		
		$oMSG = new MSG();
		$oPCS_pack = new PCS_pack();
		$oPCS_types = new PCS_types();
		
		# On récupère les types nécessaires:
		$oMSG->setData('ID_FAMILLE_TYPES', 'Type de pack');
		$types_pack = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
		
		$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/");
	
		$ID_PACK = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_id_pack']);
		$NOM = preg_replace($chaines_interdites, "", ucfirst(trim($_POST['form_pack_nom'])));
		$DESCRIPTION = nl2br(ucfirst(trim($_POST['form_pack_description'])));
		$TYPE_PACK = preg_replace($chaines_interdites, "", $_POST['form_pack_type_pack']);
		$PRIX_BASE = preg_replace($chaines_interdites, "", trim($_POST['form_pack_prix_base']));
		$DUREE = preg_replace($chaines_interdites, "", trim($_POST['form_pack_duree']));
		$SOUMIS_REDUCTIONS_PARRAINAGE = preg_replace($chaines_interdites, "", $_POST['form_pack_soumis_reduction_parrainage']);
		$GAIN_PARRAINAGE_MAX = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_gain_parrainage_max']);
		$REDUCTION = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_reduction']);
		$VISIBLE = preg_replace($chaines_interdites, "", $_POST['form_pack_visible']);

		$CV_VISIBILITE = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_cv_visibilite']);
		$CV_ACCESSIBLE = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_cv_accessible']);
		$NB_FICHES_VISITABLES = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_nb_fiches_visitables']);
		$CV_VIDEO_ACCESSIBLE = preg_replace($chaines_interdites, "", $_POST['form_pack_cv_video_accessible']);
		$ALERTE_NON_DISPONIBILITE = preg_replace($chaines_interdites, "", $_POST['form_pack_alerte_non_disponibilite']);
		$NB_DEPARTEMENTS_ALERTE = preg_replace($chaines_interdites, "", (int)$_POST['form_pack_nb_departements_alerte']);
		$PARRAINAGE_ACTIVE = preg_replace($chaines_interdites, "", $_POST['form_pack_parrainage_active']);
		$PREVISUALISATION_FICHES = preg_replace($chaines_interdites, "", $_POST['form_pack_previsualisation_fiches']);
		$CONTRATS_PDF = preg_replace($chaines_interdites, "", $_POST['form_pack_contrats_pdf']);
		$SUIVI = preg_replace($chaines_interdites, "", $_POST['form_pack_suivi']);
		$PUBS = preg_replace($chaines_interdites, "", $_POST['form_pack_pubs']);
		
		# On prépare nos variables nécessaires pour les messages d'erreurs.
		$_SESSION['modifier_fiche_pack']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
		$_SESSION['modifier_fiche_pack']['message'] = "";# On initialise et on rajoutera par dessus.
		
		$nb_erreur = 0;
		
		# On vérifie l'intégrité des données:
		
		# On commence par vérifier que les champs obligatoire ne soient pas vides.
		if(empty($NOM) || empty($DESCRIPTION) || empty($NB_FICHES_VISITABLES)){
			$_SESSION['modifier_fiche_pack']['message'].= "<span class='alert'>Un de champs obligatoire est vide.</span><br />";
			$nb_erreur++;
		}

		# On vérifie que le type de pack soit correct.
		$type_pack_ok = 0;
		foreach($types_pack as $key=>$type_pack){
			if($type_pack['ID_TYPES'] == $TYPE_PACK){
				$type_pack_ok++;
			}
		}
		
		# Si ça vaut 0 alors c'est que le type n'existe pas en BDD donc modification formulaire. Si plus de 1 alors c'est qu'on a deux types identiques en BDD. (impossible)
		if($type_pack_ok != 1){
			$_SESSION['modifier_fiche_pack']['message'].= "<span class='alert'>Le type de pack sélectionné n'existe pas.</span><br />";
			$nb_erreur++;
		}
		
		# On vire les virgules probables.
		$PRIX_BASE = str_replace(",", ".", $PRIX_BASE);
		
		# Si malgré ça le prix ne correspond pas.
		if(!is_numeric($PRIX_BASE)){
			$_SESSION['modifier_fiche_pack']['message'].= "<span class='alert'>Le prix de base est incorrect. <span class='petit'>(Ex: 250/999.0/99,5)</span></span><br />";
			$nb_erreur++;
		}
		
		
		# S'il n'y a pas d'erreurs.
		if($nb_erreur == 0){
			$oMSG->setData('ID_PACK', $ID_PACK);
			$oMSG->setData('NOM', $NOM);
			$oMSG->setData('DESCRIPTION', $DESCRIPTION);
			$oMSG->setData('TYPE_PACK', $TYPE_PACK);
			$oMSG->setData('PRIX_BASE', $PRIX_BASE);
			$oMSG->setData('DUREE', $DUREE);
			$oMSG->setData('SOUMIS_REDUCTIONS_PARRAINAGE', $SOUMIS_REDUCTIONS_PARRAINAGE);
			$oMSG->setData('GAIN_PARRAINAGE_MAX', $GAIN_PARRAINAGE_MAX);
			$oMSG->setData('REDUCTION', $REDUCTION);
			$oMSG->setData('VISIBLE', $VISIBLE);
			
			$oMSG->setData('CV_VISIBILITE', $CV_VISIBILITE);
			$oMSG->setData('CV_ACCESSIBLE', $CV_ACCESSIBLE);
			$oMSG->setData('NB_FICHES_VISITABLES', $NB_FICHES_VISITABLES);
			$oMSG->setData('CV_VIDEO_ACCESSIBLE', $CV_VIDEO_ACCESSIBLE);
			$oMSG->setData('ALERTE_NON_DISPONIBILITE', $ALERTE_NON_DISPONIBILITE);
			$oMSG->setData('NB_DEPARTEMENTS_ALERTE', $NB_DEPARTEMENTS_ALERTE);
			$oMSG->setData('PARRAINAGE_ACTIVE', $PARRAINAGE_ACTIVE);
			$oMSG->setData('PREVISUALISATION_FICHES', $PREVISUALISATION_FICHES);
			$oMSG->setData('CONTRATS_PDF', $CONTRATS_PDF);
			$oMSG->setData('SUIVI', $SUIVI);
			$oMSG->setData('PUBS', $PUBS);
			
			$oPCS_pack->fx_modifier_pack($oMSG);
			
			$_SESSION['modifier_fiche_pack']['message'].= "<span class='valide'>Le pack a été modifié.</span><br />";
			header('Location: '.$oCL_page->getPage('modifier_fiche_pack')."?id_pack=".$ID_PACK);
			
		}else{
			$_SESSION['modifier_fiche_pack']['message'].= "<span class='alert'>Le pack n'a pas été modifié.</span><br />";
			header('Location: '.$oCL_page->getPage('modifier_fiche_pack')."?id_pack=".$ID_PACK);
		}
		
		
	}else{
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	require_once('couche_metier/CL_date.php');
	
	$oMSG = new MSG();
	$oPCS_pack = new PCS_pack();
	$oCL_date = new CL_date();
	
	# On initialise nos variables.
	$_SESSION['historique_achat_pack']['message'] = "";
	$_SESSION['historique_achat_pack']['message_affiche'] = false;
	$nb_erreur = 0;
	
	if(isset($_POST['form_activer_pack_date_achat'])){
		# On vérifie l'intégrité des données.
		if(!($oCL_date->fx_verif_date($_POST['form_activer_pack_date_achat'], "en", true))){
			# Si la date fournie est incorrecte.
			$nb_erreur++;
			$_SESSION['historique_achat_pack']['message'].= "<span class='alert'>Le format de la date fournie est incorrect. <span class='petit gris'>(C'est pas bien de modifier le code source !)</span></span><br />";
		}
		
		# On vérifie qu'il y a bien un résulat pour la date d'achat fournie pour la personne en cours.
		$oMSG->setData('DATE_ACHAT', $_POST['form_activer_pack_date_achat']);
		$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
		
		
		$pack_personne = $oPCS_pack->fx_recuperer_pack_by_ID_PERSONNE_et_DATE_ACHAT($oMSG)->getData(1)->fetchAll();
		//$pack_personne[0]['nb_pack'] => COUNT(ID_PACK)
		
		if($pack_personne[0]['nb_pack'] != 1){
			# Le nombre de packs comptés est différent de 1, soit il n'y en a pas soit il y en a plusieurs, ce qui est théoriquement impossible.
			$nb_erreur++;
			$_SESSION['historique_achat_pack']['message'].= "<span class='alert'>Aucun pack n'a été trouvé pour la sélection faite.<br />Veuillez contacter le support si l'erreur ne vient pas de vous.</span><br />";
		}
		
		if($nb_erreur == 0){
			# Les informations fournies sont exactes.
			
			$now_en = date("Y-m-d H:i:s");
			# On récupère le pack activé en ce moment.
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			
			$pack_personne_actuel = $oPCS_pack->fx_recuperer_pack_actif_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			# On modifie le pack activé en ce moment.
			$oMSG->setData('ID_PACK', $pack_personne_actuel[0]['ID_PACK']);
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('DATE_ACHAT', $pack_personne_actuel[0]['DATE_ACHAT']);
			$oMSG->setData('DATE_FIN', $now_en);
			
			$oPCS_pack->fx_modifier_DATE_FIN_by_IDs($oMSG);
			
			# On modifie le pack activé sélectionné afin de l'activer.
			$oMSG->setData('ID_PACK', $pack_personne[0]['ID_PACK']);
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('DATE_ACHAT', $_POST['form_activer_pack_date_achat']);
			$oMSG->setData('DATE_DEBUT', $now_en);
			$oMSG->setData('DATE_FIN', $oCL_date->fx_ajouter_date($now_en, true, false, 'en', 'en', 0, $pack_personne[0]['DUREE']));
			// On ajoute la DUREE à la date du jour pour obtenir la date de fin.

			$oPCS_pack->fx_modifier_DATE_DEBUT_et_DATE_FIN_by_IDs($oMSG);
			
			
			# On recharge la session.
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$nouveau_pack_personne_actuel = $oPCS_pack->fx_recuperer_pack_actif_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			$_SESSION['pack']['activé'] = true;
			$_SESSION['pack']['DATE_ACHAT'] = $oCL_date->fx_ajouter_date($nouveau_pack_personne_actuel[0]['DATE_ACHAT'], true, false, 'en', 'fr');
			$_SESSION['pack']['ID_PACK'] = $nouveau_pack_personne_actuel[0]['ID_PACK'];
			$_SESSION['pack']['NOM'] = $nouveau_pack_personne_actuel[0]['NOM'];
			$_SESSION['pack']['TYPE_PACK'] = $nouveau_pack_personne_actuel[0]['TYPE_PACK'];
			$_SESSION['pack']['PRIX_BASE'] = $nouveau_pack_personne_actuel[0]['PRIX_BASE'];
			$_SESSION['pack']['DUREE'] = $nouveau_pack_personne_actuel[0]['DUREE'];
			$_SESSION['pack']['CV_VISIBILITE'] = $nouveau_pack_personne_actuel[0]['CV_VISIBILITE'];
			$_SESSION['pack']['CV_ACCESSIBLE'] = $nouveau_pack_personne_actuel[0]['CV_ACCESSIBLE'];
			$_SESSION['pack']['NB_FICHES_VISITABLES'] = $nouveau_pack_personne_actuel[0]['NB_FICHES_VISITABLES'];# On ne charge pas ici le NB_FICHES_VISITABLES du pack mais celui 
																						 # de la table pack_personne, voir couche_metier/VIEW_pack.php.
			$_SESSION['pack']['CV_VIDEO_ACCESSIBLE'] = $nouveau_pack_personne_actuel[0]['CV_VIDEO_ACCESSIBLE'];
			$_SESSION['pack']['ALERTE_NON_DISPONIBILITE'] = $nouveau_pack_personne_actuel[0]['ALERTE_NON_DISPONIBILITE'];
			$_SESSION['pack']['NB_DEPARTEMENTS_ALERTE'] = $nouveau_pack_personne_actuel[0]['NB_DEPARTEMENTS_ALERTE'];
			$_SESSION['pack']['PARRAINAGE_ACTIVE'] = $nouveau_pack_personne_actuel[0]['PARRAINAGE_ACTIVE'];
			$_SESSION['pack']['PREVISUALISATION_FICHES'] = $nouveau_pack_personne_actuel[0]['PREVISUALISATION_FICHES'];
			$_SESSION['pack']['CONTRATS_PDF'] = $nouveau_pack_personne_actuel[0]['CONTRATS_PDF'];
			$_SESSION['pack']['SUIVI'] = $nouveau_pack_personne_actuel[0]['SUIVI'];
			$_SESSION['pack']['PUBS'] = $nouveau_pack_personne_actuel[0]['PUBS'];
			$_SESSION['pack']['date_fin_validite'] = $oCL_date->fx_ajouter_date($nouveau_pack_personne_actuel[0]['DATE_FIN'], true, false, 'en', 'fr');
			$_SESSION['pack']['date_fin_validite_formatee'] = $oCL_date->fx_ajouter_date($nouveau_pack_personne_actuel[0]['DATE_FIN'], true, true, 'en', 'fr');
			
			
			# On envoi l'email.
			$additional_headers = "From: noreply@liveanim.fr \r\n";
			$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
			$destinataires = $_SESSION['compte']['EMAIL'];
			$sujet = utf8_decode("LiveAnim [Activation d'un pack]");
			
			$message = "------------------------------\n";
			$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
			$message.= "------------------------------\n\n";
			$message.= utf8_decode("Bonjour ".$_SESSION['compte']['PSEUDO'].", \n");
			$message.= utf8_decode("Vous venez d'activer manuellement le pack ".$nouveau_pack_personne_actuel[0]['NOM']." acheté le ".$_SESSION['pack']['DATE_ACHAT'].". \n\n");
			$message.= utf8_decode("Ce pack est donc à présent utilisé, sa date de fin de validité est le ".$_SESSION['pack']['date_fin_validite'].". \n");
			$message.= utf8_decode("Le pack utilisé précédemment a donc été stoppé.\n\n");
			$message.= utf8_decode("Si ce n'est pas vous qui avez activé ce pack, vous pouvez contacter notre service client à l'adresse suivante:\n");
			$message.= utf8_decode("contact@liveanim.com \n\n");
			$message.= utf8_decode("L'adresse IP utilisée lors de l'activation de votre pack est: ".$_SERVER["REMOTE_ADDR"]."\n\n");
			$message.= utf8_decode("------------------------------\n\n\n");
			$message.= utf8_decode("LiveAnim vous remercie de votre confiance et vous souhaite de bien profiter de votre nouveau pack !\n\n");
			$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
			
			mail($destinataires, $sujet, $message, $additional_headers);
			
			# On redirige et on affiche.
			$_SESSION['historique_achat_pack']['message'].= "<span class='valide'>Votre pack a été correctement activé.<br />Vous pouvez profiter de ses fonctionnalités dès à présent.<br />Un email vous a été envoyé.</span><br />";
			header('Location: '.$oCL_page->getPage('historique_achat_pack', 'absolu')."#achat_pack");
			
		}else{
			
			header('Location: '.$oCL_page->getPage('historique_achat_pack', 'absolu')."#achat_pack");
		}
	}else{
		# L'appel à la page n'a pas été fait depuis le formulaire, redirection.
		header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
	}

}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
session_start();

require_once('couche_metier/CL_page.php');
$oCL_page = new CL_page();

if(isset($_POST['form_inscription_login'])){
	# On vérifie que le formulaire arrive bien du btn inscription.

	# On formate correctement les données:
	$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
	$parrain = preg_replace ($chaines_interdites, "", $_POST['form_inscription_parrain']);
	$login = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_inscription_login'])));
	$nom = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_inscription_nom'])));
	$prenom = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_inscription_prenom'])));
	$civilite = preg_replace ($chaines_interdites, "", $_POST['form_inscription_civilite']);
	$type_personne = preg_replace ($chaines_interdites, "", $_POST['form_inscription_type_personne']);
	$mdp = preg_replace ($chaines_interdites, "", $_POST['form_inscription_mdp']);
	$mdp2 = preg_replace ($chaines_interdites, "", $_POST['form_inscription_mdp2']);
	$email = preg_replace ($chaines_interdites, "", trim($_POST['form_inscription_email']));
	$email2 = preg_replace ($chaines_interdites, "", trim($_POST['form_inscription_email2']));
	$cgu = preg_replace ($chaines_interdites, "", $_POST['form_inscription_cgu']);
	$newsletter = preg_replace ($chaines_interdites, "", $_POST['form_inscription_newsletter']);
	$offres_annonceurs = preg_replace ($chaines_interdites, "", $_POST['form_inscription_offres_annonceurs']);
	$connaissance_site = preg_replace ($chaines_interdites, "", $_POST['form_inscription_connaissance_site']);
	
	
	
	# On prépare le terrain pour sauvegarder les informations transmises. On les videra si elles sont incorrectes !
	$_SESSION['inscription'] = array();
	$_SESSION['inscription']['login'] = $login;
	$_SESSION['inscription']['nom'] = $nom;
	$_SESSION['inscription']['prenom'] = $prenom;
	$_SESSION['inscription']['civilite'] = $civilite;
	$_SESSION['inscription']['type_personne'] = $type_personne;
	$_SESSION['inscription']['mdp'] = $mdp;
	$_SESSION['inscription']['mdp2'] = $mdp2;
	$_SESSION['inscription']['email'] = $email;
	$_SESSION['inscription']['email2'] = $email2;
	$_SESSION['inscription']['cgu'] = $cgu;
	$_SESSION['inscription']['newsletter'] = $newsletter;
	$_SESSION['inscription']['offres_annonceurs'] = $offres_annonceurs;
	$_SESSION['inscription']['connaissance_site'] = $connaissance_site;
	$_SESSION['inscription']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
	$_SESSION['inscription']['message'] = "";# On initialise et on rajoutera par dessus.
	
	$nb_erreur = 0;
	
	# On vérifie que ce qui est nécessaire n'est pas vide.
	if(empty($login) || empty($mdp) || empty($email) || empty($nom) || empty($prenom)){

		$_SESSION['inscription']['message'].= "<span class='alert'>Un des champs requis est vide.</span><br />";
		
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	# On vérifie que l'ID_PARRAIN fournit est correct.
	if($parrain == "0" || $parrain == ""){
		# L'ID_PARRAIN fournit est incorrect/nul mais n'annulera pas pour autant l'inscription.
		$parrain = "Aucun";
		unset($_SESSION['parrain']);# On supprime le parrain s'il y en avait un.
	}
	
	# On vérifie que la civilité sélectionnée est correcte.
	$civilites = array("Mr", "Mme", "Mlle");
	if(!in_array($civilite, $civilites)){
		# L'utilisateur a modifié le code source, on l'envoi chier.
		$_SESSION['inscription']['civilite'] = "";
		
		$_SESSION['inscription']['message'].= "<span class='alert'>La modification du code source dans le but de passer les protections du système est interdite.<br />Votre IP a été enregistrée, nous vous rappellons que ce que vous faites est punissable aux yeux de la loi.</span><br />";
		
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	# On vérifie que le type de personne fournit est correct: ----------------------------------------------------------- /!\ Si rajout de type_personne dans la BDD, rajouter ici aussi !
	$types_personne = array("Prestataire", "Organisateur");
	if(!in_array($type_personne, $types_personne)){
		# L'utilisateur a modifié le code source, on l'envoi chier.
		$_SESSION['inscription']['type_personne'] = "";
		
		$_SESSION['inscription']['message'].= "<span class='alert'>La modification du code source dans le but de passer les protections du système est interdite.<br />Votre IP a été enregistrée, nous vous rappellons que ce que vous faites est punissable aux yeux de la loi.</span><br />";
		
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	# On vérifie les tailles des champs saisis.
	if(strlen($login) < 4){
		$_SESSION['inscription']['login'] = "";
		$_SESSION['inscription']['login'] = "";
		$_SESSION['inscription']['message'].= "<span class='alert'>Votre pseudo est trop court. 4 caractères minimum.</span><br />";
		
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	if(strlen($login) > 20){
		$_SESSION['inscription']['login'] = "";
		$_SESSION['inscription']['login'] = "";
		$_SESSION['inscription']['message'].= "<span class='alert'>Votre pseudo est trop long. 20 caractères maximum.</span><br />";
		
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	if(strlen($mdp) < 4){
		$_SESSION['inscription']['mdp'] = "";
		$_SESSION['inscription']['mdp2'] = "";
		$_SESSION['inscription']['message'].= "<span class='alert'>Votre mot de passe est trop court. 4 caractères minimum.</span><br />";
		
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	if(strlen($mdp) > 20){
		$_SESSION['inscription']['mdp'] = "";
		$_SESSION['inscription']['mdp2'] = "";
		$_SESSION['inscription']['message'].= "<span class='alert'>Votre mot de passe est trop long. 20 caractères maximum.</span><br />";
		
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	# On vérifie que la case des CGU a bien été cochée:
	if($cgu == 0){
		$_SESSION['inscription']['message'].= "<span class='alert'>Vous n'avez pas validé les Conditions Générales d'Utilisation.</span><br />";
		
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	# On vérifie que les mdp soient bien égaux:
	if(!($mdp == $mdp2)){
		$_SESSION['inscription']['mdp'] = "";
		$_SESSION['inscription']['mdp2'] = "";
		$_SESSION['inscription']['message'].= "<span class='alert'>Les deux mots de passe ne correspondent pas. Veuillez les retaper.</span><br />";
		
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	
	
	if(!($email == $email2)){
		$_SESSION['inscription']['message'].= "<span class='alert'>Les deux emails ne correspondent pas. Veuillez les corriger.</span><br />";
		
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	# On vérifie que le login et l'email n'existent pas déjà:
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/MSG.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	
	$oMSG->setData('PSEUDO', $login);
	$oMSG->setData(1, "");
	
	$nb_pseudo = $oPCS_personne->fx_compter_pseudo_by_PSEUDO($oMSG)->getData(1)->fetchAll();
	if($nb_pseudo[0]['nb_pseudo'] > 0){
	
		$_SESSION['inscription']['login'] = "";
		
		$_SESSION['inscription']['message'].= "<span class='alert'>L'identifiant que vous avez rentré est déjà utilisé.</span><br />";
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	$oMSG->setData('EMAIL', $email);
	$oMSG->setData(1, "");
	
	$nb_email = $oPCS_personne->fx_compter_email_by_EMAIL($oMSG)->getData(1)->fetchAll();
	if($nb_email[0]['nb_email'] > 0){
	
		$_SESSION['inscription']['email'] = "";
		$_SESSION['inscription']['email2'] = "";
		
		$_SESSION['inscription']['message'].= "<span class='alert'>L'email que vous avez rentré est déjà utilisé.</span><br />";
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	# On teste si l'adresse e-mail est à un format valide.
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		
		$_SESSION['inscription']['email'] = "";
		$_SESSION['inscription']['email2'] = "";
		
		$_SESSION['inscription']['message'].= "<span class='alert'>L'email que vous avez rentré est invalide.</span><br />";
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
		$nb_erreur++;
	}
	
	# On teste les valeurs des options.
	
	# On teste la valeur de la newsletter.
	
	if($newsletter == "on"){
		$_SESSION['inscription']['newsletter'] = true;
		$newsletter = 1;
	}else{
		$_SESSION['inscription']['newsletter'] = false;
		$newsletter = 0;
	}
	
	# On teste la valeur des offres_annonceurs.
	if($offres_annonceurs == "on"){
		$_SESSION['inscription']['offres_annonceurs'] = true;
		$offres_annonceurs = 1;
	}else{
		$_SESSION['inscription']['offres_annonceurs'] = false;
		$offres_annonceurs = 0;
	}
	
	

	# On a effectué toutes les vérifications, il ne reste plus qu'à crypter les mots de passes et envoyer les requêtes d'insertion.
	if($nb_erreur == 0){
	
		require_once 'couche_metier/CL_cryptage.php';
		$oCL_cryptage = new CL_cryptage();
		
		# On crypte le mot de passe.
		$mdp_crypte = $oCL_cryptage->Cryptage($mdp, $login);
		
		# On récupère l'IP
		$IP = $_SERVER["REMOTE_ADDR"];
		
		# On génère une clé d'activation aléatoire:
		$cle_activation = sha1(microtime(true)*152000);
		
		# On prépare le message contenant les informations.
		$oMSG->setData('PSEUDO', $login);
		$oMSG->setData('NOM', $nom);
		$oMSG->setData('PRENOM', $prenom);
		$oMSG->setData('CIVILITE', $civilite);
		$oMSG->setData('TYPE_PERSONNE', $type_personne);
		$oMSG->setData('MDP', utf8_encode($mdp_crypte));# On encode le mot de passe sinon il n'est pas compris par la BDD.
		$oMSG->setData('EMAIL', $email);
		$oMSG->setData('NEWSLETTER', $newsletter);
		$oMSG->setData('OFFRES_ANNONCEURS', $offres_annonceurs);
		$oMSG->setData('CONNAISSANCE_SITE', $connaissance_site);
		$oMSG->setData('PARRAIN', $parrain);
		$oMSG->setData('VISIBLE', false);# Le compte n'est pas utilisable en l'état, nécessite une activation par e-mail.
		$oMSG->setData('CLE_ACTIVATION', $cle_activation);
		
		# On crée le compte.
		$ID_PERSONNE = $oPCS_personne->fx_creer_compte($oMSG)->getData(1);
		
		# On regarde si l'IP existe déjà dans la table ip.
		$nb_IP = $oPCS_personne->fx_compter_IPs_by_ID_IP($oMSG)->getData(1)->fetchAll();
		
		# On regarde si l'IP existe déjà dans la table ip_personne (cookie).
		$oMSG->setData('IP_COOKIE', $_COOKIE['lang']);# On récupère l'IP du cookie et on vérifie aussi qu'elle ne se trouve pas déjà dans la table ip_personne.
		$nb_IP_cookie = $oPCS_personne->fx_compter_IPs_by_IP_COOKIE($oMSG)->getData(1)->fetchAll();

		if($nb_IP[0]['nb_IP'] == 0 && $nb_IP_cookie[0]['nb_IP'] == 0){
			# Aucune IP n'existe, on crée le compte normalement, il sera activable par email.
						
			# On crée l'ID_IP via l'IP détectée par le cookie. Si c'est la même ça ne fonctionnera pas mais n'empèchera pas le reste du script.
			$oMSG->setData('ID_IP', $_COOKIE['lang']);
			$oPCS_personne->fx_creer_IP($oMSG);
			
			# On crée l'ID_IP via l'IP détectée par le serveur.
			$oMSG->setData('ID_IP', $IP);
			$oPCS_personne->fx_creer_IP($oMSG);
			
			# On lie l'IP.
			$now = date('Y-m-d H:i:s');
			$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
			$oMSG->setData('IP_COOKIE', $_COOKIE['lang']);# lang => IP, on l'appelle langue pour tromper les tricheurs idiots.
			$oMSG->setData('COOKIE_DETRUIT', $_COOKIE['admin']);# admin => sert à vérifier le premier cookie, on sait si lang a été détruit ou pas.
			$oMSG->setData('DATE_CONNEXION', $now);
			# Le pack dure un mois.
			$DATE_FIN = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m")+1, date("d"),  date("Y")));
			
			$oPCS_personne->fx_lier_IP_et_PERSONNE($oMSG);
			
			# On récupère la personne crée.
			$Personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();# On récupère les informations du membre crée.
			
			# On récupère le seul pack gratuit qui doit exister.
			require_once('couche_metier/PCS_pack.php');
			$oPCS_pack = new PCS_pack();
			
			$oMSG->setData('TYPE_PACK', "Gratuit");
			$oMSG->setData('limit', "LIMIT 0,1");
			
			$pack = $oPCS_pack->fx_recuperer_pack_by_TYPE_PACK_et_LIMIT($oMSG)->getData(1)->fetchAll();
			
			# On attribue le pack à la personne.
			$oMSG->setData('ID_PACK', $pack[0]['ID_PACK']);
			$oMSG->setData('DATE_ACHAT', $now);
			$oMSG->setData('DATE_DEBUT', $now);
			$oMSG->setData('DATE_FIN', $DATE_FIN);
			$oMSG->setData('REDUCTION', 0);
			$oMSG->setData('DATAS_PAYPAL', '');
			
			
			$oPCS_pack->fx_lier_pack_personne($oMSG);
					
			# On envoi l'email:		
			$additional_headers = "From: noreply@liveanim.fr \r\n";
			$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
			$destinataires = $email;
			$sujet = utf8_decode("Inscription à LiveAnim [Activation du compte]");
			
			$message = "------------------------------\n";
			$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
			$message.= "------------------------------\n\n";
			$message.= utf8_decode("Bonjour ".$login.", \n");
			$message.= utf8_decode("Votre inscription à ".$oCL_page->getPage('accueil')." a bien été effectuée. \n\n");
			$message.= utf8_decode("Pour pouvoir utiliser votre compte sur ".$oCL_page->getPage('accueil', 'absolu')." vous devez d'abord activer votre compte en cliquant sur le lien ci-dessous.\n");
			$message.= utf8_decode($oCL_page->getPage('inscription', 'absolu')."?email=".$email."&cle_activation=".$cle_activation." \n\n");
			$message.= utf8_decode("Voici un rappel de votre identifiant et de votre mot de passe: \n");
			$message.= utf8_decode("Identifiant de connexion: ".$login."\n");
			$message.= utf8_decode("Mot de passe: ".$mdp."\n\n");
			$message.= utf8_decode("L'adresse IP utilisée pour la création de votre compte est: ".$IP."\n\n");
			$message.= utf8_decode("------------------------------\n\n\n");
			$message.= utf8_decode("LiveAnim vous remercie de votre confiance et vous rappelle que cette inscription est totalement gratuite !\n\n");
			$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
			
			if(mail($destinataires, $sujet, $message, $additional_headers)){
				# Si le mail a été correctement envoyé:			
				$_SESSION['compte']['première_visite_message'] = 
					"<span class='valide'>Bonjour ".$login.", votre inscription s'est déroulée correctement.<br />".
					"Un e-mail vous a été envoyé à l'adresse ".$email.", il contient la clé d'activation de votre compte et vous rappelle vos identifiant et mot de passe.<br /><br />";
			}else{
				$_SESSION['compte']['première_visite_message'] = 
					"<span class='valide'>Bonjour ".$login.", votre inscription s'est déroulée correctement.<br />".
					"<span class='alert'>Malheureusement, notre service d'envoi d'email n'a pas fonctionné correctement <span class='petit'>[Serveur mail HS]</span> et aucun e-mail d'inscription ne vous a été envoyé. Cela ne change rien au compte qui a été correctement crée. Vous devez néanmois activer votre compte en cliquant sur ce lien: <br />".
					$oCL_page->getPage('inscription', 'absolu')."?email=".$email."&cle_activation=".$cle_activation." <br />Nous nous excusons pour le désagrément occasionné.</span><br /><br />";
			}
		}else{
			# L'IP de création du compte existe déjà donc on va faire une activation du compte par administration.
			
			# On crée l'ID_IP via l'IP détectée par le cookie. Si c'est la même ça ne fonctionnera pas mais n'empèchera pas le reste du script.
			$oMSG->setData('ID_IP', $_COOKIE['lang']);
			$oPCS_personne->fx_creer_IP($oMSG);
			
			# On crée l'ID_IP via l'IP détectée par le serveur.
			$oMSG->setData('ID_IP', $IP);
			$oPCS_personne->fx_creer_IP($oMSG);
			
			# On lie l'IP.
			$now = date('Y-m-d H:i:s');
			$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
			$oMSG->setData('IP_COOKIE', $_COOKIE['lang']);# lang => IP, on l'appelle langue pour tromper les tricheurs idiots.
			$oMSG->setData('COOKIE_DETRUIT', $_COOKIE['admin']);# admin => sert à vérifier le premier cookie, on sait si lang a été détruit ou pas.
			$oMSG->setData('DATE_CONNEXION', $now);
			# Le pack dure un mois.
			$DATE_FIN = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m")+1, date("d"),  date("Y")));
			
			$oPCS_personne->fx_lier_IP_et_PERSONNE($oMSG);
			
			# On récupère la personne crée.
			$Personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();# On récupère les informations du membre crée.
			
			# On récupère le seul pack gratuit qui doit exister.
			require_once('couche_metier/PCS_pack.php');
			$oPCS_pack = new PCS_pack();
			
			$oMSG->setData('TYPE_PACK', "Gratuit");
			$oMSG->setData('limit', "LIMIT 0,1");
			
			$pack = $oPCS_pack->fx_recuperer_pack_by_TYPE_PACK_et_LIMIT($oMSG)->getData(1)->fetchAll();
			
			# On attribue le pack à la personne.
			$oMSG->setData('ID_PACK', $pack[0]['ID_PACK']);
			$oMSG->setData('DATE_ACHAT', $now);
			$oMSG->setData('DATE_DEBUT', $now);
			$oMSG->setData('DATE_FIN', $DATE_FIN);
			$oMSG->setData('REDUCTION', 0);
			$oMSG->setData('DATAS_PAYPAL', '');
			
			$oPCS_pack->fx_lier_pack_personne($oMSG);
					
			# On envoi l'email:		
			$additional_headers = "From: noreply@liveanim.fr \r\n";
			$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
			$destinataires = $email;
			$sujet = utf8_decode("Inscription à LiveAnim");
			
			$message = "------------------------------\n";
			$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
			$message.= "------------------------------\n\n";
			$message.= utf8_decode("Bonjour ".$login.", \n");
			$message.= utf8_decode("Votre inscription à ".$oCL_page->getPage('accueil')." a bien été effectuée. \n\n");
			$message.= utf8_decode("Pour pouvoir utiliser votre compte sur ".$oCL_page->getPage('accueil', 'absolu')." vous devez d'abord attendre que l'équipe de modération active votre compte.\n\n");
			$message.= utf8_decode("Voici un rappel de votre identifiant et de votre mot de passe: \n");
			$message.= utf8_decode("Identifiant de connexion: ".$login."\n");
			$message.= utf8_decode("Mot de passe: ".$mdp."\n\n");
			$message.= utf8_decode("L'adresse IP utilisée pour la création de votre compte est: ".$IP."\n\n");
			$message.= utf8_decode("------------------------------\n\n\n");
			$message.= utf8_decode("LiveAnim vous remercie de votre confiance et vous rappelle que cette inscription est totalement gratuite !\n\n");
			$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
			
			if(mail($destinataires, $sujet, $message, $additional_headers)){
				# Si le mail a été correctement envoyé:			
				$_SESSION['compte']['première_visite_message'] = 
					"<span class='valide'>Bonjour ".$login.", votre inscription s'est déroulée correctement. L'activation de votre compte se fera manuellement par l'équipe de modération.<br />".
					"Un e-mail vous a été envoyé à l'adresse ".$email.", il vous rappelle vos identifiant et mot de passe.<br /><br />";
			}else{
				$_SESSION['compte']['première_visite_message'] = 
					"<span class='valide'>Bonjour ".$login.", votre inscription s'est déroulée correctement.<br />".
					"<span class='alert'>Malheureusement, notre service d'envoi d'email n'a pas fonctionné correctement <span class='petit'>[Serveur mail HS]</span> et aucun e-mail d'inscription ne vous a été envoyé. Cela ne change rien au compte qui a été correctement crée.<br />".
					"L'activation de votre compte se fera manuellement par notre équipe de modération.<br />Nous nous excusons pour le désagrément occasionné.</span><br /><br />";
			}
		}
		
		$Personne = null;# On détruit les informations.
		
		$_SESSION['compte']['première_visite'] = true;# On redirigera vers le bon contenu.
		$_SESSION['inscription'] = array();# On vide les valeurs rentrées par l'utilisateur.
		header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
	}

}else{
	$_SESSION['inscription'] = array();
	$_SESSION['inscription']['message_affiche'] = false;
	$_SESSION['inscription']['message'] = "<span class='alert'>Vous devez soumettre le formulaire d'inscription via le bouton de validation qui se trouve en bas de cette page, merci.</span>";
	header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
}

?><?php
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

	if(isset($_POST['form_modifier_mdp_ancien_mdp'])){
		$ancien_MDP = $_POST['form_modifier_mdp_ancien_mdp'];
		$nouveau_MDP = $_POST['form_modifier_mdp_nouveau_mdp'];
		$nouveau_MDP_bis = $_POST['form_modifier_mdp_nouveau_mdp_bis'];
		
		# On prépare les variables.
		$_SESSION['modifier_mdp']['message'] = "";
		$_SESSION['modifier_mdp']['message_affiche'] = false;
		$nb_erreur = 0;
		
		# On vérifie qu'aucun champ ne soit vide.
		if(empty($ancien_MDP) || empty($nouveau_MDP) || empty($nouveau_MDP_bis)){
			$_SESSION['modifier_mdp']['message'].= "<span class='alert'>Un des champs est vide.</span><br />";
			$nb_erreur++;
		}
		
		if($nouveau_MDP != $nouveau_MDP_bis){
			$_SESSION['modifier_mdp']['message'].= "<span class='alert'>Le nouveau mot de passe n'a pas été correctement réécrit.</span><br />";
			$nb_erreur++;
		}
		
		if(strlen($nouveau_MDP) < 4){
			$_SESSION['modifier_mdp']['message'].= "<span class='orange'>La longueur du nouveau mot de passe doit être de 4 caractères au minimum.</span><br />";
			$nb_erreur++;
		}
		
		if(strlen($nouveau_MDP) > 20){
			$_SESSION['modifier_mdp']['message'].= "<span class='orange'>La longueur du nouveau mot de passe doit être de 20 caractères au maximum.</span><br />";
			$nb_erreur++;
		}
		
		if($nb_erreur == 0){
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_personne.php');
			require_once('couche_metier/CL_cryptage.php');

			$oMSG = new MSG();
			$oPCS_personne = new PCS_personne();
			$oCL_cryptage = new CL_cryptage();
			
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			$oMSG->setData('MDP', utf8_encode($oCL_cryptage->Cryptage($_POST['form_modifier_mdp_ancien_mdp'], $_SESSION['compte']['PSEUDO'])));

			$nb_personne = $oPCS_personne->fx_compter_personne_by_ID_PERSONNE_et_MDP($oMSG)->getData(1)->fetchAll();
			
			if($nb_personne[0]['nb_personne'] == 1){
				# Le mot de passe correspond au compte.
				$oMSG->setData('MDP', utf8_encode($oCL_cryptage->Cryptage($_POST['form_modifier_mdp_nouveau_mdp'], $_SESSION['compte']['PSEUDO'])));
				
				$oPCS_personne->fx_changer_mdp($oMSG);
				
				$IP = $_SERVER["REMOTE_ADDR"];
				
				# On envoi l'email:		
				$additional_headers = "From: noreply@liveanim.fr \r\n";
				$additional_headers.= "Content-Type: text/plain; charset=iso-8859-1";
				$destinataires = $_SESSION['compte']['EMAIL'];
				$sujet = utf8_decode("LiveAnim [Modification de votre Mot de passe]");
				
				$message = "------------------------------\n";
				$message.= utf8_decode("Vous recevez cet e-mail de la part d'un service automatique, ne répondez pas à cet e-mail. \n");
				$message.= "------------------------------\n\n";
				$message.= utf8_decode("Bonjour ".$_SESSION['compte']['PSEUDO'].", \n");
				$message.= utf8_decode("Votre mot de passe a été modifié depuis votre espace personnel. \n\n");
				$message.= utf8_decode("Si ce n'est pas vous qui avez effectué ce changement sachez que vous pouvez faire une récupération de mot de passe à cette adresse:\n");
				$message.= utf8_decode($oCL_page->getPage('recuperation_mdp', 'absolu')." \n\n");
				$message.= utf8_decode("La modification de votre mot de passe a été effectuée le ".date("Y-m-d")." à ".date("H:i:s")." à l'adresse IP ".$IP."\n\n");
				$message.= utf8_decode("------------------------------\n\n\n");
				$message.= utf8_decode("Mail envoyé le ").date("d-m-Y").utf8_decode(" à ").date("H")."h".date("i")."mn.\n\n";
				
				if(mail($destinataires, $sujet, $message, $additional_headers)){
					# Si le mail a été correctement envoyé:			
					$_SESSION['modifier_mdp']['message'].= "<span class='valide'>Le mot de passe a été changé. Un email vous a été envoyé.</span><br />";
				}else{
					$_SESSION['modifier_mdp']['message'].= "<span class='valide'>Le mot de passe a été changé.</span><br />";
				}
			}else{
				$_SESSION['modifier_mdp']['message'].= "<span class='alert'>L'ancien mot de passe n'est pas correct.</span><br />";
			}
			
			header('Location: '.$oCL_page->getPage('modifier_mdp', 'absolu'));
			
		}else{
			header('Location: '.$oCL_page->getPage('modifier_mdp', 'absolu'));
			$_SESSION['modifier_mdp']['message'].= "<span class='alert'>Le mot de passe n'a pas été modifié.</span><br />";
		}
		
	}
	
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	# On recharge le compte actuel au cas où le forfait ai changé.
	require_once('couche_metier/MSG.php');
	require_once('couche_metier/PCS_personne.php');
	require_once('couche_metier/CL_date.php');
	require_once('couche_metier/PCS_pack.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	$oCL_date = new CL_date();
	$oPCS_pack = new PCS_pack();

	$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
	
	$personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
	
	# MAJ du compte.
	$_SESSION['compte']['PSEUDO'] = $personne[0]['PSEUDO'];
	$_SESSION['compte']['NOM'] = $personne[0]['NOM'];
	$_SESSION['compte']['PRENOM'] = $personne[0]['PRENOM'];
	$_SESSION['compte']['CIVILITE'] = $personne[0]['CIVILITE'];
	$_SESSION['compte']['EMAIL'] = $personne[0]['EMAIL'];
	$_SESSION['compte']['TYPE_PERSONNE'] = $personne[0]['TYPE_PERSONNE'];
	$_SESSION['compte']['PARRAIN'] = $personne[0]['PARRAIN'];
	$_SESSION['compte']['REDUCTION'] = $personne[0]['REDUCTION'];
	
	$now = date("d-m-Y");
	$oNOW = new DateTime( $now );
	$now = $oNOW->format("Ymd");
	
	# MAJ du pack utilisé.
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
?><?php
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
?><?php
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
	require_once('couche_metier/PCS_personne.php');
	
	$oMSG = new MSG();
	$oPCS_personne = new PCS_personne();
	
	$oMSG->setData('CLE_ACTIVATION', "");
	
	$comptes_inactifs = $oPCS_personne->fx_recuperer_comptes_non_actives($oMSG)->getData(1)->fetchAll();
	
	function fx_recuperer_infos_by_ID_IP($ID_IP){
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		
		$oMSG->setData('ID_IP', $ID_IP);
		
		return $infos_ID_IP = $oPCS_personne->fx_recuperer_infos_by_ID_IP($oMSG)->getData(1)->fetchAll();
	
	}
	
	function fx_recuperer_infos_by_IP_COOKIE($IP_COOKIE){
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		
		$oMSG->setData('IP_COOKIE', $IP_COOKIE);
		
		return $infos_IP_COOKIE = $oPCS_personne->fx_recuperer_infos_by_IP_COOKIE($oMSG)->getData(1)->fetchAll();
	
	}

}
?><?php
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
	require_once('couche_metier/PCS_types.php');
	
	$oMSG = new MSG();
	$oPCS_types = new PCS_types();
	
	$oMSG->setData('ID_FAMILLE_TYPES', 'Type de pack');
	
	$types_packs = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?><?php
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
	
	if(isset($_GET['id_annonce'])){
		$ID_ANNONCE = (int)$_GET['id_annonce'];
		if($ID_ANNONCE != 0){
			require_once('couche_metier/MSG.php');
			require_once('couche_metier/PCS_annonce.php');
			require_once('couche_metier/PCS_contrat.php');
			require_once('couche_metier/PCS_pack.php');
			require_once('couche_metier/PCS_personne.php');
			require_once('couche_metier/PCS_types.php');
			require_once('couche_metier/CL_date.php');

			$oMSG = new MSG();
			$oPCS_annonce = new PCS_annonce();
			$oPCS_contrat = new PCS_contrat();
			$oPCS_pack = new PCS_pack();
			$oPCS_personne = new PCS_personne();
			$oPCS_types = new PCS_types();
			$oCL_date = new CL_date();
		
			# On récupère l'annonce.
			$oMSG->setData('ID_ANNONCE', $ID_ANNONCE);
			$oMSG->setData('VISIBLE', 1);
			
			# On modifie le nom de la variable car il y a un culbutage avec une autre variable $annonce...
			$annonce_courante = $oPCS_annonce->fx_recuperer_annonce_complete_by_ID_ANNONCE($oMSG)->getData(1)->fetchAll();
			
			# On récupère les informations relatives à l'adresse de la personne.
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			
			$personne_courante = $oPCS_personne->fx_recuperer_adresse_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			# On vérifie que l'annonce ait bien, rapportée un résultat.
			if(isset($annonce_courante[0]['ID_ANNONCE'])){
				
				$id_annonce_ok = 1;
				# On met en forme les données.
				$annonce_courante[0]['DATE_ANNONCE'] = $oCL_date->fx_ajouter_date($annonce_courante[0]['DATE_ANNONCE'], true, false, 'en', 'fr');
				$annonce_courante[0]['DATE_DEBUT'] = $oCL_date->fx_ajouter_date($annonce_courante[0]['DATE_DEBUT'], true, false, 'en', 'fr');
				$annonce_courante[0]['DATE_FIN'] = $oCL_date->fx_ajouter_date($annonce_courante[0]['DATE_FIN'], true, false, 'en', 'fr');
				
				$annonce_courante[0]['DATE_ANNONCE'] = substr(str_replace(array(':', ' '), array('h', ' à '), $annonce_courante[0]['DATE_ANNONCE']), 0, -3);
				$annonce_courante[0]['DATE_DEBUT'] = substr(str_replace(array(':', ' '), array('h', ' à '), $annonce_courante[0]['DATE_DEBUT']), 0, -3);
				$annonce_courante[0]['DATE_FIN'] = substr(str_replace(array(':', ' '), array('h', ' à '), $annonce_courante[0]['DATE_FIN']), 0, -3);
				
				$annonce_courante[0]['DESCRIPTION'] = str_replace(array('<br>', '<br />'), '', $annonce_courante[0]['DESCRIPTION']);
				$annonce_courante[0]['ARTISTES_RECHERCHES'] = str_replace(array('<br>', '<br />'), '', $annonce_courante[0]['ARTISTES_RECHERCHES']);
				
				# On vérifie le type de la personne.
				if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
					# On effectue les vérifications avant la décrémentation du nombre d'annonce visitables.
					if(!in_array($annonce_courante[0]['ID_ANNONCE'], $_SESSION['compte']['annonces_visitées'])){
						# Si l'id_annonce en cours n'est pas dans le tableau alors c'est que le prestataire n'a jamais visité cette annonce.
						if($_SESSION['pack']['NB_FICHES_VISITABLES'] > 0){
							# On vérifie que le prestataire puisse encore visiter des fiches.
							
							# On recharge les annonces visitées depuis la BDD puis on rajoute l'annonce en cours.
							$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
							
							$nb_annonces_visitees = $oPCS_personne->fx_recuperer_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
							
							$_SESSION['compte']['annonces_visitées'] = $nb_annonces_visitees[0]['ANNONCES_VISITEES'].$annonce_courante[0]['ID_ANNONCE']."/";
							
							# On met à jour la BDD.
							$oMSG->setData('ANNONCES_VISITEES', $_SESSION['compte']['annonces_visitées']);
							$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
							
							$oPCS_personne->fx_modifier_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG);
							
							# On met à jour la session avec un tableau de données et non pas une chaine.
							$_SESSION['compte']['annonces_visitées'] = explode('/', $_SESSION['compte']['annonces_visitées']);
							
							# On met à jour la session en décrémentant le nombre de fiches visitables.
							$_SESSION['pack']['NB_FICHES_VISITABLES']--;
							
							# On met à jour la BDD.
							$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
							$oMSG->setData('ID_PACK', $_SESSION['pack']['ID_PACK']);
							$oMSG->setData('DATE_ACHAT', $oCL_date->fx_ajouter_date($_SESSION['pack']['DATE_ACHAT'], true, false, 'fr', 'en'));
							$oMSG->setData('NB_FICHES_VISITABLES', $_SESSION['pack']['NB_FICHES_VISITABLES']);
							
							$oPCS_pack->fx_decrementer_NB_FICHES_VISITABLES_by_ID_PERSONNE($oMSG);

						}else{
						# Ne peut plus visiter d'annonce.
						$id_annonce_ok = 0;
						$_SESSION['annonce']['message_affiche'] = false;
						$_SESSION['annonce']['message'] = "<span class='alert'>Vous ne pouvez plus visiter d'annonce avec votre pack actuel. <a href='".$oCL_page->getPage('acheter_pack')."'>Achetez un pack.</a></span><br />";
						}
					}
				}# N'est pas prestataire. Pas d'action spécifique, les Organisateurs ne verront qu'une partie de l'annonce.
			
			}else{
				$id_annonce_ok = 0;
				$_SESSION['annonce']['message_affiche'] = false;
				$_SESSION['annonce']['message'] = "<span class='alert'>L'annonce que vous cherchez n'existe pas. (3)</span><br />";
			}
		}else{
			$id_annonce_ok = 0;
			$_SESSION['annonce']['message_affiche'] = false;
			$_SESSION['annonce']['message'] = "<span class='alert'>L'annonce que vous cherchez n'existe pas. (2)</span><br />";
		}
	}else{
		$id_annonce_ok = 0;
		$_SESSION['annonce']['message_affiche'] = false;
		$_SESSION['annonce']['message'] = "<span class='alert'>L'annonce que vous cherchez n'existe pas. (1)</span><br />";
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>SetEnv PHP_VER 5
SetEnv REGISTER_GLOBALS 0
SetEnv MAGIC_QUOTES 0



Options -Indexes



######### MAINTENANCE #########

ErrorDocument 403 /maintenance.php
allow from 79.80.96.47
deny from all

<Files maintenance.php>
allow from all
</Files>var map;
var panel;
var direction;

console.log("GMap.js chargé");
function initialiser_GMap(){
	var latLng = new google.maps.LatLng(50.6371834, 3.063017400000035); // Correspond au coordonnées de Lille
	var myOptions = {
		zoom      : 14, // Zoom par défaut
		center    : latLng, // Coordonnées de départ de la carte de type latLng 
		mapTypeId : google.maps.MapTypeId.ROADMAP, // Type de carte, différentes valeurs possible HYBRID, ROADMAP, SATELLITE, TERRAIN
		maxZoom   : 20,
		scrollwheel: false
	};
	
	map      = new google.maps.Map(document.getElementById('map'), myOptions);
	panel    = document.getElementById('panel');
	
	direction = new google.maps.DirectionsRenderer({
		map: map,
		panel: panel
	});
}

function calculate(){
    origin      = document.getElementById('origin').value; // Le point départ
    destination = document.getElementById('destination').value; // Le point d'arrivé
	console.log(origin);
    if(origin && destination){
        var request = {
            origin      : origin,
            destination : destination,
            travelMode  : google.maps.DirectionsTravelMode.DRIVING // Type de transport
        }
        var directionsService = new google.maps.DirectionsService(); // Service de calcul d'itinéraire
        directionsService.route(request, function(response, status){ // Envoie de la requête pour calculer le parcours
            if(status == google.maps.DirectionsStatus.OK){
                direction.setDirections(response); // Trace l'itinéraire sur la carte et les différentes étapes du parcours
            }
        });
    }
}

function verifier_donnees(){
	if(document.getElementById('origin').value == ", 0 "){
		document.getElementById('map').style.display = "none";
		document.getElementById('map_infos').style.display = "none";
		document.getElementById('map_erreur').innerHTML = "<center><span class='orange'>Vous devez renseigner votre adresse dans votre profil pour bénéficier de cette fonctionnalité.<br /></span>"+
		"<a href='http://liveanim.com/modifier_fiche_perso.php#form_fiche_membre_adresse'>Modifier mon addresse.</a></center><br /><br />";
	}
}var div_affiche = 0;
var plus= "images/Plus.png";
var moins= "images/Moins.png";

function initialiser_liste_annonce(div){
	document.getElementById(div).style.display = 'none';
}

function fx_affiche(div, img){
	div = document.getElementById(div);
	img = document.getElementById(img);
	if(div_affiche == 0){
		div_affiche = 1;
		div.style.display = 'inline';
		img.src = moins;
	}else{
		div_affiche = 0;
		div.style.display = 'none';
		img.src = plus;
	}

}var activer_pack_maintenant;

function maj_formulaire_paiement(div, id_personne, id_pack){
	var custom = document.getElementById(div);

	if(document.getElementById('activer_pack_maintenant').checked == true){
		activer_pack_maintenant = 1;
	}else{
		activer_pack_maintenant = 0;
	}
	
	custom.value = "id_personne="+id_personne+"&id_pack="+id_pack+"&duree=1&activer_pack_maintenant="+activer_pack_maintenant;

}function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '' : '<br />';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function fx_previsualiser(id_texte, id_zone_affichage){
	document.getElementById(id_zone_affichage).innerHTML = nl2br(document.getElementById(id_texte).value, false);
}var div_affiche = 0;
var plus= "images/Plus.png";
var moins= "images/Moins.png";

function initialiser_contrat(div){
	document.getElementById(div).style.display = 'none';
}

function fx_affiche(div, img){
	div = document.getElementById(div);
	img = document.getElementById(img);
	if(div_affiche == 0){
		div_affiche = 1;
		div.style.display = 'inline';
		img.src = moins;
	}else{
		div_affiche = 0;
		div.style.display = 'none';
		img.src = plus;
	}

}* { 
	margin:0;
	padding:0;
}
html, body { 
	height:100%;
}
body { 
	background:#000000;
	background-image:url(../images/background.png);
	background-repeat:no-repeat;	
	font-family:Arial, Helvetica, sans-serif;
	font-size:100%; 
	line-height:1.125em;
	color:#161616;
}

img {
	border:0; 
	vertical-align:top; 
	text-align:left;
}
object { 
	vertical-align:top; 
	outline:none;
}
ul, ol { 
	list-style:none;
}

.fleft { 
	float:left;
}
.fright { 
	float:right;
}
.clear { 
	clear:both;
}

.col-1, .col-2, .col-3 { 
	float:left;
}

.alignright { 
	text-align:right;
}
.aligncenter { 
	text-align:center;
}

.wrapper { 
	width:100%;
	overflow:hidden;
}
.container { 
	width:100%;
}


/*==== GLOBAL =====*/
#main {
	width:940px; 
	margin:0 auto;
	font-size:.8125em;
}

#header {
	height:353px;
	background:url(../images/main-tail.png) left top repeat-x #fff;
	padding:0 0 0 20px;
}
#content {
	background:#fff;
}
	#content .aside {
		float:left;
		width:333px;
	}
	#content .mainContent {
		float:left;
		width:607px;
		background:#e7e7e7;
	}
#footer {
	padding:32px 0 99px 0;
}


/*----- forms parameters -----*/

input, select, textarea { 
	font-family:Arial, Helvetica, sans-serif; font-size:1em;
	vertical-align:middle;
	font-weight:normal;
}


/*----- other -----*/
.img-indent { 
	margin:0 20px 0 0; 
	float:left;
}
.img-box { 
	width:100%; 
	overflow:hidden; 
	padding-bottom:20px;
}
	.img-box img { 
		float:left; 
		margin:0 20px 0 0;
	}

.extra-wrap { 
	overflow:hidden;
}


p {
	margin-bottom:18px;
}
.p1 { 
	margin-bottom:9px;
}
.p2 { 
	margin-bottom:18px;
}
.p3 { 
	margin-bottom:27px;
}

/*----- txt, links, lines, titles -----*/
a {
	color:#ff005a; 
	outline:none;
}
a:hover{
	text-decoration:none;
}

h1 {
	
}
h2 {
	font-size:34px;
	text-transform:uppercase;
	line-height:1.2em;
	letter-spacing:-1px;
	border-bottom:2px solid #1e1e1e;
	margin:0 -20px 20px -20px;
	padding:0 20px 10px 20px;
}
h3 {
	font-size:34px;
	text-transform:uppercase;
	line-height:1.2em;
	margin-bottom:20px;
	letter-spacing:-1px;
}
h4 {
	font-size:19px;
	line-height:1.2em;
	text-transform:uppercase;
	font-weight:normal;
	margin-bottom:18px;
}
	h4 a {
		font-size:15px;
		font-weight:bold;
		text-decoration:none;
	}
	h4 a:hover {
		background:#ff005a;
		color:#fff;
	}
h5 {
	font-size:15px;
	color:#ff005a;
	text-transform:uppercase;
}
h6 {
	font-size:15px;
	text-transform:uppercase;
	margin-bottom:2px;
}
	h6 a {
		text-decoration:none;
	}
	h6 a:hover {
		background:#ff005a;
		color:#fff;
	}


.link1 { 
	font-weight:bold;
	font-size:14px;
	text-transform:uppercase;
}
.link2 {
	background:url(../images/arrow2.gif) no-repeat right 4px;
	padding-right:12px;
	color:#161616;
	text-transform:uppercase;
	text-decoration:none;
	font-size:18px;
}
.link2:hover {
	color:#ff005a;
}

.link3 {
	font-size:11px;
	text-decoration:none;
}
.link3:hover {
	text-decoration:underline;
}




/*===== header =====*/
#header .col-1 {
	width:297px;
	margin-right:16px;
	padding-top:19px;
}
	#header .slogan {
		padding:20px 0 18px 0;
	}
#header .col-2 {
	width:607px;
}
	#header .nav {
		height:70px;
		overflow:hidden;
	}
		#header .nav .alert{
			color: #ED830B;
			font-style: italic;
		}
		#header .nav li {
			float:left;
		}
			#header .nav li a {
				float:left;
				color:#161616;
				font-size:20px;
				text-transform:uppercase;
				font-weight:bold;
				text-decoration:none;
				line-height:70px;
				padding:0 9px 0 10px;
				background:url(../images/divider.gif) no-repeat left 24px;
			}
			#header .nav li a.first {
				background:none;
			}
			#header .nav li a:hover, #header .nav li a.current {
				color:#fff;
				background:#ff005a;
				margin-right:-2px;
				padding-right:11px;
				position:relative;
			}


/*
 * Required 
*/
#loopedSlider .container { width:607px; height:283px; overflow:hidden; position:relative; }
#loopedSlider .slides { position:absolute; top:0; left:0; }
#loopedSlider .slides div.slide { position:absolute; top:0; width:607px; display:none; }
#loopedSlider .slides strong { 
	display:block;
	position:absolute;
	z-index:10;
	left:272px;
	top:260px;
	width:209px;
	height:23px;
	background:#ff005a;
	color:#fff;
	text-transform:uppercase;
	text-indent:13px;
	line-height:23px;
}
/*
 * Optional
*/
#loopedSlider {width:607px; position:relative; clear:both;}
#loopedSlider ul.pagination { list-style:none; padding:0; margin:0; position:absolute; right:0; top:260px;}
#loopedSlider ul.pagination li  { float:left; font-size:15px; line-height:1.2em; font-weight:bold; padding-left:2px;}
#loopedSlider ul.pagination li a { padding:2px 8px 3px 7px; background:#ff005a; color:#fff; text-decoration:none; float:left;}
#loopedSlider ul.pagination li a:hover {background:#e7e7e7; color:#000;}
#loopedSlider ul.pagination li.active a { background:#e7e7e7; color:#000;}


/*===== content =====*/
#content .indent {
	padding:47px 26px 70px 20px;
}
	#content .aside .indent {
		padding:47px 26px 70px 20px;
	}
		#content .aside .section {
			padding-bottom:45px;
		}
	#content .mainContent .indent {
		padding:42px 20px 40px 20px;
	}
		#content .mainContent .section {
			padding-bottom:45px;
		}
	
	
	.adv-menu {
		width:240px;
	}
		.adv-menu li {
			display:inline;
		}
			.adv-menu li a {
				display:block;
				font-size:17px;
				color:#fff;
				font-weight:bold;
				padding:7px 0 8px 15px;
				margin-bottom:1px;
				background:#000;
				text-decoration:none;
				position:relative;
			}
			.adv-menu li a:hover {
				background:#ff005a;
			}
	
	
	
	.members-list {
		width:100%;
		overflow:hidden;
		padding-bottom:12px;
	}
		.members-list li {
			float:left;
			width:87px;
			padding-right:13px;
			padding:0 13px 18px 0;
		}
		.members-list li.alt {
			padding-right:0;
		}
			.members-list li img {
				display:block;
				margin-bottom:4px;
			}
	
	.list1 li {
		font-size:16px;
		background:url(../images/arrow1.gif) no-repeat left top;
		padding:0 0 7px 28px;
	}
	
	.topics {
		position:relative;
	}
		.topics li {
			float:left;
			margin:0 0 19px 0;
			padding:0 0 19px 0;
			width:301px;
			border-bottom:1px solid #bfbfbf;
			position:relative;
		}
		.topics li.alt {
			width:264px;
		}
			.topics li img {
				float:left;
				margin-right:12px;
			}
			.topics li h5 {
				padding-top:22px;
			}
			.topics li p {
				text-transform:uppercase;
				margin-bottom:0;
				min-height:34px;
				height:auto !important;
				height:34px;
			}
			.topics li span {
				font-size:11px;
				color:#bfbfbf;
			}
	

#newsSlider { position:relative;}
#newsSlider .container { width:567px; height:491px; overflow:hidden; position:relative; }
#newsSlider .slides { position:absolute; top:0; left:0; }
#newsSlider .slides div.slide { position:absolute; top:0; width:567px; display:none; }
#newsSlider .pagination { display:none;}
#newsSlider .previous {
	position:absolute;
	right:31px;
	top:-68px;
	background:url(../images/prev.gif) no-repeat left top;
	width:30px;
	height:27px;
	display:block;
}
#newsSlider .next {
	position:absolute;
	right:0;
	top:-68px;
	background:url(../images/next.gif) no-repeat left top;
	width:30px;
	height:27px;
	display:block;
}


.news-list li {
	width:100%;
	overflow:hidden;
	padding-bottom:28px;
}
.news-list li.last {
	padding-bottom:0;
}
	.news-list li img {
		float:left;
		margin-right:20px;
	}
	.news-list li span {
		background:url(../images/divider1.gif) no-repeat left 3px;
		padding:0 0 0 11px;
		color:#bfbfbf;
		font-size:11px;
		margin-left:11px;
	}


/*===== footer =====*/
#footer, #footer a {
	color:#fff;
	font-size:12px;
}
	#footer .fleft {
		padding:12px 0 0 0;
	}
	#footer dl {
		float:right;
		background:#000;
		padding:10px 10px 10px 18px;
	}
		#footer dl dt {
			float:left;
			font-size:14px;
			line-height:1.2em;
			color:#fff;
			text-transform:uppercase;
			font-weight:bold;
			padding:3px 9px 0 0;
		}
		#footer dl dd {
			float:left;
			padding-left:4px;
		}


/*----- forms -----*/

/*==========================================*/
.adv-zzz {
				display:block;
				font-size:15px;
				color:#fff;
			
				padding:7px 0 8px 15px;
				margin-bottom:1px;
				background:url(../images/Cadre-connexion.png) no-repeat;
				width:100%;
				height:152px;
				text-decoration:none;
				position:relative;
				
}

.adv-zzz .fright{
	padding-right: 30px;
}



/* ------------------------------------------------------------ Mon CSS --------------------------------------------------------------------------- */

/* ----------------------------------- CSS spécifique aux pages -------------------------------------- */

.afficher_titre{
    text-decoration: none;
	font-weight: bold;
    color: #2799C2;
}

.alert{
	color: red;
	font-weight: bold;
}

.valide{
	color: #2799C2;
	font-weight: bold;
}

.orange{
	color: #ED830B;
	font-weight: bold;
}

.noir{
	color: #000000;
}

.noir_fond{
	color: #000000;
	font-weight: bold;
	background-color: #ff005a;
}

.gauche{
	color: #2799C2;
	font-weight: bold;
	text-decoration: underline;
	text-align: left;
}

.droite{
	color: #2799C2;
	font-weight: bold;
	text-decoration: underline;
	text-align: right;
}

.textarea50{
	width: 50%;
}
.textarea60{
	width: 60%;
}
.textarea70{
	width: 70%;
}
.textarea80{
	width: 80%;
}
.textarea90{
	width: 90%;
}
.textarea100{
	width: 100%;
}


.my_input12{
	width: 12%;
}
.my_input25{
	width: 25%;
}

.petit{
	text-decoration: none;
	font-size: 10px;
}

.legend_basique{
	font-weight:bold;
	text-decoration: underline;
	text-align: center;
	color:  #ff005a;
}

.formulaire{
	color: #ff005a;
	font-weight:bold;
	text-decoration: none;
	padding-left: 2%;
}

.formulaire_inscription{
	background-image: url(../images/fond_inscription_milieu.png);
  	background-repeat: repeat;
  	background-position: center;
	width:101.34%;
	height:1000px
}

.repeat_x{
	background-repeat: repeat-x;
}

#pack{
	padding-left:2%;
	padding-right:2%;
	text-align:justify;
}

	#pack .cool{
		padding-left:0%;
		padding-right:0%;
		color: #ff005a;
		font-weight:none;	
	}
	
	#pack .bad{
	padding-left:0%;
	padding-right:0%;
	color: #ED830B;
	font-weight:none;	
	}

	#pack li{
		border-left:2%;
		list-style-image: url("../images/liste1.png");
	}
	
.gras{
	font-weight:bold;
}

.souligne{
	text-decoration:underline;
}

.selectionne{
	background-color: #9FF7B3;
}

.gris{
	color: #ABC8E2;
}

.rose{
	color: #ff005a;
}

.padding_LR{
	padding-left:2%;
	padding-right:2%;
}

.justify{
	text-align:justify;
}
<?php
class MPG_contrat_personne{

	private $ID_CONTRAT;
	private $ID_PERSONNE;

	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_CONTRAT = "";
	$this->ID_PERSONNE = "";

	}
	
	public function INSERT($oMSG){
		$this->ID_CONTRAT = $oMSG->getData('ID_CONTRAT');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
	
		$this->sql = "INSERT INTO contrat_personne (ID_CONTRAT, ID_PERSONNE) VALUES (:ID_CONTRAT, :ID_PERSONNE);";
		
		$params = array(  
					':ID_CONTRAT' =>$this->ID_CONTRAT,		
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}<?php
class MPG_departement{

	private $ID_DEPARTEMENT;
	private $ID_REGION;
	private $NOM;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_DEPARTEMENT = "";
	$this->ID_REGION = "";
	$this->NOM = "";

	}
	
	
	public function SELECT_all($oMSG){
	
		$this->sql = "SELECT ID_DEPARTEMENT, ID_REGION, NOM FROM departement ORDER BY ID_DEPARTEMENT;";
		$params = array(    
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
}<?php
class MPG_famille_types{

	private $ID_FAMILLE_TYPES;
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_FAMILLE_TYPES = "";
	}
	
}<?php
class MPG_message{

	private $ID_MESSAGE;
	private $TITRE;
	private $CONTENU;
	private $DATE_ENVOI;
	private $EXPEDITEUR;
	private $DESTINATAIRE;
	private $TYPE_MESSAGE;
	private $VISIBLE;
	
	public function __construct(){
		$this->ID_MESSAGE="";
		$this->TITRE="";
		$this->CONTENU="";
		$this->DATE_ENVOI="";
		$this->EXPEDITEUR="";
		$this->DESTINATAIRE="";
		$this->TYPE_MESSAGE="";
		$this->VISIBLE="";
	
	}
	
	// ----------------------------------------------------- INSERT ---------------------------------------------------------
	
	public function INSERT($oMSG){
		$this->TITRE = $oMSG->getData('TITRE');
		$this->CONTENU = $oMSG->getData('CONTENU');
		$this->DATE_ENVOI = $oMSG->getData('DATE_ENVOI');
		$this->EXPEDITEUR = $oMSG->getData('EXPEDITEUR');
		$this->DESTINATAIRE = $oMSG->getData('DESTINATAIRE');
		$this->TYPE_MESSAGE = $oMSG->getData('TYPE_MESSAGE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
	
		$this->sql = "INSERT INTO message (TITRE, CONTENU, DATE_ENVOI, EXPEDITEUR, DESTINATAIRE, TYPE_MESSAGE, VISIBLE) ".
		"VALUES (:TITRE, :CONTENU, :DATE_ENVOI, :EXPEDITEUR, :DESTINATAIRE, :TYPE_MESSAGE, :VISIBLE);";
		
		$params = array(
					':TITRE'=>$this->TITRE,
					':CONTENU'=>$this->CONTENU,
					':DATE_ENVOI'=>$this->DATE_ENVOI,
					':EXPEDITEUR'=>$this->EXPEDITEUR,
					':DESTINATAIRE'=>$this->DESTINATAIRE,
					':TYPE_MESSAGE'=>$this->TYPE_MESSAGE,
					':VISIBLE'=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
}<?php
class MPG_message_personne{

	private $ID_MESSAGE;
	private $ID_PERSONNE;
	private $STATUT_MESSAGE;
	private $DATE_LECTURE;
	private $DATE_REPONSE;

	
	public function __construct(){
		$this->ID_MESSAGE="";
		$this->ID_PERSONNE="";
		$this->STATUT_MESSAGE="";
		$this->DATE_LECTURE="";
		$this->DATE_REPONSE="";	
	}
	
	// ----------------------------------------------------- INSERT -----------------------------------------------
	
	public function INSERT($oMSG){
		$this->ID_MESSAGE = $oMSG->getData('ID_MESSAGE');
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		
		$this->sql = "INSERT INTO message_personne (ID_MESSAGE, ID_PERSONNE, STATUT_MESSAGE) ".
		"VALUES (:ID_MESSAGE, :ID_PERSONNE, :STATUT_MESSAGE);";
		
		$params = array(
					':ID_MESSAGE'=>$this->ID_MESSAGE,
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ----------------------------------------------------- UPDATE -----------------------------------------------
	
	public function UPDATE_message_lu_by_ID_MESSAGE($oMSG){
		$this->ID_MESSAGE = $oMSG->getData('ID_MESSAGE');
		$this->DATE_LECTURE = $oMSG->getData('DATE_LECTURE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		
		$this->sql = "UPDATE message_personne SET DATE_LECTURE=:DATE_LECTURE, STATUT_MESSAGE=:STATUT_MESSAGE WHERE ID_MESSAGE=:ID_MESSAGE;";
		
		$params = array(
					':ID_MESSAGE'=>$this->ID_MESSAGE,
					':DATE_LECTURE'=>$this->DATE_LECTURE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
}<?php
class MPG_pack{

	private $ID_PACK;
	private $NOM;
	private $DESCRIPTION;
	private $TYPE_PACK;
	private $PRIX_BASE;
	private $DUREE;
	private $SOUMIS_REDUCTIONS_PARRAINAGE;
	private $GAIN_PARRAINAGE_MAX;
	private $REDUCTION;
	private $VISIBLE;
	private $CV_VISIBILITE;
	private $CV_ACCESSIBLE;
	private $NB_FICHES_VISITABLES;
	private $CV_VIDEO_ACCESSIBLE;
	private $ALERTE_NON_DISPONIBILITE;
	private $NB_DEPARTEMENTS_ALERTE;
	private $PARRAINAGE_ACTIVE;
	private $PREVISUALISATION_FICHES;
	private $CONTRATS_PDF;
	private $SUIVI;
	private $PUBS;

	private $sql;

	public function __construct() {
		$this->sql = "";
		$this->ID_PACK = "";
		$this->NOM = "";
		$this->DESCRIPTION = "";
		$this->TYPE_PACK = "";
		$this->PRIX_BASE = "";
		$this->DUREE = "";
		$this->SOUMIS_REDUCTIONS_PARRAINAGE = "";
		$this->GAIN_PARRAINAGE_MAX = "";
		$this->REDUCTION = "";
		$this->VISIBLE = "";
		$this->CV_VISIBILITE = "";
		$this->CV_ACCESSIBLE = "";
		$this->NB_FICHES_VISITABLES = "";
		$this->CV_VIDEO_ACCESSIBLE = "";
		$this->ALERTE_NON_DISPONIBILITE = "";
		$this->NB_DEPARTEMENTS_ALERTE = "";
		$this->PARRAINAGE_ACTIVE = "";
		$this->PREVISUALISATION_FICHES = "";
		$this->CONTRATS_PDF = "";
		$this->SUIVI = "";
		$this->PUBS = "";
	}
	
	public function SELECT_COUNT_all_packs($oMSG){

		$this->sql = "SELECT COUNT(ID_PACK) AS nb_packs from pack;";
		
		$params = array(
		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_PACK_by_VISIBLE($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		
		$this->sql = "SELECT COUNT(ID_PACK) AS nb_packs from pack WHERE VISIBLE=:VISIBLE;";
		
		$params = array(
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_ID_PACK($oMSG){		
		$this->ID_PACK = $oMSG->getData("ID_PACK");
		
		$this->sql = "SELECT * from pack WHERE ID_PACK=:ID_PACK;";
		
		$params = array(
				":ID_PACK"=>$this->ID_PACK,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_minimum($oMSG){		
		$this->sql = "SELECT ID_PACK, NOM, TYPE_PACK, PRIX_BASE, DUREE, VISIBLE from pack ORDER BY PRIX_BASE DESC;";
		
		$params = array(
		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_VISIBLE($oMSG){	
		$this->VISIBLE = $oMSG->getData("VISIBLE");
	
		$this->sql = "SELECT * from pack WHERE VISIBLE=:VISIBLE ORDER BY PRIX_BASE DESC;";
		
		$params = array(
				":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_ALL_by_TYPE_PACK_et_LIMIT($oMSG){		
		$this->TYPE_PACK = $oMSG->getData("TYPE_PACK");
		$limit = $oMSG->getData("limit");
		
		$this->sql = "SELECT * from pack WHERE TYPE_PACK=:TYPE_PACK $limit;";
		
		$params = array(
				":TYPE_PACK"=>$this->TYPE_PACK,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// --------------------------------------------------- INSERT ---------------------------------------------------
	
	public function INSERT($oMSG){
		$this->NOM = $oMSG->getData("NOM");
		$this->DESCRIPTION = $oMSG->getData("DESCRIPTION");
		$this->TYPE_PACK = $oMSG->getData("TYPE_PACK");
		$this->PRIX_BASE = $oMSG->getData("PRIX_BASE");
		$this->DUREE = $oMSG->getData("DUREE");
		$this->SOUMIS_REDUCTIONS_PARRAINAGE = $oMSG->getData("SOUMIS_REDUCTIONS_PARRAINAGE");
		$this->GAIN_PARRAINAGE_MAX = $oMSG->getData("GAIN_PARRAINAGE_MAX");
		$this->REDUCTION = $oMSG->getData("REDUCTION");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->CV_VISIBILITE = $oMSG->getData("CV_VISIBILITE");
		$this->CV_ACCESSIBLE = $oMSG->getData("CV_ACCESSIBLE");
		$this->NB_FICHES_VISITABLES = $oMSG->getData("NB_FICHES_VISITABLES");
		$this->CV_VIDEO_ACCESSIBLE = $oMSG->getData("CV_VIDEO_ACCESSIBLE");
		$this->ALERTE_NON_DISPONIBILITE = $oMSG->getData("ALERTE_NON_DISPONIBILITE");
		$this->NB_DEPARTEMENTS_ALERTE = $oMSG->getData("NB_DEPARTEMENTS_ALERTE");
		$this->PARRAINAGE_ACTIVE = $oMSG->getData("PARRAINAGE_ACTIVE");
		$this->PREVISUALISATION_FICHES = $oMSG->getData("PREVISUALISATION_FICHES");
		$this->CONTRATS_PDF = $oMSG->getData("CONTRATS_PDF");
		$this->SUIVI = $oMSG->getData("SUIVI");
		$this->PUBS = $oMSG->getData("PUBS");
	
		$this->sql = "INSERT INTO pack (NOM, DESCRIPTION, TYPE_PACK, PRIX_BASE, DUREE, SOUMIS_REDUCTIONS_PARRAINAGE, GAIN_PARRAINAGE_MAX, REDUCTION, ".
		"VISIBLE, CV_VISIBILITE, CV_ACCESSIBLE, NB_FICHES_VISITABLES, CV_VIDEO_ACCESSIBLE, ALERTE_NON_DISPONIBILITE, NB_DEPARTEMENTS_ALERTE, ".
		"PARRAINAGE_ACTIVE, PREVISUALISATION_FICHES, CONTRATS_PDF, SUIVI, PUBS) ".
		"VALUES (:NOM, :DESCRIPTION, :TYPE_PACK, :PRIX_BASE, :DUREE, :SOUMIS_REDUCTIONS_PARRAINAGE, :GAIN_PARRAINAGE_MAX, :REDUCTION, :VISIBLE, :CV_VISIBILITE, ".
		":CV_ACCESSIBLE, :NB_FICHES_VISITABLES, :CV_VIDEO_ACCESSIBLE, :ALERTE_NON_DISPONIBILITE, :NB_DEPARTEMENTS_ALERTE, :PARRAINAGE_ACTIVE, ".
		":PREVISUALISATION_FICHES, :CONTRATS_PDF, :SUIVI, :PUBS);";
		
		$params = array(    
					":NOM"=>$this->NOM,
					":DESCRIPTION"=>$this->DESCRIPTION,
					":TYPE_PACK"=>$this->TYPE_PACK,
					":PRIX_BASE"=>$this->PRIX_BASE,
					":DUREE"=>$this->DUREE,
					":SOUMIS_REDUCTIONS_PARRAINAGE"=>$this->SOUMIS_REDUCTIONS_PARRAINAGE,
					":GAIN_PARRAINAGE_MAX"=>$this->GAIN_PARRAINAGE_MAX,
					":REDUCTION"=>$this->REDUCTION,
					":VISIBLE"=>$this->VISIBLE,
					":CV_VISIBILITE"=>$this->CV_VISIBILITE,
					":CV_ACCESSIBLE"=>$this->CV_ACCESSIBLE,
					":NB_FICHES_VISITABLES"=>$this->NB_FICHES_VISITABLES,
					":CV_VIDEO_ACCESSIBLE"=>$this->CV_VIDEO_ACCESSIBLE,
					":ALERTE_NON_DISPONIBILITE"=>$this->ALERTE_NON_DISPONIBILITE,
					":NB_DEPARTEMENTS_ALERTE"=>$this->NB_DEPARTEMENTS_ALERTE,
					":PARRAINAGE_ACTIVE"=>$this->PARRAINAGE_ACTIVE,
					":PREVISUALISATION_FICHES"=>$this->PREVISUALISATION_FICHES,
					":CONTRATS_PDF"=>$this->CONTRATS_PDF,
					":SUIVI"=>$this->SUIVI,
					":PUBS"=>$this->PUBS,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// --------------------------------------------------- UPDATE  -----------------------------------------------------
	
	public function UPDATE($oMSG){
		$this->ID_PACK = $oMSG->getData("ID_PACK");
		$this->NOM = $oMSG->getData("NOM");
		$this->DESCRIPTION = $oMSG->getData("DESCRIPTION");
		$this->TYPE_PACK = $oMSG->getData("TYPE_PACK");
		$this->PRIX_BASE = $oMSG->getData("PRIX_BASE");
		$this->DUREE = $oMSG->getData("DUREE");
		$this->SOUMIS_REDUCTIONS_PARRAINAGE = $oMSG->getData("SOUMIS_REDUCTIONS_PARRAINAGE");
		$this->GAIN_PARRAINAGE_MAX = $oMSG->getData("GAIN_PARRAINAGE_MAX");
		$this->REDUCTION = $oMSG->getData("REDUCTION");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->CV_VISIBILITE = $oMSG->getData("CV_VISIBILITE");
		$this->CV_ACCESSIBLE = $oMSG->getData("CV_ACCESSIBLE");
		$this->NB_FICHES_VISITABLES = $oMSG->getData("NB_FICHES_VISITABLES");
		$this->CV_VIDEO_ACCESSIBLE = $oMSG->getData("CV_VIDEO_ACCESSIBLE");
		$this->ALERTE_NON_DISPONIBILITE = $oMSG->getData("ALERTE_NON_DISPONIBILITE");
		$this->NB_DEPARTEMENTS_ALERTE = $oMSG->getData("NB_DEPARTEMENTS_ALERTE");
		$this->PARRAINAGE_ACTIVE = $oMSG->getData("PARRAINAGE_ACTIVE");
		$this->PREVISUALISATION_FICHES = $oMSG->getData("PREVISUALISATION_FICHES");
		$this->CONTRATS_PDF = $oMSG->getData("CONTRATS_PDF");
		$this->SUIVI = $oMSG->getData("SUIVI");
		$this->PUBS = $oMSG->getData("PUBS");
	
		$this->sql = "UPDATE pack SET NOM=:NOM, DESCRIPTION=:DESCRIPTION, TYPE_PACK=:TYPE_PACK, PRIX_BASE=:PRIX_BASE, DUREE=:DUREE, ".
		"SOUMIS_REDUCTIONS_PARRAINAGE=:SOUMIS_REDUCTIONS_PARRAINAGE, GAIN_PARRAINAGE_MAX=:GAIN_PARRAINAGE_MAX, REDUCTION=:REDUCTION, ".
		"VISIBLE=:VISIBLE, CV_VISIBILITE=:CV_VISIBILITE, CV_ACCESSIBLE=:CV_ACCESSIBLE, NB_FICHES_VISITABLES=:NB_FICHES_VISITABLES, ".
		"CV_VIDEO_ACCESSIBLE=:CV_VIDEO_ACCESSIBLE, ALERTE_NON_DISPONIBILITE=:ALERTE_NON_DISPONIBILITE, NB_DEPARTEMENTS_ALERTE=:NB_DEPARTEMENTS_ALERTE, ".
		"PARRAINAGE_ACTIVE=:PARRAINAGE_ACTIVE, PREVISUALISATION_FICHES=:PREVISUALISATION_FICHES, CONTRATS_PDF=:CONTRATS_PDF, SUIVI=:SUIVI, PUBS=:PUBS ".
		"WHERE ID_PACK=:ID_PACK;";
		
		$params = array(    
					":ID_PACK"=>$this->ID_PACK,
					":NOM"=>$this->NOM,
					":DESCRIPTION"=>$this->DESCRIPTION,
					":TYPE_PACK"=>$this->TYPE_PACK,
					":PRIX_BASE"=>$this->PRIX_BASE,
					":DUREE"=>$this->DUREE,
					":SOUMIS_REDUCTIONS_PARRAINAGE"=>$this->SOUMIS_REDUCTIONS_PARRAINAGE,
					":GAIN_PARRAINAGE_MAX"=>$this->GAIN_PARRAINAGE_MAX,
					":REDUCTION"=>$this->REDUCTION,
					":VISIBLE"=>$this->VISIBLE,
					":CV_VISIBILITE"=>$this->CV_VISIBILITE,
					":CV_ACCESSIBLE"=>$this->CV_ACCESSIBLE,
					":NB_FICHES_VISITABLES"=>$this->NB_FICHES_VISITABLES,
					":CV_VIDEO_ACCESSIBLE"=>$this->CV_VIDEO_ACCESSIBLE,
					":ALERTE_NON_DISPONIBILITE"=>$this->ALERTE_NON_DISPONIBILITE,
					":NB_DEPARTEMENTS_ALERTE"=>$this->NB_DEPARTEMENTS_ALERTE,
					":PARRAINAGE_ACTIVE"=>$this->PARRAINAGE_ACTIVE,
					":PREVISUALISATION_FICHES"=>$this->PREVISUALISATION_FICHES,
					":CONTRATS_PDF"=>$this->CONTRATS_PDF,
					":SUIVI"=>$this->SUIVI,
					":PUBS"=>$this->PUBS,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}<?php
class MPG_pack_personne{

	private $ID_PERSONNE;
	private $ID_PACK;
	private $DATE_ACHAT;
	private $DATE_DEBUT;
	private $DATE_FIN;
	private $REDUCTION;
	private $NB_FICHES_VISITABLES;
	private	$DATAS_PAYPAL;

	private $sql;

	public function __construct() {
		$this->sql = "";
		$this->ID_PERSONNE = "";
		$this->ID_PACK = "";
		$this->DATE_ACHAT = "";
		$this->DATE_DEBUT = "";
		$this->DATE_FIN = "";
		$this->REDUCTION = "";
		$this->NB_FICHES_VISITABLES = "";		
		$this->DATAS_PAYPAL = "";			
	}
	
	// -------------------------------------------------- SELECT -----------------------------------------------------------
	
	
	// -------------------------------------------------- INSERT -----------------------------------------------------------
	
	public function INSERT($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->ID_PACK = $oMSG->getData("ID_PACK");
		$this->DATE_ACHAT = $oMSG->getData("DATE_ACHAT");
		$this->DATE_DEBUT = $oMSG->getData("DATE_DEBUT");
		$this->DATE_FIN = $oMSG->getData("DATE_FIN");
		$this->REDUCTION = $oMSG->getData("REDUCTION");
		$this->NB_FICHES_VISITABLES = $oMSG->getData("NB_FICHES_VISITABLES");
		$this->DATAS_PAYPAL = $oMSG->getData("DATAS_PAYPAL");
		
		$this->sql = "INSERT INTO pack_personne (ID_PERSONNE, ID_PACK, DATE_ACHAT, DATE_DEBUT, DATE_FIN, REDUCTION, NB_FICHES_VISITABLES, DATAS_PAYPAL) ".
		"VALUES (:ID_PERSONNE, :ID_PACK, :DATE_ACHAT, :DATE_DEBUT, :DATE_FIN, :REDUCTION, :NB_FICHES_VISITABLES, :DATAS_PAYPAL);";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
				":ID_PACK"=>$this->ID_PACK,
				":DATE_ACHAT"=>$this->DATE_ACHAT,
				":DATE_DEBUT"=>$this->DATE_DEBUT,
				":DATE_FIN"=>$this->DATE_FIN,
				":REDUCTION"=>$this->REDUCTION,
				":NB_FICHES_VISITABLES"=>$this->NB_FICHES_VISITABLES,
				":DATAS_PAYPAL"=>$this->DATAS_PAYPAL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ------------------------------------------------- UPDATE ------------------------------------------------------
	
	public function UPDATE_DATE_FIN_by_IDs($oMSG){		
	$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	$this->ID_PACK = $oMSG->getData("ID_PACK");
	$this->DATE_ACHAT = $oMSG->getData("DATE_ACHAT");
	$this->DATE_FIN = $oMSG->getData("DATE_FIN");
	
	$this->sql = "UPDATE pack_personne SET DATE_FIN=:DATE_FIN WHERE ID_PACK=:ID_PACK AND ID_PERSONNE=:ID_PERSONNE AND DATE_ACHAT=:DATE_ACHAT;";
	
	$params = array(
			":ID_PERSONNE"=>$this->ID_PERSONNE,
			":ID_PACK"=>$this->ID_PACK,
			":DATE_ACHAT"=>$this->DATE_ACHAT,
			":DATE_FIN"=>$this->DATE_FIN,
				);
	
	$oMSG->setData(0, $this->sql);
	$oMSG->setData(1, $params);
	
	return $oMSG;
	}
	
	public function UPDATE_DATE_DEBUT_et_DATE_FIN_by_IDs($oMSG){		
	$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	$this->ID_PACK = $oMSG->getData("ID_PACK");
	$this->DATE_ACHAT = $oMSG->getData("DATE_ACHAT");
	$this->DATE_DEBUT = $oMSG->getData("DATE_DEBUT");
	$this->DATE_FIN = $oMSG->getData("DATE_FIN");
	
	$this->sql = "UPDATE pack_personne SET DATE_DEBUT=:DATE_DEBUT, DATE_FIN=:DATE_FIN WHERE ID_PACK=:ID_PACK AND ID_PERSONNE=:ID_PERSONNE AND DATE_ACHAT=:DATE_ACHAT;";
	
	$params = array(
			":ID_PERSONNE"=>$this->ID_PERSONNE,
			":ID_PACK"=>$this->ID_PACK,
			":DATE_ACHAT"=>$this->DATE_ACHAT,
			":DATE_DEBUT"=>$this->DATE_DEBUT,
			":DATE_FIN"=>$this->DATE_FIN,
				);
	
	$oMSG->setData(0, $this->sql);
	$oMSG->setData(1, $params);
	
	return $oMSG;
	}
	
	public function UPDATE_decremente_NB_FICHES_VISITABLES_by_IDs($oMSG){		
	$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	$this->ID_PACK = $oMSG->getData("ID_PACK");
	$this->DATE_ACHAT = $oMSG->getData("DATE_ACHAT");
	$this->NB_FICHES_VISITABLES = $oMSG->getData("NB_FICHES_VISITABLES");
	
	$this->sql = "UPDATE pack_personne SET NB_FICHES_VISITABLES=:NB_FICHES_VISITABLES-1 WHERE ID_PACK=:ID_PACK AND ID_PERSONNE=:ID_PERSONNE AND DATE_ACHAT=:DATE_ACHAT;";
	
	$params = array(
			":ID_PERSONNE"=>$this->ID_PERSONNE,
			":ID_PACK"=>$this->ID_PACK,
			":DATE_ACHAT"=>$this->DATE_ACHAT,
			":NB_FICHES_VISITABLES"=>$this->NB_FICHES_VISITABLES,
				);
	
	$oMSG->setData(0, $this->sql);
	$oMSG->setData(1, $params);
	
	return $oMSG;
	}
	
}<?php
class MPG_personne{

	private $ID_PERSONNE;
	private $PSEUDO;
	private $NOM;
	private $PRENOM;
	private $DESCRIPTION;
	private $URL_PHOTO_PRINCIPALE;
	private $DATE_NAISSANCE;
	private $CIVILITE;
	private $EMAIL;
	private $MDP;
	private $TYPE_PERSONNE;
	private $STATUT_PERSONNE;
	private $CONNAISSANCE_SITE;
	private $NEWSLETTER;
	private $OFFRES_ANNONCEURS;
	private $DEPARTEMENTS;
	private $VILLE;
	private $ADRESSE;
	private $CP;
	private $TEL_FIXE;
	private $TEL_PORTABLE;
	private $REDUCTION;
	private $PARRAIN;
	private $SIRRET;
	private $TARIFS;
	private $DISTANCE_PRESTATION_MAX;
	private $CV_VIDEO;
	private $MATERIEL;
	private $VISIBLE;
	private $DATE_BANNISSEMENT;
	private $PERSONNE_SUPPRIMEE;
	private $DATE_SUPPRESSION_REELLE;
	private $RAISON_SUPPRESSION;	
	private $CLE_ACTIVATION;
	private $ANNONCES_VISITEES;
	
	# IP
	private $ID_IP;
	private $IP_COOKIE;
	private $COOKIE_DETRUIT;
	private $DATE_CONNEXION;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_PERSONNE = "";
	$this->PSEUDO = "";
	$this->NOM = "";
	$this->PRENOM = "";
	$this->DESCRIPTION = "";
	$this->URL_PHOTO_PRINCIPALE = "";
	$this->DATE_NAISSANCE = "";
	$this->CIVILITE = "";
	$this->EMAIL = "";
	$this->MDP = "";
	$this->TYPE_PERSONNE = "";
	$this->STATUT_PERSONNE = "";
	$this->CONNAISSANCE_SITE = "";
	$this->NEWSLETTER = "";
	$this->OFFRES_ANNONCEURS = "";
	$this->DEPARTEMENTS = "";
	$this->VILLE = "";
	$this->ADRESSE = "";
	$this->CP = "";
	$this->TEL_FIXE = "";
	$this->TEL_PORTABLE = "";
	$this->REDUCTION = "";
	$this->PARRAIN = "";
	$this->SIRRET = "";
	$this->TARIFS = "";
	$this->DISTANCE_PRESTATION_MAX = "";
	$this->CV_VIDEO = "";
	$this->MATERIEL = "";
	$this->VISIBLE = "";
	$this->DATE_BANNISSEMENT = "";
	$this->PERSONNE_SUPPRIMEE = "";
	$this->DATE_SUPPRESSION_REELLE = "";
	$this->RAISON_SUPPRESSION = "";	
	$this->CLE_ACTIVATION = "";	
	$this->ANNONCES_VISITEES = "";	
	
	# IP
	$this->ID_IP = "";
	$this->IP_COOKIE = "";
	$this->COOKIE_DETRUIT = "";	
	$this->DATE_CONNEXION = "";
	}
	
	public function SELECT_COUNT_PSEUDO($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
	
		$this->sql = "SELECT COUNT(PSEUDO) AS nb_pseudo FROM personne WHERE PSEUDO=:PSEUDO;";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_pseudo_by_PSEUDO_et_MDP($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
		$this->MDP = $oMSG->getData("MDP");
	
		$this->sql = "SELECT COUNT(PSEUDO) AS nb_pseudo FROM personne WHERE PSEUDO=:PSEUDO AND MDP=:MDP;";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					":MDP"=>$this->MDP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_personne_by_ID_PERSONNE_et_MDP($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->MDP = $oMSG->getData("MDP");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) AS nb_personne FROM personne WHERE ID_PERSONNE=:ID_PERSONNE AND MDP=:MDP;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":MDP"=>$this->MDP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_EMAIL($oMSG){
		$this->EMAIL = $oMSG->getData("EMAIL");
	
		$this->sql = "SELECT COUNT(EMAIL) AS nb_email FROM personne WHERE EMAIL=:EMAIL;";
		$params = array(    
					":EMAIL"=>$this->EMAIL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_compte_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT * FROM personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_PSEUDO($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
	
		$this->sql = "SELECT * FROM personne WHERE PSEUDO=:PSEUDO;";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_PSEUDO_et_EMAIL($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
		$this->EMAIL = $oMSG->getData("EMAIL");
	
		$this->sql = "SELECT * FROM personne WHERE PSEUDO=:PSEUDO AND EMAIL=:EMAIL;";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					":EMAIL"=>$this->EMAIL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_by_EMAIL_et_CLE_ACTIVATION($oMSG){
		$this->CLE_ACTIVATION = $oMSG->getData("CLE_ACTIVATION");
		$this->EMAIL = $oMSG->getData("EMAIL");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) AS nb_personne, CLE_ACTIVATION  FROM personne WHERE CLE_ACTIVATION=:CLE_ACTIVATION AND EMAIL=:EMAIL AND VISIBLE=false;";
		$params = array(    
					":CLE_ACTIVATION"=>$this->CLE_ACTIVATION,
					":EMAIL"=>$this->EMAIL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_IP_by_ID_IP($oMSG){
		$this->ID_IP = $oMSG->getData("ID_IP");
	
		$this->sql = "SELECT COUNT(ID_IP) AS nb_IP FROM ip WHERE ID_IP=:ID_IP;";
		$params = array(    
					":ID_IP"=>$this->ID_IP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_IP_by_IP_COOKIE($oMSG){
		$this->IP_COOKIE = $oMSG->getData("IP_COOKIE");
	
		$this->sql = "SELECT COUNT(ID_IP) AS nb_IP FROM ip WHERE ID_IP=:IP_COOKIE;";
		$params = array(    
					":IP_COOKIE"=>$this->IP_COOKIE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_by_CLE_ACTIVATION($oMSG){
		$this->CLE_ACTIVATION = $oMSG->getData("CLE_ACTIVATION");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, EMAIL, VISIBLE, CLE_ACTIVATION, ID_IP, DATE_CONNEXION, IP_COOKIE ".
		"FROM ip_personne NATURAL JOIN personne WHERE CLE_ACTIVATION<>:CLE_ACTIVATION GROUP BY ID_PERSONNE HAVING COUNT(ID_IP) >= 1 ORDER BY ID_IP, IP_COOKIE, DATE_CONNEXION, PSEUDO, EMAIL;";# ATTENTION: "!=" et non "="
		$params = array(    
					":CLE_ACTIVATION"=>$this->CLE_ACTIVATION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_by_ID_IP($oMSG){
		$this->ID_IP = $oMSG->getData("ID_IP");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, EMAIL, VISIBLE, CLE_ACTIVATION, ID_IP, DATE_CONNEXION, IP_COOKIE ".
		"FROM ip_personne NATURAL JOIN personne WHERE ID_IP=:ID_IP GROUP BY PSEUDO ORDER BY ID_IP, IP_COOKIE, DATE_CONNEXION, PSEUDO, EMAIL;";
		$params = array(    
					":ID_IP"=>$this->ID_IP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_by_IP_COOKIE($oMSG){
		$this->IP_COOKIE = $oMSG->getData("IP_COOKIE");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, EMAIL, VISIBLE, CLE_ACTIVATION, ID_IP, DATE_CONNEXION, IP_COOKIE ".
		"FROM ip_personne NATURAL JOIN personne WHERE IP_COOKIE=:IP_COOKIE GROUP BY PSEUDO ORDER BY ID_IP, IP_COOKIE, DATE_CONNEXION, PSEUDO, EMAIL;";
		$params = array(    
					":IP_COOKIE"=>$this->IP_COOKIE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_tous_membres($oMSG){
		$champs = $oMSG->getData("champs");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, EMAIL FROM personne ORDER BY PSEUDO;";
		$params = array(    
					
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_tous_membres($oMSG){
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) AS nb_personne FROM personne;";
		$params = array(    
		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_tous_membres_by_LIMIT($oMSG){
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, URL_PHOTO_PRINCIPALE, EMAIL, TYPE_PERSONNE, VISIBLE, DATE_BANNISSEMENT, PERSONNE_SUPPRIMEE, ".
		"DATE_SUPPRESSION_REELLE, RAISON_SUPPRESSION, CLE_ACTIVATION FROM personne ORDER BY PSEUDO, TYPE_PERSONNE, EMAIL LIMIT $debut_affichage, ".
		"$nb_result_affiches ;";
		$params = array(    
		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	public function SELECT_COUNT_by_CLE_ACTIVATION($oMSG){
		$this->CLE_ACTIVATION = $oMSG->getData("CLE_ACTIVATION");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) as nb_comptes ".
		"FROM ip_personne NATURAL JOIN personne WHERE CLE_ACTIVATION!=:CLE_ACTIVATION AND (SELECT COUNT(ID_IP) FROM ip_personne) > 1;";# ATTENTION: "!=" et non "="
		$params = array(    
					":CLE_ACTIVATION"=>$this->CLE_ACTIVATION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_comptes_by_PERSONNE_SUPPRIMEE_et_VISIBILITE($oMSG){
		$this->PERSONNE_SUPPRIMEE = $oMSG->getData("PERSONNE_SUPPRIMEE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) as nb_comptes_supprimes ".
		"FROM personne WHERE PERSONNE_SUPPRIMEE=:PERSONNE_SUPPRIMEE AND VISIBLE=:VISIBLE;";
		$params = array(    
					":PERSONNE_SUPPRIMEE"=>$this->PERSONNE_SUPPRIMEE,
					":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_comptes_supprimes_par_utilisateur($oMSG){
		$this->PERSONNE_SUPPRIMEE = $oMSG->getData("PERSONNE_SUPPRIMEE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, NOM, PRENOM, CIVILITE, EMAIL, TEL_FIXE, TEL_PORTABLE, RAISON_SUPPRESSION ".
		"FROM personne WHERE PERSONNE_SUPPRIMEE=:PERSONNE_SUPPRIMEE AND VISIBLE=:VISIBLE ORDER BY DATE_SUPPRESSION_REELLE DESC;";
		$params = array(    
					":PERSONNE_SUPPRIMEE"=>$this->PERSONNE_SUPPRIMEE,
					":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_REDUCTION_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT REDUCTION FROM personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_PERSONNE_by_ID_PERSONNE_et_EMAIL_et_MDP($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->EMAIL = $oMSG->getData("EMAIL");
		$this->MDP = $oMSG->getData("MDP");
	
		$this->sql = "SELECT COUNT(ID_PERSONNE) as nb_personne FROM personne WHERE ID_PERSONNE=:ID_PERSONNE AND EMAIL=:EMAIL AND MDP=:MDP;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":EMAIL"=>$this->EMAIL,
					":MDP"=>$this->MDP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_filleuls_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, NOM, PRENOM, CIVILITE, TYPE_PERSONNE FROM personne WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY PSEUDO;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_adresse_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT ID_PERSONNE, PSEUDO, NOM, PRENOM, CIVILITE, TYPE_PERSONNE, ADRESSE, CP, VILLE FROM personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT ANNONCES_VISITEES FROM personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_PSEUDO_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT PSEUDO FROM personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ------------------------------------------------------------- INSERT
	
	public function INSERT_all($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
		$this->NOM = $oMSG->getData("NOM");
		$this->PRENOM = $oMSG->getData("PRENOM");
		$this->CIVILITE = $oMSG->getData("CIVILITE");
		$this->EMAIL = $oMSG->getData("EMAIL");
		$this->MDP = $oMSG->getData("MDP");
		$this->TYPE_PERSONNE = $oMSG->getData("TYPE_PERSONNE");
		$this->CONNAISSANCE_SITE = $oMSG->getData("CONNAISSANCE_SITE");
		$this->NEWSLETTER = $oMSG->getData("NEWSLETTER");
		$this->OFFRES_ANNONCEURS = $oMSG->getData("OFFRES_ANNONCEURS");
		$this->PARRAIN = $oMSG->getData("PARRAIN");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->CLE_ACTIVATION = $oMSG->getData("CLE_ACTIVATION");
		
	
		$this->sql = "INSERT INTO personne (PSEUDO, NOM, PRENOM, CIVILITE, EMAIL, MDP, TYPE_PERSONNE, CONNAISSANCE_SITE, NEWSLETTER, OFFRES_ANNONCEURS, PARRAIN, VISIBLE, CLE_ACTIVATION) ".
					 "VALUES (:PSEUDO, :NOM, :PRENOM, :CIVILITE, :EMAIL, :MDP, :TYPE_PERSONNE, :CONNAISSANCE_SITE, :NEWSLETTER, :OFFRES_ANNONCEURS, :PARRAIN, :VISIBLE, :CLE_ACTIVATION);";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					":NOM"=>$this->NOM,
					":PRENOM"=>$this->PRENOM,
					":CIVILITE"=>$this->CIVILITE,
					":EMAIL"=>$this->EMAIL,
					":MDP"=>$this->MDP,
					":TYPE_PERSONNE"=>$this->TYPE_PERSONNE,
					":CONNAISSANCE_SITE"=>$this->CONNAISSANCE_SITE,
					":NEWSLETTER"=>$this->NEWSLETTER,
					":OFFRES_ANNONCEURS"=>$this->OFFRES_ANNONCEURS,
					":PARRAIN"=>$this->PARRAIN,
					":VISIBLE"=>$this->VISIBLE,
					":CLE_ACTIVATION"=>$this->CLE_ACTIVATION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function INSERT_IP($oMSG){
		$this->ID_IP = $oMSG->getData("ID_IP");
	
		$this->sql = "INSERT INTO ip (ID_IP) VALUES (:ID_IP);";
		$params = array(    
					":ID_IP"=>$this->ID_IP,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function INSERT_liaison_IP_et_PERSONNE($oMSG){
		$this->ID_IP = $oMSG->getData("ID_IP");
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->IP_COOKIE = $oMSG->getData("IP_COOKIE");
		$this->COOKIE_DETRUIT = $oMSG->getData("COOKIE_DETRUIT");
		$this->DATE_CONNEXION = $oMSG->getData("DATE_CONNEXION");
	
		$this->sql = "INSERT INTO ip_personne (ID_IP, ID_PERSONNE, IP_COOKIE, COOKIE_DETRUIT, DATE_CONNEXION) VALUES (:ID_IP, :ID_PERSONNE, :IP_COOKIE, :COOKIE_DETRUIT, :DATE_CONNEXION);";
		$params = array(    
					":ID_IP"=>$this->ID_IP,
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":IP_COOKIE"=>$this->IP_COOKIE,
					":COOKIE_DETRUIT"=>$this->COOKIE_DETRUIT,
					":DATE_CONNEXION"=>$this->DATE_CONNEXION,
					);

		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// --------------------------------------------------------------- UPDATE
	
	public function UPDATE_activation_compte($oMSG){
		$this->CLE_ACTIVATION = $oMSG->getData("CLE_ACTIVATION");
		$this->EMAIL = $oMSG->getData("EMAIL");
	
		$this->sql = "UPDATE personne SET CLE_ACTIVATION=:CLE_ACTIVATION, VISIBLE=true WHERE EMAIL=:EMAIL;";
		$params = array(    
					":CLE_ACTIVATION"=>$this->CLE_ACTIVATION,
					":EMAIL"=>$this->EMAIL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_TYPE_PERSONNE_by_PSEUDO($oMSG){
		$this->PSEUDO = $oMSG->getData("PSEUDO");
		$this->TYPE_PERSONNE = $oMSG->getData("TYPE_PERSONNE");
	
		$this->sql = "UPDATE personne SET TYPE_PERSONNE=:TYPE_PERSONNE WHERE PSEUDO=:PSEUDO;";
		$params = array(    
					":PSEUDO"=>$this->PSEUDO,
					":TYPE_PERSONNE"=>$this->TYPE_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_validite_compte_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->DATE_BANNISSEMENT = $oMSG->getData("DATE_BANNISSEMENT");
		$this->DATE_SUPPRESSION_REELLE = $oMSG->getData("DATE_SUPPRESSION_REELLE");
		$this->RAISON_SUPPRESSION = $oMSG->getData("RAISON_SUPPRESSION");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->PERSONNE_SUPPRIMEE = $oMSG->getData("PERSONNE_SUPPRIMEE");
	
		$this->sql = "UPDATE personne SET DATE_BANNISSEMENT=:DATE_BANNISSEMENT, DATE_SUPPRESSION_REELLE=:DATE_SUPPRESSION_REELLE, RAISON_SUPPRESSION=:RAISON_SUPPRESSION, VISIBLE=:VISIBLE, PERSONNE_SUPPRIMEE=:PERSONNE_SUPPRIMEE ".
		"WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":DATE_BANNISSEMENT"=>$this->DATE_BANNISSEMENT,
					":DATE_SUPPRESSION_REELLE"=>$this->DATE_SUPPRESSION_REELLE,
					":RAISON_SUPPRESSION"=>$this->RAISON_SUPPRESSION,
					":VISIBLE"=>$this->VISIBLE,
					":PERSONNE_SUPPRIMEE"=>$this->PERSONNE_SUPPRIMEE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UPDATE_fiche_personnelle_basique_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->NOM = $oMSG->getData("NOM");
		$this->PRENOM = $oMSG->getData("PRENOM");
		$this->CIVILITE = $oMSG->getData("CIVILITE");
		$this->DATE_NAISSANCE = $oMSG->getData("DATE_NAISSANCE");
		$this->URL_PHOTO_PRINCIPALE = $oMSG->getData("URL_PHOTO_PRINCIPALE");
		$this->EMAIL = $oMSG->getData("EMAIL");
		$this->TEL_FIXE = $oMSG->getData("TEL_FIXE");
		$this->TEL_PORTABLE = $oMSG->getData("TEL_PORTABLE");
		$this->REDUCTION = $oMSG->getData("REDUCTION");
		$this->ADRESSE = $oMSG->getData("ADRESSE");
		$this->CP = $oMSG->getData("CP");
		$this->VILLE = $oMSG->getData("VILLE");
		$this->NEWSLETTER = $oMSG->getData("NEWSLETTER");
		$this->OFFRES_ANNONCEURS = $oMSG->getData("OFFRES_ANNONCEURS");

	
		$this->sql = "UPDATE personne SET NOM=:NOM, PRENOM=:PRENOM, CIVILITE=:CIVILITE, DATE_NAISSANCE=:DATE_NAISSANCE, ".
		"URL_PHOTO_PRINCIPALE=:URL_PHOTO_PRINCIPALE, EMAIL=:EMAIL, TEL_FIXE=:TEL_FIXE, TEL_PORTABLE=:TEL_PORTABLE, REDUCTION=:REDUCTION, ".
		"ADRESSE=:ADRESSE, CP=:CP, VILLE=:VILLE, NEWSLETTER=:NEWSLETTER, OFFRES_ANNONCEURS=:OFFRES_ANNONCEURS ".
		"WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":NOM"=>$this->NOM,
					":PRENOM"=>$this->PRENOM,
					":CIVILITE"=>$this->CIVILITE,
					":DATE_NAISSANCE"=>$this->DATE_NAISSANCE,
					":URL_PHOTO_PRINCIPALE"=>$this->URL_PHOTO_PRINCIPALE,
					":EMAIL"=>$this->EMAIL,
					":TEL_FIXE"=>$this->TEL_FIXE,
					":TEL_PORTABLE"=>$this->TEL_PORTABLE,
					":REDUCTION"=>$this->REDUCTION,
					":ADRESSE"=>$this->ADRESSE,
					":CP"=>$this->CP,
					":VILLE"=>$this->VILLE,
					":NEWSLETTER"=>$this->NEWSLETTER,
					":OFFRES_ANNONCEURS"=>$this->OFFRES_ANNONCEURS,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	public function UPDATE_fiche_personnelle_complete_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->NOM = $oMSG->getData("NOM");
		$this->PRENOM = $oMSG->getData("PRENOM");
		$this->CIVILITE = $oMSG->getData("CIVILITE");
		$this->DATE_NAISSANCE = $oMSG->getData("DATE_NAISSANCE");
		$this->URL_PHOTO_PRINCIPALE = $oMSG->getData("URL_PHOTO_PRINCIPALE");
		$this->EMAIL = $oMSG->getData("EMAIL");
		$this->TEL_FIXE = $oMSG->getData("TEL_FIXE");
		$this->TEL_PORTABLE = $oMSG->getData("TEL_PORTABLE");
		$this->REDUCTION = $oMSG->getData("REDUCTION");
		$this->ADRESSE = $oMSG->getData("ADRESSE");
		$this->CP = $oMSG->getData("CP");
		$this->VILLE = $oMSG->getData("VILLE");
		$this->NEWSLETTER = $oMSG->getData("NEWSLETTER");
		$this->OFFRES_ANNONCEURS = $oMSG->getData("OFFRES_ANNONCEURS");
		
		$this->DESCRIPTION = $oMSG->getData("DESCRIPTION");
		$this->STATUT_PERSONNE = $oMSG->getData("STATUT_PERSONNE");
		$this->DEPARTEMENTS = $oMSG->getData("DEPARTEMENTS");
		$this->SIRET = $oMSG->getData("SIRET");
		$this->TARIFS = $oMSG->getData("TARIFS");
		$this->DISTANCE_PRESTATION_MAX = $oMSG->getData("DISTANCE_PRESTATION_MAX");
		$this->CV_VIDEO = $oMSG->getData("CV_VIDEO");
		$this->MATERIEL = $oMSG->getData("MATERIEL");
		// Les ANNONCES_VISITEES ne sont pas gérées pour le moment. 

	
		$this->sql = "UPDATE personne SET NOM=:NOM, PRENOM=:PRENOM, CIVILITE=:CIVILITE, DATE_NAISSANCE=:DATE_NAISSANCE, ".
		"URL_PHOTO_PRINCIPALE=:URL_PHOTO_PRINCIPALE, EMAIL=:EMAIL, TEL_FIXE=:TEL_FIXE, TEL_PORTABLE=:TEL_PORTABLE, REDUCTION=:REDUCTION, ADRESSE=:ADRESSE, ".
		"CP=:CP, VILLE=:VILLE, NEWSLETTER=:NEWSLETTER, OFFRES_ANNONCEURS=:OFFRES_ANNONCEURS, DESCRIPTION=:DESCRIPTION, ".
		"STATUT_PERSONNE=:STATUT_PERSONNE, DEPARTEMENTS=:DEPARTEMENTS, SIRET=:SIRET, TARIFS=:TARIFS, ".
		"DISTANCE_PRESTATION_MAX=:DISTANCE_PRESTATION_MAX, CV_VIDEO=:CV_VIDEO, MATERIEL=:MATERIEL ".
		"WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":NOM"=>$this->NOM,
					":PRENOM"=>$this->PRENOM,
					":CIVILITE"=>$this->CIVILITE,
					":DATE_NAISSANCE"=>$this->DATE_NAISSANCE,
					":URL_PHOTO_PRINCIPALE"=>$this->URL_PHOTO_PRINCIPALE,
					":EMAIL"=>$this->EMAIL,
					":TEL_FIXE"=>$this->TEL_FIXE,
					":TEL_PORTABLE"=>$this->TEL_PORTABLE,
					":REDUCTION"=>$this->REDUCTION,
					":ADRESSE"=>$this->ADRESSE,
					":CP"=>$this->CP,
					":VILLE"=>$this->VILLE,
					":NEWSLETTER"=>$this->NEWSLETTER,
					":OFFRES_ANNONCEURS"=>$this->OFFRES_ANNONCEURS,
					":DESCRIPTION"=>$this->DESCRIPTION,
					":STATUT_PERSONNE"=>$this->STATUT_PERSONNE,
					":DEPARTEMENTS"=>$this->DEPARTEMENTS,
					":SIRET"=>$this->SIRET,
					":TARIFS"=>$this->TARIFS,
					":DISTANCE_PRESTATION_MAX"=>$this->DISTANCE_PRESTATION_MAX,
					":CV_VIDEO"=>$this->CV_VIDEO,
					":MATERIEL"=>$this->MATERIEL,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_MDP_by_ID_PERSONNE($oMSG){
		$this->MDP = $oMSG->getData("MDP");
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "UPDATE personne SET MDP=:MDP WHERE ID_PERSONNE=:ID_PERSONNE";
		$params = array(    
					":MDP"=>$this->MDP,
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_infos_perso_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->NOM = $oMSG->getData("NOM");
		$this->PRENOM = $oMSG->getData("PRENOM");
		$this->CIVILITE = $oMSG->getData("CIVILITE");
		$this->DATE_NAISSANCE = $oMSG->getData("DATE_NAISSANCE");
		$this->URL_PHOTO_PRINCIPALE = $oMSG->getData("URL_PHOTO_PRINCIPALE");
		$this->TEL_FIXE = $oMSG->getData("TEL_FIXE");
		$this->TEL_PORTABLE = $oMSG->getData("TEL_PORTABLE");
		$this->ADRESSE = $oMSG->getData("ADRESSE");
		$this->CP = $oMSG->getData("CP");
		$this->VILLE = $oMSG->getData("VILLE");

	
		$this->sql = "UPDATE personne SET NOM=:NOM, PRENOM=:PRENOM, CIVILITE=:CIVILITE, DATE_NAISSANCE=:DATE_NAISSANCE, URL_PHOTO_PRINCIPALE=:URL_PHOTO_PRINCIPALE, ".
		"TEL_FIXE=:TEL_FIXE, TEL_PORTABLE=:TEL_PORTABLE, ADRESSE=:ADRESSE, CP=:CP, VILLE=:VILLE WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":NOM"=>$this->NOM,
					":PRENOM"=>$this->PRENOM,
					":CIVILITE"=>$this->CIVILITE,
					":DATE_NAISSANCE"=>$this->DATE_NAISSANCE,
					":URL_PHOTO_PRINCIPALE"=>$this->URL_PHOTO_PRINCIPALE,
					":TEL_FIXE"=>$this->TEL_FIXE,
					":TEL_PORTABLE"=>$this->TEL_PORTABLE,
					":ADRESSE"=>$this->ADRESSE,
					":CP"=>$this->CP,
					":VILLE"=>$this->VILLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_REDUCTION_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->REDUCTION = $oMSG->getData("REDUCTION");

	
		$this->sql = "UPDATE personne SET REDUCTION=:REDUCTION WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":REDUCTION"=>$this->REDUCTION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_REDUCTION_by_ID_PARRAIN($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->REDUCTION = $oMSG->getData("REDUCTION");

	
		$this->sql = "UPDATE personne SET REDUCTION=REDUCTION+:REDUCTION WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":REDUCTION"=>$this->REDUCTION,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function UDPATE_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->ANNONCES_VISITEES = $oMSG->getData("ANNONCES_VISITEES");

	
		$this->sql = "UPDATE personne SET ANNONCES_VISITEES=:ANNONCES_VISITEES WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":ANNONCES_VISITEES"=>$this->ANNONCES_VISITEES,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}
?><?php
class MPG_types{

	private $ID_TYPES;
	private $ID_FAMILLE_TYPES;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_TYPES = "";
	$this->ID_FAMILLE_TYPES = "";
	}
	
	
	public function SELECT_COUNT_ID_TYPES_by_ID_TYPES($oMSG){
		$this->ID_TYPES = $oMSG->getData("ID_TYPES");
	
		$this->sql = "SELECT COUNT(ID_TYPES) as nb_types FROM types WHERE ID_TYPES=:ID_TYPES;";
		$params = array(    
					":ID_TYPES"=>$this->ID_TYPES,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
}<?php
/**
* Cette classe permet de transmettre un flot d'informations très importantes.
* A utiliser pour transmettre la plupart des informations.
*/
class MSG{
	
	private $statut; # Booléen de validation des diverses opérations.
	private $message_erreur; # Message d'erreur à l'intention de l'utilisateur.
	private $data; # Tableau contenant toutes les données.
	
	function __construct(){
		$this->statut = false;
		$this->message_erreur = NULL;
		$this->data = array();
	
	}
	
	# Accesseurs:
	public function getStatut(){
		return $this->statut;
	}
	
	public function getMessage_erreur(){
		return $this->message_erreur;
	}
	
	public function getData($data){
		return $this->data[$data];
	}
	
	public function getDatas(){
		return $this->data;
	}


	# Mutateur:
	public function setStatut($statut){
		$this->statut = $statut;
	}
	
	public function setMessage_erreur($message){
		$this->message_erreur = $message;
	}
	
	public function setData($key, $value){
		$this->data[$key] = $value;
	}
	
	public function setDatas($data){
		$this->data = $data;
	}
	
}




?><?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_annonce.php';
require_once 'couche_metier/MPG_departement.php';
require_once 'couche_metier/VIEW_annonce.php';

class PCS_annonce{

	private $oCAD;
	private $oMPG_annonce;
	private $oMPG_departement;
	private $oVIEW_annonce;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_annonce = new MPG_annonce();
		$this->oMPG_departement = new MPG_departement();
		$this->oVIEW_annonce = new VIEW_annonce();
	}
	
	// ----------------------- MPG_annonce --------------------------
	
	public function fx_recuperer_annonce_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_all_by_ID_ANNONCE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_annonce_by_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_COUNT_ID_ANNONCE_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_toutes_annonces_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_COUNT_ID_ANNONCE_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_toutes_annonces_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_toutes_annonces_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_annonces_futures_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_COUNT_ID_ANNONCE_futures_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_toutes_annonces_futures_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_annonces_futures_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_annonces_par_criteres($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_COUNT_annonces_par_criteres($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_annonce_valide_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_annonce_valide_by_ID_ANNONCE($oMSG));
		
		return $oMSG;
	}
	
	// ----------------------- MPG_departement ----------------------
	
	public function fx_recuperer_tous_departements($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_departement->SELECT_all($oMSG));
		
		return $oMSG;
	}
	
	
	// --------------------- VIEW_annonce --------------------------
	
	public function fx_recuperer_min_annonce_by_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_annonce->SELECT_min_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_annonces_par_criteres($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_annonce->SELECT_annonces_par_criteres($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_annonce_complete_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_annonce->SELECT_annonce_complete_by_ID_ANNONCE($oMSG));
		
		return $oMSG;
	}
	
	// ---------------------------------------------------------------- ActionRows ----------------------------------------------------------------------
	
	// ----------------------- MPG_annonce ----------------------------
	
	public function fx_creer_annonce($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_annonce->INSERT($oMSG), true);
		
		return $oMSG;
	}
	
	public function fx_modifier_annonce_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_annonce->UPDATE_by_ID_ANNONCE($oMSG), true);
		
		return $oMSG;
	}
}<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_contrat.php';
require_once 'couche_metier/MPG_contrat_personne.php';
require_once 'couche_metier/VIEW_contrat.php';

class PCS_contrat{

	private $oCAD;
	private $oMPG_contrat;
	private $oVIEW_contrat;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_contrat = new MPG_contrat();
		$this->oMPG_contrat_personne = new MPG_contrat_personne();
		$this->oVIEW_contrat = new VIEW_contrat();
	}
	
	// ----------------------- MPG_contrat --------------------------
	
	public function fx_compter_contrat_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_contrat->SELECT_COUNT_ID_CONTRAT_by_ID_ANNONCE($oMSG));
		
		return $oMSG;
	}
	
	
	// ----------------------- VIEW_contrat --------------------------
	
	public function fx_compter_contrat_by_ID_ANNONCE_et_ID_PERSONNE_et_condition($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_COUNT_nb_contrat_by_ID_PERSONNE_et_ID_ANNONCE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_contrat_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_COUNT_nb_contrat_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_contrat_min_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_contrat_min_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	
	// ---------------------------------------------------------------- ActionRows ----------------------------------------------------------------------
	
	// ----------------------- MPG_contrat ----------------------------
	
	public function fx_creer_contrat($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_contrat->INSERT($oMSG), true);
		
		return $oMSG;
	}
	
	// ---------------------- MPG_contrat_personne --------------------
	
	public function fx_lier_contrat($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_contrat_personne->INSERT($oMSG));
		
		return $oMSG;
	}
}<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_message.php';
require_once 'couche_metier/MPG_message_personne.php';
require_once 'couche_metier/VIEW_message.php';

class PCS_message{

	private $oCAD;
	private $oMPG_message;
	private $oMPG_departement;
	private $oVIEW_message;
	
	public function __construct(){
		$this->oCAD = new CAD();
		$this->oMPG_message = new MPG_message();
		$this->oMPG_message_personne = new MPG_message_personne();
		$this->oVIEW_message = new VIEW_message();
	
	}
	// ---------------------------------------------- GetRow --------------------------------------------

	
	
	
	// ------------------------ VIEW_message -----------------------

	public function fx_compter_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_message->SELECT_COUNT_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_message->SELECT_min_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_message->SELECT_COUNT_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_message->SELECT_min_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_message_by_ID_MESSAGE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_message->SELECT_message_by_ID_MESSAGE($oMSG));
		
		return $oMSG;
	}
	
	// ---------------------------------------------- ActionRows --------------------------------------------
	
	// ---------------------- MPG_message_personne ------------------

	public function fx_creer_message($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_message->INSERT($oMSG), true);# Récupération de l'ID crée.
		
		return $oMSG;
	}
	// ------------------------- MPG_message_personne -----------------
	
	public function fx_lier_message($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_message_personne->INSERT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_message_lu($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_message_personne->UPDATE_message_lu_by_ID_MESSAGE($oMSG));
		
		return $oMSG;
	}

}
?><?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_pack.php';
require_once 'couche_metier/MPG_pack_personne.php';
require_once 'couche_metier/VIEW_pack.php';

class PCS_pack{

	private $oCAD;
	private $oMPG_pack;
	private $oMPG_pack_personne;
	private $oVIEW_pack;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_pack = new MPG_pack();
		$this->oMPG_pack_personne = new MPG_pack_personne();
		$this->oVIEW_pack = new VIEW_pack();
	}
	
	public function fx_compter_tous_packs($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_COUNT_all_packs($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_packs_by_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_COUNT_PACK_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_packs($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_all_minimum($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_packs_by_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_all_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_pack_by_ID_PACK($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_all_by_ID_PACK($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_pack_by_TYPE_PACK_et_LIMIT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_pack->SELECT_ALL_by_TYPE_PACK_et_LIMIT($oMSG));
		
		return $oMSG;
	}
	// ---------------------- oMPG_pack_personne -------------
	
	
	
	
	// ---------------------- oVIEW_pack ---------------------
	
	public function fx_recuperer_pack_actif_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_ALL_by_DATE_ACHAT_et_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_dernier_pack_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_dernier_achat_by_DATE_ACHAT_et_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_tous_packs_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_COUNT_ID_PACK_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_packs_by_ID_PERSONNE_et_by_LIMIT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_ALL_by_ID_PERSONNE_et_by_LIMIT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_pack_by_ID_PERSONNE_et_DATE_ACHAT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_pack->SELECT_pack_by_ID_PERSONNE_et_DATE_ACHAT($oMSG));
		
		return $oMSG;
	}
	
	// ------------------------------------------------------------- ActionRows  ------------------------------------------
	
	public function fx_creer_pack($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack->INSERT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_pack($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack->UPDATE($oMSG));
		
		return $oMSG;
	}
	
	// ---------------------- oMPG_pack_personne --------------
	
	public function fx_lier_pack_personne($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack_personne->INSERT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_DATE_FIN_by_IDs($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack_personne->UPDATE_DATE_FIN_by_IDs($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_DATE_DEBUT_et_DATE_FIN_by_IDs($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack_personne->UPDATE_DATE_DEBUT_et_DATE_FIN_by_IDs($oMSG));
		
		return $oMSG;
	}
	
	public function fx_decrementer_NB_FICHES_VISITABLES_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_pack_personne->UPDATE_decremente_NB_FICHES_VISITABLES_by_IDs($oMSG));
		
		return $oMSG;
	}
	
}<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_personne.php';
require_once 'couche_metier/VIEW_personne.php';

# Toutes la gestion des affichages/modification des IP est géré depuis le MPG_personne et la VIEW_personne car ils sont intimement liés.

class PCS_personne{

	private $oCAD;
	private $oMPG_personne;
	private $oVIEW_personne;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_personne = new MPG_personne();
		$this->oVIEW_personne = new VIEW_personne();
	}
	
	public function fx_compter_pseudo_by_PSEUDO($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_PSEUDO($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_email_by_EMAIL($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_EMAIL($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_compte_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_compte_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	
	public function fx_compter_pseudo_by_PSEUDO_et_MDP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_pseudo_by_PSEUDO_et_MDP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_personne_by_ID_PERSONNE_et_MDP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_personne_by_ID_PERSONNE_et_MDP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_compte_by_PSEUDO($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_all_by_PSEUDO($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_compte_by_PSEUDO_et_EMAIL($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_all_by_PSEUDO_et_EMAIL($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_by_EMAIL_et_CLE_ACTIVATION($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_by_EMAIL_et_CLE_ACTIVATION($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_IPs_by_ID_IP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_ID_IP_by_ID_IP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_IPs_by_IP_COOKIE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_ID_IP_by_IP_COOKIE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_comptes_non_actives($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_by_CLE_ACTIVATION($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_infos_by_ID_IP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_by_ID_IP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_infos_by_IP_COOKIE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_by_IP_COOKIE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_membres($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_tous_membres($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_tous_membres($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_tous_membres($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_membres_by_LIMIT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_tous_membres_by_LIMIT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_comptes_non_actives($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_by_CLE_ACTIVATION($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_comptes_by_PERSONNE_SUPPRIMEE_et_VISIBILITE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_comptes_by_PERSONNE_SUPPRIMEE_et_VISIBILITE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_comptes_supprimes($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_comptes_supprimes_par_utilisateur($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_REDUCTION_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_REDUCTION_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_personne_by_ID_PERSONNE_et_EMAIL_et_MDP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_ID_PERSONNE_by_ID_PERSONNE_et_EMAIL_et_MDP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_filleuls_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_filleuls_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_adresse_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_adresse_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_PSEUDO_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_PSEUDO_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	// -------------------- Vues
	
	public function fx_recuperer_toutes_ip_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_toutes_ip_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_date_creation_compte($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_date_creation_compte($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_dernieres_connexions_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_dernieres_connexions_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	
	// ------------------------------------------ Insertions/Modifications
	
	public function fx_creer_compte($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->INSERT_all($oMSG), true);# On récupère l'ID crée
		
		return $oMSG;
	}
	
	public function fx_creer_IP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->INSERT_IP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_valider_compte($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UPDATE_activation_compte($oMSG));
		
		return $oMSG;
	}
	
	public function fx_lier_IP_et_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->INSERT_liaison_IP_et_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_rang($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UPDATE_TYPE_PERSONNE_by_PSEUDO($oMSG));
		
		return $oMSG;
	}
	
	public function fx_bannir_personne($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UPDATE_validite_compte_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_maj_fiche_personnelle_selon_TYPE_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		
		if($oMSG->getData('TYPE_PERSONNE') == 'Admin' || $oMSG->getData('TYPE_PERSONNE') == 'Prestataire'){
			$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UPDATE_fiche_personnelle_complete_by_ID_PERSONNE($oMSG));
		}else if($oMSG->getData('TYPE_PERSONNE') == 'Organisateur'){
			$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UPDATE_fiche_personnelle_basique_by_ID_PERSONNE($oMSG));
		}else{
			return "Erreur: Type de la personne non défini.";
		}
		
		
		return $oMSG;
	}
	
	public function fx_changer_mdp($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_MDP_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_supprimer_infos_perso_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_infos_perso_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_REDUCTION_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_REDUCTION_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_REDUCTION_by_ID_PARRAIN($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_REDUCTION_by_ID_PARRAIN($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	
}

?><?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_types.php';
require_once 'couche_metier/MPG_famille_types.php';
require_once 'couche_metier/VIEW_types.php';

class PCS_types{

	private $oCAD;
	private $oMPG_types;
	private $oMPG_famille_types;
	private $oVIEW_types;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_types = new MPG_types();
		$this->oMPG_famille_types = new MPG_famille_types();
		$this->oVIEW_types = new VIEW_types();
	}
	
	public function fx_recuperer_tous_types_par_famille($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_types->SELECT_ALL_BY_ID_FAMILLE_TYPES($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_types_by_ID_TYPES($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_types->SELECT_COUNT_ID_TYPES_by_ID_TYPES($oMSG));
		
		return $oMSG;
	}
	
}<?php
class VIEW_annonce{

	private $ID_ANNONCE;
	private $ID_DEPARTEMENT;
	private $VISIBLE;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_ANNONCE = "";
	$this->ID_DEPARTEMENT = "";
	$this->VISIBLE = "";
	}
	
	
	public function SELECT_min_by_VISIBLE($oMSG){
		$this->VISIBLE = $oMSG->getData('VISIBLE');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');
	
		$this->sql = "SELECT ID_ANNONCE, annonce.ID_PERSONNE AS ID_PERSONNE, TITRE, TYPE_ANNONCE, DATE_ANNONCE, GOLDLIVE, STATUT, PSEUDO ".
		"FROM personne LEFT OUTER JOIN annonce ON personne.ID_PERSONNE=annonce.ID_PERSONNE WHERE annonce.VISIBLE=:VISIBLE ORDER BY STATUT, GOLDLIVE DESC, DATE_ANNONCE ".
		"LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(  
					':VISIBLE' =>$this->VISIBLE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_annonces_par_criteres($oMSG){
		$this->VISIBLE = $oMSG->getData('VISIBLE');
		$this->STATUT = $oMSG->getData('STATUT');
		$criteres = $oMSG->getData('criteres');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');
	
		$this->sql = 
		"SELECT annonce.ID_ANNONCE as ID_ANNONCE, annonce.TITRE as TITRE, annonce.TYPE_ANNONCE, annonce.DATE_DEBUT, annonce.DATE_FIN, ".
		"annonce.BUDGET AS BUDGET, NB_CONVIVES FROM annonce LEFT OUTER JOIN contrat ON annonce.ID_ANNONCE = contrat.ID_ANNONCE ".
		"WHERE annonce.VISIBLE=:VISIBLE AND annonce.STATUT=:STATUT $criteres ".
		"ORDER BY annonce.GOLDLIVE DESC, annonce.DATE_DEBUT DESC ".
		"LIMIT $debut_affichage, $nb_result_affiches;";

		$params = array(  
					':VISIBLE' =>$this->VISIBLE,
					':STATUT' =>$this->STATUT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_annonce_complete_by_ID_ANNONCE($oMSG){
		$this->ID_ANNONCE = $oMSG->getData('ID_ANNONCE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
	
		$this->sql = "SELECT annonce.ID_ANNONCE, annonce.ID_PERSONNE AS ID_PERSONNE, departement.ID_DEPARTEMENT AS ID_DEPARTEMENT, annonce.TITRE, ".
		"annonce.TYPE_ANNONCE, annonce.DATE_ANNONCE, annonce.GOLDLIVE, annonce.STATUT, annonce.DATE_DEBUT, annonce.DATE_FIN, ".
		"annonce.ARTISTES_RECHERCHES, annonce.BUDGET, annonce.NB_CONVIVES, annonce.DESCRIPTION, annonce.ADRESSE, annonce.VILLE, annonce.CP, ".
		"personne.PSEUDO, departement.NOM FROM annonce LEFT OUTER JOIN departement ON departement.ID_DEPARTEMENT=annonce.ID_DEPARTEMENT LEFT OUTER JOIN personne ".
		"ON personne.ID_PERSONNE = annonce.ID_PERSONNE WHERE annonce.VISIBLE=:VISIBLE AND annonce.ID_ANNONCE=:ID_ANNONCE";
		
		$params = array(  
					':ID_ANNONCE' =>$this->ID_ANNONCE,
					':VISIBLE' =>$this->VISIBLE,					
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
}<?php
class VIEW_contrat{

	private $ID_CONTRAT;
	private $ID_ANNONCE;
	private $VISIBLE;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_CONTRAT = "";
	$this->ID_ANNONCE = "";
	$this->VISIBLE = "";
	}
	
	
	public function SELECT_COUNT_nb_contrat_by_ID_PERSONNE_et_ID_ANNONCE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->ID_ANNONCE = $oMSG->getData('ID_ANNONCE');
		$condition = $oMSG->getData('condition');
		if(empty($condition)){
			$condition = ";";
		}
	
		$this->sql = "SELECT COUNT(ID_CONTRAT) as nb_contrat FROM contrat NATURAL JOIN contrat_personne WHERE ID_PERSONNE=:ID_PERSONNE AND ID_ANNONCE=:ID_ANNONCE $condition;";
		
		$params = array(  
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					':ID_ANNONCE' =>$this->ID_ANNONCE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_nb_contrat_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
	
		$this->sql = "SELECT COUNT(contrat.ID_CONTRAT) as nb_contrat FROM contrat RIGHT OUTER JOIN contrat_personne ".
		"ON contrat_personne.ID_CONTRAT = contrat.ID_CONTRAT WHERE contrat_personne.ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(  
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_contrat_min_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
		$debut_affichage = $oMSG->getData('debut_affichage');

		$this->sql = "SELECT contrat.ID_CONTRAT, contrat.ID_ANNONCE, DATE_CONTRAT, STATUT_CONTRAT, URL_CONTRAT_PDF, annonce.TITRE  FROM contrat_personne ".
		"LEFT OUTER JOIN contrat ON contrat_personne.ID_CONTRAT = contrat.ID_CONTRAT ".
		"LEFT OUTER JOIN annonce ON annonce.ID_ANNONCE = contrat.ID_ANNONCE ".
		"WHERE contrat_personne.ID_PERSONNE=:ID_PERSONNE ".
		"LIMIT $debut_affichage, $nb_result_affiches;";
		
		$params = array(  
					':ID_PERSONNE' =>$this->ID_PERSONNE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
		
}<?php
class VIEW_message{

	private $ID_MESSAGE;
	private $ID_PERSONNE;
	private $STATUT_MESSAGE;
	private $DATE_LECTURE;
	private $DATE_REPONSE;

	
	public function __construct(){
		$this->ID_MESSAGE="";
		$this->ID_PERSONNE="";
		$this->STATUT_MESSAGE="";
		$this->DATE_LECTURE="";
		$this->DATE_REPONSE="";

	
	}
	
	public function SELECT_COUNT_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
	
		$this->sql = "SELECT COUNT(message.ID_MESSAGE) as nb_message FROM message LEFT OUTER JOIN message_personne ".
		"ON message.ID_MESSAGE = message_personne.ID_MESSAGE WHERE ID_PERSONNE=:ID_PERSONNE AND STATUT_MESSAGE<>:STATUT_MESSAGE AND VISIBLE=:VISIBLE;";# /!\ <>
		
		$params = array(
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					':VISIBLE'=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_min_message_by_nonSTATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
		$debut_affichage = $oMSG->getData('debut_affichage');
		$nb_result_affiches = $oMSG->getData('nb_result_affiches');
	
		$this->sql = "SELECT message.ID_MESSAGE as ID_MESSAGE, TITRE, DATE_ENVOI, EXPEDITEUR, message_personne.ID_PERSONNE, STATUT_MESSAGE FROM message ".
		"LEFT OUTER JOIN message_personne ON message.ID_MESSAGE = message_personne.ID_MESSAGE ".
		"WHERE ID_PERSONNE=:ID_PERSONNE AND STATUT_MESSAGE<>:STATUT_MESSAGE AND VISIBLE=:VISIBLE ".
		"LIMIT $debut_affichage, $nb_result_affiches;";# /!\ <> !
		
		$params = array(
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					':VISIBLE'=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
	
		$this->sql = "SELECT COUNT(message.ID_MESSAGE) as nb_message FROM message LEFT OUTER JOIN message_personne ".
		"ON message.ID_MESSAGE = message_personne.ID_MESSAGE WHERE ID_PERSONNE=:ID_PERSONNE AND STATUT_MESSAGE=:STATUT_MESSAGE AND VISIBLE=:VISIBLE;";
		
		$params = array(
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					':VISIBLE'=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_min_message_by_STATUT_et_ID_PERSONNE_et_VISIBLE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData('ID_PERSONNE');
		$this->STATUT_MESSAGE = $oMSG->getData('STATUT_MESSAGE');
		$this->VISIBLE = $oMSG->getData('VISIBLE');
	
		$this->sql = "SELECT message.ID_MESSAGE as ID_MESSAGE, TITRE, DATE_ENVOI, EXPEDITEUR, ID_PERSONNE, STATUT_MESSAGE FROM message ".
		"LEFT OUTER JOIN message_personne ON message.ID_MESSAGE = message_personne.ID_MESSAGE ".
		"WHERE ID_PERSONNE=:ID_PERSONNE AND STATUT_MESSAGE=:STATUT_MESSAGE AND VISIBLE=:VISIBLE;";
		
		$params = array(
					':ID_PERSONNE'=>$this->ID_PERSONNE,
					':STATUT_MESSAGE'=>$this->STATUT_MESSAGE,
					':VISIBLE'=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_message_by_ID_MESSAGE($oMSG){
		$this->ID_MESSAGE = $oMSG->getData('ID_MESSAGE');
	
		$this->sql = "SELECT message.ID_MESSAGE as ID_MESSAGE, TITRE, CONTENU, DATE_ENVOI, EXPEDITEUR, DESTINATAIRE, TYPE_MESSAGE, ID_PERSONNE, ".
		"STATUT_MESSAGE, DATE_LECTURE, DATE_REPONSE FROM message LEFT OUTER JOIN message_personne ON message.ID_MESSAGE = message_personne.ID_MESSAGE ".
		"WHERE message.ID_MESSAGE=:ID_MESSAGE;";
		
		$params = array(
					':ID_MESSAGE'=>$this->ID_MESSAGE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}<?php
class VIEW_pack{

	private $ID_PERSONNE;
	private $ID_PACK;

	private $sql;

	public function __construct() {
		$this->sql = "";
		$this->ID_PERSONNE = "";
		$this->ID_PACK = "";		
	}
	
	public function SELECT_ALL_by_DATE_ACHAT_et_ID_PERSONNE($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$limit = $oMSG->getData("limit");
		
		$this->sql = "SELECT pack.ID_PACK as ID_PACK, NOM, TYPE_PACK, PRIX_BASE, DUREE, CV_VISIBILITE, CV_ACCESSIBLE, pack_personne.NB_FICHES_VISITABLES ".
		"as NB_FICHES_VISITABLES, CV_VIDEO_ACCESSIBLE, ALERTE_NON_DISPONIBILITE, NB_DEPARTEMENTS_ALERTE, PARRAINAGE_ACTIVE, PREVISUALISATION_FICHES, CONTRATS_PDF, ".
		"SUIVI, PUBS, ID_PERSONNE, DATE_ACHAT, DATE_DEBUT, DATE_FIN ".
		"FROM pack LEFT OUTER JOIN pack_personne ON pack.ID_PACK=pack_personne.ID_PACK WHERE ID_PERSONNE=:ID_PERSONNE ".
		"AND DATE_DEBUT < NOW() AND DATE_FIN > NOW() ORDER BY DATE_ACHAT DESC $limit;";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_dernier_achat_by_DATE_ACHAT_et_ID_PERSONNE($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$limit = $oMSG->getData("limit");
		
		$this->sql = "SELECT pack.ID_PACK as ID_PACK, NOM, TYPE_PACK, PRIX_BASE, DUREE, CV_VISIBILITE, CV_ACCESSIBLE, pack_personne.NB_FICHES_VISITABLES ".
		"as NB_FICHES_VISITABLES, CV_VIDEO_ACCESSIBLE, ALERTE_NON_DISPONIBILITE, NB_DEPARTEMENTS_ALERTE, PARRAINAGE_ACTIVE, PREVISUALISATION_FICHES, CONTRATS_PDF, ".
		"SUIVI, PUBS, ID_PERSONNE, DATE_ACHAT, DATE_DEBUT, DATE_FIN ".
		"FROM pack LEFT OUTER JOIN pack_personne ON pack.ID_PACK=pack_personne.ID_PACK WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY DATE_ACHAT DESC $limit;";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_PACK_by_ID_PERSONNE($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		
		$this->sql = "SELECT COUNT(pack_personne.ID_PACK)  AS nb_pack FROM pack_personne WHERE ID_PERSONNE=:ID_PERSONNE;";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_ALL_by_ID_PERSONNE_et_by_LIMIT($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$nb_result_affiches = $oMSG->getData("nb_result_affiches");
		$debut_affichage = $oMSG->getData("debut_affichage");
		
		$this->sql = "SELECT pack.ID_PACK as ID_PACK, NOM, TYPE_PACK, PRIX_BASE, DUREE, pack_personne.NB_FICHES_VISITABLES as NB_FICHES_VISITABLES, ".
		"ID_PERSONNE, DATE_ACHAT, DATE_DEBUT, DATE_FIN, pack_personne.REDUCTION AS REDUCTION ".
		"FROM pack LEFT OUTER JOIN pack_personne ON pack.ID_PACK=pack_personne.ID_PACK WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY DATE_ACHAT DESC LIMIT $debut_affichage, $nb_result_affiches";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_pack_by_ID_PERSONNE_et_DATE_ACHAT($oMSG){		
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->DATE_ACHAT = $oMSG->getData("DATE_ACHAT");
		
		$this->sql = "SELECT COUNT(pack_personne.ID_PACK) AS nb_pack, pack_personne.ID_PACK AS ID_PACK, ID_PERSONNE, DATE_ACHAT, DATE_DEBUT, DATE_FIN, DUREE ".
		"FROM pack LEFT OUTER JOIN pack_personne ON pack.ID_PACK=pack_personne.ID_PACK ".
		"WHERE ID_PERSONNE=:ID_PERSONNE AND DATE_ACHAT=:DATE_ACHAT;";
		
		$params = array(
				":ID_PERSONNE"=>$this->ID_PERSONNE,
				":DATE_ACHAT"=>$this->DATE_ACHAT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}<?php
class VIEW_personne{

	private $ID_PERSONNE;
	private $ID_IP;
	private $ID_ANNONCE;
	private $ID_PACK;
	private $ID_CONTRAT;
	private $ID_TYPES;
	private $ID_MESSAGE;
	
	private $sql;
	
	public function __construct() {
		$this->sql = "";
		$this->ID_PERSONNE = "";
		$this->ID_IP = "";
		$this->ID_ANNONCE = "";
		$this->ID_PACK = "";
		$this->ID_CONTRAT = "";
		$this->ID_TYPES = "";
		$this->ID_MESSAGE = "";
	}
	
	// ----------------------------------------------------------- Vues sur la table des IP.
	
	public function SELECT_toutes_ip_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT * FROM ip_personne WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY DATE_CONNEXION DESC;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_date_creation_compte($oMSG){
	
		$this->sql = "SELECT * FROM ip_personne ORDER BY DATE_CONNEXION ASC LIMIT 0,1;";
		$params = array(    

						);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_dernieres_connexions_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$limit = $oMSG->getData("limit");
	
		$this->sql = "SELECT * FROM ip_personne WHERE ID_PERSONNE=:ID_PERSONNE ORDER BY DATE_CONNEXION DESC $limit;";
		$params = array(    
						":ID_PERSONNE"=>$this->ID_PERSONNE,
						);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}<?php
class VIEW_types{

	private $ID_TYPES;
	private $ID_FAMILLE_TYPES;
	
	private $sql;
	
	public function __construct() {
		$this->sql = "";
		$this->ID_TYPES = "";
		$this->ID_FAMILLE_TYPES = "";
	}
	
	public function SELECT_ALL_BY_ID_FAMILLE_TYPES($oMSG){
		$this->ID_FAMILLE_TYPES = $oMSG->getData("ID_FAMILLE_TYPES");
	
		$this->sql = "SELECT * FROM types NATURAL JOIN famille_types WHERE ID_FAMILLE_TYPES=:ID_FAMILLE_TYPES;";
		$params = array(    
					":ID_FAMILLE_TYPES"=>$this->ID_FAMILLE_TYPES,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
}<?php 
	class CL_cryptage{
	
	public function Cryptage($MDP, $Clef){
		
		# On rajoute un grain de sable et une clé
		$Clef = "¤".$Clef."V4@";
		
		$LClef = strlen($Clef);
		$LMDP = strlen($MDP);
							
		if ($LClef < $LMDP){
					
			$Clef = str_pad($Clef, $LMDP, $Clef, STR_PAD_RIGHT);
		
		}
					
		elseif ($LClef > $LMDP){

			$diff = $LClef - $LMDP;
			$_Clef = substr($Clef, 0, -$diff);

		}
	    
		return $MDP ^ $Clef; // La fonction envoie le texte crypté
				
	}
	
}
?><?php
class CL_date{

	private $seconde;
	private $minute;
	private $heure;
	private $jour;
	private $mois;
	private $annee;
	
	private $date;
	private $now;
	
	private $tab_date;
	
	public function __construct(){
	
		$this->seconde = date("s");
		$this->minute = date("i");
		$this->heure = date("H");
		$this->jour = date("d");
		$this->mois = date("m");
		$this->annee = date("Y");
		
		$this->date = date("Y-m-d");
		$this->now = date("Y-m-d H:i:s");
		
		$this->tab_date = array();
	}
	
	/**
    * @desc Convertit la date au format desiré.
    * @param date $date : Date que l'on souhaite modifier.
    * @return date
	*
	*	/!\ Utiliser plutôt la fonction fx_ajouter_date qui permet de faire la même chose et plus encore. /!\
	*
	*
    */
	public function fx_convertir_date($date, $datetime = false, $return_date_formatee = false, $langue = "fr", $mois_ajout = 0){
		# On remplace toutes les "/" en "-".
		$this->date = str_replace("/", "-", $date);
		
		if($langue == "fr"){
			if(!$datetime){
				$this->tab_date = explode('-' , $this->date);
				$this->date  = $this->tab_date[2].'-'.$this->tab_date[1].'-'.$this->tab_date[0];
				return $this->date;
			
			}else{
				$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([Y][M][D + H:i:s])	
				$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([D][H:i:s])
				$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
				$date = date("d-m-Y H:i:s", mktime($tab_date3[0], $tab_date3[1], $tab_date3[2], $tab_date[1]+$mois_ajout, $tab_date2[0],  $tab_date[0]));
				$date_formatee = date("YmdHis", mktime($tab_date3[0], $tab_date3[1], $tab_date3[2], $tab_date[1]+$mois_ajout, $tab_date2[0],  $tab_date[0]));
				if(!$return_date_formatee){
					return $date;
				}else{
					return $date_formatee;
				}
			}
			# A vérifier car non testé.
		}else if($langue == "en"){
			if(!$datetime){
				$this->tab_date = explode('-' , $this->date);
				$this->date  = $this->tab_date[0].'-'.$this->tab_date[1].'-'.$this->tab_date[2];
				return $this->date;
			
			}else{
				$tab_date = explode('-', $date);# On récupère la date dans un tableau de trois cases ([Y][M][D + H:i:s])	
				$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([D][H:i:s])
				$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
				$date = date("Y-m-d H:i:s", mktime($tab_date3[0], $tab_date3[1], $tab_date3[2], $tab_date[1]+$mois_ajout, $tab_date2[0],  $tab_date[0]));
				$date_formatee = date("YmdHis", mktime($tab_date3[0], $tab_date3[1], $tab_date3[2], $tab_date[1]+$mois_ajout, $tab_date2[0],  $tab_date[0]));
				if(!$return_date_formatee){
					return $date;
				}else{
					return $date_formatee;
				}
			}
		}
	}
	
	/**
    * @desc Vérifie que la date fournit est bien au format voulu.
    * @param date $date : Date que l'on souhaite vérifier.
	* @param string $format_voulu : Format de la date, en ou fr.	
    * @return date
    */
	public function fx_verif_date($date, $format_voulu = "fr", $datetime = false){
		# On remplace toutes les "/" en "-".
		$this->date = str_replace("/", "-", $date);
		
		$datetime_fr= "/\d{2}\-\d{2}\-\d{4} \d{2}:\d{2}:\d{2}/";
		$datetime_en= "/\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}/";
		
		# On vérifie que le format de $date recu est bien une date.
		$date_fr = "/^\d{2}\-\d{2}\-\d{4}$/";
		$date_en = "/^\d{4}\-\d{2}\-\d{2}$/";
		
		if(!$datetime){
			# Si on souhaite un format date (Y-m-d ou d-m-Y) en sortie.
			if($format_voulu == "fr"){
				if(preg_match($date_fr, $this->date)){
					return true;
				}else{
					return false;
				}
			}else{
				if(preg_match($date_en, $this->date)){
					return true;
				}else{
					return false;
				}
			}
		}else{
			# Si on souhaite un format datetime (Y-m-d H:i:s ou d-m-Y H:i:s) en sortie.
			if($format_voulu == "fr"){
				if(preg_match($datetime_fr, $this->date)){
					return true;
				}else{
					return false;
				}
			}else{
				if(preg_match($datetime_en, $this->date)){
					return true;
				}else{
					return false;
				}
			}
			
		}
	}
	
	/**
	* @author  Ambroise Dhenain
	* @since [Création] 15 septembre 2011
	* @since [Modification] 07 octobre 2011
	* 
	* @desc Transforme la date de départ en lui rajoutant du temps.
	*		Permet de passer du format en->fr ou fr->en
	*		Gère les date et datetime
	*		Peut renvoyer une date formatée permettant d'effectuer des opérations d'égalité entre date. (<, >, =)
	*
	* @return Une date ou datetime.
	*
	*
	*/
	public function fx_ajouter_date($date, $datetime = false, $return_date_formatee = false, $format_fournit = "en", $format_voulu = "en", $jour = 0, $mois = 0, $annee = 0, $heure = 0, $minute = 0, $seconde = 0){
		# On remplace toutes les "/" en "-".
		$this->date = str_replace("/", "-", $date);
		
		if($datetime){	
			if($format_fournit == 'en'){
				if($format_voulu == 'en'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([Y][M][D + H:i:s])	
					$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([D][H:i:s])
					$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
					
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("YmdHis", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date2[0]+$jour,  $tab_date[0]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("Y-m-d H:i:s", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date2[0]+$jour,  $tab_date[0]+$annee));
						return $this->date;
					}
				}else if($format_voulu == 'fr'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([Y][M][D + H:i:s])	
					$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([D][H:i:s])
					$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("dmYHis", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date2[0]+$jour,  $tab_date[0]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("d-m-Y H:i:s", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date2[0]+$jour,  $tab_date[0]+$annee));
						return $this->date;
					}
				}else{
					return "Erreur: Format voulu invalide. (arg 4)";
				}
			}else if($format_fournit == 'fr'){
				if($format_voulu == 'en'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([D][M][Y + H:i:s])	
					$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([Y][H:i:s])
					$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
					
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("YmdHis", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date[0]+$jour,  $tab_date2[0]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("Y-m-d H:i:s", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date[0]+$jour,  $tab_date2[0]+$annee));
						return $this->date;
					}
				}else if($format_voulu == 'fr'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([D][M][Y + H:i:s])	
					$tab_date2 = explode(' ', $tab_date[2]);# On récupère le reste de la date dans un tableau à deux cases ([Y][H:i:s])
					$tab_date3 = explode(':', $tab_date2[1]);# On récupère le reste de la date dans un tableau à 3 cases ([H][i][s])
					
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("YmdHis", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date[0]+$jour,  $tab_date2[0]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("d-m-Y H:i:s", mktime($tab_date3[0]+$heure, $tab_date3[1]+$minute, $tab_date3[2]+$seconde, $tab_date[1]+$mois, $tab_date[0]+$jour,  $tab_date2[0]+$annee));
						return $this->date;
					}
				}else{
					return "Erreur: Format voulu invalide. (arg 4)";
				}
			}else{
				return "Erreur: Format fournit invalide. (arg 3)";
			}
		}else{
			if($format_fournit == 'en'){
				if($format_voulu == 'en'){
				
				}else if($format_voulu == 'fr'){
				
				}else{
					return "Erreur: Format voulu invalide. (arg 4)";
				}
			}else if($format_fournit == 'fr'){
				if($format_voulu == 'en'){
					$tab_date = explode('-', $this->date);# On récupère la date dans un tableau de trois cases ([d][m][Y])
					if($return_date_formatee){
						# On retourne une date formatée.
						$this->date = date("Ymd", mktime(0, 0, 0, $tab_date[0]+$mois, $tab_date[1]+$jour,  $tab_date[2]+$annee));
						return $this->date;
					}else{
						# on retourne une date affichable.
						$this->date = date("Y-m-d", mktime(0, 0, 0, $tab_date[0]+$mois, $tab_date[1]+$jour,  $tab_date[2]+$annee));
						return $this->date;
					}
					
				}else if($format_voulu == 'fr'){
					
				}else{
					return "Erreur: Format voulu invalide. (arg 4)";
				}
			}else{
				return "Erreur: Format fournit invalide. (arg 3)";
			}
		}
		
	}
	
	/**
	* @author  Ambroise Dhenain
	* @since [Création] 07 octobre 2011
	* @since [Modification] 07 octobre 2011
	* 
	* @desc Formatte un datetime de manière à ce qu'il soit affichable.
	*		Peut supprimer les heures, minutes, secondes.
	*
	* @return Une date ou datetime.
	* @return false si la date n'est pas valide
	*
	*/
	public function fx_formatter_heure($date, $datetime = true, $format_fournit='en', $supprimer_Hms = false, $ajouter_A = true, $return_format_Hhm = true){
		# On remplace toutes les "/" en "-".
		$this->date = str_replace("/", "-", $date);
		
		if(!self::fx_verif_date($this->date, $format_fournit, $datetime)){
			return false;
		}
		
		if($datetime){
			if($supprimer_Hms){
				$this->tab_date = split(' ', $this->date);
				$this->date = $this->tab_date[0];
				
				return $this->date;
			}else if($ajouter_A && $return_format_Hhm){
				$this->date = str_replace(' ', ' à ', $this->date);
				$this->date = substr(str_replace(':', 'h', $this->date), 0, -3);
				
				return $this->date;
			}else if($return_format_Hhm){
				$this->date = substr(str_replace(':', 'h', $this->date), 0, -3);
				
				return $this->date;
			}else if($ajouter_A){
				$this->date = str_replace(' ', ' à ', $this->date);
				
				return $this->date;
			}
		}else{
		
		}
	}
	
}
?><?php
class CL_page{

	private $tab_page_absolu;
	private $tab_page_relatif;
	private $tab_config;
	private $tab_image_relatif;
	private $dossier_image;
	
	# Fonction qui attribue à chaque valeur l'id de la page, il suffit de rajouter un couple clé=>valeur pour référencer une nouvelle page.
	public function __construct(){
	
	# Modifier cette valeur pour modifier tout le début des liens absolus du site:
	$debut = "http://liveanim.com/";
	
	# Modifier cette valeur pour modifier le chemin d'accès depuis le dossier www aux images.
	$this->dossier_image = "images/";
	
	$this->tab_page_relatif = array(# Les liens ci-dessous sont relatifs par rapport à l'URL en cours.
						
						# Pages externes au site:
						
						
						# Pages du site:
						'accueil' =>'http://liveanim.com/',
						'inscription' =>'inscription.php',
						'activation' =>'activation.php',
						'recuperation_mdp' =>'recuperation_mdp.php',
						'gestion_compte' =>'gestion_compte.php',
						'administration' =>'administration.php',
						'changer_rang' =>'changer_rang.php',
						'activer_comptes' =>'activer_comptes.php',
						'bannir_membre' =>'bannir_membre.php',
						'liste_membre' =>'liste_membre.php',
						'comptes_supprimes' =>'comptes_supprimes.php',
						'modifier_fiche_membre' =>'modifier_fiche_membre.php',
						'ajouter_pack' =>'ajouter_pack.php',
						'liste_packs' =>'liste_packs.php',
						'modifier_fiche_pack' =>'modifier_fiche_pack.php',
						'modifier_fiche_perso' =>'modifier_fiche_perso.php',
						'modifier_mdp' =>'modifier_mdp.php',
						'supprimer_compte' =>'supprimer_compte.php',
						'acheter_pack' =>'acheter_pack.php',
						'historique_achat_pack' =>'historique_achat_pack.php',
						'IPN' =>'script_achat_pack_ipn.php',
						'achat_pack_ok' =>'achat_pack_ok.php',
						'achat_pack_annule' =>'achat_pack_annule.php',
						'achat_pack_error' =>'achat_pack_error.php',
						'filleuls' =>'filleuls.php',
						'lien_parrainage' =>'lien_parrainage.php',
						'creer_annonce' =>'creer_annonce.php',
						'liste_annonces_en_attente' =>'liste_annonces_en_attente.php',
						'modifier_fiche_annonce_by_admin' =>'modifier_fiche_annonce_by_admin.php',
						'modifier_fiche_annonce' =>'modifier_fiche_annonce.php',
						'historique_annonce' =>'historique_annonce.php',
						'liste_annonce' =>'liste_annonce.php',
						'annonce' =>'annonce.php',
						'creer_contrat' =>'creer_contrat.php',
						'personne' =>'personne.php',
						'messagerie' =>'messagerie.php',
						'message' =>'message.php',
						'supprimer_message' =>'supprimer_message.php',
						'historique_contrat' =>'historique_contrat.php',
						'contrat' =>'contrat.php',
						
						
						
						
						# Dossier du site:
						'paiement_bancaire_pack' =>'bancaire/achats_packs/',

						
						# Les scripts php sont précisés par le .php à la fin:
						'deconnexion.php' =>'script_deconnexion.php',
						
						# Les scripts js sont précisés par le .js à la fin, ils se trouvent dans le dossier /js:
						'acheter_pack.js' =>'js/acheter_pack.js',
						'activer_pack.js' =>'js/activer_pack.js',
						
						# Ressources:
						'cgu' =>'ressources/cgu.pdf',					
						
						);
						
	$this->tab_page_absolu = array(# Les liens ci-dessous sont absolus.
												
						# Pages du site:
						'accueil' =>$debut,
						'inscription' =>$debut.$this->tab_page_relatif['inscription'],
						'activation' =>$debut.$this->tab_page_relatif['activation'],
						'recuperation_mdp' =>$debut.$this->tab_page_relatif['recuperation_mdp'],
						'gestion_compte' =>$debut.$this->tab_page_relatif['gestion_compte'],
						'administration' =>$debut.$this->tab_page_relatif['administration'],
						'changer_rang' =>$debut.$this->tab_page_relatif['changer_rang'],
						'activer_comptes' =>$debut.$this->tab_page_relatif['activer_comptes'],
						'bannir_membre' =>$debut.$this->tab_page_relatif['bannir_membre'],
						'liste_membre' =>$debut.$this->tab_page_relatif['liste_membre'],
						'comptes_supprimes' =>$debut.$this->tab_page_relatif['comptes_supprimes'],
						'modifier_fiche_membre' =>$debut.$this->tab_page_relatif['modifier_fiche_membre'],
						'ajouter_pack' =>$debut.$this->tab_page_relatif['ajouter_pack'],
						'liste_packs' =>$debut.$this->tab_page_relatif['liste_packs'],
						'modifier_fiche_pack' =>$debut.$this->tab_page_relatif['modifier_fiche_pack'],
						'modifier_fiche_perso' =>$debut.$this->tab_page_relatif['modifier_fiche_perso'],
						'modifier_mdp' =>$debut.$this->tab_page_relatif['modifier_mdp'],
						'supprimer_compte' =>$debut.$this->tab_page_relatif['supprimer_compte'],
						'acheter_pack' =>$debut.$this->tab_page_relatif['acheter_pack'],
						'historique_achat_pack' =>$debut.$this->tab_page_relatif['historique_achat_pack'],
						'IPN' =>$debut.$this->tab_page_relatif['IPN'],
						'achat_pack_ok' =>$debut.$this->tab_page_relatif['achat_pack_ok'],
						'achat_pack_annule' =>$debut.$this->tab_page_relatif['achat_pack_annule'],
						'achat_pack_error' =>$debut.$this->tab_page_relatif['achat_pack_error'],
						'filleuls' =>$debut.$this->tab_page_relatif['filleuls'],
						'lien_parrainage' =>$debut.$this->tab_page_relatif['lien_parrainage'],
						'creer_annonce' =>$debut.$this->tab_page_relatif['creer_annonce'],
						'liste_annonces_en_attente' =>$debut.$this->tab_page_relatif['liste_annonces_en_attente'],
						'modifier_fiche_annonce_by_admin' =>$debut.$this->tab_page_relatif['modifier_fiche_annonce_by_admin'],
						'modifier_fiche_annonce' =>$debut.$this->tab_page_relatif['modifier_fiche_annonce'],
						'historique_annonce' =>$debut.$this->tab_page_relatif['historique_annonce'],
						'liste_annonce' =>$debut.$this->tab_page_relatif['liste_annonce'],
						'annonce' =>$debut.$this->tab_page_relatif['annonce'],
						'creer_contrat' =>$debut.$this->tab_page_relatif['creer_contrat'],
						'personne' =>$debut.$this->tab_page_relatif['personne'],
						'messagerie' =>$debut.$this->tab_page_relatif['messagerie'],
						'message' =>$debut.$this->tab_page_relatif['message'],
						'supprimer_message' =>$debut.$this->tab_page_relatif['supprimer_message'],
						'historique_contrat' =>$debut.$this->tab_page_relatif['historique_contrat'],
						'contrat' =>$debut.$this->tab_page_relatif['contrat'],
						
						
						
						
						# Dossier du site:
						'paiement_bancaire_pack' =>$debut.$this->tab_page_relatif['paiement_bancaire_pack'],
						
						# Les scripts js sont précisés par le .js à la fin:
						'acheter_pack.js' =>$debut.$this->tab_page_relatif['acheter_pack.js'],
						'activer_pack.js' =>$debut.$this->tab_page_relatif['activer_pack.js'],
						
						# Ressources:
						'cgu' =>$debut.$this->tab_page_relatif['cgu'],
						
						);
	
	
	$this->tab_config = array(
						'compte_credite' =>'liveanim@gmail.com',
								
						);
	
	
	
	
	$this->tab_image_relatif = array(
						# Images en vrac
						'casque_blanc'  =>'1page-img4.jpg',
						'casque_argent'  =>'1page-img2.jpg',
						'casque_or'  =>'1page-img1.jpg',
						'avat_test1'  =>'1page-img5.jpg',
						'news1'  =>'1page-img12.jpg',
						'disco1'  =>'2page-img3.jpg',
						'annonces_gold'  =>'annonces_gold.gif',
						'btn_droite'  =>'arrow1.gif',
						'btn_droite_petit'  =>'arrow2.gif',
						'background'  =>'background.jpg',
						'cadre_connexion1'  =>'Bloc-Connexion.jpg',
						'cadre_connexion2'  =>'Cadre-connexion.png',# Utilisé
						'disco_ball'  =>'disco ball.png',
						'favicon'  =>'favicon.gif',
						'fond_inscription'  =>'fond_inscription.jpg',
						'fond_inscription_bas'  =>'fond_inscription_bas.jpg',
						'fond_inscription_haut'  =>'fond_inscription_haut.jpg',
						'fond_inscription_milieu'  =>'fond_inscription_milieu.png',
						'twitter'  =>'icon1.gif',
						'icon2'  =>'icon2.gif',
						'facebook'  =>'icon3.gif',
						'icon4'  =>'icon4.gif',
						'rss'  =>'icon5.gif',
						'fr'  =>'icon6.gif',
						'etoile_menu'  =>'imgmenu.png',
						'inscription_gratuite'  =>'inscription.png',
						'liste1'  =>'liste1.png',
						'logo_liveanim'  =>'logo.png',
						'micro_renverse'  =>'micro.gif',
						'mon_compte'  =>'mon_compte.gif',
						'suivant'  =>'next.gif',
						'non1'  =>'non.gif',
						'ok1'  =>'ok.gif',
						
						'pdf'  =>'pdf.png',
						'pdf_non'  =>'pdf_non.png',
						'precedent'  =>'prev.gif',
						'previsualiser'  =>'previsualiser.jpg',
						'supprimer_personne_gros'  =>'supprimer_personne.png',
						'admin_rond'  =>'photo_administration2.gif',
						'supprimer_personne_petit'  =>'supprimer_personne_petit.png',
						'valider'  =>'valider.png',
						'valider2'  =>'valider2.png',
						'valider3'  =>'valider3.jpg',
						'voir' =>'voir.jpg',
						'voir_non' =>'voir_non.png',
						'plus' =>'Plus.png',
						'moins' =>'Moins.png',
						'paypal_boutons' =>'paypal_boutons.png',
						
						# Sliders
						'cle_administration'  =>'photo_administration1.gif',
						'administration'  =>'slide_administration.gif',
						'slide_test'  =>'slide1.jpg',
						
						# Parrainage
						'special_parrainage'  =>'parrainage1.png',
						);
	
	}
	
	
	# Accesseur en lecture de $tab_page.
    public function getPage($page, $type_lien = "relatif") {
        if($type_lien == "absolu"){
			return $this->tab_page_absolu[$page];
		}else if($type_lien == "relatif"){
			return $this->tab_page_relatif[$page];
		}
	}
	
	# Accesseur en lecture de $tab_config
	public function getConfig($nom_array) {
		return $this->tab_config[$nom_array];
	}
	
	# Accesseur en lecture de $tab_image.
    public function getImage($image) {
		return $this->dossier_image.$this->tab_image_relatif[$image];
	}
	
	# Accesseur en lecture de $tab_image.
    public function getDossierImage() {
		return $this->dossier_image;
	}

}
?><?php
/*
* @Author: Ambroise Dhenain
* @Date: 15 septembre 2011
* @Description: Classe d'upload qui permet d'uploader n'importe quel type de fichier en précisant toutes ses caractéristiques.
* @Exemple d'utilisation: 

// On crée l'objet, on lui passe ses paramètres, ils ne seront pas forcément tous modifiés.
$oCL_upload = new CL_upload($_FILES['fichier_uploade'], "images/uploads/membres", array("png", "gif", "jpg", "jpeg"), 0777, array("image/jpeg", "image/jpeg", "image/png", "image/gif", "image/pjpg", "image/pjpeg"), 200, 200, 30000);
	$new_filename = $ID_PERSONNE."_".date("Y-m-d_H-i-s");
	$ext = explode('.', $_FILES['fichier_uploade']['name']);
	$extension = $ext[count($ext)-1];
	
	// On upload le fichier: $verif_mime = true, $verif_largeur = false, $verif_longueur = false, $verif_size = true (valeurs de base de la fonction).
	$tab_message = $oCL_upload->fx_upload($_FILES['fichier_uploade']['name'], $new_filename);
	
	if($tab_message['reussite'] == true){
		$URL_PHOTO_PRINCIPALE =  $oCL_page->getPage('accueil', 'absolu').$tab_message['resultat'];
	}else{
		$_SESSION['modification_fiche_membre']['message'].= $tab_message['resultat'];
		$URL_PHOTO_PRINCIPALE = "";
		$echec_upload = true;
		# On empèche pas la modification de la fiche.
	}
	
* 
*/

class CL_upload {
    
	private $file;
    private $path;
    private $extensions = array();
    private $chmod = 0777;
	private $mime = array();
	private $hauteur = 200;
	private $largeur = 200;
	private $size = 2097152;
    /* La taille correspond au nombre d'OCTETS maximums du fichier. Ici 2Mo soit le maximal autorisé par php de base. 
	* (Modifiable ! --> http://forum.ovh.com/archive/index.php/t-7622.html  (Serveur Dédié nécessaire !) ) */
	
	private $errorsMessage = "";# Contient tous les messages d'erreurs.
	
    private $messages = array(
                            "success"   => "<span class='alert'>Téléchargement effectué avec succès.</span><br />",
                            "extension" => "<span class='alert'>Extension non autorisé.</span><br />",
                            "echec_upload"    => "<span class='alert'>Une erreur est survenue.</span><br />",
							"nom"		=> "<span class='alert'>Le nom du fichier est invalide.</span><br />",
							"mime"		=> "<span class='alert'>Le type mime du fichier est invalide.</span><br />",
							"largeur"	=> "<span class='alert'>La largeur du fichier est trop grande.</span><br />",
							"longueur"	=> "<span class='alert'>La longueur du fichier est trop grande.</span><br />",
							"size"		=> "<span class='alert'>La taille (Ko) du fichier est trop grande.</span><br />",
                        );    
    
    /**
    * Constructeur
    * @param string $file : Fichier à uploader
    * @param string $path : Chemin du fichier à uploader
    * @param array $extensions : Extensions autorisé pour l'upload
    * @param int $chmod : Droits du fichier uploadé
    * @param array $mime : Les types mimes autorisés
    * @param int $hauteur : Hauteur maximale du fichier (pixels)
	* @param int $largeur : Largeur maximale du fichier (pixels)
	* @param int $size : Taille maximale du fichier (octets)
    */
    public function __construct($file = null, $path = "images/uploads/", $extensions = array("jpg", "jpeg", "png", "gif"), $chmod = 0777, $mime = array("image/jpeg", "image/jpeg", "image/png", "image/gif", "image/pjpg", "image/pjpeg"), $hauteur = 200, $largeur = 200, $size = 2097152) {
        $this->file = $file;
        $this->path = $path;
        $this->extensions = $extensions;
        $this->chmod = $chmod;
		$this->mime = $mime;
		$this->hauteur = $hauteur;
		$this->largeur = $largeur;
		$this->size = $size;
		$this->errorsMessage = "";
    }    
    
    /**
    * Retourne le fichier uploadé
    * @return string
    */
    public function getFile() {
        return $this->file;
    }

    /**
    * Retourne le chemin d'upload
    * @return string
    */
    public function getPath() {
        return $this->path;
    }

    /**
    * Retourne les extensions autorisé sous forme de tableau
    * @return array
    */
    public function getExtensions() {
        return $this->extensions;
    }

    /**
    * Retourne le chmod du répertoire d'upload
    * @return int
    */
    public function getChmod() {
        return $this->chmod;
    }

    /**
    * Retourne la valeur d'une clé de l'attribut $messages
    * @return string
    */
    public function getMessage($name) {
        $keys = array_keys($this->messages);
        if(in_array($name, $keys)){
            return $this->messages[$name];
		}
        else
            return "<strong>" . $name . "</strong> n'est pas pris en charge";
    }

    /**
    * Définit le fichier à uploader
    * @param string $file : Fichier à uploader
    * @return void
    */
    public function setFile($file) {
        $this->file = $file;
    }
    
    /**
    * Définit le chemin de l'upload
    * @param string $path : Chemin du fichier à uploader
    * @return void
    */
    public function setPath($path) {
        $this->path = $path;
    }
    
    /**
    * Définit les extensions autorisé lors de l'upload
    * @param array $extensions : Extensions autorisé pour l'upload
    * @return void
    */
    public function setExtensions($extensions = array()){
        $this->extensions = $extensions;
    }

    /**
    * Définit le chmod du répertoire d'upload
    * @param int $chmod : Chmod du répertoire
    * @return void
    */
    public function setChmod($chmod) {
        $this->chmod = $chmod;
    }
    
    /**
    * Définit un message personnalisé sous forme de tableau associatif
    * @param array $message : Message personnalisé (array("success" => "New message"))
    * @return void
    */
    public function setMessage($message = array()){
        foreach($message as $key => $value){
            $this->messages[$key] = $value;
        }
    }
	
	/**
    * Retourne la hauteur du fichier
    * @return int
    */
    public function getHauteur() {
        return $this->Hauteur;
    }

    /**
    * Définit la hauteur maximale du fichier (en pixels).
	* @param int $hauteur : La hauteur du fichier, en pixels.
    * @return void
    */
    public function setHauteur($hauteur = 200) {
        $this->hauteur = $hauteur;
    } 
	
	/**
    * Retourne la largeur du fichier
    * @return int
    */
    public function getLargeur() {
        return $this->largeur;
    }

    /**
    * Définit la largeur maximale du fichier (en pixels).
	* @param int $largeur : La largeur du fichier, en pixels.
    * @return void
    */
    public function setLargeur($largeur = 200) {
        $this->largeur = $largeur;
    } 
    
	 /**
    * Retourne le type mime du fichier
    * @return string
    */
    public function getMime() {
        return $this->mime;
    }

    /**
    * Définit les types mimes autorisés
	* @param array $mime : Tableau contenant tous les types mime autorisés.
    * @return void
    */
    public function setMime($mime = array()) {
        $this->mime = $mime;
    } 
	
	/**
    * Retourne la taille en octets du fichier
    * @return string
    */
    public function getSize() {
        return $this->size;
    }

    /**
    * Définit la taille maximale (en octet) du fichier
	* @param int $size : La taille en octets. 2Mo si non précisé.
    * @return void
    */
    public function setSize($size = 2097152) {
        $this->size = $size;
    }
	
    /**
    * Upload le fichier
	* @param string $filename : Le nom du fichier.
	* @param string $new_filename : Le nouveau nom du fichier si différent de "0".
	* @param bool $verif_mime : Indique si le type mime doit être vérifiée. Vrai par défaut.
	* @param bool $verif_largeur : Indique si la largeur doit être vérifiée. Faux par défaut.
	* @param bool $verif_longueur : Indique si la longueur doit être vérifiée. Faux par défaut.
	* @param bool $verif_size : Indique si la taille doit être vérifiée. Vrai par défaut.
    * @return string or array
    */
    public function fx_upload($filename, $new_filename = "0", $verif_mime = true, $verif_largeur = false, $verif_longueur = false, $verif_size = true){
		$error = 0;# Un type INT permet d'incrémenter à chaque erreur sans ce soucier de la valeur précédente. On peut donc connaître le nombre d'erreurs totales.
        $this->errorsMessage = "";# On réinitialise les messages d'erreurs entre deux appels de fx_upload.
        
		# On récupère l'extension du fichier en découpant le nom à chaque "." et en sélectionnant la dernière partie.
        $ext = explode('.', $filename);
        $extension = $ext[count($ext)-1];

		# On récupère les tailles du fichier. /!\ On regarde par rapport à l'emplacement temporaire du fichier.
		$tailles = getimagesize($this->file['tmp_name']);
		
		if($verif_mime){
			# On récupère le type MIME du fichier.
			$mime = $tailles['mime'];
		}
		
		if($verif_size){
			# On récupère la taille en BYTES ~ octets du fichier.
			$size = $this->file['size'];
		}
		
		# Si on a fournit un nouveau nom alors on modifie l'ancien.
		if($new_filename != "0"){
			$filename = $new_filename.".".$extension; # On oublie pas de rajouter l'extension à la fin.
		}else{
			$filename.=".".$extension; # On rajoute l'extention.
		}
		
		
		// ------------------------------------------ On commence les vérifications après avoir récupéré toutes les données.
		
		# On vérifie le nom du fichier.
        if($filename == ''){
			$error++; # Le fichier ne possède pas de nom.
			$this->errorsMessage.= $this->getMessage('nom');
		}
	
		# On vérifie l'extension du fichier.
		if(!in_array($extension, $this->extensions)) {
			$this->errorsMessage.= $this->getMessage('extension');
			$error++;
		}
	
		# On vérifie le type mime du fichier.
		if(!in_array($mime, $this->mime)) {
			$this->errorsMessage.= $this->getMessage('mime');
			$error++;
		}
		
		# Si on a décidé de vérifier la largeur.
		if($verif_largeur){
			if($tailles[0] > $this->largeur){
				# La largeur de l'image est supérieur à celle que l'on désire.
				$this->errorsMessage.= $this->getMessage('largeur');
				$error++;
			}
		}
	
		# Si on a décidé de vérifier la longueur.
		if($verif_longueur){
			if($tailles[1] > $this->longueur){
				# La longueur de l'image est supérieur à celle que l'on désire.
				$this->errorsMessage.= $this->getMessage('longueur');
				$error++;
			}
		}
	
		if($verif_size){
			if($size > $this->size){
				# Si la taille du fichier est supérieure à la taille que l'on désire.
				$this->errorsMessage.= $this->getMessage('size');
				$error++;
			}
		}

		# S'il n'y a aucune erreur
        if($error == 0) {
			# On regarde si le chemin choisi est un dossier.
            if(!is_dir($this->path)) {
				# Si ce n'est pas un dossier alors on le crée et on lui attribue le chmod. (Chmod = droits d'accès au niveau du serveur pour les dossiers/fichiers).
                mkdir($this->path);
                chmod($this->path, $this->chmod);
            }
            
			# Si le fichier qu'on veut mettre dans le dossier existe déjà on lui attribue une valeur devant son nom.
            if(file_exists($this->path .'/'. $filename)){
                $filename = time() . $filename;
			}
            
			# On tente d'uploader le fichier.
            $upload = move_uploaded_file($this->file['tmp_name'], $this->path .'/'. $filename);
                if($upload){
					$chemin = $this->path .'/'. $filename;
					
                    return array("reussite" => true, "resultat" => $this->path .'/'. $filename);# On retourne true pour dire que ça a fonctionné.
				}
                else{
                    return array("reussite" => false, "resultat" => $this->getMessage('echec_upload'));
				}
        }else {
			# S'il y a une/des erreur(s) alors on retourne le message d'erreur et un booleen false
            return array("reussite" => false, "resultat" => $this->errorsMessage);
        }
    }
    
    /**
    * Permet d'avoir un aperçu rapide de l'objet instancié.
    * @return string
    */
    public function getUpload() {
        echo '<pre>';
        print_r($this);
        echo '</pre>';
    }
	
	/**
    * Permet d'avoir un aperçu rapide de l'objet instancié et de toutes ses méthodes.
    * @return string
    */
	public function __toString(){
		return '<pre>' . print_r($this, true) . 'Méthodes: ' . print_r(get_class_methods(__CLASS__), true) . '<pre>';
	}

}
?><?php
class CL_video{

	private $url;
	private $url_hebergeur;
	private $url_decoupee;
	private $delimiter;
	private $tag_video;
	private $regex;
	private $youtube;
	private $youtu;
	private $youtubewatch;
	
	public function __construct($delimiter = "/", $regex = ""){
		$this->url = "";
		$this->url_hebergeur = "";
		$this->url_decoupee = "";
		$this->delimiter = $delimiter;
		$this->tag_video = "";
		$this->regex = $regex;
		$this->youtube = "http://www.youtube.com/v/";
		$this->youtu = "http://youtu.be/";
		$this->youtubewatch = "http://www.youtube.com/watch?v=";
	}

	public function fx_recuperer_tag($url){
		$this->url = $url;
		
		$this->url_decoupee = explode($this->delimiter, $this->url);
		
		# On compte le nombre d'éléments dans le tableau.
		$nb_elements = count($this->url_decoupee);
	
		# Vérification youtube
		if(substr_count($this->url, $this->youtube) > 0){
			return $this->url;
			
		}else if(substr_count($this->url, $this->youtu)  > 0){
			# On retourne la chaine de base + le dernier élément du tableau.
			return $this->youtube.$this->url_decoupee[$nb_elements-1];
			
		}else if(substr_count($this->url, $this->youtubewatch)  > 0){
			# On retourne la chaine mais en modifiant le début.
			return str_replace("http://www.youtube.com/watch?v=", "http://www.youtube.com/v/", $this->url);
		
		}else{
			//return $this->youtube.$this->url_decoupee[$nb_elements-1];
		}
	}

}
?><?php
class MPG_annonce{

	private $ID_ANNONCE;
	private $ID_PERSONNE;
	private $ID_DEPARTEMENT;
	private $TITRE;
	private $TYPE_ANNONCE;
	private $DATE_ANNONCE;
	private $DATE_DEBUT;
	private $DATE_FIN;
	private $ARTISTES_RECHERCHES;
	private $BUDGET;
	private $NB_CONVIVES;
	private $DESCRIPTION;
	private $ADRESSE;
	private $CP;
	private $VILLE;
	private $GOLDLIVE;
	private $VISIBLE;
	private $STATUT;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_ANNONCE = "";
	$this->ID_PERSONNE = "";
	$this->ID_DEPARTEMENT = "";
	$this->TITRE = "";
	$this->TYPE_ANNONCE = "";
	$this->DATE_ANNONCE = "";
	$this->DATE_DEBUT = "";
	$this->DATE_FIN = "";
	$this->ARTISTES_RECHERCHES = "";
	$this->BUDGET = "";
	$this->NB_CONVIVES = "";
	$this->DESCRIPTION = "";
	$this->ADRESSE = "";
	$this->CP = "";
	$this->VILLE = "";
	$this->GOLDLIVE = "";
	$this->VISIBLE = "";
	$this->STATUT = "";
	}
	
	
	public function SELECT_COUNT_ID_ANNONCE_by_VISIBLE($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
	
		$this->sql = "SELECT COUNT(ID_ANNONCE) as nb_annonce FROM annonce WHERE VISIBLE=:VISIBLE;";
		$params = array(    
					":VISIBLE"=>$this->VISIBLE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_all_by_ID_ANNONCE($oMSG){
		$this->ID_ANNONCE = $oMSG->getData("ID_ANNONCE");
	
		$this->sql = "SELECT ID_ANNONCE, ID_PERSONNE, ID_DEPARTEMENT, TITRE, TYPE_ANNONCE, DATE_ANNONCE, DATE_DEBUT, DATE_FIN, ARTISTES_RECHERCHES, ".
		"BUDGET, NB_CONVIVES, DESCRIPTION, ADRESSE, CP, VILLE, GOLDLIVE, VISIBLE, STATUT FROM annonce WHERE ID_ANNONCE=:ID_ANNONCE;";
		$params = array(    
					":ID_ANNONCE"=>$this->ID_ANNONCE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_ANNONCE_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT COUNT(ID_ANNONCE) as nb_annonce FROM annonce WHERE ID_PERSONNE=:ID_PERSONNE;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_toutes_annonces_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$debut_affichage = $oMSG->getData("debut_affichage");
		$nb_result_affiches = $oMSG->getData("nb_result_affiches");
	
		$this->sql = "SELECT ID_ANNONCE, TITRE, TYPE_ANNONCE, DATE_ANNONCE, DATE_DEBUT, DATE_FIN, ".
		"GOLDLIVE, VISIBLE, STATUT FROM annonce WHERE ID_PERSONNE=:ID_PERSONNE ".
		"ORDER BY DATE_ANNONCE DESC LIMIT $debut_affichage, $nb_result_affiches;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_ID_ANNONCE_futures_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
	
		$this->sql = "SELECT COUNT(ID_ANNONCE) as nb_annonce FROM annonce WHERE ID_PERSONNE=:ID_PERSONNE AND DATE_DEBUT > NOW();";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_annonces_futures_by_ID_PERSONNE($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$debut_affichage = $oMSG->getData("debut_affichage");
		$nb_result_affiches = $oMSG->getData("nb_result_affiches");
		
		$this->sql = "SELECT ID_ANNONCE, TITRE, TYPE_ANNONCE, DATE_ANNONCE, DATE_DEBUT, DATE_FIN, ".
		"GOLDLIVE, VISIBLE, STATUT FROM annonce WHERE ID_PERSONNE=:ID_PERSONNE AND DATE_DEBUT > NOW() ".
		"ORDER BY DATE_ANNONCE DESC LIMIT $debut_affichage, $nb_result_affiches;";
		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_COUNT_annonces_par_criteres($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->STATUT = $oMSG->getData("STATUT");
		$criteres = $oMSG->getData("criteres");
		
		$this->sql = "SELECT COUNT(ID_ANNONCE) as nb_annonce FROM annonce WHERE VISIBLE=:VISIBLE AND STATUT=:STATUT $criteres";
		$params = array(    
					":VISIBLE"=>$this->VISIBLE,
					":STATUT"=>$this->STATUT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	public function SELECT_annonce_valide_by_ID_ANNONCE($oMSG){
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->STATUT = $oMSG->getData("STATUT");
		$this->ID_ANNONCE = $oMSG->getData("ID_ANNONCE");		

		$this->sql = "SELECT ID_ANNONCE, ID_PERSONNE, TITRE, TYPE_ANNONCE, DATE_DEBUT, DATE_FIN, BUDGET FROM annonce WHERE VISIBLE=:VISIBLE AND STATUT=:STATUT AND ID_ANNONCE=:ID_ANNONCE AND DATE_DEBUT > NOW();";
		$params = array(    
					":VISIBLE"=>$this->VISIBLE,
					":STATUT"=>$this->STATUT,
					":ID_ANNONCE"=>$this->ID_ANNONCE,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ----------------------------------------------------- INSERT ---------------------------------------------------
	
	public function INSERT($oMSG){
		$this->ID_PERSONNE = $oMSG->getData("ID_PERSONNE");
		$this->ID_DEPARTEMENT = $oMSG->getData("ID_DEPARTEMENT");
		$this->TITRE = $oMSG->getData("TITRE");
		$this->TYPE_ANNONCE = $oMSG->getData("TYPE_ANNONCE");
		$this->DATE_ANNONCE = $oMSG->getData("DATE_ANNONCE");
		$this->DATE_DEBUT = $oMSG->getData("DATE_DEBUT");
		$this->DATE_FIN = $oMSG->getData("DATE_FIN");
		$this->ARTISTES_RECHERCHES = $oMSG->getData("ARTISTES_RECHERCHES");
		$this->BUDGET = $oMSG->getData("BUDGET");
		$this->NB_CONVIVES = $oMSG->getData("NB_CONVIVES");
		$this->DESCRIPTION = $oMSG->getData("DESCRIPTION");
		$this->ADRESSE = $oMSG->getData("ADRESSE");
		$this->CP = $oMSG->getData("CP");
		$this->VILLE = $oMSG->getData("VILLE");
		$this->GOLDLIVE = $oMSG->getData("GOLDLIVE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->STATUT = $oMSG->getData("STATUT");
	
		$this->sql = "INSERT INTO annonce (ID_PERSONNE, ID_DEPARTEMENT, TITRE, TYPE_ANNONCE, DATE_ANNONCE, DATE_DEBUT, DATE_FIN, ARTISTES_RECHERCHES, BUDGET, ".
		"NB_CONVIVES, DESCRIPTION, ADRESSE, CP, VILLE, GOLDLIVE, VISIBLE, STATUT) VALUES(:ID_PERSONNE, :ID_DEPARTEMENT, :TITRE, :TYPE_ANNONCE, :DATE_ANNONCE, :DATE_DEBUT, ".
		":DATE_FIN, :ARTISTES_RECHERCHES, :BUDGET, :NB_CONVIVES, :DESCRIPTION, :ADRESSE, :CP, :VILLE, :GOLDLIVE, :VISIBLE, :STATUT);";

		$params = array(    
					":ID_PERSONNE"=>$this->ID_PERSONNE,
					":ID_DEPARTEMENT"=>$this->ID_DEPARTEMENT,
					":TITRE"=>$this->TITRE,
					":TYPE_ANNONCE"=>$this->TYPE_ANNONCE,
					":DATE_ANNONCE"=>$this->DATE_ANNONCE,
					":DATE_DEBUT"=>$this->DATE_DEBUT,
					":DATE_FIN"=>$this->DATE_FIN,
					":ARTISTES_RECHERCHES"=>$this->ARTISTES_RECHERCHES,
					":BUDGET"=>$this->BUDGET,
					":NB_CONVIVES"=>$this->NB_CONVIVES,
					":DESCRIPTION"=>$this->DESCRIPTION,
					":ADRESSE"=>$this->ADRESSE,
					":CP"=>$this->CP,
					":VILLE"=>$this->VILLE,
					":GOLDLIVE"=>$this->GOLDLIVE,
					":VISIBLE"=>$this->VISIBLE,
					":STATUT"=>$this->STATUT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	// ------------------------------------- UPDATE ---------------------------------
	
	public function UPDATE_by_ID_ANNONCE($oMSG){
		$this->ID_ANNONCE = $oMSG->getData("ID_ANNONCE");
		$this->ID_DEPARTEMENT = $oMSG->getData("ID_DEPARTEMENT");
		$this->TITRE = $oMSG->getData("TITRE");
		$this->TYPE_ANNONCE = $oMSG->getData("TYPE_ANNONCE");
		$this->DATE_DEBUT = $oMSG->getData("DATE_DEBUT");
		$this->DATE_FIN = $oMSG->getData("DATE_FIN");
		$this->ARTISTES_RECHERCHES = $oMSG->getData("ARTISTES_RECHERCHES");
		$this->BUDGET = $oMSG->getData("BUDGET");
		$this->NB_CONVIVES = $oMSG->getData("NB_CONVIVES");
		$this->DESCRIPTION = $oMSG->getData("DESCRIPTION");
		$this->ADRESSE = $oMSG->getData("ADRESSE");
		$this->CP = $oMSG->getData("CP");
		$this->VILLE = $oMSG->getData("VILLE");
		$this->GOLDLIVE = $oMSG->getData("GOLDLIVE");
		$this->VISIBLE = $oMSG->getData("VISIBLE");
		$this->STATUT = $oMSG->getData("STATUT");
	
		$this->sql = "UPDATE annonce SET ID_DEPARTEMENT=:ID_DEPARTEMENT, TITRE=:TITRE, TYPE_ANNONCE=:TYPE_ANNONCE, DATE_DEBUT=:DATE_DEBUT, DATE_FIN=:DATE_FIN, ".
		"ARTISTES_RECHERCHES=:ARTISTES_RECHERCHES, BUDGET=:BUDGET, NB_CONVIVES=:NB_CONVIVES, DESCRIPTION=:DESCRIPTION, ADRESSE=:ADRESSE, CP=:CP, VILLE=:VILLE, ".
		"GOLDLIVE=:GOLDLIVE, VISIBLE=:VISIBLE, STATUT=:STATUT WHERE ID_ANNONCE=:ID_ANNONCE;";

		$params = array(    
					":ID_ANNONCE"=>$this->ID_ANNONCE,
					":ID_DEPARTEMENT"=>$this->ID_DEPARTEMENT,
					":TITRE"=>$this->TITRE,
					":TYPE_ANNONCE"=>$this->TYPE_ANNONCE,
					":DATE_DEBUT"=>$this->DATE_DEBUT,
					":DATE_FIN"=>$this->DATE_FIN,
					":ARTISTES_RECHERCHES"=>$this->ARTISTES_RECHERCHES,
					":BUDGET"=>$this->BUDGET,
					":NB_CONVIVES"=>$this->NB_CONVIVES,
					":DESCRIPTION"=>$this->DESCRIPTION,
					":ADRESSE"=>$this->ADRESSE,
					":CP"=>$this->CP,
					":VILLE"=>$this->VILLE,
					":GOLDLIVE"=>$this->GOLDLIVE,
					":VISIBLE"=>$this->VISIBLE,
					":STATUT"=>$this->STATUT,
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
}<?php
class MPG_contrat{

	private $ID_CONTRAT;
	private $ID_ANNONCE;
	private $DATE_CONTRAT;
	private $STATUT_CONTRAT;
	private $URL_CONTRAT_PDF;
	private $DATE_EVALUATION;
	private $DESCRIPTION;
	private $DATE_DEBUT;
	private $DATE_FIN;
	private $PRIX;
	private $GOLDLIVE;
	
	private $sql;
	
	public function __construct() {
	$this->sql = "";
	$this->ID_CONTRAT = "";
	$this->ID_ANNONCE = "";
	$this->DATE_CONTRAT = "";
	$this->STATUT_CONTRAT = "";
	$this->URL_CONTRAT_PDF = "";
	$this->DATE_EVALUATION = "";
	$this->DESCRIPTION = "";
	$this->DATE_DEBUT = "";
	$this->DATE_FIN = "";
	$this->PRIX = "";
	$this->GOLDLIVE = "";
	}
	
	
	public function SELECT_COUNT_ID_CONTRAT_by_ID_ANNONCE($oMSG){
		$this->ID_ANNONCE = $oMSG->getData('ID_ANNONCE');
	
		$this->sql = "SELECT COUNT(ID_CONTRAT) as nb_contrat FROM contrat WHERE ID_ANNONCE=:ID_ANNONCE;";
		
		$params = array(  
					':ID_ANNONCE' =>$this->ID_ANNONCE,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
	
	
	// --------------------------------------------- INSERT -----------------------------------------
	
	public function INSERT($oMSG){
		$this->ID_ANNONCE = $oMSG->getData('ID_ANNONCE');
		$this->DATE_CONTRAT = $oMSG->getData('DATE_CONTRAT');
		$this->DATE_DEBUT = $oMSG->getData('DATE_DEBUT');
		$this->DATE_FIN = $oMSG->getData('DATE_FIN');
		$this->PRIX = $oMSG->getData('PRIX');
		$this->DESCRIPTION = $oMSG->getData('DESCRIPTION');
		$this->STATUT_CONTRAT = $oMSG->getData('STATUT_CONTRAT');
	
		$this->sql = "INSERT INTO contrat (ID_ANNONCE, DATE_CONTRAT, STATUT_CONTRAT, DESCRIPTION, DATE_DEBUT, DATE_FIN, PRIX) ".
		"VALUES (:ID_ANNONCE, :DATE_CONTRAT, :STATUT_CONTRAT, :DESCRIPTION, :DATE_DEBUT, :DATE_FIN, :PRIX);";
		
		$params = array(  
					':ID_ANNONCE' =>$this->ID_ANNONCE,		
					':DATE_CONTRAT' =>$this->DATE_CONTRAT,		
					':DATE_DEBUT' =>$this->DATE_DEBUT,		
					':DATE_FIN' =>$this->DATE_FIN,		
					':PRIX' =>$this->PRIX,		
					':DESCRIPTION' =>$this->DESCRIPTION,		
					':STATUT_CONTRAT' =>$this->STATUT_CONTRAT,		
					);
		
		$oMSG->setData(0, $this->sql);
		$oMSG->setData(1, $params);
		
		return $oMSG;
	}
		
}