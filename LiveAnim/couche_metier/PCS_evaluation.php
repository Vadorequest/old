<?php
require_once 'couche_donnees/CAD.php';
require_once 'couche_metier/MPG_evaluation.php';

class PCS_evaluation{

	private $oCAD;
	private $oMPG_evaluation;

	public function __construct() {
		$this->oCAD = new CAD();
		$this->oMPG_evaluation = new MPG_evaluation();
	}
	
	// ----------------------- MPG_evaluation --------------------------
	
	public function fx_compter_evaluation_by_ID_CONTRAT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_evaluation->SELECT_COUNT_ID_EVALUATION_by_ID_CONTRAT($oMSG));
		
		return $oMSG;
	}
	
	
	public function fx_recuperer_evaluations_by_ID_CONTRAT($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_evaluation->SELECT_evaluations_by_ID_CONTRAT($oMSG));
		
		return $oMSG;
	}
	
	
	public function fx_compter_evaluation_by_ID_CONTRAT_et_TYPE_EVALUATION($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_evaluation->SELECT_COUNT_ID_EVALUATION_by_ID_CONTRAT_et_TYPE_EVALUATION($oMSG));
		
		return $oMSG;
	}
	
	
	public function fx_recuperer_evaluations_by_ID_CONTRAT_et_TYPE_EVALUATION($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->GetRows($this->oMPG_evaluation->SELECT_evaluations_by_ID_CONTRAT_et_TYPE_EVALUATION($oMSG));
		
		return $oMSG;
	}

	// ---------------------------------------------------------------- ActionRows ----------------------------------------------------------------------
		
	public function fx_creer_evaluation($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_evaluation->INSERT($oMSG), true);
		
		return $oMSG;
	}
	
	public function fx_maj_evaluation($oMSG){
		$this->oCAD = new CAD();
		$oMSG = $this->oCAD->ActionRows($this->oMPG_evaluation->UPDATE($oMSG), true);
		
		return $oMSG;
	}
	
}