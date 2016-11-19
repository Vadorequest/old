<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_menuv_gestion_compte.php');

# On vérifie que la personne soit connectée.
if($_SESSION['compte']['connecté'] == true){
?>
	
		<br />
		<fieldset style="background-color:#cecece;opacity:0.9;" class="padding_LR"><legend style="background-color:#cecece;opacity:0.9;border-radius:5px;" class="legend_basique">Mes&nbsp;infos&nbsp;perso:&nbsp;</legend><br />
			<ul>
				<li><a title="Vous pouvez y modifier l'intégralité de vos informations personnelles hormis votre mot de passe." href="<?php echo $oCL_page->getPage('modifier_fiche_perso'); ?>">Modifier mes informations personnelles.</a></li>
				<li><a title="Vous pouvez y modifier votre mot de passe." href="<?php echo $oCL_page->getPage('modifier_mdp'); ?>">Modifier mon mot de passe.</a></li>
				<li>&nbsp;</li>
				<li><a title="Vous pouvez y supprimer votre compte. (Aucun remboursement n'aura lieu.)" href="<?php echo $oCL_page->getPage('supprimer_compte'); ?>">Supprimer mon compte.</a></li>
			</ul>
			<br />
		</fieldset>
		<br /><br />
		<?php
		if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
		?>
			<fieldset style="background-color:#cecece;opacity:0.9;" class="padding_LR"><legend style="background-color:#cecece;opacity:0.9;border-radius:5px;" class="legend_basique">Mes&nbsp;Packs:&nbsp;</legend><br />
				<ul>
					<li><a title="Consultez tous vous achats de packs." href="<?php echo $oCL_page->getPage('historique_achat_pack'); ?>">Historique de mes achats.</a></li>
					<li><a title="Venez découvrir nos Packs !" href="<?php echo $oCL_page->getPage('acheter_pack'); ?>">Acheter un Pack.</a></li>
				</ul>
				<br />
			</fieldset>
			<br /><br />
		<?php
		}
		?>
		<fieldset style="background-color:#cecece;opacity:0.9;" class="padding_LR"><legend style="background-color:#cecece;opacity:0.9;border-radius:5px;" class="legend_basique">Mes&nbsp;Annonces:&nbsp;</legend><br />
			<ul>
				<?php
				if($_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				?>
					<li><a href="<?php echo $oCL_page->getPage('historique_annonce')."?rq=toutes"; ?>">Historiques de mes annonces.</a><?php if($toutes_annonces[0]['nb_annonce'] > 0){?>&nbsp;<span class="orange" title="Vous avez fait <?php echo $toutes_annonces[0]['nb_annonce']; ?> annonces jusqu'à maintenant.">[<?php echo $toutes_annonces[0]['nb_annonce']; ?>]</span><?php } ?></li>
					<li><a href="<?php echo $oCL_page->getPage('historique_annonce')."?rq=futures"; ?>">Mes annonces en cours.</a><?php if($annonces_futures[0]['nb_annonce'] > 0){?>&nbsp;<span class="orange" title='Vous avez <?php echo $annonces_futures[0]['nb_annonce']; ?> annonces en cours.
			(Annonces dont la date de début est future)'>[<?php echo $annonces_futures[0]['nb_annonce']; ?>]</span><?php } ?></li>
					<li><a href="<?php echo $oCL_page->getPage('gestion_annonce_goldlive'); ?>">Mes annonces GoldLive.</a><?php if($annonces_goldlive[0]['nb_annonce'] > 0){?>&nbsp;<span class="orange" title="Vous avez <?php echo $annonces_goldlive[0]['nb_annonce']; ?> annonces GoldLive.">[<?php echo $annonces_goldlive[0]['nb_annonce']; ?>]</span><?php } ?></li>
					<li><a href="<?php echo $oCL_page->getPage('creer_annonce'); ?>">Créer une annonce.</a></li>
				<?php
				}
				if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				?>
					<li><a href="<?php echo $oCL_page->getPage('annonces_visitees'); ?>">Annonces débloquées.</a>&nbsp;<?php if(is_array($_SESSION['compte']['annonces_visitées'])){ ?><span class="orange" title="Vous avez débloqué <?php echo (count($_SESSION['compte']['annonces_visitées'])-1); ?> annonces, vous pouvez les consulter à volonté.">[<?php echo (count($_SESSION['compte']['annonces_visitées'])-1); ?>]</span><?php } ?></li>
				<?php
				}
				?>
			</ul>
			<br />
		</fieldset>
		<br /><br />
		
		<fieldset style="background-color:#cecece;opacity:0.9;" class="padding_LR"><legend style="background-color:#cecece;opacity:0.9;border-radius:5px;" class="legend_basique">Mes&nbsp;Contrats:&nbsp;</legend><br />
			<ul>
				<li><a href="<?php echo $oCL_page->getPage('historique_contrat')."?rq=toutes"; ?>">Historiques de mes contrats.</a><?php if($tous_contrats[0]['nb_contrat'] > 0){?>&nbsp;<span class="orange" title="Vous avez effectué <?php echo $tous_contrats[0]['nb_contrat']; ?> contrats jusqu'à maintenant.">[<?php echo $tous_contrats[0]['nb_contrat']; ?>]</span><?php } ?></li>
				<li><a href="<?php echo $oCL_page->getPage('historique_contrat')."?rq=courants"; ?>">Mes contrats en cours.</a><?php if($contrats_courants[0]['nb_contrat'] > 0){?>&nbsp;<span class="orange" title="Vous avez effectué <?php echo $contrats_courants[0]['nb_contrat']; ?> contrats jusqu'à maintenant.">[<?php echo $contrats_courants[0]['nb_contrat']; ?>]</span><?php } ?></li>
			</ul>
			<br />
		</fieldset>
		<br /><br />
		<?php
		if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
		?>
		<fieldset style="background-color:#cecece;opacity:0.9;" class="padding_LR"><legend style="background-color:#cecece;opacity:0.9;border-radius:5px;" class="legend_basique">Mes&nbsp;Prestations:&nbsp;</legend><br />
			<ul>
				<li><h5></h5></li>
				<li><a href="<?php echo $oCL_page->getPage('liste_prestation')."?rq=toutes"; ?>">Mes prestations effectuées.</a><?php if($prestations_effectues[0]['nb_contrat'] > 0){?>&nbsp;<span class="orange" title="Vous avez effectué <?php echo $prestations_effectues[0]['nb_contrat']; ?> prestation(s) jusqu'à maintenant.">[<?php echo $prestations_effectues[0]['nb_contrat']; ?>]</span><?php } ?></li>
				<li><a href="<?php echo $oCL_page->getPage('liste_prestation')."?rq=futures"; ?>">Mes prestations prévues.</a><?php if($prestations_prevues[0]['nb_contrat'] > 0){?>&nbsp;<span class="orange" title="Vous avez <?php echo $prestations_prevues[0]['nb_contrat']; ?> prestation(s) prévue(s).">[<?php echo $prestations_prevues[0]['nb_contrat']; ?>]</span><?php } ?></li>
			</ul>
			<br />
		</fieldset>
		<br /><br />
		<?php
		}
		?>
		<fieldset style="background-color:#cecece;opacity:0.9;" class="padding_LR"><legend style="background-color:#cecece;opacity:0.9;border-radius:5px;" class="legend_basique">Ma&nbsp;Messagerie:&nbsp;</legend><br />
			<ul>
				<li title="Vous avez <?php echo $messages_totaux[0]['nb_message']; ?> messages dans votre boite de réception."><a href="<?php echo $oCL_page->getPage('messagerie'); ?>">Mes messages reçus.</a></li>
				<li><a href="<?php echo $oCL_page->getPage('messagerie'); ?>"></a></li>
			</ul>
			<br />
		</fieldset>
		<br /><br />
		<?php
		if(1){#$_SESSION['pack']['PARRAINAGE_ACTIVE']
		?>
		<fieldset style="background-color:#cecece;opacity:0.9;" class="padding_LR"><legend style="background-color:#cecece;opacity:0.9;border-radius:5px;" class="legend_basique">Parrainage:&nbsp;</legend><br />
			<ul>
				<li><a href="<?php echo $oCL_page->getPage('envoyer_invitations'); ?>">Parrainer des amis.</a></li>
				<li><a href="<?php echo $oCL_page->getPage('filleuls'); ?>">Mes filleuls.</a><?php if($filleuls_totaux[0]['nb_personne'] > 0){?>&nbsp;<span class="orange" title="Vous avez <?php echo $filleuls_totaux[0]['nb_personne']; ?> filleuls.">[<?php echo $filleuls_totaux[0]['nb_personne']; ?>]</span><?php } ?></li>
				<li><a href="<?php echo $oCL_page->getPage('lien_parrainage'); ?>">Obtenir mon lien de parrainage.</a></li>
			</ul>
			<br />
		</fieldset>
		<br /><br />
		<?php
		}
		?>
	


<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>