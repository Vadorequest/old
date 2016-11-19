<!-- Affiche un lien invisible à l'utilisateur qui amène vers la page de tous les liens du site. -->
<div style="position: absolute;z-index:-1000;height:0px;width:0px;"><a href="liens.php">Liens</a></div>
<!-- Affiche le lien du facebook, j'aime. -->
<div class="fb-like" data-href="https://www.facebook.com/pages/LiveAnim/156524991110318" data-send="true" data-layout="button_count" data-width="100" data-show-faces="false" data-colorscheme="dark" data-font="lucida grande"></div><br />
<br />
<div id="fb-root">
	<script>
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) {return;}
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1&appId=219360858133211";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
</div>
<div class="padding_LR">
	Vous êtes artiste ou organisateur, nous vous mettons en relation en un clic. 
	<br />Nous nous occupons de tout à moindre coût.</div>
<div class="adv-zzz">
	<?php
	if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin" || $_SESSION['compte']['PSEUDO'] == "Vadorequest"){
		echo "<h6><a href='".$oCL_page->getPage('administration')."'>Administration</a></h6>";	
	}else{
		echo "<br />";
	}
	
	if($_SESSION['compte']['connecté'] == true){
	?>
		
		Bonjour <?php echo $_SESSION['compte']['PSEUDO']; ?><br />
		<br />
		<h4><form action="<?php echo $oCL_page->getPage("gestion_compte"); ?>" method="post"><input type="image" src="<?php echo $oCL_page->getImage('mon_compte'); ?>" alt="Accéder à mon compte" title="Accéder à mon compte" /></form></h4>
		
		<a class="fright" style="padding-right:45px;" href="<?php echo $oCL_page->getPage("deconnexion.php"); ?>" >Déconnexion</a>
	<?php
	}else if($_SESSION['compte']['connecté'] == false){
	?>
		<!-- <a href="script_connexion_facebook.php"><img alt="Connectez vous depuis facebook !" title="Connectez vous depuis facebook !" src="<?php #echo $oCL_page->getImage('facebook'); ?>"></a> -->
		<form action="script_connexion.php" method="post" id="form_connexion" name="form_connexion">
			<br />
			<table border="0" cellspacing="1" cellpadding="3">
			<tr>
				<td align="left" width="36%"><b>Pseudo</b>&nbsp;:</td>
				<td width="40%">
					<input size="12" type="text" name="form_connexion_pseudo" id="form_connexion_pseudo" size="15" maxlength="20" tabindex="1" autofocus="1"  />
				</td>
				<td width="24%" rowspan="2">&nbsp;<input type="image" height="32px" width="32px" src="<?php echo $oCL_page->getImage('valider2'); ?>" alt="Connexion" title="Connexion" id="btn_form_connexion_valider" name="btn_form_connexion_valider" value="Envoyer" tabindex="3" /></td>			</tr>
			<tr>
				<td align="left"><b>Mot&nbsp;de&nbsp;passe</b>&nbsp;:</td>
				<td>
					<input size="12" type="password" name="form_connexion_mdp" id="form_connexion_mdp" size="15" tabindex="2" />
				</td>
			</tr>
		</table>
		<br />		
		<div style="padding-right:40px;" align="right" >
			<span class='petit'><a class="" href="<?php echo $oCL_page->getPage('recuperation_mdp'); ?>" tabindex="4">Mot de passe oublié ?</a></span>
		</div>
		
	<?php
		}
	?>
		</form>
</div>
