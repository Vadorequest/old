<?php
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
			
			$oPCS_personne->fx_lier_IP_et_PERSONNE($oMSG);
			
			# Le pack dure un mois.
			$DATE_FIN = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m")+1, date("d"),  date("Y")));
					
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
			$oMSG->setData('NB_FICHES_VISITABLES', 5);
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
					"<span class='alert'>Malheureusement, notre service d'envoi d'email n'a pas fonctionné correctement <span class='petit'>[Serveur mail HS]</span> et aucun e-mail d'inscription ne vous a été envoyé.<br /><br />Cela ne change rien au compte qui a été correctement crée.<br /><br />Vous devez néanmois activer votre compte en cliquant sur ce lien: <br /><a href='".
					$oCL_page->getPage('inscription', 'absolu')."?email=".$email."&cle_activation=".$cle_activation."'>Activer mon compte</a> <br />Nous nous excusons pour le désagrément occasionné.</span><br /><br />";
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
			
			$oPCS_personne->fx_lier_IP_et_PERSONNE($oMSG);
			
			# Le pack dure un mois.
			$DATE_FIN = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m")+1, date("d"),  date("Y")));
						
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
			$oMSG->setData('NB_FICHES_VISITABLES', 10);
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
					"<span class='alert'>Malheureusement, notre service d'envoi d'email n'a pas fonctionné correctement <span class='petit'>[Serveur mail HS]</span> et aucun e-mail d'inscription ne vous a été envoyé.<br /><br />Cela ne change rien au compte qui a été correctement crée.<br /><br />".
					"L'activation de votre compte se fera manuellement par notre équipe de modération.<br /><br />Nous nous excusons pour le désagrément occasionné.</span><br /><br />";
			}
		}
		
		$Personne = null;# On détruit les informations.
		
		$_SESSION['compte']['première_visite'] = true;# On redirigera vers le bon contenu.
		$_SESSION['inscription'] = array();# On vide les valeurs rentrées par l'utilisateur.
		header('Location: '.$oCL_page->getPage('inscription'));
	}

}else{
	$_SESSION['inscription'] = array();
	$_SESSION['inscription']['message_affiche'] = false;
	$_SESSION['inscription']['message'] = "<span class='alert'>Vous devez soumettre le formulaire d'inscription via le bouton de validation qui se trouve en bas de cette page, merci.</span>";
	header('Location: '.$oCL_page->getPage('inscription', 'absolu'));
}

?>