<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

?>
	<h2>Toutes les news:</h2><br />
	<br />
	
	<?php
	if(isset($_SESSION['liste_news']['message']) && $_SESSION['liste_news']['message_affiche'] == false){
		echo $_SESSION['liste_news']['message'];
		$_SESSION['liste_news']['message_affiche'] = true;
	}
	?>
	
	<?php
		if($nb_result[0]['nb_nouveautees'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_nouveautees'], $page_actuelle);
		}
	?>
	
	<ul class="news-list">
	<?php
	foreach($nouveautees as $key=>$nouveaute){
	
		$oMSG->setData('VISIBLE', 1);
		$oMSG->setData('ID_NOUVEAUTE', $nouveaute['ID_NOUVEAUTE']);
		
		# On récupère le nombre de commentaires.
		$nb_commentaire = $oPCS_commentaire->fx_compter_tous_commentaires_by_ID_NOUVEAUTE($oMSG)->getData(1)->fetchAll(PDO::FETCH_ASSOC);
	?>
		<li>
			<img alt="Image de la nouveautée !" height="180px" width="180px" src="<?php if(!empty($nouveaute['URL_PHOTO'])){echo $nouveaute['URL_PHOTO'];}else{echo $oCL_page->getImage('news1');} ?>" />
			<b style="color:#e7a5f7"><u>Publiée le <?php echo $nouveaute['DATE_CREATION']; ?>&nbsp;:</u></b><br />
			<br />
			<h5><?php echo $nouveaute['TITRE']; ?></h5><br />
			<br />
			<h6><?php echo $nouveaute['ENTETE']; ?></h6><br />
			<br />
			<a href="<?php echo $oCL_page->getPage('news')."?id_news=".$nouveaute['ID_NOUVEAUTE']; ?>" class="link3">Voir la suite...</a><span class="comment"><a href="<?php echo $oCL_page->getPage('news')."?id_news=".$nouveaute['ID_NOUVEAUTE']; ?>"><?php if($nb_commentaire[0]['nb_commentaire'] > 0){echo $nb_commentaire[0]['nb_commentaire']." commentaires";} ?></a></span>
		</li>
	<?php
	}
	?>
	</ul>
	
	<?php
		if($nb_result[0]['nb_nouveautees'] > $nb_result_affiches){
			$path_parts = pathinfo($_SERVER['PHP_SELF']);
			$page = $path_parts["basename"];
			$page_actuelle = ($limite/$nb_result_affiches)+1;
			afficher_pages($nb_result_affiches, $page, $nb_result[0]['nb_nouveautees'], $page_actuelle);
		}
	?>
	<br />
	<center class="petit gris">Il y a au total <?php echo $nb_result[0]['nb_nouveautees']; ?> nouveautées.</center>