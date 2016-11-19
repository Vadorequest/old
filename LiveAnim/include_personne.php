<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}
?>
<?php
if(isset($_SESSION['pack']) && $_SESSION['pack']['CV_ACCESSIBLE'] >= 2){
?>
	<h2>Fiche de <?php echo $prestataire[0]['PSEUDO']; ?>:</h2><br />
	<br />
<?php
}else{
?>
	<h2>Fiche de l'artiste N°<?php echo $prestataire[0]['ID_PERSONNE']; ?>:</h2><br />
	<br />
<?php
}
?>
<?php
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
?>
<br />
<fieldset class="padding_LR"><legend class="legend_basique">Modération</legend><br />
	<br />
	<center>
		<a href="<?php echo $oCL_page->getPage('modifier_fiche_membre')."?id_personne=".$prestataire[0]['ID_PERSONNE']; ?>">Modifier la fiche du membre.</a><br />
	</center>
	<br />
</fieldset>
<?php
}
?>
<br />
<fieldset class="padding_LR"><legend class="legend_basique">Informations personnelles</legend><br />
	<br />
	<div style="width:80%; float:left;">
		<?php
		if($_SESSION['compte']['connecté'] == true){
			if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] >= 4){
			?>
				<b class="rose">Identité</b>: <?php echo $prestataire[0]['CIVILITE']; ?>. <?php echo $prestataire[0]['NOM']; ?> <?php echo $prestataire[0]['PRENOM']; ?>.
			<?php
			}else if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] < 4){
			?>
				<b class="rose">Identité</b>: <span class="orange petit">Le prestataire ne peut pas afficher cette information.</span><br />
			<?php
			}else{
			?>
				<b class="rose">Identité</b>: <span class="alert petit">Vous devez être connecté pour voir cette information !</span><br />
			<?php
			}
			?>
			<br />
			<b class="rose">Age</b>: <?php if($prestataire[0]['age'] < 150){echo $prestataire[0]['age']." ans.";}else{echo "<span class='orange petit'>Le prestataire ne souhaite pas afficher cette information.</span>";} ?> <br />
			<br />
			<?php
			if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] >= 4){
			?>
				<b class="rose">Email</b>: <?php echo $prestataire[0]['EMAIL']; ?><br />
			<?php
			}else if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] < 4){
			?>
				<b class="rose">Email</b>: <span class="orange petit">Le prestataire ne peut pas afficher cette information.</span><br />
			<?php
			}else{
			?>
				<b class="rose">Email</b>: <span class="alert petit">Vous devez être connecté pour voir cette information !</span><br />
			<?php
			}
			?>
			<br />
			<?php
			if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] >= 4){
			?>
				<b class="rose">Adresse postale</b>: <?php echo $adresse_complete; ?><br />
			<?php
			}else if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] < 4){
			?>
				<b class="rose">Adresse postale</b>: <span class="orange petit">Le prestataire ne peut pas afficher cette information.</span><br />
			<?php
			}else{
			?>
				<b class="rose">Adresse postale</b>: <span class="alert petit">Vous devez être connecté pour voir cette information !</span><br />
			<?php
			}
			?>
			<br />
			<?php
			if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] >= 4){
			?>
				<b class="rose">Téléphone fixe</b>: <?php echo $prestataire[0]['TEL_FIXE']; ?><br />
				<b class="rose">Téléphone portable</b>: <?php echo $prestataire[0]['TEL_PORTABLE']; ?><br />
			<?php
			}else if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] < 4){
			?>
				<b class="rose">Téléphone fixe</b>: <span class="orange petit">Le prestataire ne peut pas afficher cette information.</span><br />
				<b class="rose">Téléphone portable</b>: <span class="orange petit">Le prestataire ne peut pas afficher cette information.</span><br />
			<?php
			}else{
			?>
				<b class="rose">Téléphone fixe</b>: <span class="alert petit">Vous devez être connecté pour voir cette information !</span><br />
				<b class="rose">Téléphone portable</b>: <span class="alert petit">Vous devez être connecté pour voir cette information !</span><br />
			<?php
			}
		}#Fin du if de test connecté.
		
			?>
		<br />
	</div>

	<div style="width:20%; float:left;">
		<img class="image_border" src="<?php  if(!empty($prestataire[0]['URL_PHOTO_PRINCIPALE'])){echo $prestataire[0]['URL_PHOTO_PRINCIPALE'];}else{echo $oCL_page->getImage("avat_test1");} ?>" alt="Photo du membre" title="Photo du membre" />
	</div>
	<div class="clear">
		<?php
		if($_SESSION['compte']['connecté'] == true){
			if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] >= 2){
				if(!empty($prestataire[0]['DESCRIPTION'])){
				?>
					<b class="rose">Description</b>: <br />
					<?php echo $prestataire[0]['DESCRIPTION']; ?><br />
				<?php
				}else{
					echo "<b class='rose'>Description</b>: <br /><span class='orange petit'>Le prestataire n'a pas rédigé de description.</span>";
				}
			}else if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] < 2){
			?>
				<b class="rose">Description</b>: <span class="orange petit">Le prestataire ne peut pas afficher cette information.</span><br />
			<?php
			}else{
			?>
				<b class="rose">Description</b>: <span class="alert petit">Vous devez être connecté pour voir cette information !</span><br />
			<?php
			}
		}# Fin du if de test connecté
		else{
			echo "<div><br /><center class='alert'>Vous devez être connecté pour voir les informations personnelles de l'artiste !</center>";
		}
		?>
		<br />
		<br />
	</div>
	<br />
</fieldset>
<br />
<br />
<br />
<fieldset class="padding_LR"><legend class="legend_basique">Informations sur ses prestations</legend><br />
	<br />
	<div>
		<b class="rose"><u>Rôles que peut occuper <?php if(isset($_SESSION['pack']) && $_SESSION['pack']['CV_ACCESSIBLE'] >= 2){echo $prestataire[0]['PSEUDO'];}else{echo "le membre N°".$prestataire[0]['ID_PERSONNE'];} ?></u></b>: <br />
		<ul>
		<?php 
		if(count($ROLES) > 0 && !empty($ROLES[0])){
			foreach($ROLES as $key=>$ROLE){
			?>
				<li><?php echo $ROLE; ?></li>
			<?php
			}		
		}else{
			echo "<span class='orange petit'>Le prestataire ne s'est pas attribué de rôle.</span>";
		}
		?>
		</ul>
		<br />
		<b class="rose"><u>Moyennes des notes du prestataire</u></b>:<br />
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
								<img src="<?php echo $oCL_page->getImage('etoile_pleine'); ?>" alt="Le prestataire a eu <?php echo (int)$evaluations[$key_eval]['evaluation']; ?>/5" title="Le prestataire a eu <?php echo (int)$evaluations[$key_eval]['evaluation']; ?>/5" />
							<?php
							}
							# Pour chaque fois qu'on a pas eu la note on crée une étoile vide.
							for($i = 0;$i < (5 - $evaluations[$key_eval]['evaluation']); $i++){
							?>
								<img src="<?php echo $oCL_page->getImage('etoile_vide'); ?>" alt="Le prestataire a eu <?php echo (int)$evaluations[$key_eval]['evaluation']; ?>/5" title="Le prestataire a eu <?php echo (int)$evaluations[$key_eval]['evaluation']; ?>/5" />
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
		<br />
		<b class="rose"><u>Statut professionnel</u></b>: </span><br />
		<?php if(!empty($prestataire[0]['STATUT_PERSONNE'])){echo $prestataire[0]['STATUT_PERSONNE'];}else{echo "<span class='petit orange'>Le prestataire n'a pas déclaré son statut professionnel.</span>";} ?><br />
		<br />
		<?php
		# Grâce au numéro de siret on peut retrouver une personne donc on évite de l'afficher si le mec n'a pas de pack au moins Live Max.
		if($prestataire[0]['STATUT_PERSONNE'] == "Pro" && !empty($prestataire[0]['SIRET'])){
			if($pack_ok == true && $pack_prestataire[0]['CV_ACCESSIBLE'] >= 6){
			?>
				<b class="rose">N° SIRET</b>: </span><?php echo $prestataire[0]['SIRET']; ?><br />
				<br />
			<?php
			}else{
			?>
				<b class="rose">N° SIRET</b>: </span><span class="petit orange">Le prestataire ne peut pas afficher son numéro de siret.</span><br />
				<br />
			<?php
			}
		}
		?>
		<?php
		if($prestataire[0]['DISTANCE_PRESTATION_MAX'] != "0,0"){
		?>
			<b class="rose souligne">Distance maximale acceptée entre le lieu de prestation et son lieu d'habitation</b>: </span><br />
			<?php echo $prestataire[0]['DISTANCE_PRESTATION_MAX']; ?> Km.<br />
			<br />
		<?php
		}
		?>
		<b class="rose"><u>Détail des tarifs</u></b>: </span><br />
		<?php if(!empty($prestataire[0]['TARIFS'])){echo $prestataire[0]['TARIFS'];}else{echo "<span class='orange petit'>Le prestataire n'a donné aucune information sur ses tarifs.</span>";} ?><br />
		<br />
		<br />
		<b class="rose"><u>Détail de l'équipement</u></b>: </span><br />
		<?php if(!empty($prestataire[0]['MATERIEL'])){echo $prestataire[0]['MATERIEL'];}else{echo "<span class='orange petit'>Le prestataire n'a donné aucune information sur son équipement.</span>";} ?><br />
		<br />
		<br />
		<?php
		if($pack_ok == true && $pack_prestataire[0]['CV_VIDEO_ACCESSIBLE'] == true){
		?>
			<b class="rose"><u>Vidéo présentant son activité</u></b>: <br />
			<br />
			<?php 
			if(!empty($prestataire[0]['CV_VIDEO'])){
			?>
				<object style="border-width:2px;border-style:solid;" type="application/x-shockwave-flash" width="100%" height="355px" data="<?php echo $prestataire[0]['CV_VIDEO']; ?>">
					<param name="movie" value="<?php echo $prestataire[0]['CV_VIDEO']; ?>">
					<param name="wmode" value="transparent">
				</object>
			<?php
			}else{
				echo "<span class='noir'>Pas de vidéo disponible.</span>";
			}
			?>
			<br />
		<?php
		}else if($pack_ok == true && $pack_prestataire[0]['CV_VIDEO_ACCESSIBLE'] == false){
		?>
			<b class="rose"><u>Vidéo présentant son activité</u></b>:<br />
			<span class="orange petit">Le pack de l'artiste ne lui permet pas d'afficher cette information.</span><br />
		<?php
		}else{
		?>
			<b class="rose"><u>Vidéo présentant son activité</u></b>:<br />
			<span class="alert petit">Le pack de l'artiste ne lui permet pas d'afficher cette information.</span><br />
		<?php
		}
		?>
		<br />
	</div>

</fieldset>

