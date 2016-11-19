<?php
if(!isset($_SESSION)){
	session_start();
}

# On crée notre objet oCL_page.
if(!isset($oCL_page)){
	require_once('couche_metier/CL_page.php');
	$oCL_page = new CL_page();
}

if(!isset($_SESSION['administration'])){
	$_SESSION['administration'] = array();
}

	/*
	* Attention: 
	* Lorsque l'on parle de $_SESSION['compte']['TYPE_PERSONNE'] on parle à la fois de la personne connectée et de la 
	* personne modifiée. En effet, si le type est "Admin" alors il a tous les droits de modifications.
	* Si le type est Prestataire alors il peut modifier ses infos de prestations.
	* Si le type est Organisateur alors il ne peut modifier que ses infos perso.
	*
	* Amélioration possible pour une plus grande facilité d'évolution:
	* 	- Modifier le script appelé selon le TYPE_PERSONNE en SESSION.
	* 	- Vérifier que le script a le droit d'être appelé par ce TYPE_PERSONNE.
	*	- Faire les modifications selon les infos BDD ou SESSION selon le script appelé.
	*/

# On vérifie que la personne est connectée.
if($_SESSION['compte']['connecté'] == true){
	if(isset($_POST['form_fiche_membre_nom'])){
		
		require_once('couche_metier/MSG.php');
		require_once('couche_metier/PCS_personne.php');		
		require_once('couche_metier/PCS_types.php');
		require_once('couche_metier/CL_date.php');
		require_once('couche_metier/CL_upload.php');
		require_once('couche_metier/CL_cryptage.php');
		
		$oMSG = new MSG();
		$oPCS_personne = new PCS_personne();
		$oPCS_types = new PCS_types();
		$oCL_date = new CL_date();
		$oCL_cryptage = new CL_cryptage();
		
		# Si ce n'est pas un admin on vérifie que le mot de passe fournit correspond bien à l'ID_PERSONNE fournie (/!\ Modification de l'id possible /!\)
		if($_SESSION['compte']['TYPE_PERSONNE'] != "Admin"){
			
			$oMSG->setData('ID_PERSONNE', (int)$_POST['form_fiche_membre_id_personne']);
			$oMSG->setData('MDP', utf8_encode($oCL_cryptage->Cryptage($_POST['form_fiche_membre_mdp'], $_POST['form_fiche_membre_pseudo'])));
			
			$nb_personne = $oPCS_personne->fx_compter_personne_by_ID_PERSONNE_et_MDP($oMSG)->getData(1)->fetchAll();

			if($nb_personne[0]['nb_personne'] == 1){
				$id_personne_ok = true;
			}else{
				$id_personne_ok = false;
				$_SESSION['modification_fiche_membre']['message'] = "<span class='alert'>Le mot de passe saisi est incorrect. Aucune modification n'a été effectuée.</span><br />";
				$_SESSION['modification_fiche_membre']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
			}

		}else{
			$id_personne_ok = true;
		}
		
		if($id_personne_ok){
			
			# On récupère les types nécessaires:
				$oMSG->setData('ID_FAMILLE_TYPES', 'Civilité');
				$civilites = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
				
				$oMSG->setData('ID_FAMILLE_TYPES', 'Statut professionnel');
				$statuts_personne = $oPCS_types->fx_recuperer_tous_types_par_famille($oMSG)->getData(1)->fetchAll();
				
			# On récupère la réduction du personnage.
			$oMSG->setData('ID_PERSONNE', (int)$_POST['form_fiche_membre_id_personne']);
			
			$reduction = $oPCS_personne->fx_recuperer_REDUCTION_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			# On prépare nos variables nécessaires pour les messages d'erreurs.
			$_SESSION['modification_fiche_membre']['message_affiche'] = false;# On indique que le message n'a pas encore été affiché.
			$_SESSION['modification_fiche_membre']['message'] = "";# On initialise et on rajoutera par dessus.
			
			$nb_erreur = 0;
			
			# On supprime les chaines interdites.
			$chaines_interdites = array("/<[^>]*>/", "/&lt;/", "/&gt;/", "/&quot;/", "/&gt/", "/&lt/");
		
			$ID_PERSONNE = preg_replace ($chaines_interdites, "", (int)$_POST['form_fiche_membre_id_personne']);
			$NOM = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_nom'])));
			$PRENOM = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_prenom'])));
			$CIVILITE = preg_replace ($chaines_interdites, "", $_POST['form_fiche_membre_civilite']);
			$DATE_NAISSANCE = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_date_naissance']));
			$URL_PHOTO_PRINCIPALE = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_url_photo_principale']));
			$EMAIL = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_email']));
			$TEL_FIXE = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_tel_fixe']));
			$TEL_PORTABLE = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_tel_portable']));
			if($_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				# Si c'est un admin qui modifie la fiche alors on autorise la modification de la réduction.
				$REDUCTION = preg_replace ($chaines_interdites, "", (int)trim($_POST['form_fiche_membre_reduction']));
			}else{
				$REDUCTION = $reduction[0]['REDUCTION'];
			}
			$ADRESSE = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_adresse'])));
			$CP = preg_replace ($chaines_interdites, "", trim((int)$_POST['form_fiche_membre_cp']));
			$VILLE = preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_ville'])));
			$NEWSLETTER = preg_replace ($chaines_interdites, "", $_POST['form_fiche_membre_newsletter']);
			$OFFRES_ANNONCEURS = preg_replace ($chaines_interdites, "", $_POST['form_fiche_membre_offres_annonceurs']);
			
			# Si c'est un prestataire ou un admin qui modifie la fiche alors on prend en compte les informations suivantes.
			if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				$DESCRIPTION = nl2br(preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_description']))));
				$STATUT_PERSONNE = preg_replace ($chaines_interdites, "", $_POST['form_fiche_membre_statut']);
				$DEPARTEMENTS = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_departements']));
				$SIRET = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_siret']));
				$TARIFS = nl2br(preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_tarifs'])));
				$DISTANCE_PRESTATION_MAX = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_distance_prestation_max']));
				$CV_VIDEO = preg_replace ($chaines_interdites, "", trim($_POST['form_fiche_membre_cv_video']));
				$MATERIEL = nl2br(preg_replace ($chaines_interdites, "", ucfirst(trim($_POST['form_fiche_membre_materiel']))));
				
				# On récupère et on traite les rôles choisis.
				$ROLES = "";
				if(!empty($_POST['form_fiche_membre_roles'])){
					foreach($_POST['form_fiche_membre_roles'] as $key=>$role){
						$ROLES.= $role.",";
					}
					$ROLES = preg_replace ($chaines_interdites, "", $ROLES);
				}
				# Peu importe le cas précédent on vérifie les Rôles.
				if(empty($_POST['form_fiche_membre_roles']) || empty($ROLES)){
					$_SESSION['modification_fiche_membre']['message'].= "<span class='orange petit'>Attention: Vous n'avez pas donné vos qualifications, vous ne pouvez donc pas être trouvé lors des recherches d'artistes.</span><br />";
				}
				
			}
			
			# On vérifie l'intégrité des données:
			
			# On commence par vérifier que les champs obligatoire ne soient pas vides.
			if(empty($NOM) || empty($PRENOM) || empty($CIVILITE) || empty($EMAIL)){
				$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>Un des champs obligatoire est vide.<span class='petit'>(Nom, prénom, civilité et email)<span></span><br />";
				$nb_erreur++;
			}
			
			# On vérifie que la civilité soit correcte.
			$civilite_ok = 0;
			foreach($civilites as $key=>$civilite){
				if($civilite['ID_TYPES'] == $CIVILITE){
					$civilite_ok++;
				}
			}
			
			# Si ça vaut 0 alors c'est que le type n'existe pas en BDD donc modification formulaire. Si plus de 1 alors c'est qu'on a deux types identiques en BDD. (impossible)
			if($civilite_ok != 1){
				$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>La civilité sélectionnée n'existe pas.</span><br />";
				$nb_erreur++;
			}
			
			# On vérifie le format de date, on attend un format FR et on veut le transformer en EN.
			if($oCL_date->fx_verif_date($DATE_NAISSANCE, "fr")){
				$DATE_NAISSANCE = $oCL_date->fx_convertir_date($DATE_NAISSANCE);
			}else{
				if($oCL_date->fx_verif_date($DATE_NAISSANCE, "en")){
					# La date est déjà au format en, on ne fait rien.
					
				}else{
					# La date n'est ni au format en, ni fr.
					$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>Date incorrecte. <span class='petit'>Format: 00/00/0000 ou 0000/00/00.</span></span><br />";
					$nb_erreur++;
				}
			}
			
			# On s'occupe de l'image. (photo)
			if(!empty($_FILES) && $_FILES['form_fiche_membre_nouvelle_photo_principale']['error'] == 0){
					$oCL_upload = new CL_upload($_FILES['form_fiche_membre_nouvelle_photo_principale'], "images/uploads/membres", array("png", "gif", "jpg", "jpeg"), 0777, array("image/jpeg", "image/jpeg", "image/png", "image/gif", "image/pjpg", "image/pjpeg"), 200, 200, 2000000);
					
					$new_filename = $ID_PERSONNE."_".date("Y-m-d_H-i-s");
					$ext = explode('.', $_FILES['form_fiche_membre_nouvelle_photo_principale']['name']);
					$extension = $ext[count($ext)-1];
					
					$tab_message = $oCL_upload->fx_upload($_FILES['form_fiche_membre_nouvelle_photo_principale']['name'], $new_filename);
					
					if($tab_message['reussite'] == true){
						$URL_PHOTO_PRINCIPALE =  $oCL_page->getPage('accueil', 'absolu').$tab_message['resultat'];
					}else{
						$_SESSION['modification_fiche_membre']['message'].= $tab_message['resultat'];
						$URL_PHOTO_PRINCIPALE = "";
						$echec_upload = true;
						# On empèche pas la modification de la fiche.
					}
			}# On ne fait rien de plus concernant le téléchargement. 
			# Si il y a un téléchargement il change l'url de la photo, il reste prioritaire.
			
			# On teste si l'adresse e-mail est à un format valide.
			if(!filter_var($EMAIL, FILTER_VALIDATE_EMAIL)){
				
				$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>L'email que vous avez rentré est invalide.</span><br />";
				$nb_erreur++;
			}
			
			# Si c'est un prestataire ou un admin qui modifie la fiche alors on prend en compte les informations suivantes.
			if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
				
				# Au cas où il y ai eu un autre séparateur que la virgule.
				$DEPARTEMENTS = str_replace(array(";", ".", "/", "_", "-"), ",", $DEPARTEMENTS);
				
				# On limite le nombre de départements à celui indiqué par le pack, sauf si admin.
				if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" && strlen($DEPARTEMENTS) > 0){
					$tab_departements = Array();
					$tab_departements = explode(',', $DEPARTEMENTS);
					$DEPARTEMENTS = "";# On met à vide.
					
					for($i = 0;$i < $_SESSION['pack']['NB_DEPARTEMENTS_ALERTE']; $i++){
						# S'il n'y a qu'un seul caractère on met un 0 devant.
						if(strlen((string)$tab_departements[$i]) == 1){
							$DEPARTEMENTS.= "0";
						}else if(strlen((string)$tab_departements[$i]) == 0){
							# Si il n'a pas rentré de département (et n'a donc pas atteint max qu'il peut utiliser) alors on sort.
							break;
						}else if(strlen((string)$tab_departements[$i]) > 2){
							# Si le numéro du département est supérieur à 3 chiffre alors c'est qu'il y a un souci ^^
							$tab_departements[$i] = substr($tab_departements[$i],0,3);
						}
						$DEPARTEMENTS.= trim($tab_departements[$i]).", ";						
					}
					# On vire les deux derniers caractères.
					$DEPARTEMENTS[strlen(trim($DEPARTEMENTS))-1] = "";
					$DEPARTEMENTS = trim($DEPARTEMENTS);
					
					# Vérification finale au cas où.
					if($DEPARTEMENTS == "Array"){
						$DEPARTEMENTS = "";
					}
				}
				
				# On vérifie le siret uniquement s'il n'est pas vide.
				if($SIRET != ""){
					$regex_siret = "/^[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{5}$/";
					if(!preg_match($regex_siret, $SIRET)){
						$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>Le numéro de SIRET est invalide. <span class='petit noir'>(14 chiffres: 123 456 789 12345)</span></span><br />";
						$nb_erreur++;
					}
				}				
				
				# On vire les virgules probables.
				$DISTANCE_PRESTATION_MAX = str_replace(",", ".", $DISTANCE_PRESTATION_MAX);
				
				# Si malgré ça la distance ne correspond pas.
				if(!is_numeric($DISTANCE_PRESTATION_MAX)){
					$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>La distance de prestation maximale est incorrecte. <span class='petit'>(Ex: 250/999.0/99,5)</span></span><br />";
					$nb_erreur++;
				}
				
				
				# On vérifie que le statut soit correct.
				$statut_personne_ok = 0;
				foreach($statuts_personne as $key=>$statut_personne){
					if($statut_personne['ID_TYPES'] == $STATUT_PERSONNE){
						$statut_personne_ok++;
					}
				}
				
				# Si ça vaut 0 alors c'est que le type n'existe pas en BDD donc modification formulaire. Si plus de 1 alors c'est qu'on a deux types identiques en BDD. (impossible)
				if($statut_personne_ok != 1){
					$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>Le statut sélectionné n'existe pas.</span><br />";
					$nb_erreur++;
				}
				
			}
			
			# on récupère les infos de la personne.
			$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
					
			$personne = $oPCS_personne->fx_recuperer_compte_by_ID_PERSONNE($oMSG)->getData(1)->fetchAll();
			
			# On vérifie l'email fournit par rapport à l'email de en BDD.
			if($EMAIL != $personne[0]['EMAIL']){
				# L'email a été modifié, on doit vérifier s'il n'a pas déjà été pris.
				$oMSG->setData('EMAIL', $EMAIL);
				
				$nb_email = $oPCS_personne->fx_compter_email_by_EMAIL($oMSG)->getData(1)->fetchAll();
				
				if($nb_email[0]['nb_email'] != 0){
					# L'email existe déjà.
					$_SESSION['modification_fiche_membre']['message'].= "<span class='orange'>L'email saisi est déjà utilisé.</span><br />";
					$nb_erreur++;
				}
			}
			
			
			# On regarde les erreurs.
			if($nb_erreur == 0){
				# On écrit le message.
				$oMSG->setData('ID_PERSONNE', $ID_PERSONNE);
				$oMSG->setData('NOM', $NOM);
				$oMSG->setData('PRENOM', $PRENOM);
				$oMSG->setData('CIVILITE', $CIVILITE);
				$oMSG->setData('DATE_NAISSANCE', $DATE_NAISSANCE);
				$oMSG->setData('URL_PHOTO_PRINCIPALE', $URL_PHOTO_PRINCIPALE);
				$oMSG->setData('EMAIL', $EMAIL);
				$oMSG->setData('TEL_FIXE', $TEL_FIXE);
				$oMSG->setData('TEL_PORTABLE', $TEL_PORTABLE);
				$oMSG->setData('REDUCTION', $REDUCTION);
				$oMSG->setData('ADRESSE', $ADRESSE);
				$oMSG->setData('CP', $CP);
				$oMSG->setData('VILLE', $VILLE);
				$oMSG->setData('NEWSLETTER', $NEWSLETTER);
				$oMSG->setData('OFFRES_ANNONCEURS', $OFFRES_ANNONCEURS);
				
				# Si c'est un prestataire ou un admin qui modifie la fiche alors on prend en compte les informations suivantes.
				if($_SESSION['compte']['TYPE_PERSONNE'] == "Prestataire" || $_SESSION['compte']['TYPE_PERSONNE'] == "Admin"){
					$oMSG->setData('DESCRIPTION', $DESCRIPTION);
					$oMSG->setData('STATUT_PERSONNE', $STATUT_PERSONNE);
					$oMSG->setData('DEPARTEMENTS', $DEPARTEMENTS);
					$oMSG->setData('SIRET', $SIRET);
					$oMSG->setData('TARIFS', $TARIFS);
					$oMSG->setData('DISTANCE_PRESTATION_MAX', $DISTANCE_PRESTATION_MAX);
					$oMSG->setData('CV_VIDEO', $CV_VIDEO);
					$oMSG->setData('MATERIEL', $MATERIEL);		
					$oMSG->setData('ROLES', $ROLES);					
				}
				
				# On spécifie qui est-ce qui modifie la fiche:
				$oMSG->setData('TYPE_PERSONNE', $_SESSION['compte']['TYPE_PERSONNE']);		

				$oPCS_personne->fx_maj_fiche_personnelle_selon_TYPE_PERSONNE($oMSG);

				if($echec_upload){
					$_SESSION['modification_fiche_membre']['message'].= "<span class='orange'>Le téléchargement de l'image a échoué.<br />Les autres informations ont été correctement enregistrées.</span><br />";
				}else{
					$_SESSION['modification_fiche_membre']['message'].= "<span class='valide'>Les modifications ont bien été effectuées.</span><br />";
				}
				
				# On modifie les informations de session si ce n'est pas un admin.
				if($_SESSION['compte']['TYPE_PERSONNE'] != "Admin"){
					
					$_SESSION['compte']['PSEUDO'] = $personne[0]['PSEUDO'];
					$_SESSION['compte']['NOM'] = $personne[0]['NOM'];
					$_SESSION['compte']['PRENOM'] = $personne[0]['PRENOM'];
					$_SESSION['compte']['CIVILITE'] = $personne[0]['CIVILITE'];
					$_SESSION['compte']['EMAIL'] = $personne[0]['EMAIL'];
				}
				# On redirige.
				if($_SESSION['page_actuelle'] == "modifier_fiche_membre"){
					header('Location: '.$oCL_page->getPage('modifier_fiche_membre', 'absolu')."?id_personne=".$ID_PERSONNE);
				}else{
					header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu'));
				}
				
			}else{
				$_SESSION['modification_fiche_membre']['message'].= "<span class='alert'>Aucune modification n'a été effectuée.</span><br />";

				if($_SESSION['page_actuelle'] == "modifier_fiche_membre"){
					header('Location: '.$oCL_page->getPage('modifier_fiche_membre', 'absolu')."?id_personne=".$ID_PERSONNE);
				}else{
					header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu'));
				}
				
			}
		# Fin de la vérification de la validité de l'ID_PERSONNE par rapport au mdp.
		}else{
			header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu'));
		}
	}else{
		# Si pas de POST.
		header('Location: '.$oCL_page->getPage($_SESSION['page_actuelle'], 'absolu'));
	}
}else{
	# Si l'internaute n'est pas connecté.
	header('Location: '.$oCL_page->getPage('accueil', 'absolu'));
}
?>