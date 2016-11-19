<?php
if(!isset($_SESSION)){
	session_start();
}

require_once('script_prechargement_inscription.php');

# On définit la page en cours:
$_SESSION['page_actuelle'] = "inscription";

# Si le compte n'a pas encore été crée on le crée et on le classe déconnecté.
if(!isset($_SESSION['compte'])){
	$_SESSION['compte'] = array();
	$_SESSION['compte']['connecté'] = false;
	$_SESSION['compte']['première_visite'] = false;
}

# Si on active un compte.
if(isset($_GET['email']) && isset($_GET['cle_activation'])){
	
	if(filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
		$nb_caracteres = strlen("7849b20c1ec4652f35542e722bb28a09d9ce79bb");# On prend un exemple d'un clé fournie et on compte le nb de caractères.
		if(strlen($_GET['cle_activation']) == $nb_caracteres){
		
			# On appelle une fonction du script_prechargement_inscription.php qui va se charger du reste.
			fx_activer_compte($_GET['email'], $_GET['cle_activation']);
		}else{
			echo "La validation de votre compte a échoué.";
		}
	}else{
		echo "La validation de votre compte a échoué.";
	}

	$_SESSION['compte']['première_visite'] = false;

	
}else if($_SESSION['compte']['première_visite'] == true){
	$_SESSION['compte']['première_visite'] = false;
	echo $_SESSION['compte']['première_visite_message'];
	
}else if($_SESSION['compte']['connecté'] == false){
	# On teste si l'utilisateur est déjà connecté.
	?>
		<img style="" alt="inscription"
 src="images/inscription.png">
<br />
<br />

	Bonjour et bienvenue sur LiveAnim ! Le site privilégié des rencontres entres artistes et organisateurs de soirées en tout genre.

	<br />
	<a href="<?php echo $oCL_page->getPage('cgu'); ?>" target="_blank">Consulter/télécharger les conditions générales d'utilisation.</a>

	<br />
	<?php
		if(isset($_GET['parrain'])){
			$_SESSION['parrain'] = array();
			$_SESSION['parrain']['ID_PARRAIN'] = (int)$_GET['parrain'];	
		}
	?>
	<br />
	<div>
		<p>
			<?php # On affiche les messages d'erreurs/réussite s'il y en a.
				if(isset($_SESSION['inscription']['message'])){
					if($_SESSION['inscription']['message_affiche'] == false){
						echo $_SESSION['inscription']['message'];
						$_SESSION['inscription']['message_affiche'] = true;
					}
				}
			?>
		</p>
	</div>
	<br />
	<div class="formulaire_inscription">
		<img src="images/fond_inscription_haut.jpg" alt="Fond inscription haut" />
		
		<br /><br />
			<form class="formulaire" action="script_inscription.php" method="post" id="form_inscription" name="form_inscription">
				
				<input class="my_input" type="hidden" name="form_inscription_parrain" id="form_inscription_parrain" value="<?php if(isset($_SESSION['parrain']['ID_PARRAIN'])){ echo $_SESSION['parrain']['ID_PARRAIN'];} ?>" />
				<?php if(isset($_SESSION['parrain']['ID_PARRAIN']) && $_SESSION['parrain']['ID_PARRAIN'] != 0){echo "Vous allez parrainer un ami lors de votre inscription, il vous en remercie !";}else if(isset($_SESSION['parrain']['ID_PARRAIN']) && $_SESSION['parrain']['ID_PARRAIN'] == 0){echo "Le numéro du parrain est invalide, triche ou erreur ? :) Contactez nous si vous n'y êtes pour rien."; }else{echo "<span class='petit'>Vous ne parrainez personne.</span>";} ?>
				<br /><br /><img class="fright" src="images/disco ball.png" alt="Disco Ball" />

				<label for="form_inscription_login"><span class="alert">*</span> Pseudo:</label><br />
				&nbsp;&nbsp;<input onblur="fx_verif_champ_simple('pseudo', 'form_inscription_login');" class="my_input" type="text" name="form_inscription_login" id="form_inscription_login" value="<?php if(isset($_SESSION['inscription']['login'])){ echo $_SESSION['inscription']['login'];} ?>" autofocus /><br />
				<div id="pseudo"></div>
				<br />
				<label for="form_inscription_nom"><span class="alert">*</span> Nom:</label><br />
				&nbsp;&nbsp;<input onblur="fx_verif_champ_simple('nom', 'form_inscription_nom');" class="my_input" type="text" name="form_inscription_nom" id="form_inscription_nom" value="<?php if(isset($_SESSION['inscription']['nom'])){ echo $_SESSION['inscription']['nom'];} ?>" />
				<br />
				<div id="nom"></div>
				<br />
				<label for="form_inscription_prenom"><span class="alert">*</span> Prénom:</label><br />
				&nbsp;&nbsp;<input onblur="fx_verif_champ_simple('prenom', 'form_inscription_prenom');" class="my_input" type="text" name="form_inscription_prenom" id="form_inscription_prenom" value="<?php if(isset($_SESSION['inscription']['prenom'])){ echo $_SESSION['inscription']['prenom'];} ?>" />
				
				<br />
				<div id="prenom"></div>
				<br />
				<span class="alert">*</span> Civilité:<br />
				&nbsp;&nbsp;<select class="my_input" name="form_inscription_civilite" id="form_inscription_civilite">
					<option value="Mr" <?php if(isset($_SESSION['inscription']['civilite'])){ if($_SESSION['inscription']['civilite'] == "Mr"){echo "selected='selected'";}} ?>>Monsieur</option>
					<option value="Mme" <?php if(isset($_SESSION['inscription']['civilite'])){ if($_SESSION['inscription']['civilite'] == "Mme"){echo "selected='selected'";}} ?>>Madame</option>
					<option value="Mlle" <?php if(isset($_SESSION['inscription']['civilite'])){ if($_SESSION['inscription']['civilite'] == "Mlle"){echo "selected='selected'";}} ?>>Mademoiselle</option>
				</select>
				
				<br /><br />
				<span class="alert">*</span> Je suis un :<br />
				&nbsp;&nbsp;<select class="my_input" name="form_inscription_type_personne" id="form_inscription_type_personne">
					<option value="Prestataire" <?php if(isset($_SESSION['inscription']['type_personne'])){ if($_SESSION['inscription']['type_personne'] == "Prestataire"){echo "selected='selected'";}} ?>>Prestataire / Artiste</option>
					<option value="Organisateur" <?php if(isset($_SESSION['inscription']['type_personne'])){ if($_SESSION['inscription']['type_personne'] == "Organisateur"){echo "selected='selected'";}} ?>>Organisateur de soirée</option>
				</select>
				
				<br /><br />
				<label for="form_inscription_mdp"><span class="alert">*</span> Mot de passe:</label><br />
				&nbsp;&nbsp;<input onchange="fx_verif_champ_double('mdp2', 'form_inscription_mdp', 'form_inscription_mdp2');" onblur="fx_verif_champ_simple('mdp', 'form_inscription_mdp');" class="my_input" type="password" name="form_inscription_mdp" id="form_inscription_mdp" value="<?php if(isset($_SESSION['inscription']['mdp'])){ echo $_SESSION['inscription']['mdp'];} ?>" />
				
				<br />
				<div id="mdp"></div>
				<br />
				<label for="form_inscription_mdp2"><span class="alert">*</span> Retapez votre mot de passe:</label><br />
				&nbsp;&nbsp;<input onchange="fx_verif_champ_double('mdp2', 'form_inscription_mdp', 'form_inscription_mdp2');" class="my_input" type="password" name="form_inscription_mdp2" id="form_inscription_mdp2" value="<?php if(isset($_SESSION['inscription']['mdp2'])){ echo $_SESSION['inscription']['mdp2'];} ?>" />
				
				<br />
				<div id="mdp2"></div>
				<br />
				<label for="form_inscription_email"><span class="alert">*</span> Adresse e-mail <span class="petit">(Valide !)</span>: </label><br />
				&nbsp;&nbsp;<input onblur="fx_verif_champ_email('email', 'form_inscription_email');fx_verif_champ_double('email2', 'form_inscription_email', 'form_inscription_email2');" class="my_input" type="text" name="form_inscription_email" id="form_inscription_email" value="<?php if(isset($_SESSION['inscription']['email'])){ echo $_SESSION['inscription']['email'];} ?>" />
				
				<br />
				<div id="email"></div>
				<br />
				<label for="form_inscription_email2"><span class="alert">*</span> Retapez votre adresse e-mail:</label><br />
				&nbsp;&nbsp;<input onblur="fx_verif_champ_email('email2', 'form_inscription_email2');fx_verif_champ_double('email2', 'form_inscription_email', 'form_inscription_email2');" class="my_input" type="text" name="form_inscription_email2" id="form_inscription_email2" value="<?php if(isset($_SESSION['inscription']['email2'])){ echo $_SESSION['inscription']['email2'];} ?>" />
				
				<br />
				<div id="email2"></div>
				<br />
				<div id="cgu">
				<span class="alert">*</span> Vous devez accepter les <a href="<?php echo $oCL_page->getPage('cgu'); ?>" target="_blank">Conditions générales d'utilisation</a>:<br />
				&nbsp;&nbsp;<select class="my_input" name="form_inscription_cgu" id="form_inscription_cgu"><option value="1" <?php if(isset($_SESSION['inscription']['cgu']) && $_SESSION['inscription']['cgu'] == "1"){echo "selected='selected'";} ?>>J'accepte</option><option value="0" <?php if(!isset($_SESSION['inscription']['cgu'])){echo "selected='selected'";}else{if($_SESSION['inscription']['cgu'] == "0"){echo "selected='selected'";}} ?>>Je refuse</option></select>
				</div><br /><br />
				
				<u>Options:</u><br />
				<input type="checkbox" id="form_inscription_newsletter" name="form_inscription_newsletter" <?php if(isset($_SESSION['inscription']['newsletter'])){ if($_SESSION['inscription']['newsletter'] == true){echo "checked='checked'";}}else{echo "checked='checked'";} ?>> <label for="form_inscription_newsletter">Je souhaite recevoir les Newsletter de LiveAnim. <span class="petit">(Conseillé !)</span></label><br />
				<input type="checkbox" id="form_inscription_offres_annonceurs" name="form_inscription_offres_annonceurs" <?php if(isset($_SESSION['inscription']['offres_annonceurs'])){ if($_SESSION['inscription']['offres_annonceurs'] == true){echo "checked='checked'";}} ?>> <label for="form_inscription_offres_annonceurs">Je souhaite recevoir les offres des annonceurs de LiveAnim.</label><br />
				<br /><br />
				
				S'il vous plaît, où avez vous connu notre site ?<br />
				<select class="my_input" name="form_inscription_connaissance_site" id="form_inscription_connaissance_site">
					<?php # On autogénère toutes les réponses
						foreach($connaissance_site as $key=> $connaissance_site_actuel){
					?>
						<option value="<?php echo $connaissance_site_actuel['ID_TYPES']; ?>" <?php if(isset($_SESSION['inscription']['connaissance_site'])){if($connaissance_site_actuel['ID_TYPES'] == $_SESSION['inscription']['connaissance_site']){echo "selected='selected'";}}else if($connaissance_site_actuel['ID_TYPES'] == "Facebook"){echo "selected=''selected";} ?>><?php echo $connaissance_site_actuel['ID_TYPES']; ?></option>
					<?php
						}
					?>
				</select>
				<br />
				
				<span class="fright"><span class="alert">* </span>: Champ obligatoire&nbsp;</span>
				<br /><br />
				<center>
					<input type="image" src="images/valider.png" alt="Valider" name="btn_form_inscription_valider" id="btn_form_inscription_valider" value="Valider l'inscription" />
				</center>
				
			</form>
			<img src="images/fond_inscription_bas.jpg" alt="Fond inscription bas" />
		
	</div>
	<br />
	<br />
	<br />
<?php
}else{
?>
Vous êtes déjà connecté <?php echo $_SESSION['compte']['PSEUDO']; ?>, redirection en cours.
<?php
header ("Refresh: 1;URL=".$oCL_page->getPage('accueil'));
}