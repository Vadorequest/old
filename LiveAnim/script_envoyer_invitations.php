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
	if(isset($_POST['form_envoyer_invitations_emails'])){
		# On initialise nos variables.
		$_SESSION['envoyer_invitations']['message'] = "";
		$_SESSION['envoyer_invitations']['message_affiche'] = false;
	
		$EMAILS = explode(';', $_POST['form_envoyer_invitations_emails']);
		
		# Si le membre n'a pas encore envoyé d'invitation de masse.
		if(!isset($_SESSION['envoyer_invitations']['invitations_envoyees'])){
			$_SESSION['envoyer_invitations']['invitations_envoyees'] = Array();
		}
		
		# On définit le message envoyé, format HTML.
		$MESSAGE = "<br />";
		$MESSAGE.= "Bonjour ! <br />";
		$MESSAGE.= "Je t'invite à t'inscrire gratuitement sur ce site: <a href='".$oCL_page->getPage('inscription')."?parrain=".$_SESSION['compte']['ID_PERSONNE']."#inscriptionh2'>LiveAnim</a> ! <br />";
		$MESSAGE.= "<br />";
		$MESSAGE.= "Ce site permet d'organiser des soirées ou spectacles avec toutes sortes d'artistes de façon très simple en mettant en contact artistes et organisateurs. <br />";
		$MESSAGE.= "<br />";
		$MESSAGE.= "De plus en t'inscrivant via le lien ci-dessus, je bénéficierais de réductions ! <br />";
		$MESSAGE.= "<br />";
		$MESSAGE.= "À bientôt sur LiveAnim.com !<br />";
		$MESSAGE.= "<br />";
		$MESSAGE.= $_SESSION['compte']['PSEUDO'].".";
		$MESSAGE.= "<br />";
		$MESSAGE.= "<center><a href='".$oCL_page->getPage('inscription')."?parrain=".$_SESSION['compte']['ID_PERSONNE']."#inscriptionh2'><img src='".$oCL_page->getPage('accueil', 'absolu').$oCL_page->getImage('logo_liveanim')."' alt='Logo LiveAnim' title='Inscris-toi !' /></a></center>";
		
		# On paramètre l'email.
		$additional_headers = "From: ".$_SESSION['compte']['EMAIL']." \r\n";
		$additional_headers.= "MIME-Version: 1.0 \n";
		$additional_headers.= "Content-Type: text/html; charset=\"ISO-8859-1\" \n";
		$additional_headers.= "<html><head><title>Message reçu de ".$_SESSION['compte']['PSEUDO']."</title><meta http-equiv='Content-Type' Content='text/html; charset=iso-8859-1'></head> \n";
		$sujet = utf8_decode("LiveAnim [Invitation de ".$_SESSION['compte']['PSEUDO']." !]");
		$message = "<body>";
		$message.= utf8_decode($MESSAGE);
		$message.= "</body></html>";
				
		foreach($EMAILS as $key => $email){
			# On nettoie l'email.
			$email = trim($email);
			
			if(!empty($email)){
				# On vérifie que l'email soit de format valide.
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					# On vérifie que la personne n'ait pas déjà envoyé un email d'invitation à cet email.
					if(!in_array($email, $_SESSION['envoyer_invitations']['invitations_envoyees'])){
						# On note l'adresse e-mail comme étant invitée.
						$_SESSION['envoyer_invitations']['invitations_envoyees'][] = $email;
						
						if(mail($email, $sujet, $message, $additional_headers)){
							$mail_envoye = true;
						}else{
							$mail_envoye = false;
							break;
						}
					}else{
						$mail_envoye = true;
					}
				}else{
					$_SESSION['envoyer_invitations']['message'].= "<span class='orange'>Un des emails saisi n'est pas valide.</span><br />";
					$mail_envoye = true;
				}
			}else{
				$mail_envoye = true;
			}
		}
		
		if($mail_envoye){
			$_SESSION['envoyer_invitations']['message'].= "<span class='rose'>Les invitations ont été envoyées.</span><br />";
		}else{
			$_SESSION['envoyer_invitations']['message'].= "<span class='alert'>Un souci technique nous empêche d'envoyer des e-mails pour le moment, nous nous excusons sincèrement de ce contretemps qui n'est pas de notre dû.<br />Merci.</span><br />";
		}
		
		header('Location: '.$oCL_page->getPage('envoyer_invitations', 'absolu'));
	}else{
		header('Location: '.$oCL_page->getPage('envoyer_invitations', 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>