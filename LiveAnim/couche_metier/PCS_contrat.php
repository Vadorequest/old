<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_contrat.php';
require_once 'couche_metier/MPG_contrat_personne.php';
require_once 'couche_metier/VIEW_contrat.php';

class PCS_contrat{

	private $oCAD;
	private $oMPG_contrat;
	private $oVIEW_contrat;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_contrat = new MPG_contrat();
		$this->oMPG_contrat_personne = new MPG_contrat_personne();
		$this->oVIEW_contrat = new VIEW_contrat();
	}
	
	// ----------------------- MPG_contrat --------------------------
	
	public function fx_compter_contrat_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_contrat->SELECT_COUNT_ID_CONTRAT_by_ID_ANNONCE($oMSG));
		
		return $oMSG;
	}
	
	
	public function fx_compter_ID_CONTRAT_by_ID_CONTRAT_et_DESTINATAIRE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_contrat->SELECT_COUNT_ID_CONTRAT_by_ID_CONTRAT_et_DESTINATAIRE($oMSG));
		
		return $oMSG;
	}
	
	
	public function fx_compter_tous_contrats_by_STATUT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_contrat->SELECT_COUNT_by_STATUT($oMSG));
		
		return $oMSG;
	}
	
	
	public function fx_compter_tous_contrats($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_contrat->SELECT_COUNT_all($oMSG));
		
		return $oMSG;
	}
	
	
	
	// ----------------------- MPG_contrat_personne --------------------------
	
	public function fx_compter_contrat_by_ID_CONTRAT_et_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_contrat_personne->SELECT_COUNT_ID_CONTRAT_by_ID_CONTRAT_et_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_destinataire_by_ID_CONTRAT_et_nonID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_contrat_personne->SELECT_destinataire_by_ID_CONTRAT_et_nonID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	
	// ----------------------- VIEW_contrat --------------------------
	
	public function fx_compter_contrat_by_ID_ANNONCE_et_ID_PERSONNE_et_condition($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_COUNT_nb_contrat_by_ID_PERSONNE_et_ID_ANNONCE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_contrat_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_COUNT_nb_contrat_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_contrat_min_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_contrat_min_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_contrats_courants_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_COUNT_contrats_courants_min_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_contrats_courants_min_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_contrats_courants_min_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_contrat_by_ID_CONTRAT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_contrat_by_ID_CONTRAT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_departement_annonce_lors_annulation_contrat($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_departement_annonce_lors_annulation_contrat($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_contrats_by_STATUT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_by_STATUT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_tous_contrats($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_all($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_prestations_effectues($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_COUNT_prestations($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_prestations_min($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_prestations_min($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_moy_evaluation_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_MOY_evaluation_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_calculer_gains_contrats_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_contrat->SELECT_SUM_gains_contrats_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	// ---------------------------------------------------------------- ActionRows ----------------------------------------------------------------------
	
	// ----------------------- MPG_contrat ----------------------------
	
	public function fx_creer_contrat($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_contrat->INSERT($oMSG), true);
		
		return $oMSG;
	}
	
	public function fx_maj_contrat($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_contrat->UPDATE_refus($oMSG));
		
		return $oMSG;
	}
	
	public function fx_maj_STATUT_by_ID_CONTRAT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_contrat->UPDATE_statut_by_ID_CONTRAT($oMSG));
		
		return $oMSG;
	}
	
	public function fx_valider_contrat($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_contrat->UPDATE_validation($oMSG));
		
		return $oMSG;
	}
	
	public function fx_maj_DATE_EVALUATION_by_ID_CONTRAT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_contrat->UPDATE_DATE_EVALUATION_by_ID_CONTRAT($oMSG));
		
		return $oMSG;
	}
	
	// ---------------------- MPG_contrat_personne --------------------
	
	public function fx_lier_contrat($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_contrat_personne->INSERT($oMSG));
		
		return $oMSG;
	}
}