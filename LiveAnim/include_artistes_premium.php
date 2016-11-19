<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once ('couche_metier/MSG.php');
require_once ('couche_metier/PCS_personne.php');
require_once ('couche_metier/CL_date.php');

$oMSG = new MSG();
$oPCS_personne = new PCS_personne();
$oCL_date = new CL_date();

# On récupère tous les artistes qui possèdent un pack par ordre de pack.

$oMSG->setData('VISIBLE', 1);
$oMSG->setData('TYPE_PERSONNE', 'Prestataire');

$artistes_premium = $oPCS_personne->fx_recuperer_artistes_premium($oMSG)->GetData(1)->fetchAll(PDO::FETCH_ASSOC);

# Mise en forme des variables.
foreach($artistes_premium as $key=>$artiste_premium){
	$artistes_premium[$key]['ROLE_simple'] = explode(',', $artiste_premium['ROLES']);
	
	if(count($artistes_premium[$key]['ROLE_simple']) > 0){
		if(count($artistes_premium[$key]['ROLE_simple']) == 1){
			$artistes_premium[$key]['ROLE_simple'] = $artistes_premium[$key]['ROLE_simple'][0];
		}else{
			$artistes_premium[$key]['ROLE_simple'] = $artistes_premium[$key]['ROLE_simple'][0].", ...";
		}		
	}else{
		$artistes_premium[$key]['ROLE_simple'] = "<span class='petit gris'>Pas de rôle renseigné...</span>";
	}
}

# Préparation à l'autogénération. (Ca va être COMIQUE !) -> Ce fut comique =D
$div_slide = Array();
$nb_artistes = count($artistes_premium);
$int_div = 0;
$i = 0;


foreach($artistes_premium as $key=>$artiste_premium){
	$div_slide[$int_div][$i] = $artiste_premium;
	$i++;
	if($i == 6){# Défini le nombre d'affichage par série. (3 gauche + 3 droite)
		$i = 0;
		$int_div++;
	}
}
?>
<div class="section">
	 <img style="" alt="Artiste prémium"  src="/images/artiste-premium.png"><br /><br />
	  <!-- newsSlider begin -->
	<div id="newsSlider">
		<div class="container">
			<div class="slides">
				<?php
				foreach($div_slide as $key_div=>$div){
				$binaire = true;# Va nous permettre de compter le <li>
				?>
					<div class="slide">
						<ul class="topics">
							<?php
							foreach($div as $key_ul=>$ul){
								# On récupère la date de création du compte.
								$oMSG->setData('ID_PERSONNE', $ul['ID_PERSONNE']);
								
								$personne = $oPCS_personne->fx_recuperer_date_creation_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
								$personne[0]['DATE_CONNEXION'] = $oCL_date->fx_ajouter_date($personne[0]['DATE_CONNEXION'], false, false, 'en', 'fr');
								
								# On met en forme les rôles.
								$personne[0]['ROLES'] = trim(str_replace(',', ', ', $ul['ROLES']));
								$personne[0]['ROLES'][strlen($personne[0]['ROLES'])-1] = " ";
							?>
								<li <?php if($binaire){echo "class='alt'";} ?>>
								   <a href="<?php echo $oCL_page->getPage('personne')."?id_personne=".$ul['ID_PERSONNE']; ?>"><img width="93px" height="117px" alt="Photo de l'artiste." src="<?php if(isset($ul['URL_PHOTO_PRINCIPALE']) && !empty($ul['URL_PHOTO_PRINCIPALE'])){echo $ul['URL_PHOTO_PRINCIPALE'];}else if($ul['CV_ACCESSIBLE'] > 4){echo $oCL_page->getImage('casque_or');}else if($ul['CV_ACCESSIBLE'] > 2){echo $oCL_page->getImage('casque_argent');}else{echo $oCL_page->getImage('casque_blanc');} ?>" /></a>
								   <h5><?php if(isset($_SESSION['pack']['CV_ACCESSIBLE']) && $_SESSION['pack']['CV_ACCESSIBLE'] >= 2){echo $ul['PSEUDO'];}else{echo "Membre N°".$ul['ID_PERSONNE'];} ?></h5> 
								   <p title="<?php echo $personne[0]['ROLES']; ?>"><?php echo $ul['ROLE_simple']; ?></p>
								   <p><a href="<?php echo $oCL_page->getPage('personne')."?id_personne=".$ul['ID_PERSONNE']; ?>" class="link2">Voir son CV</a></p>
								   <span style="color:#000000;opacity:0.3;">Inscrit le <?php echo $personne[0]['DATE_CONNEXION']; ?></span>
								</li>
							<?php
								# on modifie l'état du binaire.
								if($binaire){
									$binaire = false;
								}else{
									$binaire = true;
								}
							}
							?>
						</ul>
					</div>
				<?php
				}
				?>
			</div>
		</div>
		<?php
		if($nb_artistes >= 7){
		?>		
			<a href="#" class="previous"></a><a href="#" class="next"></a>
		<?php
		}
		?>
	</div>
</div>