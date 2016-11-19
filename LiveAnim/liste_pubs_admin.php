<?php
# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
require_once('include_header.php');
?>
<title>Administration: Gestion des pubs</title>
</head>
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
	
	require_once('script_prechargement_liste_pubs_admin.php');
	
	# On définit la page courante:
	$_SESSION['page_actuelle'] = "liste_pubs_admin";

?>
	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
						require_once('include_connexion.php');
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
						require_once('include_menu_principal.php');
						require_once('include_slider.php');
					?>
				</div>
			</div>
		</div>
		<div id="content">
			<div class="wrapper">
				<div class="aside">
					<div class="indent">
						<?php
						/* Partie qui peut prendre les include: 
								include_annonces_gold.php
								include_artiste.php
								include_partenaire.php
								include_nouveaux_artistes.php
								include_derniers_projets.php
						*/
							require_once('include_menuv_admin.php');
						?>					
				   </div>
				</div>
				<div class="mainContent maxheight">
					<div class="indent">
						<?php
							/* Partie qui peut prendre les include: 
									include_artistes_premium.php
									include_nouveautees.php
							*/
							require_once('include_liste_pubs_admin.php');
						?>	
						
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
				require_once('include_footer.php');
	?>
	   </div>
	   <script type="text/javascript"> Cufon.now(); </script>
	</body>
	</html>
	<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>