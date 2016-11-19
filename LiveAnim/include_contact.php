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
<img style="" alt="contactez nous"
 src="images/contactez-nous.png">
<br />
<br />
<?php
if(isset($_SESSION['contact']['message']) && $_SESSION['contact']['message_affiche'] == false){
	echo $_SESSION['contact']['message'];
	$_SESSION['contact']['message_affiche'] = true;
	}
?>
<br />
<fieldset class="padding_LR"><legend class="legend_basique">Formulaire de contact:</legend>
	<br />
	Vous rencontrez un problème ? Un bug ? Ou bien vous souhaitez simplement obtenir des informations ?<br />
	Peu importe la raison, sélectionnez la puis indiquez nous précisément votre problème, notre équipe fera tout son possible pour y répondre dans les plus brefs délais.<br />
	<br />
	Contactez la HotLine LiveAnim <b>gratuitement si votre abonnement téléphonique<br /> vous le permet</b> depuis votre poste fixe au : <br /><br />
	<b>09 81 39 57 28</b> ou au <b>04 69 31 10 30</b>.<br />
	(Entre 13h et 18h)<br />
	<br />
	<form class="formulaire" action="script_contact.php" method="post" name="form_contact">
		<?php
		# Si le membre n'est pas connecté alors on affiche des champs supplémentaires.
		if(!$_SESSION['compte']['connecté']){
		?>
			<label for="form_contact_pseudo"><span class="alert">* </span>Indiquez votre pseudo:</label><br />
			<input onblur="fx_verif_champ_simple('pseudo', 'form_contact_pseudo');" type="text" name="form_contact_pseudo" id="form_contact_pseudo" value="<?php if(isset($_SESSION['contact']['pseudo'])){echo $_SESSION['contact']['pseudo'];} ?>" size="40" required /><br />
			<div id="pseudo"></div>
			<br />
			<label for="form_contact_email"><span class="alert">* </span>Indiquez votre email:</label><br />
			<input onblur="fx_verif_champ_email('email', 'form_contact_email');" type="email" name="form_contact_email" id="form_contact_email" value="<?php if(isset($_SESSION['contact']['email'])){echo $_SESSION['contact']['email'];} ?>" size="40" required /><br />
			<div id="email"></div>
			<br />			
		<?php
		}
		?>
		<label for="form_contact_raison"><span class="alert">* </span>Sélectionnez la raison:</label><br />
		<select name="form_contact_raison" id="form_contact_raison" required>
			<option value="1" <?php if(isset($_SESSION['contact']['raison']) && $_SESSION['contact']['raison'] == 1){echo "selected='selected'";} ?>>Je n'arrive pas à me connecter</value>
			<option value="2" <?php if(isset($_SESSION['contact']['raison']) && $_SESSION['contact']['raison'] == 2){echo "selected='selected'";} ?>>Mon compte a été banni</value>
			<option value="3" <?php if(isset($_SESSION['contact']['raison']) && $_SESSION['contact']['raison'] == 3){echo "selected='selected'";} ?>>Mon compte a été suspendu temporairement</value>
			<option value="4" <?php if(isset($_SESSION['contact']['raison']) && $_SESSION['contact']['raison'] == 4){echo "selected='selected'";} ?>>J'ai rencontré un bogue sur le site</value>
			<option value="5" <?php if(isset($_SESSION['contact']['raison']) && $_SESSION['contact']['raison'] == 5){echo "selected='selected'";} ?>>Le site s'affiche mal</value>
			<option value="6" <?php if(isset($_SESSION['contact']['raison']) && $_SESSION['contact']['raison'] == 6){echo "selected='selected'";} ?>>J'ai trouvé une faille de sécurité</value>
			<option value="7" <?php if(isset($_SESSION['contact']['raison']) && $_SESSION['contact']['raison'] == 7){echo "selected='selected'";} ?>>Autres ...</value>
		</select><br />
		<br />
		<label for="form_contact_descriptif"><span class="alert">* </span>Décrivez votre problème:</label><br />
		<textarea onblur="fx_verif_champ_simple('descriptif', 'form_contact_descriptif');" rows="12" cols="80" id="form_contact_descriptif" name="form_contact_descriptif" required> <?php if(isset($_SESSION['contact']['description'])){echo $_SESSION['contact']['description'];} ?></textarea><br />
		<div id="descriptif"></div>
		<br />
		<span class="fright" style="padding-right:30px;">* Obligatoire</span><br />
		<center><input type="image" src="<?php echo $oCL_page->getImage('valider'); ?>" alt="Valider" title="Valider" /></center><br />
		<br />
	</form>
	
	
</fieldset>