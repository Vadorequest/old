<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

require_once('script_prechargement_menuv_admin.php');

# On vérifie que la personne est connectée et Admin.
if($_SESSION['compte']['connecté'] == true && $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
?>
<ul>
	<li><h5>Gestion des Membres:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('activer_comptes'); ?>">Activer les comptes non activés.</a>&nbsp;<span title="Il y a <?php echo $nb_comptes_inactifs[0]['nb_comptes']; ?> comptes en attente de modération." class="orange">[<?php echo $nb_comptes_inactifs[0]['nb_comptes']; ?>]</span></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_membre'); ?>">Voir la liste des membres.</a>&nbsp;<span title="Il y a <?php echo $nb_comptes[0]['nb_personne']; ?> utilisateurs au total." class="orange">[<?php echo $nb_comptes[0]['nb_personne']; ?>]</span></li>
	<li><a href="<?php echo $oCL_page->getPage('comptes_supprimes'); ?>">Voir les comptes supprimés.&nbsp;<span class="petit">(Par l'utilisateur)</span></a>&nbsp;<span title="Il y a <?php echo $nb_comptes_supprimes[0]['nb_comptes_supprimes']; ?> comptes supprimés par leur utilisateur." class="orange">[<?php echo $nb_comptes_supprimes[0]['nb_comptes_supprimes']; ?>]</span></li>
	<li>&nbsp;</li>
	<li><a href="<?php echo $oCL_page->getPage('bannir_membre'); ?>">Bannir un membre</a></li>
	<li><a href="<?php echo $oCL_page->getPage('changer_rang'); ?>">Changer le rang.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Annonces:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_annonces_en_attente'); ?>">Voir toutes les annonces en attente de validation.</a>&nbsp;<span title="Il y a <?php echo $nb_annonces_en_attente[0]['nb_annonce']; ?> annonces en attente." class="orange">[<?php echo $nb_annonces_en_attente[0]['nb_annonce']; ?>]</span></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_annonce_admin'); ?>">Voir toutes les annonces.</a>&nbsp;<span title="Il y a <?php echo $nb_annonces_totales[0]['nb_annonce']; ?> annonces en tout." class="orange">[<?php echo $nb_annonces_totales[0]['nb_annonce']; ?>]</span></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Contrats:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_contrats_admin')."?rq=en_cours"; ?>">Voir tous les contrats récents/en cours.</a></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_contrats_admin')."?rq=tous"; ?>">Voir tous les contrats.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Packs:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_packs'); ?>">Voir tous les packs.</a>&nbsp;<span title="Il y a <?php echo $nb_packs[0]['nb_packs']; ?> packs existants dont <?php echo $nb_packs_inactifs[0]['nb_packs']; ?> pack(s) désactivé(s)." class="orange">[<?php echo $nb_packs[0]['nb_packs']; ?>]</span></li>
	<li><a href="<?php echo $oCL_page->getPage('ajouter_pack'); ?>">Ajouter un pack.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Prestataires:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_role_admin'); ?>">Gérer les rôles.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des News:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_news_admin'); ?>">Voir toutes les news.</a>&nbsp;<span title="Il y a <?php echo $nb_news[0]['nb_nouveautees']; ?> news affichées et <?php echo $nb_news_desactive[0]['nb_nouveautees']; ?> news désactivée(s)." class="orange">[<?php echo $nb_news[0]['nb_nouveautees']; ?>]</span></li>
	<li><a href="<?php echo $oCL_page->getPage('ajouter_news'); ?>">Publier une news.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Pubs:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('liste_pubs_admin'); ?>">Voir toutes les pubs.</a>&nbsp;<span title="Il y a <?php echo $nb_pubs[0]['nb_pubs']; ?> pubs d'affichées." class="orange">[<?php echo $nb_pubs[0]['nb_pubs']; ?>]</span></li>
	<li><a href="<?php echo $oCL_page->getPage('ajouter_pub'); ?>">Ajouter une pub</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion des Messages:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('envoyer_message_admin'); ?>">Envoyer un message.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion du Parrainage:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('gestion_parrainage'); ?>">Voir les meilleurs parrains.</a></li>
</ul>
<br /><hr /><br />
<ul>
	<li><h5>Gestion globale:</h5></li>
	<li><a href="<?php echo $oCL_page->getPage('statistiques_site'); ?>">Voir les statistiques du site.</a></li>
	<li><a href="<?php echo $oCL_page->getPage('gestion_faq'); ?>">Modifier la page de la FAQ.</a></li>
	<li><a href="<?php echo $oCL_page->getPage('gestion_cgu'); ?>">Modifier les CGU. <span class="petit">(pdf)</span></a></li>
	<li><a href="<?php echo $oCL_page->getPage('gestion_mentions_legales'); ?>">Modifier les mentions légales. <span class="petit">(pdf)</span></a></li>
	<li><a href="<?php echo $oCL_page->getPage('gestion_slides'); ?>">Gestion des slides.</a></li>
</ul>





<?php
}else{
	# Si l'internaute n'est pas connecté et admin il gicle.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>