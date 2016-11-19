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
	if($id_contrat_ok){
	?>
		<h2>Contrat N°<?php echo $contrat[0]['ID_CONTRAT']; ?></h2><br />
		<br />
		<?php
		if(isset($_SESSION['contrat']['message']) && $_SESSION['contrat']['message_affiche'] == false){
			echo $_SESSION['contrat']['message'];
			$_SESSION['contrat']['message_affiche'] = true;
		}
		?>
		<fieldset class="padding_LR"><legend class="legend_basique">Détails du contrat N°<?php echo $contrat[0]['ID_CONTRAT']; ?></legend><br />
		<br />
		<center>
			Ce contrat porte sur l'annonce <a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$contrat[0]['ID_ANNONCE']; ?>"><?php echo $contrat[0]['TITRE'] ?></a>.<br />
		<br />
		Ce contrat est actuellement <b><span class="<?php if($contrat[0]['STATUT_CONTRAT'] == "Validé"){echo "rose";}else if($contrat[0]['STATUT_CONTRAT'] == "Refusé"){echo "orange";}else if($contrat[0]['STATUT_CONTRAT'] == "Annulé"){echo "alert";}else{echo "noir";}?>"><?php echo $contrat[0]['STATUT_CONTRAT']; ?></span></b>.<br />
		<br />
		Évènement: <span class="rose"><?php echo $contrat[0]['TYPE_ANNONCE']; ?></span>.<br />
		<br />
		</center>
		<br />
		<hr />
		<br />
		<table width="100%">
			<tr class="formulaire">
				<th scope="col">Offre d'origine:</th>
				<th scope="col">Demande actuelle:</th>
			</tr>
			<tr>
				<th colspan="2">&nbsp;</th>
			<tr>
				<th height="25px">Début le <?php echo $contrat[0]['DATE_DEBUT_annonce']; ?>.</th>
				<th height="25px">Début le <span class="<?php if($contrat[0]['DATE_DEBUT_annonce'] != $contrat[0]['DATE_DEBUT_contrat']){echo "orange";}else{echo "valide";} ?>"><?php echo $contrat[0]['DATE_DEBUT_contrat']; ?></span>.</th>
			</tr>
			<tr>
				<th height="25px">Fin le <?php echo $contrat[0]['DATE_FIN_annonce']; ?>.</th>
				<th height="25px">Fin le <span class="<?php if($contrat[0]['DATE_FIN_annonce'] != $contrat[0]['DATE_FIN_contrat']){echo "orange";}else{echo "valide";} ?>"><?php echo $contrat[0]['DATE_FIN_contrat']; ?></span>.</th>
			</tr>
			<tr>
				<th height="25px">Prix: <?php echo $contrat[0]['PRIX_annonce']; ?>€ (HT)</th>
				<th height="25px">Prix: <span class="<?php if($contrat[0]['PRIX_contrat'] != $contrat[0]['PRIX_annonce']){echo "orange";}else{echo "valide";} ?>"><?php echo $contrat[0]['PRIX_contrat']; ?>€</span> (HT)</th>
			</tr>
		</table>
		<br />
		<center class="valide">Commentaires:</center><br />
		<?php echo $contrat[0]['DESCRIPTION_contrat']; ?><br />
		<br />
		<br />
		<hr />
		<br />
		<?php
		# Si le contrat est validé, que l'internaute est l'organisateur et que la date de fin du contrat et révolue.
		if($contrat[0]['STATUT_CONTRAT'] == "Validé" && $_SESSION['compte']['TYPE_PERSONNE'] != "Prestataire" && $contrat[0]['DATE_FIN_contrat_formatee'] < $now_formatee){
		?>
			<b><u>Veuillez noter la prestatation de l'artiste:</u></b><br />
			<br />
			<hr />
			<br />
			<center class=" rose"><b>Vous avez noté le prestataire le <?php echo $contrat[0]['DATE_EVALUATION'] ?>.</b></center><br />
			<b class="fright rose">Dernières notes:</b>
			<br />
			<?php
			foreach($types as $key=>$type){
			?>
			
				<b><?php echo $type['ID_TYPES']; ?>:</b>
				<form action="script_noter_prestataire.php" method="post">
					<input type="hidden" name="form_noter_prestataire_id_contrat" value="<?php echo $contrat[0]['ID_CONTRAT']; ?>" />
					<ul class="notes-echelle">
						<li>
							<label for="note01<?php echo $type['ID_TYPES']; ?>" title="Note&nbsp;: 1 sur 5">&nbsp;</label>
							<input type="radio" name="<?php echo $type['ID_TYPES']; ?>1" id="note01<?php echo $type['ID_TYPES']; ?>" value="1" />
						</li>
						<li>
							<label for="note02<?php echo $type['ID_TYPES']; ?>" title="Note&nbsp;: 2 sur 5">&nbsp;</label>
							<input type="radio" name="<?php echo $type['ID_TYPES']; ?>2" id="note02<?php echo $type['ID_TYPES']; ?>" value="2" />
						</li>
						<li>
							<label for="note03<?php echo $type['ID_TYPES']; ?>" title="Note&nbsp;: 3 sur 5">&nbsp;</label>
							<input type="radio" name="<?php echo $type['ID_TYPES']; ?>3" id="note03<?php echo $type['ID_TYPES']; ?>" value="3" />
						</li>
						<li>
							<label for="note04<?php echo $type['ID_TYPES']; ?>" title="Note&nbsp;: 4 sur 5">&nbsp;</label>
							<input type="radio" name="<?php echo $type['ID_TYPES']; ?>4" id="note04<?php echo $type['ID_TYPES']; ?>" value="4" />
						</li>
						<li>
							<label for="note05<?php echo $type['ID_TYPES']; ?>" title="Note&nbsp;: 5 sur 5">&nbsp;</label>
							<input type="radio" name="<?php echo $type['ID_TYPES']; ?>5" id="note05<?php echo $type['ID_TYPES']; ?>" value="5" />
						</li>
						<?php
						if($evaluations[$key]['TYPE_EVALUATION'] == $type['ID_TYPES']){
						?>
							<span class="fright">
							<?php
								# Pour chaque fois qu'on a une note on créée une étoile pleine.
								for($i = 0;$i < $evaluations[$key]['EVALUATION']; $i++){
								?>
									<img src="<?php echo $oCL_page->getImage('etoile_pleine'); ?>" alt="<?php echo $i+1; ?>/5" title="<?php echo $i+1; ?>/5" />
								<?php
								}
								# Pour chaque fois qu'on a pas eu la note on crée une étoile vide.
								for($i = 0;$i < (5 - $evaluations[$key]['EVALUATION']); $i++){
								?>
									<img src="<?php echo $oCL_page->getImage('etoile_vide'); ?>" alt="Vous avez eu <?php echo $evaluations[$key]['EVALUATION']; ?>/5" title="Vous avez eu <?php echo $evaluations[$key]['EVALUATION']; ?>/5" />
								<?php
								}
								?>
								</td></tr>
							</span>
						<?php
						}
						?>
					</ul>
					<br / style="clear:left;">
					<br />
			<?php
			}
			?>
					<center>
						<input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Valider mes notes" title="Valider mes notes" />
					</center>
				</form>
				<br />
				<hr />
				<br />
			
		<?php
		}else if($contrat[0]['STATUT_CONTRAT'] == "Validé" && $_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" && $contrat[0]['DATE_FIN_contrat_formatee'] < $now_formatee){
		?>
			<?php
			if(!empty($evaluations)){ 
				?>
				<center class="valide">Vous avez obtenu les notes suivantes:<br />
				<br />
				<table>
				<?php
				foreach($evaluations as $key=>$evaluation){
					?>
						<tr><th><b class="rose"><?php echo $evaluation['TYPE_EVALUATION']; ?></b>:&nbsp;&nbsp;</th><td>
					<?php
					# Pour chaque fois qu'on a une note on créée une étoile pleine.
					for($i = 0;$i < $evaluation['EVALUATION']; $i++){
					?>
						<img src="<?php echo $oCL_page->getImage('etoile_pleine'); ?>" alt="<?php echo $i+1; ?>/5" title="<?php echo $i+1; ?>/5" />
					<?php
					}
					# Pour chaque fois qu'on a pas eu la note on crée une étoile vide.
					for($i = 0;$i < (5 - $evaluation['EVALUATION']); $i++){
					?>
						<img src="<?php echo $oCL_page->getImage('etoile_vide'); ?>" alt="Vous avez eu <?php echo $evaluation['EVALUATION']; ?>/5" title="Vous avez eu <?php echo $evaluation['EVALUATION']; ?>/5" />
					<?php
					}
					?>
					</td></tr>
					<?php
				}
				?>
				</table>
				<br /></center>
				<?php
			}else{
			?>
				<center class="orange">Vous n'avez pas encore été noté pour cette prestation.</center><br />
			<?php
			}
			?>
		
		<?php
		}else if($contrat[0]['STATUT_CONTRAT'] == "Annulé après validation" || $contrat[0]['STATUT_CONTRAT'] == "Annulé"){
		?>
			<center class="orange">Ce contrat a été annulé.</center><br />
		<?php
		}else if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
		?>
			<center class="orange">Ce contrat n'est pas encore terminé, l'organisateur ne peut donc pas noter votre prestation pour le moment.</center><br />
		<?php
		}else{
		?>
			<center class="orange">Ce contrat n'est pas encore terminé, vous ne pouvez pas noter le prestataire pour le moment.</center><br />
		<?php
		}
		?>
		
		<br />
		<hr />
		<br />
		
		<center><ul class="fleft" id="contrat_list">
			<?php
			# Si c'est le destinataire qui visualise le contrat alors on lui permet de l'accepter. (si le contrat n'est pas refusé ou déjà accepté)
			if($contrat[0]['DESTINATAIRE'] == $_SESSION['compte']['ID_PERSONNE'] && $contrat[0]['STATUT_CONTRAT'] != "Annulé" && $contrat[0]['STATUT_CONTRAT'] != "Validé"){
			?>
				<li class="fleft">
					<a onclick="return confirm('Souhaitez-vous accepter ce contrat ? \nVous pourrez toujours l\'annuler en cas de non disponibilité imprévue.');" href="<?php echo $oCL_page->getPage('script_accepter_contrat')."?id_contrat=".$contrat[0]['ID_CONTRAT']; ?>"><input type="image" src="<?php echo $oCL_page->getImage('accepter_contrat'); ?>" alt="Accepter le contrat" title="Accepter le contrat" /></a>
				</li>
			<?php
			}
			# Si le contrat n'est ni annulé ni validé alors on permet la modification du contrat.
			if($contrat[0]['STATUT_CONTRAT'] != "Annulé" && $contrat[0]['STATUT_CONTRAT'] != "Validé" && $contrat[0]['STATUT_CONTRAT'] != "Annulé après validation"){
			?>
				<!-- Oui, je suis d'accord, c'est un truc de porc. Rapide, efficace, universel mais c'est cochon ^^ -->
				<li class="fleft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
				<li class="fleft"><a href="<?php echo $oCL_page->getPage('modifier_contrat')."?id_contrat=".$contrat[0]['ID_CONTRAT']; ?>"><input type="image" src="<?php echo $oCL_page->getImage('modifier_contrat'); ?>" alt="Modifier le contrat" title="Modifier le contrat" /></a></li>
				<li class="fleft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
				<li class="fleft"><a onclick="return confirm('Souhaitez-vous annuler ce contrat définitivement ? \nCette action est irréversible.');" href="<?php echo $oCL_page->getPage('script_annuler_contrat')."?id_contrat=".$contrat[0]['ID_CONTRAT']; ?>"><input type="image" src="<?php echo $oCL_page->getImage('supprimer'); ?>" alt="Annuler définitivement le contrat" title="Annuler définitivement le contrat" /></a></li>
			<?php
			}else if(($contrat[0]['STATUT_CONTRAT'] == "Validé" || $contrat[0]['STATUT_CONTRAT'] == "En attente") && $contrat[0]['DATE_FIN_contrat_formatee'] > $now_formatee){
			?>
				<li class="fleft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
				<li class="fleft">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
				<li class="fleft"><a onclick="return confirm('Souhaitez-vous annuler ce contrat définitivement ? \nCette action est irréversible.');" href="<?php echo $oCL_page->getPage('script_annuler_contrat')."?id_contrat=".$contrat[0]['ID_CONTRAT']; ?>"><input type="image" src="<?php echo $oCL_page->getImage('supprimer'); ?>" alt="Annuler définitivement le contrat" title="Annuler définitivement le contrat" /></a></li>
			<?php
			}else if($contrat[0]['DATE_FIN_contrat_formatee'] < $now_formatee){
			
			?>
				<b>Ce contrat est actuellement <span class="orange"><?php echo $contrat[0]['STATUT_CONTRAT']; ?></span>, mais sa date de validité est dépassée, vous ne pouvez plus le modifier.</b><br />
				<br />
			<?php
			}else{
			?>
				<b>Ce contrat est actuellement <span class="orange"><?php echo $contrat[0]['STATUT_CONTRAT']; ?></span>, vous ne pouvez ni le modifier ni le valider.</b><br />
				<br />
			<?php
			}
			?>
		</ul></center>
		</fieldset>
	<?php
	}else{
		if(isset($_SESSION['contrat']['message']) && $_SESSION['contrat']['message_affiche'] == false){
			echo $_SESSION['contrat']['message'];
			$_SESSION['contrat']['message_affiche'] = true;
		}
	}
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>