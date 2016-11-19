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
?>