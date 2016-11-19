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
	<img style="" alt="Historique contrat"
 src="images/historique-contrat.png">
<br />
<br />
	<?php
	if(isset($_SESSION['historique_contrat']['message']) && $_SESSION['historique_contrat']['message_affiche'] == false){
		echo $_SESSION['historique_contrat']['message'];
		$_SESSION['historique_contrat']['message_affiche'] = true;
	}
	?>
	<?php
		if($nb_result[0]['nb_contrat'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_contrat'], $page_actuelle);
		}
	?>
	<fieldset class="padding_LR"><legend class="legend_basique">Historique</legend><br />
		<br />
		<table width="100%" >
		  <tr>
				<th scope="col" width="25%">Annonce</th>
				<th scope="col" width="10%">Contrat avec</th>
				<th scope="col" width="34%">Date de création</th>
				<th scope="col" width="15%">Statut</th>
				<th scope="col" width="8%">Voir</th>
				<th scope="col" width="8%">PDF</th>
			</tr>
			<?php
			if($nb_result[0]['nb_contrat'] == 0){
			?>
					<tr><th colspan="6"><hr /></th></tr>
					<tr><th colspan="6" class="orange" height="40px"><?php if(isset($_GET['rq']) && $_GET['rq'] == "toutes"){?>Vous n'avez jamais effectué de contrat.<?php }else{?>Vous n'avez aucun contrat en cours.<?php }?></th></tr>
			<?php
			}
			foreach($contrats as $key=>$contrat){
				# On récupère la personne qui fait ce contrat avec la personne connectée.				
				$oMSG->setData('ID_CONTRAT', $contrat['ID_CONTRAT']);
				$oMSG->setData('ID_PERSONNE', $_SESSION['compte']['ID_PERSONNE']);
				
				$id_contractant = $oPCS_contrat->fx_recuperer_destinataire_by_ID_CONTRAT_et_nonID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
				
				$oMSG->setData('ID_PERSONNE', $id_contractant[0]['ID_PERSONNE']);
				
				$contractant = $oPCS_personne->fx_recuperer_compte_min_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
			?>
				<tr><th colspan="6"><hr /></th></tr>
				<tr>
					<th scope="row"><a href="<?php echo $oCL_page->getPage('annonce')."?id_annonce=".$contrat['ID_ANNONCE']; ?>"><?php echo $contrat['TITRE']; ?></a></th>
					<th scope="row">
						<?php 
						if($contractant[0]['TYPE_PERSONNE'] == "Prestataire" || $contractant[0]['TYPE_PERSONNE'] == "Admin"){
						?>
							<a href="<?php echo $oCL_page->getPage('personne')."?id_personne=".$contractant[0]['ID_PERSONNE']; ?>"><?php echo $contractant[0]['PSEUDO']; ?></a>
						<?php
						}else{
							if($_SESSION['pack']['CV_ACCESSIBLE'] >= 2){
								echo $contractant[0]['PSEUDO'];
							}else{
								echo "Membre N°".$contractant[0]['ID_PERSONNE'];
							}
						}
						?>
					</th>
					<td title="<?php echo $contrat['DATE_CONTRAT']; ?>"><center><?php echo $contrat['DATE_CONTRAT_simple']; ?><center></td>
					<th class="<?php if($contrat['STATUT_CONTRAT'] == "Annulé"){echo "alert";}else if($contrat['STATUT_CONTRAT'] == "Refusé"){echo "orange";}else if($contrat['STATUT_CONTRAT'] == "Validé"){echo "rose";}else{} ?>"><?php echo $contrat['STATUT_CONTRAT']; ?></th>
					<th><a href="<?php echo $oCL_page->getPage('contrat')."?id_contrat=".$contrat['ID_CONTRAT']; ?>"><img src="<?php echo $oCL_page->getImage('voir'); ?>" alt="" title="" /></a></th>
					<?php
					if(($contrat['STATUT_CONTRAT'] == "Validé" || $contrat['STATUT_CONTRAT'] == "Annulé après validation")&& $_SESSION['pack']['CONTRATS_PDF'] == true){
					?>
						<th><a href="<?php echo $oCL_page->getPage('contrat_pdf')."?id_contrat=".$contrat['ID_CONTRAT']; ?>"><img src="<?php echo $oCL_page->getImage('pdf'); ?>" alt="Télécharger en PDF" title="Télécharger en PDF" /></a></th>
					<?php
					}else if($_SESSION['pack']['CONTRATS_PDF'] != true){
					?>
						<th><img src="<?php echo $oCL_page->getImage('pdf_non'); ?>" alt="Votre pack ne vous permet pas de bénéficier de cette fonctionnalité !" title="Votre pack ne vous permet pas de bénéficier de cette fonctionnalité !" /></th>
					<?php
					}else{
					?>
						<th><img src="<?php echo $oCL_page->getImage('pdf_non'); ?>" alt="Le contrat n'a pas encore été validé ! Vous ne pouvez pas télécharger le .pdf !" title="Le contrat n'a pas encore été validé ! Vous ne pouvez pas télécharger le .pdf !" /></th>
					<?php
					}
					?>
				</tr>
			<?php
			}
			?>
		</table>
		<br />
	</fieldset>
	
	
	
	
	
	
	
	<?php
		if($nb_result[0]['nb_contrat'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_contrat'], $page_actuelle);
		}
	?>


<?php
}else{
	# Si l'internaute n'est pas connecté il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>