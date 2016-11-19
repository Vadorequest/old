<?php
$_SESSION['page_actuelle'] = "recuperation_mdp";

require_once('couche_metier/CL_page.php');
$oCL_page = new CL_page();

if(isset($_SESSION['compte']['connecté']) && $_SESSION['compte']['connecté'] == true){
	 ?>
	 Vous êtes déjà connecté. Vous ne pouvez pas effectuer une récupération de mot de passe en étant connecté.
	 
	 <?php
	 header ("Refresh: 1;URL=".$oCL_page->getPage('accueil'));
	
}else{
?>
<h2>Récupération de mot de passe:</h2>

<h4><u>Comment ça marche ?</u></h4>
Le principe de récupération de votre mot de passe est simple.<br />
Vous devez entrer votre pseudo et votre adresse e-mail dans les cases indiquées.<br />
Le système vérifiera ces informations et si elles sont exactes alors un e-mail vous sera envoyé avec votre mot de passe.<br /><br />

<?php
if(isset($_SESSION['récupération']['message'])){
	if($_SESSION['récupération']['message_affiche'] == false){
		echo $_SESSION['récupération']['message'];
		$_SESSION['récupération']['message_affiche'] = true;
	}
}

?>
<br />
<fieldset class="formulaire"><legend class="legend_basique">Formulaire de récupération de votre mot de passe:</legend>
	<form action="script_recuperation_mdp.php" method="post" name="form_recuperation_mdp" id="form_recuperation_mdp">
		<br />
		<span class="alert">*</span><label for="form_recuperation_mdp_pseudo">Pseudo:</label><br />
		<input type="text" name="form_recuperation_mdp_pseudo" id="form_recuperation_mdp_pseudo" class="my_input" /><br  />
		<br />
		
		<span class="alert">*</span><label for="form_recuperation_mdp_email">E-mail:</label><br />
		<input type="text" name="form_recuperation_mdp_email" id="form_recuperation_mdp_email" class="my_input" /><br  />
		<br />
		
		<center>
			<input type="image" src="images/valider.jpg" name="btn_form_recuperation_mdp_valider" id="btn_form_recuperation_mdp_valider" class="art-button" value="Valider" />
		</center>
		</form>
</fieldset>
<?php
}
?>