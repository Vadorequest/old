<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){

	?>
	<h2>Page d'activation des comptes:</h2>

	Voici une liste de tous les comptes qui ont été crées sur une IP déjà utilisée.<br />
	En général il y aura autant d'IP que d'IP cookie. Si jamais il y a plus d'IP cookie que d'IP alors c'est que le membre change d'IP (box) sans vider ses cookies.<br />
	Dans ce cas, si l'IP est différente de celle du cookie alors la ligne devient rouge.<br />
	<br />
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
	  <tr class="valide">
			<th width="15%" scope="col">Pseudo</th>
			<th width="15%" scope="col">IP</th>
			<th width="15%" scope="col">IP <span class="petit">(cookie)</span></th>
			<th width="30%" scope="col">Date de création</th>
			<th width="5%" scope="col">Activer</th>
			<th width="5%" scope="col">Bannir</th>
	    </tr>
	<?php
	foreach($comptes_inactifs as $key=> $compte_inactif){
		if($compte_inactif['VALIDE'] == true){
			echo "<span class='alert'>";# Couleur d'alerte si un compte possède encore une clé d'activation en étant valide.
		}
	?>
		<tr><td colspan="6"><br /><hr /></td></tr>
		<tr <?php if($compte_inactif['ID_IP'] != $compte_inactif['IP_COOKIE']){echo "class='alert'";}else{echo "class='valide'";} ?>>
			<th><span title="ID_PERSONNE: N°<?php echo $compte_inactif['ID_PERSONNE']; ?>"><a href="<?php echo $oCL_page->getPage('modifier_fiche_membre')."?id_personne=".$compte_inactif['ID_PERSONNE']; ?>"><?php echo $compte_inactif['PSEUDO']; ?></a></span></th>
			<th><?php echo $compte_inactif['ID_IP']; ?></th>
			<th><?php echo $compte_inactif['IP_COOKIE']; ?></th>
			<th title="<?php echo $compte_inactif['DATE_CONNEXION']; ?>"><?php echo $compte_inactif['DATE_CONNEXION_simple']; ?></th>
			<th>
				<a href="<?php echo $oCL_page->getPage('inscription', 'absolu')."?email=".$compte_inactif['EMAIL']."&cle_activation=".$compte_inactif['CLE_ACTIVATION']; ?>"><img src="images/ok.gif" alt="" title="" /></a>
			</th>
			<th><a href="<?php echo $oCL_page->getPage('bannir_membre'); ?>?id_personne=<?php echo $compte_inactif['ID_PERSONNE']; ?>"><img src="images/supprimer_personne_petit.png" alt="Bannir <?php echo $compte_inactif['PSEUDO']; ?>" title="Bannir <?php echo $compte_inactif['PSEUDO']; ?>" /></a></th>
		</tr>
		<tr><td colspan="6">Liste des IP du compte <?php echo $compte_inactif['PSEUDO']; ?>: <span class="valide">(IP simple)</span></td></tr>
		<?php
		$infos_ID_IP = fx_recuperer_infos_by_ID_IP($compte_inactif['ID_IP']);
		$infos_IP_COOKIE = fx_recuperer_infos_by_IP_COOKIE($compte_inactif['IP_COOKIE']);
		foreach($infos_ID_IP as $key2=>$info_ID_IP){
			# On traite les données.
			$info_ID_IP['DATE_CONNEXION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($info_ID_IP['DATE_CONNEXION'], true, false, 'en', 'fr'), true, 'fr');
			$info_ID_IP['DATE_CONNEXION_simple'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($info_ID_IP['DATE_CONNEXION'], true, false, 'fr', 'fr'), true, 'fr', true, false, true);
			
			if($info_ID_IP['ID_PERSONNE'] != $compte_inactif['ID_PERSONNE']){
		?>
				<tr <?php if($info_ID_IP['ID_IP'] != $info_ID_IP['IP_COOKIE']){echo "class='alert'";}else{echo "class='petit'";} ?>>
					<th><span title="ID_PERSONNE: N°<?php echo $info_ID_IP['ID_PERSONNE']; ?>"><?php echo $info_ID_IP['PSEUDO']; ?></span></th>
					<th><?php echo $info_ID_IP['ID_IP']; ?></th>
					<th><?php echo $info_ID_IP['IP_COOKIE']; ?></th>
					<th title="<?php echo $info_ID_IP['DATE_CONNEXION']; ?>"><?php echo $info_ID_IP['DATE_CONNEXION_simple']; ?></th>
					<th><?php if($info_ID_IP['VISIBLE'] == true){echo "Activé";}else{echo "Inactif";} ?></th>
					<?php if($info_ID_IP['ID_IP'] != $info_ID_IP['IP_COOKIE']){echo "<th>/!\</th>";} ?>
				</tr>
		<?php
			}
		}# Fin du foreach infos_ID_IP
		?>
		<tr><td colspan="6">Liste des IP du compte <?php echo $compte_inactif['PSEUDO']; ?>: <span class="alert">(IP par cookie)</span></td></tr>
		<?php
		foreach($infos_IP_COOKIE as $key2=>$info_IP_COOKIE){
			# On traite les données.
			$info_IP_COOKIE['DATE_CONNEXION'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($info_IP_COOKIE['DATE_CONNEXION'], true, false, 'en', 'fr'), true, 'fr');
			$info_IP_COOKIE['DATE_CONNEXION_simple'] = $oCL_date->fx_formatter_heure($oCL_date->fx_ajouter_date($info_IP_COOKIE['DATE_CONNEXION'], true, false, 'fr', 'fr'), true, 'fr', true, false, true);
			
			if($info_IP_COOKIE['ID_PERSONNE'] != $compte_inactif['ID_PERSONNE']){
		?>
				<tr <?php if($info_IP_COOKIE['ID_IP'] != $info_IP_COOKIE['IP_COOKIE']){echo "class='alert'";}else{echo "class='petit'";} ?>>
					<th><span title="ID_PERSONNE: N°<?php echo $info_IP_COOKIE['ID_PERSONNE']; ?>"><?php echo $info_IP_COOKIE['PSEUDO']; ?></span></th>
					<th><?php echo $info_IP_COOKIE['ID_IP']; ?></th>
					<th><?php echo $info_IP_COOKIE['IP_COOKIE']; ?></th>
					<th title="<?php echo $info_ID_IP['DATE_CONNEXION']; ?>"><?php echo $info_IP_COOKIE['DATE_CONNEXION_simple']; ?></th>
					<th><?php if($info_IP_COOKIE['VISIBLE'] == true){echo "Activé";}else{echo "Inactif";} ?></th>
					<?php if($info_IP_COOKIE['ID_IP'] != $info_IP_COOKIE['IP_COOKIE']){echo "<th>/!\</th>";} ?>
				</tr>
		<?php
			}
		}# Fin du foreach infos_IP_COOKIE
		if($compte_inactif['VALIDE'] == true){
			echo "</span>";
		}
	}# Fin du foreach
		?>
	</table>









<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>