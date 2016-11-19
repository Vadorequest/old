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

	<img style="" alt="Modifier mot de passe"
 src="images/modification-pass.png">
<br />
<br />
	<?php
		if(isset($_SESSION['modifier_mdp']['message']) && $_SESSION['modifier_mdp']['message_affiche'] == false){
			echo $_SESSION['modifier_mdp']['message'];
			$_SESSION['modifier_mdp']['message_affiche'] = true;
		}
	?>
	<br />
	<form class="formulaire" action="script_modifier_mdp.php" method="post" name="form_modifier_mdp" id="form_modifier_mdp" >
		Rentrez votre mot de passe actuel:<br />
		<input onblur="fx_verif_champ_simple('ancien_mdp', 'form_modifier_mdp_ancien_mdp');" type="password" name="form_modifier_mdp_ancien_mdp" id="form_modifier_mdp_ancien_mdp" /><br />
		<div id="ancien_mdp"></div>
		<br />
		Rentrez votre nouveau mot de passe:<br />
		<input onblur="fx_verif_champ_simple('new_mdp', 'form_modifier_mdp_nouveau_mdp');fx_verif_champ_double('new_mdp2', 'form_modifier_mdp_nouveau_mdp', 'form_modifier_mdp_nouveau_mdp_bis');" type="password" name="form_modifier_mdp_nouveau_mdp" id="form_modifier_mdp_nouveau_mdp" /><br />
		<div id="new_mdp"></div>
		<br />
		Répétez votre nouveau mot de passe:<br />
		<input onblur="fx_verif_champ_double('new_mdp2', 'form_modifier_mdp_nouveau_mdp', 'form_modifier_mdp_nouveau_mdp_bis');" type="password" name="form_modifier_mdp_nouveau_mdp_bis" id="form_modifier_mdp_nouveau_mdp_bis" /><br />
		<div id="new_mdp2"></div>
		<br />
		<span class="alert fright">* Tous les champs sont obligatoires.</span><br />
		<br />
		<center>
			<input type="image" src="images/valider.png" alt="Valider" title="Valider" name="btn_form_modifier_mdp_valider" id="btn_form_modifier_mdp_valider" />
		</center>
	</form>
<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>