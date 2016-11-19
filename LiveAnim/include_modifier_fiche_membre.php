<?php
if(!isset($_SESSION)){
	session_start();
}
// ------------------------------- ADMINISTRATION --------------------------------
# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
?>
	<h2>Modification de la fiche personnelle d'un membre:</h2><br />
	<br />

<?php
	if($ID_PERSONNE_ok == 1){
	# L'id_personne transmis est correct, on affiche les données récupérées si elles existent.
	
		if(!empty($personne[0]['ID_PERSONNE'])){
			# Si l'ID_PERSONNE de la personne fournie en GET n'est pas vide c'est que cette personne existe.
			if($personne[0]['TYPE_PERSONNE'] != "Admin"){
				echo "<span class='orange'>/!\ Le code HTML sera automatiquement supprimé des données. /!\</span><br /><br />";
				require_once('include_form_modifier_fiche_membre.php');
			?>
				
				<br />
				<br />
				<?php
				if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				?>
					<br />
					<fieldset class="padding_LR"><legend class="legend_basique">Récapitulatif des IP de connection:</legend><br />
					<table width="100%" border="0" cellspacing="1" cellpadding="1">
						<tr>
							<th scope="col">Date de connexion:</th>
							<th scope="col">IP:</th>
							<th scope="col">Cookie:</th>
							<th scope="col">Cookie détruit:</th>
						</tr>
						<tr><th colspan="4"><hr /></th></tr>
						<?php
						while($ip = $ip_personne->fetch(PDO::FETCH_ASSOC)){
						?>
						<tr <?php if($ip['IP_COOKIE'] != $ip['ID_IP']){echo "class='alert'";} ?>>
							<th><?php echo $ip['DATE_CONNEXION']; ?></th>
							<th><?php echo $ip['ID_IP']; ?></th>
							<th><?php echo $ip['IP_COOKIE']; ?></th>
							<th><?php if($ip['COOKIE_DETRUIT'] == true){echo "<span class='orange'>Oui</span>";}else{echo "Non";} ?></th>
						</tr>
						<tr><th colspan="4"><hr /></th></tr>
						<?php
						}# Fin du while() d'affichage des IPs.
						?>
					</table>
					</fieldset>
			<?php
				}
			#$ip_personne
			}else{
				echo "<span class='orange'>Vous ne pouvez pas modifier les informations d'un administrateur.</span>";
			}
		}else{
			echo "<span class='alert'>Erreur: La requête n'a retourné aucun résultat. Il n'y a pas de membre possédant cet ID.</span>";
		}
	}else{
		echo "<span class='alert'>Erreur: L'id_personne transmit est incorrect.</span>";
	}

}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>