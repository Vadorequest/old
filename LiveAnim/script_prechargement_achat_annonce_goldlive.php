<?php
//https://payment.allopass.com/buy/buy.apu?ids=270455&idd=1096819
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
	if(isset($_GET["RECALL"]) && isset($_GET['reussite']) && $_GET['reussite'] == 1){
	
		$RECALL = $_GET["RECALL"];
		
		if( trim($RECALL) == "" )
		{
			# RECALL est vide.
			$achat_annonce_goldlive_ok = false;
			die();
		}
		// $RECALL contient le code d'accès
		$RECALL = urlencode($RECALL);
		 
		// $AUTH doit contenir l'identifiant de VOTRE document
		 
		$AUTH = urlencode('54/75/456');

		/**
		* envoi de la requête vers le serveur AlloPAss
		* dans la variable $r[0] on aura la réponse du serveur
		* dans la variable $r[1] on aura le code du pays d'appel de l'internaute
		* (FR,BE,UK,DE,CH,CA,LU,IT,ES,AT,...)
		* Dans le cas du multicode, on aura également $r[2],$r[3] etc...
		* contenant à chaque fois le résultat et le code pays.
		*/
		 
	    $r = @file( "http://payment.allopass.com/api/checkcode.apu?code=$RECALL&auth=$AUTH" );
		
		/*  # Pas moyen de contacter allopass pour le moment.
			// on teste la réponse du serveur
			if( substr( $r[0],0,2 ) != "OK" ) 
			{
				$achat_annonce_goldlive_ok = false;
				die();
			}
		*/
		# Sinon c'est que c'est bon !
		$achat_annonce_goldlive_ok = true;
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_annonce.php');
		
		$oMSG = new MSG();
		$oPCS_annonce = new PCS_annonce();
		
		$now_concat = date('Y-m-d-H-i-s');
		
		# On passe l'annonce courante en goldlive.
		$oMSG->setData('ID_ANNONCE', $_SESSION['annonce']['annonce_courante']);
		$oMSG->setData('GOLDLIVE', 1);
		$oMSG->setData('Date achat', $now_concat);
		$oMSG->setData('Code', $RECALL);
		$oMSG->setData('Réponse allopass', $r);
		
		$oPCS_annonce->fx_modifier_goldlive_by_ID_ANNONCE($oMSG);
										
		# On sauvegarde dans un fichier les données de l'achat.
		file_put_contents($oCL_page->getPage('paiement_allopass_annonce_goldlive')."OK_IDP-".$_SESSION['compte']['ID_PERSONNE']."_IDA-".$_SESSION['annonce']['annonce_courante']."_".$now_concat."_oMSG.txt", print_r($oMSG, true));
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>