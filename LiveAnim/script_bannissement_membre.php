<?php
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
}