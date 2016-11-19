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
?>
			<img style="" alt="Acheter un pack"
 src="images/acheter-pack.png">
<br />
<br />
	Voici tous nos packs, vous pouvez en choisir un nouveau selon votre besoin.<br />
	Vous pouvez choisir d'activer le pack immédiatement ou de de l'activer une fois votre pack actuel terminé. Quel que soit votre choix il est <b>définitif</b>.<br />
	<br />
	<br /> 
	<span class="petit orange">/!\ <u>Attention</u>: Une fois le paiement validé le choix de l'activation/non activation du pack acheté est <i>définitif</i>, donc n'hésitez pas à annuler le paiement avant de le valider si vous avez fait une erreur.</span>
	<br />
	<br />
	Si vous demandez une activation immédiate de votre nouveau pack, le délai de l'actuel sera terminé. Vous ne récupèrerez pas le temps non utilisé entre les deux packs.<br />
	<br />
	A contraire, si le pack acheté s'active à la fin de votre pack actuel alors vous ne perdrez rien mais vous ne bénéficierez pas des avantages du pack acheté avant que l'actuel ne se termine.<br />
	<br />
	<?php
	foreach($packs as $key=>$pack){
	?>
		<div id="pack" class="<?php if($pack['ID_PACK'] == $_SESSION['pack']['ID_PACK']){ echo "";} ?>" style="">
			<div class="textboxtop"><center class="gras souligne" style="font-size:15px;"><?php echo $pack['NOM'] ?></center></div>
			<div class="textbox">
				<?php if($pack['ID_PACK'] == $_SESSION['pack']['ID_PACK'] && $_SESSION['pack']['activé'] == true){ echo "<center style='font-size:11px;' class='gras noir'>[Pack Activé jusqu'au ".$_SESSION['pack']['date_fin_validite']."]</center>";} ?>
				<center class="cool">
					<?php echo $pack['DESCRIPTION'] ?><br />
				</center>
				<br />
				<center>
				Le prix total de ce pack est de <b><?php echo $pack['PRIX_BASE'] ?>€</b>. Il dure <b><?php echo $pack['DUREE'] ?> mois</b>. <span class="petit">
				</span>
				<br />
				</center>
				<?php
				if($pack['SOUMIS_REDUCTIONS_PARRAINAGE']){
					?>
					<span class="cool gras"></span>
					<?php
				}else{
					?>
					<span class="bad souligne"></span>
					<?php
				}
				?>

				<span class="souligne">Si l'un de vos filleuls achète ce pack vous bénéficierez d'une réduction de <?php echo $pack['REDUCTION'] ?>% sur l'achat de votre prochain pack !</span> <span class='petit'>(Les réductions se cumulent)</span><br />
				<br />
				<center class="souligne gras">Les avantages de ce pack:</center><br />
				<ul>
					<?php
					if($pack['CV_VISIBILITE'] == 10){
						?>
						<li class="cool "> <img style="" alt="Vip Anim"  src="/images/next2.png"> Votre C.V sera affiché dans la partie premium en page d'acceuil.</li>
						<?php
						}else if($pack['CV_VISIBILITE'] > 8){
						?>
						<li></li>
						<?php
						}else if($pack['CV_VISIBILITE'] > 6){
						?>
						<li></li>
						<?php
						}else if($pack['CV_VISIBILITE'] > 4){
						?>
						<li class="cool"></li>
						<?php
						}else if($pack['CV_VISIBILITE'] > 2){
						?>
						<li class="bad gras"></li>
						<?php
						}else if($pack['CV_VISIBILITE'] > 0){
						?>
						<li class="bad"></li>
						<?php
						}else{
						?>
						<li class="alert"> Votre C.V sera affiché dans la partie premium.</li>
						<?php
					}
					?>

					<?php
					if($pack['CV_ACCESSIBLE'] == 10){
						?>
						<li class="cool gras"></li>
						<?php
						}else if($pack['CV_ACCESSIBLE'] > 8){
						?>
						<li></li>
						<?php
						}else if($pack['CV_ACCESSIBLE'] > 6){
						?>
						<li></li>
						<?php
						}else if($pack['CV_ACCESSIBLE'] > 4){
						?>
						<li class="cool"></li>
						<?php
						}else if($pack['CV_ACCESSIBLE'] > 2){
						?>
						<li class="bad gras"></li>
						<?php
						}else if($pack['CV_ACCESSIBLE'] > 0){
						?>
						<li class="bad"></li>
						<?php
						}else{
						?>
						<li class="alert"></li>
						<?php
					}
					?>
					<?php
					if($pack['NB_FICHES_VISITABLES'] > 1000){
					?>
						<li class="cool"><img style="" alt="Vip Anim"  src="/images/next2.png"> Vous pourrez visiter un nombre illimité d'annonces&nbsp;!</li>
					<?php
					}else{
					?>
						<li class="cool"><img style="" alt="Vip Anim"  src="/images/next2.png"> Vous pourrez visiter jusqu'à <?php echo $pack['NB_FICHES_VISITABLES'] ?> annonces.</li>
					<?php
					}
					if($pack['CV_VIDEO_ACCESSIBLE']){
					?>
						<li class="cool"><img style="" alt="Vip Anim"  src="/images/next2.png"> L'accès de votre CV Vidéo sera accessible librement.</li>
					<?php
					}else{
					?>
						<li class="bad"></li>
					<?php
					}
					
					if($pack['ALERTE_NON_DISPONIBILITE']){
					?>
						<li class="cool"><img style="" alt="Vip Anim"  src="/images/next2.png"> Récupération automatique des soirées annulées près de chez vous.</li>
						<li class="cool"><img style="" alt="Vip Anim"  src="/images/next2.png"> Vous pourrez surveiller jusqu'à <?php echo $pack['NB_DEPARTEMENTS_ALERTE'] ?> départements.</li>
					<?php
					}else{
					?>
						<li class="bad"></li>
					<?php
					}
					
					if($pack['PARRAINAGE_ACTIVE']){
					?>
						<li class="cool"></li>
					<?php
					}else{
					?>
						<li class="bad"></li>
					<?php
					}
					
					if($pack['PREVISUALISATION_FICHES']){
					?>
						<li class="cool"></li>
					<?php
					}else{
					?>
						<li class="bad"></li>
					<?php
					}
					
					if($pack['CONTRATS_PDF']){
					?>
						<li class="cool"><img style="" alt="Vip Anim"  src="/images/next2.png"> Vous pourrez récupérer tous vos contrats sous format .pdf.</li>
					<?php
					}else{
					?>
						<li class="bad"></li>
					<?php
					}
					
					if($pack['SUIVI']){
					?>
						<li class="cool"></li>
					<?php
					}else{
					?>
						<li class="bad"></li>
					<?php
					}
					
					if($pack['PUBS']){
					?>
						<li class="bad"></li>
					<?php
					}else{
					?>
						<li class="cool"></li>
					<?php
					}
					?>
				</ul>
				<br />
				<?php
				/*
				* 	action --> Dirige vers paypal. /!\ Ne pas oublier de virer le sandbox pour le vrai script.
				*	amount: Somme à payer.
				*	currency_code: Type de monnaie.
				*	shipping: Frais de port.
				*	tax: Taxe.
				*	return: Page sur laquelle est redirigé l'utilisateur à la fin du paiement. (Bravo vous avez réussi !)
				* 	cancel_return: Page sur laquelle est redirigé l'utilisateur si le paiement est annulé.
				*	notify_url: Notification instantanée de Paiement (IPN). /!\ Hyper important.
				*	cmd: Le type de commande, ne pas modifier.
				*	business: Nom du compte qui va recevoir l'argent.
				*	item_name: Le nom de l'item vendu.
				*	no_note: 
				*	lc:	
				*	bn:
				*	custom: Toutes nos propres variables --> id_personne, id_pack
				*/
				?>
				<center>
					<?php
					if($pack['SOUMIS_REDUCTIONS_PARRAINAGE'] == true && $pack['VISIBLE'] == true && $pack['beneficie_reduction'] == true){
					?>
						<?php
						if($pack['nouvelle_reduction'] > 0){
						?>
							Total de réduction sur le pack: <b class="cool gras"><?php echo $pack['nouvelle_reduction']; ?>%.</b><br />
							Prix <span class="petit">(Avec réduction)</span>: <b class="cool"><?php echo $pack['nouveau_prix']; ?>€</b><br />
							<br />
					
						<?php
						}else{
						?>
							<span class="orange petit">Vous ne bénéficiez actuellement d'aucune réduction, <a href="<?php echo $oCL_page->getPage('lien_parrainage'); ?>">parrainez</a> pour en obtenir gratuitement !</span><br />
							<br />
						<?php
						}
						?>
					<?php
					}
					?>
					<label for="activer_pack_maintenant">Activer le pack dès maintenant&nbsp;</label><input onclick="maj_formulaire_paiement('custom', '<?php echo $_SESSION['compte']['ID_PERSONNE']; ?>', '<?php echo $pack['ID_PACK']; ?>');" type="checkbox" name="activer_pack_maintenant" id="activer_pack_maintenant" <?php if($_SESSION['pack']['PRIX_BASE'] < $pack['PRIX_BASE']){echo "checked='checked'";} ?> /><br />
					
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input name="amount" type="hidden" value="<?php if(isset($pack['nouveau_prix']) && !empty($pack['nouveau_prix'])){echo $pack['nouveau_prix'];}else{echo $pack['PRIX_BASE'];} ?>" />
						<input name="currency_code" type="hidden" value="EUR" />
						<input name="shipping" type="hidden" value="0.00" />
						<input name="tax" type="hidden" value="0.00" />
						<input name="return" type="hidden" value="<?php echo $oCL_page->getPage('achat_pack_ok', 'absolu'); ?>" />
						<input name="cancel_return" type="hidden" value="<?php echo $oCL_page->getPage('achat_pack_annule', 'absolu'); ?>" />
						<input name="notify_url" type="hidden" value="<?php echo $oCL_page->getPage('IPN', 'absolu'); ?>" />
						<input name="cmd" type="hidden" value="_xclick" />
						<input name="business" type="hidden" value="<?php echo $oCL_page->getConfig('compte_credite'); ?>" />
						<input name="item_name" type="hidden" value="<?php echo $pack['NOM']; ?>" />
						<input name="no_note" type="hidden" value="1" />
						<input name="lc" type="hidden" value="FR" />
						<input name="bn" type="hidden" value="PP-BuyNowBF" />
						<input id="custom" name="custom" type="hidden" value="id_personne=<?php echo $_SESSION['compte']['ID_PERSONNE']; ?>&id_pack=<?php echo $pack['ID_PACK']; ?>&duree=1&activer_pack_maintenant=0" />
						<br />
						<input type="image" src="<?php echo $oCL_page->getImage('paypal_boutons'); ?>" title="Acheter le forfait <?php echo $pack['NOM']; ?> (Via Paypal)" alt="Acheter le forfait <?php echo $pack['NOM']; ?> (Via Paypal)">
					</form>
					<span class="petit cool gras">Vous serez redirigé vers PayPal pour cette transaction, vous pouvez payer <u>via tous les moyens de paiement acceptés par PayPal</u>.
					La transaction bancaire est <u>assurée</u> par <u>PayPal</u> et est <u>sécurisée</u>.</span>
				</center>
			
			</div>
			<div class="textboxbottom"></div>
		</div>
		<br />
		<br />
	<?php
	}# Fin du foreach des packs.
	?>
	

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>