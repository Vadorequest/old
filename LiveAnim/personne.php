<?php
# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
require_once('include_header.php');
?>
<title>Fiche du prestataire N°<?php echo $_GET['id_personne']; ?></title>
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


		
require_once('script_prechargement_personne.php');

# On définit la page courante:
$_SESSION['page_actuelle'] = "personne";

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
							include_partenaire.php
							include_nouveaux_artistes.php
							include_dernieres_annonces.php
					*/
						require_once('include_types_artistes.php');
						require_once('include_nouveaux_artistes.php');
						require_once('include_dernieres_annonces.php');
					?>					
			   </div>
			</div>
			<div class="mainContent maxheight">
				<?php
					require_once('include_pub_haut.php');
				?>	
				<div class="indent">
					<?php
						/* Partie qui peut prendre les include: 
								include_artistes_premium.php
								include_nouveautees.php
						*/
						require_once('include_personne.php');
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
			require_once('include_pub_bas.php');
			require_once('include_footer.php');
		?>
   </div>
   <script type="text/javascript"> Cufon.now(); </script>
</body>
</html>