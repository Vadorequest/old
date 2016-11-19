<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_personne.php';
require_once 'couche_metier/VIEW_personne.php';

# Toutes la gestion des affichages/modification des IP est géré depuis le MPG_personne et la VIEW_personne car ils sont intimement liés.

class PCS_personne{

	private $oCAD;
	private $oMPG_personne;
	private $oVIEW_personne;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_personne = new MPG_personne();
		$this->oVIEW_personne = new VIEW_personne();
	}
	
	public function fx_compter_pseudo_by_PSEUDO($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_PSEUDO($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_email_by_EMAIL($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_EMAIL($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_compte_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_compte_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_compte_min_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_compte_min_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_pseudo_by_PSEUDO_et_MDP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_pseudo_by_PSEUDO_et_MDP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_personne_by_ID_PERSONNE_et_MDP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_personne_by_ID_PERSONNE_et_MDP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_compte_by_PSEUDO($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_all_by_PSEUDO($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_compte_by_PSEUDO_et_EMAIL($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_all_by_PSEUDO_et_EMAIL($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_by_EMAIL_et_CLE_ACTIVATION($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_by_EMAIL_et_CLE_ACTIVATION($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_IPs_by_ID_IP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_ID_IP_by_ID_IP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_IPs_by_IP_COOKIE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_ID_IP_by_IP_COOKIE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_comptes_non_actives($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_by_CLE_ACTIVATION($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_infos_by_ID_IP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_by_ID_IP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_infos_by_IP_COOKIE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_by_IP_COOKIE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_membres($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_tous_membres($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_tous_membres($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_tous_membres($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_membres_by_LIMIT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_tous_membres_by_LIMIT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_comptes_non_actives($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_by_CLE_ACTIVATION($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_comptes_by_PERSONNE_SUPPRIMEE_et_VISIBILITE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_comptes_by_PERSONNE_SUPPRIMEE_et_VISIBILITE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_comptes_supprimes($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_comptes_supprimes_par_utilisateur($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_REDUCTION_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_REDUCTION_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_personne_by_ID_PERSONNE_et_EMAIL_et_MDP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_ID_PERSONNE_by_ID_PERSONNE_et_EMAIL_et_MDP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_filleuls_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_filleuls_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_filleuls_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_filleuls_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_adresse_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_adresse_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_PSEUDO_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_PSEUDO_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_compte_by_ID_FACEBOOK($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_all_by_ID_FACEBOOK($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_PARRAIN($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_tous_PARRAIN($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_connectes($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_personne->SELECT_COUNT_connectes($oMSG));
		
		return $oMSG;
	}
	// -------------------- Vues
	
	public function fx_recuperer_toutes_ip_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_toutes_ip_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_date_creation_compte_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_date_creation_compte_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_dernieres_connexions_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_dernieres_connexions_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	
	public function fx_recuperer_personne_by_ID_CONTRAT_et_nonTYPE_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_personne_by_ID_CONTRAT_et_nonTYPE_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_personne_by_ID_CONTRAT_et_TYPE_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_personne_by_ID_CONTRAT_et_TYPE_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_prestataires_lors_annulation_contrat($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_prestataires_lors_annulation_contrat($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_personne_par_criteres($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_COUNT_ID_PERSONNE_par_criteres($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_personne_par_criteres($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_personnes_par_criteres($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_date_creation_compte($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_date_creation_compte($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_artistes_premium($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_artistes_premium($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_toutes_identites_personnes_by_TYPE_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_personne->SELECT_toutes_identites_personnes_by_TYPE_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	// ------------------------------------------ Insertions/Modifications
	
	public function fx_creer_compte($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->INSERT_all($oMSG), true);# On récupère l'ID crée
		
		return $oMSG;
	}
	
	public function fx_creer_IP($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->INSERT_IP($oMSG));
		
		return $oMSG;
	}
	
	public function fx_valider_compte($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UPDATE_activation_compte($oMSG));
		
		return $oMSG;
	}
	
	public function fx_lier_IP_et_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->INSERT_liaison_IP_et_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_rang($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UPDATE_TYPE_PERSONNE_by_PSEUDO($oMSG));
		
		return $oMSG;
	}
	
	public function fx_bannir_personne($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UPDATE_validite_compte_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_maj_fiche_personnelle_selon_TYPE_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		
		if($oMSG->getData('TYPE_PERSONNE') == 'Admin' || $oMSG->getData('TYPE_PERSONNE') == 'Prestataire'){
			$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UPDATE_fiche_personnelle_complete_by_ID_PERSONNE($oMSG));
		}else if($oMSG->getData('TYPE_PERSONNE') == 'Organisateur'){
			$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UPDATE_fiche_personnelle_basique_by_ID_PERSONNE($oMSG));
		}else{
			return "Erreur: Type de la personne non défini.";
		}
		
		
		return $oMSG;
	}
	
	public function fx_changer_mdp($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_MDP_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_supprimer_infos_perso_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_infos_perso_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_REDUCTION_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_REDUCTION_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_REDUCTION_by_ID_PARRAIN($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_REDUCTION_by_ID_PARRAIN($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_ANNONCES_VISITEES_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_modifier_DERNIERE_ACTIVITE_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_personne->UDPATE_DERNIERE_ACTIVITE_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	
}

?>