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

	if(!$fp){
		file_put_contents($oCL_page->getPage('paiement_bancaire_annonce_goldlive')."ERROR_".$now_concat."_POST.txt", print_r($_POST, true));
	}else{
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
							
							$nb_erreur = 0;
							
							# On charge les informations de notre client via id_personne.
							require_once('couche_metier/MSG.php');
							require_once('couche_metier/PCS_annonce.php');
							
							$oMSG = new MSG();
							$oPCS_annonce = new PCS_annonce();
							
							# On vérifie que le prix soit au moins de 1.00
							if($payment_amount < 1.60){
								$nb_erreur++;
							}
							
							# On vérifie que la monnaie soit l'euro.
							if($payment_currency != "EUR"){
								$nb_erreur++;
							}
							
							# On vérifie qu'il n'y ait pas d'erreur.
							if($nb_erreur == 0){
								# On met à jour l'annonce comme étant goldlive.
								
								$oMSG->setData('ID_ANNONCE', $datas['id_annonce']);
								$oMSG->setData('GOLDLIVE', 1);
								
								$oPCS_annonce->fx_modifier_goldlive_by_ID_ANNONCE($oMSG);
								
								$now_concat = date('Y-m-d-H-i-s');
								
								# On sauvegarde dans un fichier les données de l'achat.
								file_put_contents($oCL_page->getPage('paiement_bancaire_annonce_goldlive')."OK_IDP-".$datas['id_personne']."_IDA-".$datas['id_annonce']."_".$now_concat."_POST.txt", print_r($_POST, true));
							}else{
								file_put_contents($oCL_page->getPage('paiement_bancaire_annonce_goldlive')."ERROR_IDP-".$datas['id_personne']."_IDA-".$datas['id_annonce']."_".$now_concat."_POST.txt", print_r($_POST, true));
							}
						}else{
							file_put_contents($oCL_page->getPage('paiement_bancaire_annonce_goldlive')."ERROR_".$now_concat."_POST.txt", print_r($_POST, true));
						}
				}else {
					file_put_contents($oCL_page->getPage('paiement_bancaire_annonce_goldlive')."ERROR_".$now_concat."_POST.txt", print_r($_POST, true));
				}
				exit();
		   }else if (strcmp ($res, "INVALID") == 0) {
				// Transaction invalide
					file_put_contents($oCL_page->getPage('paiement_bancaire_annonce_goldlive')."ERROR_".$now_concat."_POST.txt", print_r($_POST, true));
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