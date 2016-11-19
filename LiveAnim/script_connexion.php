<?php
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
		$_SESSION['connexion']['message'] = "<span class='alert'>Mot de passe incorrect.</span><br />";
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
			$_SESSION['pack']['CONTRATS_PDF'] = true;
			$_SESSION['pack']['PUBS'] = true;
			
		
		}else if($Personne[0]['TYPE_PERSONNE'] == "Admin"){
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
			$_SESSION['pack']['CONTRATS_PDF'] = true;
			$_SESSION['pack']['SUIVI'] = true;
			$_SESSION['pack']['PUBS'] = false;
			$_SESSION['pack']['date_fin_validite'] = "25-12-2100 00:00:00";
			$_SESSION['pack']['date_fin_validite_formatee'] = "25122100000000";
			
		}else{
			$_SESSION = array();# On écrase la session.
			$_SESSION['compte']['connecté'] = false;
			
			$_SESSION['connexion']['message'].= "<span class='alert'>Erreur. Veuillez contacter un administrateur via notre formulaire de contact.<br />Error: Type de personne non définit. Accès interdit.</span><br />";
		
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
		
		# On redirige.
		if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
			header('Location: '.$oCL_page->getPage('administration'));
		}else{
			header('Location: '.$oCL_page->getPage('gestion_compte'));
		}
	}

}else{
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
