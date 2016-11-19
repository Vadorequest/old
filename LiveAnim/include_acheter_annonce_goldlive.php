<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne est connectée.
if($_SESSION['compte']['connecté'] == true){
?>
	<fieldset class="padding_LR"><legend class="legend_basique">Annonce&nbsp;GoldLive:&nbsp;</legend><br />
		<br />
		Vous voulez que votre annonce soit bien <b>visible</b> ? Le statut <b class="rose">GoldLive</b> est là pour ça !<br />
		Pour seulement <span class="petit">1€</span> cette annonce sera placée <span class="rose">en tête des résultats de recherche</span> du site et de moteurs de recherches !<br />
		<br />
		<fieldset class="padding_LR"><legend class="legend_basique">Paypal:&nbsp;</legend><br />
			Nous mettons à votre disposition le moyen de paiement Paypal, qui vous permet de payer via un compte Paypal pour seulement 1.6€ !<br />
			<br />
			<center>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="XG6P5HWAGUK2L">
					<input name="shipping" type="hidden" value="0.00" />
					<input name="tax" type="hidden" value="0.00" />
					<input name="notify_url" type="hidden" value="<?php echo $oCL_page->getPage('annonce_goldlive_IPN', 'absolu'); ?>" />
					<input id="custom" name="custom" type="hidden" value="id_personne=<?php echo $_SESSION['compte']['ID_PERSONNE']; ?>&id_annonce=<?php echo $annonce[0]['ID_ANNONCE']; ?>" />
					<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
					<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
				</form>

			</center>
			<br />
		</fieldset>
		<br />
		<fieldset class="padding_LR"><legend class="legend_basique">Allopass&nbsp;</legend><br />
			Nous mettons à votre disposition le moyen de paiement Allopass, qui vous permet de payer par téléphone (appel ou SMS, surtaxés) ou via Neosurf.<br />
			<br />
			<center>
				<script type="text/javascript" src="https://payment.allopass.com/buy/checkout.apu?ids=270455&idd=1096819&lang=fr"></script>
				<noscript>
					<a href="https://payment.allopass.com/buy/buy.apu?ids=270455&idd=1096819" style="border:0">
						<img src="https://payment.allopass.com/static/buy/button/fr/162x56.png" style="border:0" alt="Achetez maintenant !" />
					</a>
				</noscript>
			</center>
			<br />
		</fieldset>
		<br />
	</fieldset>

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>