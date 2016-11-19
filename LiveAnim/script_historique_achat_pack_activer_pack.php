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
?>