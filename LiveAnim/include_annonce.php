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
	<h2><?php echo $annonce_courante[0]['TITRE'] ?></h2><br />
	<?php
	if(isset($_SESSION['annonce']['message']) && $_SESSION['annonce']['message_affiche'] == false){
		echo $_SESSION['annonce']['message'];
		$_SESSION['annonce']['message_affiche'] = true;
	}
	?>
	<?php
	if($id_annonce_ok){# On affiche le contenu de l'annonce.
	?>
		<?php
		if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
		?>
		<br />
		<fieldset class="padding_LR"><legend class="legend_basique">Modération</legend><br />
			<br />
			<center>
				<a href="<?php echo $oCL_page->getPage('modifier_fiche_annonce_by_admin')."?id_annonce=".$annonce_courante[0]['ID_ANNONCE']; ?>">Modifier l'annonce.</a><br />
			</center>
			<br />
		</fieldset>
		<?php
		}
		?>
		<br />
		<?php
		# Si c'est la personne qui a crée l'annonce qui regarde l'annonce alors il peut la passer goldlive.
		if($_SESSION['compte']['ID_PERSONNE'] == $annonce_courante[0]['ID_PERSONNE']){
			# Si l'annonce n'est pas déjà goldlive
			if(!$annonce_courante[0]['GOLDLIVE']){
				require_once('include_acheter_annonce_goldlive.php');
			}else{
				echo "<center class='rose'>Cette annonce possède le statut GOLDLIVE et est donc placée en tête des résultats de recherche&nbsp;!</center><br /><br />";
			}
		}
		echo "<br /><br />";
		?>

		<fieldset class="padding_LR"><legend class="legend_basique">Informations relative à l'annonce</legend><br />
			<br />
			<center>
				<span class="rose">Cette annonce a été créée le <b><?php echo $annonce_courante[0]['DATE_ANNONCE']; ?></b>.</span><br />
				<br />
				<span class="rose"><u>Évènement:</u></span> <b><?php echo $annonce_courante[0]['TYPE_ANNONCE'] ?></b>.<br />
				<br />
				La représentation débute le <b><?php echo $annonce_courante[0]['DATE_DEBUT'] ?></b> et se termine le <b><?php echo $annonce_courante[0]['DATE_FIN'] ?></b>.<br />
				<br />
				<span class="rose">Le budget initial prévu est de <b><?php echo $annonce_courante[0]['BUDGET'] ?>€.</b></span><br />
				<?php
				if($annonce_courante[0]['NB_CONVIVES'] != 0){
				?>
					<span class="rose">La représentation se fera devant <b><?php echo $annonce_courante[0]['NB_CONVIVES'] ?> personnes.</b></span><br />
				<?php
				}else{
				?>
					<span class="petit">(Le nombre d'invité n'a pas été précisé)</span><br />
				<?php
				}
				?>
				<br />
			</center>
			<div class="justify">
				<span class="rose"><u>Description:</u></span><br />
				<?php echo $annonce_courante[0]['DESCRIPTION'] ?><br />
				<br />
				<span class="rose"><u>Artistes recherchés:</u></span><br />
				<?php echo $annonce_courante[0]['ARTISTES_RECHERCHES'] ?><br />
			</div>
			<br />
			<?php
			if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin" || $_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
			?>
				<center>
					Cette annonce vous intéresse ? Faites un <b>contrat</b> !<br />
					<a <?php if (!$_SESSION['compte']['connecté']){echo ""; }else{ ?> href="<?php echo $oCL_page->getPage('creer_contrat')."?id_annonce=".$annonce_courante[0]['ID_ANNONCE']; ?>" <?php } ?>>Faire un contrat</a><br />
					<br />
				</center>
			<?php
			}
			?>
		</fieldset>
		<br />
		<br />
		<fieldset style="" class="padding_LR"><legend class="legend_basique">Informations supplémentaires:</legend><br />
		<br />
		<b><u>Trajet entre vous et le lieu de prestation:</u></b><br />
		<br />
		<input type="hidden" name="origin" id="origin" value="<?php echo $personne_courante[0]['ADRESSE'].", ".$personne_courante[0]['CP']." ".$personne_courante[0]['VILLE']; ?>" />
		<?php
		if($afficher_infos_contact){
		?>
		<input type="hidden" name="destination" id="destination" value="<?php echo $annonce_courante[0]['ADRESSE'].", ".$annonce_courante[0]['CP']." ".$annonce_courante[0]['VILLE']; ?>" />
		<?php
		}else{
		?>
		<input type="hidden" name="destination" id="destination" value="<?php echo $annonce_courante[0]['CP']." ".$annonce_courante[0]['VILLE']; ?>" />
		<?php
		}
		?>
		<div id="map" style="position:relative;height:400px;width:100%;">
		
		</div>
		<br />
		<div id="map_infos">
			<br />
			<span class="rose">La carte officielle avec d'autres options.<span class="petit"> (impression, chemin le moins cher, etc...)</span>:</span><br />
			<center>
				<a href="http://maps.google.fr/maps?f=d&source=s_d&saddr=<?php echo $personne_courante[0]['ADRESSE']; ?>,+<?php echo $personne_courante[0]['VILLE']; ?>&daddr=<?php echo $annonce_courante[0]['VILLE']; ?>">Carte officielle !</a>
			</center>
			<br />
		</div>
		<div id="map_erreur">
		
		</div>
		<script type="text/javascript">
			initialiser_GMap();
			calculate();
			verifier_donnees();
		</script>
		</fieldset>
	<?php
	}else{
		echo "<span class='orange'>Cette annonce est invalide, réessayez.</span>";
	}
	?>