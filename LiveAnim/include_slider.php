<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('couche_metier/MSG.php');
require_once('couche_metier/PCS_slide.php');

$oMSG = new MSG();
$oPCS_slide = new PCS_slide();

$oMSG->setData('VISIBLE', 1);

$slides_ = $oPCS_slide->fx_recuperer_slides_by_VISIBLE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
foreach($slides_ as $key=>$slide_){
	$slides_[$key]['ACCES'] = explode(',', $slide_['ACCES']);
}
?>

<div id="loopedSlider">	
	<div class="container">
		<div class="slides">
			<?php
			foreach($slides_ as $key=>$slide_){
				# On dit que le slide n'a pas encore été affiché.
				$slide_['affiché'] = false;

				if(in_array('Non connectés', $slide_['ACCES']) && !$slide_['affiché']){

					if(!$_SESSION['compte']['connecté']){
						$slide_['affiché'] = true;
					?>
						<div class="<?php echo $slide_['CLASSE']; ?>"><a href="<?php echo $slide_['LIEN']; ?>"><img alt="" src="<?php echo $slide_['URL'] ?>" /></a><strong><?php echo $slide_['TITRE']; ?></strong></div>
					<?php
					}
				}
				if(in_array('Prestataire', $slide_['ACCES']) && !$slide_['affiché']){
					if(isset($_SESSION['compte']['TYPE_PERSONNE']) && $_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire"){
						$slide_['affiché'] = true;
					?>
						<div class="<?php echo $slide_['CLASSE']; ?>"><a href="<?php echo $slide_['LIEN']; ?>"><img alt="" src="<?php echo $slide_['URL'] ?>" /></a><strong><?php echo $slide_['TITRE']; ?></strong></div>
					<?php
					}
				}
				if(in_array('Organisateur', $slide_['ACCES']) && !$slide_['affiché']){
					if(isset($_SESSION['compte']['TYPE_PERSONNE']) && $_SESSION['compte']['TYPE_PERSONNE'] == "Organisateur"){
						$slide_['affiché'] = true;
					?>
						<div class="<?php echo $slide_['CLASSE']; ?>"><a href="<?php echo $slide_['LIEN']; ?>"><img alt="" src="<?php echo $slide_['URL'] ?>" /></a><strong><?php echo $slide_['TITRE']; ?></strong></div>
					<?php
					}
				}
				if(in_array('Admin', $slide_['ACCES']) && !$slide_['affiché']){
					if(isset($_SESSION['compte']['TYPE_PERSONNE']) && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
						$slide_['affiché'] = true;
					?>
						<div class="<?php echo $slide_['CLASSE']; ?>"><a href="<?php echo $slide_['LIEN']; ?>"><img alt="" src="<?php echo $slide_['URL'] ?>" /></a><strong><?php echo $slide_['TITRE']; ?></strong></div>
					<?php
					}
				}
			}
			?>
		</div>
	</div>
</div>
