<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_annonce.php';
require_once 'couche_metier/MPG_departement.php';
require_once 'couche_metier/VIEW_annonce.php';

class PCS_annonce{

	private $oCAD;
	private $oMPG_annonce;
	private $oMPG_departement;
	private $oVIEW_annonce;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_annonce = new MPG_annonce();
		$this->oMPG_departement = new MPG_departement();
		$this->oVIEW_annonce = new VIEW_annonce();
	}
	
	// ----------------------- MPG_annonce --------------------------
	
	public function fx_recuperer_annonce_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_all_by_ID_ANNONCE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_annonce_by_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_COUNT_ID_ANNONCE_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_toutes_annonces_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_COUNT_ID_ANNONCE_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_toutes_annonces_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_toutes_annonces_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_annonces_futures_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_COUNT_ID_ANNONCE_futures_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_toutes_annonces_futures_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_annonces_futures_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_annonces_par_criteres($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_COUNT_annonces_par_criteres($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_annonce_valide_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_annonce_valide_by_ID_ANNONCE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_toutes_annonces($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_COUNT_toutes_annonces($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_tous_goldlive($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_annonce->SELECT_COUNT_tous_goldlive($oMSG));
		
		return $oMSG;
	}
	
	// ----------------------- MPG_departement ----------------------
	
	public function fx_recuperer_tous_departements($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_departement->SELECT_all($oMSG));
		
		return $oMSG;
	}
	
	
	// --------------------- VIEW_annonce --------------------------
	
	public function fx_recuperer_min_annonce_by_VISIBLE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_annonce->SELECT_min_by_VISIBLE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_annonces_par_criteres($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_annonce->SELECT_annonces_par_criteres($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_annonce_complete_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_annonce->SELECT_annonce_complete_by_ID_ANNONCE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_toutes_annonces_min($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_annonce->SELECT_all_min($oMSG));
		
		return $oMSG;
	}
	
	public function fx_compter_annonces_by_GOLDLIVE_et_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_annonce->SELECT_COUNT_annonces_by_GOLDLIVE_et_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_recuperer_annonces_by_GOLDLIVE_et_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_annonce->SELECT_annonces_by_GOLDLIVE_et_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	public function fx_calculer_couts_annonces_goldlive_by_ID_PERSONNE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oVIEW_annonce->SELECT_SUM_couts_annonces_goldlive_by_ID_PERSONNE($oMSG));
		
		return $oMSG;
	}
	
	// ---------------------------------------------------------------- ActionRows ----------------------------------------------------------------------
	
	// ----------------------- MPG_annonce ----------------------------
	
	public function fx_creer_annonce($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_annonce->INSERT($oMSG), true);
		
		return $oMSG;
	}
	
	public function fx_modifier_annonce_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_annonce->UPDATE_by_ID_ANNONCE($oMSG), true);
		
		return $oMSG;
	}
	
	public function fx_modifier_goldlive_by_ID_ANNONCE($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_annonce->UPDATE_goldlive_by_ID_ANNONCE($oMSG), true);
		
		return $oMSG;
	}
}