<?php
class CL_page{

	private $tab_page_absolu;
	private $tab_page_relatif;
	private $tab_config;
	private $tab_image_relatif;
	private $dossier_image;
	
	# Fonction qui attribue à chaque valeur l'id de la page, il suffit de rajouter un couple clé=>valeur pour référencer une nouvelle page.
	public function __construct(){
	
	# Modifier cette valeur pour modifier tout le début des liens absolus du site:
	$debut = "http://liveanim.com/";
	
	# Modifier cette valeur pour modifier le chemin d'accès depuis le dossier www aux images.
	$this->dossier_image = "images/";
	
	$this->tab_page_relatif = array(# Les liens ci-dessous sont relatifs par rapport à l'URL en cours.
						
						# Pages externes au site:
						
						
						# Pages du site:
						'accueil' =>'http://liveanim.com/',
						'inscription' =>'inscription.php',
						'activation' =>'activation.php',
						'recuperation_mdp' =>'recuperation_mdp.php',
						'gestion_compte' =>'gestion_compte.php',
						'administration' =>'administration.php',
						'changer_rang' =>'changer_rang.php',
						'activer_comptes' =>'activer_comptes.php',
						'bannir_membre' =>'bannir_membre.php',
						'liste_membre' =>'liste_membre.php',
						'comptes_supprimes' =>'comptes_supprimes.php',
						'modifier_fiche_membre' =>'modifier_fiche_membre.php',
						'ajouter_pack' =>'ajouter_pack.php',
						'liste_packs' =>'liste_packs.php',
						'modifier_fiche_pack' =>'modifier_fiche_pack.php',
						'modifier_fiche_perso' =>'modifier_fiche_perso.php',
						'modifier_mdp' =>'modifier_mdp.php',
						'supprimer_compte' =>'supprimer_compte.php',
						'acheter_pack' =>'acheter_pack.php',
						'historique_achat_pack' =>'historique_achat_pack.php',
						'achat_pack_ok' =>'achat_pack_ok.php',
						'achat_pack_annule' =>'achat_pack_annule.php',
						'achat_pack_error' =>'achat_pack_error.php',
						'filleuls' =>'filleuls.php',
						'lien_parrainage' =>'lien_parrainage.php',
						'creer_annonce' =>'creer_annonce.php',
						'liste_annonces_en_attente' =>'liste_annonces_en_attente.php',
						'modifier_fiche_annonce_by_admin' =>'modifier_fiche_annonce_by_admin.php',
						'modifier_fiche_annonce' =>'modifier_fiche_annonce.php',
						'historique_annonce' =>'historique_annonce.php',
						'liste_annonce' =>'liste_annonce.php',
						'annonce' =>'annonce.php',
						'creer_contrat' =>'creer_contrat.php',
						'personne' =>'personne.php',
						'messagerie' =>'messagerie.php',
						'message' =>'message.php',
						'supprimer_message' =>'script_supprimer_messages.php',
						'historique_contrat' =>'historique_contrat.php',
						'contrat' =>'contrat.php',
						'modifier_contrat' =>'modifier_fiche_contrat.php',
						'contrat_pdf' =>'contrat_pdf.php',
						'liste_artiste' =>'liste_artiste.php',
						'ajouter_news' =>'ajouter_news.php',
						'modifier_news' =>'modifier_news.php',
						'liste_news_admin' =>'liste_news_admin.php',
						'liste_news' =>'liste_news.php',
						'news' =>'news.php',
						'liste_annonce_admin' =>'liste_annonce_admin.php',
						'liste_contrats_admin' =>'liste_contrats_admin.php',
						'faq' =>'faq.php',
						'contact' =>'contact.php',
						'liste_prestation' =>'liste_prestation.php',
						'gestion_annonce_goldlive' =>'gestion_annonce_goldlive.php',
						'mentions_legales' =>'ressources/mentionslegales.pdf',
						'annonces_visitees' =>'annonces_visitees.php',
						'gestion_parrainage' =>'gestion_parrainage.php',
						'statistiques_site' =>'statistiques_site.php',
						'gestion_cgu' =>'gestion_cgu.php',
						'gestion_mentions_legales' =>'gestion_mentions_legales.php',
						'gestion_faq' =>'gestion_faq.php',
						'gestion_slides' =>'gestion_slides.php',
						'modifier_slide' =>'modifier_slide.php',
						'envoyer_message_admin' =>'envoyer_message_admin.php',
						'liste_pubs_admin' =>'liste_pubs_admin.php',
						'ajouter_pub' =>'ajouter_pub.php',
						'modifier_pub' =>'modifier_pub.php',
						'envoyer_invitations' =>'envoyer_invitations.php',
						'liste_role_admin' =>'liste_role_admin.php',
						
						
						
						
						
						
						# Dossier du site:
						'paiement_bancaire_pack' =>'bancaire/achats_packs/',
						'paiement_bancaire_annonce_goldlive' =>'bancaire/achats_annonces_goldlive_paypal/',
						'paiement_allopass_annonce_goldlive' =>'bancaire/achats_annonces_goldlive_allopass/',

						
						# Les scripts php sont précisés par le (.php à la fin) script_ au début:
						'deconnexion.php' =>'script_deconnexion.php',
						'script_accepter_contrat' =>'script_accepter_contrat.php',
						'script_annuler_contrat' =>'script_annuler_contrat.php',
						'IPN' =>'script_achat_pack_ipn.php',
						'annonce_goldlive_IPN' =>'script_annonce_goldlive_IPN.php',
						'script_connexion_facebook' =>'script_connexion_facebook.php',
						
						# Les scripts js sont précisés par le .js à la fin, ils se trouvent dans le dossier /js:
						'acheter_pack.js' =>'js/acheter_pack.js',
						'activer_pack.js' =>'js/activer_pack.js',
						
						# Ressources:
						'cgu' =>'ressources/cgu.pdf',					
						
						);
						
	$this->tab_page_absolu = array(# Les liens ci-dessous sont absolus.
												
						# Pages du site:
						'accueil' =>$debut,
						'inscription' =>$debut.$this->tab_page_relatif['inscription'],
						'activation' =>$debut.$this->tab_page_relatif['activation'],
						'recuperation_mdp' =>$debut.$this->tab_page_relatif['recuperation_mdp'],
						'gestion_compte' =>$debut.$this->tab_page_relatif['gestion_compte'],
						'administration' =>$debut.$this->tab_page_relatif['administration'],
						'changer_rang' =>$debut.$this->tab_page_relatif['changer_rang'],
						'activer_comptes' =>$debut.$this->tab_page_relatif['activer_comptes'],
						'bannir_membre' =>$debut.$this->tab_page_relatif['bannir_membre'],
						'liste_membre' =>$debut.$this->tab_page_relatif['liste_membre'],
						'comptes_supprimes' =>$debut.$this->tab_page_relatif['comptes_supprimes'],
						'modifier_fiche_membre' =>$debut.$this->tab_page_relatif['modifier_fiche_membre'],
						'ajouter_pack' =>$debut.$this->tab_page_relatif['ajouter_pack'],
						'liste_packs' =>$debut.$this->tab_page_relatif['liste_packs'],
						'modifier_fiche_pack' =>$debut.$this->tab_page_relatif['modifier_fiche_pack'],
						'modifier_fiche_perso' =>$debut.$this->tab_page_relatif['modifier_fiche_perso'],
						'modifier_mdp' =>$debut.$this->tab_page_relatif['modifier_mdp'],
						'supprimer_compte' =>$debut.$this->tab_page_relatif['supprimer_compte'],
						'acheter_pack' =>$debut.$this->tab_page_relatif['acheter_pack'],
						'historique_achat_pack' =>$debut.$this->tab_page_relatif['historique_achat_pack'],
						'achat_pack_ok' =>$debut.$this->tab_page_relatif['achat_pack_ok'],
						'achat_pack_annule' =>$debut.$this->tab_page_relatif['achat_pack_annule'],
						'achat_pack_error' =>$debut.$this->tab_page_relatif['achat_pack_error'],
						'filleuls' =>$debut.$this->tab_page_relatif['filleuls'],
						'lien_parrainage' =>$debut.$this->tab_page_relatif['lien_parrainage'],
						'creer_annonce' =>$debut.$this->tab_page_relatif['creer_annonce'],
						'liste_annonces_en_attente' =>$debut.$this->tab_page_relatif['liste_annonces_en_attente'],
						'modifier_fiche_annonce_by_admin' =>$debut.$this->tab_page_relatif['modifier_fiche_annonce_by_admin'],
						'modifier_fiche_annonce' =>$debut.$this->tab_page_relatif['modifier_fiche_annonce'],
						'historique_annonce' =>$debut.$this->tab_page_relatif['historique_annonce'],
						'liste_annonce' =>$debut.$this->tab_page_relatif['liste_annonce'],
						'annonce' =>$debut.$this->tab_page_relatif['annonce'],
						'creer_contrat' =>$debut.$this->tab_page_relatif['creer_contrat'],
						'personne' =>$debut.$this->tab_page_relatif['personne'],
						'messagerie' =>$debut.$this->tab_page_relatif['messagerie'],
						'message' =>$debut.$this->tab_page_relatif['message'],
						'supprimer_message' =>$debut.$this->tab_page_relatif['supprimer_message'],
						'historique_contrat' =>$debut.$this->tab_page_relatif['historique_contrat'],
						'contrat' =>$debut.$this->tab_page_relatif['contrat'],
						'modifier_contrat' =>$debut.$this->tab_page_relatif['modifier_contrat'],
						'contrat_pdf' =>$debut.$this->tab_page_relatif['contrat_pdf'],
						'liste_artiste' =>$debut.$this->tab_page_relatif['liste_artiste'],
						'ajouter_news' =>$debut.$this->tab_page_relatif['ajouter_news'],
						'modifier_news' =>$debut.$this->tab_page_relatif['modifier_news'],
						'liste_news_admin' =>$debut.$this->tab_page_relatif['liste_news_admin'],
						'liste_news' =>$debut.$this->tab_page_relatif['liste_news'],
						'news' =>$debut.$this->tab_page_relatif['news'],
						'liste_contrats_admin' =>$debut.$this->tab_page_relatif['liste_contrats_admin'],
						'faq' =>$debut.$this->tab_page_relatif['faq'],
						'contact' =>$debut.$this->tab_page_relatif['contact'],
						'liste_prestation' =>$debut.$this->tab_page_relatif['liste_prestation'],
						'gestion_annonce_goldlive' =>$debut.$this->tab_page_relatif['gestion_annonce_goldlive'],
						'mentions_legales' =>$debut.$this->tab_page_relatif['mentions_legales'],
						'gestion_parrainage' =>$debut.$this->tab_page_relatif['gestion_parrainage'],
						'statistiques_site' =>$debut.$this->tab_page_relatif['statistiques_site'],
						'gestion_cgu' =>$debut.$this->tab_page_relatif['gestion_cgu'],
						'gestion_mentions_legales' =>$debut.$this->tab_page_relatif['gestion_mentions_legales'],
						'gestion_slides' =>$debut.$this->tab_page_relatif['gestion_slides'],
						'modifier_slide' =>$debut.$this->tab_page_relatif['modifier_slide'],
						'envoyer_message_admin' =>$debut.$this->tab_page_relatif['envoyer_message_admin'],
						'liste_pubs_admin' =>$debut.$this->tab_page_relatif['liste_pubs_admin'],
						'ajouter_pub' =>$debut.$this->tab_page_relatif['ajouter_pub'],
						'modifier_pub' =>$debut.$this->tab_page_relatif['modifier_pub'],
						'envoyer_invitations' =>$debut.$this->tab_page_relatif['envoyer_invitations'],
						'liste_role_admin' =>$debut.$this->tab_page_relatif['liste_role_admin'],
						
						
						
						
						
						# Dossier du site:
						'paiement_bancaire_pack' =>$debut.$this->tab_page_relatif['paiement_bancaire_pack'],
						'paiement_bancaire_annonce_goldlive' =>$debut.$this->tab_page_relatif['paiement_bancaire_annonce_goldlive'],
						'paiement_allopass_annonce_goldlive' =>$debut.$this->tab_page_relatif['paiement_allopass_annonce_goldlive'],
						
						# Les scripts php sont précisés par le (.php à la fin) script_ au début:
						'deconnexion.php' =>$debut.$this->tab_page_relatif['deconnexion.php'],
						'script_accepter_contrat' =>$debut.$this->tab_page_relatif['script_accepter_contrat'],
						'script_annuler_contrat' =>$debut.$this->tab_page_relatif['script_annuler_contrat'],
						'IPN' =>$debut.$this->tab_page_relatif['IPN'],
						'annonce_goldlive_IPN' =>$debut.$this->tab_page_relatif['annonce_goldlive_IPN'],
						'script_connexion_facebook' =>$debut.$this->tab_page_relatif['script_connexion_facebook'],
						
						# Les scripts js sont précisés par le .js à la fin:
						'acheter_pack.js' =>$debut.$this->tab_page_relatif['acheter_pack.js'],
						'activer_pack.js' =>$debut.$this->tab_page_relatif['activer_pack.js'],
						
						# Ressources:
						'cgu' =>$debut.$this->tab_page_relatif['cgu'],
						
						);
	
	
	$this->tab_config = array(
						'compte_credite' =>'liveanim@gmail.com',
								
						);
	
	
	
	
	$this->tab_image_relatif = array(
						# Images en vrac
						'casque_blanc'  =>'1page-img4.jpg',
						'casque_argent'  =>'1page-img1.jpg',
						'casque_or'  =>'1page-img1.png',
						'avat_test1'  =>'1page-img5.png',
						'news1'  =>'1page-img12.jpg',
						'disco1'  =>'2page-img3.png',
						'annonces_gold'  =>'annonces_gold.gif',
						'btn_droite'  =>'arrow1.png',
						'btn_droite_petit'  =>'arrow2.png',
						'background'  =>'background.jpg',
						'cadre_connexion1'  =>'Bloc-Connexion.jpg',
						'cadre_connexion3'  =>'Cadre-connexion.png',
						'cadre_connexion2'  =>'Cadre de connexion.png',# Utilisé
						'disco_ball'  =>'disco ball.png',
						'favicon'  =>'favicon.gif',
						'fond_inscription'  =>'fond_inscription.jpg',
						'fond_inscription_bas'  =>'fond_inscription_bas.jpg',
						'fond_inscription_haut'  =>'fond_inscription_haut.jpg',
						'fond_inscription_milieu'  =>'fond_inscription_milieu.png',
						'twitter'  =>'icon1.gif',
						'icon2'  =>'icon2.gif',
						'facebook'  =>'icon3.gif',
						'icon4'  =>'icon4.gif',
						'rss'  =>'icon5.gif',
						'fr'  =>'icon6.gif',
						'etoile_menu'  =>'imgmenu.png',
						'inscription_gratuite'  =>'inscription.png',
						'liste1'  =>'liste1.png',
						'logo_liveanim'  =>'logo.png',
						'micro_renverse'  =>'micro.gif',
						'mon_compte'  =>'mon_compte.png',
						'suivant'  =>'next.png',
						'non1'  =>'non.gif',
						'ok1'  =>'ok.gif',
						
						'pdf'  =>'pdf.png',
						'pdf_non'  =>'pdf_non.png',
						'precedent'  =>'prev.png',
						'previsualiser'  =>'previsualiser.jpg',
						'supprimer_gros'  =>'supprimer_personne.png',
						'supprimer'  =>'supprimer.png',
						'admin_rond'  =>'photo_administration2.gif',
						'supprimer_personne_petit'  =>'supprimer_personne_petit.png',
						
						'valider2'  =>'valider.png',
						'valider3'  =>'valider3.jpg',
						'voir' =>'voir.jpg',
						'voir_non' =>'voir_non.png',
						
						'paypal_boutons' =>'paypal_boutons.gif',
						'news' =>'news.gif',
						'accepter_contrat' =>'accepter_contrat.png',
						'modifier_contrat' =>'modifier_contrat.png',
						'etoile_pleine' =>'Etoile_pleine.png',
						'etoile_vide' =>'Etoile_vide.png',
						
						
						# Sliders
						'cle_administration'  =>'photo_administration1.gif',
						'administration'  =>'slide_administration.gif',
						'slide_test'  =>'slide1.jpg',
						
						# Parrainage
						'special_parrainage'  =>'parrainage1.png',
						
						# Images template LiveAnim (trucs rose ^^)
						'plus' =>'Plus.png',
						'petit_plus' =>'Petit_plus.png',
						'moins' =>'Moins.png',
						'petit_moins' =>'Petit_moins.png',
						'valider'  =>'valider4.png',
						'groupe'  =>'Bonhommes.png',
						'croix'  =>'Croix.png',
						'petite_croix'  =>'non.gif',
						'email'  =>'Email.png',
						'fleche_droite'  =>'Flèche droite.png',
						'fleche_gauche'  =>'Flèche gauche.png',
						'loupe'  =>'Loupe.png',
						'new_message'  =>'Message ouvert.png',
						'messagerie'  =>'Message fermé.png',
						'accueil'  =>'Maison.png',
						'inscription'  =>'inscription.png',
						'liste_annonce'  =>'Liste_annonce.png',
						'exclamation'  =>'exclamation.png',
						'interrogation'  =>'interrogation.png',
						'pouce_endroit'  =>'Pouce endroit.png',
						'pouce_envers'  =>'Pouce envers.png',
						'reglages'  =>'Réglages.png',
						'tel'  =>'Téléphone.png',
						'pouce_endroit'  =>'Pouce_endroit.png',
						'pouce_envers'  =>'Pouce_envers.png',
						);
	
	}
	
	
	# Accesseur en lecture de $tab_page.
    public function getPage($page, $type_lien = "relatif") {
        if($type_lien == "absolu"){
			return $this->tab_page_absolu[$page];
		}else if($type_lien == "relatif"){
			return $this->tab_page_relatif[$page];
		}
	}
	
	# Accesseur en lecture de $tab_config
	public function getConfig($nom_array) {
		return $this->tab_config[$nom_array];
	}
	
	# Accesseur en lecture de $tab_image.
    public function getImage($image) {
		return $this->dossier_image.$this->tab_image_relatif[$image];
	}
	
	# Accesseur en lecture de $tab_image.
    public function getDossierImage() {
		return $this->dossier_image;
	}

}
?>