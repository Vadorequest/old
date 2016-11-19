<?php
# On inclut l'entête. Elle ouvre la session, crée un compte s'il n'existe pas. Et bufferise les données.
require_once('include_header.php');
?>
<title>Maintenance en cours</title>
</head>

	<body id="page1" onload="new ElementMaxHeight();">
	   <div id="main">
		  <!-- header -->
		  <div id="header">
			<div class="wrapper">
				<div class="col-1">				
					<?php
						# On inclut le formulaire de connexion ainsi que la bannière LiveAnim.
					?>
					<div class="logo"><a href="index.php"><img alt="" src="images/logo.png" /></a></div>
				</div>
				<div class="col-2">
					<?php
						/* Partie qui peut prendre les include: 
								include_menu_principal.php
								include_slider.php
						*/
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
							
						?>	
						<h3>Maintenance:</h3>
						Le site est actuellement en maintenance, il est totalement indisponible pour toute la durée de la maintenance.<br />
						Cette maintenance peut-être due à diverses raisons, mise à jour du site, réparation ou simplement une sauvegarde des données.<br />
						<br />
						Nous vous prions de patienter et nous excusons de ce désagrément.<br />
						<br />
						L'équipe LiveAnim.
							
						</div>
					</div>
				</div>
			</div>
			<?php
				/* Partie qui peut prendre les include: 
						include_pub_bas.php
						include_footer.php
				*/
			?>
	   </div>
	</body>
	</html>
