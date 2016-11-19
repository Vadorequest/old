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
	<img style="" alt="Mon compte"
 src="images/gestion-compte.png">
<br />
<br />
	Bienvenue <strong><?php echo $_SESSION['compte']['PSEUDO']; ?></strong> dans l'interface de gestion de votre compte client !<br />
	Vous pouvez via le menu de gauche gérer toutes vos données et configurations de votre compte.<br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Voici un récapitulatif de vos informations:</legend><br />
		<br />
		<?php
		if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
		?>
			<?php
			if(!empty($_SESSION['pack']['NOM'])){
			?>
				- Vous possédez actuellement le pack <strong><?php echo $_SESSION['pack']['NOM']; ?></strong>, il est valable jusqu'au <strong><?php echo $_SESSION['pack']['date_fin_validite']; ?></strong>.<br />
				<br />
			<?php
			}else{
			?>
				- <span class="alert">Vous ne possédez actuellement aucun pack.</span><br />
				- L'accès aux annonces est possible mais <b>extrêmement restreint</b><br />
				- Vous ne <b>pouvez pas être trouvé lors des recherches d'artiste</b>.</b><br />
				<br />
				<center><a href="<?php echo $oCL_page->getPage('acheter_pack'); ?>">Voir les packs disponibles.</a></center>
				<br />
			<?php
			}
			?>
			<?php
			if($_SESSION['pack']['NB_FICHES_VISITABLES'] > 1000){
			?>
				- Votre pack vous permet de visiter un nombre illimité d'annonces !<br />
				<br />
			<?php
			}else{
			?>
				- Vous pouvez visiter encore <?php if($_SESSION['pack']['NB_FICHES_VISITABLES'] > 20){echo "<span class='rose'>".$_SESSION['pack']['NB_FICHES_VISITABLES']."</span>";}else if($_SESSION['pack']['NB_FICHES_VISITABLES'] > 10){echo "<span class='orange'>".$_SESSION['pack']['NB_FICHES_VISITABLES']."</span>";}else if($_SESSION['pack']['NB_FICHES_VISITABLES'] < 10){echo "<span class='alert'>".$_SESSION['pack']['NB_FICHES_VISITABLES']."</span>";} ?> annonces.<br />
				<br />
			<?php
			}
			?>
			<span class="valide">- Vos prestations notées vous attribuent les notes moyennes suivantes:</span><br />
			<br />
			<div class="padding_LR">
				<table>
				<?php
				foreach($types_evaluation as $key=>$type_evaluation){
					foreach($evaluations as $key_eval=>$evaluation){
						if($key_eval == $type_evaluation['ID_TYPES']){
							if(!isset($evaluations[$key_eval]['erreur'])){
							?>
							
							<tr><th width="30%"><b class="rose"><?php echo $key_eval ?></b>:</th>
							<th>
							<?php
								# Pour chaque fois qu'on a une note on créée une étoile pleine.
								for($i = 0;$i < $evaluations[$key_eval]['evaluation']; $i++){
								?>
									<img src="<?php echo $oCL_page->getImage('etoile_pleine'); ?>" alt="<?php echo $i+1; ?>/5" title="<?php echo $i+1; ?>/5" />
								<?php
								}
								# Pour chaque fois qu'on a pas eu la note on crée une étoile vide.
								for($i = 0;$i < (5 - $evaluations[$key_eval]['evaluation']); $i++){
								?>
									<img src="<?php echo $oCL_page->getImage('etoile_vide'); ?>" alt="Vous avez eu <?php echo (int)$evaluations[$key_eval]['evaluation']; ?>/5" title="Vous avez eu <?php echo (int)$evaluations[$key_eval]['evaluation']; ?>/5" />
								<?php
								}
								?>
								</th></tr>
								
								<?php
							}else{
							# Le prestataire n'a jamais été noté.
							?>
							<b class="rose"><?php echo $key_eval; ?></b>: <?php echo $evaluation['erreur']; ?><br />
							<?php
							}
							
						}
					}
				}
				?>
				</table>
			</div>
			<br />
			- Vous bénéficiez actuellement de <strong><?php echo $_SESSION['compte']['REDUCTION']; ?>%</strong> de réduction sur votre prochain achat de pack.<br />
			<?php
				# Fin du if du type prestataire.
		}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur"){
		?>
			Nous vous rappellons que toutes les annonces que vous nous soumettez seront d'abord validées par un administrateur avant d'être visibles aux autres utilisateurs.<br />
			Vous ne pourrez plus modifier vos annonces une fois celles-ci validées, veuillez donc les corriger rapidement si vous faites une erreur !<br />
			<br />
		<?php
		}# Fin du if du type organisateur
		?>
		<br />
	</fieldset>
	<br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Outils&nbsp;de&nbsp;suivi:&nbsp;</legend>
		<br />
		<?php
		if(isset($_SESSION['pack']['SUIVI']) && $_SESSION['pack']['SUIVI']){
		?>
			<table width="100%">
				<tr class="formulaire">
					<th>Dépenses:</th>
					<th>Gain actuel:</th>
					<th>Gains prévus prochainement:</th>
				</tr>
				<tr><th colspan="3"><hr /></th></tr>
				<tr >
					<th><?php if($cout_packs[0]['prix_total'] != null && $cout_packs != 0){echo $cout_packs[0]['prix_total']."€";}else{echo "<span class='noir'>Vous n'avez jamais acheté de pack.</span>";} ?></th>
					<th><?php if($gain_prestations_passees[0]['prix_total'] != null && $gain_prestations_passees[0]['prix_total'] != 0){echo $gain_prestations_passees[0]['prix_total']."€";}else{echo "<span class='noir'>Vous n'avez jamais effectué de prestation.</span>";} ?></th>
					<th><?php if($gain_prestations_futures[0]['prix_total'] != null && $gain_prestations_futures[0]['prix_total'] != 0){echo $gain_prestations_futures[0]['prix_total']."€";}else{echo "<span class='noir'>Vous n'avez aucune prestation de prévue.</span>";} ?></th>
				</tr>			
			</table>
		<?php
		}else{
		?>
			<center class="petit orange">Votre pack ne vous permet pas de bénficier d'outils de suivi des vos achats & ventes.</center><br />
		<?php
		}
		?>
		<br />
	</fieldset>
	<br />
	<br />
	<fieldset class="padding_LR"><legend class="legend_basique">Informations du compte:</legend><br />
		<br />
		<span class="rose">Votre compte a été crée le:</span> <?php echo $date_creation_compte[0]['DATE_CONNEXION']; ?><br />
		<br />
		<br />
		<fieldset class="padding_LR"><legend class="legend_basique">Voici&nbsp;les&nbsp;dates&nbsp;de&nbsp;vos&nbsp;10&nbsp;dernières&nbsp;connexions:&nbsp;&nbsp;</legend><br />
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr>
					<th class="rose" width="50%" scope="col">Date de la connexion:</th>
					<th class="rose" width="50%" scope="col">Adresse IP de la connexion:</th>
				</tr>
				<?php
				foreach($dernieres_connexions as $key=>$derniere_connexion){
				?>
					<tr><th colspan="2"><hr /></th></tr>
					<tr height="40px">
						<th scope="col"><?php echo $derniere_connexion['DATE_CONNEXION']; ?></th>
						<th scope="col"><?php echo $derniere_connexion['ID_IP']; ?></th>
					</tr>
				<?php
				}
				?>
			</table>
		</fieldset>
		<br />
	</fieldset>
	
	

<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>