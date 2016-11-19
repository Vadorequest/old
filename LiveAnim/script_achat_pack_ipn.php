<?php 
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
							$oMSG->setData('ID_PACK', 7);
							$oMSG->setData('ID_PERSONNE', $personne[0]['ID_PERSONNE']);
							$oMSG->setData('DATE_ACHAT', $now);
							$oMSG->setData('DATE_DEBUT', '1950-01-01 00:00:00');
							$oMSG->setData('DATE_FIN', '1950-01-01 00:00:00');
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
}