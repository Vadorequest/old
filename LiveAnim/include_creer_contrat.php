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
	<h2>Créer un contrat pour l'annonce <a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$annonce[0]['ID_ANNONCE']; ?>"><?php echo $annonce[0]['TITRE']; ?></a></h2><br />
	<br />
	<?php
	if(isset($_SESSION['creer_contrat']['message']) && $_SESSION['creer_contrat']['message_affiche'] == false){
		echo $_SESSION['creer_contrat']['message'];
		$_SESSION['creer_contrat']['message_affiche'] = true;
	}

	?>
	<fieldset class="padding_LR"><legend class="legend_basique">Création d'un contrat</legend>
		Vous vous apprêtez à créer un contrat concernant l'annonce N°<?php echo $annonce[0]['ID_ANNONCE']; ?>.<br />
		&nbsp;&nbsp;&nbsp;<img id="img_plus_moins" onclick="fx_affiche('informations_contrat', 'img_plus_moins');" src="<?php echo $oCL_page->getImage('petit_plus'); ?>" alt="Afficher/Cacher l'aide" title="Afficher/Cacher l'aide" /><br />
		
		<div class="justify" id="informations_contrat"><br />
			<br />
			Notre système d'édition de contrat est simple.<br />
			<br />
			Vous créez un contrat concernant une annonce qui vous intéresse et pour laquelle vous proposez vos services.<br />
			<br />
			Vous pouvez modifier certains champs, notamment les dates ainsi que votre rémunération.<br />
			<span class="petit">(Prenez bien en compte que dans le cas de grosses festivités, le budget indiqué peut être le budget total prévu pour tous las artistes demandés !)</span>
			<br />
			L'organisateur est aussitôt prévenu par e-mail, le contrat lui est envoyé par la messagerie du site.<br />
			<br />
			Il peut alors accepter, refuser ou annuler le contrat. <br />
			- L'acceptation peut se faire par les deux personnes mais uniquement si les valeurs (dates, rémunération) fournies restent inchangées.<br />
			- L'annulation d'un contrat est définitif. <br />
			- Le refus passe le statut du contrat en attente jusqu'à ce que l'autre personne donne son avis. (Validation, refus ou annulation).<br />
			<br />
			Une fois un contrat validé, il est toujours possible de l'annuler mais plus d'en modifier les clauses.<br />
			<br />
			Si vous avez une indisponibilité quelconque qui fait que vous ne pourrez pas respecter cet accord veuillez l'annuler le plus rapidement possible afin que l'autre partie puisse prendre ses dispositions.<br />
			<br />
			<p class="orange">
				Si des frais ont été engagés avant la prise de connaissance de l'annulation alors les deux parties doivent s'arranger entre elles.<br />
				Nous mettons à votre disposition tous les contrats afin que -si litige il y a - vous possédiez une pièce justificative.<br />
				<span class="petit">Notez bien que les contrats sous format PDF évoluent en fonction de vos actions une fois le contrat validé (Notations, annulation, ...).<br />
				N'hésitez pas à les télécharger. (Si votre pack le permet !)</span><br />
			</p>
		</div>
		<?php
		require_once('include_form_ajouter_modifier_contrat.php');
		?>
	</fieldset>
	<script type="text/javascript">
		initialiser_div("informations_contrat");
	</script>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>