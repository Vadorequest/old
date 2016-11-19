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
	if(isset($_POST['ids_msg']) || isset($_GET['ids_msg'])){
		if(isset($_GET['ids_msg'])){
			$ID_MESSAGE = $_GET['ids_msg'];
			
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
			
			$_SESSION['messagerie']['message'] = "";
			$_SESSION['messagerie']['message_affiche'] = false;
			
			$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
			$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
			
			# On vérifie que le message appartient bien à la personne.
			$nb_message = $oPCS_message->fx_compter_message_by_ID_MESSAGE_et_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
						
			if($nb_message[0]['nb_message'] == 1){
				# On 'supprime' le message.
				$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
				$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
				$oMSG->setData('STATUT_MESSAGE', 'Supprimé');
				
				$oPCS_message->fx_supprimer_message($oMSG);
				
				$_SESSION['messagerie']['message'].= "<center class='valide'>Le message a été supprimé.</center><br />";
				header('Location: '.$oCL_page->getPage('messagerie', 'absolu'));
				
			}else{
				# Le message n'appartient pas à la personne.
				$_SESSION['messagerie']['message'].= "<span class='alert'>Le message que vous essayez de supprimer ne vous appartient pas.</span><br />";
				header('Location: '.$oCL_page->getPage('messagerie', 'absolu'));
			}
		}else if(isset($_POST['ids_msg']) && !empty($_POST['ids_msg'])){
			foreach($_POST['ids_msg'] as $ID_MESSAGE){
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
				
				$_SESSION['messagerie']['message'] = "";
				$_SESSION['messagerie']['message_affiche'] = false;
				
				$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
				$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
				
				# On vérifie que le message appartient bien à la personne.
				$nb_message = $oPCS_message->fx_compter_message_by_ID_MESSAGE_et_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
							
				if($nb_message[0]['nb_message'] == 1){
					# On 'supprime' le message.
					$oMSG->setData('ID_MESSAGE', $ID_MESSAGE);
					$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
					$oMSG->setData('STATUT_MESSAGE', 'Supprimé');
					
					$oPCS_message->fx_supprimer_message($oMSG);
					
					if(count($_POST['ids_msg']) > 1){
						$_SESSION['messagerie']['message'].= "<center class='valide'>Les messages ont été supprimés.</center><br />".
						"<center class='petit'>(En cas de suppression accidentelle d'un message, contactez nous s'il est vraiment important)</center><br />";
					}else{
						$_SESSION['messagerie']['message'].= "<center class='valide'>Le message a été supprimé.</center><br />".
						"<center class='petit'>(En cas de suppression accidentelle d'un message, contactez nous s'il est vraiment important)</center><br />";
					}
					header('Location: '.$oCL_page->getPage('messagerie', 'absolu'));
					
				}else{
					# Le message n'appartient pas à la personne.
					$_SESSION['messagerie']['message'].= "<span class='alert'>Le message que vous essayez de supprimer ne vous appartient pas.</span><br />";
					header('Location: '.$oCL_page->getPage('messagerie', 'absolu'));
				}
			}
		}else{
			# On a pas défini ce cas là.
			header('Location: '.$oCL_page->getPage('messagerie', 'absolu'));
		}
	}else{
		# Si on ne reçoit rien.
		$_SESSION['messagerie']['message'].= "<span class='orange'>Vous n'avez pas sélectionné de message.</span><br />";
		header('Location: '.$oCL_page->getPage('messagerie', 'absolu'));
	}	
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>