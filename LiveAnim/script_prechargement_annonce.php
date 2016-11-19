<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

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
			
			if($_SESSION['compte']['ID_PERSONNE'] == $annonce_courante[0]['ID_PERSONNE']){
				# On définit l'annonce comme étant l'annonce courante, sert si le mec qui regarde l'annonce est le même que celui qui l'a créée.
				$_SESSION['annonce']['annonce_courante'] = (int)$_GET['id_annonce'];
			}
			
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
			if(isset($_SESSION['compte']['TYPE_PERSONNE']) && $_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
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

						
					}
				}
			}# N'est pas prestataire. Pas d'action spécifique, les Organisateurs ne verront qu'une partie de l'annonce.
		
			# On recharge une seconde fois les annonces visitées depuis la BDD.
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			
			$nb_annonces_visitees = $oPCS_personne->fx_recuperer_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();

			# Si l'annonce en cours est dans le tableau alors on dit que l'annonce peut afficher les informations afin de permettre à l'utilisateur de contacter le client.
			if(in_array($ID_ANNONCE, $_SESSION['compte']['annonces_visitées']) || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				$afficher_infos_contact = true;
			}else{
				$afficher_infos_contact = false;
			}
		}else{
			$id_annonce_ok = 0;
			header('Location:'.$oCL_page->getPage('liste_annonce'));
		}
	}else{
		$id_annonce_ok = 0;
		header('Location:'.$oCL_page->getPage('liste_annonce'));
	}
}else{
	$id_annonce_ok = 0;
	header('Location:'.$oCL_page->getPage('liste_annonce'));
}
?>